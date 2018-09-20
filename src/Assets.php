<?php

/**
 * Loads required theme assets
 * @package AgriFlex4
 * @since 1.0.0
 */
class Assets {

	public function __construct() {

		// Register global scripts used in the theme
		add_action( 'wp_enqueue_scripts', array( $this, 'register_public_scripts' ) );

		// Enqueue global scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_scripts' ) );

		// Register global styles used in the theme
		add_action( 'wp_enqueue_scripts', array( $this, 'register_public_styles' ) );

		// Enqueue global styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_styles' ) );

	}

	/**
	 * Registers globally used scripts
	 * @since 1.0
	 * @return void
	 */
	public function register_public_scripts() {

		wp_register_script( 'fastclick',
			AF_THEME_DIRURL . '/node_modules/foundation-sites/js/vendor/fastclick.js',
			false,
			true
		);

		wp_register_script( 'foundation',
			AF_THEME_DIRURL . '/node_modules/foundation-sites/js/foundation/foundation.js',
			array( 'jquery' ),
			false,
			true
		);

		wp_register_script( 'foundation-topbar',
			AF_THEME_DIRURL . '/node_modules/foundation-sites/js/foundation/foundation.topbar.js',
			array( 'foundation' ),
			false,
			true
		);

		wp_register_script( 'agriflex-public',
			AF_THEME_DIRURL . '/js/public.min.js',
			false,
			false,
			true
		);

	}

	/**
	 * Enqueues globally used scripts
	 * @since 1.0
	 * @return void
	 */
	public function enqueue_public_scripts() {

    wp_enqueue_script( 'fastclick' );
		wp_enqueue_script( 'foundation' );
		wp_enqueue_script( 'foundation-topbar' );
		wp_enqueue_script( 'agriflex-public' );

	}

	/**
	 * Registers all styles used within the theme
	 * @since 1.0
	 * @return void
	 */
	public function register_public_styles() {

		wp_register_style(
			'default-styles',
			AF_THEME_DIRURL . 'style.css',
			array(),
			'',
			'screen'
		);

	}

	/**
	 * Enqueues styles used globally
	 * @since 1.0
	 * @global $wp_styles
	 * @return void
	 */
	public function enqueue_public_styles() {

		wp_enqueue_style( 'default-styles' );

	}

    /**
     * Add the correct Typekit
     * @since 1.0
     * @todo Replace with async js and deal with FOUC
     * @todo Pass in variable (TBD) to select correct kit
     * @return string
     */
    public function add_typekit() {

        if(defined('AG_EXT_DIRNAME') || defined('AG_EXTUNIT_DIRNAME') ) {
            // For Extension
            $key = 'xox0blb';
        } elseif (defined('AG_COL_DIRNAME') ) {
            // For College
            $key = 'bbz1kzh';
        } else {
            // For Default Agriflex3
            $key = 'mtx5vmp';
        }

        if( !is_admin() ) :
            ?>
            <script type="text/javascript" src="//use.typekit.net/<?php echo $key; ?>.js"></script>
            <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
            <?php
        endif;

    }


}
