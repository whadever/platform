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
}