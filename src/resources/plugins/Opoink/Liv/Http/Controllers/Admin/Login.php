<?php
namespace Plugins\Opoink\Liv\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Plugins\Opoink\Liv\Models\AdminUser;

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

	public function forgotPassword(Request $request){
		return inertiaRender('Opoink/Liv/resources/js/Pages/Admin/Users/ForgotPassword', [
		]);
	}

	public function forgotPasswordCode(Request $request){
		$this->validate($request, [
            'email' => 'required|email|exists:admins,email',
        ]);

		$email = $request->input('email');
		$user = AdminUser::where('email', $email)->first();

		if($user){

			dd($user);

			// $code = rand(1000, 9999);
			// $user->forgot_password_code = $code;
			// $user->forgot_password_code_expire = date('Y-m-d H:i:s', strtotime('+2 hour'));
			// $user->save();

			// AdminUser::where('email', $email)->update([
			// 	'forgot_password_code' => $code,
			// 	'forgot_password_code_expire' => date('Y-m-d H:i:s', strtotime('+2 hour'))
			// ]);

			// \Mail::to($email)->send(new \Opoink\Oliv\app\Mail\ForgotPassword($code));
			// return redirect()->route('admin.forgot-password')->withSuccess([
			// 	'Please check your email for the code.'
			// ]);
		} 
		else {
			// return response()->json([
			// 	'message' => 'CMS block successfully saved.',
			// 	'data' => $model
			// ], 200);
		}
	}
}
?>