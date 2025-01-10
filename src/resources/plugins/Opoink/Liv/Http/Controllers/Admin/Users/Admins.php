<?php
namespace Plugins\Opoink\Liv\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Plugins\Opoink\Liv\Models\AdminUser;
use Plugins\Opoink\Liv\Models\AdminsRoles as AdminsRolesModel;

class Admins extends Controller {

	public function __construct(
    ){}

	/**
	 * Handle the incoming request.
	 */
	public function __invoke(Request $request)
	{
		$columns = ['id', 'firstname', 'lastname', 'email', 'admin_user_role_id', 'created_at', 'updated_at'];
		// $cursor_name = 'admins';
		$per_page = 15;

		$paginator = AdminUser::cursorPaginate($per_page);

		/**
		 * to get the role info
		 */
		foreach ($paginator as $key => $value) {
			$value->admin_user_role;
		}

		return inertiaRender('Opoink/Liv/resources/js/Pages/Admin/Users/Admins/Index', [
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
		$admin = null;
		$roles = null;
		$admin_user_roles = AdminsRolesModel::get();
		$page_name = 'Add New Admin User';
		if($id){
			$admin = AdminUser::find($id);

			if(!$admin){
				return redirect()->route('admin.users.admins.index')->withErrors([
					'Admin user with ID '.$id.' not found'
				]);
			}

			$page_name = 'Edit Admin User';
		}

		$adminUser = getAuthAdminUser();
		$isRoleAllowed = isRoleAllowed('add_edit_admin_users');

		if($admin){
			if($admin->id == $adminUser['id']){
				$isRoleAllowed = true;
			}
		}

		if(!$isRoleAllowed){
			return redirect()->route('admin.users.admins.index')->withErrors([
				'You do not have permission to add or edit an admin user.'
			]);
		}

		return inertiaRender('Opoink/Liv/resources/js/Pages/Admin/Users/Admins/AddEdit', [
			'propsdata' => [
				'page_name' => $page_name,
				'admin' => $admin,
				'admin_user_roles' => $admin_user_roles
			]
		]);
	}

	/**
	 * Handle the incoming request.
	 */
	public function saveAction(Request $request)
	{
		$data = $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'password' => '',
            'admin_user_role_id' => '',
        ]);

		if( empty($data['password']) ){
			unset($data['password']);
		}
		else {
			$data['password'] = Hash::make($data['password']);
		}

		$adminUser = getAuthAdminUser();

		$id = (int)$request->input('id');
		$isRoleAllowed = isRoleAllowed('add_edit_admin_users');
		if($isRoleAllowed || $id == $adminUser['id']){
			if($id){
				$admin = AdminUser::find($id);
				if(!$admin){
					return redirect()->route('admin.users.admins.index')->withErrors([
						'Admin user with ID '.$id.' not found'
					]);
				}
				else {
					if($admin->email != $request->input('email')){
						$isEmailExist = AdminUser::where('email', $request->input('email'))->first();
						if($isEmailExist){
							return redirect()->route('admin.users.admins.edit', ['id' => $id])->withErrors([
								'The email ' . $request->input('email') . ' was already used by another admin user'
							]);
						}
					}
	
					AdminUser::where('id', $id)->update($data);
				}
			}
			else {
				$adminUser = new AdminUser;
				$adminUser->firstname = $request->input('firstname');
				$adminUser->lastname = $request->input('lastname');
				$adminUser->email = $request->input('email');
				$adminUser->password = $request->input('password');
				$adminUser->admin_user_role_id = $request->input('admin_user_role_id');
				$adminUser->save();
	
				$id = $adminUser->id;
			}
	
			return redirect()->route('admin.users.admins.edit', ['id' => $id])->withSuccess([
				'Admin user successfully saved.'
			]);
		}
		else {
			return redirect()->route('admin.users.admins.index')->withErrors([
				'You do not have permission to add or edit an admin user.'
			]);
		}
	}

	public function deleteAction(Request $request, $id=null)
	{
		$adminUser = AdminUser::find($id);

		if($adminUser){
			$adminUser->delete();
			return response()->json([
				'message' => 'Admin user successfully deleted.'
			], 200);
		}
		else {
			return response()->json([
				'errors' => [
					'Admin user with ID '.$id.' not found'
				]
			], 406);	
		}
	}
}
?>