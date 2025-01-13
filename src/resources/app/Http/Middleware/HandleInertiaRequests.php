<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Inertia\Inertia;
use Tightenco\Ziggy\Ziggy;
use \App\Models\AdminUserRolesResource;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {

		$data = [
			'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];


		$adminUser = auth()->guard('admin')->user();
		if($adminUser){
			unset($adminUser->password);
			$adminUser = $adminUser->getAttributes();

			if($adminUser['admin_type'] == 'super_admin'){
				$adminUser['roles_resource'] = "*";
			}
			else {
				// if (!session()->has('roles_resource')) {
				// 	$resources =  AdminUserRolesResource::where('admin_user_role_id', $adminUser['admin_user_role_id'])->get();
				// 	$roles_resource = [];
				// 	foreach ($resources as $resource) {
				// 		$roles_resource[$resource->resource] = $resource->resource;
				// 	}
				// 	session()->put('roles_resource', $roles_resource);
				// 	session()->save();
				// }
				// else {
				// 	$roles_resource = session()->get('roles_resource');
				// }
				// $adminUser['roles_resource'] = $roles_resource;

				$adminUser['roles_resource'] = getRolesResource($adminUser['admin_user_role_id']);
			}
		}

		$data['auth'] = [
			'admin' => $adminUser,
			'client' => $request->user()
		];

		/** add only for admin routes */
		if(isAdminRoute()){
			$data['adminmenu'] = config('plugins.adminmenu');
		}

		return array_merge(parent::share($request), $data);


    }
}
