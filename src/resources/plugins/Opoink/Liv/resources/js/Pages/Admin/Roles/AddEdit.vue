<script setup>
	import { Head, Link, useForm } from '@inertiajs/vue3';
	import Default from '@@Plugins@@/Opoink/Liv/resources/js/Layouts/Admin/Default.vue';
	import { adminSideTabs } from '@@Plugins@@/Opoink/Liv/resources/js/States/admin.side.tabs';
	import { route } from 'ziggy-js';

	const props = defineProps(['propsdata']);

	const form = useForm({
		id: props.propsdata.role ? props.propsdata.role.id : '',
		role_name: props.propsdata.role ? props.propsdata.role.role_name : '',
		resource: props.propsdata.form_role_resources
	});

	const submit = () => {
		form.post(route('admin.users.admins.roles.saveaction'), {});
	};

	adminSideTabs.setDefaultTab('role-information').setQueryParam('active-tab');
</script>

<template>
    <Head :title="propsdata.page_name" />
	

	<Default>
		<div id="admin-user-add-edit">
			
			<div class="mt-5 mb-3">
				<h1 class="mb-0 fs-3">{{propsdata.page_name}}</h1>
			</div>

			<div class="row">
				<div class="col-6 offset-6">
					<div class="text-end pt-5 pb-3">
						<Link :href="route('admin.users.admins.roles.index')">
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
											:class="{'active' : adminSideTabs.isActiveTab('role-information', route())}"
										>
											Role Information
										</Link>
									</li>
								</ul>
							</div>
						</div>
						<div class="col-9">
							<input type="hidden" class="form-control" name="id" v-model="form.id">


							<div class="row mb-3">
								<div class="col-3 mt-0">
									<label for="formdata-role_name" class="col-form-label text-end d-block">Role Name</label>
								</div>
								<div class="col-9 mt-0">
									<input type="text" id="formdata-role_name" class="form-control" name="role_name" v-model="form.role_name">
									<small class="text-sm text-danger" v-if="form.errors.role_name">
										{{form.errors.role_name}}
									</small>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-3 mt-0">
									<label for="formdata-role_name" class="col-form-label text-end d-block">Role Resource</label>
								</div>
								<div class="col-9 mt-0">
									<div class="form-check" v-for="resource in propsdata.config_role_resources">
										<input class="form-check-input" type="checkbox" :id="resource.value" :value="resource.value" :true-value="[]" v-model="form.resource">
										<label class="form-check-label" :for="resource.value">
											{{resource.label}}
										</label>
									</div>
								</div>
							</div>


						</div>
					</div>
				</div>
			</div>
		</div>
	</Default>
	
</template>
