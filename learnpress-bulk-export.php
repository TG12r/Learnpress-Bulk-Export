<?php
/**
 * Plugin Name: LearnPress Bulk Export
 * Description: Export LearnPress user progress to PDF in bulk.
 * Version: 1.0.0
 * Author: Tomás Hoyos <tomashoyosd9@gmail.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'LPBE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'LPBE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include FPDF
if ( ! class_exists( 'FPDF' ) ) {
    require_once LPBE_PLUGIN_DIR . 'includes/fpdf/fpdf.php';
}

require_once LPBE_PLUGIN_DIR . 'includes/class-lp-bulk-export-loader.php';

function lpbe_init() {
    $loader = new LP_Bulk_Export_Loader();
    $loader->run();
}
add_action( 'plugins_loaded', 'lpbe_init' );
