<?php
// +-------------------------------------------------+
// ï¿½ 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.13 2016-04-29 14:14:38 jpermanne Exp $
if (stristr($_SERVER['REQUEST_URI'], ".inc.php"))
	die("no access");

require_once $class_path . '/library_map/library_map_graph.class.php';
require_once $class_path.'/library_map/library_structure_data.class.php';

// Chemin à changer pour le svg
$graph = new library_map_graph($class_path . '/library_map/plan.svg');
$structures = new library_structure_data();

echo "
<div id='library-map-panel' data-dojo-type='dijit/layout/BorderContainer' data-dojo-props='splitter:true' style='height:800px;width:100%;'>
<div data-dojo-type='dijit/layout/ContentPane' data-dojo-props='region:\"left\", splitter:true' style='height:100%;width:200px;'>
<input type='hidden' id='contribution_area_num' name='contribution_area_num' value='!!id!!'/>
<div data-dojo-id='libraryMapStore' data-dojo-type='apps/library_map/Store' data-dojo-props='data:".encoding_normalize::json_encode($graph->render())."'></div>
<div data-dojo-id='libraryMapModel' data-dojo-type='dijit/tree/ObjectStoreModel' data-dojo-props='store: libraryMapStore, query: {treeId: 0}'></div>
<div data-dojo-id='libraryMapTree' data-dojo-type='apps/library_map/Tree' data-dojo-props='model: libraryMapModel' ></div>
</div>
<div data-dojo-id='libraryMapPlan' data-dojo-type='apps/library_map/Panel' data-dojo-props='region:\"center\"' style='height:100%;width:auto;overflow:scroll;'><svg>
". $graph->search(5, null, null, 1, 1) ."</svg>
</div>
</div>
<div id='modal-vue'>
<modal :options='options' :structure-type='structureType' :graph-id='graphId' v-if='showModal' @close='showModal = false'>
</div>";

// Ci-dessous, arbre avec les données de structure de la bib dans PMB
// echo "
// <div data-dojo-type='dijit/layout/BorderContainer' data-dojo-props='splitter:true' style='height:800px;width:100%;'>
// <div data-dojo-type='dijit/layout/ContentPane' data-dojo-props='region:\"left\", splitter:true' style='height:100%;width:200px;'>
// <input type='hidden' id='contribution_area_num' name='contribution_area_num' value='!!id!!'/>
// <div data-dojo-id='structuresStore' data-dojo-type='apps/library_map/structures/Store' data-dojo-props='data:".encoding_normalize::json_encode($structures->get_structure())."'></div>
// <div data-dojo-id='structuresModel' data-dojo-type='dijit/tree/ObjectStoreModel' data-dojo-props='store: structuresStore, query: {type: \"root\"}'></div>
// <div data-dojo-id='structuresTree' data-dojo-type='apps/library_map/structures/Tree' data-dojo-props='model: structuresModel' ></div>
// </div>
// <div data-dojo-id='libraryMapPlan' data-dojo-type='apps/library_map/Panel' data-dojo-props='region:\"center\"' style='height:100%;width:auto;overflow:scroll;'>
// ". $graph->search() ."
// </div>
// </div>";
?>
