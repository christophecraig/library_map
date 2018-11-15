<?php

// +-------------------------------------------------+
// | 2002-2011 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: exploded_search_segment.class.php,v 1.1 2017-08-11 16:10:01 tsamson Exp $
if (stristr($_SERVER['REQUEST_URI'], ".class.php"))
	die("no access");
	
class library_structure_data {
	
	public function __construct() {
		
	}
	
	// Voir comment mieux décomposer cette méthode ?
	public function get_structure() {
		// Cas où il n'y a pas de surloc ?
		$structures = array();
		
		$query = 'SELECT surloc_id FROM sur_location';
		$result = pmb_mysql_query($query);
		while ($r = pmb_mysql_fetch_assoc($result)) {
			$surloc = new sur_location($r['surloc_id']);
			$surloc_data = array(
					'treeId' => $surloc->id,
					'surloc_libelle' => $surloc->name,
					'type' => 'surloc'
			);
			$structures[]= $surloc_data;
			
			foreach($surloc->docs_location_data as $loc) {
				$loc['parent'] = $surloc->id;
				$loc['treeId'] = $loc['idlocation'];
				$loc['type'] = 'location';
				$loc_instance = new docs_location($loc['idlocation']);
				foreach($loc_instance->get_sections_from_location() as $section_id) {
					$query2 = 'SELECT idsection, section_libelle FROM docs_section where idsection ="'. $section_id .'"';
					$result2 = pmb_mysql_query($query2);
					while ($r2 = pmb_mysql_fetch_array($result2)) {
						$section_data = array(
								'treeId' => $r2['idsection'],
								'section_libelle' => $r2['section_libelle'],
								'type' => 'section',
								'parent' => $loc['idlocation']
						);
						$structures[]= $section_data;
					}
				}
				$structures[]= $loc;
			}
		}
		return $structures;
	}
}
