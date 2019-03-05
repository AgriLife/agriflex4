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

		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );

		add_action( 'init', array( $this, 'init' ) );

	}

	/**
	 * Add theme support for wide page alignment
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function after_setup_theme() {

		add_theme_support( 'align-wide' );

	}

	/**
	 * Initialize the various classes
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function init() {

		// Get Genesis setup the way we want it.
		require_once AF_THEME_DIRPATH . '/src/class-genesis.php';
		$af_genesis = new \AgriFlex\Genesis();

		// Enqueue our assets.
		require_once AF_THEME_DIRPATH . '/src/class-assets.php';
		$af_assets = new \AgriFlex\Assets();

		// Fix the navigation.
		require_once AF_THEME_DIRPATH . '/src/class-navigation.php';
		$af_navigation = new \AgriFlex\Navigation();

		// Add AgriLife Required DOM Elements.
		require_once AF_THEME_DIRPATH . '/src/class-requireddom.php';
		$af_required = new \AgriFlex\RequiredDOM();

		// Make People plugin customizations.
		require_once AF_THEME_DIRPATH . '/src/class-people.php';
		$af_people = new \AgriFlex\People();

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
