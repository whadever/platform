<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Configuration options for Xero private application
 */

$config = array(
	'consumer'	=> array(
    	'key'		=> 'ZGEEB7LOJCZTASGB2CCHVRPPYBK7RJ',
    	'secret'	=> 'OJRX9VLO5RKJANT3WEUHVCRYYSRN2P'
    ),
    'certs'		=> array(
    	'private'  	=> APPPATH.'certs/privatekey.pem',
    	'public'  	=> APPPATH.'certs/publickey.cer'
    ),
    'format'    => 'json'
);