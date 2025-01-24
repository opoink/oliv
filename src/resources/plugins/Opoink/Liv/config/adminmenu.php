<?php
return [
	'dashboard' => [
		'route' => 'admin.index',
		'label' => 'Dashboard',
		'fa_icon' => 'fa-solid fa-house',
		'child' => []
	],
	'users' => [
		'route' => null,
		'label' => 'Users',
		'fa_icon' => 'fa-solid fa-users',
		'is_active_menu_url' => '/users',
		'is_active_menu_name' => 'users',
		'child' => [
			'col_0' => [
				'users' => [
					'title' => 'Users',
					'links' => [
						[
							'route' => 'admin.users.admins.index',
							'label' => 'Admin Users'
						],
						[
							'route' => 'admin.users.admins.index',
							'label' => 'Client Users'
						]
					],
				],
				'roles' => [
					'title' => 'Roles',
					'links' => [
						[
							'route' => 'admin.users.admins.roles.index',
							'label' => 'Admin Roles'
						]
					],
				]
			]
		]
	],
	'settings' => [
		'route' => 'admin.settings.index',
		'label' => 'Settings',
		'fa_icon' => 'fa-solid fa-gears',
		'child' => []
	],
];
?>