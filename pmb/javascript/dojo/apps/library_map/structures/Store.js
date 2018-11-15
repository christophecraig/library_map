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
		structures: [],
		idProperty: 'treeId', // pour éviter que ça n'arrete jamais de chercher des children
		constructor: function() {
			this.data.push({type: 'root'});
			this.structures = new Memory({data: this.data});
		},
		getChildren: function(obj, options) {
			if (obj.type == 'root') {
					return this.query({type: 'surloc'});
			}
			return this.query({parent: obj.treeId});
			
			return children;				

		}
	});
});
