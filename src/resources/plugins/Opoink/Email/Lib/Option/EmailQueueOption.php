<?php
namespace Plugins\Opoink\Email\Lib\Option;

use PHPMailer\PHPMailer\SMTP;

class EmailQueueOption {
	
	const PENDING = "Pending";
	const SENDING = "Sending";
	const SENT = "Sent";
	const FAILED = "Failed";

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
			self::PENDING => self::PENDING,
			self::SENDING => self::SENDING,
			self::SENT => self::SENT,
			self::FAILED => self::FAILED
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