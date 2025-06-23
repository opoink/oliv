<script setup>
	import { Head, Link, useForm, router } from '@inertiajs/vue3';
	import Default from '@@Plugins@@/Opoink/Liv/resources/js/Layouts/Admin/Default.vue';
	import { adminSideTabs } from '@@Plugins@@/Opoink/Liv/resources/js/States/admin.side.tabs';
	import { loader } from '@@Plugins@@/Opoink/Liv/resources/js/States/loader.js';
	import { toast } from '@@Plugins@@/Opoink/Liv/resources/js/States/toast.js';
	import { onBeforeMount, onMounted, ref } from 'vue';
	import Editor from '@@Plugins@@/Opoink/Cms/resources/js/Components/Editor.vue';
	import { getAdminUrl } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/common.js';



	const props = defineProps([
		'propsdata'
	]);

	const form = useForm({
		id: props.propsdata.cms_block ? props.propsdata.cms_block.id : '',
		name: props.propsdata.cms_block ? props.propsdata.cms_block.name : '',
		identifier: props.propsdata.cms_block ? props.propsdata.cms_block.identifier : '',
		content: props.propsdata.cms_block ? props.propsdata.cms_block.content : '',
	});

	const editorRef = ref(null);

	onBeforeMount(() => {
		adminSideTabs.setActiveTab('block-content');
	});

	onMounted(() => {
		if(props.propsdata.cms_block){
			let identifier = props.propsdata.cms_block.identifier;
			identifier = identifier.split('-');

			for (let i = 0; i < identifier.length; i++) {
				identifier[i] = identifier[i][0].toUpperCase() + identifier[i].substr(1);
			}
			identifier = identifier.join('');
		}
	});

	const isEdit = function(){
		return parseInt(form.id) > 0;
	}

	const submit = function(){
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
					url: getAdminUrl('/cms/block/save'),
					data: {
						id: form.id,
						name: form.name,
						identifier: form.identifier,
						content: form.content,
					}
				})
				.then(response => {
					toast.add(response.data.message, 'success');
					loader.setLoader(false);
					if(!isEdit()){
						router.visit( getAdminUrl('/cms/block/edit/' + response.data.data.id) );
					}
				})
				.catch(error => {
					if(Array.isArray(error.response.data.errors)){
						error.response.data.errors.forEach(error => {
							toast.add(error, 'danger');
						});
					}
					else {
						if(typeof error.response.data.errors.identifier != 'undefined'){
							error.response.data.errors.identifier.forEach(error => {
								form.setError('identifier', error);
							});
						}
						if(typeof error.response.data.errors.name != 'undefined'){
							error.response.data.errors.name.forEach(error => {
								form.setError('name', error);
							});
						}
					}
					loader.setLoader(false)
				});
			}
		}, 100)
	}
</script>


<template>
    <Head :title="propsdata.page_name" />
	

	<Default>
		<div id="admin-cms-block-add-edit">
			<div class="row">
				<div class="col-6 offset-6">
					<div class="text-end pt-5 pb-3">
						<Link :href="getAdminUrl('/cms/block')">
							<button class="btn btn-outline-primary">
								<i class="fa-solid fa-left-long"></i><span class="ms-3">Back</span> 
							</button>
						</Link>
						<button class="btn btn-primary ms-3" :disabled="form.processing" @click="submit()">
							<i class="fa-solid fa-save"></i><span class="ms-3">Save</span> 
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
											:class="{'active' : adminSideTabs.isActiveTab == 'block-content'}"
										>
											Block Content
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
										:disabled="isEdit()"
									>
									<small class="text-sm text-danger" v-if="form.errors.identifier">
										{{form.errors.identifier}}
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
