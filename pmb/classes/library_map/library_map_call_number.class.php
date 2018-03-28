<?php

// +-------------------------------------------------+
// | 2002-2011 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: exploded_search_segment.class.php,v 1.1 2017-08-11 16:10:01 tsamson Exp $
if (stristr($_SERVER['REQUEST_URI'], ".class.php"))
	die("no access");

require_once ($class_path . '/library_map/library_map_base.class.php');
class library_map_call_number extends library_map_base {
	protected $type = "call_number";
	private $min_call_number = '';
	private $max_call_number = '';

	protected function get_properties($graph_id){
		parent::get_properties($graph_id);
		$this->min_call_number = $this->get_min_call_number();
		$this->max_call_number = $this->get_max_call_number();
	}

	/**
	 * TODO vrais setters à faire ici, qui modifieront la valeur en attribut etc
	 *
	 * @return string
	 */
	public function set_min_call_number($call_number){
	}

	public function set_max_call_number($call_number){
	}

	public function get_min_call_number(){
		return $this->dom_element->getAttribute('min_call_number');
	}

	public function get_max_call_number(){
		return $this->dom_element->getAttribute('max_call_number');
	}
}