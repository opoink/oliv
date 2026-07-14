<?php
namespace Plugins\Opoink\Email\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Plugins\Opoink\Email\Models\Emails AS EmailsModel;
use Illuminate\Support\Facades\DB;
use Plugins\Opoink\Liv\Lib\AdminListing;

class EmailController extends Controller {

	/**
	 * Handle the incoming request.
	 */
	public function __invoke(Request $request)
	{
		$isRoleAllowed = isRoleAllowed('oliv_email_list');
		if(!$isRoleAllowed){
			return redirect()->route('admin.index')->withErrors([
				'You need permission to view all emails.'
			]);
		}
		
		$adminListing = app(AdminListing::class);

		$targetFile = 'plugins/Opoink/Email/Lib/Bookmarks/emails.json';

		["paginator" => $paginator, "sort_order" => $sortOrder] = $adminListing->setTargetDefaultBookmark($targetFile)
		->setNamespace('opoink_email_listing')
		->getPaginator(EmailsModel::class);

		$bookmark = $adminListing->getBookmark();
		$columns = $adminListing->getCollumns();

		return inertiaRender('Opoink/Email/resources/js/Pages/Admin/Emails/Index', [
			'propsdata' => [
				'sort_order' => $sortOrder,
				'filters' => $request->filters,
				'listing' => [
					'columns' => $columns,
					'per_page' => $bookmark->config['current']['paging']['pageSize'],
					'paginator' => $paginator
				],
				'bookmark' => $bookmark,
				'inertia_version' => $adminListing->getInertiaVersion()
			]
		]);
	}

	public function addEdit(Request $request, $id=null){
		$isRoleAllowed = isRoleAllowed('oliv_email_add_edit');
		if(!$isRoleAllowed){
			return redirect()->route('admin.emails')->withErrors([
				'You need permission to add or edit an email content.'
			]);
		}

		$page_name = 'Add Email Content';

		$model = null;
		if($id){
			$model = EmailsModel::find($id);

			if(!$model){
				return redirect()->route('admin.emails')->withErrors([
					'Email with ID '.$id.' not found'
				]);
			}

			$page_name = 'Edit Email Content';
		}

		return inertiaRender('Opoink/Email/resources/js/Pages/Admin/Emails/AddEdit', [
			'propsdata' => [
				'page_name' => $page_name,
				'model' => $model
			]
		]);
	}

	public function saveAction(Request $request){
		$isRoleAllowed = isRoleAllowed('oliv_email_add_edit');
		if(!$isRoleAllowed){
			return response()->json([
				'message' => 'You need permission to add or edit an email.'
			], 422);
		}
		
		$data = $this->validate($request, [
			'id' => 'numeric|exists:emails,id',
			'name' => 'required',
			'subject' => 'required',
			'content' => 'required',
			'css' => ''
		]);

		if(!empty($data['id'])){		
			$model = EmailsModel::find($data['id']);
			if(!$model){
				return response()->json([
					'message' => 'Email with ID '.$data['id'].' not found'
				], 422);
			}
		}
		else {
			$model = new EmailsModel();
		}


		DB::beginTransaction();
		try {
			$model->name = $data['name'];
			$model->subject = $data['subject'];
			$model->content = $data['content'];
			$model->css = $data['css'];
			$model->save();

			DB::commit();
			return response()->json([
				'message' => 'Email content saved successfully',
				'data' => $model
			], 200);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json([
				'message' => $e->getMessage()
			], 500);
		}
	}

	public function deleteAction(Request $request, $id=null){
		$isRoleAllowed = isRoleAllowed('oliv_email_delete');
		if(!$isRoleAllowed){
			return response()->json([
				'message' => 'You need permission to delete an email.'
			], 422);
		}

		$model = EmailsModel::find($id);
		if($model){
			$model->delete();
			return response()->json([
				'message' => 'Email content deleted'
			], 200);
		}
		else {
			return response()->json([
				'message' => 'Email with ID '.$id.' not found'
			], 422);
		}
	}
}