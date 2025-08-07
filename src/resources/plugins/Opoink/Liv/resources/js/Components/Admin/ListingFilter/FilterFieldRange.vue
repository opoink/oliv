<script setup>
	import { ref } from 'vue';

	const emit = defineEmits(['update:modelValue']);
	const props = defineProps([
		'field', 
		'name',
		'modelValue'
	]);
	const fieldModel = ref(props.modelValue);
	const handleChange = function(){
		emit('update:modelValue', fieldModel.value);
	}
</script>

<template>
	<div>
		<label class="form-label fw-bold mb-0">{{name}}</label>
		<div class="mb-3 row">
			<label :for="'filter-fields-'+field+'-from'" class="col-sm-2 col-form-label">From</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" :id="'filter-fields-'+field+'-from'" :name="field + '_from'" v-model="fieldModel.from" @input="handleChange" />
			</div>
		</div>
		<div class="mb-3 row">
			<label for="'filter-fields-'+field+'-to'" class="col-sm-2 col-form-label">To</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" :id="'filter-fields-'+field+'-to'" :name="field + '_to'" v-model="fieldModel.to" @input="handleChange" />
			</div>
		</div>
	</div>
</template>