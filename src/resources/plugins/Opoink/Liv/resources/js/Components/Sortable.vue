<script setup>
	/** https://github.com/SortableJS/Sortable?tab=readme-ov-file */
	import Sortable from 'sortablejs/modular/sortable.complete.esm.js';
</script>
<script>
	export default {
		data() {
			return {
				draggingKey: null
			}
		},
		props: ['items', 'htmlclass', 'onupdatecallback'],
		methods: {
			onUpdateCallback(e){
				if(typeof this.onupdatecallback == 'function'){
					this.onupdatecallback(e);
				}
			}
		},
		mounted: function(){
			var a = this;
			var el = document.getElementById('sortable-items');
			var sortable = Sortable.create(el, {
				animation: 100,
				onUpdate: function(e){
					a.onUpdateCallback(e);
				}
			});
		}
	}
</script>

<template>
	<div id="sortable-items" :class="htmlclass">
		<template v-for="(item, key) in items">
			<div class="sortable-item">
				<slot name="sortableitem" v-bind="{'item': item}" />
			</div>
		</template>
	</div>
</template>