<?php
namespace Plugins\Opoink\Liv\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Plugins\Opoink\Liv\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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
			$mail = new PHPMailer(true);
			try {
				//Server settings
				$mail->SMTPDebug = config('oliv.phpmailer_smtpdebug');		//Enable verbose debug output
				$mail->isSMTP();											//Send using SMTP
				$mail->Host       = config('oliv.phpmailer_host');			//Set the SMTP server to send through
				$mail->SMTPAuth   = config('oliv.phpmailer_smtpauth');		//Enable SMTP authentication
				$mail->Username   = config('oliv.phpmailer_username');		//SMTP username
				$mail->Password   = config('oliv.phpmailer_password');		//SMTP password
				$mail->SMTPSecure = config('oliv.phpmailer_smtpsecure');	//Enable implicit TLS encryption
				$mail->Port       = config('oliv.phpmailer_port');			//TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

				//Recipients
				$mail->setFrom(config('oliv.phpmailer_username'));
				$mail->addAddress($user->email, $user->firstname);			//Add a recipient

				$code = rand(100000, 999999);
				ob_start();
					include(ROOT. '/plugins/Opoink/Liv/resources/views/email/forgot-password.phtml');
					$emailTemplate = ob_get_contents();
				ob_end_clean();
				

				//Content
				$mail->isHTML(true);										//Set email format to HTML
				$mail->Subject = 'Oliv Forgot Password Code';
				$mail->Body = $emailTemplate;

				$mail->send();

				$user->forgot_password_code = $code;
				$user->forgot_password_code_expire = date('Y-m-d H:i:s', strtotime('+2 hour'));
				$user->save();

				return response()->json([
					'message' => 'Please check your email for the code.'
				], 200);
			} catch (Exception $e) {
				return response()->json([
					'message' => "Message could not be sent. Mailer Error: " . $mail->ErrorInfo
				], 500);
			}
		} 
		else {
			return response()->json([
				'message' => 'Email not found.',
			], 406);
		}
	}

	public function resetPassword(Request $request){
		return inertiaRender('Opoink/Liv/resources/js/Pages/Admin/Users/ResetPassword', [
		]);
	}

	public function resetPasswordSave(Request $request){
		$this->validate($request, [
			'email' => 'required|email|exists:admins,email',
			'code' => 'required',
			'password' => 'required',
			'confirm_password' => 'required|same:password',
		]);

		$email = $request->input('email');
		$code = $request->input('code');
		$password = $request->input('password');

		$user = AdminUser::where('email', $email)
			->where('forgot_password_code', $code)
			->where('forgot_password_code_expire', '>=', date('Y-m-d H:i:s'))
			->first();

		if($user){
			$user->password = Hash::make($password);
			$user->forgot_password_code = null;
			$user->forgot_password_code_expire = null;
			$user->save();

			auth()->guard('admin')->attempt(['email' => $request->input('email'),  'password' => $request->input('password')]);

			return response()->json([
				'message' => 'Password has been reset.'
			], 200);
		} 
		else {
			return response()->json([
				'errors' => [
					'code' => ['Invalid code.'],
				],
			], 406);
		}
	}
}
?>