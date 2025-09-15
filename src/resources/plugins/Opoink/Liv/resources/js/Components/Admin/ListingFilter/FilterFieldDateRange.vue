<script setup>
	import { ref } from 'vue';

	/**
	 * https://www.npmjs.com/package/vuejs3-datepicker
	 */
	import Datepicker from 'vuejs3-datepicker';

	const emit = defineEmits(['update:modelValue']);
	const props = defineProps([
		'field', 
		'name',
		'modelValue'
	]);
	const fieldModel = ref(props.modelValue);
	const from = ref(fieldModel.value.from);
	const to = ref(fieldModel.value.to);

	const handleChangeFrom = function(d){
		if(d){
			let datetime = d.toJSON().slice(0, 19).replace('T', ' ');
			datetime = datetime.split(' ')[0];
			fieldModel.value.from = datetime;
		}
		else {
			fieldModel.value.from = '';
		}
	}
	const handleChangeTo = function(d){
		if(d){
			let datetime = d.toJSON().slice(0, 19).replace('T', ' ');
			datetime = datetime.split(' ')[0];
			fieldModel.value.to = datetime;
		}
		else {
			fieldModel.value.from = '';
		}
	}
</script>

<template>
	<div>
		<label class="form-label fw-bold mb-0">{{name}}</label>
		<div class="mb-3 row">
			<label :for="'filter-fields-'+field+'-from'" class="col-sm-2 col-form-label">From</label>
			<div class="col-sm-10">
				<Datepicker v-model="from" @selected="handleChangeFrom" :format="'yyyy-MM-dd'" :value="from" :input-class="'h-35-px'"></Datepicker>
			</div>
		</div>
		<div class="mb-3 row">
			<label for="'filter-fields-'+field+'-to'" class="col-sm-2 col-form-label">To</label>
			<div class="col-sm-10">
				<Datepicker v-model="to" @selected="handleChangeTo" :format="'yyyy-MM-dd'" :value="to" :input-class="'h-35-px'"></Datepicker>
			</div>
		</div>
	</div>
</template>