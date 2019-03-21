// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: Tree.js,v 1.2 2018-10-10 15:23:19 apetithomme Exp $

define(['dojo/_base/declare',
        'dijit/Tree',
        'dojo/dom-construct',
        'dijit/tree/dndSource',
        'dojo/_base/lang',
        'dojo/dnd/Target',
        'dojo/on',
        'dojo/dom',
        'dojo/topic'
], function(declare, Tree, domConstruct, dndSource, lang, dndTarget, on, dom, topic) {
	return declare([Tree], {	
		id: 'libraryMapTree',
		
		dndController: dndSource,
		
		showRoot: false,
		
		persist: true,
		
		openOnClick: false,
						
		postCreate: function() {
			this.inherited(arguments);
			this.dndController.checkAcceptance = lang.hitch(this, this.dndCheckAcceptance);
			this.dndController.checkItemAcceptance = lang.hitch(this, this.dndCheckItemAcceptance);
		},
		
		dndCheckAcceptance: function(source, nodes) {			
			var item = source.tree.selectedItem;
			if (item.type == 'property') return true;
			return false;
		},

		dndCheckItemAcceptance: function(target, source, position) {
			return false;
		},
		
		getLabel: function(item) {
			return item.label;
		},
		
		getLabelStyle: function(item) {
			console.log(item)
			if (item.alreadyComputed) {
				return {'font-weight': 'bold'};
			}
		},
		
		onDblClick: function(item, node, evt) {
			document.querySelectorAll('.map-zone-highlight').forEach(el => el.classList.remove('map-zone-highlight'));
			var zone = document.querySelector('[graphid="'+item.treeId+'"]');
			if (zone.nodeName == 'rect') {
				zone.classList.add('map-zone-highlight');
			} else {
				zone.children[0].classList.add('map-zone-highlight');
			}
		},
		
		getIconClass: function(item, opened){
		    return (item.type == "call_number" ? "dijitLeaf" : (opened ? "dijitFolderOpened" : "dijitFolderClosed"));
		}
	});
});