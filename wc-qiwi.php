<?php
/*
    Plugin Name: WooCommerce - Qiwi Payment Gateway
    Plugin URI: https://wordpress.org/plugins/wc-qiwi
    Description: Allows you to use Qiwi payment gateway with the WooCommerce plugin.
    Version: 0.1.0.1
    Author: Mofsy
    Author URI: https://mofsy.ru
    Text Domain: wc-qiwi
    Domain Path: /languages

    Copyright: © 2015-2016 Mofsy.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if(!defined('ABSPATH'))
{
    exit;
}

add_action('plugins_loaded', 'woocommerce_qiwi_init', 0);

function woocommerce_qiwi_init()
{
    /**
     * Main check
     */
    if (!class_exists('WC_Payment_Gateway'))
    {
        return;
    }
    if(class_exists('WC_Qiwi'))
    {
        return;
    }

    /**
     * Define plugin url
     */
    define('WC_QIWI_URL', plugin_dir_url(__FILE__));

    /**
     * Load language
     */
    load_plugin_textdomain( 'wc-qiwi',  false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

    /**
     * Check status
     */
    if( class_exists('WooCommerce_Payment_Status') )
    {
        add_filter( 'woocommerce_valid_order_statuses_for_payment', array( 'WC_Qiwi', 'valid_order_statuses_for_payment' ), 52, 2 );
    }

    /**
     * Gateway class load
     */
    include_once dirname(__FILE__) . '/class-wc-qiwi.php';

    /**
     * Add the gateway to WooCommerce
     **/
    function woocommerce_add_qiwi_gateway($methods)
    {
        $methods[] = 'WC_Qiwi';

        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_qiwi_gateway');
}

/**
 * Get WooCommerce version
 *
 * @return mixed
 */
function woocommerce_qiwi_get_version()
{
    if ( ! function_exists( 'get_plugins' ) )
    {
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    $plugin_folder = get_plugins( '/' . 'woocommerce' );
    $plugin_file = 'woocommerce.php';

    if(isset( $plugin_folder[$plugin_file]['Version'] ))
    {
        return $plugin_folder[$plugin_file]['Version'];
    }

    return null;
}