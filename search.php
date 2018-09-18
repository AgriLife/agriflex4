<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage AgriFlex4
 */

// Remove search result meta data
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_content', 'genesis_do_post_permalink', 14 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

// Restrict excerpt length
add_filter( 'excerpt_length', 'sp_excerpt_length' );
function sp_excerpt_length( $length ) {
	return 50;
}

// Ensure search results use excerpts
add_filter( 'genesis_pre_get_option_content_archive', 'show_excerpts' );
function show_excerpts() {
	return 'excerpts';
}

// Add search results page title
add_action( 'genesis_before_loop', function() {

  $title = sprintf( '<div class="entry-header"><h1 class="entry-title">Search Results for: %s</h1></div>',
    get_search_query()
  );

  echo $title;

});

genesis();
