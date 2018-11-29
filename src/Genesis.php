<?php

namespace AgriFlex;

/**
 * Sets up Genesis Framework to our needs
 * @package AgriFlex3
 * @since 0.1.0
 */
class Genesis {

	public function __construct() {

		// Add the responsive viewport
		$this->add_responsive_viewport();

		// Add the responsive viewport
		$this->add_accessibility();

		// Keep Genesis from loading any stylesheets
		$this->remove_stylesheet();

		// Force IE out of compatibility mode
		add_action( 'genesis_meta', array( $this, 'fix_compatibility_mode' ) );

		// Specify the favicon location
		add_filter( 'genesis_pre_load_favicon', array( $this, 'add_favicon' ) );

		// Create the structural wraps
		$this->add_structural_wraps();

		// Clean up the comment area
		add_filter( 'comment_form_defaults', array( $this, 'cleanup_comment_text' ) );

		// Remove profile fields
		add_action( 'admin_init', array( $this, 'remove_profile_fields' ) );

		// Remove unneeded layouts
		$this->remove_genesis_layouts();

		// Remove unneeded sidebars
		$this->remove_genesis_sidebars();

		// Remove site description
		remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

		// Move Genesis in-post SEO box to a lower position
		remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );
		add_action( 'admin_menu', array( $this, 'move_inpost_seo_box' ) );

		// Move Genesis in-post layout box to a lower position
		remove_action( 'admin_menu', 'genesis_add_inpost_layout_box' );
		add_action( 'admin_menu', array( $this, 'move_inpost_layout_box' ) );

		// Remove some Genesis settings metaboxes
		add_action( 'genesis_theme_settings_metaboxes', array( $this, 'remove_genesis_metaboxes' ) );

		// Sticky Header
		add_filter( 'genesis_attr_site-header', array( $this, 'sticky_header_container' ) );
		add_filter( 'genesis_structural_wrap-header', array( $this, 'sticky_header' ) );

		// Add Read More excerpt link
		add_filter( 'excerpt_more', array( $this, 'agriflex_auto_excerpt_more' ) );

	}

	/**
	 * Adds the responsive viewport meta tag
	 * @since 0.1.0
	 * @return void
	 */
	private function add_responsive_viewport() {

		add_theme_support( 'genesis-responsive-viewport' );

	}

	/**
	 * Adds the responsive viewport meta tag
	 * @since 0.1.0
	 * @return void
	 */
	private function add_accessibility() {

		add_theme_support( 'genesis-accessibility', array( 'search-form', 'skip-links' ) );

		// Move skip links
		// remove_action ( 'genesis_before_header', 'genesis_skip_links', 5 );
		// add_action ( 'genesis_before', 'genesis_skip_links', 1 );

		// Modify primary navigation
		// add_filter( 'genesis_do_nav', array( $this, 'primary_nav_id' ), 12, 5 );

	}

	/**
	 * Ensure primary navigation menu works with skip link
	 *
	 * @param $nav_output The raw menu HTML
	 * @since 0.1.0
	 * @return string
	*/
	public function primary_nav_id( $nav_output, $nav, $args ) {

		preg_match_all('/<ul[^>]+>/', $nav_output, $uls);

		foreach ($uls[0] as $value) {

			if( preg_match( '/\bmenu-primary\b/', $value ) === 1 ){

				$newvalue = preg_replace('/id="[^"]+"/', '/id="genesis-nav-primary"/', $value);
				$nav_output = str_replace( $value, $newvalue, $nav_output );
				break;

			}

		}

		return $nav_output;

	}

	/**
	 * Removes any stylesheet Genesis may try to load
	 * @since 0.1.0
	 * @return void
	 */
	private function remove_stylesheet() {

		remove_action( 'genesis_meta', 'genesis_load_stylesheet' );

	}

	/**
	 * Forces IE out of compatibility mode
	 * @since 0.1.0
	 * @return void
	 */
	public function fix_compatibility_mode() {

		echo '<meta http-equiv="X-UA-Compatible" content="IE=Edge">';

	}

	/**
	 * Changes the Genesis default favicon location
	 * @since 0.1.0
	 * @param string $favicon_url The default favicon location
	 * @return string
	 */
	public function add_favicon( $favicon_url ) {

		return AF_THEME_DIRURL . '/images/favicon.ico';

	}

	/**
	 * Adds structural wraps to the specified elements
	 * @since 0.1.0
	 * @return void
	 */
	private function add_structural_wraps() {

		add_theme_support(
			'genesis-structural-wraps',
			array(
				'header',
				'menu-primary',
				'site-inner',
				'footer',
			)
		);

	}


	/**
	 * Remove unneeded user profile fields
	 * @since 0.1.0
	 * @return void
	 */
	public function remove_profile_fields() {

		remove_action( 'show_user_profile', 'genesis_user_options_fields' );
		remove_action( 'edit_user_profile', 'genesis_user_options_fields' );
		remove_action( 'show_user_profile', 'genesis_user_archive_fields' );
		remove_action( 'edit_user_profile', 'genesis_user_archive_fields' );
		remove_action( 'show_user_profile', 'genesis_user_seo_fields'     );
		remove_action( 'edit_user_profile', 'genesis_user_seo_fields'     );
		remove_action( 'show_user_profile', 'genesis_user_layout_fields'  );
		remove_action( 'edit_user_profile', 'genesis_user_layout_fields'  );

	}

	/**
	 * Removes any layouts that we don't need
	 * @since 0.1.0
	 * @return void
	 */
	private function remove_genesis_layouts() {

		genesis_unregister_layout( 'sidebar-content' );
		genesis_unregister_layout( 'content-sidebar-sidebar' );
		genesis_unregister_layout( 'sidebar-sidebar-content' );
  	genesis_unregister_layout( 'sidebar-content-sidebar' );

	}

	/**
	 * Removes any default sidebars that we don't need
	 * @since 0.1.0
	 * @return void
	 */
	private function remove_genesis_sidebars() {

		unregister_sidebar( 'sidebar-alt' );

	}

	/**
	 * Cleans up the default comments text
	 * @since 0.1.0
	 * @param  array $args The default arguments
	 * @return array       The new arguments
	 */
	public function cleanup_comment_text( $args ) {

		$args['comment_notes_before'] = '';
		$args['comment_notes_after']  = '';

		return $args;

	}

	/**
	 * Moves the Genesis in-post SEO box to a lower position
	 * @since 0.1.0
	 * @author Bill Erickson
	 * @return void
	 */
	public function move_inpost_seo_box() {

		if ( genesis_detect_seo_plugins() )
			return;

		foreach ( (array) get_post_types( array( 'public' => true ) ) as $type ) {
			if ( post_type_supports( $type, 'genesis-seo' ) )
				add_meta_box( 'genesis_inpost_seo_box', __( 'Theme SEO Settings', AF_THEME_TEXTDOMAIN ), 'genesis_inpost_seo_box', $type, 'normal', 'default' );
		}

	}

	/**
	 * Moves the Genesis in-post layout box to a lower postion
	 * @since 0.1.0
	 * @return void
	 */
	public function move_inpost_layout_box() {

		if ( ! current_theme_supports( 'genesis-inpost-layouts' ) )
			return;

		foreach ( (array) get_post_types( array( 'public' => true ) ) as $type ) {
			if ( post_type_supports( $type, 'genesis-layouts' ) )
				add_meta_box( 'genesis_inpost_layout_box', __( 'Layout Settings', 'genesis' ), 'genesis_inpost_layout_box', $type, 'normal', 'default' );
		}

	}

	public function remove_genesis_metaboxes( $_genesis_theme_settings_pagehook ) {

		if ( ! is_super_admin() )
			remove_meta_box( 'genesis-theme-settings-version', $_genesis_theme_settings_pagehook, 'main' );

		remove_meta_box( 'genesis-theme-settings-nav',        $_genesis_theme_settings_pagehook, 'main' );
		remove_meta_box( 'genesis-theme-settings-scripts',    $_genesis_theme_settings_pagehook, 'main' );

	}

	/**
	 * Adds attributes for sticky navigation
	 * @since 0.1.0
	 * @return void
	 */
	public function sticky_header_container( $atts ){

		$atts['data-sticky-container'] = 'true';

		return $atts;

	}

	/**
	 * Adds attributes for sticky navigation
	 * @since 0.1.0
	 * @return void
	 */
	public function sticky_header( $output ){

		$output = preg_replace('/wrap"/', 'wrap" data-sticky data-options="stickyOn:small;marginTop:0;"', $output);

		return $output;

	}

	/**
	 * Adds the Read More link to post excerpts
	 */
	public function agriflex_auto_excerpt_more( $more ) {

		return '... <span class="read-more"><a href="' . get_permalink() . '">' .
    __( 'Read More &rarr;', AF_THEME_TEXTDOMAIN ) . '</a></span>';

  }

}
