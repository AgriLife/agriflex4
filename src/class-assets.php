<?php
/**
 * The file that provides CSS and JS assets for the theme.
 *
 * @link       https://github.com/AgriLife/agriflex4/blob/master/src/class-assets.php
 * @since      0.1.0
 * @package    agriflex4
 * @subpackage agriflex4/src
 */

namespace AgriFlex;

/**
 * Loads required theme assets
 *
 * @package AgriFlex4
 * @since 0.1.0
 */
class Assets {

	/**
	 * Initialize the class
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function __construct() {

		// Register global scripts used in the theme.
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_styles' ) );

		// Register global scripts used in the theme.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_public_scripts' ), 11 );

		// Enqueue global scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_scripts' ), 13 );

		// Register global styles used in the theme.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_public_styles' ) );

		// Enqueue global styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_styles' ) );

	}

	/**
	 * Registers admin styles
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function register_admin_styles() {

		wp_register_style(
			'agriflex-admin',
			AF_THEME_DIRURL . '/css/admin.css',
			array(),
			filemtime( AF_THEME_DIRPATH . '/css/admin.css' ),
			'screen'
		);

		wp_enqueue_style( 'agriflex-admin' );

	}

	/**
	 * Registers globally used scripts
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function register_public_scripts() {

		wp_register_script(
			'foundation',
			AF_THEME_DIRURL . '/js/foundation.concat.js',
			array( 'jquery' ),
			filemtime( AF_THEME_DIRPATH . '/js/foundation.concat.js' ),
			true
		);

		wp_register_script(
			'agriflex-public',
			AF_THEME_DIRURL . '/js/public.min.js',
			false,
			filemtime( AF_THEME_DIRPATH . '/js/public.min.js' ),
			true
		);

	}

	/**
	 * Enqueues globally used scripts
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function enqueue_public_scripts() {

		wp_enqueue_script( 'foundation' );
		wp_enqueue_script( 'agriflex-public' );

	}

	/**
	 * Registers all styles used within the theme
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function register_public_styles() {

		wp_register_style(
			'agriflex4-googlefonts',
			'https://fonts.googleapis.com/css?family=Oswald|Monoton|Open+Sans',
			array(),
			'1.0.0'
		);

		wp_register_style(
			'agriflex-default-styles',
			AF_THEME_DIRURL . '/css/default.css',
			array(),
			filemtime( AF_THEME_DIRPATH . '/css/default.css' ),
			'screen'
		);

	}

	/**
	 * Enqueues styles used globally
	 *
	 * @since 0.1.0
	 * @global $wp_styles
	 * @return void
	 */
	public function enqueue_public_styles() {

		wp_enqueue_style( 'agriflex4-googlefonts' );
		wp_enqueue_style( 'agriflex-default-styles' );

	}


}
