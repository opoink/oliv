<script setup>
	import { toast } from '@@Plugins@@/Opoink/Liv/resources/js/States/toast.js';
</script>

<template>
	<div id="toast-wrapper">
		<template v-for="(message, key) in toast.list">
			<div class="alert" v-bind:class="'alert-' + message.type" role="alert">
				<div class="toast-message" v-html="message.msg"></div>
				<button class="toast-close" @click="toast.clear(key)">
					<i class="fa-solid fa-circle-xmark"></i>
				</button>
			</div>
		</template>
	</div>
</template>