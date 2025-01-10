<script setup>
	import { Head, Link, useForm } from '@inertiajs/vue3';
	import Default from '@@Plugins@@/Opoink/Liv/resources/js/Layouts/Admin/Default.vue';
	import { adminSideTabs } from '@@Plugins@@/Opoink/Liv/resources/js/States/admin.side.tabs';
	import { loader } from '@@Plugins@@/Opoink/Liv/resources/js/States/loader.js';
	import { toast } from '@@Plugins@@/Opoink/Liv/resources/js/States/toast.js';

	
	import Editor from '@@Plugins@@/Opoink/Cms/resources/js/Components/Editor.vue';
</script>

<script>
	export default {
		data() {
			return {
				form: useForm({
					id: this.propsdata.cms_page ? this.propsdata.cms_page.id : '',
					name: this.propsdata.cms_page ? this.propsdata.cms_page.name : '',
					identifier: this.propsdata.cms_page ? this.propsdata.cms_page.identifier : '',
					meta_title: this.propsdata.cms_page ? this.propsdata.cms_page.meta_title : '',
					meta_keywords: this.propsdata.cms_page ? this.propsdata.cms_page.meta_keywords : '',
					meta_description: this.propsdata.cms_page ? this.propsdata.cms_page.meta_description : '',
					content: this.propsdata.cms_page ? this.propsdata.cms_page.content : '',
				}),
				componentName: ''
			}
		},
		props: ['propsdata'],
		beforeMount: function(){
			adminSideTabs.setDefaultTab('tab-page-content').setQueryParam('active-tab');
		},
		methods: {
			submit() {
				if(!this.form.content.length){
					toast.add('Content is required', 'danger');
				}
				else {
					this.form.content = JSON.parse(this.form.content);
	
					loader.setLoader(true);
					axios({
						method: 'post',
						url: route('admin.cms.pages.saveaction'),
						data: {
							id: this.form.id,
							name: this.form.name,
							identifier: this.form.identifier,
							meta_title: this.form.meta_title,
							meta_keywords: this.form.meta_keywords,
							meta_description: this.form.meta_description,
							content: this.form.content,
						}
					})
					.then(response => {
						toast.add(response.data.message, 'success');
						loader.setLoader(false);
						if(!this.isEdit()){
							this.$inertia.visit( route('admin.cms.pages.edit', {id: response.data.data.id}) );
						}
					})
					.catch(error => {
						console.log('error error', error);
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
			},
			isEdit(){
				return parseInt(this.form.id) > 0;
			}
		},
		mounted: function(){
			if(this.propsdata.cms_page){
				let identifier = this.propsdata.cms_page.identifier;
				identifier = identifier.split('-');

				for (let i = 0; i < identifier.length; i++) {
					identifier[i] = identifier[i][0].toUpperCase() + identifier[i].substr(1);
				}
				identifier = identifier.join('');

				this.componentName = identifier;
			}
		}
	}
</script>

<template>
	<Head :title="propsdata.page_name" />
	<Default>
		<div id="admin-cms-page-add-edit">

			<div class="row">
				<div class="col-6 offset-6">
					<div class="text-end pt-5 pb-3">
						<Link :href="route('admin.cms.pages.index')">
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
											:class="{'active' : adminSideTabs.isActiveTab('tab-page-content', route())}"
										>
											Page Content
										</Link>
									</li>
								</ul>
							</div>
						</div>



						<div class="col-9">
							<div class="row mb-3" v-if="propsdata.cms_page">
								<div class="col-12">
									<p class="mb-0">Use the page by importing it on your Vue Component template</p>
									<p class="mb-0">
										<code>
											import {{componentName}} from '@/Cms/Pages/{{componentName}}/VueComponent.vue';
											<br>
											&lt;{{componentName}}/&gt;
										</code>
									</p>
								</div>
							</div>

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
									<Editor :selector="'cms-block-editor'" :grapjsvalue="form.grapjsvalue" v-model="form.content"  />
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