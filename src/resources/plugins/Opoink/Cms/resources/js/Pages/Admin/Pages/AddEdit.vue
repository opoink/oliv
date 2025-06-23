<script setup>
	import { Head, Link, useForm, router } from '@inertiajs/vue3';
	import Default from '@@Plugins@@/Opoink/Liv/resources/js/Layouts/Admin/Default.vue';
	import { adminSideTabs } from '@@Plugins@@/Opoink/Liv/resources/js/States/admin.side.tabs';
	import { loader } from '@@Plugins@@/Opoink/Liv/resources/js/States/loader.js';
	import { toast } from '@@Plugins@@/Opoink/Liv/resources/js/States/toast.js';
	import { onBeforeMount, onMounted, ref } from 'vue';
	import { getAdminUrl } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/common.js';

	
	import Editor from '@@Plugins@@/Opoink/Cms/resources/js/Components/Editor.vue';

	const props = defineProps([
		'propsdata'
	]);
	
	const form = useForm({
		id: props.propsdata.cms_page ? props.propsdata.cms_page.id : '',
		name: props.propsdata.cms_page ? props.propsdata.cms_page.name : '',
		identifier: props.propsdata.cms_page ? props.propsdata.cms_page.identifier : '',
		meta_title: props.propsdata.cms_page ? props.propsdata.cms_page.meta_title : '',
		meta_keywords: props.propsdata.cms_page ? props.propsdata.cms_page.meta_keywords : '',
		meta_description: props.propsdata.cms_page ? props.propsdata.cms_page.meta_description : '',
		content: props.propsdata.cms_page ? props.propsdata.cms_page.content : '',
	});

	const editorRef = ref(null);

	onBeforeMount(() => {
		adminSideTabs.setActiveTab('tab-page-content');
	});

	onMounted(() => {
		if(props.propsdata.cms_page){
			let identifier = props.propsdata.cms_page.identifier;
			identifier = identifier.split('-');

			for (let i = 0; i < identifier.length; i++) {
				identifier[i] = identifier[i][0].toUpperCase() + identifier[i].substr(1);
			}
			identifier = identifier.join('');
		}
	});

	const submit = function() {
		editorRef.value.emitContent();
		setTimeout(() => {
			if(!form.content.length){
				toast.add('Content is required', 'danger');
			}
			else {
				form.content = JSON.parse(form.content);
				loader.setLoader(true);
				axios({
					method: 'post',
					url: getAdminUrl('/cms/pages/save'),
					data: {
						id: form.id,
						name: form.name,
						identifier: form.identifier,
						meta_title: form.meta_title,
						meta_keywords: form.meta_keywords,
						meta_description: form.meta_description,
						content: form.content,
					}
				})
				.then(response => {
					toast.add(response.data.message, 'success');
					loader.setLoader(false);
					if(!isEdit()){
						router.visit( getAdminUrl('/cms/pages/edit/' + response.data.data.id) );
					}
				})
				.catch(error => {
					let errors = error.response.data.errors;
					if(Array.isArray(errors)){
						errors.forEach(error => {
							toast.add(error, 'danger');
						});
					}
					else {
						Object.keys(errors).forEach((key) => {
							errors[key].forEach(error => {
								this.form.setError(key, error);
							});
						});
					}
					loader.setLoader(false)
				});
			}
		});
	}

	const isEdit = function(){
		return parseInt(form.id) > 0;
	}
</script>
<template>
	<Head :title="propsdata.page_name" />
	<Default>
		<div id="admin-cms-page-add-edit">

			<div class="row">
				<div class="col-6 offset-6">
					<div class="text-end pt-5 pb-3">
						<Link :href="getAdminUrl('/cms/pages')">
							<button class="btn btn-outline-primary">
								<i class="fa-solid fa-left-long"></i><span class="ms-3">Back</span> 
							</button>
						</Link>
						<button class="btn btn-primary ms-3" :disabled="form.processing" @click="submit()">
							<i class="fa-solid fa-save"></i><span class="ms-3">Save ss</span> 
						</button>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-10">
					<div class="row">
						<div class="col-3">
							<div class="admin-side-tabs">
								<ul>
									<li>
										<Link 
											href="#" 
											class="admin-side-tab-item"
											:class="{'active' : adminSideTabs.isActiveTab == 'tab-page-content'}"
										>
											Page Content
										</Link>
									</li>
								</ul>
							</div>
						</div>



						<div class="col-9">
							<input type="hidden" class="form-control" name="id" v-model="form.id">
							<div class="row mb-3">
								<div class="col-6">
									<label for="formdata-name" class="form-label d-block">Name</label>
									<input type="text" id="formdata-name" class="form-control" name="name" v-model="form.name">
									<small class="text-sm text-danger" v-if="form.errors.name">
										{{form.errors.name}}
									</small>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-6">
									<label for="formdata-identifier" class="form-label d-block">Identifier</label>
									<input 
										type="text" 
										id="formdata-identifier" 
										class="form-control" 
										name="identifier" 
										v-model="form.identifier"
									>
									<small class="text-sm text-danger" v-if="form.errors.identifier">
										{{form.errors.identifier}}
									</small>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-6">
									<label for="formdata-meta_title" class="form-label d-block">Meta Title</label>
									<input type="text" id="formdata-meta_title" class="form-control" name="meta_title" v-model="form.meta_title">
									<small class="text-sm text-danger" v-if="form.errors.meta_title">
										{{form.errors.meta_title}}
									</small>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-6">
									<label for="formdata-meta_keywords" class="form-label d-block">Meta Keywords</label>
									<textarea class="form-control" id="formdata-meta_keywords" rows="3" v-model="form.meta_keywords"></textarea>
									<small class="text-sm text-danger" v-if="form.errors.meta_keywords">
										{{form.errors.meta_keywords}}
									</small>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-6">
									<label for="formdata-meta_description" class="form-label d-block">Meta Description</label>
									<textarea class="form-control" id="formdata-meta_description" rows="3" v-model="form.meta_description"></textarea>
									<small class="text-sm text-danger" v-if="form.errors.meta_description">
										{{form.errors.meta_description}}
									</small>
								</div>
							</div>

							

							<div class="row mb-3">
								<div class="">
									<label for="formdata-content" class="form-label d-block">Content</label>
									<Editor :selector="'cms-block-editor'" :grapjsvalue="form.grapjsvalue" v-model="form.content" ref="editorRef"  />
									<small class="text-sm text-danger" v-if="form.errors.content">
										{{form.errors.content}}
									</small>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</Default>
</template>