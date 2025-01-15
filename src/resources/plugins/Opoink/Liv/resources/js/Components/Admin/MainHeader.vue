<script setup>
	import { Link } from '@inertiajs/vue3';
	import { usePage } from '@inertiajs/vue3';

	const $page = usePage();
</script>
<template>
	<div class="container-fluid">
		<div class="main-header">
			<div class="header-content">
				<div class="row">
					<div class="col-6">
						<div class="logo-container d-flex align-items-center">
							<img class="logo" src="../../../images/oliv-logo-2.png" title="logo" >
						</div>
					</div>
					<div class="col-6">
						<div class="action-btn d-flex align-items-center justify-content-end">
							<div class="dropdown">
								<button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
									<i class="fa-solid fa-user"></i><span class="mx-3">{{ $page.props.auth.admin.firstname }} {{ $page.props.auth.admin.lastname }}</span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right" data-v-ref="main-header-profile-dropdown">
									<li>
										<Link :href="route('admin.users.admins.edit', {id: $page.props.auth.admin.id})">
											<button class="dropdown-item" type="button">Edit Profile</button>
										</Link>
									</li>
									<li>
										<Link :href="route('admin.logout')">
											<button class="dropdown-item" type="button">Logout</button>
										</Link>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</template>