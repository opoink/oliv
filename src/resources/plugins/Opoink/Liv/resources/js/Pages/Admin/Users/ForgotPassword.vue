<script setup>
	import { Head, Link, useForm } from '@inertiajs/vue3';
	import { route } from 'ziggy-js';

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
		axios({
			method: 'post',
			url: route('admin.forgot-password.send.code'),
			data: form.data()
		})
		.then(response => {
			console.log(response.data);
		})
		.catch(error => {
			form.errors = error.response.data.errors;
		});
	};
</script>

<template>
	<Head title="Log in" />
	<div id="login-page">
		<div class="login-box-wrapper d-flex align-items-center justify-content-center">
			<div class="login-box">
				<div class="card">
					<div class="card-body">
						<div class="logo-container">
							<img class="logo" src="../../../../images/oliv-logo-2.png" title="logo" >
						</div>
						<h1 class="h5 fw-400 text-center mb-4">
							Welcome, please sign-in
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
								<Link class="btn btn-link" :href="route('admin.login')">
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
</template>