/**
 * docs 
 * https://grapesjs.com/docs/getting-started.html 
 */
import grapesjs from 'grapesjs';
import 'grapesjs/dist/css/grapes.min.css';
import 'grapesjs/dist/grapes.min.js';

import * as grapesjspresetwebpage from 'grapesjs-preset-webpage/dist/index.js';
import * as grapesjsblocksbasic from 'grapesjs-blocks-basic/dist/index.js';
import * as grapesjspluginforms from 'grapesjs-plugin-forms/dist/index.js';
import * as grapesjscomponentcountdown from 'grapesjs-component-countdown/dist/index.js';
import * as grapesjspluginexport from 'grapesjs-plugin-export/dist/index.js';
import * as grapesjscustomcode from 'grapesjs-custom-code/dist/index.js';
import * as grapesjsparserpostcss from 'grapesjs-parser-postcss/dist/index.js';
import * as grapesjstooltip from 'grapesjs-tooltip/dist/index.js';
import * as grapesjstuiimageeditor from 'grapesjs-tui-image-editor/dist/index.js';
import * as grapesjstyped from 'grapesjs-typed/dist/index.js';
import * as grapesjsstylebg from 'grapesjs-style-bg/dist/index.js';
// import * as grapesjstabs from 'grapesjs-tabs/dist/grapesjs-tabs.min.js';


const windowStoragePlugin = (editor) => {
	editor.Storage.add('window', {
		async load(options) {
			return window.oliv_cms['gjs_project'];
		},
	
		async store(data, options) {
			window.oliv_cms['gjs_project'] = data;
		}
	});
};

export default {
	container: '#gjs',
	editor: null,
	is_maximized: false,
	loadedScripts: 0,

	setContainer(container){
		this.container = container;
		return this;
	},

	loadScript(){
		return new Promise((resolve) => {
			if (typeof window === 'undefined') {
				resolve(false);
			}
			else {
				window['grapesjs-preset-webpage'] = grapesjspresetwebpage;
				window['grapesjs-blocks-basic'] = grapesjsblocksbasic;
				window['grapesjs-plugin-forms'] = grapesjspluginforms;
				window['grapesjs-component-countdown'] = grapesjscomponentcountdown;
				window['grapesjs-plugin-export'] = grapesjspluginexport;
				window['grapesjs-custom-code'] = grapesjscustomcode;
				window['grapesjs-parser-postcss'] = grapesjsparserpostcss;
				window['grapesjs-tooltip'] = grapesjstooltip;
				window['grapesjs-tui-image-editor'] = grapesjstuiimageeditor;
				window['grapesjs-typed'] = grapesjstyped;
				window['grapesjs-style-bg'] = grapesjsstylebg;
				// window['grapesjs-tabs'] = grapesjstabs
	
				resolve(true);
			}
		});
	},

	init(callback=null){
		if(!this.editor){
			this.editor = grapesjs.init({
				height: "400px",
				container: "#gjs",
				fromElement: true,
				showOffsets: true,
				assetManager: {
					embedAsBase64: true,
				},
				storageManager: {
					type: 'window',
					options: {
						window: {}
					}
				},
				selectorManager: { 
					componentFirst: true,
					options: {
						local: { // Options for the `local` type
							key: 'gjsProject', // The key for the local storage
						},
					}
				},
				styleManager: {
					sectors: [
						{
							name: "General",
							properties: [
								{
									extend: "float",
									type: "radio",
									default: "none",
									options: [
										{ value: "none", className: "fa fa-times" },
										{ value: "left", className: "fa fa-align-left" },
										{ value: "right", className: "fa fa-align-right" },
									],
								},
								"display",
								{ extend: "position", type: "select" },
								"top",
								"right",
								"left",
								"bottom",
							],
						},
						{
							name: "Dimension",
							open: false,
							properties: [
								"width",
								{
									id: "flex-width",
									type: "integer",
									name: "Width",
									units: ["px", "%"],
									property: "flex-basis",
									toRequire: 1,
								},
								"height",
								"max-width",
								"min-height",
								"margin",
								"padding",
							],
						},
						{
							name: "Typography",
							open: false,
							properties: [
								"font-family",
								"font-size",
								"font-weight",
								"letter-spacing",
								"color",
								"line-height",
								{
									extend: "text-align",
									options: [
										{ id: "left", label: "Left", className: "fa fa-align-left" },
										{ id: "center", label: "Center", className: "fa fa-align-center" },
										{ id: "right", label: "Right", className: "fa fa-align-right" },
										{ id: "justify", label: "Justify", className: "fa fa-align-justify" },
									],
								},
								{
									property: "text-decoration",
									type: "radio",
									default: "none",
									options: [
										{ id: "none", label: "None", className: "fa fa-times" },
										{ id: "underline", label: "underline", className: "fa fa-underline" },
										{ id: "line-through", label: "Line-through", className: "fa fa-strikethrough" },
									],
								},
								"text-shadow",
							],
						},
						{
							name: "Decorations",
							open: false,
							properties: [
								"opacity",
								"border-radius",
								"border",
								"box-shadow",
								"background", // { id: 'background-bg', property: 'background', type: 'bg' }
							],
						},
						{
							name: "Extra",
							open: false,
							buildProps: ["transition", "perspective", "transform"],
						},
						{
							name: "Flex",
							open: false,
							properties: [
								{
									name: "Flex Container",
									property: "display",
									type: "select",
									defaults: "block",
									list: [
										{ value: "block", name: "Disable" },
										{ value: "flex", name: "Enable" },
									],
								},
								{
									name: "Flex Parent",
									property: "label-parent-flex",
									type: "integer",
								},
								{
									name: "Direction",
									property: "flex-direction",
									type: "radio",
									defaults: "row",
									list: [
										{
											value: "row",
											name: "Row",
											className: "icons-flex icon-dir-row",
											title: "Row",
										},
										{
											value: "row-reverse",
											name: "Row reverse",
											className: "icons-flex icon-dir-row-rev",
											title: "Row reverse",
										},
										{
											value: "column",
											name: "Column",
											title: "Column",
											className: "icons-flex icon-dir-col",
										},
										{
											value: "column-reverse",
											name: "Column reverse",
											title: "Column reverse",
											className: "icons-flex icon-dir-col-rev",
										},
									],
								},
								{
									name: "Justify",
									property: "justify-content",
									type: "radio",
									defaults: "flex-start",
									list: [
										{
											value: "flex-start",
											className: "icons-flex icon-just-start",
											title: "Start",
										},
										{
											value: "flex-end",
											title: "End",
											className: "icons-flex icon-just-end",
										},
										{
											value: "space-between",
											title: "Space between",
											className: "icons-flex icon-just-sp-bet",
										},
										{
											value: "space-around",
											title: "Space around",
											className: "icons-flex icon-just-sp-ar",
										},
										{
											value: "center",
											title: "Center",
											className: "icons-flex icon-just-sp-cent",
										},
									],
								},
								{
									name: "Align",
									property: "align-items",
									type: "radio",
									defaults: "center",
									list: [
										{
											value: "flex-start",
											title: "Start",
											className: "icons-flex icon-al-start",
										},
										{
											value: "flex-end",
											title: "End",
											className: "icons-flex icon-al-end",
										},
										{
											value: "stretch",
											title: "Stretch",
											className: "icons-flex icon-al-str",
										},
										{
											value: "center",
											title: "Center",
											className: "icons-flex icon-al-center",
										},
									],
								},
								{
									name: "Flex Children",
									property: "label-parent-flex",
									type: "integer",
								},
								{
									name: "Order",
									property: "order",
									type: "integer",
									defaults: 0,
									min: 0,
								},
								{
									name: "Flex",
									property: "flex",
									type: "composite",
									properties: [
										{
											name: "Grow",
											property: "flex-grow",
											type: "integer",
											defaults: 0,
											min: 0,
										},
										{
											name: "Shrink",
											property: "flex-shrink",
											type: "integer",
											defaults: 0,
											min: 0,
										},
										{
											name: "Basis",
											property: "flex-basis",
											type: "integer",
											units: ["px", "%", ""],
											unit: "",
											defaults: "auto",
										},
									],
								},
								{
									name: "Align",
									property: "align-self",
									type: "radio",
									defaults: "auto",
									list: [
										{
											value: "auto",
											name: "Auto",
										},
										{
											value: "flex-start",
											title: "Start",
											className: "icons-flex icon-al-start",
										},
										{
											value: "flex-end",
											title: "End",
											className: "icons-flex icon-al-end",
										},
										{
											value: "stretch",
											title: "Stretch",
											className: "icons-flex icon-al-str",
										},
										{
											value: "center",
											title: "Center",
											className: "icons-flex icon-al-center",
										},
									],
								},
							],
						},
					],
				},
				plugins: [
					'grapesjs-preset-webpage',
					'grapesjs-blocks-basic',
					'grapesjs-plugin-forms',
					'grapesjs-component-countdown',
					'grapesjs-plugin-export',
					'grapesjs-custom-code',
					'grapesjs-parser-postcss',
					'grapesjs-tooltip',
					'grapesjs-tui-image-editor',
					'grapesjs-typed',
					'grapesjs-style-bg',
					'grapesjs-tabs',
					windowStoragePlugin
				],
				pluginsOpts: {
					"gjs-blocks-basic": { flexGrid: true },
					"grapesjs-tui-image-editor": {
						script: [
							"https://uicdn.toast.com/tui.code-snippet/v1.5.2/tui-code-snippet.min.js",
							"https://uicdn.toast.com/tui-color-picker/v2.2.7/tui-color-picker.min.js",
							"https://uicdn.toast.com/tui-image-editor/v3.15.2/tui-image-editor.min.js",
						],
						style: [
							"https://uicdn.toast.com/tui-color-picker/v2.2.7/tui-color-picker.min.css", 
							"https://uicdn.toast.com/tui-image-editor/v3.15.2/tui-image-editor.min.css"
						],
					},
					"grapesjs-tabs": {
						tabsBlock: { 
							category: "Extra" 
						},
					},
					"grapesjs-typed": {
						block: {
							category: "Extra",
							content: {
								type: "typed",
								"type-speed": 40,
								strings: ["Text row one", "Text row two", "Text row three"],
							},
						},
					},
					"grapesjs-preset-webpage": {
						modalImportTitle: "Import Template",
						modalImportLabel: '<div style="margin-bottom: 10px; font-size: 13px;">Paste here your HTML/CSS and click Import</div>',
						modalImportContent: function (editor) {
							return editor.getHtml() + "<style>" + editor.getCss() + "</style>";
						},
					}
				},
			});

			if(typeof callback == 'function'){
				this.editor.on('load', (data, res) => {
					callback(this.editor);
				});
				this.editor.on('update', (data, res) => {
					callback(this.editor);
				});
			}
		}
		else {
			console.log(this.editor);
		}
	},
	destroy(){
		try {
			this.editor.destroy();
			this.editor = null;
		} catch (error) {
			/** do nothing for now */
		}
	},
	gjsMinMax(){
		if(this.is_maximized){
			this.is_maximized = false;
		}
		else {
			this.is_maximized = true;
		}
	}
}