<?php
namespace Opoink\Oliv\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
// use Tighten\Ziggy\Ziggy;

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
			// 'ziggy' => fn () => [
            //     ...(new Ziggy)->toArray(),
            //     'location' => $request->url(),
            // ],
        ];

		$adminUser = auth()->guard('admin')->user();
		if($adminUser){
			unset($adminUser->password);
			$adminUser = $adminUser->getAttributes();

			if($adminUser['admin_type'] == 'super_admin'){
				$adminUser['roles_resource'] = "*";
			}
			else {
				$adminUser['roles_resource'] = getRolesResource($adminUser['admin_user_role_id']);
			}
		}

		$data['auth'] = [
			'admin' => $adminUser,
			'client' => $request->user()
		];

		/** add only for admin routes */
		if(isAdminRoute()){
			$data['admin_url'] = getAdminUrl();
			$data['adminmenu'] = config('adminmenus');
		}

		return array_merge(parent::share($request), $data);
    }
}
