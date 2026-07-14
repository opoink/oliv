<?php
namespace Plugins\Opoink\Email\Lib\Option;

use PHPMailer\PHPMailer\SMTP;

class SmtpOption {
	
	// const DEBUG_OFF = "No output";
	// const DEBUG_CLIENT = "Client messages";
	// const DEBUG_SERVER = "Client and server messages";
	// const DEBUG_CONNECTION = "As SERVER plus connection status";
	// const DEBUG_LOWLEVEL = "Noisy, low-level data output, rarely needed";

	public function toOptionArray(){
		$options = self::getOptions();

		$opts = [];
		foreach ($options as $key => $value) {
			$opts[] = ["label" => ucwords(str_replace("_", " ", $value)), "value" => $key];
		}
		return $opts;
	}

	public static function getOptions(){
		return [
			SMTP::DEBUG_OFF => "No output",
			SMTP::DEBUG_CLIENT => "Client messages",
			SMTP::DEBUG_SERVER => "Client and server messages",
			SMTP::DEBUG_CONNECTION => "As SERVER plus connection status",
			SMTP::DEBUG_LOWLEVEL => "Noisy, low-level data output, rarely needed"
		];
	}

	public static function getLabel(string $key){
		$options = self::getOptions();

		if(isset($options[$key])){
			return ucwords(str_replace("_", " ", $options[$key]));
		}
		else {
			throw new \Exception("Unknown status", 500);
		}
	}
}
?>