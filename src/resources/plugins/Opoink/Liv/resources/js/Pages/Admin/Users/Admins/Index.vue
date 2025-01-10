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

	const confirmModalOptionElId = 'userDeleteConfirm';
	const confirmModalContenData = ref(null);
	const confirmModal = ref(null);

	onMounted(() => {
		confirmModal.value = new bootstrap.Modal(document.getElementById(confirmModalOptionElId), {
			backdrop: 'static',
			keyboard: true,
			focus: true
		});
	});

	const deleteItem = (data) => {
		confirmModalContenData.value = data;
		confirmModal.value.show();
		console.log('deleteItem deleteItem', data);
	}
	function deleteOnConfirm(){
		loader.setLoader(true);
		confirmModal.value.hide();

		axios({
			method: 'delete',
			url: route('admin.users.admins.delete', {id: confirmModalContenData.value.id}),
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
						<Link :href="route('admin.users.admins.add')">
							<button class="btn btn-primary">
								<i class="fa-solid fa-user-pen"></i><span class="ms-3">Create New Admin</span> 
							</button>
						</Link>
					</div>
				</div>
			</div>

			<Listing :propsdata="propsdata" :action="1">
				<template #th_id>ID</template>
				<template #th_firstname>Firstname</template>
				<template #th_lastname>Lastname</template>
				<template #th_email>E-Mail</template>
				<template #th_admin_user_role_id>User Role</template>
				<template #th_created_at>Created At</template>
				<template #th_updated_at>Updated At</template>


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
								<Link class="dropdown-item" :href="route('admin.users.admins.edit', {id: item.id})" v-bind:data-id="item.id">
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
