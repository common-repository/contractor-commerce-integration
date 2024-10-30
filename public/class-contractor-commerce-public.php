<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.contractorcommerce.com/
 * @since      1.0.0
 *
 * @package    Contractor_Commerce
 * @subpackage Contractor_Commerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Contractor_Commerce
 * @subpackage Contractor_Commerce/public
 * @author     PJ Hile <pjhile@samedaysupply.com>
 */
class Contractor_Commerce_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Contractor_Commerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Contractor_Commerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/contractor-commerce-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Contractor_Commerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Contractor_Commerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/contractor-commerce-public.js', array( 'jquery' ), $this->version, false );

	}

    public function content_filter( $content ) {
        $options = get_option( 'concom_settings' );

        if ( get_the_ID() == $options['concom_select_field_1'] ) {
            $page_name = get_page_uri();

            $historyMode = '';

            if (isset(get_option( 'concom_settings' )['concom_seo_checkbox']) ) {
                $historyMode .= 'history-mode="' . $page_name . '"';
            }

            if (isset(get_option( 'concom_settings' )['not_place_in_head']) ) {
                return '<div id="concom-shop" ' . $historyMode . '></div><script async defer src="https://plugin.contractorcommerce.com?key=' . $options['concom_text_field_0'] . '"></script>';
            }

            return '<div id="concom-shop" ' . $historyMode . '></div>';
        }

        return $content;
    }

    public function concom_script() {
        $options = get_option( 'concom_settings' );

        if (isset(get_option( 'concom_settings' )['not_place_in_head']) ) {
            return;
        }

        if (get_the_ID() == $options['concom_select_field_1'] ) {
            echo '<script async defer src="https://plugin.contractorcommerce.com?key=' . $options['concom_text_field_0'] . '"></script>';
        }
    }
}
