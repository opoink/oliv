<script setup>
	import { loader } from '@@Plugins@@/Opoink/Liv/resources/js/States/loader.js';
</script>
<template>
	<div class="loader-wrapper" :class="{'d-none' : !loader.isActive}">
		<div class="d-flex align-items-center justify-content-center h-100">
			<div>
				<div class="lds-ring"><div></div><div></div><div></div><div></div></div>
				<template v-if="loader.content">
					<div class="loading-message text-white text-center" v-html="loader.content"></div>
				</template>
			</div>
		</div>
	</div>
</template>