<?php
class LP_Bulk_Export_Loader {
    public function run() {
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_ajax_hooks();
    }

    private function load_dependencies() {
        require_once LPBE_PLUGIN_DIR . 'includes/class-lp-bulk-export-admin.php';
        require_once LPBE_PLUGIN_DIR . 'includes/class-lp-bulk-export-ajax.php';
    }

    private function define_admin_hooks() {
        $plugin_admin = new LP_Bulk_Export_Admin();
        add_action( 'admin_menu', array( $plugin_admin, 'add_plugin_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ) );
    }

    private function define_ajax_hooks() {
        $plugin_ajax = new LP_Bulk_Export_Ajax();
        add_action( 'wp_ajax_lpbe_search_users', array( $plugin_ajax, 'search_users' ) );
        add_action( 'wp_ajax_lpbe_get_user_courses', array( $plugin_ajax, 'get_user_courses' ) );
        add_action( 'wp_ajax_lpbe_generate_pdf', array( $plugin_ajax, 'generate_pdf' ) );
    }
}
