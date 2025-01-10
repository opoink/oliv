<script setup>
	import SideNav from '../../Components/Admin/SideNav.vue';
	import MainHeader from '../../Components/Admin/MainHeader.vue';
	import Toast from '../../Components/Toast.vue';
	import Loader from '../../Components/Loader.vue';
</script>
<template>
	
	<SideNav />


	<main id="content-wrapper">
		<MainHeader />
		<div class="container-fluid pb-5">
			<div class="row">
				<div class="col-12">
					<slot />
				</div>
			</div>
		</div>
	</main>

	<Loader />
	<Toast />
</template>