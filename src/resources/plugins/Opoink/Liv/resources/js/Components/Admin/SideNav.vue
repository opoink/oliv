<script setup>
	import { Link, usePage } from '@inertiajs/vue3';
	import { adminSideNav } from '@@Plugins@@/Opoink/Liv/resources/js/States/admin.side.nav';
	import { isRoleAllowed, getAdminUrl } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/common.js';

	/** component import on build rollup will be injected here */
</script>
<script>
	export default {
		data() {
			return {
				page: usePage()
			}
		},
		methods: {
			moveObject(event, element) {
				let delta = 0;
				if (!event) event = window.event;
				if (event.wheelDelta) {
					delta = -event.wheelDelta / 60;
				} else if (event.detail) {
					delta = event.detail / 2;
				}

				let currPos = element.offsetTop;
				currPos = parseInt(currPos) - (delta*10);
				if(currPos >= 0){
					element.style.top = "0px";
				}
				else {
					let currHeight = element.offsetHeight;
					let wh = window.innerHeight;
					if(currHeight > wh){
						let maxTop = 0 - (currHeight - wh);
						if(maxTop < currPos){
							element.style.top = currPos+"px";
						}
						else {
							element.style.top = maxTop + "px";
						}
					}
					else {
						element.style.top = "0px";
					}
				}
			},
			setScroll(){
				let element = document.getElementById('side-nav-items');
				element.style.top = "0px";

				document.addEventListener("wheel", (event) => {
					this.moveObject(event, element);
				});
			},
			isAllowed(menu){
				if(typeof menu.role_resource != 'undefined'){
					return isRoleAllowed(menu.role_resource)
				}
				else {
					/** if the page.props.auth.admin.roles_resource is * means all resource */
					return this.page.props.auth.admin.roles_resource === '*';
				}
			}
		},
		mounted(){
			adminSideNav.init();
			this.setScroll();
		}
	}
</script>

<template>
	<div class="side-nav">
		<div class="side-nav-backdrop" :class="{'d-none' : adminSideNav.activeMenu == ''}" @click="adminSideNav.showActiveMenu('')"></div>
		<ul id="side-nav-items" data-v-ref="side-nav-items">
			<template v-for="lv1 in $page.props.adminmenu">
				<template v-if="lv1.route">
					<li v-if="isAllowed(lv1)">
						<Link class="menu-action" :href="lv1.route != null ? getAdminUrl(lv1.route) : '#'">
							<span class="icon">
								<i :class="lv1.fa_icon"></i>
							</span>
							<span class="label">{{lv1.label}}</span>
						</Link>
					</li>
				</template>
				<template v-if="!lv1.route">
					<li 
						v-bind:class="{'active' : adminSideNav.isMenuActive($page.url.startsWith($getAdminUrl(lv1.is_active_menu_url)), lv1.is_active_menu_name)}"
						v-if="isAllowed(lv1)"
					>
						<a href="javascript:void(0)" class="menu-action" @click.prevent="adminSideNav.showActiveMenu(lv1.is_active_menu_name)">
							<span class="icon">
								<i :class="lv1.fa_icon"></i>
							</span>
							<span class="label">{{lv1.label}}</span>
						</a>

						<div class="side-nav-content" v-bind:class="{'active' : adminSideNav.activeMenu == lv1.is_active_menu_name}">
							<div class="snc-header clearfix">
								<button type="button" aria-label="Close" @click="adminSideNav.showActiveMenu(lv1.is_active_menu_name)">
									<i class="fa-solid fa-xmark"></i>
								</button>
							</div>
							<div class="d-flex">
								<template v-for="col in lv1.child">
									<div class="snc-body">
										<div class="links-wrapper" v-for="row in col">
											<p class="link-title">{{row.title}}</p>
											<ul>
												<template v-for="link in row.links">
													<li v-if="isAllowed(link)">
														<Link :href="getAdminUrl(link.route)" v-bind:class="{'active' : $page.url == getAdminUrl(link.route)}">
															{{link.label}}
														</Link>
													</li>
												</template>
											</ul>
										</div>
									</div>
								</template>
							</div>
						</div>
					</li>
				</template>
			</template>
		</ul>
	</div>
</template>