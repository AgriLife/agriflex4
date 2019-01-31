<?php

class AgriFlex {

	private static $file = __FILE__;

	private static $instance;

	private function __construct() {

		add_theme_support( 'html5', array() );

		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );

		add_action( 'init', array( $this, 'init' ) );

	}

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

	public static function get_instance() {

		return null === self::$instance ? new self() : self::$instance;

	}

}
