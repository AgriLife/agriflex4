<?php
/**
 * AgriFlex4
 * @package      AgriFlex4
 * @since        0.1.0
 * @copyright    Copyright (c) 2018, Texas A&M AgriLife
 * @author       Zachary K. Watkins
 * @license      GPL-2.0+
 */

// Initialize Genesis
require_once TEMPLATEPATH . '/lib/init.php';
require 'vendor/autoload.php';

// Define some useful constants
define( 'AF_THEME_DIRNAME', 'agriflex4' );
define( 'AF_THEME_DIRPATH', get_stylesheet_directory() );
define( 'AF_THEME_DIRURL', get_stylesheet_directory_uri() );
define( 'AF_THEME_TEXTDOMAIN', 'agriflex4' );

// Autoload all classes
spl_autoload_register( 'AgriFlex::autoload' );

class AgriFlex {

	private static $file = __FILE__;

	private static $instance;

	private function __construct() {

    add_theme_support( 'html5', array() );

		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );

		add_action( 'init', array( $this, 'init' ) );

    // remove_filter( 'genesis_attr_nav-link-wrap', 'genesis_attributes_nav_link_wrap' );

	}

	public function after_setup_theme() {

		add_theme_support( 'align-wide' );

	}

	/**
	 * Initialize the various classes
	 * @since 0.1.0
	 * @return void
	 */
	public function init() {

		// Get Genesis setup the way we want it
		$af_genesis = new \AgriFlex\Genesis;

		// Enqueue our assets
		$af_assets = new \AgriFlex\Assets;

		// Fix the navigation
		$af_navigation = new \AgriFlex\Navigation;

    // Add AgriLife Required DOM Elements
    $af_required = new \AgriFlex\RequiredDOM;

	}

	/**
	 * Autoloads any classes called within the theme
	 * @since 0.1.0
	 * @param  string $classname The name of the class
	 * @return void
	 */
	public static function autoload( $classname ) {

		$filename = dirname( __FILE__ ) .
      DIRECTORY_SEPARATOR .
      str_replace( '_', DIRECTORY_SEPARATOR, $classname ) .
      '.php';
    if ( file_exists( $filename ) )
      require $filename;

	}

	public static function get_instance() {

		return null == self::$instance ? new self : self::$instance;

	}

}

AgriFlex::get_instance();
