<?php
/**
 * Template Name: Private: Must Be Logged In
 *
 * @package WordPress
 * @subpackage AgriFlex4
 */

if ( ! is_user_logged_in() ) {
	remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
	add_action( 'genesis_entry_content', 'agrilife_members_only' );
}

/**
 * Output the message to users who are not logged in
 *
 * @since 0.1.0
 * @return void
 */
function agrilife_members_only() {

	$output = sprintf(
		'<p>You must <a href="%s" title="login">login</a> to view this page</p>',
		wp_login_url()
	);

	wp_kses_post( $output );

}

genesis();
