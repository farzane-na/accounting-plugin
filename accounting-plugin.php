<?php
/*
 * Plugin Name:       accounting
 * Plugin URI:        https://github.com/farzane-na/accounting-plugin
 * Description:       A plugin with which you can create online pay slips and also through this plugin you can have an Elementor widget to filter a series of products or post types.
 * Version:           1.0.0
 * Requires PHP:      7.4
 * Author:            Farzane Nazmabadi
 * Author URI:        https://farzanenazmabadi.ir/
 * Update URI:        https://github.com/farzane-na/accounting-plugin
 * Text Domain:       accounting
 * Domain Path:       /languages
 * Requires Plugins:  Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
function translate_plugin() {
    load_plugin_textdomain( 'accounting', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'translate_plugin' );

add_action( 'elementor/init', 'init_custom_elementor_widgets' );
function init_custom_elementor_widgets() {
    require_once __DIR__ . '/widgets/filtering-card-widget.php';

    add_action( 'elementor/widgets/widgets_registered', 'register_custom_widget_elementor' );
}
function register_custom_widget_elementor( $widgets_manager ) {
    $widgets_manager->register( new \Filtering_Card_Widget() );
}
function filtering_card_enqueue_assets() {

    wp_register_style(
        'filtering-card-style',
        plugin_dir_url(__FILE__) . 'assets/style/app.css',
        [],
        '1.0.0'
    );
    wp_register_script(
        'filtering-card-script',
        plugin_dir_url(__FILE__) . 'assets/script/app.js',
        [],
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'filtering_card_enqueue_assets');
