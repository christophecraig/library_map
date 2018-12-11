<?php

// +-------------------------------------------------+
// | 2002-2011 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: exploded_search_segment.class.php,v 1.1 2017-08-11 16:10:01 tsamson Exp $
if (stristr($_SERVER['REQUEST_URI'], ".class.php"))
	die("no access");

require_once ($class_path . '/library_map/library_map_location.class.php');
require_once ($class_path . '/library_map/library_map_section.class.php');
require_once ($class_path . '/library_map/library_map_call_number.class.php');
require_once ($class_path . '/library_map/library_map_text.class.php');

/**
 * Classe
 * @class library_map_base
 */
class library_map_base {
	protected $dom_element;
	protected $parent;
	private $children = [];
	private $id = 0;
	private $label = null;
	private $graph = null;
	private $graph_id = "1";
	protected $type = "base";

	/**
	 *
	 * @param DOMElement $dom_element
	 * @param library_map_base $parent
	 * @param string $id
	 * @param DOMDocument $document
	 */
	public function __construct($dom_element, $parent, $graph_id = '0', $document){
		$this->dom_element = $dom_element;
		$this->parent = $parent;
		$this->graph = $document;
		$this->get_properties($graph_id);
	}

	/**
	 * Generates and assigns unique id for this instance
	 *
	 * @param string $id
	 */
	protected function get_properties($graph_id){
		if ($this->parent) {
			$this->graph_id = $this->parent->graph_id . "." . $graph_id;
		} else {
			$this->graph_id = $graph_id;
		}
		$this->dom_element->setAttribute('graphId', $this->graph_id);
		$this->create_children();
	}

	/**
	 * Adds children to $this->children array
	 *
	 * This function creates children via the Graph method named createNode then adds them too $this->children array
	 *
	 * @return null
	 */
	private function create_children(){
		if ($this->dom_element->childNodes === null) {
			return null;
		}
		
		$child_graph_id = "0";
		for($i = 0; $i < $this->dom_element->childNodes->length; $i ++) {
			$child = $this->dom_element->childNodes[$i];
			if ($this->graph->is_graph_node($child)) {
				$child_graph_id ++;
				$created_child = $this->graph->create_node($child, $child_graph_id, $this);
				$this->children[] = $created_child;
			}
		}
		return null;
	}

	public static function get_class_name_from_type($type){
		switch ($type) {
			case 'location' :
				return 'library_map_location';
			case 'section' :
				return 'library_map_section';
			case 'call_number' :
				return 'library_map_call_number';
			case 'text' :
				return 'library_map_text';
			default :
				return 'library_map_base';
		}
	}

	/**
	 *
	 * @return array
	 */
	public function get_children(){
		return $this->children;
	}

	/**
	 *
	 * @return string
	 */
	public function get_id(){
		// TODO : something wrong here with the attribute
		return $this->dom_element->hasAttribute('id') ? $this->dom_element->getAttribute('id') : null;
	}

	public function get_graph_id(){
		return $this->graph_id;
	}

	/**
	 *
	 * @return string
	 */
	public function get_type(){
		return $this->type;
	}

	/**
	 *
	 * @return string
	 */
	public function get_parent(){
		return $this->parent;
	}

	public function get_graph(){
		return $this->graph;
	}

	/**
	 *
	 * @return DOMElement
	 */
	public function get_dom_element(){
		return $this->dom_element;
	}

	/**
	 */
	public function get_label(){
		return $this->dom_element->hasAttribute('label') ? $this->dom_element->getAttribute('label') : $this->get_type(). ' inconnu(e).';
	}

	/**
	 *
	 * @return string
	 */
	public function get_name(){
		return $this->dom_element->nodeName;
	}
}
