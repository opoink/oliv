<script setup>
	import { Head, Link } from '@inertiajs/vue3';
	import Default from '@@Plugins@@/Opoink/Liv/resources/js/Layouts/Admin/Default.vue';
	import { getAdminUrl } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/common.js';
	import { reactive, ref } from 'vue';
	import moment from "moment";
	// import { toast } from '@@Plugins@@/Opoink/Liv/resources/js/States/toast.js';
	// import { onBeforeMount } from 'vue';

	import Listing from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/Listing.vue';
	import ListingThSort from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ListingThSort.vue';
	import { ListingFilter } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/listing.filter.js';
	import bookmark from '@@Plugins@@/Opoink/Liv/resources/js/Lib/Bookmark';

	// import FilterFieldRange from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ListingFilter/FilterFieldRange.vue';
	// import FilterFieldSelect from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ListingFilter/FilterFieldSelect.vue';
	// import FilterFieldText from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ListingFilter/FilterFieldText.vue';
	// import FilterFieldDateRange from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ListingFilter/FilterFieldDateRange.vue';

	// import ActiveFilterFieldText from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ListingFilter/ActiveFilterFieldText.vue';
	// import ActiveFilterFieldRange from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ListingFilter/ActiveFilterFieldRange.vue';

	
	const props = defineProps(['propsdata', 'errors']);

	const lf = reactive(new ListingFilter());
	const bm = ref( new bookmark(JSON.parse(JSON.stringify(props.propsdata))) );

	// onBeforeMount(() => {
	// 	try {
	// 		Object.keys(props.errors).forEach(function(key) {
	// 			toast.add(props.errors[key], 'danger');
	// 		});
	// 	} catch (error) {
	// 	}
	// });
</script>

<template>
	<Head title="Email Content"></Head>
	<Default>
		<div id="admin-email-list">
			<div class="mt-5 mb-3">
				<h1 class="mb-0 fs-3">Email Content List</h1>
			</div>

			<div class="row">
				<div class="col-6 offset-6">
					<div class="text-end pt-5 pb-3">
						<Link :href="getAdminUrl('/emails/add')">
							<button class="btn btn-primary">
								<i class="fa-solid fa-user-pen"></i><span class="ms-3">Add Email Content</span> 
							</button>
						</Link>
					</div>
				</div>
			</div>
			



			<Listing :propsdata="propsdata" :action="1">
				<template v-for="column in bm.propsdata.listing.columns" :key="column" v-slot:[`th_${column}`]>
					<ListingThSort :sortorder="lf.sortOrder" :column_name="column" @click="lf.setSortOrder(column)">
						{{ bm.getColumnLabel(column) }}
					</ListingThSort>
				</template>


				<template v-for="column in bm.propsdata.listing.columns" :key="column" v-slot:[column]="{ item }">
					<template v-if="column == 'updated_at' || column == 'created_at'">
						{{ moment(item[column]).format("MMM DD, YYYY h:mm:ss a") }}
					</template>
					<template v-else>
						{{ bm.getColumnValue(column, item[column]) }}
					</template>
				</template>
			
				<template #item="{ item }">
					<button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
						Action
					</button>
					<ul class="dropdown-menu">
						<li>
							<Link class="dropdown-item" :href="getAdminUrl('/emails/edit/' + item.id)" v-bind:data-id="item.id">
								<i class="fa-solid fa-pencil"></i> <span>Edit</span>
							</Link>
						</li>
					</ul>
				</template>
			</Listing>
		</div>
	</Default>
</template>