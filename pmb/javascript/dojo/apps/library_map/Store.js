// +-------------------------------------------------+
// � 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: Store.js,v 1.3 2018-10-10 15:23:19 apetithomme Exp $


define(["dojo/_base/declare", 
        "dojo/store/Memory",
        'dojo/_base/lang',
        'dojo/topic'
], function(declare, Memory, lang, topic){
	return declare(Memory, {
		idProperty: 'treeId',
		
		constructor: function() {
			this.structures = new Memory({data: this.data});
		},
		
		getChildren: function(object, node) {
			var children = this.query({parent: object.treeId});
			console.log(object.treeId, children)
			return children;
		}
	});
});
