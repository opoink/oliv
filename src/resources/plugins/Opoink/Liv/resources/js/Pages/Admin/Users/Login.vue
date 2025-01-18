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
		password: '',
		remember: false
	});

	const submit = () => {
		form.post(route('admin.login.action'), {
			onFinish: () => form.reset('password'),
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
								<small class="text-sm text-danger">{{form.errors.email}}</small>
							</div>
							<div class="mb-3">
								<label for="password" class="form-label">Password</label>
								<div class="password-wrapper">
									<input id="password" type="password" class="form-control" v-model="form.password">
								</div>
								<small class="text-sm text-danger">{{form.errors.password}}</small>
							</div>

							<div class="d-flex align-items-center justify-content-end mt-4">
								<Link class="btn btn-link" :href="route('admin.forgot-password')">
									Forgot your password?
								</Link >
								<button class="btn btn-primary">Log in</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>