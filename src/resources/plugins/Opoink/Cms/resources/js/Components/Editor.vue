<script setup>
	import { ref, onMounted, onBeforeMount } from 'vue';
	import { loader } from '@@Plugins@@/Opoink/Liv/resources/js/States/loader.js';
	import GrapeJS from '@@Plugins@@/Opoink/Cms/resources/js/Lib/grapejs/grape';
	// import jquery from 'jquery';
	import * as block3col from '@@Plugins@@/Opoink/Cms/resources/js/Lib/grapejs/block-3-col.json';
	import { defineExpose } from 'vue';

	const selectedEditor = ref('');
	const showUseEditorButtons = ref(false);
	const tinymceValue = ref('');
	const tinymcsInstance = ref(null);
	const contentUpdater = ref({
		tinymce: null,
		gjs: null,
		plaintext: null
	});
	const useplainText = ref(true);
	const plaintextValue = ref('');

	const props = defineProps([
		'selector',
		'modelValue'
	]);

	onBeforeMount(() => {
		clearTinymce();
		clearGjs();
		clearPlainText();
	});

	onMounted(() => {
		localStorage.removeItem('gjsProject');
		GrapeJS.loadScript().then(() => {
			if(props.modelValue){
				let c = JSON.parse(props.modelValue);
				if(c){
					if(c.editor == 'gjs'){
						useGrapeJs(false);
					}
					else if(c.editor == 'tinymce'){
						tinymceValue.value = c.content;
						useTinyMce();
					}
					else if(c.editor == 'plaintext'){
						plaintextValue.value = c.content;
						usePlainText();
					}
				}
			}
			else {
				showUseEditorButtons.value = true;
			}
		});
	});

	const setEditor = function(editor){
		selectedEditor.value = editor;
	}

	const emitContent = function(){
		let c = { editor: selectedEditor.value, content: null }
		if(selectedEditor.value == 'gjs'){
			c.content = {
				html: GrapeJS.editor.getHtml(),
				css: GrapeJS.editor.getCss({ avoidProtected: true }),
				data: GrapeJS.editor.getProjectData()
			}
		}
		else if(selectedEditor.value == 'tinymce'){
			c.content = tinymcsInstance.value.getContent();
		}
		else if(selectedEditor.value == 'plaintext'){
			c.content = plaintextValue.value;
		}
		this.$emit('update:modelValue', JSON.stringify(c));
	}

	const useGrapeJs = function(isNew=false){
		setEditor('gjs');
		clearTinymce();
		clearPlainText();

		let projectData =  block3col.content.data;
		if(props.modelValue && !isNew){
			let c = JSON.parse(props.modelValue);
			projectData = c.content.data;
		}

		if(typeof window['oliv_cms'] == 'undefined'){
			window['oliv_cms'] = {};
		}

		window.oliv_cms['gjs_project'] = projectData;

		GrapeJS.init();
	}
	const clearGjs = function(){
		GrapeJS.destroy();
		try {
			delete window.oliv_cms.gjs_project;
		} catch (error) {
			/** do nothing */
		}
	}

	const loadTinyMCE = function(){
		window.tinymce.init({
			selector: '#'+props.selector,
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
			tinymcsInstance.value = t[0];
		});
	}


	/** TinyMCE */
	const useTinyMce = function(){
		useplainText.value = false;
		setEditor('tinymce');
		loadTinyMCE();
		clearGjs();
		clearPlainText();
	}
	const clearTinymce = function(){
		tinymcsInstance.value = null;
		window.tinymce.remove('#'+props.selector);
	}


	/** Plain Text */
	const usePlainText = function(){
		useplainText.value = true;
		clearTinymce();
		clearGjs();
		setEditor('plaintext');
	}
	const clearPlainText = function (){
	}

	defineExpose({ emitContent });
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
		
		<div class="plaintext-wrapper" v-show="selectedEditor == 'plaintext'">
			<textarea v-model="plaintextValue" rows="10" class="w-100"></textarea>
		</div>
		<div class="gjs-wrapper" v-show="selectedEditor == 'gjs'" v-bind:class="{'max-size' : GrapeJS.is_maximized}">
			<div class="row">
				<div class="col-12">
					<div class="text-end bg-white p-2">
						<button class="btn btn-outline-primary btn-sm" title="maximize" v-on:click="GrapeJS.gjsMinMax()" v-show="!GrapeJS.is_maximized">
							<i class="fa-regular fa-window-maximize"></i>
						</button>
						<button class="btn btn-outline-primary btn-sm" title="maximize" v-on:click="GrapeJS.gjsMinMax()" v-show="GrapeJS.is_maximized">
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
			<button class="btn btn-outline-primary btn-sm me-3" v-on:click="usePlainText()" v-if="selectedEditor != 'plaintext'">
				Use Plain Text
			</button>
			<button class="btn btn-outline-primary btn-sm me-3" v-on:click="useTinyMce()" v-if="selectedEditor != 'tinymce'">
				Use TinyMCE
			</button>
			<button class="btn btn-outline-primary btn-sm" v-on:click="useGrapeJs(true)" v-if="selectedEditor != 'gjs'">
				Use Grape
			</button>
		</div>
	</div>
</template>