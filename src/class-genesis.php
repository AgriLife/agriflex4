<?php
/**
 * The file that initializes Genesis features and changes for this child theme.
 *
 * @link       https://github.com/AgriLife/agriflex4/blob/master/src/class-genesis.php
 * @since      0.1.0
 * @package    agriflex4
 * @subpackage agriflex4/src
 */

namespace AgriFlex;

/**
 * Sets up Genesis Framework to our needs
 *
 * @package AgriFlex3
 * @since 0.1.0
 */
class Genesis {

	/**
	 * Initialize the class
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function __construct() {

		// Add the responsive viewport.
		$this->add_responsive_viewport();

		// Add the responsive viewport.
		$this->add_accessibility();

		// Keep Genesis from loading any stylesheets.
		$this->remove_stylesheet();

		// Force IE out of compatibility mode.
		add_action( 'genesis_meta', array( $this, 'fix_compatibility_mode' ) );

		// Specify the favicon location.
		add_filter( 'genesis_pre_load_favicon', array( $this, 'add_favicon' ) );

		// Create the structural wraps.
		$this->add_structural_wraps();

		// Clean up the comment area.
		add_filter( 'comment_form_defaults', array( $this, 'cleanup_comment_text' ) );

		// Remove profile fields.
		add_action( 'admin_init', array( $this, 'remove_profile_fields' ) );

		// Remove unneeded layouts.
		$this->remove_genesis_layouts();

		// Remove unneeded sidebars.
		$this->remove_genesis_sidebars();

		// Add widget areas.
		$this->add_widget_areas();

		// Remove site description.
		remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

		// Move Genesis in-post SEO box to a lower position.
		remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );
		add_action( 'admin_menu', array( $this, 'move_inpost_seo_box' ) );

		// Move Genesis in-post layout box to a lower position.
		remove_action( 'admin_menu', 'genesis_add_inpost_layout_box' );
		add_action( 'admin_menu', array( $this, 'move_inpost_layout_box' ) );

		// Remove some Genesis settings metaboxes.
		add_action( 'genesis_theme_settings_metaboxes', array( $this, 'remove_genesis_metaboxes' ) );

		// Add Foundation XY Grid Classes.
		add_filter( 'genesis_structural_wrap-site-inner', array( $this, 'class_site_inner_wrap' ) );
		add_filter( 'genesis_attr_content-sidebar-wrap', array( $this, 'class_grid_x_content' ) );
		add_filter( 'genesis_attr_sidebar-content-wrap', array( $this, 'class_grid_x_content' ) );
		add_filter( 'genesis_attr_content-wrap', array( $this, 'class_grid_x_content' ) );
		add_filter( 'genesis_attr_title-area', array( $this, 'class_cell_title_area' ) );
		add_filter( 'genesis_attr_nav-primary', array( $this, 'class_cell_nav_primary' ) );
		add_filter( 'genesis_attr_content', array( $this, 'class_cell_content' ) );
		add_filter( 'genesis_attr_sidebar-primary', array( $this, 'class_cell_sidebar' ) );
		add_filter( 'genesis_structural_wrap-footer', array( $this, 'class_footer_wrap' ) );

		// Sticky Header.
		add_filter( 'genesis_structural_wrap-header', array( $this, 'sticky_header' ) );
		remove_action( 'wp_head', 'genesis_custom_header_style' );

		// Add Read More excerpt link.
		add_filter( 'excerpt_more', array( $this, 'agriflex_auto_excerpt_more' ), 11 );

		// Relocate primary navigation menu.
		remove_action( 'genesis_after_header', 'genesis_do_nav' );
		add_action( 'genesis_header', 'genesis_do_nav' );

		// Replace site title with logo.
		add_filter( 'genesis_seo_title', array( $this, 'add_logo' ), 10, 3 );

	}

	/**
	 * Add grid-container class name
	 *
	 * @since 0.1.0
	 * @param string $output The wrap HTML.
	 * @return string
	 */
	public function class_site_inner_wrap( $output ) {

		$output = str_replace( 'class="', 'class="grid-container ', $output );

		return $output;

	}

	/**
	 * Add header title area cell class names
	 *
	 * @since 0.1.0
	 * @param array $attributes HTML attributes.
	 * @return array
	 */
	public function class_grid_container( $attributes ) {
		$attributes['class'] .= ' grid-container full';
		return $attributes;
	}

	/**
	 * Add grid-x class name
	 *
	 * @since 0.1.0
	 * @param string $output The wrap HTML.
	 * @return string
	 */
	public function class_footer_wrap( $output ) {

		$output = str_replace( 'class="', 'class="grid-container grid-x grid-padding-x ', $output );

		return $output;
	}

	/**
	 * Add grid-x class name
	 *
	 * @since 0.1.0
	 * @param array $attributes HTML attributes.
	 * @return array
	 */
	public function class_grid_x_content( $attributes ) {
		$margin               = 'full-width-content' === genesis_site_layout() ? '' : ' grid-padding-x';
		$attributes['class'] .= " grid-x$margin";
		return $attributes;
	}

	/**
	 * Add header title area cell class names
	 *
	 * @since 0.1.0
	 * @param array $attributes HTML attributes.
	 * @return array
	 */
	public function class_cell_title_area( $attributes ) {
		$attributes['class'] .= ' cell small-6 medium-2';
		return $attributes;
	}

	/**
	 * Add header nav primary cell class names
	 *
	 * @since 0.1.0
	 * @param array $attributes HTML attributes.
	 * @return array
	 */
	public function class_cell_nav_primary( $attributes ) {
		$attributes['class'] .= ' cell small-12 medium-auto';
		return $attributes;
	}

	/**
	 * Add content cell class names
	 *
	 * @since 0.1.0
	 * @param array $attributes HTML attributes.
	 * @return array
	 */
	public function class_cell_content( $attributes ) {
		$attributes['class'] .= ' cell small-12 medium-auto';
		return $attributes;
	}

	/**
	 * Add sidebar cell class names
	 *
	 * @since 0.1.0
	 * @param array $attributes HTML attributes.
	 * @return array
	 */
	public function class_cell_sidebar( $attributes ) {
		$attributes['class'] .= ' cell small-12 medium-4';
		return $attributes;
	}

	/**
	 * Adds the responsive viewport meta tag
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function add_responsive_viewport() {

		add_theme_support( 'genesis-responsive-viewport' );

	}

	/**
	 * Adds the responsive viewport meta tag
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function add_accessibility() {

		add_theme_support( 'genesis-accessibility', array( 'search-form', 'skip-links' ) );

		// Move skip links.
		remove_action( 'genesis_before_header', 'genesis_skip_links', 5 );
		add_action( 'genesis_before', 'genesis_skip_links', 1 );

	}

	/**
	 * Removes any stylesheet Genesis may try to load
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function remove_stylesheet() {

		remove_action( 'genesis_meta', 'genesis_load_stylesheet' );

	}

	/**
	 * Forces IE out of compatibility mode
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function fix_compatibility_mode() {

		echo '<meta http-equiv="X-UA-Compatible" content="IE=Edge">';

	}

	/**
	 * Changes the Genesis default favicon location
	 *
	 * @since 0.1.0
	 * @param string $favicon_url The default favicon location.
	 * @return string
	 */
	public function add_favicon( $favicon_url ) {

		return AF_THEME_DIRURL . '/images/favicon.ico';

	}

	/**
	 * Adds structural wraps to the specified elements
	 *
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
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function remove_profile_fields() {

		remove_action( 'show_user_profile', 'genesis_user_options_fields' );
		remove_action( 'edit_user_profile', 'genesis_user_options_fields' );
		remove_action( 'show_user_profile', 'genesis_user_archive_fields' );
		remove_action( 'edit_user_profile', 'genesis_user_archive_fields' );
		remove_action( 'show_user_profile', 'genesis_user_seo_fields' );
		remove_action( 'edit_user_profile', 'genesis_user_seo_fields' );
		remove_action( 'show_user_profile', 'genesis_user_layout_fields' );
		remove_action( 'edit_user_profile', 'genesis_user_layout_fields' );

	}

	/**
	 * Removes any layouts that we don't need
	 *
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
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function remove_genesis_sidebars() {

		unregister_sidebar( 'sidebar-alt' );
		unregister_sidebar( 'header-right' );

	}

	/**
	 * Adds sidebars
	 *
	 * @since 1.0.6
	 * @return void
	 */
	private function add_widget_areas() {

		genesis_register_sidebar(
			array(
				'name'        => __( 'Header - Right', 'agrilife-today' ),
				'id'          => 'af4-header-right',
				'description' => __( 'This is the widget area for the right side of the header.', 'agrilife-today' ),
			)
		);

	}

	/**
	 * Cleans up the default comments text
	 *
	 * @since 0.1.0
	 * @param array $args The default arguments.
	 * @return array The new arguments
	 */
	public function cleanup_comment_text( $args ) {

		$args['comment_notes_before'] = '';
		$args['comment_notes_after']  = '';

		return $args;

	}

	/**
	 * Moves the Genesis in-post SEO box to a lower position
	 *
	 * @since 0.1.0
	 * @author Bill Erickson
	 * @return void
	 */
	public function move_inpost_seo_box() {

		if ( genesis_detect_seo_plugins() ) {
			return;
		}

		foreach ( (array) get_post_types( array( 'public' => true ) ) as $type ) {
			if ( post_type_supports( $type, 'genesis-seo' ) ) {
				add_meta_box( 'genesis_inpost_seo_box', __( 'Theme SEO Settings', 'agriflex4' ), 'genesis_inpost_seo_box', $type, 'normal', 'default' );
			}
		}

	}

	/**
	 * Moves the Genesis in-post layout box to a lower postion
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function move_inpost_layout_box() {

		if ( ! current_theme_supports( 'genesis-inpost-layouts' ) ) {
			return;
		}

		foreach ( (array) get_post_types( array( 'public' => true ) ) as $type ) {
			if ( post_type_supports( $type, 'genesis-layouts' ) ) {
				add_meta_box( 'genesis_inpost_layout_box', __( 'Layout Settings', 'genesis' ), 'genesis_inpost_layout_box', $type, 'normal', 'default' );
			}
		}

	}

	/**
	 * Adds attributes for sticky navigation and add wrap for header layout requirements
	 *
	 * @since 0.1.0
	 * @param string $_genesis_theme_settings_pagehook The hook name for the genesis theme setting.
	 * @return void
	 */
	public function remove_genesis_metaboxes( $_genesis_theme_settings_pagehook ) {

		if ( ! is_super_admin() ) {
			remove_meta_box( 'genesis-theme-settings-version', $_genesis_theme_settings_pagehook, 'main' );
		}

		remove_meta_box( 'genesis-theme-settings-nav', $_genesis_theme_settings_pagehook, 'main' );
		remove_meta_box( 'genesis-theme-settings-scripts', $_genesis_theme_settings_pagehook, 'main' );

	}

	/**
	 * Adds attributes for sticky navigation and add wrap for header layout requirements
	 *
	 * @since 0.1.0
	 * @param string $output The output of the Genesis header wrap.
	 * @return string
	 */
	public function sticky_header( $output ) {

		$output = preg_replace( '/<div class="wrap"/', '<div class="wrap" data-sticky-container><div class="wrap" data-sticky data-options="stickyOn:small;marginTop:0;"><div class="grid-container"><div class="grid-x grid-padding-x"', $output );
		$output = preg_replace( '/<\/div>$/', '</div></div></div></div>', $output );

		return $output;

	}

	/**
	 * Add close wrap to enable desired header layout
	 *
	 * @since 0.1.0
	 * @param string $output The output of the header extra wrap close.
	 * @return string
	 */
	public function header_extra_wrap_close( $output ) {

		return $output;

	}

	/**
	 * Adds the Read More link to post excerpts
	 *
	 * @since 0.1.0
	 * @param string $more The current "more" text.
	 * @return string
	 */
	public function agriflex_auto_excerpt_more( $more ) {

		return '... <span class="read-more"><a href="' . get_permalink() . '">' .
		__( 'Read More &rarr;', 'agriflex4' ) . '</a></span>';

	}

	/**
	 * Initialize the class
	 *
	 * @since 0.1.0
	 * @param string $title Genesis SEO title html.
	 * @param string $inside The inner HTML of the title.
	 * @param string $wrap The tag name of the seo title wrap element.
	 * @return string
	 */
	public function add_logo( $title, $inside, $wrap ) {

		$logo = sprintf( '<img src="%s">', AF_THEME_DIRURL . '/images/logo-agrilife.png' );
		$home = trailingslashit( home_url() );

		$new_inside = sprintf(
			'<a href="%s" title="Texas A&M AgriLife">%s</a>',
			$home,
			$logo
		);

		$title = str_replace( $inside, $new_inside, $title );

		return $title;

	}

}
