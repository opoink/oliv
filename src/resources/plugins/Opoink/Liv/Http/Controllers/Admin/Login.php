<?php
namespace Plugins\Opoink\Liv\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Login extends Controller {

	public function __construct(
    ){}

	/**
	 * Handle the incoming request.
	 */
	public function __invoke(Request $request)
	{
		return inertiaRender('Opoink/Liv/resources/js/Pages/Admin/Users/Login', [
		]);
	}

	public function authUser(Request $request){
		$this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(auth()->guard('admin')->attempt(['email' => $request->input('email'),  'password' => $request->input('password')])){
            $user = auth()->guard('admin')->user();
            return redirect()->route('admin.index')->withSuccess([
				'Welcome back.'
			]);
        }else {
            return redirect()->route('admin.login')->withErrors([
				'Whoops! invalid email and password.'
			]);
        }
	}

	public function adminLogout(){
		auth()->guard('admin')->logout();
		return redirect()->route('admin.login');
	}
}
?>