<?php
namespace Plugins\Opoink\Liv\Lib;

class QryFilter {

	protected $fieldMap = [];

	/**
	 * @param string $key
	 * @param string $mapKey
	 */
	public function addFieldMap(string $key, string $mapKey){
		$this->fieldMap[$key] = $mapKey;
	}
	
	/**
	 * @param \Illuminate\Http\Request $request
	 * @param \Illuminate\Database\Eloquent\Builder $qry
	 * @param array $columns
	 */
	public function setFilters(
		\Illuminate\Http\Request $request, 
		\Illuminate\Database\Eloquent\Builder $qry, 
		array $columns
	){
		if(is_array($request->filters)){
			$fieldMap = $this->fieldMap;
			foreach ($request->filters as $filter) {
				$urlParams = new \Opoink\Oliv\Lib\DataObject($filter);

				$key = $urlParams->getKey();
				if(isset($fieldMap[$key])){
					$key = $fieldMap[$key];
				}

				if(!in_array($urlParams->getKey(), $columns)){
					continue;
				}

				if(!empty($key)){
					$value = $urlParams->getValue();
					$values = $urlParams->getValues();
					$condition = $urlParams->getCondition();

					if(!$condition) {
						$condition = 'LIKE';
					}
					if($value && !$values){
						if($condition == 'LIKE'){
							$value = '%'.$value.'%';
						}
						$qry->where($key, $condition, $value);
					}
					elseif($values && !$value){
						$from = $urlParams->getData('values/from');
						$to = $urlParams->getData('values/to');
						if($from && $to){
							$qry->whereBetween($key, [$from, $to]);
						}
						elseif($from && !$to){
							$qry->where($key, ">=", $from);
						}
						elseif(!$from && $to){
							$qry->where($key, "<=", $to);
						}
					}
				}
			}
		}
	}

	
	/**
	 * @param \Illuminate\Http\Request $request
	 * @param \Illuminate\Database\Eloquent\Builder $qry
	 * @param array $columns
	 */
	public function setSortOrder(
		\Illuminate\Http\Request $request, 
		\Illuminate\Database\Eloquent\Builder $qry, 
		array $columns
	){
		$fieldMap = $this->fieldMap;

		$sortOrder = [
			'key' => $qry->getModel()->getKeyName(),
			'value' => 'desc'
		];
		if($request->sort_order){
			if(in_array($request->sort_order['key'], $columns) && in_array($request->sort_order['value'], ['asc', 'desc'])){
				$sortOrder = $request->sort_order;
			}
		}

		$key = $sortOrder['key'];
		if(isset($fieldMap[$key])){
			$key = $fieldMap[$key];
		}

		$qry->orderBy($key, $sortOrder['value']);

		return $sortOrder;
	}
}
?>