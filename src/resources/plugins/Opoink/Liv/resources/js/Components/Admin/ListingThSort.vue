<script setup lang="ts">
	
	const props = defineProps(['sortorder', 'column_name']);
</script>

<template>
	<span class="c-pointer">
		<span><slot /></span>
		<span class="ms-2 fs-12" v-if="sortorder.key == column_name">
			<i class="fa-solid fa-angles-down" v-if="sortorder.value == 'asc'"></i>
			<i class="fa-solid fa-angles-up" v-if="sortorder.value == 'desc'"></i>
		</span>
	</span>
</template>