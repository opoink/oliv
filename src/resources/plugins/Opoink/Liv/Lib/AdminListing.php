<?php
namespace Plugins\Opoink\Liv\Lib;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Plugins\Opoink\Liv\Models\ListingBookmark as ListingBookmark;
use Plugins\Opoink\Liv\Lib\Facades\QryFilter;
use Inertia\Inertia;

class AdminListing {

	/**
	 * @var \Plugins\Opoink\Liv\Models\ListingBookmark
	 */
	protected $bookmark;

	/**
	 * @var array
	 */
	protected $columns;

	/**
	 * @var string path to the default bookmark JSON file
	 */
	protected $defaultBookMarkTargetFile;

	/**
	 * @var string
	 */
	protected $namespace;

	/**
	 * @param string $targetFile absolute path to bookmark JSON file
	 * @return \Plugins\Opoink\Liv\AdminListing
	 */
	public function setTargetDefaultBookmark(string $targetFile){
		$this->defaultBookMarkTargetFile = base_path($targetFile);
		return $this;
	}

	/**
	 * @return \Plugins\Hnd\Base\Lib\AdminListing
	 */
	public function setNamespace(string $namespace){
		$this->namespace = $namespace;
		return $this;
	}

	public function getNamespace(){
		return  $this->namespace;
	}

	/**
	 * @return \Plugins\Opoink\Liv\Models\ListingBookmark
	 */
	public function getBookmark(){
		if(!$this->bookmark){
			$user = auth()->guard('admin')->user();

			$namespace = $this->getNamespace();

			$cacheKey = 'listing_bookmark_'.$namespace.'_' . $user->id;
			
			$bookmark =  Cache::rememberForever($cacheKey, function() use($namespace, $user) {

				$bookmark = ListingBookmark::getBookmark($namespace, function() use($namespace, $user) {
					$config = Json::encode(Json::decode(file_get_contents($this->defaultBookMarkTargetFile)));
					return ListingBookmark::createBookmark($user->id, $namespace, $config);
				});

				$bookmark->config = Json::decode($bookmark->config);


				$config = $bookmark->config;
				$cols = $config['current']['columns'];
				foreach ($cols as $keycols => $valuecols) {
					if(isset($valuecols['filter_options']) && !empty($valuecols['filter_options'])){
						if(!is_string($valuecols['filter_options'])){
							continue; /** The filter_options was already rendered as an array */
						}

						$className = $valuecols['filter_options'];
						$cols[$keycols]['filter_options'] = app($className)->toOptionArray();
					}
				}

				$config['current']['columns'] = $cols;
				$bookmark->config = $config;
				return $bookmark;
			});

			$this->bookmark = $bookmark;
		}

		return $this->bookmark;
	}

	/**
	 * @return array
	 */
	public function getCollumns(){
		if(!$this->columns){
			$bookmark = $this->bookmark;

			$columns = [];
			foreach ($bookmark->config['current']['positions'] as $key => $value) {
				$columns[$value] = $key;

				// QryFilter::addFieldMap($key, 'CPE.' . $key);
			}
			ksort($columns);
			$columns = array_values($columns);

			$this->columns = $columns;
		}
		return $this->columns;
	}

	/**
	 * @param \Illuminate\Database\Eloquent\Builder $model namespace of an elloquent model
	 * @param string $from  
	 */
	public function getPaginator(string $model, ?callable $callback = null){
		$bookmark = $this->updateBookmark();
		$columns = $this->getCollumns();

		$qry = $model::query();
		if($callback){
			$callback($qry, $bookmark, $columns);
		}

		$request = request();
		$request->filters = $bookmark->config['current']['filters'];
		if(isset($bookmark->config['current']['sort_order'])){
			$request->sort_order = $bookmark->config['current']['sort_order'];
		}

		QryFilter::setFilters($request, $qry, $columns);
		$sortOrder = QryFilter::setSortOrder($request, $qry, $columns);
		
		// Force Laravel to use your current page
		Paginator::currentPageResolver(function () use ($bookmark) {
			return $bookmark->config['current']['paging']['current'];
		});

		$per_page = $bookmark->config['current']['paging']['pageSize'];
		$paginator = $qry->paginate($per_page);
		$paginator->appends($request->input())->links();

		return [
			'paginator' => $paginator,
			'sort_order' => $sortOrder
		];
	}

	public function getInertiaVersion(){
		return Inertia::getVersion();
	}


	/**
	 * @return \Plugins\Opoink\Liv\Models\ListingBookmark
	 */
	public function updateBookmark(){
		$bookmark = $this->getBookmark();

		$origConfig = Json::encode($bookmark->config);

		$config = $bookmark->config;
		if(request()->page){
			$config['current']['paging']['current'] = (int)request()->page;
		}

		if(request()->sort_order){
			$so = request()->sort_order;
			if(isset($so['key'])){

				if(!isset($so['value'])) {
					$so['value'] = 'ASC';
				}

				$config['current']['sort_order'] = $so;
			}
		}

		if(request()->apply_filter && request()->apply_filter == 1){
			if(request()->filters){
				$config['current']['filters'] = request()->filters;
			}
			else {
				$config['current']['filters'] = [];
			}
		}

		$newConfig = Json::encode($config);

		if($origConfig != $newConfig){
			$bookmark->config = $newConfig;
			$bookmark->save();

			$bookmark->config = $config;

			$user = auth()->guard('admin')->user();
			$cacheKey = 'listing_bookmark_'.$this->namespace.'_' . $user->id;
			Cache::forget($cacheKey);
		}

		return $bookmark;
	}

	public function saveVisibleColumns(int $id, array $columns){
		$bookmark = ListingBookmark::find($id);
		$config = json_decode($bookmark->config, true);

		foreach ($columns as $key => $value) {
			if(isset($config['current']['columns'][$key])){
				if($config['current']['columns'][$key]['visible'] == $value['visible']){
					continue;
				}
				$config['current']['columns'][$key]['visible'] = $value['visible'];
			}
		}

		$bookmark->config = json_encode($config);
		$bookmark->save();

		$cacheKey = 'listing_bookmark_'.$bookmark->namespace.'_' . $bookmark->admins_id;
		Cache::forget($cacheKey);

		$this->setNamespace($bookmark->namespace);
		return $this->getBookmark();
	}
}
?>