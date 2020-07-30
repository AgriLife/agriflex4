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

		add_filter( 'wp_kses_allowed_html', array( $this, 'post_allowed_tags' ), 11, 2 );

		$this->require_classes();

		// Add parameters for Gutenberg's YouTube embed blocks.
		// https://wpforthewin.com/remove-related-videos-wp-gutenberg-embed-blocks/.
		add_filter( 'render_block', array( $this, 'add_youtube_player_url_params' ), 10, 3 );
		add_filter(
			'wp_kses_allowed_html',
			function ( $allowed, $context ) {

				if ( 'post' === $context ) {
					if ( ! array_key_exists( 'iframe', $allowed ) ) {
						$allowed['iframe'] = array();
					}

					$allowed['iframe']['data-*']          = true;
					$allowed['iframe']['src']             = array();
					$allowed['iframe']['href']            = array();
					$allowed['iframe']['title']           = array();
					$allowed['iframe']['width']           = array();
					$allowed['iframe']['height']          = array();
					$allowed['iframe']['allow']           = array();
					$allowed['iframe']['allowfullscreen'] = array();
					$allowed['iframe']['frameborder']     = array();
				}

				return $allowed;

			},
			10,
			2
		);

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

					if ( array_key_exists( 'autoplay', $attr ) && '1' === $attr['autoplay'] ) {
						$cache = preg_replace( '/\s(src="[^"]+")/', ' data-agf-autoplay="1" data-agf-$1 src=""', $cache );
					}
				}

				return $cache;

			},
			999,
			4
		);

		// Speed up rss feed cache refresh.
		add_filter( 'wp_feed_cache_transient_lifetime', array( $this, 'rss_widget_refresh_interval' ) );

		// Make Feedzy use the smaller of the first two enclosure images in an RSS feed item.
		add_filter( 'feedzy_retrieve_image', array( $this, 'feedzy_retrieve_image' ), 11, 2 );

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
	 * Load classes so they are visible to plugins.
	 *
	 * @since 1.3.5
	 * @return void
	 */
	private function require_classes() {

		require_once AF_THEME_DIRPATH . '/src/class-assets.php';
		require_once AF_THEME_DIRPATH . '/src/class-custom-fields.php';
		require_once AF_THEME_DIRPATH . '/src/class-genesis.php';
		require_once AF_THEME_DIRPATH . '/src/class-requireddom.php';
		require_once AF_THEME_DIRPATH . '/src/class-navigation.php';
		require_once AF_THEME_DIRPATH . '/src/class-people.php';

		// Get Genesis setup the way we want it.
		global $af_genesis;
		$af_genesis = new \AgriFlex\Genesis();

		// Get Genesis setup the way we want it.
		global $af_customfields;
		$af_customfields = new \AgriFlex\Custom_Fields();

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
	 * Speed up widget refresh interval.
	 *
	 * @since 1.11.5
	 * @param int $seconds The current refresh rate in seconds.
	 * @return int
	 */
	public function rss_widget_refresh_interval( $seconds ) {

		return 600;

	}

	/**
	 * Retrive image from the item object
	 *
	 * @since   1.11.6
	 *
	 * @param   string $the_thumbnail The thumbnail url.
	 * @param   object $item The item object.
	 *
	 * @return  string
	 */
	public function feedzy_retrieve_image( $the_thumbnail, $item ) {

		$data = $item->data;

		if ( array_key_exists( 'child', $data ) ) {

			$child = array_values( $data['child'] )[0];

			if ( count( $child['enclosure'] ) > 1 ) {

				$enclosure_a = array_values( $child['enclosure'][0]['attribs'] )[0];
				$enclosure_b = array_values( $child['enclosure'][1]['attribs'] )[0];
				$length_a    = $enclosure_a['length'];
				$length_b    = $enclosure_b['length'];

				if ( $length_b < $length_a ) {

					$the_thumbnail = $enclosure_b['url'];

				}
			}
		}

		return $the_thumbnail;

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
