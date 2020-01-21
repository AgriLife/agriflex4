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

	}

	/**
	 * Add theme support for wide page alignment
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function after_setup_theme() {

		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );

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
