<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.contractorcommerce.com/
 * @since             1.0.0
 * @package           Contractor_Commerce
 *
 * @wordpress-plugin
 * Plugin Name:       Contractor Commerce Integration
 * Plugin URI:        https://bitbucket.org/hvactechnologies/contractor-commerce-integration
 * Description:       Adds Contractor Commerce to your site.
 * Version:           1.1.6
 * Author:            Contractor Commerce
 * Author URI:        https://www.contractorcommerce.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       contractor-commerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

global $conComActivePageQuery;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CONTRACTOR_COMMERCE_VERSION', '1.1.6' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-contractor-commerce-activator.php
 */
function activate_contractor_commerce() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-contractor-commerce-activator.php';
    Contractor_Commerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-contractor-commerce-deactivator.php
 */
function deactivate_contractor_commerce() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-contractor-commerce-deactivator.php';
    Contractor_Commerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_contractor_commerce' );
register_deactivation_hook( __FILE__, 'deactivate_contractor_commerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-contractor-commerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_contractor_commerce() {

    $plugin = new Contractor_Commerce();
    $plugin->run();

}

function isPluginSetup ( $defaultReturn = null ) {
    if ( get_option( 'concom_settings' ) && ! isset( get_option( 'concom_settings' )['concom_select_field_1'] ) ) {
        return $defaultReturn;
    }
    
    if ( ! isset(get_option( 'concom_settings' )['concom_seo_checkbox']) ) {
        return $defaultReturn;
    }
}

function contractor_commerce_exlude_query_vars( $query_vars ) {
    isPluginSetup( $query_vars );
    
    $uri = get_page_uri( get_option( 'concom_settings' )['concom_select_field_1']);
    
    if ( isset($query_vars['pagename']) && $query_vars['pagename'] === $uri ) {
        unset($query_vars['page'], $query_vars['year']);
    }
    
    return $query_vars;
}

add_filter( 'request', 'contractor_commerce_exlude_query_vars' );

function contractor_commerce_rewrite_rules() {
    isPluginSetup();
    
    $shop_page_name = get_post(get_option( 'concom_settings' )['concom_select_field_1'])->post_name;
    
    add_rewrite_rule('^' . $shop_page_name . '(?:/([^/]+))?(?:/([^/]+))?(?:/([^/]+))?/?$', 'index.php?pagename=' . $shop_page_name, 'top');
}

add_action( 'init', 'contractor_commerce_rewrite_rules' );

add_action ( 'init', function () {  
    isPluginSetup();
    
    $shop_page_name = get_post(get_option( 'concom_settings' )['concom_select_field_1'])->post_name;
    
    wp_register_sitemap_provider( 
        $shop_page_name, 
        new class extends \WP_Sitemaps_Provider {
            public function __construct() {
                $this->name = get_post(get_option( 'concom_settings' )['concom_select_field_1'])->post_name;
            }
            public function get_url_list( $page_num, $post_type = '' ) {
                return [
                    ['loc' => home_url('/' . $this->name . '/services/')],
                    ['loc' => home_url('/' . $this->name . '/products/')],
                ];
            }
            public function get_max_num_pages( $subtype = '' ) {
                return 1;
            }
        }
    );
} );

add_action( 'wp_head', function () {
    isPluginSetup();
    
    if ( get_the_ID() == get_option( 'concom_settings' )['concom_select_field_1'] ) {
        echo '<link rel="canonical" href="' . home_url($_SERVER['REQUEST_URI']) . '">';
    }
} );

run_contractor_commerce();
