<?php
return [
	'dashboard' => [
		'route' => '/',
		'label' => 'Dashboard',
		'fa_icon' => 'fa-solid fa-house',
		'role_resource' => 'oliv_dashboard',
		'child' => []
	],
	'users' => [
		'route' => null,
		'label' => 'Users',
		'fa_icon' => 'fa-solid fa-users',
		'is_active_menu_url' => '/users',
		'is_active_menu_name' => 'users',
		'role_resource' => 'oliv_users',
		'child' => [
			'col_0' => [
				'users' => [
					'title' => 'Users',
					'links' => [
						[
							'route' => '/users/admins',
							'label' => 'Admin Users',
							'role_resource' => 'view_admin_users'
						],
						[
							'route' => '/admins',
							'label' => 'Client Users',
							'role_resource' => 'view_client_users'
						]
					],
				],
				'roles' => [
					'title' => 'Roles',
					'links' => [
						[
							'route' => '/users/admins/roles',
							'label' => 'Admin Roles',
							'role_resource' => 'oliv_roles'
						]
					],
				]
			]
		]
	],
	'settings' => [
		'route' => '/settings',
		'label' => 'Settings',
		'fa_icon' => 'fa-solid fa-gears',
		'role_resource' => 'oliv_settings',
		'child' => []
	],
];
?>