<?php
namespace Plugins\Opoink\Cms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Plugins\Opoink\Cms\Models\CmsBlock;
use Plugins\Opoink\Cms\Lib\Cms;

class BlockController extends Controller {

	public function __construct(
		protected \Opoink\Oliv\Lib\Writer $writer
	){}

	/**
	 * Handle the incoming request.
	 */
	public function __invoke(Request $request)
	{
		$columns = ['id', 'name', 'identifier', 'created_at', 'updated_at'];

		$qry = CmsBlock::select('*');
		if(is_array($request->filters)){
			foreach ($request->filters as $filter) {
				$urlParams = new \Opoink\Oliv\Lib\DataObject($filter);

				$key = $urlParams->getKey();

				if(!in_array($key, $columns)){
					continue;
				}
				
				if(!empty($key)){
					$value = $urlParams->getValue();
					$values = $urlParams->getValues();
					$condition = $urlParams->getCondition();

					if(!$condition) {
						$condition = 'LIKE';
					}
					
					if($value){
						if($condition == 'LIKE'){
							$value = '%'.$value.'%';
						}
						$qry->where($key, $condition, $value);
					}
					elseif($values){
						$from = (int)$urlParams->getData('values/from');
						$to = (int)$urlParams->getData('values/to');

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

		$per_page = 15;
		$paginator = $qry->cursorPaginate($per_page);
		return inertiaRender('Opoink/Cms/resources/js/Pages/Admin/Block/Index', [
			'propsdata' => [
				'filters' => $request->filters,
				'listing' => [
					'columns' => $columns,
					'per_page' => $per_page,
					'paginator' => $paginator
				]
			]
		]);
	}

	/**
	 * Handle the incoming request.
	 */
	public function addEdit(Request $request, $id=null)
	{
		$model = null;
		$page_name = 'Add New CMS Block';
		if($id){
			$model = CmsBlock::find($id);

			if(!$model){
				return redirect()->route('admin.cms.block.index')->withErrors([
					'CMS block with ID '.$id.' not found'
				]);
			}

			$page_name = 'Edit CMS Block';
		}

		$isRoleAllowed = isRoleAllowed('cms_block_add_edit');
		if(!$isRoleAllowed){
			return redirect()->route('admin.cms.block.index')->withErrors([
				'You do not have permission to add or edit a CMS block.'
			]);
		}

		return inertiaRender('Opoink/Cms/resources/js/Pages/Admin/Block/AddEdit', [
			'propsdata' => [
				'page_name' => $page_name,
				'cms_block' => $model
			]
		]);
	}

	/**
	 * Handle the incoming request.
	 */
	public function saveAction(Request $request)
	{
		$data = $this->validate($request, [
			'name' => 'required',
			'identifier' => 'required'
		]);

		$isRoleAllowed = isRoleAllowed('cms_block_save_action');
		if($isRoleAllowed){
			$id = (int)$request->input('id');

			$identifier = '';
			if($id){
				$model = CmsBlock::find($id);
				if(!$model){
					return redirect()->route('admin.cms.block.index')->withErrors([
						'CMS block with ID '.$id.' not found'
					]);
				}
				else {
					$identifier = $model->identifier;
					
					$model->name = $request->input('name');
					$model->content = json_encode($request->input('content'));
					$model->saveQuietly();
				}
			}
			else {
				$identifier = str_replace(' ', '-', $request->input('identifier'));
				$identifier = preg_replace('/[^A-Za-z0-9\-]/', '', $identifier);
				$identifier = strtolower($identifier);

				if(!$this->isIdentifierExist($identifier)){
					$model = new CmsBlock;
					$model->name = $request->input('name');
					$model->identifier = $identifier;
					$model->content = json_encode($request->input('content'));
					$model->save();
		
					$id = $model->id;
				}
				else {
					return response()->json([
						'errors' => ['The identifier already exists.']
					], 406);
				}
			}
			
			return response()->json([
				'message' => 'CMS block successfully saved.',
				'data' => $model
			], 200);
		}
		else {
			return response()->json([
				'errors' => [
					'You do not have permission to add or edit a CMS block.'
				]
			], 406);
		}
	}

	

	public function isIdentifierExist($identifier){
		$model = new CmsBlock;
		return $model->where('identifier', $identifier)->first();
	}

	public function deleteAction(Request $request, $id=null){
		$isRoleAllowed = isRoleAllowed('cms_block_delete_action');
		if($isRoleAllowed){
			$model = CmsBlock::find($id);
			if($model){
				$dirName = CmsBlock::getDirName($model->identifier);
				$path = CmsBlock::getComponentPath($dirName);

				$this->writer->deleteDir($path);

				$model->delete();
				return response()->json([
					'message' => 'CMS block successfully deleted.'
				], 200);
			}
		}
		else {
			return response()->json([
				'errors' => [
					'You do not have permission to delete a CMS block.'
				]
			], 406);
		}
	}
}
?>