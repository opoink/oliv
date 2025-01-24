<script setup>
	import { Head, Link } from '@inertiajs/vue3';
	import Default from '@@Plugins@@/Opoink/Liv/resources/js/Layouts/Admin/Default.vue';

	/** component import on build rollup will be injected here */
</script>

<template>
    <Head title="Settings" />

	<Default>
		<div id="settings" data-v-ref="settings">

			<div class="mt-5 mb-3">
				<h1 class="mb-0 fs-3">Settings</h1>
			</div>

			<div class="row">
				<div class="col-6 offset-6">
					<div class="text-end pt-5 pb-3">
						<button class="btn btn-primary ms-3">
							<i class="fa-solid fa-save"></i><span class="ms-3">Save</span> 
						</button>
					</div>
				</div>
			</div>



			<div class="row">
				<div class="col-8">
					<div class="row">

						
						<div class="col-3">
							<div class="admin-side-tabs">
								<ul>
									<!-- component-inject-settings-tabs-before -->
									<li>
										<Link 
											href="#" 
											class="admin-side-tab-item active"
										>
											General
										</Link>
									</li>
									<!-- component-inject-settings-tabs-after -->
								</ul>
							</div>
						</div>




					</div>
				</div>
			</div>
		</div>
		
	</Default>
</template>