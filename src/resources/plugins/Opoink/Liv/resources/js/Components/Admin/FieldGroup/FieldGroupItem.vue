<script setup>
	const props = defineProps([
		'target_id',
		'title'
	])
</script>
<template>
	<li class="field_group">
		<div class="field_group-title py-3 c-pointer collapsed" data-bs-toggle="collapse" :data-bs-target="'#' + target_id" aria-expanded="true" :aria-controls="target_id">
			<p class="mb-0 fw-600 clearfix">
				<span class="float-start">{{title}}</span>
				<span class="float-end me-3"><i class="fa-solid fa-caret-down"></i></span>
			</p>
		</div>
		<div :id="target_id" class="field_group-content ps-4 collapse show">
			<slot />
		</div>
	</li>
</template>