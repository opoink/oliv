<script setup>
	import { onMounted, ref } from 'vue';
	import { Head, Link, router, useForm } from '@inertiajs/vue3';
	import Default from '@@Plugins@@/Opoink/Liv/resources/js/Layouts/Admin/Default.vue';
	import Listing from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/Listing.vue';
	import moment from "moment";
	import ModalConfirmmation from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ModalConfirmmation.vue';
	import { loader } from '@@Plugins@@/Opoink/Liv/resources/js/States/loader.js';
	import { toast } from '@@Plugins@@/Opoink/Liv/resources/js/States/toast.js';
	import { route } from 'ziggy-js';

	
	const confirmModalOptionElId = 'cms-block-delete-confirm';
	const confirmModalContenData = ref(null);
	const confirmModal = ref(null);
	const isShowFilter = ref(false);

	const props = defineProps(['propsdata']);

	const filterForm = useForm({
		id: {
			from: '',
			to: ''
		},
		name: '',
		identifier: ''
	})

	function deleteItem(data){
		confirmModalContenData.value = data;
		confirmModal.value.show();
	}

	function deleteOnConfirm(){
		loader.setLoader(true);
		confirmModal.value.hide();

		axios({
			method: 'delete',
			url: route('admin.cms.block.deleteaction', {id: confirmModalContenData.value.id}),
		})
		.then(response => {
			toast.add(response.data.message, 'success');
			router.reload();
			loader.setLoader(false);
		})
		.catch(error => {
			error.response.data.errors.forEach(error => {
				toast.add(error, 'danger');
			});
			loader.setLoader(false)
		});
	}

	function deleteOnClose(){
		/** do nothing for now */
	}

	onMounted(() => {
		confirmModal.value = new bootstrap.Modal(document.getElementById(confirmModalOptionElId), {
			backdrop: 'static',
			keyboard: true,
			focus: true
		});
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

	function showFilters(){
		if(isShowFilter.value){
			isShowFilter.value = false;
		}
		else {
			isShowFilter.value = true;
		}
	}

	function applyFilters(){
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

		if(filterForm.name){
			filters.push({key: 'name',value: filterForm.name});
		}
		if(filterForm.identifier){
			filters.push({key: 'identifier',value: filterForm.identifier});
		}

		let queryParams = {filters: filters};
		router.visit( route('admin.cms.block.index', queryParams) );
	}
</script>

<template>
	<Head title="CMS Blocks" />

	<Default>
		<div id="admin-cms-block-list">
			<div class="mt-5 mb-3">
				<h1 class="mb-0 fs-3">CMS Blocks</h1>
			</div>

			
			<div class="row">
				<div class="col-6 offset-6">
					<div class="text-end pt-5 pb-3">
						<Link :href="route('admin.cms.block.add')">
							<button class="btn btn-primary">
								<i class="fa-solid fa-user-pen"></i><span class="ms-3">Add New CMS Block</span> 
							</button>
						</Link>
					</div>
				</div>
			</div>
			
			<div class="row filter-section mb-5">
				<div class="col-6 offset-6">
					<div class="text-end py-3">
						<div class="btn-group">
							<button type="button" class="btn btn-outline-secondary" @click="showFilters()">
								Filters
							</button>
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
										<input type="text" class="form-control" id="filter-fields-id-from" name="name" v-model="filterForm.id.from">
									</div>
								</div>
								<div class="mb-3 row">
									<label for="filter-fields-id-to" class="col-sm-2 col-form-label">To</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="filter-fields-id-to" name="name" v-model="filterForm.id.to">
									</div>
								</div>
							</div>
							<div class="col-3">
								<div class="mb-3">
									<label for="filter-fields-name" class="form-label fw-bold mb-0">Name</label>
									<input type="text" class="form-control" id="filter-fields-name" name="name" v-model="filterForm.name">
								</div>
							</div>
							<div class="col-3">
								<div class="mb-3">
									<label for="filter-fields-identifier" class="form-label fw-bold mb-0">Identifier</label>
									<input type="text" class="form-control" id="filter-fields-identifier" name="identifier" v-model="filterForm.identifier">
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
				<template #th_id>ID</template>
				<template #th_name>Name</template>
				<template #th_identifier>Identifier</template>
				<template #th_created_at>Created At</template>
				<template #th_updated_at>Updated At</template>


				<template #id="{ item }">
					{{item.id}}
				</template>
				<template #name="{ item }">
					{{item.name}}
				</template>
				<template #identifier="{ item }">
					{{item.identifier}}
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
								<Link class="dropdown-item" :href="route('admin.cms.block.edit', {id: item.id})" v-bind:data-id="item.id">
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
		title="Delete CMS Block" 
		buttoncloselabel="No"
		buttonconfirmlabel="Yes"
		:onconfirm="deleteOnConfirm"
		:onclose="deleteOnClose"
	>
		<p>Do you want to delete the CMS block {{confirmModalContenData?.name}}?</p>
		<ul class="">
			<li>You need to remove the imports of this block on Vue Components.</li>
			<li>You need to make a new build <code>npm run build</code> or <code>npm run build:ssr</code> for this to take effect.</li>
		</ul>	
	</ModalConfirmmation>
</template>