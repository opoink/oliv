<?php
return [
	[
		'name' => 'dashboard',
		'route' => '/',
		'label' => 'Dashboard',
		'fa_icon' => 'fa-solid fa-house',
		'role_resource' => 'oliv_dashboard',
		'children' => []
	],
	[
		'name' => 'users',
		'route' => null,
		'label' => 'Users',
		'fa_icon' => 'fa-solid fa-users',
		'is_active_menu_url' => '/users',
		'is_active_menu_name' => 'users',
		'role_resource' => 'oliv_users',
		'children' => [
			[
				[
					'name' => 'users.users',
					'title' => 'Users',
					'links' => [
						[
							'name' => 'users.users.admins',
							'route' => '/users/admins',
							'label' => 'Admin Users',
							'role_resource' => 'view_admin_users'
						],
						[
							'name' => 'users.users.users',
							'route' => '/users/admins',
							'label' => 'Client Users',
							'role_resource' => 'view_client_users'
						]
					],
				],
				[
					'name' => 'users.roles',
					'title' => 'Roles',
					'links' => [
						[
							'name' => 'users.admins.roles',
							'route' => '/users/admins/roles',
							'label' => 'Admin Roles',
							'role_resource' => 'oliv_roles'
						]
					],
				]
			]
		]
	],
	[
		'name' => 'settings',
		'route' => '/settings',
		'label' => 'Settings',
		'fa_icon' => 'fa-solid fa-gears',
		'role_resource' => 'oliv_settings',
		'children' => []
	],
];
?>