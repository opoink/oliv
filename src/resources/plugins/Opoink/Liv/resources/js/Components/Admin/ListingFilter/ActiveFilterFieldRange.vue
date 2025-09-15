<script setup lang="ts">
	import { ref, onBeforeMount } from 'vue';

	const emit = defineEmits(['removeFilter']);

	const props = defineProps([
		'listingFilter', 
		'field',
		'label'
	]);

	const lf = ref(props.listingFilter);

	const from = ref(props.listingFilter.filterFields[props.field].from);
	const to = ref(props.listingFilter.filterFields[props.field].to);

	const label = ref(props.label);

	onBeforeMount(() => {
		if(!label.value){
			label.value = 'Column Name';
		}
	});

	const removeFilter = function(){
		lf.value.filterFields[props.field].from = "";
		lf.value.filterFields[props.field].to = "";
		lf.value.removeFilter();
		emit('removeFilter');
	}

</script>

<template>
	<span class="d-block py-1" v-if="from || to">
		<span class="text-muted">{{label}}: </span>{{from}} - {{to}}
		<span class="ms-2 c-pointer">
			<i class="fa-solid fa-times-circle" @click="removeFilter();"></i>
		</span>
	</span>
</template>