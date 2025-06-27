<?php
return [
	[
		'name' => 'cms',
		'route' => null,
		'label' => 'CMS',
		'fa_icon' => 'fa-solid fa-file-code',
		'is_active_menu_url' => '/cms',
		'is_active_menu_name' => 'cms',
		'role_resource' => 'cms',
		'children' => [
			[
				[
					'name' => 'cms.content',
					'title' => 'Content',
					'links' => [
						[
							'name' => 'cms.content.block',
							'route' => '/cms/block',
							'label' => 'Blocks',
							'role_resource' => 'cms_block'
						],
						[
							'name' => 'cms.content.pages',
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