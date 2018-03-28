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
		$this->section_id = $this->get_section_id();
	}
	
	public function get_section_id(){
		return $this->dom_element->getAttribute('section');
	}
	
	public static function get_sections_from_pmb_by_loc($location_id){
		echo $location_id;
		return pmb_mysql_fetch_all(pmb_mysql_query('select distinct idsection, section_libelle, docsloc_section.num_location from docs_section
		inner join docsloc_section
		on docsloc_section.num_section = docs_section.idsection
		where docsloc_section.num_location = ' . $location_id));
	}
}
