<?php 
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: typ_doc.inc.php,v 1.24 2018-10-12 11:59:35 dgoron Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

// proceed à faire ? savoir aussi si on veut lire ou écrire dans la table
require_once $class_path.'/library_map/library_map_section.class.php';

switch ($structure_type) {
	case 'section':
		if ($action == 'set') {
			$sections = library_map_section::save_section_link($graph_id, $pmb_id, $structure_type);
		} else {
			$sections = library_map_section::get_sections_from_pmb_by_loc($parent_id);
		}// Ne retourne rien tant que les ids sur le plan ne correspondent pas à ceux de la base (tableau vide)
		echo encoding_normalize::json_encode($sections);
		break;
	case 'location':
		
		// TODO : n'afficher que les localisations appartenant à la surlocalisation parente
		if ($action == 'set') {
			$locations = library_map_location::save_location_link($graph_id, $pmb_id, $structure_type);
		} else {
			$locations = library_map_location::get_locations_from_pmb();
			echo encoding_normalize::json_encode($locations);			
		}
		break;
	case 'surloc':
		// TODO : n'afficher que les sections appartenant à la localisation parente
		
		$sur_locations = array();
		$query = 'select surloc_libelle, surloc_id from sur_location';
		$result = pmb_mysql_query($query);
		while ($r = pmb_mysql_fetch_assoc($result)) {
			$sur_locations[]= $r;
		}
		echo encoding_normalize::json_encode($sur_locations);
		break;
	case 'call_number':
		$cotes = array();

		// TODO : Conception, mise au point sur fonctionnement
// 		while ($r = pmb_mysql_fetch_assoc($result)) {
// 			$cotes[]= $r;
// 		}
		echo encoding_normalize::json_encode($cotes);
		// TODO : chercher les cotes ?? où sont-elles ?
		break;
	default:
		break;
}