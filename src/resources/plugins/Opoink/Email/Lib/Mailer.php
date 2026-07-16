<?php
namespace Plugins\Opoink\Email\Lib;

use Plugins\Opoink\Email\Models\Emails AS EmailsModel;
use Illuminate\Support\Facades\Blade;
use Plugins\Opoink\Liv\Lib\Facades\SystemConfig;

use Plugins\Opoink\Email\Lib\Option\EmailQueueOption;
use Plugins\Opoink\Email\Models\EmailQueue AS EmailQueueModel;

use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;

class Mailer {

	public function render(string $__php, array $__data)
	{
		$obLevel = ob_get_level();
		ob_start();
		extract($__data, EXTR_SKIP);
		try {
			eval('?' . '>' . $__php);
		} catch (\Exception $e) {
			while (ob_get_level() > $obLevel) ob_end_clean();
			throw $e;
		}
		return ob_get_clean();
	}

	public function renderBlade(string $template, array $params){
		$phpStr = Blade::compileString($template);
		return $this->render($phpStr, $params);
	}

	/**
	 * @return \Plugins\Opoink\Email\Models\Emails|null
	 */
	public function getTemplate(string $name){
		$content = EmailsModel::getTemplate('ho_pending_status');
		return $content;
	}

	public function getDefaultTemplate(){
		$defaultTemplate = EmailsModel::getDefaultTemplate();
		
		$baseContent = "";
		if(!$defaultTemplate){
			$baseContent = file_get_contents(base_path("plugins/Opoink/Email/resources/views/default_template.blade.php"));
		}
		else {
			$baseContent = $defaultTemplate->content;
		}

		$phpStr = Blade::compileString($baseContent);
		$body = $this->render($phpStr, [
			"logo" => SystemConfig::getValue("opoink_liv__general/website/email/variablevalues/logo"),
			"companyname" => SystemConfig::getValue("opoink_liv__general/website/email/variablevalues/companyname"),
			"tagline" => SystemConfig::getValue("opoink_liv__general/website/email/variablevalues/tagline"),
			"companyaddress" => SystemConfig::getValue("opoink_liv__general/website/email/variablevalues/companyaddress"),
			"companyphone" => SystemConfig::getValue("opoink_liv__general/website/email/variablevalues/companyphone"),
			"companyemail" => SystemConfig::getValue("opoink_liv__general/website/email/variablevalues/companyemail"),
			"facebook" => SystemConfig::getValue("opoink_liv__general/website/email/variablevalues/facebook"),
			"twitter" => SystemConfig::getValue("opoink_liv__general/website/email/variablevalues/twitter"),
			"website" => SystemConfig::getValue("opoink_liv__general/website/email/variablevalues/website"),
			"subject" => "{{ \$subject }}",
			"content" => "{!! \$content !!}",
		]);

		return $body;
	}

	/**
	 * @param int $limit
	 * @param (\Closure(\Illuminate\Database\Eloquent\Builder): void)|null $beforeFetchQueue
	 */
	public function sendPending(int $limit = 30, ?\Closure $beforeFetchQueue = null){
		$qry = EmailQueueModel::where('status', EmailQueueOption::PENDING);
		$qry->where('scheduled_at', '<=', now());
		$qry->limit($limit);

		if($beforeFetchQueue instanceof \Closure){
			$beforeFetchQueue($qry);
		}

		$emails = $qry->get();
		foreach ($emails as $key => $email) {
			try {
				$this->send(
					[
						[
							"email" => $email->recipient,
							"name" => ""
						]
					], 
					$email->subject, 
					$email->body
				);

				$email->status = EmailQueueOption::SENT;
				$email->attempts = $email->attempts++;
				$email->sent_at = date("Y-m-d H:i:s");
				$email->save();
				
			} catch (\Throwable $th) {
				$email->status = EmailQueueOption::FAILED;
				$email->attempts = $email->attempts++;
				$email->fail_message = $th->getMessage();
				$email->save();
			}
		}
	}

	/**
	 * @param array $recipients [ [email => "juandelacruz@domain.com", "name" => "Juan Dela Cruz"] ]
	 */
	public function send(array $recipients, string $subject, string $body, ?\Closure $beforeSend = null){
		$mail = new PHPMailer(true);
		try {
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);

			$configPath = "opoink_liv__general/website/email/";

			//Server settings
			$mail->SMTPDebug = SystemConfig::getValue($configPath."smtpdebug");
			$mail->isSMTP();
			$mail->Host       = SystemConfig::getValue($configPath."host");
			$mail->SMTPAuth   = SystemConfig::getValue($configPath."smtpauth") == \Plugins\Opoink\Liv\Lib\Option\YesNo::YES ? true : false;
			$mail->Username   = SystemConfig::getValue($configPath."username");
			$mail->Password   = SystemConfig::getValue($configPath."password");
			$mail->SMTPSecure = SystemConfig::getValue($configPath."smtpsecure");
			$mail->Port       = (int)SystemConfig::getValue($configPath."port");

			$mail->setFrom(SystemConfig::getValue($configPath."username"), '');

			//Recipients
			foreach($recipients as $recipient) {
				$mail->addAddress($recipient['email'], $recipient['name']);
			}

			//Attachments
			// $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
			// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

			if($beforeSend instanceof \Closure){
				$beforeSend($mail);
			}

			//Content
			$mail->isHTML(true);
			$mail->Subject = $subject;;
			$mail->Body    = $body;
			// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			// 🟢 Set Priority
			$mail->Priority = 1; // 1 = High, 3 = Normal, 5 = Low
			$mail->addCustomHeader('X-MSMail-Priority', 'High');
			$mail->addCustomHeader('Importance', 'High');

			$mail->send();
		} catch (\Exception $e) {
			throw new \Exception("Message could not be sent. Mailer Error: {$mail->ErrorInfo}", 500);
		}
	}
}
?>