<?php
namespace Opoink\Oliv\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

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
		$data = [];

		$adminUser = auth()->guard('admin')->user();
		if($adminUser){
			unset($adminUser->password);

            try {
                $event = app(\Plugins\Opoink\Liv\Lib\Event::class);
                $event->dispatch('Opoink_Oliv_Middleware_HandleInertiaRequests', [
                    'adminUser' => $adminUser
                ]);
            } catch (\Throwable $th) {
                /** do nothing for now */
            }
			$adminUser = $adminUser->getAttributes();

            /**
             * if the $adminUser['roles_resource'] was already set
             * means that an event listener already set $adminUser['roles_resource']
             * so we dont need to set it here again
             */
            if(!isset($adminUser['roles_resource'])){
                if($adminUser['admin_type'] == 'super_admin'){
                    $adminUser['roles_resource'] = "*";
                }
                else {
                    $adminUser['roles_resource'] = getRolesResource($adminUser['admin_user_role_id']);
                }
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
