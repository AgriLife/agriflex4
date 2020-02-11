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

					// Decide how many calendar items to display.
					$count = 3;
					if ( array_key_exists( 'count', $attributes ) && ! empty( $attributes['count'] ) ) {
						$count = $attributes['count'];
					}

					// Build the LiveWhale Feed URL.
					$furl = 'https://calendar.tamu.edu/live/json/events';
					if ( array_key_exists( 'group', $attributes ) && ! empty( $attributes['group'] ) ) {
						$furl .= '/group/' . $attributes['group'];
					}
					if ( array_key_exists( 'category', $attributes ) && ! empty( $attributes['category'] ) ) {
						$furl .= '/category/' . $attributes['category'];
					}
					if ( array_key_exists( 'tag', $attributes ) && ! empty( $attributes['tag'] ) ) {
						$furl .= '/tag/' . $attributes['tag'];
					}
					if ( array_key_exists( 'starred', $attributes ) && true === $attributes['starred'] ) {
						$furl .= '/only_starred/true';
					}
					$furl .= '/hide_repeats/';

					$output       = '';
					$feed_json    = wp_remote_get( $furl );
					$feed_array   = json_decode( $feed_json['body'], true );
					$l_events     = array_slice( $feed_array, 0, $count ); // Choose number of events.
					$l_event_list = '';

					foreach ( $l_events as $event ) {

						$title      = $event['title'];
						$url        = $event['url'];
						$location   = $event['location'];
						$date       = $event['date_utc'];
						$time       = $event['date_time'];
						$date       = date_create( $date );
						$date_day   = date_format( $date, 'd' );
						$date_month = date_format( $date, 'M' );

						if ( array_key_exists( 'custom_room_number', $event ) && ! empty( $event['custom_room_number'] ) ) {

							$location .= ' ' . $event['custom_room_number'];

						}

						$l_event_list .= sprintf(
							'<div class="event cell medium-auto small-12"><div class="grid-x grid-padding-x"><div class="cell date shrink"><div class="month h3">%s</div><div class="h2 day">%s</div></div><div class="cell title auto"><a href="%s" title="%s" class="event-title">%s</a><div class="location">%s</div></div></div></div>',
							$date_month,
							$date_day,
							$url,
							$title,
							$title,
							$location
						);

					}

					$output .= sprintf(
						'<div class="alignfull livewhale invert"><div class="grid-container"><div class="grid-x grid-padding-x padding-y"><div class="events-cell cell medium-auto small-12 grid-container"><div class="grid-x grid-padding-x">%s</div></div><div class="events-all cell medium-shrink small-12"><a class="h3 arrow-right" href="%s">All Events</a></div></div></div></div>',
						$l_event_list,
						str_replace( '/live/json', '', $furl )
					);

					return $output;
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
