<?php

namespace AgriFlex;

class Navigation {

	public function __construct() {

		// // Use a custom walker for the primary nav
		add_filter( 'genesis_do_nav', array( $this, 'custom_nav_walker' ), 10, 3 );

		// // Add custom 'has-dropdown' class to parent menu items
		// add_filter( 'wp_nav_menu_objects', array( $this, 'custom_parent_class' ) );

		// // Add custom 'active' class when needed
		// add_filter( 'nav_menu_css_class', array( $this, 'custom_active_class' ), 10, 2 );

		// // Add search to the nav bar
		// add_filter( 'agriflex_nav_elements', array( $this, 'display_search' ) );

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

	public function custom_parent_class( $items ) {

		$parents = array();
		foreach ( $items as $item ) {
			if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
				$parents[] = $item->menu_item_parent;
			}
		}

		foreach ( $items as $item ) {
			if ( in_array( $item->ID, $parents ) ) {
				$item->classes[] = 'has-dropdown';
			}
		}

		return $items;
	}

	public function custom_active_class($classes, $item){

		$active_classes = array(
			'current-menu-ancestor',
			'current-menu-parent',
			'current-menu-item',
		);

     if( count( array_intersect( $classes, $active_classes ) ) > 0 ){
             $classes[] = 'active ';
     }

     return $classes;

	}

	public function display_search() {

		$output = sprintf( '<ul class="menu-search-container"><div class="search"><li>%s</li></div></ul>',
			get_search_form( false )
		);
		return $output;

	}

}
