define(['dojo/_base/declare', 
        'dijit/layout/ContentPane', 
        'dojo/request/xhr', 
        'dojo/dom',
        'dojo/dom-construct',
        'dojo/dnd/Target',
        'dojo/on',
        'dojo/_base/lang',
        'dojo/topic',
        'dojo/Deferred',
        'dojo/query',
        'dojo/dom-attr',
        'dojo/json'
], function(declare, ContentPane, xhr, dom, domConstruct, dndTarget, on, lang, topic, Deferred, query, domAttr, dojoJson){
	return declare(ContentPane, {
		modified: false,
		field: {},
		element: document.querySelector('[data-dojo-id="libraryMapPlan"]'),
		vue: null,
		constructor: function() {
			this.inherited(arguments)
			topic.subscribe('dblClick', lang.hitch(this, this.display))
			Vue.component('modal', {
				template: `
			  <transition name="modal">
			    <div class="modal-mask">
			      <div class="modal-wrapper"  @click.capture.self="$emit(\'close\')">
			        <div class="modal-container">

			          <div class="modal-header">
			            <slot name="header">
			              Associer cette zone à une {{structureType}}
			            </slot>
			          </div>

			          <div class="modal-body">
			            <slot name="body">
			              <select v-if="structureType == 'section'" v-model="selected">
							  <option v-for="opt in options" :value="opt.idsection">
							    {{ opt.section_libelle }}
							  </option>
							</select>
							
			              <select v-else-if="structureType == 'location'" v-model="selected">
							  <option v-for="opt in options" :value="opt.idlocation">
							    {{ opt.location_libelle }}
							  </option>
							</select>
							
			              <select v-else-if="structureType == 'call_number'" v-model="selected">
							  <option v-for="opt in options" :value="opt.idlocation">
							    {{ opt.location_libelle }}
							  </option>
							</select>
			            </slot>
			          </div>

			          <div class="modal-footer">
			            <slot name="footer">
			              default footer
			              <button class="modal-default-button" @click="save">
			                OK
			              </button>
			            </slot>
			          </div>
			        </div>
			      </div>
			    </div>
			  </transition>`,
			  props: ['options', 'structureType', 'graphId'],
			  data() {
				  return {
					  selected: ''
				  }
			  },
			  methods: {
				  save(e) {
					  console.log(this.selected, this.structureType, this.graphId)
					  xhr.post('./ajax.php?module=admin&categ=library_map&action=set&structure_type=' + this.structureType, 
						{
							 data: {
								 graph_id: this.graphId,
								 structure_type: this.structureType,
								 pmb_id: this.selected
							 }
						}).then(function(data) {
							console.log(data)
						})
				  }
			  }
			})
			this.vue = new Vue({
				el: '#modal-vue',
				data() {
					return {
						section: [],
						location: [],
						call_number: [],
						selected: '',
						options: [], // text, value
						structureType: '',
						graphId: '',
						showModal: false
					}
				}
			});
			console.log(this.vue)
			this.element.addEventListener('dblclick', lang.hitch(this, this.openModal));
		},
		
		display: function(item, node, evt) {
			if (item.type != 'property') {
				return false;
			}
			if (this.confirmUnload()) {
				this.modified = false;
				this.destroyDescendants(false);
			}
		},
		
		confirmUnload: function() {
			if (this.modified) {
				return confirm(pmbDojo.messages.getMessage('contribution_area', 'contribution_area_computed_fields_confirm'));
			}
			return true;
		},
		
		openModal: function(e) {
			var el = e.target;
			var type = typeof el.getAttribute('type') === 'string'  ? el.getAttribute('type') : el.getAttribute('shape');
			if (el.hasAttribute('shape')) {
				el = el.parentNode;
			} 
			var type = el.getAttribute('type');
			switch (type) {
				case null:
					break;
				default:
					switch ( type ) {
						case 'section':
   							this.getStructures(type, el.parentNode.getAttribute('location'));
							break;
						case 'call_number':
							this.getStructures(type, el.parentNode.getAttribute('section'));
							break;
						case 'location':
							this.getStructures(type);
							break;
						case 'surloc':
						default:
							break;
					}
					break;
			}
			this.vue.$data.graphId = el.getAttribute('graphid');
			this.vue.$data.showModal = true;
			
			// TODO: open modal with select multiple v-model with data from this.getStructures
		},
		
		getStructures(type, parentId = null) {
			var deferred = new Deferred();
			xhr('./ajax.php?module=admin&categ=library_map&structure_type=' + type + (parentId ? '&parent_id=' + parentId : ''))
			.then(
				lang.hitch(this, function(data){
					this.vue.$data.options = JSON.parse(data);
					this.vue.$data.structureType = type;
					deferred.resolve('success');
				}));
			return deferred.promise;
		}
	})
})