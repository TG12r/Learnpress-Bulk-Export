<?php
class LP_Bulk_Export_Admin {
    public function add_plugin_admin_menu() {
        add_menu_page(
            'LP Bulk Export',
            'LP Bulk Export',
            'manage_options',
            'lp-bulk-export',
            array( $this, 'display_plugin_admin_page' ),
            'dashicons-media-document',
            56
        );
    }

    public function enqueue_styles() {
        wp_enqueue_style( 'lpbe-admin-css', LPBE_PLUGIN_URL . 'assets/css/admin.css', array(), '1.0.0', 'all' );
    }

    public function enqueue_scripts() {
        wp_enqueue_script( 'lpbe-admin-js', LPBE_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), '1.0.0', false );
        wp_localize_script( 'lpbe-admin-js', 'lpbe_vars', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'lpbe_nonce' )
        ) );
    }

    public function display_plugin_admin_page() {
        require_once LPBE_PLUGIN_DIR . 'admin/admin-page.php';
    }
}
