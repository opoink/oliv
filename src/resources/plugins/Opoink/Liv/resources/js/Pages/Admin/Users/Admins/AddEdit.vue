<script setup>
	import { Head, Link, router } from '@inertiajs/vue3';
	import Default from '@@Plugins@@/Opoink/Liv/resources/js/Layouts/Admin/Default.vue';
	import { adminSideTabs } from '@@Plugins@@/Opoink/Liv/resources/js/States/admin.side.tabs';
	import { loader } from '@@Plugins@@/Opoink/Liv/resources/js/States/loader.js';
	import { FormData } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/form.data';
	import { onBeforeMount, onMounted, reactive, ref } from 'vue';
	import { toast } from '@@Plugins@@/Opoink/Liv/resources/js/States/toast.js';
	import { getAdminUrl } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/common.js';


	const props = defineProps(['propsdata']);
	const adminFormData = reactive(new FormData());

	// const form = useForm({
	// 	id: props.propsdata.admin ? props.propsdata.admin.id : '',
	// 	firstname: props.propsdata.admin ? props.propsdata.admin.firstname : '',
	// 	lastname: props.propsdata.admin ? props.propsdata.admin.lastname : '',
	// 	email: props.propsdata.admin ? props.propsdata.admin.email : '',
	// 	password: '',
	// 	admin_user_role_id: props.propsdata.admin ? props.propsdata.admin.admin_user_role_id : '',
	// });

	onBeforeMount(() => {
		adminFormData.fields['id'] = props.propsdata.admin ? props.propsdata.admin.id : '';
		adminFormData.fields['firstname'] = props.propsdata.admin ? props.propsdata.admin.firstname : '';
		adminFormData.fields['lastname'] = props.propsdata.admin ? props.propsdata.admin.lastname : '';
		adminFormData.fields['email'] = props.propsdata.admin ? props.propsdata.admin.email : '';
		adminFormData.fields['password'] = '';
		adminFormData.fields['admin_user_role_id'] = props.propsdata.admin ? props.propsdata.admin.admin_user_role_id : '';
		adminFormData.setForm();
	});

	const submit = () => {
		loader.setLoader(true);
		axios({
			method: 'post',
			url: route('admin.users.admins.saveaction'),
			data: adminFormData.form.data()
		})
		.then(response => {
			toast.add(response.data.message, 'success');
			loader.setLoader(false);
			router.visit( route('admin.users.admins.edit', {id: response.data.data.id}) );
		})
		.catch(error => {
			loader.setLoader(false);
			if(typeof error.response != 'undefined'){
				if(typeof error.response.data.message == 'string'){		
					toast.add(error.response.data.message, 'danger');
				}
				else {
					adminFormData.form.setError(error.response.data.errors);
				}
			}

		});
	};

	adminSideTabs.setActiveTab('admin-information');

</script>

<template>
    <Head :title="propsdata.page_name" />
	

	<Default>
		<div id="admin-user-add-edit">
			<div class="row">
				<div class="col-6 offset-6">
					<div class="text-end pt-5 pb-3">
						<Link :href="getAdminUrl('/users/admins')">
							<button class="btn btn-outline-primary">
								<i class="fa-solid fa-left-long"></i><span class="ms-3">Back</span> 
							</button>
						</Link>
						<button class="btn btn-primary ms-3" @click="submit()">
							<i class="fa-solid fa-save"></i><span class="ms-3">Save</span> 
						</button>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-12">
					<div class="row">
						<div class="col-3">
							<div class="admin-side-tabs">
								<ul>
									<li>
										<a 
											href="#" 
											class="admin-side-tab-item"
											:class="{'active' : adminSideTabs.isActiveTab == 'admin-information'}"
											@click.prevent="adminSideTabs.setActiveTab('admin-information')"
										>
											Admin Information
										</a>
									</li>
								</ul>
							</div>
						</div>
						<div class="col-9">
							<input type="hidden" class="form-control" name="id">


							<div class="row mb-3">
								<div class="col-2 mt-0">
									<label for="adminFormData-firstname" class="col-form-label text-end d-block">
										Firstname <span class="text-danger">*</span>
									</label>
								</div>
								<div class="col-6 mt-0">
									<input type="text" id="adminFormData-firstname" class="form-control" name="firstname" v-model="adminFormData.form.firstname">
									<template v-if="adminFormData.form.errors.firstname">
										<small class="text-sm text-danger" v-for="error in adminFormData.form.errors.firstname">
											{{error}}
										</small>
									</template>
								</div>
							</div>
							<!-- <div class="row mb-3">
								<div class="col-3 mt-0">
									<label for="formdata-firstname" class="col-form-label text-end d-block">Firstname</label>
								</div>
								<div class="col-9 mt-0">
									<input type="text" id="formdata-firstname" class="form-control" name="firstname" v-model="form.firstname">
									<small class="text-sm text-danger" v-if="form.errors.firstname">
										{{form.errors.firstname}}
									</small>
								</div>
							</div> -->

							<div class="row mb-3">
								<div class="col-2 mt-0">
									<label for="adminFormData-lastname" class="col-form-label text-end d-block">
										Lastname <span class="text-danger">*</span>
									</label>
								</div>
								<div class="col-6 mt-0">
									<input type="text" id="adminFormData-lastname" class="form-control" name="lastname" v-model="adminFormData.form.lastname">
									<template v-if="adminFormData.form.errors.lastname">
										<small class="text-sm text-danger" v-for="error in adminFormData.form.errors.lastname">
											{{error}}
										</small>
									</template>
								</div>
							</div>
							<!-- <div class="row mb-3">
								<div class="col-3 mt-0">
									<label for="formdata-lastname" class="col-form-label text-end d-block">Lastname</label>
								</div>
								<div class="col-9 mt-0">
									<input type="text" id="formdata-lastname" class="form-control" name="lastname" v-model="form.lastname">
									<small class="text-sm text-danger" v-if="form.errors.lastname">
										{{form.errors.lastname}}
									</small>
								</div>
							</div> -->


							<div class="row mb-3">
								<div class="col-2 mt-0">
									<label for="adminFormData-email" class="col-form-label text-end d-block">
										Email <span class="text-danger">*</span>
									</label>
								</div>
								<div class="col-6 mt-0">
									<input type="text" id="adminFormData-email" class="form-control" name="email" v-model="adminFormData.form.email">
									<template v-if="adminFormData.form.errors.email">
										<small class="text-sm text-danger" v-for="error in adminFormData.form.errors.email">
											{{error}}
										</small>
									</template>
								</div>
							</div>
							<!-- <div class="row mb-3">
								<div class="col-3 mt-0">
									<label for="formdata-email" class="col-form-label text-end d-block">Email</label>
								</div>
								<div class="col-9 mt-0">
									<input type="text" id="formdata-email" class="form-control" name="email" v-model="form.email">
									<small class="text-sm text-danger" v-if="form.errors.email">
										{{form.errors.email}}
									</small>
								</div>
							</div> -->

							<div class="row mb-3">
								<div class="col-2 mt-0">
									<label for="adminFormData-password" class="col-form-label text-end d-block">
										Password <span class="text-danger">*</span>
									</label>
								</div>
								<div class="col-6 mt-0">
									<input type="text" id="adminFormData-password" class="form-control" name="password" v-model="adminFormData.form.password">
									<template v-if="adminFormData.form.errors.password">
										<small class="text-sm text-danger" v-for="error in adminFormData.form.errors.password">
											{{error}}
										</small>
									</template>
								</div>
							</div>
							<!-- <div class="row mb-3">
								<div class="col-3 mt-0">
									<label for="formdata-password" class="col-form-label text-end d-block">Password</label>
								</div>
								<div class="col-9 mt-0">
									<input type="text" id="formdata-password" class="form-control" name="password" v-model="form.password">
									<small class="text-sm text-danger" v-if="form.errors.password">
										{{form.errors.password}}
									</small>
								</div>
							</div> -->


							<div class="row mb-3">
								<div class="col-2 mt-0">
									<label for="adminFormData-admin_user_role_id" class="col-form-label text-end d-block">
										Admin User Role ID <span class="text-danger">*</span>
									</label>
								</div>
								<div class="col-6 mt-0">
									<select class="form-select" id="adminFormData-admin_user_role_id" name="admin_user_role_id" aria-label="Default select example" v-model="adminFormData.form.admin_user_role_id">
										<option value="">Select Admin User Role</option>
										<template v-for="role in propsdata.admin_user_roles">
											<option :value="role.id">{{role.role_name}}</option>
										</template>
									</select>
									<template v-if="adminFormData.form.errors.admin_user_role_id">
										<small class="text-sm text-danger" v-for="error in adminFormData.form.errors.admin_user_role_id">
											{{error}}
										</small>
									</template>
								</div>
							</div>
							<!-- <div class="row mb-3">
								<div class="col-3 mt-0">
									<label for="formdata-admin_user_role_id" class="col-form-label text-end d-block">Admin User Role ID</label>
								</div>
								<div class="col-9 mt-0">
									<select class="form-select" aria-label="Default select example" v-model="form.admin_user_role_id">
										<option value="">Select Admin User Role</option>
										<template v-for="role in propsdata.admin_user_roles">
											<option :value="role.id">{{role.role_name}}</option>
										</template>
									</select>
									<small class="text-sm text-danger" v-if="form.errors.admin_user_role_id">
										{{form.errors.admin_user_role_id}}
									</small>
								</div>
							</div> -->



						</div>
					</div>
				</div>
			</div>
		</div>
	</Default>
	
</template>
