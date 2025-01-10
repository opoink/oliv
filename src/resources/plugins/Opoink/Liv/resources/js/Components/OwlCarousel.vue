<script setup>
	// import { scriptloader } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/scriptloader.js';
	import jQuery from 'jquery';

	if(typeof window != 'undefined'){
		window.$ = jQuery;
		window.jQuery = jQuery;
	}

	import '@@Plugins@@/Opoink/Liv/resources/owlcarousel/owl.carousel.min.css';
	import '@@Plugins@@/Opoink/Liv/resources/owlcarousel/owl.theme.default.min.css';
	import '@@Plugins@@/Opoink/Liv/resources/owlcarousel/owl.carousel.min.js';
</script>
<script>
	export default {
		data() {
			return {
				el: null,
				basicConfig: {
					margin: 10,
					nav: true,
					loop: false,
					responsive: {
						0: {
							items: 1
						},
						600: {
							items: 3
						},
						1000: {
							items: 6
						}
					}
				}
			}
		},
		props: [
			'elid',
			'owlconfig'
		],
		methods: {
			getConfig(){
				if(typeof this.owlconfig != 'undefined'){
					return this.owlconfig
				}
				else {
					return this.basicConfig
				}
			},
			owlStart: function(){
				let owlCheckerCount = 0;
				let owlChecker = setInterval(() => {
					if(typeof this.el.owlCarousel == 'function'){
						this.el.removeClass('d-none');
						this.el.owlCarousel(this.getConfig());
						clearInterval(owlChecker);
					}
					if(owlCheckerCount >= 500){
						clearInterval(owlChecker);
					}
					owlCheckerCount++;
				}, 100);
			}
		},
		mounted(){
			this.el = jQuery('#' + this.elid);
			this.owlStart()
		},
		beforeMount(){
		}
	}
</script>

<template>
	<div :id="elid" class="owl-carousel owl-theme d-none">
		<slot />
	</div>
</template>