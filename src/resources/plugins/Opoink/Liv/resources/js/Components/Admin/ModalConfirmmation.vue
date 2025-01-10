<script setup>
	const props = defineProps([
		'id',
		'title',
		'buttoncloselabel',
		'buttonconfirmlabel',
		'content',
		'onconfirm',
		'onclose',
	]);

	function _onConfirm(){
		if(typeof props.onconfirm == 'function'){
			props.onconfirm();
		}	
	}

	function _onClose(){
		if(typeof props.onconfirm == 'function'){
			props.onclose();
		}	
	}
</script>
<template>
	<div class="modal fade" :id="id" tabindex="-1" :aria-labelledby="id + 'Label'" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" :id="id + 'Label'">{{title}}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" v-on:click="_onClose()"></button>
				</div>
				<div class="modal-body">
					<slot />
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" v-on:click="_onClose()">{{buttoncloselabel}}</button>
					<button type="button" class="btn btn-primary" v-on:click="_onConfirm()">{{buttonconfirmlabel}}</button>
				</div>
			</div>
		</div>
	</div>
</template>