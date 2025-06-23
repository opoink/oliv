<script setup>
	import { Head, Link, router } from '@inertiajs/vue3';
	import { toast } from '@@Plugins@@/Opoink/Liv/resources/js/States/toast.js';
	import { loader } from '@@Plugins@@/Opoink/Liv/resources/js/States/loader.js';
	import Loader from '@@Plugins@@/Opoink/Liv/resources/js/Components/Loader.vue';
	import Toast from '@@Plugins@@/Opoink/Liv/resources/js/Components/Toast.vue';
	import { FormData } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/form.data';
	import { reactive, onBeforeMount } from 'vue';
	import { getAdminUrl } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/common.js';


	// const form = useForm({
	// 	email: '',
	// 	code: '',
	// 	new_password: '',
	// 	confirm_password: '',
	// });

	const resetPasswordFormData = reactive(new FormData());

	onBeforeMount(() => {
		resetPasswordFormData.fields['email'] = '';
		resetPasswordFormData.fields['code'] = '';
		resetPasswordFormData.fields['password'] = '';
		resetPasswordFormData.fields['confirm_password'] = '';
		resetPasswordFormData.setForm();
	});

	const submitResetPassword = () => {
		loader.setLoader(true);
		resetPasswordFormData.form.clearErrors();
		axios({
			method: 'post',
			url: getAdminUrl('/reset-password/save'),
			data: resetPasswordFormData.form.data()
		})
		.then(response => {
			loader.setLoader(false);
			toast.add(response.data.message, 'success');
			router.visit(getAdminUrl('/'));
		})
		.catch(error => {
			loader.setLoader(false);
			resetPasswordFormData.form.setError(error.response.data.errors);
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

						<div>
							<div class="mb-2">
								<label for="resetPasswordFormData-email" class="col-form-label d-block">
									Email address <span class="text-danger">*</span>
								</label>
								<input type="text" id="resetPasswordFormData-email" class="form-control" name="email" v-model="resetPasswordFormData.form.email">
								<template v-if="resetPasswordFormData.form.errors.email">
									<small class="text-sm text-danger" v-for="error in resetPasswordFormData.form.errors.email">
										{{error}}
									</small>
								</template>
							</div>

							<div class="mb-2">
								<label for="resetPasswordFormData-code" class="col-form-label d-block">
									Code <span class="text-danger">*</span>
								</label>
								<input type="text" id="resetPasswordFormData-code" class="form-control" name="code" v-model="resetPasswordFormData.form.code">
								<template v-if="resetPasswordFormData.form.errors.code">
									<small class="text-sm text-danger" v-for="error in resetPasswordFormData.form.errors.code">
										{{error}}
									</small>
								</template>
							</div>

							<div class="mb-2">
								<label for="resetPasswordFormData-password" class="col-form-label d-block">
									New Password <span class="text-danger">*</span>
								</label>
								<input type="password" id="resetPasswordFormData-password" class="form-control" name="password" v-model="resetPasswordFormData.form.password">
								<template v-if="resetPasswordFormData.form.errors.password">
									<small class="text-sm text-danger" v-for="error in resetPasswordFormData.form.errors.password">
										{{error}}
									</small>
								</template>
							</div>

							<div class="mb-2">
								<label for="resetPasswordFormData-confirm_password" class="col-form-label d-block">
									Confirm New Password <span class="text-danger">*</span>
								</label>
								<input type="password" id="resetPasswordFormData-confirm_password" class="form-control" name="confirm_password" v-model="resetPasswordFormData.form.confirm_password">
								<template v-if="resetPasswordFormData.form.errors.confirm_password">
									<small class="text-sm text-danger" v-for="error in resetPasswordFormData.form.errors.confirm_password">
										{{error}}
									</small>
								</template>
							</div>

							<div class="d-flex align-items-center justify-content-end mt-4">
								<Link class="btn btn-link" :href="getAdminUrl('/login')">
									Login
								</Link >
								<button class="btn btn-primary" @click="submitResetPassword()">Reset Password</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<Loader />
	<Toast />
</template>