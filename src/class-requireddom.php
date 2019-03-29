<?php
/**
 * The file that provides required public DOM elements for the theme
 *
 * A class definition that inserts DOM where needed in the theme
 *
 * @link       https://github.com/AgriLife/agriflex4/blob/master/src/class-requireddom.php
 * @since      1.0.0
 * @package    agriflex4
 * @subpackage agriflex4/src
 */

namespace AgriFlex;

/**
 * Add Required DOM elements and changes
 *
 * @package AgriFlex4
 * @since 0.1.0
 */
class RequiredDOM {

	/**
	 * Initialize the class
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function __construct() {

		// Setup Foundation.
		add_filter( 'language_attributes', array( $this, 'add_no_js_class_to_html_tag' ), 10, 2 );

		// Replace default Genesis footer.
		remove_action( 'genesis_footer', 'genesis_do_footer' );
		add_action( 'genesis_before', array( $this, 'build_footer_content' ) );

		// Alter header tags for SEO.
		add_filter( 'genesis_seo_title', array( $this, 'alter_title_tag' ), 10, 3 );
		add_filter( 'genesis_seo_description', array( $this, 'alter_description_tag' ), 10, 3 );

		add_filter( 'af4_before_nav', array( $this, 'add_search_toggle' ), 10, 4 );

		// Add search form after navigation menu.
		add_action( 'genesis_header', array( $this, 'add_search_form' ) );

	}

	/**
	 * Add a no-js class where needed for Foundation styling to occur in the right order.
	 *
	 * @since 0.1.0
	 * @param string $output Properties of HTML element.
	 * @param string $doctype The doctype of the element.
	 * @return string
	 */
	public function add_no_js_class_to_html_tag( $output, $doctype ) {

		if ( 'html' !== $doctype ) {
			return $output;
		}

		$output .= ' class="no-js"';

		return $output;

	}

	/**
	 * Replace heading tag with div
	 *
	 * @param string $title The title text.
	 * @param string $inside The inner HTML.
	 * @param string $wrap The tag name of the wrap element.
	 *
	 * @return string
	 */
	public static function alter_title_tag( $title, $inside, $wrap ) {

		return preg_replace( '/\b' . $wrap . '\b/', 'div', $title );

	}

	/**
	 * Replace description tag with div
	 *
	 * @param string $title The title text.
	 * @param string $inside The inner HTML.
	 * @param string $wrap The tag name of the wrap element.
	 *
	 * @return string
	 */
	public function alter_description_tag( $title, $inside, $wrap ) {

		// $wrap may empty for some reason.
		if ( empty( $wrap ) ) {
			preg_match( '/\w+/', $title, $results );
			$wrap = $results ? $results[0] : 'h2';
		}

		// $inside may be empty for some reason.
		if ( empty( $inside ) ) {
			$results = preg_split( '/<\/?' . $wrap . '[^>]*>/', $title );
			$inside  = count( $results ) > 1 ? $results[1] : esc_attr( get_bloginfo( 'description' ) );
		}

		// Place wildcards where needed.
		$title = preg_replace( '/\b' . $wrap . '\b/', '%s', $title );
		if ( ! empty( $inside ) ) {
			$title = str_replace( $inside, '%s', $title );
		}

		// Add the site title before the description.
		$wrap  = 'div';
		$title = sprintf(
			$title,
			$wrap,
			$inside,
			$wrap
		);

		return $title;

	}

	/**
	 * Add required links to bottom of page
	 *
	 * @since 1.0
	 * @return void
	 */
	public function build_footer_content() {

		add_action( 'genesis_footer', array( $this, 'render_required_links' ) );
		add_action( 'genesis_footer', array( $this, 'render_tamus_logo' ) );

	}

	/**
	 * Render required links
	 *
	 * @since 1.0
	 * @return void
	 */
	public static function render_required_links() {

		$output  = '<div class="footer-container"><ul class="req-links">';
		$output .= '<li><a href="http://agrilife.org/required-links/compact/">Compact with Texans</a></li>';
		$output .= '<li><a href="http://agrilife.org/required-links/privacy/">Privacy and Security</a></li>';
		$output .= '<li><a href="http://itaccessibility.tamu.edu/" target="_blank">Accessibility Policy</a></li>';
		$output .= '<li><a href="http://publishingext.dir.texas.gov/portal/internal/resources/DocumentLibrary/State%20Website%20Linking%20and%20Privacy%20Policy.pdf" target="_blank">State Link Policy</a></li>';
		$output .= '<li><a href="http://www.tsl.state.tx.us/trail" target="_blank">Statewide Search</a></li>';
		$output .= '<li><a href="http://veterans.tamu.edu/" target="_blank">Veterans Benefits</a></li>';
		$output .= '<li><a href="https://fch.tamu.edu/programs/military-programs/" target="_blank">Military Families</a></li>';
		$output .= '<li><a href="https://secure.ethicspoint.com/domain/en/report_custom.asp?clientid=19681" target="_blank">Risk, Fraud &amp; Misconduct Hotline</a></li>';
		$output .= '<li><a href="https://gov.texas.gov/organization/hsgd" target="_blank">Texas Homeland Security</a></li>';
		$output .= '<li><a href="http://veterans.portal.texas.gov/">Texas Veterans Portal</a></li>';
		$output .= '<li><a href="http://agrilifeas.tamu.edu/hr/diversity/equal-opportunity-educational-programs/" target="_blank">Equal Opportunity</a></li>';
		$output .= '<li class="last"><a href="http://agrilife.org/required-links/orpi/">Open Records/Public Information</a></li>';
		$output .= '</ul></div>';

		echo wp_kses_post( $output );

	}

	/**
	 * Render TAMUS logo
	 *
	 * @since 1.0
	 * @return void
	 */
	public static function render_tamus_logo() {

		$output = '<div class="footer-container-tamus"><a href="http://tamus.edu/" title="Texas A&amp;M University System"><img class="footer-tamus" src="' . AF_THEME_DIRURL . '/images/footer-tamus-maroon.png" alt="Texas A&amp;M University System Member" /></a></div>';

		echo wp_kses_post( $output );

	}

	/**
	 * Add header search and menu toggle
	 *
	 * @since 0.1.0
	 * @param string $content Content of 'af4_before_nav' variable in theme.
	 * @param string $open    Content of the element's open tag.
	 * @param string $close   Content of the element's close tag.
	 * @param string $inside  Content of the element's inner HTML.
	 * @return string
	 */
	public function add_search_toggle( $content, $open, $close, $inside ) {

		$search = '<div class="title-bar title-bar-search" data-responsive-toggle="header-search"><button class="search-icon" type="button" data-toggle="header-search"></button><div class="title-bar-title">Search</div></div>';

		$content = $open . $search . $inside . $close;

		return $content;

	}

	/**
	 * Add header search form
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function add_search_form() {

		echo '<div id="header-search">';
		get_search_form();
		echo '</div>';

	}

}
