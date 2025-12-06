<script setup lang="ts">
	import { Head } from '@inertiajs/vue3';
	import Default from '../../Layouts/Admin/Default.vue';
	import { onBeforeMount } from 'vue';
	import { toast } from '@@Plugins@@/Opoink/Liv/resources/js/States/toast.js';
	/** component import on build rollup will be injected here */

	const props = defineProps(['propsdata', 'errors']);

	onBeforeMount(() => {
		try {
			Object.keys(props.errors).forEach(function(key) {
				toast.add(props.errors[key], 'danger');
			});
		} catch (error) {
		}
	});

</script>

<template>
    <Head title="Dashboard" />
	
	<Default>
		<div id="dashboard" data-v-ref="dashboard">
			nothing is here for now
		</div>
		
	</Default>
	
</template>
