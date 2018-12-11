<?php

// +-------------------------------------------------+
// | 2002-2011 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $graph_id: exploded_search_segment.class.php,v 1.1 2017-08-11 16:10:01 tsamson Exp $
if (stristr($_SERVER['REQUEST_URI'], ".class.php"))
	die("no access");

require_once ($class_path . '/library_map/library_map_base.class.php');
class library_map_section extends library_map_base {
	protected $type = "section";
	private $section_id = 0;
	protected function get_properties($graph_id){
		parent::get_properties($graph_id);
		$this->section_id = $this->set_section();
	}
	
	private function set_section(){
		return $this->dom_element->getAttribute('section');
	}

	public function get_section_id() {
		return $this->section_id;
	}
	
	public static function get_sections_from_pmb_by_loc($location_id){
		$sections = array();
		$query = 'select distinct idsection, section_libelle, docsloc_section.num_location
		from docs_section
		inner join docsloc_section
		on docsloc_section.num_section = docs_section.idsection
		where docsloc_section.num_location = ' . $location_id;
		$result = pmb_mysql_query($query);
		while ($r = pmb_mysql_fetch_assoc($result)) {
			$sections[]= $r;
		}
		return $sections;
	}
	
	public static function save_section_link($graph_id, $pmb_id, $type) {
		// On remet à zéro
		$query = 'delete from library_map_link where graph_id = "'.$graph_id.'"';
		pmb_mysql_query($query);
		// et on enregistre
		$query = "insert into library_map_link ( `graph_id`, `structure_id`, `structure_type`) values ('".$graph_id."',".$pmb_id.",'".$type."')";
		try {
			pmb_mysql_query($query);
		}
		catch (Exception $e) {
			return $e;
		}
		return $query;
	}
}
