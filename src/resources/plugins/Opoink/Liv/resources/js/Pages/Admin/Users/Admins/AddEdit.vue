<script setup>
	import { Head, Link, useForm } from '@inertiajs/vue3';
	import Default from '@@Plugins@@/Opoink/Liv/resources/js/Layouts/Admin/Default.vue';
	import { adminSideTabs } from '@@Plugins@@/Opoink/Liv/resources/js/States/admin.side.tabs';
	import { route } from 'ziggy-js';

	const props = defineProps(['propsdata']);

	const form = useForm({
		id: props.propsdata.admin ? props.propsdata.admin.id : '',
		firstname: props.propsdata.admin ? props.propsdata.admin.firstname : '',
		lastname: props.propsdata.admin ? props.propsdata.admin.lastname : '',
		email: props.propsdata.admin ? props.propsdata.admin.email : '',
		password: '',
		admin_user_role_id: props.propsdata.admin ? props.propsdata.admin.admin_user_role_id : '',
	});

	const submit = () => {
		form.post(route('admin.users.admins.saveaction'), {
			onFinish: () => form.reset('password'),
		});
	};

	adminSideTabs.setDefaultTab('admin-information').setQueryParam('active-tab');

</script>

<template>
    <Head :title="propsdata.page_name" />
	

	<Default>
		<div id="admin-user-add-edit">
			<div class="row">
				<div class="col-6 offset-6">
					<div class="text-end pt-5 pb-3">
						<Link :href="route('admin.users.admins.index')">
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
				<div class="col-8">
					<div class="row">
						<div class="col-3">
							<div class="admin-side-tabs">
								<ul>
									<li>
										<Link 
											href="#" 
											class="admin-side-tab-item"
											:class="{'active' : adminSideTabs.isActiveTab('admin-information', route())}"
										>
											Admin Information
										</Link>
									</li>
								</ul>
							</div>
						</div>
						<div class="col-9">
							<input type="hidden" class="form-control" name="id" v-model="form.id">


							<div class="row mb-3">
								<div class="col-3 mt-0">
									<label for="formdata-firstname" class="col-form-label text-end d-block">Firstname</label>
								</div>
								<div class="col-9 mt-0">
									<input type="text" id="formdata-firstname" class="form-control" name="firstname" v-model="form.firstname">
									<small class="text-sm text-danger" v-if="form.errors.firstname">
										{{form.errors.firstname}}
									</small>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-3 mt-0">
									<label for="formdata-lastname" class="col-form-label text-end d-block">Lastname</label>
								</div>
								<div class="col-9 mt-0">
									<input type="text" id="formdata-lastname" class="form-control" name="lastname" v-model="form.lastname">
									<small class="text-sm text-danger" v-if="form.errors.lastname">
										{{form.errors.lastname}}
									</small>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-3 mt-0">
									<label for="formdata-email" class="col-form-label text-end d-block">Email</label>
								</div>
								<div class="col-9 mt-0">
									<input type="text" id="formdata-email" class="form-control" name="email" v-model="form.email">
									<small class="text-sm text-danger" v-if="form.errors.email">
										{{form.errors.email}}
									</small>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-3 mt-0">
									<label for="formdata-password" class="col-form-label text-end d-block">Password</label>
								</div>
								<div class="col-9 mt-0">
									<input type="text" id="formdata-password" class="form-control" name="password" v-model="form.password">
									<small class="text-sm text-danger" v-if="form.errors.password">
										{{form.errors.password}}
									</small>
								</div>
							</div>

							<div class="row mb-3">
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
							</div>



						</div>
					</div>
				</div>
			</div>
		</div>
	</Default>
	
</template>
