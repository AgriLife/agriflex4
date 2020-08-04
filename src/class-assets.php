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
		add_action( 'wp_enqueue_scripts', array( $this, 'register_public_scripts' ), 11 );

		// Enqueue global scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_scripts' ), 13 );

		// Register global styles used in the theme.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_public_styles' ), 1 );

		// Enqueue global styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_styles' ), 1 );

		// Register global styles used in the theme.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_external_styles' ), 3 );

		// Enqueue global styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_external_styles' ), 3 );

		// Register global scripts used in the theme.
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_external_styles' ), 3 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_external_styles' ), 3 );

		// Remove unneeded default assets.
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );

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

		// Move default jquery script to the footer.
		wp_deregister_script( 'jquery' );
		wp_deregister_script( 'jquery-migrate' );

		wp_register_script(
			'jquery',
			includes_url( '/js/jquery/jquery.js' ),
			false,
			'1.12.4',
			true
		);

		wp_register_script(
			'jquery-migrate',
			includes_url( '/js/jquery/jquery-migrate.min.js' ),
			false,
			'1.4.1',
			true
		);

		// Theme scripts.
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
			array( 'foundation' ),
			filemtime( AF_THEME_DIRPATH . '/js/public.min.js' ),
			true
		);

		wp_register_script(
			'agrilife-post-tile-search',
			AF_THEME_DIRURL . '/js/post-type-tile-filters.js',
			array( 'jquery', 'foundation' ),
			filemtime( AF_THEME_DIRPATH . '/js/post-type-tile-filters.js' ),
			true
		);

		$field = get_field( 'google_search_engine_id', 'option' );

		if ( ! empty( $field ) ) {

			wp_register_script(
				'google-cse',
				'https://cse.google.com/cse.js?cx=' . $field,
				array(),
				'1.0.0',
				true
			);

		}

	}

	/**
	 * Enqueues globally used scripts
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function enqueue_public_scripts() {

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-migrate' );
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

		global $wp_query;
		$template_name = get_post_meta( $wp_query->post->ID, '_wp_page_template', true );

		wp_register_style(
			'agriflex-default-styles',
			AF_THEME_DIRURL . '/css/style.css',
			array( 'wp-block-library' ),
			filemtime( AF_THEME_DIRPATH . '/css/style.css' ),
			'screen'
		);

		// If body class is page-template-default or post-template-default.
		if ( is_singular( 'post' ) || ( is_singular( 'page' ) && ( ! $template_name || 'default' === $template_name ) ) ) {
			wp_register_style(
				'agriflex-default-template-styles',
				AF_THEME_DIRURL . '/css/template-default.css',
				array( 'agriflex-default-styles' ),
				filemtime( AF_THEME_DIRPATH . '/css/template-default.css' ),
				'screen'
			);
		}

	}

	/**
	 * Enqueues styles used globally
	 *
	 * @since 0.1.0
	 * @global $wp_styles
	 * @return void
	 */
	public function enqueue_public_styles() {

		global $wp_query;
		$template_name = get_post_meta( $wp_query->post->ID, '_wp_page_template', true );

		wp_enqueue_style( 'agriflex-default-styles' );

		// If body class is page-template-default or post-template-default.
		if ( is_singular( 'post' ) || ( is_singular( 'page' ) && ( ! $template_name || 'default' === $template_name ) ) ) {
			wp_enqueue_style( 'agriflex-default-template-styles' );
		}

	}

	/**
	 * Registers third party styles
	 *
	 * @since 1.0.1
	 * @return void
	 */
	public function register_external_styles() {

		wp_register_style(
			'agriflex4-googlefonts',
			'https://fonts.googleapis.com/css?family=Oswald:300,400,700|Monoton|Open+Sans:300,400,600,700,800',
			array(),
			'1.0.0',
			'all'
		);

	}

	/**
	 * Enqueues third party styles
	 *
	 * @since 1.0.1
	 * @return void
	 */
	public function enqueue_external_styles() {

		wp_enqueue_style( 'agriflex4-googlefonts' );

	}


}
