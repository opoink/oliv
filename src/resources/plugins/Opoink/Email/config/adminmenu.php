<?php
return [
	[
		'name' => 'settings',
		'children' => [
			[
				[
					'name' => 'settings.settings',
					'title' => 'Email',
					'links' => [
						[
							'name' => 'settings.email.list',
							'route' => '/emails',
							'label' => 'Emails',
							'role_resource' => 'oliv_email_list'
						]
					],
				]
			]
		]
	]
];
?>