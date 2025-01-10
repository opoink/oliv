<script setup>
	import { ref, onMounted } from 'vue';
	import { loader } from '@@Plugins@@/Opoink/Liv/resources/js/States/loader.js';
	import GrapeJS from '@@Plugins@@/Opoink/Cms/resources/js/Lib/grapejs/grape';
	// import jquery from 'jquery';
	import * as block3col from '@@Plugins@@/Opoink/Cms/resources/js/Lib/grapejs/block-3-col.json';
</script>
<script>

	export default {
		data() {
			return {
				grapejs: GrapeJS,
				selectedEditor: ref(''),
				showUseEditorButtons: false,
				
				tinymceValue: '',
				tinymcsInstance: null,

				contentUpdater: {
					tinymce: null,
					gjs: null
				}
			}
		},
		props: [
			'selector',
			'modelValue'
		],
		methods: {
			setEditor(editor){
				this.selectedEditor = editor;
			},
			useGrapeJs(isNew=false){
				this.setEditor('gjs');
				this.clearTinymce();

				let projectData =  block3col.content.data;
				if(this.modelValue && !isNew){
					let c = JSON.parse(this.modelValue);
					projectData = c.content.data;
				}

				if(typeof window['oliv_cms'] == 'undefined'){
					window['oliv_cms'] = {};
				}

				window.oliv_cms['gjs_project'] = projectData;
				console.log('projectData projectData', window.oliv_cms, projectData, block3col);

				this.grapejs.init();
				this.contentUpdater.gjs = setInterval(() => {
					let c = {
						editor: this.selectedEditor,
						content: {
							html: this.grapejs.editor.getHtml(),
							css: this.grapejs.editor.getCss({ avoidProtected: true }),
							data: this.grapejs.editor.getProjectData()
						}
					}
					this.$emit('update:modelValue', JSON.stringify(c));
				}, 300);
			},
			clearGjs(){
				this.grapejs.destroy();
				try {
					delete window.oliv_cms.gjs_project;
					clearInterval(this.contentUpdater.gjs);
				} catch (error) {
					/** do nothing */
				}
			},

			loadTinyMCE(){
				window.tinymce.init({
					selector: '#'+this.selector,
					plugins: [
						'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
						'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen', 'insertdatetime',
						'media', 'table', 'emoticons', 'help'
					],
					toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
						'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
						'forecolor backcolor emoticons | help',
					menu: {
						favs: { title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons' }
					},
					menubar: 'favs file edit view insert format tools table help',
				})
				.then((t) => {
					this.tinymcsInstance = t[0];
				});
			},
			useTinyMce(){
				this.setEditor('tinymce');
				this.loadTinyMCE();
				this.clearGjs();

				this.contentUpdater.tinymce = setInterval(() => {
					let c = {
						editor: this.selectedEditor,
						content: this.tinymcsInstance.getContent()
					}
					this.$emit('update:modelValue', JSON.stringify(c));
				}, 300);
			},
			clearTinymce(){
				if(this.contentUpdater.tinymce){
					clearInterval(this.contentUpdater.tinymce);
					this.tinymcsInstance = null;
					window.tinymce.remove('#'+this.selector);
				}
			}
		},
		beforeUnmount(to, from) {
			this.clearTinymce();
			this.clearGjs();
		},
		mounted: function(){
			localStorage.removeItem('gjsProject');
			
			this.grapejs.loadScript().then(() => {
				if(this.modelValue){
					let c = JSON.parse(this.modelValue);
					if(c){
						if(c.editor == 'gjs'){
							this.useGrapeJs(false);
						}
						else if(c.editor == 'tinymce'){
							this.tinymceValue = c.content;
							this.useTinyMce();
						}
					}
				}
				else {
					this.showUseEditorButtons = true;
				}
			})
		}
	}
</script>

<template>
	<div class="content-editor">
		<div v-show="selectedEditor == 'tinymce'">
			<textarea 
				:id="selector" 
				:model="tinymceValue"
				rows="10"
				class="w-100"
			>{{tinymceValue}}</textarea>
		</div>
		<textarea 
			:model="modelValue"
			rows="10"
			class="w-100 d-none"
		>{{modelValue}}</textarea>
		
		
		<div class="gjs-wrapper" v-show="selectedEditor == 'gjs'" v-bind:class="{'max-size' : grapejs.is_maximized}">
			<div class="row">
				<div class="col-12">
					<div class="text-end bg-white p-2">
						<button class="btn btn-outline-primary btn-sm" title="maximize" v-on:click="grapejs.gjsMinMax()" v-show="!grapejs.is_maximized">
							<i class="fa-regular fa-window-maximize"></i>
						</button>
						<button class="btn btn-outline-primary btn-sm" title="maximize" v-on:click="grapejs.gjsMinMax()" v-show="grapejs.is_maximized">
							<i class="fa-solid fa-window-minimize"></i>
						</button>
					</div>
				</div>
			</div>

			<div class="panel__top">
				<div class="panel__basic-actions"></div>
				<div class="panel__switcher"></div>
			</div>
			<div class="editor-row">
				<div id="gjs-editor-canvas" class="editor-canvas">
					<div id="gjs"></div>
				</div>
				<div class="panel__right">
				  	<div class="layers-container"></div>
					<div class="styles-container"></div>
				</div>
			  </div>
			<div id="blocks"></div>
		</div>

		<div class="mt-2" v-if="showUseEditorButtons">
			<button class="btn btn-outline-primary btn-sm me-3" v-on:click="useTinyMce()" v-if="selectedEditor != 'tinymce'">
				Use TinyMCE
			</button>
			<button class="btn btn-outline-primary btn-sm" v-on:click="useGrapeJs(true)" v-if="selectedEditor != 'gjs'">
				Use Grape
			</button>
		</div>
	</div>
</template>