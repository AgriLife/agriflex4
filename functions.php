<?php
/**
 * AgriFlex4
 *
 * @package AgriFlex4
 * @since 0.1.0
 * @copyright Copyright (c) 2018, Texas A&M AgriLife
 * @author Zachary K. Watkins
 * @license GPL-2.0+
 */

// Initialize Genesis.
require_once get_template_directory() . '/lib/init.php';
require 'vendor/autoload.php';

// Define some useful constants.
define( 'AF_THEME_DIRNAME', 'agriflex4' );
define( 'AF_THEME_DIRPATH', get_stylesheet_directory() );
define( 'AF_THEME_DIRURL', get_stylesheet_directory_uri() );
define( 'AF_THEME_TEXTDOMAIN', 'agriflex4' );

// Autoload all classes.
require_once AF_THEME_DIRPATH . '/src/class-agriflex.php';
spl_autoload_register( 'AgriFlex::autoload' );
AgriFlex::get_instance();
