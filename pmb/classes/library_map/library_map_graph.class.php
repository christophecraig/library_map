<?php

// +-------------------------------------------------+
// | 2002-2011 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: exploded_search_segment.class.php,v 1.1 2017-08-11 16:10:01 tsamson Exp $
if (stristr($_SERVER['REQUEST_URI'], ".class.php"))
	die("no access");

require_once $class_path . '/library_map/library_map_base.class.php';
require_once $class_path . '/library_map/library_map_location.class.php';
require_once $class_path . '/library_map/library_map_section.class.php';
require_once $class_path . '/library_map/library_map_call_number.class.php';
require_once ($class_path . '/encoding_normalize.class.php');
/**
 * classe qui g�re le plan en svg
 */
class library_map_graph {
	private $svg;
	private $root_node;
	private $nodes_in_document;
	private $counter = 0;
	private $toRender = '';

	public function __construct($svg_file){
		$this->svg = new DOMDocument();
		$this->svg->load($svg_file);
		$this->root_node = $this->create_node($this->svg->documentElement, "0", null);
	}

	/**
	 *
	 * @param library_map_base $instance
	 * @return array of instances
	 */
	public function get_all_children($instance){
		$children = array ();
		foreach ($instance->get_children() as $child) {
			/**
			 *
			 * @var library_map_base $child
			 */
			if ($child->get_children()) {
				$sub_children['children'] = $this->get_all_children($child);
			}
			$sub_children['type'] = $child->get_type();
			$sub_children['graph_id'] = $child->get_graph_id();
			$sub_children['id'] = $child->get_id();
			$sub_children['parent'] = $child->get_parent()->get_graph_id();
			$sub_children['label'] = $child->get_label();
			$sub_children['unique-id'] = $this->counter;
			$this->counter ++;
			$sub_children['height'] = $child->get_dom_element()->hasAttribute('height') ? $child->get_dom_element()->getAttribute('height') : 0;
			$sub_children['width'] = $child->get_dom_element()->hasAttribute('width') ? $child->get_dom_element()->getAttribute('width') : 0;
			$children[] = $sub_children;
			
			switch ($child->get_type()) {
				case 'base' :
					$sub_children['id'] = 'root';
					break;
				case 'location' :
					$sub_children['location_id'] = $child->get_location_id();
					break;
				case 'section' :
					$sub_children['section_id'] = $child->get_section_id();
					break;
				case 'call_number' :
					$sub_children['min_call_number'] = $child->get_min_call_number();
					$sub_children['max_call_number'] = $child->get_max_call_number();
					break;
			}
		}
		return $children;
	}

	/**
	 * This function checks if the node should appear on the graph
	 *
	 * @param DOMNode $domElement
	 * @return boolean
	 */
	public function is_graph_node($dom_element){
		if (($dom_element->nodeType !== 1) || $dom_element->nodeName === 'defs' || $dom_element->nodeName === 'metadata' || $dom_element->nodeName === 'sodipodi:namedview') {
			// a voir cette condition, rajouter noms de tags autres, ceux-ci viennent d'inkscape. Tester avec tags propres a illustrator ?
			return false;
		}
		return true;
	}

	/**
	 *
	 * @param DOMElement $domElement
	 * @param string $id
	 * @param library_map_base $instance
	 * @return library_map_base
	 */
	public function create_node($dom_element, $id, $instance){
		$child_class = library_map_base::get_class_name_from_type($dom_element->getAttribute('type'));
		$child = new $child_class($dom_element, $instance, $id, $this);
		$this->index_node($child); // Peut-etre pas, pas utile pour Text par exemple ?
		return $child;
	}

	/**
	 * Indexes an instance hierarchically and by its id
	 *
	 * @param library_map_base $instance
	 */
	public function index_node($instance){
		$this->nodes_in_document[$instance->get_type()][] = $instance;
		$this->nodes_in_document["ids"][$instance->get_id()] = $instance;
	}

	/**
	 *
	 * @param string $id
	 * @return library_map_base|null
	 */
	public function get_element_by_id($id){
		return (isset($this->nodes_in_document['ids'][$id]) ? $this->nodes_in_document['ids'][$id] : null);
	}

	public function get_nodes(){
		return $this->nodes_in_document;
	}

	/**
	 *
	 * @return library_map_base
	 */
	public function get_root_node(){
		return $this->root_node;
	}

	/**
	 *
	 * @param library_map_base $instance
	 * @param boolean $needs_highlight
	 * @param integer $zoom_level
	 * @return string
	 */
	public function get_svg($instance, $zone_id, $first_type, $needs_highlight, $zoom_level){
		// Voir condition de rajout des balises svg ci-dessus
		if ($needs_highlight) {
			$this->highlight_Parents($zone_id, $first_type);
		}
		return '<svg width="660" height="440">' . $this->svg->saveXML($instance->get_dom_element()) . '</svg>';
	}

	/**
	 *
	 * @param string $id
	 * @param string $first_type
	 */
	private function highlight_parents($id, $first_type){
		switch ($first_type) {
			
			case 'call_number' :
				if ($this->get_element_by_id($id)->get_type() === 'call_number') {
					$rect = $this->get_element_by_id($id)->get_dom_element();
				} else {
					$rect = $this->get_element_by_id($id . '.1')->get_dom_element();
				}
				break;
			
			case 'section' :
				if ($this->get_element_by_id($id)->get_type() === 'section') {
					$rect = $this->get_element_by_id($id . '.1')->get_dom_element();
				} else {
					$rect = $this->get_element_by_id($id . '.1')->get_dom_element();
				}
				break;
			
			case 'location' :
				$rect = $this->get_element_by_id($id . '.1')->get_dom_element();
				break;
		}
		
		var_dump($rect);
		
		$rect->setAttribute('class', 'highlight');
		if (strlen($id) > 3) {
			$parent_id = substr($id, 0, -2);
			$this->highlight_parents($parent_id, $first_type);
		}
	}

	/**
	 *
	 * @return array
	 */
	public function get_locations_nodes(){
		return $this->get_root_node()->get_children();
	}

	/**
	 *
	 * @param string $id
	 * @return array
	 */
	public function get_sections_nodes($id){
		$location = $this->get_element_by_id($id);
		if (!is_object($location)) {
			return array ();
		}
		return $location->get_children();
	}

	/**
	 *
	 * @return array
	 */
	private function get_sections_from_pmb(){
		return pmb_mysql_fetch_all(pmb_mysql_query('select distinct idsection from docs_section'));
	}

	/**
	 *
	 * @param integer $location
	 * @param integer $section
	 * @param string $call_number
	 * @param integer $status
	 *        	code statut exemplaire
	 * @param integer $zoom_level
	 *        	niveau de zoom
	 * @param boolean $restrict_to_zoom
	 *        	D�termine si l'on doit afficher plus large que la partie du plan recherch�e
	 * @return string
	 */
	public function search($location = null, $section = null, $call_number = null, $status = 1, $zoom_level = 0, $restrict_to_zoom = false){
		$first_type = '';
		$instance;
		// TODO refaire getlocations
		if ($location !== null) {
			foreach ($this->get_nodes()['location'] as $loc) {
				if ($loc->get_location_id() == $location) {
					$loc_exists = true;
					$loc_instance = $loc;
				}
			}
			if (!$loc_exists)
				return "Cette localisation n'est pas repr�sent�e sur le plan !";
		}
		
		// if ($section !== null && (!in_array($section, $this->get_sections_from_pmb()))) {
		// return "Cette section n'est pas repr�sent�e sur le plan ! ";
		// }
		
		if ($section !== null && (!in_array($section, $this->get_sections_nodes($id)))) {
			return "Cette section n'est pas repr�sent�e sur le plan";
		}
		
		if ($status != 1 && $status != 13) {
			return "L'exemplaire n'est pas consultable pour le moment";
		}
		
		// Ci-dessous, test� 2 fois la nullit� de $location, pas utile
		if ($location === null && $section === null && $call_number === null) {
			$needs_highlight = false;
		} else {
			$needs_highlight = true;
		}
		
		switch ($zoom_level) {
			
			case 0 :
				$zone_id = $this->root_node->get_id();
				$needs_highlight = null;
				break;
			
			case 1 : // only location given
				if ($restrict_to_zoom) {
					$instance = $this->get_nodes()['location'][array_search($location, $this->get_nodes()['location'])];
					$zone_id = $instance->get_id();
				} else {
					foreach ($this->get_nodes()['location'] as $location_instance) {
						if ($location_instance->get_locations_id() === $location) {
							$zone_id = $location_instance->get_id();
						}
					}
				}
				break;
			
			case 2 : // location and Section given
				if ($restrict_to_zoom) {
					$instance = $this->get_nodes()['section'][array_search($section, $this->get_nodes()['section'])];
					$zone_id = $instance->get_id();
				} else {
					foreach ($this->get_nodes()['section'] as $section_instance) {
						if ($section_instance->get_map_section_id() === $section) {
							$zone_id = $section_instance->get_id();
						}
					}
				}
				break;
			
			case 3 : // location, Section and call_number given
				if ($restrict_to_zoom) {
					$instance = $this->get_nodes()['call_number'][array_search($call_number, $this->get_nodes()['call_number'])];
					$zone_id = $instance->get_id();
				} else {
					foreach ($this->get_nodes()['call_number'] as $call_number_instance) {
						if ($call_number_instance->get_min_call_number() < $call_number && $call_number_instance->get_max_call_number() > $call_number) {
							$zone_id = $call_number_instance->get_id();
							$instance = $this->root_node;
							break;
						}
					}
				}
				
				break;
			
			default :
				$instance = $this->root_node;
				return 'Le niveau de zoom indiqu� n\'est pas correct';
				break;
		}
		
		$instance = isset($instance) ? $instance : $this->root_node;
		echo $zoom_level;
		echo $needs_highlight;
		return $this->get_svg($instance, $zone_id, ((!is_null($this->get_element_by_id($zone_id))) ? $this->get_element_by_id($zone_id)->get_type() : 'library_map_base'), $needs_highlight, $zoom_level);
	}

	private function prepare_for_render($plan){
		$jsonFromSvg = array (
				children => array () 
		);
		$jsonFromSvg['children'] = $plan->get_all_children($plan->get_root_node());
		return $this->formatJson($jsonFromSvg);
	}
// TODO: Soit dans prepare_for_render soit dans formatJson, il faudra faire l'op�ration qui consiste � mettre � plat tout les enfants du tableau
// � voir ici
	private function formatJson($jsonFragment){
		foreach ($jsonFragment['children'] as $child) {
			if ($child['type'] === 'location' || $child['type'] === 'section' || $child['type'] === 'call_number') {
				$svgMapData[] = $child;
				if (!isset($child['children'])) {
					return null;
				}
				$this->formatJson($child);
			}
		}
// 		var_dump($svgMapData);
		return $svgMapData;
	}

	/**
	 *
	 * @param array $plan
	 */
	public function render($plan = null){
		$plan = is_null($plan) ? $this : $plan;
		$render = array(
				type => 'base',
				'graph-id' => '0',
				children => array()
		);
		
		foreach ($this->prepare_for_render($plan) as $item) {
			$render[] = $item;
		}
		
		return $render;

	}
}