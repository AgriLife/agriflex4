<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/AgriLife/agriflex4/blob/master/src/class-agriflex.php
 * @since      0.1.0
 * @package    agriflex4
 * @subpackage agriflex4/src
 */

/**
 * The core plugin class
 *
 * @since 0.1.0
 * @return void
 */
class AgriFlex {

	/**
	 * File name
	 *
	 * @var file
	 */
	private static $file = __FILE__;

	/**
	 * Instance
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 * Initialize the class
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function __construct() {

		add_theme_support( 'html5', array() );

		add_image_size( 'archive', 400, 225, true );

		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );

		add_action( 'init', array( $this, 'init' ) );

		add_filter( 'wp_kses_allowed_html', array( $this, 'post_allowed_tags' ), 11, 2 );

		$this->register_templates();

		$this->require_classes();

		// Add parameters for Gutenberg's YouTube embed blocks.
		// https://wpforthewin.com/remove-related-videos-wp-gutenberg-embed-blocks/.
		add_filter( 'render_block', array( $this, 'add_youtube_player_url_params' ), 10, 3 );
		add_filter(
			'embed_oembed_html',
			function( $cache, $url, $attr, $post_ID ) {

				preg_match( '/\ssrc="([^"]+)"/', $cache, $old_url );

				if ( 2 <= count( $old_url ) ) {
					unset( $attr['width'] );
					unset( $attr['height'] );
					$old_url  = $old_url[1];
					$new_url  = $old_url . '&';
					$new_atts = array();
					foreach ( $attr as $att => $value ) {
						array_push( $new_atts, $att . '=' . $value );
					}
					$new_url .= implode( '&', $new_atts );
					$cache    = str_replace( $old_url, $new_url, $cache );
				}

				return $cache;

			},
			999,
			4
		);

		if ( function_exists( 'acf_add_options_page' ) ) {

			$settings = array(
				'page_title'  => __( 'AgriFlex4 Options' ),
				'menu_title'  => __( 'AgriFlex4 Options' ),
				'menu_slug'   => 'agriflex4-options',
				'capability'  => 'edit_themes',
				'position'    => '',
				'parent_slug' => 'options-general.php',
			);

			acf_add_options_page( $settings );

		}

		// Gutenberg LiveWhale block.
		add_action(
			'init',
			function() {

				wp_register_script(
					'gutenberg-af4-livewhale',
					AF_THEME_DIRURL . '/js/block-livewhale.js',
					array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
					filemtime( AF_THEME_DIRPATH . '/js/block-livewhale.js' ),
					true
				);

				/**
				 * Handle block content before adding to webpage.
				 *
				 * @since 1.8.3
				 * @param array  $attributes The block's attributes.
				 * @param string $content The block's content.
				 * @return string
				 */
				function cgb_api_block_posts( $attributes, $content ) {
					$url            = 'https://calendar.tamu.edu/live/json/events/group/College%20of%20Agriculture%20and%20Life%20Sciences/only_starred/true/hide_repeats/true/';
					$block_content  = str_replace( '<', '&lt;', $content );
					$block_content .= implode( ',', preg_replace( '/<|\n/', '', $attributes ) );
					// Return the frontend output for our block.
					return $block_content;
				}

				register_block_type(
					'agriflex4/livewhale-calendar',
					array(
						'editor_script'   => 'gutenberg-af4-livewhale',
						'render_callback' => 'cgb_api_block_posts',
						'attributes'      => array(
							'group' => array(
								'type' => 'string',
							),
						),
					)
				);

			}
		);

	}

	/**
	 * Add theme support for wide page alignment
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function after_setup_theme() {

		$defaults = array(
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title', 'site-description' ),
		);

		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'custom-logo', $defaults );

	}

	/**
	 * Initialize the various classes
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function init() {

		// Add page template custom fields.
		if ( class_exists( 'acf' ) ) {
			require_once AF_THEME_DIRPATH . '/fields/options-fields.php';
			require_once AF_THEME_DIRPATH . '/fields/service-landing-page-fields.php';
		}

	}

	/**
	 * Load classes so they are visible to plugins.
	 *
	 * @since 1.3.5
	 * @return void
	 */
	private function require_classes() {

		require_once AF_THEME_DIRPATH . '/src/class-assets.php';
		require_once AF_THEME_DIRPATH . '/src/class-genesis.php';
		require_once AF_THEME_DIRPATH . '/src/class-requireddom.php';
		require_once AF_THEME_DIRPATH . '/src/class-navigation.php';
		require_once AF_THEME_DIRPATH . '/src/class-people.php';

		// Get Genesis setup the way we want it.
		global $af_genesis;
		$af_genesis = new \AgriFlex\Genesis();

		// Enqueue our assets.
		$af_assets = new \AgriFlex\Assets();

		// Fix the navigation.
		$af_navigation = new \AgriFlex\Navigation();

		// Add AgriLife Required DOM Elements.
		global $af_required;
		$af_required = new \AgriFlex\RequiredDOM();

		// Make People plugin customizations.
		$af_people = new \AgriFlex\People();

	}

	/**
	 * Change allowed HTML tags for wp_kses_post()
	 *
	 * @since 1.5.13
	 * @param array  $allowedposttags Allowed HTML elements and attributes.
	 * @param string $context The filter context within the current instance.
	 * @return array
	 */
	public function post_allowed_tags( $allowedposttags, $context ) {

		if ( 'post' === $context ) {
			$allowedposttags['img']['srcset'] = true;
			$allowedposttags['img']['sizes']  = true;
		}

		return $allowedposttags;

	}

	/**
	 * Initialize page templates
	 *
	 * @since 0.7.0
	 * @return void
	 */
	private function register_templates() {

		require_once AF_THEME_DIRPATH . '/src/class-pagetemplate.php';
		$service = new \AgriFlex\PageTemplate( AF_THEME_TEMPLATE_PATH, 'service-landing-page.php', 'Service Landing Page' );
		$service->register();

	}

	/**
	 * Add URL parameters to the YouTube Embed block.
	 *
	 * @since 1.7.5
	 * @param string $block_content The string version of the block.
	 * @param array  $block The Gutenberg block object.
	 * @return string
	 */
	public function add_youtube_player_url_params( $block_content, $block ) {

		if ( 'core-embed/youtube' === $block['blockName'] ) {

			$block_content = str_replace( '?feature=oembed', '?feature=oembed&modestbranding=1&showinfo=0&rel=0', $block_content );

		}

		return $block_content;
	}

	/**
	 * Autoloads any classes called within the theme
	 *
	 * @since 0.1.0
	 * @param string $classname The name of the class.
	 * @return void
	 */
	public static function autoload( $classname ) {

		$filename = dirname( __FILE__ ) .
			DIRECTORY_SEPARATOR .
			str_replace( '_', DIRECTORY_SEPARATOR, $classname ) .
			'.php';
		if ( file_exists( $filename ) ) {
			require $filename;
		}

	}

	/**
	 * Return instance of class
	 *
	 * @since 0.1.0
	 * @return object.
	 */
	public static function get_instance() {

		return null === self::$instance ? new self() : self::$instance;

	}

}
