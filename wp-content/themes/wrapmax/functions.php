<?php
/**
 * Theme Functions
 *
 * @author Jegstudio
 * @package wrapmax
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

defined( 'WRAPMAX_VERSION' ) || define( 'WRAPMAX_VERSION', '1.0.0' );
defined( 'WRAPMAX_DIR' ) || define( 'WRAPMAX_DIR', trailingslashit( get_template_directory() ) );

defined( 'GUTENVERSE_COMPANION_REQUIRED_VERSION' ) || define( 'GUTENVERSE_COMPANION_REQUIRED_VERSION', '1.0.5' );
defined( 'GUTENVERSE_FRAMEWORK_REQUIRED_VERSION' ) || define( 'GUTENVERSE_FRAMEWORK_REQUIRED_VERSION', '2.0.0' );

require get_parent_theme_file_path( 'inc/autoload.php' );

Wrapmax\Init::instance();
