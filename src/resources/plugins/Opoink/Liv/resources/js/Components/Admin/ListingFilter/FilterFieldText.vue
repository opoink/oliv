<script setup lant="ts">
	import { onBeforeMount, ref } from 'vue';

	const emit = defineEmits(['update:modelValue']);

	const props = defineProps([
		'field', 
		'name',
		'modelValue'
	]);

	const fieldModel = ref('');

	onBeforeMount(() => {
		fieldModel.value = props.modelValue;
	});

	const handleChange = function(){
		emit('update:modelValue', fieldModel.value)
	}
</script>

<template>
	<div :id="'filter-fields-wrapper-' + field">
		<div class="mb-3">
			<label :for="'filter-fields-' + field" class="form-label fw-bold mb-0">{{name}}</label>
			<input type="text" class="form-control" :id="'filter-fields-' + field" :name="field" v-model="fieldModel" @input="handleChange" />
		</div>
	</div>
</template>