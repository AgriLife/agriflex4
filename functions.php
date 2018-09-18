<?php
/**
 * AgriFlex4
 * @package      AgriFlex4
 * @since        1.0.0
 * @copyright    Copyright (c) 2018, Texas A&M AgriLife
 * @author       Zachary K. Watkins
 * @license      GPL-2.0+
 */

// Initialize Genesis
require_once TEMPLATEPATH . '/lib/init.php';

// Define some useful constants
define( 'AF_THEME_DIRNAME', 'agriflex4' );
define( 'AF_THEME_DIRPATH', get_stylesheet_directory() );
define( 'AF_THEME_DIRURL', get_stylesheet_directory_uri() );
define( 'AF_THEME_TEXTDOMAIN', 'agriflex4' );

// Autoload all classes
spl_autoload_register( 'AgriFlex::autoload' );
