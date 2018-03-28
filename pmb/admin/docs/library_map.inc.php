<?php
// +-------------------------------------------------+
// � 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: main.inc.php,v 1.13 2016-04-29 14:14:38 jpermanne Exp $
if (stristr($_SERVER['REQUEST_URI'], ".inc.php"))
	die("no access");

require_once ($class_path . '/library_map/library_map_graph.class.php');
require_once ($class_path . '/encoding_normalize.class.php');

$graph = new library_map_graph($class_path . '/library_map/plan.svg');
echo $graph->search();

$locations = $graph->get_locations_nodes();

// locations du plan
function get_map_form_location(){
	$location_ids = array ();
	foreach (library_map_location::get_locations_from_pmb() as $loc_id) {
		$location_ids[] = $loc_id;
	}
	return $location_ids;
}

// $map_form_section = library_map_section::get_sections_from_pmb_by_loc($location_id);
echo '<select id="locations-picker">';
foreach (get_map_form_location() as $loc) {
	echo '<option value="' . $loc[0] . '">' . $loc[1] . '</option>';
}
echo '</select>';

foreach ($locations as $location) {
	/**
	 *
	 * @var library_map_location $location
	 */
	if ($location !== null) {
		// var_dump($graph->get_locations_nodes());
	}
	
	// if (get_class($location) == 'library_map_location') {
	// print '
	// <div class="row">
	// <div class="colonne3">' .$graph->get_element_by_id($location)->get_name() . ' (' . $graph->get_element_by_id($location)->get_id() . ')' . '</div>
	// <div class="colonne_suite">' . // . docs_location::get_html_select(array(;
	// '<select onchange="">';
	
	// foreach ( $pmb_sections as $pmb_section ) {
	// /**
	// *
	// * @var library_map_section $sectionPMB
	// */
	// echo '<option>' . $pmb_section . '</option>';
	// }
	
	// echo '</select>
	// </div>';
	// }
}

echo '<div id="treeOne" class="colonne2"></div>';
foreach ($graph->get_all_children($graph->get_root_node()) as $child) {
// 	var_dump($child);
	echo 'test';
}
?>

<script>
var svgMapData = [{
	type: 'Base',
	"graph-id": 0,
	label: 'Localisations'
}]
var jsonFromSvg = {children: []}
jsonFromSvg.children = JSON.parse('<?php echo encoding_normalize::json_encode($graph->get_all_children($graph->get_root_node())) ?>');
formatJson(jsonFromSvg)

var counter = 0

function formatJson (jsonFragment) {	
	jsonFragment.children.forEach(child => {
		if (child.type === 'location' || child.type === 'section' || child.type === 'call_number') {
			svgMapData.push(child);
			console.log(child.children)
			if (!child.hasOwnProperty('children')) {
				console.log('children : ', child.children, child['graph-id'], counter)
				return null;
			}
			formatJson(child);
		}
	})
	return svgMapData
}
console.log('svgMapData : ', svgMapData);

require([
    "dojo/store/Memory",
    "dijit/tree/ObjectStoreModel", "dijit/Tree",
    "dojo/domReady!"
], function(Memory, ObjectStoreModel, Tree) {
		var myStore = new Memory({
        data: svgMapData,
        getChildren: function(object){
            return this.query({
				parent: object['graph-id']
            })
    	}
	});

    // Create the model
    var myModel = new ObjectStoreModel({
        store: myStore,
        query: {'graph-id': '0'},
        labelAttr: 'label'
    });

    console.log(myModel.store);
    // Create the Tree.
    var tree = new Tree({
        model: myModel
    });
    
    tree.placeAt(document.getElementById('treeOne'));
    tree.startup()
})
</script>
