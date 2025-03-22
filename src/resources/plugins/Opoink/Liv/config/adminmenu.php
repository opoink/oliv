<?php
return [
	'dashboard' => [
		'route' => 'admin.index',
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
							'route' => 'admin.users.admins.index',
							'label' => 'Admin Users',
							'role_resource' => 'view_admin_users'
						],
						[
							'route' => 'admin.users.admins.index',
							'label' => 'Client Users',
							'role_resource' => 'view_client_users'
						]
					],
				],
				'roles' => [
					'title' => 'Roles',
					'links' => [
						[
							'route' => 'admin.users.admins.roles.index',
							'label' => 'Admin Roles',
							'role_resource' => 'oliv_roles'
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
		'role_resource' => 'oliv_settings',
		'child' => []
	],
];
?>