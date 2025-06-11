<script setup>
	import { Head, Link, router, useForm } from '@inertiajs/vue3';
	import { onBeforeMount, onMounted, ref } from 'vue';
	import moment from "moment";
	
	import Default from '@@Plugins@@/Opoink/Liv/resources/js/Layouts/Admin/Default.vue';
	import Listing from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/Listing.vue';
	import ModalConfirmmation from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ModalConfirmmation.vue';
	import ListingThSort from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ListingThSort.vue';

	import { loader } from '@@Plugins@@/Opoink/Liv/resources/js/States/loader.js';
	import { toast } from '@@Plugins@@/Opoink/Liv/resources/js/States/toast.js';
	import { getAdminUrl } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/common.js';

	const props = defineProps(['propsdata', 'errors']);

	const confirmModalOptionElId = 'userDeleteConfirm';
	const confirmModalContenData = ref(null);
	const confirmModal = ref(null);

	const isShowFilter = ref(false);
	const showFilters = function(){
		if(isShowFilter.value){
			isShowFilter.value = false;
		}
		else {
			isShowFilter.value = true;
		}
	}
	const filterForm = useForm({
		id: {
			from: '',
			to: ''
		},
		firstname: '',
		lastname: '',
		email: '',
		admin_user_role_id: '',
		created_at: '',
		updated_at: '',
	});
	const sortOrder = ref({
		key: '',
		value: 'desc'
	});

	const applyFilters = function(){
		let filters = [];
		
		let idValues = {}
		if(filterForm.id.from){
			idValues['from'] = filterForm.id.from
		}
		if(filterForm.id.to){
			idValues['to'] = filterForm.id.to
		}

		if(typeof idValues.from != 'undefined' || typeof idValues.to != 'undefined'){
			filters.push({
				key: 'id',
				values: idValues
			});
		}
		if(filterForm.firstname){
			filters.push({key: 'firstname', value: filterForm.firstname});
		}
		if(filterForm.lastname){
			filters.push({key: 'lastname', value: filterForm.lastname});
		}
		if(filterForm.email){
			filters.push({key: 'email', value: filterForm.email});
		}
		if(filterForm.admin_user_role_id){
			filters.push({key: 'admin_user_role_id', value: filterForm.admin_user_role_id});
		}
		if(filterForm.created_at){
			filters.push({key: 'created_at', value: filterForm.created_at});
		}
		if(filterForm.updated_at){
			filters.push({key: 'updated_at', value: filterForm.updated_at});
		}

		let queryParams = {
			sort_order: sortOrder.value,
			filters: filters
		};

		router.visit( getAdminUrl('/users/admins', queryParams) );
	}

	const removeFilter = function(){
		setTimeout(() => {
			applyFilters();
		}, 100);
	}

	const setSortOrder = function(key){
		sortOrder.value.key = key;
		if(sortOrder.value.value == 'asc'){
			sortOrder.value.value = 'desc';
		}
		else {
			sortOrder.value.value = 'asc';
		}
		applyFilters();
	}

	onBeforeMount(() => {
		try {
			Object.keys(props.errors).forEach(function(key) {
				toast.add(props.errors[key], 'danger');
			});
		} catch (error) {
			console.log(error)
		}
	});

	onMounted(() => {
		confirmModal.value = new bootstrap.Modal(document.getElementById(confirmModalOptionElId), {
			backdrop: 'static',
			keyboard: true,
			focus: true
		});

		if(props.propsdata.sort_order){
			sortOrder.value = props.propsdata.sort_order;
		}

		if(props.propsdata.filters){
			props.propsdata.filters.forEach(filter => {
				if(typeof filter.value != 'undefined'){
					filterForm[filter.key] = filter.value;
				}
				if(typeof filter.values != 'undefined'){
					filterForm[filter.key] = filter.values;
				}
			});
		}
	});

	const deleteItem = (data) => {
		confirmModalContenData.value = data;
		confirmModal.value.show();
	}
	function deleteOnConfirm(){
		loader.setLoader(true);
		confirmModal.value.hide();

		axios({
			method: 'delete',
			url: getAdminUrl('/users/admins/delete/' + confirmModalContenData.value.id),
		})
		.then(response => {
			toast.add(response.data.message, 'success');
			router.reload();
			loader.setLoader(false);
		})
		.catch(error => {
			loader.setLoader(false);
			error.response.data.errors.forEach(error => {
				toast.add(error, 'danger');
			});
		});
	}

	function deleteOnClose(){
		// console.log('deleteOnClose deleteOnClose');
	}
</script>

<template>
    <Head title="Admins" />
	

	<Default>
		<div id="admin-user-list">
			<div class="mt-5 mb-3">
				<h1 class="mb-0 fs-3">Admins</h1>
			</div>

			
			<div class="row">
				<div class="col-6 offset-6">
					<div class="text-end pt-5 pb-3">
						<Link :href="getAdminUrl('/users/admins/add')">
							<button class="btn btn-primary">
								<i class="fa-solid fa-user-pen"></i><span class="ms-3">Create New Admin</span> 
							</button>
						</Link>
					</div>
				</div>
			</div>

			<div class="row filter-section mb-5">
				<div class="col-12">
					<div class="row">
						<div class="col-6 col-6 d-flex align-items-center">
							<div>
								<span class="fw-bold me-3 d-block w-100">Filters: </span>
							</div>
							<div class="fs-12">
								<span class="d-block py-1" v-if="filterForm.id.from || filterForm.id.to">
									<span class="text-muted">ID: </span>{{filterForm.id.from}} - {{filterForm.id.to}}
									<span class="ms-2 c-pointer">
										<i class="fa-solid fa-times-circle" @click="filterForm.id.from = ''; filterForm.id.to = ''; removeFilter();"></i>
									</span>
								</span>
								<span class="d-block py-1" v-if="filterForm.firstname">
									<span class="text-muted">First Name: </span>{{filterForm.firstname}}
									<span class="ms-2 c-pointer">
										<i class="fa-solid fa-times-circle" @click="filterForm.firstname = ''; removeFilter();"></i>
									</span>
								</span>
								<span class="d-block py-1" v-if="filterForm.lastname">
									<span class="text-muted">Last Name: </span>{{filterForm.lastname}}
									<span class="ms-2 c-pointer">
										<i class="fa-solid fa-times-circle" @click="filterForm.lastname = ''; removeFilter();"></i>
									</span>
								</span>
								<span class="d-block py-1" v-if="filterForm.email">
									<span class="text-muted">Email: </span>{{filterForm.email}}
									<span class="ms-2 c-pointer">
										<i class="fa-solid fa-times-circle" @click="filterForm.email = ''; removeFilter();"></i>
									</span>
								</span>
							</div>
						</div>
						<div class="col-6">
							<div class="text-end py-3">
								<div class="btn-group">
									<button type="button" class="btn btn-outline-secondary" @click="showFilters()">
										Filters
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 dropdown-divider" v-show="isShowFilter"></div>
				<div class="col-12" v-show="isShowFilter">
					<div class="filter-fields p-2">
						<div class="row">
							<div class="col-3">
								<label class="form-label fw-bold mb-0">ID</label>
								<div class="mb-3 row">
									<label for="filter-fields-id-from" class="col-sm-2 col-form-label">From</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="filter-fields-id-from" name="id_from" v-model="filterForm.id.from">
									</div>
								</div>
								<div class="mb-3 row">
									<label for="filter-fields-id-to" class="col-sm-2 col-form-label">To</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="filter-fields-id-to" name="id_to" v-model="filterForm.id.to">
									</div>
								</div>
							</div>
							<div class="col-3">
								<div class="mb-3">
									<label for="filter-fields-firstname" class="form-label fw-bold mb-0">First Name</label>
									<input type="text" class="form-control" id="filter-fields-firstname" name="firstname" v-model="filterForm.firstname">
								</div>
							</div>
							<div class="col-3">
								<div class="mb-3">
									<label for="filter-fields-lastname" class="form-label fw-bold mb-0">Last Name</label>
									<input type="text" class="form-control" id="filter-fields-lastname" name="lastname" v-model="filterForm.lastname">
								</div>
							</div>
							<div class="col-3">
								<div class="mb-3">
									<label for="filter-fields-email" class="form-label fw-bold mb-0">Email</label>
									<input type="text" class="form-control" id="filter-fields-email" name="email" v-model="filterForm.email">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12 text-end">
								<div class="mb-3">
									<button class="btn btn-outline-primary" @click="applyFilters()">Apply Filters</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<Listing :propsdata="propsdata" :action="1">
				<template #th_id>
					<ListingThSort :sortorder="sortOrder" :column_name="'id'" @click="setSortOrder('id')">ID</ListingThSort>
				</template>
				<template #th_firstname>
					<ListingThSort :sortorder="sortOrder" :column_name="'firstname'" @click="setSortOrder('firstname')">First Name</ListingThSort>
				</template>
				<template #th_lastname>
					<ListingThSort :sortorder="sortOrder" :column_name="'lastname'" @click="setSortOrder('lastname')">Last Name</ListingThSort>
				</template>
				<template #th_email>
					<ListingThSort :sortorder="sortOrder" :column_name="'email'" @click="setSortOrder('email')">Email</ListingThSort>
				</template>
				<template #th_admin_user_role_id>
					User Role
				</template>
				<template #th_created_at>
					<ListingThSort :sortorder="sortOrder" :column_name="'created_at'" @click="setSortOrder('created_at')">Created At</ListingThSort>
				</template>
				<template #th_updated_at>
					<ListingThSort :sortorder="sortOrder" :column_name="'updated_at'" @click="setSortOrder('updated_at')">Updated At</ListingThSort>
				</template>


				<template #id="{ item }">
					{{item.id}}
				</template>
				<template #firstname="{ item }">
					{{item.firstname}}
				</template>
				<template #lastname="{ item }">
					{{item.lastname}}
				</template>
				<template #email="{ item }">
					<a :href="'mailto:' + item.email">{{item.email}}</a>
				</template>
				<template #admin_user_role_id="{ item }">
					<template v-if="item.admin_user_role">
						{{item.admin_user_role.role_name}}
					</template>
					<template v-else>
						*
					</template>
				</template>
				<template #created_at="{ item }">
					{{ moment(item.created_at).format("MMM DD, YYYY h:mm:ss a") }}
				</template>
				<template #updated_at="{ item }">
					{{ moment(item.updated_at).format("MMM DD, YYYY h:mm:ss a") }}
				</template>
				<template #item="{ item }">
					<div class="btn-group">
						<button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
						  	Action
						</button>
						<ul class="dropdown-menu">
							<li>
								<Link class="dropdown-item" :href="getAdminUrl('/users/admins/edit/' + item.id)" v-bind:data-id="item.id">
									<i class="fa-solid fa-pencil"></i> <span>Edit</span>
								</Link>
							</li>
							<li v-if="item.admin_type != 'super_admin'">
								<a class="dropdown-item" href="javascript:void(0)" @click="deleteItem(item)">
									<i class="fa-solid fa-trash"></i> <span>Delete</span>
								</a>
							</li>
						</ul>
					</div>
				</template>
			</Listing>
		</div>
	</Default>

	<ModalConfirmmation 
		:id="confirmModalOptionElId" 
		title="Delete User" 
		buttoncloselabel="No"
		buttonconfirmlabel="Yes"
		:onconfirm="deleteOnConfirm"
		:onclose="deleteOnClose"
	>
		Do you want to delete the admin user <span class="fw-bold">{{confirmModalContenData?.firstname}} {{confirmModalContenData?.lastname}}</span>?
</ModalConfirmmation>
	
</template>
