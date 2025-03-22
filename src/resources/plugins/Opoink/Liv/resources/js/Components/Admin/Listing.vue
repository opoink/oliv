<script setup>
	import { Link } from '@inertiajs/vue3';
	import { ref } from 'vue';

	const props = defineProps([
		'propsdata',
		'action'
	]);

	const p = ref(props.propsdata.listing.paginator);
</script>

<template>
	<div class="row">
		<div class="col-12">

			<div class="row my-4">
				<div class="col-6">
					<div class="d-flex align-items-center">
						<div class="action_column_left">
							<slot :name="'action_column_left'" />
						</div>
						<div class="ms-3" v-if="p.total">
							{{p.total}} record<span v-if="p.total > 1">s</span> found
						</div>
					</div>
				</div>
				<div class="col-6">
					<div class="clearfix">
						<nav class="float-end" aria-label="Page navigation">
							<ul class="pagination mb-0" v-if="props.propsdata.listing.paginator.links">
								<li class="page-item" v-for="link in props.propsdata.listing.paginator.links" :class="{'active' : link.active}">
									<template v-if="link.url">
										<Link class="page-link" :href="link.url" v-html="link.label"></Link>
									</template>
								</li>
							</ul>
							<ul class="pagination mb-0" v-else>
								<li class="page-item" v-if="props.propsdata.listing.paginator.prev_page_url">
									<Link class="page-link" :href="props.propsdata.listing.paginator.prev_page_url" v-html="'Prev'"></Link>
								</li>
								<li class="page-item" v-if="props.propsdata.listing.paginator.next_page_url">
									<Link class="page-link" :href="props.propsdata.listing.paginator.next_page_url" v-html="'Next'"></Link>
								</li>
							</ul>
						</nav>
					</div>
				</div>
			</div>

			<div class="listing-table-wrapper">
				<table class="table table-striped">
					<thead>
						<tr>
							<template v-for="column in propsdata.listing.columns">
								<th>
									<slot :name="'th_' + column" />
								</th>
							</template>

							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="item in propsdata.listing.paginator.data">
							<template v-for="column in propsdata.listing.columns">
								<td>
									<slot :name="column" v-bind="{'item': item}" />
								</td>
							</template>
							<td>
								<slot name="item" v-bind="{'item': item}" />
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="clearfix my-4">
				<nav class="float-end" aria-label="Page navigation">
					<ul class="pagination mb-0" v-if="props.propsdata.listing.paginator.links">
						<li class="page-item" v-for="link in props.propsdata.listing.paginator.links" :class="{'active' : link.active}">
							<template v-if="link.url">
								<Link class="page-link" :href="link.url" v-html="link.label"></Link>
							</template>
						</li>
					</ul>
					<ul class="pagination mb-0" v-else>
						<li class="page-item" v-if="props.propsdata.listing.paginator.prev_page_url">
							<Link class="page-link" :href="props.propsdata.listing.paginator.prev_page_url" v-html="'Prev'"></Link>
						</li>
						<li class="page-item" v-if="props.propsdata.listing.paginator.next_page_url">
							<Link class="page-link" :href="props.propsdata.listing.paginator.next_page_url" v-html="'Next'"></Link>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</template>