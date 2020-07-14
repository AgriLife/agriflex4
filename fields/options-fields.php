<?php
/**
 * The file that defines the Theme Options custom fields
 *
 * @link       https://github.com/AgriLife/agriflex4/blob/master/fields/options-fields.php
 * @since      0.7.0
 * @package    agriflex4
 * @subpackage agriflex4/fields
 */

if ( function_exists( 'acf_add_local_field_group' ) ) {

	acf_add_local_field_group(
		array(
			'key'                   => 'group_5e14d2d88b326',
			'title'                 => 'AgriFlex4 Options',
			'fields'                => array(),
			'location'              => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'agriflex4-options',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => '',
		)
	);

}

if ( function_exists( 'acf_add_local_field' ) ) {

	acf_add_local_field(
		array(
			'key'               => 'field_5e14d2dc822a8',
			'label'             => 'Logos',
			'name'              => 'logos',
			'type'              => 'repeater',
			'instructions'      => 'This is not used on the following sites: aglifesciences.tamu.edu, agrilife.tamu.edu',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
			'collapsed'         => '',
			'min'               => 0,
			'max'               => 2,
			'layout'            => 'block',
			'button_label'      => 'Add Logo',
			'sub_fields'        => array(
				array(
					'key'               => 'field_5e14d324822aa',
					'label'             => 'Image',
					'name'              => 'image',
					'type'              => 'image',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'return_format'     => 'array',
					'preview_size'      => 'medium',
					'library'           => 'all',
					'min_width'         => '',
					'min_height'        => '',
					'min_size'          => '',
					'max_width'         => '',
					'max_height'        => '',
					'max_size'          => '',
					'mime_types'        => 'png,svg,jpg',
				),
				array(
					'key'               => 'field_5e14d36c822ab',
					'label'             => 'Screen Size',
					'name'              => 'screen_size',
					'type'              => 'select',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'choices'           => array(
						'small'      => 'All',
						'small-only' => 'Small only',
						'medium'     => 'Medium and up',
					),
					'default_value'     => array(
						0 => 'small',
					),
					'allow_null'        => 1,
					'multiple'          => 0,
					'ui'                => 0,
					'return_format'     => 'value',
					'ajax'              => 0,
					'placeholder'       => '',
				),
			),
			'parent'            => 'group_5e14d2d88b326',
		)
	);

	acf_add_local_field(
		array(
			'key'               => 'field_5f0dfcb50958b',
			'label'             => 'Google Search Engine ID',
			'name'              => 'google_search_engine_id',
			'type'              => 'text',
			'instructions'      => '',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
			'default_value'     => '',
			'placeholder'       => '',
			'prepend'           => '',
			'append'            => '',
			'maxlength'         => 75,
			'parent'            => 'group_5e14d2d88b326',
		)
	);

}
