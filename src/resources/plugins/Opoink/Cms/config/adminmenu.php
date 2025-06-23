<?php
return [
	'cms' => [
		'route' => null,
		'label' => 'CMS',
		'fa_icon' => 'fa-solid fa-file-code',
		'is_active_menu_url' => '/cms',
		'is_active_menu_name' => 'cms',
		'role_resource' => 'cms',
		'child' => [
			'col_0' => [
				'content' => [
					'title' => 'Content',
					'links' => [
						[
							'route' => '/cms/block',
							'label' => 'Blocks',
							'role_resource' => 'cms_block'
						],
						[
							'route' => '/cms/pages',
							'label' => 'Pages',
							'role_resource' => 'cms_pages'
						]
					],
				]
			]
		]
	]
];
?>