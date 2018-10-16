<?php

namespace AgriFlex;

class Navigation {

	public function __construct() {

		// // Use a custom walker for the primary nav
		add_filter( 'genesis_do_nav', array( $this, 'custom_nav_walker' ), 10, 3 );

    // Remove span tags from nav link elements
    add_filter( 'wp_nav_menu_args', array( $this, 'custom_nav_attributes' ) );

	}

  public function custom_nav_attributes( $atts ){
  	if($atts['theme_location'] == 'primary'){
	  	$atts['link_before'] = '';
	    $atts['link_after'] = '';
	  }
    return $atts;
  }

	public function custom_nav_walker( $nav_output, $nav, $args ) {

		$args['menu_class'] = $args['menu_class'] . ' dropdown';
		$args['walker'] = new \AgriFlex\CustomNavigationWalker;
		$args['items_wrap'] = '<ul id="%s" class="%s" data-responsive-menu="accordion medium-dropdown">%s</ul>';

		$nav_menu = wp_nav_menu( $args );

		$title_bars = array(
			'wrap_open' => '<div class="title-bars title-bar-right show-for-small-only">',
			'wrap_close' => '</div>',
			'inside' => '<div class="title-bar title-bar-navigation" data-responsive-toggle="nav-menu-primary"><button class="menu-icon" type="button" data-toggle="nav-menu-primary"></button><div class="title-bar-title" data-toggle="nav-menu-primary">Menu</div></div>'
		);

		$title_bars['all'] = $title_bars['wrap_open'] . $title_bars['inside'] . $title_bars['wrap_close'];

		$before_nav = apply_filters('af4_before_nav', $title_bars['all'], $title_bars['wrap_open'], $title_bars['wrap_close'], $title_bars['inside']);

		$nav = sprintf( '<div class="top-bar" id="nav-menu-primary"><section class="top-bar-left">%s</section></div>',
			$nav_menu
		);

		$nav_markup_open = genesis_markup( array(
				'html5' => '<nav class="nav-primary" role="navigation">',
				'xhtml' => '<div id="nav">',
				'context' => 'nav-primary',
				'echo' => false,
			) );

		$nav_markup_open .= genesis_structural_wrap( 'menu-primary', 'open', 0 );

		$nav_markup_close = genesis_structural_wrap( 'menu-primary', 'close', 0 );
		$nav_markup_close .= genesis_html5() ? '</nav>' : '</div>';

		$nav_output = $before_nav . $nav_markup_open . $nav . $nav_markup_close;

		return $nav_output;

	}

	public static function get_instance() {

		return null == self::$instance ? new self : self::$instance;

	}

}
