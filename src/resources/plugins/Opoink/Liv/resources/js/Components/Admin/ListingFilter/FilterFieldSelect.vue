<script setup>
	import { onBeforeMount, ref } from 'vue';

	const emit = defineEmits(['update:modelValue']);

	const props = defineProps([
		'field', 
		'name',
		'modelValue',
		'options'
	]);

	const fieldModel = ref('');

	onBeforeMount(() => {
		fieldModel.value = props.modelValue;
	});

	const handleChange = function(){
		emit('update:modelValue', fieldModel.value)
		console.log('handleChange handleChange', fieldModel.value)
	}
</script>

<template>
	<div :id="'filter-fields-wrapper-' + field">
		<div class="mb-3">
			<label :for="'filter-fields-' + field" class="form-label fw-bold mb-0">{{name}}</label>
			<select class="form-select" :aria-label="field" :id="'filter-fields-' + field" :name="field" v-model="fieldModel" @change="handleChange()">
				<option value="">Please Select</option>
				<option v-for="option in options" :value="option.value">{{ option.label }}</option>
			</select>
		</div>
	</div>
</template>