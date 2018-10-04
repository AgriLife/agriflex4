<?php

namespace AgriFlex;

class Navigation {

	public function __construct() {

		// // Use a custom walker for the primary nav
		add_filter( 'genesis_do_nav', array( $this, 'custom_nav_walker' ), 10, 3 );

		// Add search to the nav bar
		add_filter( 'agriflex_nav_elements', array( $this, 'display_search' ) );

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

		$small_menu_button = '<div class="title-bar" data-responsive-toggle="nav-menu-primary" data-hide-for="medium"><button class="menu-icon" type="button" data-toggle="nav-menu-primary"></button><div class="title-bar-title">Menu</div></div>';

		$nav = sprintf( '%s<div class="top-bar" id="nav-menu-primary"><section class="top-bar-left">%s %s</section></div>',
			$small_menu_button,
			$nav_menu,
			apply_filters( 'agriflex_nav_elements', '' )
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

		$nav_output = $nav_markup_open . $nav . $nav_markup_close;

		return $nav_output;

	}

	public function display_search() {

		$output = sprintf( '<div class="menu-search-container">%s</div>',
			get_search_form( false )
		);
		return $output;

	}

}
