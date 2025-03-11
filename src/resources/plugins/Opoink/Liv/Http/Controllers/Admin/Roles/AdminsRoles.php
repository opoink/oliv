<?php
namespace Plugins\Opoink\Liv\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use \Plugins\Opoink\Liv\Models\AdminsRoles as AdminsRolesModel;
use \Plugins\Opoink\Liv\Models\AdminUserRolesResource as AdminUserRolesResourceModel;

class AdminsRoles extends Controller {

	public function __construct(
    ){}

	/**
	 * Handle the incoming request.
	 */
	public function __invoke(Request $request)
	{
		$isRoleAllowed = isRoleAllowed('oliv_roles');
		if(!$isRoleAllowed){
			return redirect()->route('admin.index')->withErrors([
				'You need permission to view admin roles.'
			]);
		}

		$columns = ['id', 'role_name', 'created_at', 'updated_at'];
		// // $cursor_name = 'admins';
		$per_page = 15;

		$paginator = AdminsRolesModel::cursorPaginate($per_page);

		return inertiaRender('Opoink/Liv/resources/js/Pages/Admin/Roles/Index', [
			'propsdata' => [
				'listing' => [
					'columns' => $columns,
					// 'cursor_name' => $cursor_name,
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
		$page_name = 'Add New Role';
		$role = null;
		$form_role_resources = [];
		if($id){
			$role = AdminsRolesModel::find($id);
			if(!$role){
				return redirect()->route('admin.users.admins.roles.index')->withErrors([
					'Admin user with ID '.$id.' not found'
				]);
			}

			$role_resources = AdminUserRolesResourceModel::where('admin_user_role_id', $id)->get();
			
			foreach ($role_resources as $role_resource) {
				$form_role_resources[] =  $role_resource->resource;
			}

			$page_name = 'Edit Role: ' . $role->role_name;
		}

		$config_role_resources = config('plugins.roleresource');

		return inertiaRender('Opoink/Liv/resources/js/Pages/Admin/Roles/AddEdit', [
			'propsdata' => [
				'page_name' => $page_name,
				'role' => $role,
				'form_role_resources' => $form_role_resources,
				'config_role_resources' => $config_role_resources
			]
		]);
	}

	public function saveAction(Request $request)
	{
		$data = $this->validate($request, [
            'role_name' => 'required',
        ]);

		$id = (int)$request->input('id');
		if($id){
			$role = AdminsRolesModel::find($id);
			if(!$role){
				return redirect()->route('admin.users.admins.roles.index')->withErrors([
					'Role user with ID '.$id.' not found'
				]);
			}
			else {
				AdminsRolesModel::where('id', $id)->update($data);
			}
		}
		else {
			$role = new AdminsRolesModel;
			$role->role_name = $request->input('role_name');
			$role->save();
			$id = $role->id;
		}

		AdminUserRolesResourceModel::where('admin_user_role_id', $id)->delete();

		$resource = $request->input('resource');
		foreach ($resource as $key => $value) {
			$model = new AdminUserRolesResourceModel;
			$model->resource = $value;
			$model->admin_user_role_id = $id;
			$model->save();

		}

		return redirect()->route('admin.users.admins.roles.edit', ['id' => $id])->withSuccess([
			'Role user successfully saved.'
		]);
	}

	public function deleteAction(Request $request, $id=null)
	{
		$adminRole = AdminsRolesModel::find($id);

		if($adminRole){
			$adminRole->delete();
			return response()->json([
				'message' => 'Role user successfully deleted.'
			], 200);
		}
		else {
			return response()->json([
				'errors' => [
					'Role user with ID '.$id.' not found'
				]
			], 406);	
		}
	}
}
?>