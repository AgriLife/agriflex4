<?php
/**
 * Template Name: Private: Must Be Logged In
 */

if ( ! is_user_logged_in() ) {
	remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
	add_action( 'genesis_entry_content', 'agrilife_members_only' );
}

function agrilife_members_only() {

	printf( '<p>You must <a href="%s" title="login">login</a> to view this page</p>',
		wp_login_url()
	);

}

genesis();
