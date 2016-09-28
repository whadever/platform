<?php

require 'lib/XeroOAuth.php';

//require 'con.php';

/**
 * Define for file includes
 */
define ( 'BASE_PATH', dirname(__FILE__) );

/**
 * Define which app type you are using:
 * Private - private app method
 * Public - standard public app method
 * Public - partner app method
 */
define ( "XRO_APP_TYPE", "Public" );

/**
 * Set a user agent string that matches your application name as set in the Xero developer centre
 */
$useragent = "Xero-OAuth-PHP Public";

/**
 * Set your callback url or set 'oob' if none required
 * Make sure you've set the callback URL in the Xero Dashboard
 * Go to https://api.xero.com/Application/List and select your application
 * Under OAuth callback domain enter localhost or whatever domain you are using.
 */
if(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS'])){
	$protocol = "https://";
}else{
	$protocol = "http://";
}

define ( "OAUTH_CALLBACK", $protocol.$_SERVER['HTTP_HOST'].'/jobcosting/xero/public.php' );

/**
 * Application specific settings
 * Not all are required for given application types
 * consumer_key: required for all applications
 * consumer_secret: for partner applications, set to: s (cannot be blank)
 * rsa_private_key: application certificate private key - not needed for public applications
 * rsa_public_key: application certificate public cert - not needed for public applications
 */

//include 'tests/testRunner.php';

$signatures = array (
		'consumer_key' => 'QTE1VAMUHFZBD8XUY6GQUABKCMY2TD',
		'shared_secret' => 'H7G4WLSGGLE9ICIWGAECW4OZSKUNBA',
		// API versions
		'core_version' => '2.0',
		'payroll_version' => '1.0',
		'file_version' => '1.0' 
);


$XeroOAuth = new XeroOAuth ( array_merge ( array (
		'application_type' => XRO_APP_TYPE,
		'oauth_callback' => OAUTH_CALLBACK,
		'user_agent' => $useragent 
), $signatures ) );

$initialCheck = $XeroOAuth->diagnostics ();
$checkErrors = count ( $initialCheck );
if ($checkErrors > 0) {
	// you could handle any config errors here, or keep on truckin if you like to live dangerously
	foreach ( $initialCheck as $check ) {
		echo 'Error: ' . $check . PHP_EOL;
	}
} else {
	
	$here = XeroOAuth::php_self ();
	session_start ();
	$oauthSession = retrieveSession ();
		
	if (isset ( $_REQUEST ['oauth_verifier'] )) {
		$XeroOAuth->config ['access_token'] = $_SESSION ['oauth'] ['oauth_token'];
		$XeroOAuth->config ['access_token_secret'] = $_SESSION ['oauth'] ['oauth_token_secret'];
		
		$code = $XeroOAuth->request ( 'GET', $XeroOAuth->url ( 'AccessToken', '' ), array (
				'oauth_verifier' => $_REQUEST ['oauth_verifier'],
				'oauth_token' => $_REQUEST ['oauth_token'] 
		) );
		
		if ($XeroOAuth->response ['code'] == 200) {
			
			$response = $XeroOAuth->extract_params ( $XeroOAuth->response ['response'] );
			$session = persistSession ( $response );
			
			unset ( $_SESSION ['oauth'] ); 
			//header ( "Location: {$here}" );
			/* getting the info to create contact and PO */
			$oauthSession = retrieveSession ();
			$XeroOAuth->config['access_token']  = $oauthSession['oauth_token'];
			$XeroOAuth->config['access_token_secret'] = $oauthSession['oauth_token_secret'];
			$XeroOAuth->config['session_handle'] = $oauthSession['oauth_session_handle'];
			
			$info = unserialize(file_get_contents($_SERVER['DOCUMENT_ROOT']."/../xero_files/".$_SESSION['file']));
			
			$response = $XeroOAuth->request('GET', $XeroOAuth->url('Contacts', 'core').'/'.$info['Contact']['ContactNumber'], array());
			
			if ($XeroOAuth->response['code'] != 200) {
				
				/* contact was not found. create */
		             
				$xml  = ArrayToXML::toXML(array($info['Contact']), 'Contacts');
				
				$xml  = trim(substr($xml, (stripos($xml, ">")+1)));
				
				$response = $XeroOAuth->request('POST', $XeroOAuth->url('Contacts', 'core'), array(), $xml);
				
				if ($XeroOAuth->response['code'] != 200) {
		             
					outputError($XeroOAuth);
					
			    } 
					
			}
			
			/* creating PO */
			
			$xml  = ArrayToXML::toXML($info['PurchaseOrder'], 'PurchaseOrder');
			
			$xml  = trim(substr($xml, (stripos($xml, ">")+1)));
			
			$response = $XeroOAuth->request('POST', $XeroOAuth->url('PurchaseOrder', 'core'), array(), $xml);
				
			if ($XeroOAuth->response['code'] == 200) {
				
				session_destroy();
				 
				header('Location: '.$info['redirect_url']);
				
			} else {
			   outputError($XeroOAuth);
			}
			
			
			
		} else {
			outputError ( $XeroOAuth );
		}
		// start the OAuth dance
	} elseif (isset ($_REQUEST ['file']) && isset ( $_REQUEST ['authenticate'] ) || isset ( $_REQUEST ['authorize'] )) {
		$params = array (
				'oauth_callback' => OAUTH_CALLBACK 
		);
		
		$response = $XeroOAuth->request ( 'GET', $XeroOAuth->url ( 'RequestToken', '' ), $params );
		
		if ($XeroOAuth->response ['code'] == 200) {
			
			$scope = "";
			// $scope = 'payroll.payrollcalendars,payroll.superfunds,payroll.payruns,payroll.payslip,payroll.employees,payroll.TaxDeclaration';
			if ($_REQUEST ['authenticate'] > 1)
				$scope = 'payroll.employees,payroll.payruns,payroll.timesheets';
			
			//print_r ( $XeroOAuth->extract_params ( $XeroOAuth->response ['response'] ) );
			$_SESSION ['oauth'] = $XeroOAuth->extract_params ( $XeroOAuth->response ['response'] );
			
			$authurl = $XeroOAuth->url ( "Authorize", '' ) . "?oauth_token={$_SESSION['oauth']['oauth_token']}&scope=" . $scope;
			//echo '<p>To complete the OAuth flow follow this URL: <a href="' . $authurl . '">' . $authurl . '</a></p>';
			$_SESSION ['file'] = $_REQUEST ['file'];
			header('Location: '.$authurl);
			exit;
		} else {
			outputError ( $XeroOAuth );
		}
	}
	session_destroy();
	//testLinks ();
}

/**
 * Persist the OAuth access token and session handle somewhere
 * In my example I am just using the session, but in real world, this is should be a storage engine
 *
 * @param array $params the response parameters as an array of key=value pairs
 */
function persistSession($response)
{
	if (isset($response)) {
		$_SESSION['access_token']       = $response['oauth_token'];
		$_SESSION['oauth_token_secret'] = $response['oauth_token_secret'];
		if(isset($response['oauth_session_handle']))  $_SESSION['session_handle']     = $response['oauth_session_handle'];
	} else {
		return false;
	}

}

/**
 * Retrieve the OAuth access token and session handle
 * In my example I am just using the session, but in real world, this is should be a storage engine
 *
 */
function retrieveSession()
{
	if (isset($_SESSION['access_token'])) {
		$response['oauth_token']            =    $_SESSION['access_token'];
		$response['oauth_token_secret']     =    $_SESSION['oauth_token_secret'];
		$response['oauth_session_handle']   =    $_SESSION['session_handle'];
		return $response;
	} else {
		return false;
	}

}

function outputError($XeroOAuth)
{
	echo 'Error: ' . $XeroOAuth->response['response'] . PHP_EOL;
	pr($XeroOAuth);
}

function pr($obj)
{

	if (!is_cli())
		echo '<pre style="word-wrap: break-word">';
	if (is_object($obj))
		print_r($obj);
	elseif (is_array($obj))
		print_r($obj);
	else
		echo $obj;
	if (!is_cli())
		echo '</pre>';
}

function is_cli()
{
	return (PHP_SAPI == 'cli' && empty($_SERVER['REMOTE_ADDR']));
}

class ArrayToXML
{
    /**
     * The main function for converting to an XML document.
     * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
     *
     * @param array $data
     * @param string $rootNodeName - what you want the root node to be - defaultsto data.
     * @param SimpleXMLElement $xml - should only be used recursively
     * @return string XML
     */
    public static function toXML($data, $rootNodeName = 'ResultSet', &$xml=null)
    {
        // turn off compatibility mode as simple xml throws a wobbly if you don't.
        if (ini_get('zend.ze1_compatibility_mode') == 1)
        {
            ini_set('zend.ze1_compatibility_mode', 0);
        }
        if (is_null($xml))
        {
            $xml            = simplexml_load_string("<$rootNodeName />");
            $rootNodeName   = rtrim($rootNodeName, 's');
        }
        // loop through the data passed in.
        foreach ($data as $key => $value)
        {
            // no numeric keys in our xml please!
            $numeric = 0;
            if (is_numeric($key))
            {
                $numeric    = 1;
                $key        = $rootNodeName;
            }

            // delete any char not allowed in XML element names
            $key    = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);

            // if there is another array found recursively call this function
            if (is_array($value))
            {
                $node = (ArrayToXML::isAssoc($value) || $numeric) ? $xml->addChild($key) : $xml;
                // recursive call.
                if ($numeric)
                {
                    $key    = 'anon';
                }
                ArrayToXML::toXml($value, $key, $node);
            }
            else
            {
                // add single node.
                $xml->$key  = $value;
            }
        }
        // pass back as XML
        return $xml->asXML();

        // if you want the XML to be formatted, use the below instead to return the XML
        // $doc = new DOMDocument('1.0');
        // $doc->preserveWhiteSpace = false;
        // $doc->loadXML($xml->asXML());
        // $doc->formatOutput = true;
        // return $doc->saveXML();
    }

    /**
     * Convert an XML document to a multi dimensional array
     * Pass in an XML document (or SimpleXMLElement object) and this recrusively loops through and builds a representative array
     *
     * @param string $xml - XML document - can optionally be a SimpleXMLElement object
     * @return array ARRAY
     */
    public static function toArray($xml)
    {
        if (is_string($xml))
        {
            $xml    = new SimpleXMLElement($xml);
        }
        $children   = $xml->children();
        if (!$children)
        {
            return (string) $xml;
        }
        $arr    = array();
        foreach ($children as $key => $node)
        {
            $node   = ArrayToXML::toArray($node);

            // support for 'anon' non-associative arrays
            if ($key == 'anon')
            {
                $key    = count($arr);
            }

            // if the node is already set, put it into an array
            if (array_key_exists($key, $arr) &&  isset($arr[$key]))
            {
                if (!is_array($arr[$key]) || !array_key_exists(0,$arr[$key]) ||  (array_key_exists(0,$arr[$key]) && ($arr[$key][0] == null)))
                {
                    $arr[$key]  = array($arr[$key]);
                }
                $arr[$key][] = $node;
            }
            else
            {
                $arr[$key]  = $node;
            }
        }
        return $arr;
    }

    // determine if a variable is an associative array
    public static function isAssoc($array)
    {
        return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));
    }
}