<script setup>
	import { onBeforeMount, onMounted, ref } from 'vue';

	const props = defineProps([
		"field_groups",
		"isSub"
	]);

	const fieldGroups = ref([]);

	const setFieldGroups = function(data){
		fieldGroups.value = data;
	}

	onBeforeMount(() => {
		setFieldGroups(props.field_groups);
	});

	defineExpose({
		fieldGroups,
		setFieldGroups
	});
</script>

<template>
	<ul class="field_group_wrapper">
		<template v-for="fieldGroup in fieldGroups">
			
			<li class="field_group" v-if="fieldGroup.type == 'group'" v-bind="fieldGroup?.html_attributes">
				<div 
					class="field_group-title py-3 c-pointer collapsed" 
					data-bs-toggle="collapse" 
					:data-bs-target="'#field_group_' + fieldGroup.name" 
					aria-expanded="false" 
					:aria-controls="'field_group_' + fieldGroup.name"
				>
					<p class="mb-0 fw-600 clearfix">
						<span class="float-start">{{ fieldGroup.label }}</span>
						<span class="float-end me-3"><i class="fa-solid fa-caret-down"></i></span>
					</p>
				</div>
				<div :id="'field_group_' + fieldGroup.name" class="field_group-content collapse ps-4">
					<div class="pt-3" v-if="fieldGroup.children">
						<div class="form-text mb-3" v-if="fieldGroup.comment" v-html="fieldGroup.comment"></div>
						<template v-for="field in fieldGroup.children">

							<div class="row mb-3" v-if="field.type == 'field'">
								<label class="col-sm-3 col-form-label text-end">
									<span v-if="field.label" v-html="field.label"></span>
								</label>
								<div class="col-sm-6">
									<template v-if="field.field.type == 'text'">
										<input type="text" class="form-control" v-bind="field?.html_attributes" v-model="field._value" :disabled="field._is_system_value">
									</template>
									<template v-if="field.field.type == 'select'">
										<select class="form-select" v-bind="field?.html_attributes" v-model="field._value" :disabled="field._is_system_value">
											<option value=""></option>
											<template v-if="field.field.options">
												<option v-for="option in field.field.options" :value="option.value">{{ option.label }}</option>
											</template>
										</select>
									</template>
									<template v-if="field.field.type == 'multiselect'">
										<select class="form-select" multiple v-bind="field?.html_attributes" v-model="field._value" :disabled="field._is_system_value">
											<template v-if="field.field.options">
												<option v-for="option in field.field.options" :value="option.value">{{ option.label }}</option>
											</template>
										</select>
									</template>

									<template v-if="field.field.type == 'email'">
										<input type="email" class="form-control" v-bind="field?.html_attributes" v-model="field._value" :disabled="field._is_system_value">
									</template>
									<template v-if="field.field.type == 'password'">
										<input type="password" class="form-control" v-bind="field?.html_attributes" v-model="field._value" :disabled="field._is_system_value">
									</template>
									<template v-if="field.field.type == 'file'">
										<input type="file" class="form-control" v-bind="field?.html_attributes" :disabled="field._is_system_value">
										<small>{{ field._value }}</small>
									</template>
									<template v-if="field.field.type == 'textarea'">
										<textarea class="form-control" rows="3" v-model="field._value" :disabled="field._is_system_value"></textarea>
									</template>

									<div class="form-text" v-if="field.comment" v-html="field.comment"></div>
								</div>
								<div class="col-sm-3">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" v-model="field._is_system_value">
										<label class="form-check-label" for="use-in-scope-message">Use system value</label>
									</div>
								</div>
							</div>

							<div v-if="field.type == 'group'">
								<OpoinkLivGlobalComponentsAdminSystemConfigFieldGroups 
									:field_groups="[field]" 
									:isSub="true"
									class="mb-3"
								/>
							</div>

						</template>
					</div>

				</div>
			</li>
		</template>
	</ul>
</template>