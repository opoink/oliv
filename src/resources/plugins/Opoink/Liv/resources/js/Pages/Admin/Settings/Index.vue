<script setup>
	import { Head, Link, router } from '@inertiajs/vue3';
	import Default from '@@Plugins@@/Opoink/Liv/resources/js/Layouts/Admin/Default.vue';
	import { onBeforeMount, onMounted, ref } from 'vue';
	import { toast } from '@@Plugins@@/Opoink/Liv/resources/js/States/toast.js';
	import { getAdminUrl } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/common.js';
	import { loader } from '@@Plugins@@/Opoink/Liv/resources/js/States/loader.js';

	/** component import on build rollup will be injected here */

	const props	= defineProps([
		'tab_section',
		'selected'
	]);

	const selectedTab = ref(null);
	const selectedSection = ref(null);
	const fieldGroups = ref(null);
	const opoinkLivGlobalComponentsAdminSystemConfigFieldGroupsRef = ref(null);

	onBeforeMount(() => {
		selectedTab.value = props.selected.tab;
		selectedSection.value = props.selected.section;
		fieldGroups.value = props.selected.field_groups;
	});

	onMounted(() => {
		let el = document.getElementById('tab-control-' + selectedTab.value);
		if(el){
			el.click();
		}
	});

	const save = function(){
		let data = JSON.parse(JSON.stringify(opoinkLivGlobalComponentsAdminSystemConfigFieldGroupsRef.value.fieldGroups));
		let flatten = flattenData(data, selectedTab.value+"/"+selectedSection.value);

		let jsonData = {
			data: flatten,
			tab: selectedTab.value,
			section: selectedSection.value,
		}

		loader.setLoader(true);
		axios({
			method: 'post',
			url: getAdminUrl('/settings/save'),
			data: jsonData
		})
		.then(response => {
			toast.add(response.data.message, 'success');
			fieldGroups.value = [];
			fieldGroups.value = response.data.data.field_groups;
			opoinkLivGlobalComponentsAdminSystemConfigFieldGroupsRef.value.setFieldGroups(response.data.data.field_groups);

			loader.setLoader(false);
		})
		.catch(error => {
			loader.setLoader(false);
			toast.add(error.response.data.message, 'danger');

		});
	}

	const flattenData = function(data, prefix, _flattenData = {}){
		data.forEach(d => {
			if(typeof d.children == 'object'){
				flattenData(d.children, prefix+"/"+d.name, _flattenData);
			}
			else if(d.type == 'field'){
				let v = d._value;
				if(d.field.type == "multiselect"){
					v = d._value.join(',')
				}

				_flattenData[prefix + "/" + d.name] = {
					value: v,
					is_system_value: d._is_system_value,
				}
			}
		});

		return _flattenData;
	}
</script>

<template>
    <Head title="System Configuration" />

	<Default>
		<div id="system-configuration" data-v-ref="system-configuration">

			<div class="mt-5 mb-3">
				<h1 class="mb-0 fs-3">System Configuration</h1>
			</div>

			<div class="row">
				<div class="col-6 offset-6">
					<div class="text-end pt-5 pb-3">
						<button class="btn btn-primary ms-3" @click="save()">
							<i class="fa-solid fa-save"></i><span class="ms-3">Save</span> 
						</button>
					</div>
				</div>
			</div>


			
			<!-- component-inject-settings-tabs-before -->
			<div class="row">		
				<div class="col-3">
					<ul class="admin-side-tabs-wrapper">
						<template v-for="tab in tab_section">
							<li 
								:class="{'active' : selectedTab == tab.name}" 
								:data-sort="tab.sort_order" 
								v-if="tab.type == 'tab'"
								v-bind="tab?.html_attributes"
							>
								<p 
									class="title"
									data-bs-toggle="collapse" 
									:data-bs-target="'#tab-' + tab.name" 
									aria-expanded="false" 
									:aria-controls="'tab-' + tab.name"
									:id="'tab-control-' + tab.name"
								>
									{{tab.label}}
									<span class="float-end">
										<i class="fa-solid fa-caret-down"></i>
									</span>
								</p>
								<div class="admin-side-tabs collapse" :id="'tab-' + tab.name" v-if="tab.children">
									<ul>
										<template v-for="section in tab.children">
											<li :data-sort="section.sort_order" v-if="section.type == 'section'" v-bind="section?.html_attributes">
												<Link 
													:href="'?tab='+tab.name+'&section='+section.name" 
													class="admin-side-tab-item"
													:class="{'active' : selectedSection == section.name}"
												>
													{{ section.label }}
												</Link>
											</li>
										</template>
									</ul>
								</div>
							</li>
						</template>
					</ul>
				</div>

				<div class="col-9">
					<OpoinkLivGlobalComponentsAdminSystemConfigFieldGroups 
						:field_groups="fieldGroups" 
						:isSub="false"
						ref="opoinkLivGlobalComponentsAdminSystemConfigFieldGroupsRef" 
						class="p-0"
					/>
				</div>
			</div>
			<!-- component-inject-settings-tabs-after -->
		</div>
		
	</Default>
</template>