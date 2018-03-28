<?php

// +-------------------------------------------------+
// | 2002-2011 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $graph_id: exploded_search_segment.class.php,v 1.1 2017-08-11 16:10:01 tsamson Exp $
if (stristr($_SERVER['REQUEST_URI'], ".class.php"))
	die("no access");

require_once ($class_path . '/library_map/library_map_base.class.php');
class library_map_location extends library_map_base {
	protected $type = "location";
	private $location_id = 0;

	protected function get_properties($graph_id){
		parent::get_Properties($graph_id);
		$this->location_id = $this->set_location();
	}

	private function set_location(){
		return $this->dom_element->getAttribute('location'); // Voir pour vérifier le type ensuite
	}

	public function get_location_id(){
		return $this->location_id;
	}

	public static function get_locations_from_pmb(){
		return pmb_mysql_fetch_all(pmb_mysql_query('select idlocation, location_libelle from docs_location'));
	}
}
