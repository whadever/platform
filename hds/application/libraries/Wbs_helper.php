<?php
Class Wbs_helper{
/**
 * convert any date format to mysql date like yyyy-mm-dd
 * 
 * @param type $date
 * @return type
 */
    function to_mysql_date($date){
        return date('Y-m-d',  strtotime($date));
    }
/**
 * convert any date format to mysql datetime like yyyy-mm-dd
 * 
 * @param type $date
 * @return type
 */
    function to_mysql_datetime($date){
        // print 'dt='.$date;exit;
        list($dt,$time) = explode(' ', $date);
        return date('Y-m-d',  strtotime($dt)) . ' ' . $time;
    }
    
    /**
     * convert any date to human readble date like dd/mm/yyyy
     * 
     * @param type $date
     * @return human readable date
     */
    function to_report_date($date){
        return date('d-m-Y',  strtotime($date));
    }        
    /**
     * convert any date to human readble datetime like dd/mm/yyyy
     * 
     * @param type $date
     * @return human readable date
     */
    function to_report_datetime($date){
        return date('d-m-Y H:i',  strtotime($date));
    }    
    
    
    function pp($data=NULL){
      //  print_r('<pre>' . $data . '</pre>',TRUE);
      echo '<pre>';
      print_r($data);
      echo '</pre>';
    }
    
    function pretty_print($data){
      echo '<pre>';
      print_r($data);
      echo '</pre>';
    }
    
    
function mbs_item_list($variables) {
    $items = $variables['items'];
    $title = $variables['title'];
    $type = $variables['type'];
    $attributes = $variables['attributes'];

  // Only output the list container and title, if there are any list items.
  // Check to see whether the block title exists before adding a header.
  // Empty headers are not semantic and present accessibility challenges.
  $output = '<div class="item-list">';
  if (isset($title) && $title !== '') {
    $output .= '<h3>' . $title . '</h3>';
  }

  if (!empty($items)) {
    $output .= "<$type" . $this->mbs_attributes($attributes) . '>';
    $num_items = count($items);
    $i = 0;
    foreach ($items as $item) {
      $attributes = array();
      $children = array();
      $data = '';
      $i++;
      if (is_array($item)) {
        foreach ($item as $key => $value) {
          if ($key == 'data') {
            $data = $value;
          }
          elseif ($key == 'children') {
            $children = $value;
          }
          else {
            $attributes[$key] = $value;
          }
        }
      }
      else {
        $data = $item;
      }
      if (count($children) > 0) {
        // Render nested list.
        $data .= mbs_item_list(array('items' => $children, 'title' => NULL, 'type' => $type, 'attributes' => $attributes));
      }
      if ($i == 1) {
        $attributes['class'][] = 'first';
      }
      if ($i == $num_items) {
        $attributes['class'][] = 'last';
      }
      $output .= '<li' . $this->mbs_attributes($attributes) . '>' . $data . "</li>\n";
    }
    $output .= "</$type>";
  }
  $output .= '</div>';
  return $output;
}
    
    function mbs_attributes(array $attributes = array()) {
      foreach ($attributes as $attribute => &$data) {
        $data = implode(' ', (array) $data);
        $data = $attribute . '="' . $this->check_plain($data) . '"';
      }
      return $attributes ? ' ' . implode(' ', $attributes) : '';
    }   
    
    function check_plain($text) {
      return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    } 
    
    /**
     * date difference between two dates, date1 is smaller than date2
     * 
     * @param type $date1 
     * @param type $date2
     * @return number of year; example 1/2/3
     */
    function date_diff($date1,$date2){
                $diff = abs(strtotime($date2) - strtotime($date1));
                $years = ceil($diff / (365*60*60*24));
              //  $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
               // $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                return $years;
               // printf("%d years, %d months, %d days\n", $years, $months, $days);        
    }
    
    function date_diff2($date1,$date2){
//                echo 'date2=' . $date2;
//                echo '<br>date1=' . $date1;
//                
                $diff = abs(strtotime($date2) - strtotime($date1));
                $years = floor($diff / (365*60*60*24));
                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                $months_only = $months + ($years * 12);
                $days_only = $days + ($months_only * 30);
                
                return array('years'=>$years,'months'=>$months,'days'=>$days,'months_only'=>$months_only,'days_only'=>$days_only);
               // printf("%d years, %d months, %d days\n", $years, $months, $days);        
    }
    
    function to_show_month_date($date){
        return date('M Y',  strtotime($date));
    }  
    function make_list_pdf($data, $a,  $b)
    {
            
                require_once('tcpdf/tcpdf.php');
                $ci =& get_instance();
               // $ci->load->helper('language');
                
                

                // create new PDF document
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                // set document information
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Xprocoders');
                //$pdf->SetTitle($this->lang->line('Marketing and Branding Solutions'));
                //$pdf->SetSubject($this->lang->line('Employee Details'));
                $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
				

                // set default header data
                $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE1.' ', PDF_HEADER_STRING1, array(0,0,0), array(0,64,128));
               
                $pdf->setFooterData(array(0,64,0), array(0,64,128));

                // set header and footer fonts
		//$pdf->SetFont('aealarabiya', '', 18);
                 
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                
		
               
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

                // set default monospaced font
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                // set margins
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

                // set auto page breaks
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

                // set image scale factor
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
				
                $lg = Array();
                $lg['a_meta_charset'] = 'UTF-8';
                $lg['a_meta_dir'] = 'rtl';
                $lg['a_meta_language'] = 'fa';
                $lg['w_page'] = 'page';
                $pdf->setLanguageArray($lg);

                // set some language-dependent strings (optional)
               // if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                        //require_once(dirname(__FILE__).'/lang/eng.php');
                       // $pdf->setLanguageArray($l);                        
                //}

                // ---------------------------------------------------------

                // set default font subsetting mode
                $pdf->setFontSubsetting(true);

                // Set font
                // dejavusans is a UTF-8 Unicode font, if you only need to
                // print standard ASCII chars, you can use core fonts like
                // helvetica or times to reduce file size.
                
				
				 // Restore RTL direction
                
                
                    $pdf->setRTL(false);
                    $pdf->SetFont('dejavusans', '', 10, '', true);
                

                // Add a page
                // This method has several options, check the source code documentation for more information.
                $pdf->AddPage();
				

                // set text shadow effect
                $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

                // Set some content to print
                $html = $data;
                 
               

                // Print text using writeHTMLCell()
                $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

                // ---------------------------------------------------------

                // Close and output PDF document
                // This method has several options, check the source code documentation for more information.
                $pdf->Output('develompents.pdf', 'I');
        
    }
    
    
}

