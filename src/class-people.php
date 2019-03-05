<?php
/**
 * The file that initializes AgriLife People plugin features and changes for this child theme.
 *
 * @link       https://github.com/AgriLife/agriflex4/blob/master/src/class-plugin-people.php
 * @since      0.5.3
 * @package    agriflex4
 * @subpackage agriflex4/src
 */

namespace AgriFlex;

/**
 * Changes AgriLife People plugin output to meet our needs
 *
 * @package AgriFlex4
 * @since 0.5.3
 */
class People {

	/**
	 * Initialize the class
	 *
	 * @since 0.5.3
	 * @return void
	 */
	public function __construct() {

		add_filter( 'ag-people-list-item', array( $this, 'people_listing' ), 11, 2 );

	}

	/**
	 * Changes the Genesis default favicon location
	 *
	 * @since 0.1.0
	 * @param string $listing The full output string.
	 * @param array  $markup The pieces which make up the output.
	 * @return string
	 */
	public function people_listing( $listing, $markup ) {

		$markup['title'] = str_replace( 'h4', 'strong', $markup['title'] );

		$markup['name-title'] = "<div class=\"people-head\">
					{$markup['name']}
					{$markup['title']}
				</div>";

		$listing = "{$markup['photo-wrap']}{$markup['name-title']}{$markup['contact-details']}";

		return $listing;

	}

}
