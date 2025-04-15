<?php
return [
	'auth_admin_user' => env('OLIV_AUTH_ADMIN_USER_MODEL') ? env('OLIV_AUTH_ADMIN_USER_MODEL') : "Plugins_Opoink_Liv_Models_AdminUser",
	'vite_admin_app_name' => env('VITE_ADMIN_APP_NAME', 'Oliv Admin'),
	'vite_admin_url' => env('VITE_ADMIN_URL'),
	'vite_oliv_welcome_page' => env('VITE_OLIV_WELCOME_PAGE', true),
	'phpmailer_smtpdebug' => env('PHPMAILER_SMTPDEBUG', 0),
	'phpmailer_host' => env('PHPMAILER_HOST', 'smtp.example.com'),
	'phpmailer_smtpauth' => env('PHPMAILER_SMTPAUTH', true),
	'phpmailer_username' => env('PHPMAILER_USERNAME', ''),
	'phpmailer_password' => env('PHPMAILER_PASSWORD', ''),
	'phpmailer_smtpsecure' => env('PHPMAILER_SMTPSECURE', 'ssl'),
	'phpmailer_port' => env('PHPMAILER_PORT', 465),
];
?>