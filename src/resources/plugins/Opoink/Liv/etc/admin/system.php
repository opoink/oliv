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
		"label" => "General",
		"sort_order" => 10,
		"html_attributes" => [],
		"type" => "tab",
		"children" => [
			[
				"name" => "website",
				"label" => "Website",
				"sort_order" => 10,
				"html_attributes" => [],
				"type" => "section",
				"children" => [
					[
						"name" => "general",
						"label" => "General",
						"sort_order" => 10,
						"html_attributes" => [],
						"type" => "group",
						"children" => [	
							[
								"name" => "site_name",
								"label" => "Site Name",
								"sort_order" => 10,
								"html_attributes" => [],
								"type" => "field",
								"field" => [
									"type" => "text"
								],
								"value" => "Opoink"
							]
						]
					]
				]
			]
		]
	],
	[
		"name" => "opoink_liv__sample", // The <vendor>_<plugin>__<the_name_here>
		"label" => "Sample Tab",
		"sort_order" => 10,
		"html_attributes" => [
			"data-sample_attr" => "sample_attr tab"
		],
		"type" => "tab",
		"children" => [
			[
				"name" => "sample_section",
				"label" => "Sample Section",
				"sort_order" => 10,
				"html_attributes" => [
					"data-sample_attr" => "sample_attr section"
				],
				"type" => "section",
				"children" => [
					[
						"name" => "sample_group",
						"label" => "Sample Group",
						"sort_order" => 10,
						"html_attributes" => [
							"data-sample_attr" => "sample_attr group"
						],
						"type" => "group",
						"comment" => "Your group sample comment will be placed <a href='https://opoink.com' target='_blank'>here</a>.",
						"children" => [
							[
								"name" => "sample_field_text",
								"label" => "Sample Field: Text",
								"sort_order" => 10,
								"html_attributes" => ["data-sample_attr" => "sample_attr field"],
								"type" => "field",
								"field" => ["type" => "text"],
								"value" => "Sample Field Text",
								"comment" => "Your field sample comment will be placed <a href='https://opoink.com' target='_blank'>here</a>.",
							],
							[
								"name" => "sample_field_select",
								"label" => "Sample Field: Select",
								"sort_order" => 20,
								"type" => "field",
								"field" => [
									"type" => "select",
									"options" => app(\Plugins\Opoink\Liv\Lib\Option\YesNo::class)->toOptionArray()
								],
								"value" => "yes"
							],
							[
								"name" => "sample_field_multiselect",
								"label" => "Sample Field: Multiselect",
								"sort_order" => 20,
								"type" => "field",
								"field" => [
									"type" => "multiselect",
									"options" => app(\Plugins\Opoink\Liv\Lib\Option\YesNo::class)->toOptionArray()
								],
								"value" => "yes,no"
							],
							[
								"name" => "sample_field_email",
								"label" => "Sample Field: email",
								"sort_order" => 30,
								"html_attributes" => ["data-sample_attr" => "sample_attr field"],
								"type" => "field",
								"field" => ["type" => "email"],
								"value" => "sample@domain.com",
								"comment" => "Your field sample comment will be placed <a href='https://opoink.com' target='_blank'>here</a>.",
							],
							[
								"name" => "sample_field_password",
								"label" => "Sample Field: password",
								"sort_order" => 40,
								"html_attributes" => ["data-sample_attr" => "sample_attr field"],
								"type" => "field",
								"field" => ["type" => "password"],
								"value" => "1234",
								"comment" => "Your field sample comment will be placed <a href='https://opoink.com' target='_blank'>here</a>.",
							],
							// file is currently not supported
							// [
							// 	"name" => "sample_field_file",
							// 	"label" => "Sample Field: file",
							// 	"sort_order" => 50,
							// 	"html_attributes" => ["data-sample_attr" => "sample_attr field"],
							// 	"type" => "field",
							// 	"field" => ["type" => "file"],
							// 	"value" => "/path/to/your/file", // File value will be the relative of the file
							// 	"comment" => "Your field sample comment will be placed <a href='https://opoink.com' target='_blank'>here</a>.",
							// ],
							[
								"name" => "sample_field_textarea",
								"label" => "Sample Field: textarea",
								"sort_order" => 60,
								"html_attributes" => ["data-sample_attr" => "sample_attr textarea"],
								"type" => "field",
								"field" => ["type" => "textarea"],
								"value" => "Hello World", // File value will be the relative of the file
								"comment" => "Your field sample comment will be placed <a href='https://opoink.com' target='_blank'>here</a>.",
							],
							[
								"name" => "sample_group_sub_1",
								"label" => "Sample Group Sub 1",
								"sort_order" => 100,
								"type" => "group",
								"comment" => "Your group sample comment will be placed <a href='https://opoink.com' target='_blank'>here</a>.",
								"children" => [
									[
										"name" => "sample_field_text",
										"label" => "Sample Field: Text",
										"sort_order" => 10,
										"html_attributes" => ["data-sample_attr" => "sample_attr field"],
										"type" => "field",
										"field" => ["type" => "text"],
										"value" => "Sample Field Text",
										"comment" => "Your field sample comment will be placed <a href='https://opoink.com' target='_blank'>here</a>.",
									],
									[
										"name" => "sample_group_sub_2",
										"label" => "Sample Group Sub 2",
										"sort_order" => 100,
										"type" => "group",
										"comment" => "Your group sample comment will be placed <a href='https://opoink.com' target='_blank'>here</a>.",
										"children" => [
											[
												"name" => "sample_field_text",
												"label" => "Sample Field: Text",
												"sort_order" => 10,
												"html_attributes" => ["data-sample_attr" => "sample_attr field"],
												"type" => "field",
												"field" => ["type" => "text"],
												"value" => "Sample Field Text",
												"comment" => "Your field sample comment will be placed <a href='https://opoink.com' target='_blank'>here</a>.",
											]
										]
									]
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