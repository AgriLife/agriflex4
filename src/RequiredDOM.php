<?php

namespace AgriFlex;

/**
 * Add Required links and header
 * @package AgriFlex3
 * @since 1.0
 */
class RequiredDOM
{

    public function __construct()
    {

        // Setup Foundation
        add_filter( 'language_attributes', array( $this, 'add_no_js_class_to_html_tag' ), 10, 2 );

        // Add bar of AgriLife agencies
        $this->add_agency_bar();

        // Remove default Genesis footer
        $this->remove_genesis_footer();

        // Add required links to footer
        //$this->build_footer_content();

        add_action('genesis_before', array($this, 'build_footer_content'));

        // Alter header title
        //$this->alter_header_title();

        //$this->add_unit_title();

        // Render the social icons in header
        //$this->render_header_social();

        //* Reposition the breadcrumbs
        $this->move_genesis_breadcrumbs();

        // Alter header tags for SEO
        add_filter( 'genesis_seo_title', array($this, 'alter_title_tag'), 10, 3 );
        add_filter( 'genesis_seo_description', array($this, 'alter_description_tag'), 10, 3 );

    }

    public function add_no_js_class_to_html_tag( $output, $doctype ) {

        if ( 'html' !== $doctype ) {
            return $output;
        }

        $output .= ' class="no-js"';

        return $output;

    }

    /**
     * Add agency bar to top of page
     * @since 1.0
     * @return void
     */
    private function add_agency_bar()
    {

        add_action('genesis_before', array($this, 'render_agency_bar'));

    }

    /**
     * Replace heading tag with div
     *
     * @param $title The title text
     * @param $inside
     * @param $wrap
     *
     * @return string
     */

    public static function alter_title_tag( $title, $inside, $wrap ) {

        return preg_replace( '/\b'.$wrap.'\b/', 'div', $title );

    }

    /**
     * Replace description tag with div
     *
     * @param $title The title text
     * @param $inside
     * @param $wrap
     *
     * @return string
     */
    public function alter_description_tag( $title, $inside, $wrap ) {

        // $wrap may empty for some reason
        if(empty($wrap)){
            preg_match( '/\w+/', $title, $results );
            $wrap = $results ? $results[0] : 'h2';
        }

        // $inside may be empty for some reason
        if(empty($inside)){
            $results = preg_split('/<\/?'.$wrap.'[^>]*>/', $title);
            $inside = sizeof($results) > 1 ? $results[1] : esc_attr( get_bloginfo('description'));
        }

        // Place wildcards where needed
        $title = preg_replace( '/\b'.$wrap.'\b/', '%s', $title );
        if(!empty($inside)){
            $title = str_replace( $inside, '%s', $title );
        }

        // Add the site title before the description
        $wrap = 'div';
        $title = sprintf( $title,
            $wrap,
            $inside,
            $wrap
        );

        return $title;

    }

    /**
     * Modify the header title
     * @since 1.0
     * @return string
     */
    private function alter_header_title()
    {
        add_filter('genesis_seo_title', 'sp_seo_title', 10, 3);
        function sp_seo_title()
        {

            $title = '<h4 class="site-title" itemprop="agency"><a href="http://agrilife.org" title="Texas A&amp;M AgriLife"><span>Texas A&amp;M AgriLife</span></a></h4>';
            return $title;
        }

    }


    /**
     * Add required links to bottom of page
     * @since 1.0
     * @return void
     */
    private function add_unit_title()
    {

        add_action('genesis_site_description', array($this, 'unit_title'), 1);

    }
    public function unit_title()
    {
        $wrap = 'div';
        $inside = sprintf('<a href="%s" title="%s"><span>%s</span></a>', esc_attr(get_bloginfo('url')), esc_attr(get_bloginfo('name')), get_bloginfo('name'));
        $title = sprintf('<%s class="site-unit-title" itemprop="headline">%s</%s>', $wrap, $inside, $wrap);
        echo $title;
    }

    /**
     * Add required links to bottom of page
     * @since 1.0
     * @return void
     */
    public function build_footer_content()
    {

        add_action('genesis_footer', array($this, 'render_required_links'));
        add_action('genesis_footer', array($this, 'render_tamus_logo'));

    }

    /**
     * Remove default Genesis footer
     * @since 1.0
     * @return void
     */
    private function remove_genesis_footer()
    {

        remove_action('genesis_footer', 'genesis_do_footer');

    }

    /**
     * Move Genesis Breadcrumbs
     * @since 1.0
     * @return void
     */
    private function move_genesis_breadcrumbs()
    {

        remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
        add_action( 'genesis_before_content', 'genesis_do_breadcrumbs' );

        add_filter( 'genesis_breadcrumb_args', 'sp_breadcrumb_args' );
        function sp_breadcrumb_args( $args ) {
            //$args['home'] = 'Home';
            $args['sep'] = ' > ';
            //$args['list_sep'] = ', '; // Genesis 1.5 and later
            //$args['prefix'] = '<div class="breadcrumb">';
            //$args['suffix'] = '</div>';
            //$args['heirarchial_attachments'] = true; // Genesis 1.5 and later
            //$args['heirarchial_categories'] = true; // Genesis 1.5 and later
            //$args['display'] = true;
            $args['labels']['prefix'] = '';
            //$args['labels']['author'] = 'Archives for ';
            //$args['labels']['category'] = 'Archives for '; // Genesis 1.6 and later
            //$args['labels']['tag'] = 'Archives for ';
            //$args['labels']['date'] = 'Archives for ';
            //$args['labels']['search'] = 'Search for ';
            //$args['labels']['tax'] = 'Archives for ';
            //$args['labels']['post_type'] = 'Archives for ';
            //$args['labels']['404'] = 'Not found: '; // Genesis 1.5 and later
            return $args;
        }

    }

    /**
     * Render the agency header bar
     * @since 1.0
     * @return string
     */
    public function render_agency_bar()
    {

        $output = '
          <div class="agency-bar">
            <div class="agency-wrap">
                <ul>
		            <li class="tfs-item"><a href="http://texasforestservice.tamu.edu/"><span>Texas A&amp;M Forest Service</span></a></li><li class="tvmdl-item"><a href="http://tvmdl.tamu.edu/"><span>Texas A&amp;M Veterinary Medical Diagnostics Laboratory</span></a></li><li class="ext-item"><a href="http://agrilifeextension.tamu.edu/"><span>Texas A&amp;M AgriLife Extension Service</span></a></li><li class="res-item"><a href="http://agriliferesearch.tamu.edu/"><span>Texas A&amp;M AgriLife Research</span></a></li><li class="college-item"><a href="http://aglifesciences.tamu.edu/"><span>Texas A&amp;M College of Agrculture and Life Sciences</span></a></li>
		        </ul>
		    </div>
		  </div>
		  ';

        echo $output;

    }

    /**
     * Render required links
     * @since 1.0
     * @return string
     */
    public static function render_required_links()
    {

        $output = '<div class="footer-container"><ul class="req-links">
                <li><a href="http://agrilife.org/required-links/compact/">Compact with Texans</a></li>
                <li><a href="http://agrilife.org/required-links/privacy/">Privacy and Security</a></li>
                <li><a href="http://itaccessibility.tamu.edu/" target="_blank">Accessibility Policy</a></li>
                <li><a href="http://publishingext.dir.texas.gov/portal/internal/resources/DocumentLibrary/State%20Website%20Linking%20and%20Privacy%20Policy.pdf" target="_blank">State Link Policy</a></li>
                <li><a href="http://www.tsl.state.tx.us/trail" target="_blank">Statewide Search</a></li>
                <li><a href="http://veterans.tamu.edu/" target="_blank">Veterans Benefits</a></li>
                <li><a href="https://fch.tamu.edu/programs/military-programs/" target="_blank">Military Families</a></li>
                <li><a href="https://secure.ethicspoint.com/domain/en/report_custom.asp?clientid=19681" target="_blank">Risk, Fraud &amp; Misconduct Hotline</a></li>
                <li><a href="https://gov.texas.gov/organization/hsgd" target="_blank">Texas Homeland Security</a></li>
                <li><a href="http://veterans.portal.texas.gov/">Texas Veteran&apos;s Portal</a></li>
                <li><a href="http://agrilifeas.tamu.edu/hr/diversity/equal-opportunity-educational-programs/" target="_blank">Equal Opportunity</a></li>
                <li class="last"><a href="http://agrilife.org/required-links/orpi/">Open Records/Public Information</a></li>
            </ul></div>';

        echo $output;

    }

    /**
     * Render TAMUS logo
     * @since 1.0
     * @return string
     */
    public static function render_tamus_logo()
    {

        $output = '<div class="footer-container-tamus"><a href="http://tamus.edu/" title="Texas A&amp;M University System"><img class="footer-tamus" src="'.AF_THEME_DIRURL.'/img/footer-tamus-maroon.png" alt="Texas A&amp;M University System Member" /></a></div>';

        echo $output;

    }


}
