<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ajax_selector.php,v 1.110 2018-10-29 09:02:07 dgoron Exp $

$base_path=".";
$base_noheader=1;
$base_nobody=1;
//$base_nocheck=1;
//Il me faut le charset pour la suite

require_once($base_path."/includes/init.inc.php");
require_once($base_path."/includes/error_report.inc.php") ;
require_once($base_path.'/includes/opac_config.inc.php');
require_once($class_path.'/library_map/library_map_graph.class.php');
// récupération paramètres MySQL et connection á la base
if (file_exists($base_path.'/includes/opac_db_param.inc.php')) require_once($base_path.'/includes/opac_db_param.inc.php');
	else die("Fichier opac_db_param.inc.php absent / Missing file Fichier opac_db_param.inc.php");

if($charset != "utf-8"){
	$_POST = array_uft8_decode($_POST);
}
//$_GET = array_uft8_decode($_GET);

require_once($base_path."/includes/global_vars.inc.php");

require_once($base_path.'/includes/opac_mysql_connect.inc.php');
$dbh = connection_mysql();

//Sessions !! Attention, ce doit être impérativement le premier include (à cause des cookies)
require_once($base_path."/includes/session.inc.php");

require_once($base_path.'/includes/start.inc.php');

require_once($base_path."/includes/check_session_time.inc.php");

require_once($base_path."/includes/misc.inc.php");
require_once($base_path.'/includes/divers.inc.php');

// récupération localisation
require_once($base_path.'/includes/localisation.inc.php');
require_once($base_path."/includes/rec_history.inc.php");

// inclusion des fonctions utiles pour renvoyer la réponse à la requette recu 
require_once ($base_path . "/includes/ajax.inc.php");
require_once($base_path."/includes/marc_tables/".$pmb_indexation_lang."/empty_words");

require_once($include_path.'/plugins.inc.php');

header("Content-Type: text/html; charset=$charset");
$start=stripslashes($datas);
$start = str_replace("*","%",$start);
$insert_between_separator = "";
$taille_search = "";
if($att_id_filter == 'null'){
    $att_id_filter = null;
}

$query = '';
$result = pmb_mysql_query();
$graph = new library_map_graph($class_path . '/library_map/plan.svg');

echo encoding_normalize::json_encode($graph->search());

