<?php
/**
 * The template for displaying blog posts on a page chosen on the Reading
 * Settings dashboard page.
 *
 * @link       https://github.com/AgriLife/agriflex4/blob/master/home.php
 * @since      0.6.4
 * @package    agriflex4
 */

remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
add_action( 'genesis_entry_header', 'genesis_do_post_image', 8 );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
add_filter( 'genesis_post_info', 'af4_post_meta_filter' );
add_filter( 'excerpt_more', 'af4_remove_more_link', 12 );

/**
 * Modify post meta data.
 *
 * @since 0.6.4
 * @param string $post_info The post meta content using shortcodes.
 * @return string
 */
function af4_post_meta_filter( $post_info ) {
	$post_info = '[post_date] [post_edit]';
	return $post_info;
}

/**
 * Removes the read-more link from the excerpt.
 *
 * @since 0.6.4
 * @return string
 */
function af4_remove_more_link() {
	return '...';
}

genesis();
