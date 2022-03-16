<?php
/**
 * The file that provides navigation DOM changes for the Foundation primary nav menu.
 *
 * A class definition that inserts or changes DOM where needed in the theme
 * to provide a correctly formatted navigation menu for the Foundation framework
 * to interact with.
 *
 * Updated 3/16/2022 to reflect new Foundation syntax for dropdown accordion menu toggle 
 *
 * @link       https://github.com/AgriLife/agriflex4/blob/master/src/class-navigation.php
 * @since      0.1.0
 * @package    agriflex4
 * @subpackage agriflex4/src
 */

namespace AgriFlex;

/**
 * Add Required DOM elements and changes for the primary navigation menu.
 *
 * @package AgriFlex4
 * @since 0.1.0
 */
class Navigation {

	/**
	 * Initialize the class
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function __construct() {

		// // Use a custom walker for the primary nav.
		add_filter( 'genesis_do_nav', array( $this, 'custom_nav_walker' ), 10, 3 );

		// Remove span tags from nav link elements.
		add_filter( 'wp_nav_menu_args', array( $this, 'custom_nav_attributes' ) );

		add_filter( 'widget_nav_menu_args', array( $this, 'vertical_widget_menu' ), 11, 4 );

	}

	/**
	 * Make nav menu widgets vertical.
	 *
	 * @since 1.12.0
	 * @param array   $nav_menu_args An array of arguments passed to wp_nav_menu() to retrieve a navigation menu.
	 * @param WP_Term $nav_menu Nav menu object for the current menu.
	 * @param array   $args Display arguments for the current widget.
	 * @param array   $instance Array of settings for the current widget.
	 * @return array
	 */
	public function vertical_widget_menu( $nav_menu_args, $nav_menu, $args, $instance ) {

		if ( false !== strpos( $args['name'], 'Sidebar' ) ) {

			if ( ! isset( $nav_menu_args['menu_class'] ) ) {

				$nav_menu_args['menu_class'] = 'menu vertical';

			} else {

				$nav_menu_args['menu_class'] .= ' vertical';

			}
		}

		return $nav_menu_args;

	}

	/**
	 * Add HTML attributes to the primary navigation menu.
	 *
	 * @since 0.1.0
	 * @param array $atts Attributes of each registered navigation menu.
	 * @return array
	 */
	public function custom_nav_attributes( $atts ) {

		if ( 'primary' === $atts['theme_location'] ) {
			$atts['link_before'] = '';
			$atts['link_after']  = '';
		}

		return $atts;
	}

	/**
	 * Add HTML elements to the primary navigation menu container and modify each
	 * navigation menu item with required attributes for the Foundation framework.
	 *
	 * @since 0.1.0
	 * @param string $nav_output The full output of the navigation menu.
	 * @param string $nav The output of the navigation menu itself.
	 * @param array  $args The list of arguments for the navigation menu.
	 * @return array
	 */
	public function custom_nav_walker( $nav_output, $nav, $args ) {

		$args['menu_class'] = $args['menu_class'] . 'vertical medium-horizontal menu';
		$args['menu_id']    = 'genesis-nav-primary';
		require_once AF_THEME_DIRPATH . '/src/class-customnavigationwalker.php';
		$args['walker']     = new \AgriFlex\CustomNavigationWalker();
		$args['items_wrap'] = '<ul id="%s" class="%s" data-responsive-menu="accordion medium-dropdown" data-submenu-toggle="true">%s</ul>';
		$args['class']      = apply_filters( 'af4_primary_nav_class', array( 'nav-primary', 'cell', 'small-12', 'medium-auto' ) );

		$nav_menu = wp_nav_menu( $args );
		$nav_menu = apply_filters( 'af4_primary_nav_menu', $nav_menu );

		$before_nav = apply_filters( 'af4_before_nav', '' );

		// Top bar.
		$top_bar_atts = array(
			'class' => 'top-bar',
			'id'    => 'nav-menu-primary',
		);
		$top_bar_atts = apply_filters( 'af4_top_bar_attr', $top_bar_atts );
		$top_bar_att  = '';

		// Cycle through attributes, build tag attribute string.
		foreach ( $top_bar_atts as $key => $value ) {

			if ( ! $value ) {
				continue;
			}

			if ( true === $value ) {
				$top_bar_att .= esc_html( $key ) . ' ';
			} else {
				$top_bar_att .= sprintf( '%s="%s" ', esc_html( $key ), esc_attr( $value ) );
			}
		}

		// Top bar left.
		$top_bar_left_atts = array( 'class' => 'top-bar-left' );
		$top_bar_left_atts = apply_filters( 'af4_top_bar_left_attr', $top_bar_left_atts );
		$top_bar_left_att  = '';

		// Cycle through attributes, build tag attribute string.
		foreach ( $top_bar_left_atts as $key => $value ) {

			if ( ! $value ) {
				continue;
			}

			if ( true === $value ) {
				$top_bar_left_att .= esc_html( $key ) . ' ';
			} else {
				$top_bar_left_att .= sprintf( '%s="%s" ', esc_html( $key ), esc_attr( $value ) );
			}
		}

		$nav = sprintf(
			'<div %s><section %s>%s</section></div>',
			$top_bar_att,
			$top_bar_left_att,
			$nav_menu
		);

		$nav_markup_open = genesis_markup(
			array(
				'html5'   => sprintf( '<nav class="%s">', implode( ' ', $args['class'] ) ),
				'xhtml'   => '<div id="nav">',
				'context' => 'nav-primary',
				'echo'    => false,
			)
		);

		$nav_markup_open  .= genesis_get_structural_wrap( 'menu-primary', 'open' );
		$nav_markup_close  = genesis_get_structural_wrap( 'menu-primary', 'close' );
		$nav_markup_close .= genesis_html5() ? '</nav>' : '</div>';
		$nav_output        = $before_nav . $nav_markup_open . $nav . $nav_markup_close;

		return $nav_output;

	}

	/**
	 * Get the instance of this class
	 *
	 * @since 0.1.0
	 * @return object
	 */
	public static function get_instance() {

		return null === self::$instance ? new self() : self::$instance;

	}

}
