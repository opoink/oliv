<?php
return [
	'cms' => [
		'route' => null,
		'label' => 'CMS',
		'fa_icon' => 'fa-solid fa-file-code',
		'is_active_menu_url' => '/cms',
		'is_active_menu_name' => 'cms',
		'child' => [
			'col_0' => [
				'content' => [
					'title' => 'Content',
					'links' => [
						[
							'route' => 'admin.cms.block.index',
							'label' => 'Blocks'
						],
						[
							'route' => 'admin.cms.pages.index',
							'label' => 'Pages'
						]
					],
				]
			]
		]
	]
];
?>