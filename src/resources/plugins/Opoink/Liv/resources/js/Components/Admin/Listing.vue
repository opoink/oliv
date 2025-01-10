<script setup>
	import { Link } from '@inertiajs/vue3';
	const props = defineProps([
		'propsdata',
		'action'
	]);

</script>

<template>
	<div class="row">
		<div class="col-12">


			<div class="clearfix my-4">
				<nav class="float-end" aria-label="Page navigation">
					<ul class="pagination mb-0">
						<li class="page-item" v-if="props.propsdata.listing.paginator.prev_page_url">
							<Link class="page-link" :href="props.propsdata.listing.paginator.prev_page_url" v-html="'Prev'"></Link>
						</li>
						<li class="page-item" v-if="props.propsdata.listing.paginator.next_page_url">
							<Link class="page-link" :href="props.propsdata.listing.paginator.next_page_url" v-html="'Next'"></Link>
						</li>
					</ul>
				</nav>
			</div>

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

			<div class="clearfix my-4">
				<nav class="float-end" aria-label="Page navigation">
					<ul class="pagination mb-0">
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