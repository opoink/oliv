<?php
namespace Plugins\Opoink\Cms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lib\Writer;
use Plugins\Opoink\Cms\Models\CmsPages;
use Plugins\Opoink\Cms\Lib\Cms;

class PagesController extends Controller {

	/**
	 * Handle the incoming request.
	 */
	public function __invoke(Request $request)
	{
		$columns = ['id', 'name', 'identifier', 'created_at', 'updated_at'];

		$qry = CmsPages::select('*');

		$per_page = 15;
		$paginator = $qry->cursorPaginate($per_page);
		return inertiaRender('Opoink/Cms/resources/js/Pages/Admin/Pages/Index', [
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
		$page_name = 'Add New CMS Page';
		if($id){
			$model = CmsPages::find($id);

			if(!$model){
				return redirect()->route('admin.cms.pages.index')->withErrors([
					'CMS page with ID '.$id.' not found'
				]);
			}

			$page_name = 'Edit CMS Page';
		}

		$isRoleAllowed = isRoleAllowed('cms_pages_add_edit');
		if(!$isRoleAllowed){
			return redirect()->route('admin.cms.pages.index')->withErrors([
				'You do not have permission to add or edit a CMS pages.'
			]);
		}

		return inertiaRender('Opoink/Cms/resources/js/Pages/Admin/Pages/AddEdit', [
			'propsdata' => [
				'page_name' => $page_name,
				'cms_page' => $model
			]
		]);
	}

	/**
	 * Handle the incoming request.
	 */
	public function saveAction(Request $request)
	{
		$data = $this->validate($request, [
			'name' => 'bail|required|max:255',
			'identifier' => 'bail|required|max:255',
			'meta_title' => 'bail|required|max:255',
			'meta_keywords' => 'bail|required|max:255',
			'meta_description' => 'bail|required|max:255',
			'content' => 'bail|required',
		]);

		$isRoleAllowed = isRoleAllowed('cms_pages_save_action');
		if($isRoleAllowed){
			$id = (int)$request->input('id');

			try {
				$identifier = '';

				if($id){
					$model = CmsPages::find($id);
					if(!$model){
						return response()->json([
							'errors' => ['CMS page with ID '.$id.' not found']
						], 406);
					}
					else {
						$identifier = $model->identifier;
						// CmsPages::where('id', $id)->update([
						// 	'name' => $request->input('name'),
						// 	'content' => json_encode($request->input('content')),
						// 	'meta_title' => $request->input('meta_title'),
						// 	'meta_keywords' => $request->input('meta_keywords'),
						// 	'meta_description' => $request->input('meta_description'),
						// ]);
						
						$model->name = $request->input('name');
						$model->content = json_encode($request->input('content'));
						$model->meta_title = $request->input('meta_title');
						$model->meta_keywords = $request->input('meta_keywords');
						$model->meta_description = $request->input('meta_description');
						$model->save();
					}
				}
				else {
					$identifier = str_replace(' ', '-', $request->input('identifier'));
					$identifier = preg_replace('/[^A-Za-z0-9\-]/', '', $identifier);
					$identifier = strtolower($identifier);

					if(!$this->isIdentifierExist($identifier)){
						$model = new CmsPages;
						
						$model->name = $request->input('name');
						$model->identifier = $identifier;
						$model->content = json_encode($request->input('content'));
						$model->meta_title = $request->input('meta_title');
						$model->meta_keywords = $request->input('meta_keywords');
						$model->meta_description = $request->input('meta_description');
						$model->save();
					}
					else {
						return response()->json([
							'errors' => ['The identifier already exists.']
						], 406);
					}
				}

				if(!empty($identifier)){
					$cms = app(Cms::class);
					$cms->saveComponent($identifier, $request->input('content'), 'Pages');

					return response()->json([
						'message' => 'CMS page ' . $request->input('name') . ' successfully saved.',
						'data' => $model
					], 200);
				}
				
			} catch (\Exception $e) {
				return response()->json([
					'errors' => [$e->getMessage()]
				], 406);
			}
		}
		else {
			return response()->json([
				'errors' => ['You do not have permission to add or edit a CMS pages.']
			], 406);
		}
	}

	public function isIdentifierExist($identifier){
		$model = new CmsPages;
		$model->where('identifier', '=', $identifier);
		$model->get();

		return $model->first() != null;
	}
}