<?php
/**
 * Level 1: The main tab on the left side.
 * Level 2: A section inside that main tab.
 * Level 3: A group of items inside the section.
 * Level 4 and above:
 *     - May represent another nested group (multi-level grouping structure).
 *     - Or may directly represent a form field definition (e.g., input, select, checkbox).
 * 
 * In other words, levels beyond 3 can contain either sub-groups or actual form fields,
 * allowing the configuration to support deeply nested and flexible form layouts.
 */

return [
	[
		"name" => "opoink_liv__general", // The <vendor>_<plugin>__<the_name_here>
		"children" => [
			[
				"name" => "website",
				"children" => [
					[
						"name" => "email",
						"label" => "Email",
						"sort_order" => 20,
						"html_attributes" => [],
						"type" => "group",
						"children" => [	
							[
								"name" => "host",
								"label" => "Host",
								"sort_order" => 10,
								"html_attributes" => [],
								"type" => "field",
								"field" => [
									"type" => "text"
								],
								"value" => "smtp.example.com"
							],
							[
								"name" => "smtpauth",
								"label" => "SMTP Auth",
								"sort_order" => 20,
								"html_attributes" => [],
								"type" => "field",
								"field" => [
									"type" => "select",
									"options" => app(\Plugins\Opoink\Liv\Lib\Option\YesNo::class)->toOptionArray()
								],
								"value" => \Plugins\Opoink\Liv\Lib\Option\YesNo::YES
							],
							[
								"name" => "username",
								"label" => "Username",
								"sort_order" => 30,
								"html_attributes" => [],
								"type" => "field",
								"field" => [
									"type" => "text"
								],
								"value" => "user@example.com"
							],
							[
								"name" => "password",
								"label" => "Password",
								"sort_order" => 40,
								"html_attributes" => [],
								"type" => "field",
								"field" => [
									"type" => "password"
								],
								"value" => ""
							],
							[
								"name" => "smtpsecure",
								"label" => "SMTP Secure",
								"sort_order" => 50,
								"html_attributes" => [],
								"type" => "field",
								"field" => [
									"type" => "text"
								],
								"value" => "ssl"
							],
							[
								"name" => "port",
								"label" => "Port",
								"sort_order" => 60,
								"html_attributes" => [],
								"type" => "field",
								"field" => [
									"type" => "text"
								],
								"value" => "465"
							],
							[
								"name" => "smtpdebug",
								"label" => "SMTP Debug",
								"sort_order" => 70,
								"html_attributes" => [],
								"type" => "field",
								"field" => [
									"type" => "select",
									"options" => app(\Plugins\Opoink\Email\Lib\Option\SmtpOption::class)->toOptionArray()
								],
								"value" => \PHPMailer\PHPMailer\SMTP::DEBUG_OFF
							],
							[
								"name" => "variablevalues",
								"label" => "Variable Values",
								"sort_order" => 80,
								"html_attributes" => [],
								"type" => "group",
								"children" => [
									[
										"name" => "logo",
										"label" => "Logo",
										"sort_order" => 10,
										"html_attributes" => [
											"placeholder" => "https://domain.com/your/logo.jpg"
										],
										"type" => "field",
										"field" => [
											"type" => "text"
										],
										"value" => "",
										"comment" => "Provide the URL of the image that will appear as your company logo in outgoing emails."
									],
									[
										"name" => "companyname",
										"label" => "Company Name",
										"sort_order" => 20,
										"html_attributes" => [],
										"type" => "field",
										"field" => [
											"type" => "text"
										],
										"value" => "",
									],
									[
										"name" => "tagline",
										"label" => "Tagline",
										"sort_order" => 30,
										"html_attributes" => [],
										"type" => "field",
										"field" => [
											"type" => "text"
										],
										"value" => "",
									],
									[
										"name" => "companyaddress",
										"label" => "Company Address",
										"sort_order" => 40,
										"html_attributes" => [],
										"type" => "field",
										"field" => [
											"type" => "text"
										],
										"value" => "",
									],
									[
										"name" => "companyphone",
										"label" => "Company Phone",
										"sort_order" => 50,
										"html_attributes" => [],
										"type" => "field",
										"field" => [
											"type" => "text"
										],
										"value" => "",
									],
									[
										"name" => "companyemail",
										"label" => "Company Email",
										"sort_order" => 60,
										"html_attributes" => [],
										"type" => "field",
										"field" => [
											"type" => "text"
										],
										"value" => "",
									],
									[
										"name" => "facebook",
										"label" => "Facebook",
										"sort_order" => 70,
										"html_attributes" => [],
										"type" => "field",
										"field" => [
											"type" => "text"
										],
										"value" => "",
									],
									[
										"name" => "twitter",
										"label" => "Twitter",
										"sort_order" => 70,
										"html_attributes" => [],
										"type" => "field",
										"field" => [
											"type" => "text"
										],
										"value" => "",
									],
									[
										"name" => "website",
										"label" => "Website",
										"sort_order" => 80,
										"html_attributes" => [],
										"type" => "field",
										"field" => [
											"type" => "text"
										],
										"value" => "",
									],
								]
							]
						]
					]
				]
			]
		]
	]
]

?>