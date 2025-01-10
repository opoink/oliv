<script setup>
	import { Head, Link, router } from '@inertiajs/vue3';
	import { onMounted, ref } from 'vue';
	import moment from "moment";

	import Default from '@@Plugins@@/Opoink/Liv/resources/js/Layouts/Admin/Default.vue';
	import Listing from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/Listing.vue';
	import ModalConfirmmation from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ModalConfirmmation.vue';
	import { loader } from '@@Plugins@@/Opoink/Liv/resources/js/States/loader.js';
	import { toast } from '@@Plugins@@/Opoink/Liv/resources/js/States/toast.js';

	const props = defineProps(['propsdata']);

	const confirmModalOptionElId = 'roleDeleteConfirm';
	const confirmModalContenData = ref(null);
	const confirmModal = ref(null);

	onMounted(() => {
		confirmModal.value = new bootstrap.Modal(document.getElementById(confirmModalOptionElId), {
			backdrop: 'static',
			keyboard: true,
			focus: true
		});
	});

	function deleteItem(data){
		confirmModalContenData.value = data;
		confirmModal.value.show();
	}

	function deleteOnConfirm(){
		loader.setLoader(true);
		confirmModal.value.hide();

		axios({
			method: 'delete',
			url: route('admin.users.admins.roles.delete', {id: confirmModalContenData.value.id}),
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
</script>

<template>
    <Head title="Admin Roles" />

	<Default>
		<div id="admin-user-list">
			<div class="mt-5 mb-3">
				<h1 class="mb-0 fs-3">Admins Roles</h1>
			</div>

			
			<div class="row">
				<div class="col-6 offset-6">
					<div class="text-end pt-5 pb-3">
						<Link :href="route('admin.users.admins.roles.add')">
							<button class="btn btn-primary">
								<span>Create New Role</span> 
							</button>
						</Link>
					</div>
				</div>
			</div>


			<Listing :propsdata="propsdata" :action="1">
				<template #th_id>ID</template>
				<template #th_role_name>Role Name</template>
				<template #th_created_at>Created At</template>
				<template #th_updated_at>Updated At</template>


				<template #id="{ item }">
					{{item.id}}
				</template>
				<template #role_name="{ item }">
					{{item.role_name}}
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
								<Link class="dropdown-item" :href="route('admin.users.admins.roles.edit', {id: item.id})" v-bind:data-id="item.id">
									<i class="fa-solid fa-pencil"></i> <span>Edit</span>
								</Link>
							</li>
							<li>
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
		title="Delete Role" 
		buttoncloselabel="No"
		buttonconfirmlabel="Yes"
		:onconfirm="deleteOnConfirm"
		:onclose="deleteOnClose"
	>
		Do you want to delete the role of {{confirmModalContenData?.role_name}}?
	</ModalConfirmmation>
</template>