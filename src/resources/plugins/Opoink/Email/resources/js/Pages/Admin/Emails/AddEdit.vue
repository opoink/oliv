<script setup>
	import { Head, Link, router } from '@inertiajs/vue3';
	import Default from '@@Plugins@@/Opoink/Liv/resources/js/Layouts/Admin/Default.vue';
	import { getAdminUrl } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/common';
	import { adminSideTabs } from '@@Plugins@@/Opoink/Liv/resources/js/States/admin.side.tabs';
	import { loader } from '@@Plugins@@/Opoink/Liv/resources/js/States/loader.js';
	import { toast } from '@@Plugins@@/Opoink/Liv/resources/js/States/toast.js';
	import { FormData as _FormData } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/form.data';
	import { reactive, ref } from 'vue';
	// import ModalConfirmmation from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ModalConfirmmation.vue';

	
	const props = defineProps(['propsdata']);

	const formData = reactive(new _FormData());
	formData.fields['id'] = props.propsdata.model? props.propsdata.model.id : '';
	formData.fields['name'] = props.propsdata.model? props.propsdata.model.name : '';
	formData.fields['subject'] = props.propsdata.model? props.propsdata.model.subject : '';
	formData.fields['content'] = props.propsdata.model? props.propsdata.model.content : '';
	formData.fields['css'] = props.propsdata.model? props.propsdata.model.css : '';
	formData.setForm();

	const isEdit = ref(props.propsdata.model ? true : false);

	adminSideTabs.setActiveTab('email-information');

	const save = function() {
		formData.form.clearErrors();

		let data = formData.form.data();
		let d = {
			name: formData.form.name,
			subject: formData.form.subject,
			content: formData.form.content,
			css: formData.form.css,
		}
		if(isEdit.value){
			d.id = formData.form.id;
		}

		loader.setLoader(true);
		axios({
			method: 'post',
			url: getAdminUrl('/emails/save'),
			data: d
		})
		.then(response => {
			toast.add(response.data.message, 'success');
			loader.setLoader(false);
			router.visit( getAdminUrl('/emails/edit/' + response.data.data.id) );
		})
		.catch(error => {
			loader.setLoader(false);
			if(typeof error.response.data.errors != 'undefined'){
				formData.form.setError(error.response.data.errors);
			}
			else if(typeof error.response.data.message == 'string'){
				toast.add(error.response.data.message, 'danger');
			}
		});
	}





	// const deleteConfirmModalElId = 'deleteConfirm';
	// const deleteConfirmModal = ref(null);
	// const deleteAction = () => {
	// 	if(props.propsdata.model){
	// 		deleteConfirmModal.value.show();
	// 	}
	// }
	// const deleteOnConfirm = function(){
	// 	loader.setLoader(true);
	// 	axios({
	// 		method: 'delete',
	// 		url: route('admin.emails.deleteaction', {id: formData.form.id})
	// 	})
	// 	.then(response => {
	// 		deleteConfirmModal.value.hide();
	// 		toast.add(response.data.message, 'success');
	// 		loader.setLoader(false);
	// 		router.visit( route('admin.emails') );
	// 	})
	// 	.catch(error => {
	// 		loader.setLoader(false);
	// 		if(typeof error.response.data.message == 'string'){
	// 			toast.add(error.response.data.message, 'danger');
	// 		}
	// 	});
	// }
	// const deleteOnClose = function(){}



	
	// onMounted(() => {
	// 	deleteConfirmModal.value = new bootstrap.Modal(document.getElementById(deleteConfirmModalElId), {
	// 		backdrop: 'static',
	// 		keyboard: true,
	// 		focus: true
	// 	});
	// });
</script>
<template>
	<Head :title="propsdata.page_name" />

	<Default>
		<div id="admin-email-add-edit">
			<div class="mt-5 mb-3">
				<h1 class="mb-0 fs-3">
					{{propsdata.page_name}}<span v-if="propsdata.model?.name">: {{propsdata.model.name}}</span>
				</h1>
			</div>

			<div class="row">
				<div class="col-6 offset-6">
					<div class="text-end pt-5 pb-3">
						<Link :href="getAdminUrl('/emails')">
							<button class="btn btn-outline-secondary">
								<i class="fa-solid fa-left-long"></i><span class="ms-3">Back</span> 
							</button>
						</Link>
						<!-- <button class="btn btn-outline-secondary ms-3" @click="deleteAction()" v-if="props.propsdata.model">
							<i class="fa-solid fa-trash"></i><span class="ms-3">Delete</span> 
						</button> -->
						<button class="btn btn-primary ms-3" @click="save()">
							<i class="fa-solid fa-save"></i><span class="ms-3">Save</span> 
						</button>
					</div>
				</div>
			</div>


			<div class="row">
				<div class="col-3">
					<div class="admin-side-tabs">
						<ul>
							<li>
								<a href="#" class="admin-side-tab-item" :class="{'active' : adminSideTabs.isActiveTab == 'email-information'}">
									Email Information
								</a>
							</li>
						</ul>
					</div>
				</div>




				<div class="col-9">
					<input type="hidden" id="formData-id" class="form-control" name="id" v-model="formData.form.id">

					<div class="row mb-3">
						<div class="col-2 mt-0">
							<label for="formData-name" class="col-form-label text-end d-block">
								Name <span class="text-danger">*</span>
							</label>
						</div>
						<div class="col-6 mt-0">
							<input type="text" id="formData-name" class="form-control" name="name" v-model="formData.form.name">
							<template v-if="formData.form.errors.name">
								<small class="text-sm text-danger d-block" v-for="error in formData.form.errors.name">{{error}}</small>
							</template>
						</div>
					</div>

					<div class="row mb-3">
						<div class="col-2 mt-0">
							<label for="formData-subject" class="col-form-label text-end d-block">
								Subject <span class="text-danger">*</span>
							</label>
						</div>
						<div class="col-6 mt-0">
							<input type="text" id="formData-subject" class="form-control" name="subject" v-model="formData.form.subject">
							<template v-if="formData.form.errors.subject">
								<small class="text-sm text-danger d-block" v-for="error in formData.form.errors.subject">{{error}}</small>
							</template>
						</div>
					</div>

					<div class="row mb-3">
						<div class="col-2 mt-0">
							<label for="formData-content" class="col-form-label text-end d-block">
								Content <span class="text-danger">*</span>
							</label>
						</div>
						<div class="col-6 mt-0">
							<textarea class="form-control" id="statusFormData-description" rows="10" name="description" v-model="formData.form.content"></textarea>
							<template v-if="formData.form.errors.content">
								<small class="text-sm text-danger d-block" v-for="error in formData.form.errors.content">{{error}}</small>
							</template>
						</div>
					</div>

					<div class="row mb-3">
						<div class="col-2 mt-0">
							<label for="formData-css" class="col-form-label text-end d-block">
								CSS <span class="text-danger">*</span>
							</label>
						</div>
						<div class="col-6 mt-0">
							<textarea class="form-control" id="statusFormData-description" rows="10" name="description" v-model="formData.form.css"></textarea>
							<template v-if="formData.form.errors.css">
								<small class="text-sm text-danger" v-for="error in formData.form.errors.css">{{error}}</small>
							</template>
						</div>
					</div>



				</div>
			</div>



		</div>
	</Default>





	<ModalConfirmmation 
		:id="deleteConfirmModalElId" 
		title="Delete Email Content" 
		buttoncloselabel="No"
		buttonconfirmlabel="Yes"
		:onconfirm="deleteOnConfirm"
		:onclose="deleteOnClose"
	>
		Do you want to delete this email content?
	</ModalConfirmmation>
</template>