<script setup>
	import { onBeforeMount, reactive, ref } from 'vue';
	
	import Listing from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/Listing.vue';
	import ListingThSort from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ListingThSort.vue';
	import { ListingFilter } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/listing.filter.js';

	import FilterFieldRange from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ListingFilter/FilterFieldRange.vue';
	import FilterFieldSelect from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ListingFilter/FilterFieldSelect.vue';
	import FilterFieldText from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ListingFilter/FilterFieldText.vue';
	import FilterFieldDateRange from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ListingFilter/FilterFieldDateRange.vue';

	import ActiveFilterFieldText from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ListingFilter/ActiveFilterFieldText.vue';
	import ActiveFilterFieldRange from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/ListingFilter/ActiveFilterFieldRange.vue';

	const props = defineProps([
		'propsdata',
		'bm'
	]);
	
	const lf = reactive(new ListingFilter());
	const attributes = ref(null);

	const componentMap = {
		FilterFieldRange,
		FilterFieldSelect,
		FilterFieldText,
		FilterFieldDateRange
	};

	onBeforeMount(() => {
		attributes.value = JSON.parse(JSON.stringify(props.bm.propsdata.bookmark.config.current.attributes));
		setFilterFields();
		props.bm.setColumns();
		if(props.propsdata.sort_order){
			lf.sortOrder = props.propsdata.sort_order;
		}
	});

	const setFilterFields = function(){
		props.bm.drop_down_columns.forEach(column => {
			if(props.bm.config_cols[column].filter){
				let foundItem = props.bm.propsdata.bookmark.config.current.filters.find(i => (column == i.key));
				if(props.bm.config_cols[column].filter_input == 'FilterFieldRange' || props.bm.config_cols[column].filter_input == 'FilterFieldDateRange'){
					let _from = '';
					let _to = '';
					if(typeof foundItem != 'undefined'){
						_from = foundItem.values?.from ? foundItem.values.from : ''
						_to = foundItem.values?.to ? foundItem.values.to : ''
					}
					lf.addFieldToFilter(column, {from: _from, to: _to});
				}
				else {
					let val = ''
					if(typeof foundItem != 'undefined'){
						val = foundItem.value;
					}

					lf.addFieldToFilter(column, val);
				}
			}
		});
	}

	const getComponent = function(name) {
		return componentMap[name] || null;
	}
</script>

<template>
	<div class="row filter-section mb-5">
		<div class="col-12">
			<div class="row">
				<div class="col-6 col-6 d-flex align-items-center">
					<div>
						<span class="fw-bold me-3 d-block w-100">Filters: </span>
					</div>
					<div class="fs-12">
						<template v-for="(activeFilter, key) in lf.filterFields">
							<template v-if="typeof activeFilter == 'string'">
								<ActiveFilterFieldText :listingFilter="lf" :label="attributes[key]" :field="key"></ActiveFilterFieldText>
							</template>
							<template v-else>
								<ActiveFilterFieldRange :listingFilter="lf" :label="attributes[key]" :field="key"></ActiveFilterFieldRange>
							</template>
						</template>
					</div>
				</div>
				<div class="col-6">
					<div class="d-flex align-items-center justify-content-end py-3">
						<button type="button" class="btn btn-outline-secondary" @click="lf.showFilters()">
							Filters
						</button>
						<div class="ms-2 listing-action-dropdown-wrapper">
							<button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#collapseColumns" aria-expanded="false" aria-controls="collapseColumns">
								Columns
							</button>
							<div id="collapseColumns" class="listing-action-dropdown lg collapse" style="width: 600px">
								<div class="card">
									<div class="card-header">
										{{ bm.countVisibleTrue('Visible Column') }}
									</div>
									<div class="card-body">
										<div class="row">
											<template v-for="column in bm.drop_down_columns">
												<div class="col-4">
													<div class="form-check">
														<input class="form-check-input" type="checkbox" :id="column" v-model="bm.config_cols[column].visible" @change="bm.columnShowHide(column)">
														<label class="form-check-label text-truncate d-block" :for="column">
															{{ bm.getColumnLabel(column) }}
														</label>
													</div>
												</div>
											</template>
										</div>
									</div>
									<div class="card-footer text-end">
										<button class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#collapseColumns" aria-expanded="false" aria-controls="collapseColumns">Close</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 dropdown-divider" v-show="lf.isShowFilter"></div>
		<div class="col-12" v-show="lf.isShowFilter">
			<form @submit.prevent="lf.applyFilters()" action="" method="GET">
				<div class="filter-fields p-2">
					<div class="row">
						<template v-for="column in bm.drop_down_columns" >
							<component 
								:is="getComponent(bm.config_cols[column].filter_input)" 
								:name="bm.getColumnLabel(column)" 
								class="col-3" 
								:field="column" 
								:options="bm.config_cols[column].filter_options"
								v-model="lf.filterFields[column]"
								v-if="bm.config_cols[column].visible && bm.config_cols[column].filter" 
							/>
						</template>
					</div>
					<div class="row">
						<div class="col-12 text-end">
							<div class="mb-3">
								<button class="btn btn-outline-primary">Apply Filters</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<Listing :propsdata="props.propsdata">
		<template v-for="column in bm.propsdata.listing.columns" :key="column" v-slot:[`th_${column}`]>
			<ListingThSort :sortorder="lf.sortOrder" :column_name="column" @click="lf.setSortOrder(column)">
				{{ bm.getColumnLabel(column) }}
			</ListingThSort>
		</template>

		<template v-for="column in bm.propsdata.listing.columns" :key="column" v-slot:[column]="{ item }">
			<slot :name="column" v-bind="{'item': item}" />
		</template>

		<template #item="{ item }">
			<slot name="itemaction" v-bind="{'item': item}" />
		</template>
	</Listing>
</template>