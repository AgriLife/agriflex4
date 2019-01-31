<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage AgriFlex4
 */

// Remove search result meta data.
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_content', 'genesis_do_post_permalink', 14 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

// Restrict excerpt length.
add_filter( 'excerpt_length', 'sp_excerpt_length' );
/**
 * Adds the responsive viewport meta tag
 *
 * @since 0.1.0
 * @param int $length The character length of the search result excerpt.
 * @return int
 */
function sp_excerpt_length( $length ) {
	return 50;
}

// Ensure search results use excerpts.
add_filter( 'genesis_pre_get_option_content_archive', 'show_excerpts' );
/**
 * Modify the content archive options
 *
 * @since 0.1.0
 * @return string
 */
function show_excerpts() {
	return 'excerpts';
}

// Add search results page title.
add_action( 'genesis_before_loop', 'agriflex4_search_results_header' );

/**
 * Adds the search results header
 *
 * @since 0.1.0
 * @return void
 */
function agriflex4_search_results_header() {

	$title = sprintf(
		'<div class="entry-header"><h1 class="entry-title">Search Results for: %s</h1></div>',
		get_search_query()
	);

	echo wp_kses_post( $title );

}

genesis();
