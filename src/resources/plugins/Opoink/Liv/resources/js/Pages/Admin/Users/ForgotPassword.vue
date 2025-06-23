<script setup>
	import { Head, Link, useForm, router } from '@inertiajs/vue3';
	import { toast } from '@@Plugins@@/Opoink/Liv/resources/js/States/toast.js';
	import { loader } from '@@Plugins@@/Opoink/Liv/resources/js/States/loader.js';
	import Loader from '@@Plugins@@/Opoink/Liv/resources/js/Components/Loader.vue';
	import Toast from '@@Plugins@@/Opoink/Liv/resources/js/Components/Toast.vue';
	import { getAdminUrl } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/common.js';

	const props = defineProps({
		canResetPassword: {
			type: Boolean,
		},
		status: {
			type: String,
		}
	});

	const form = useForm({
		email: '',
	});

	const submitResetPasswordCode = () => {
		
		loader.setLoader(true);
		axios({
			method: 'post',
			url: getAdminUrl('/forgot-password/send/code'),
			data: form.data()
		})
		.then(response => {
			loader.setLoader(false);
			toast.add(response.data.message, 'success');
			router.visit(getAdminUrl('/reset-password'));
		})
		.catch(error => {
			loader.setLoader(false);
			form.errors = error.response.data.errors;
		});
	};
</script>

<template>
	<Head title="Forgot Password" />
	<div id="login-page">
		<div class="login-box-wrapper d-flex align-items-center justify-content-center">
			<div class="login-box">
				<div class="card">
					<div class="card-body">
						<div class="logo-container">
							<img class="logo" src="../../../../images/oliv-logo-2.png" title="logo" >
						</div>
						<h1 class="h5 fw-400 text-center mb-4">
							Forgot Password
						</h1>

						<form @submit.prevent="submit">
							<div class="mb-3">
								<label for="email" class="form-label">Email address</label>
								<input id="email" type="email" class="form-control" v-model="form.email" autocomplete="username">
								<template v-for="error in form.errors.email">
									<small class="text-sm text-danger d-block">{{error}}</small>
								</template>
							</div>

							<div class="d-flex align-items-center justify-content-end mt-4">
								<Link class="btn btn-link" :href="getAdminUrl('/login')">
									Login
								</Link >
								<button class="btn btn-primary" @click="submitResetPasswordCode()">Reset Password</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<Loader />
	<Toast />
</template>