<?php

require_once($class_path.'/library_map/library_map_base.class.php');

class library_map_text extends library_map_base {
  protected $type = "Text";
  private $text_value = '';

  protected function get_properties($id) {
      parent::get_properties($id);
      $this->text_value=$this->get_text();
  }
  private function get_text () {
    return $this->get_dom_element()->getAttribute('nodeValue');
  }
}
