<?php
// +-------------------------------------------------+
// ï¿½ 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
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

echo '<select id="locations-picker">';
foreach (get_map_form_location() as $loc) {
	echo '<option value="' . $loc[0] . '">' . $loc[1] . '</option>';
}
echo '</select>';
var_dump($graph->get_all_children($graph->get_root_node()));
echo gettype($graph->get_all_children($graph->get_root_node()));
?>

<div id="treeOne" class="colonne2"></div>

<script>

// function formatJson (jsonFragment) {
// jsonFragment.children.forEach(child => {
// if (child.type === 'location' || child.type === 'section' || child.type === 'call_number') {
// svgMapData.push(child);
// console.log(child.children)
// if (!child.hasOwnProperty('children')) {
// console.log('children : ', child.children, child['graph_id'], counter)
// return null;
// }
// formatJson(child);
// }
// })
// return svgMapData
// }

var svgMapData = JSON.parse('<?php echo encoding_normalize::json_encode($graph->render()); ?>');
console.log('test');

console.log('svgMapData : ', svgMapData); // Version Ajax ?

require([
    "dojo/store/Memory",
    "dijit/tree/ObjectStoreModel", "dijit/Tree",
    "dojo/domReady!"
], function(Memory, ObjectStoreModel, Tree) {
		var myStore = new Memory({
        data: svgMapData,
        getChildren: function(object){
            return this.query({
				parent: object['graph_id']
            })
    	}
	});

    // Create the model
    var myModel = new ObjectStoreModel({
        store: myStore,
        query: {'graph_id': '0'},
        labelAttr: 'label'
    });

    console.log(myModel.store);
    // Create the Tree.
    var tree = new Tree({
        model: myModel,
        onClick: function(item) {
            console.log('item : ', item)
			document.querySelector(`rect[graph_id="${item["graph_id"]}"]`).style = 'fill: red';
        }
    });
    
    tree.placeAt(document.getElementById('treeOne'));
    tree.startup()
    tree.onLoadDeferred.then(function() {
        console.log('exceptionnel c\'est chargé', tree.model)
	
	})
})
</script>
