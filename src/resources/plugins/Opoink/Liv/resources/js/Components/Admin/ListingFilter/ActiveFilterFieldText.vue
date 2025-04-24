<script setup lang="ts">
	import { ref } from 'vue';

	const emit = defineEmits(['removeFilter']);

	const props = defineProps([
		'listingFilter', 
		'field'
	]);

	const lf = ref(props.listingFilter);

	const removeFilter = function(){
		lf.value.filterFields[props.field] = "";
		lf.value.removeFilter();
		emit('removeFilter');
	}

</script>

<template>
	<span class="d-block py-1" v-if="lf.filterFields[field]">
		<span class="text-muted">Role: </span>{{lf.filterFields[field]}}
		<span class="ms-2 c-pointer">
			<i class="fa-solid fa-times-circle" @click="removeFilter()"></i>
		</span>
	</span>
</template>