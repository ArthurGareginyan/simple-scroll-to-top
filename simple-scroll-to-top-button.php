<?php
/**
 * Plugin Name: Simple Scroll to Top Button
 * Plugin URI: https://github.com/ArthurGareginyan/simple-scroll-to-top-button
 * Description: Easily add cross browser "Scroll to Top" button to your website. It will be responsive and compatible with all major browsers. It will work with any theme!
 * Author: Arthur Gareginyan
 * Author URI: http://www.arthurgareginyan.com
 * Version: 3.1
 * License: GPL3
 * Text Domain: simple-scroll-to-top-button
 * Domain Path: /languages/
 *
 * Copyright 2016 Arthur Gareginyan (email : arthurgareginyan@gmail.com)
 *
 * This file is part of "Simple Scroll to Top Button".
 *
 * "Simple Scroll to Top Button" is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * "Simple Scroll to Top Button" is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with "Simple Scroll to Top Button".  If not, see <http://www.gnu.org/licenses/>.
 *
 */


/**
 * Prevent Direct Access
 *
 * @since 0.1
 */
defined('ABSPATH') or die("Restricted access!");

/**
 * Define global constants
 *
 * @since 3.1
 */
defined('SSTOPB_DIR') or define('SSTOPB_DIR', dirname(plugin_basename(__FILE__)));
defined('SSTOPB_BASE') or define('SSTOPB_BASE', plugin_basename(__FILE__));
defined('SSTOPB_URL') or define('SSTOPB_URL', plugin_dir_url(__FILE__));
defined('SSTOPB_PATH') or define('SSTOPB_PATH', plugin_dir_path(__FILE__));
defined('SSTOPB_VERSION') or define('SSTOPB_VERSION', '3.1');

/**
 * Register text domain
 *
 * @since 2.0
 */
function ssttbutton_textdomain() {
	load_plugin_textdomain( 'simple-scroll-to-top-button', false, SSTOPB_DIR . '/languages/' );
}
add_action( 'init', 'ssttbutton_textdomain' );

/**
 * Print direct link to Simple Scroll to Top Button admin page
 *
 * Fetches array of links generated by WP Plugin admin page ( Deactivate | Edit )
 * and inserts a link to the Simple Scroll to Top Button admin page
 *
 * @since  2.0
 * @param  array $links Array of links generated by WP in Plugin Admin page.
 * @return array        Array of links to be output on Plugin Admin page.
 */
function ssttbutton_settings_link( $links ) {
	$settings_page = '<a href="' . admin_url( 'options-general.php?page=simple-scroll-to-top-button.php' ) .'">' . __( 'Settings', 'simple-scroll-to-top-button' ) . '</a>';
	array_unshift( $links, $settings_page );
	return $links;
}
add_filter( "plugin_action_links_".SSTOPB_BASE, 'ssttbutton_settings_link' );

/**
 * Register "Scroll to Top" submenu in "Settings" Admin Menu
 *
 * @since 2.0
 */
function ssttbutton_register_submenu_page() {
	add_options_page( __( 'Scroll to Top', 'simple-scroll-to-top-button' ), __( 'Scroll to Top', 'simple-scroll-to-top-button' ), 'manage_options', basename( __FILE__ ), 'ssttbutton_render_submenu_page' );
}
add_action( 'admin_menu', 'ssttbutton_register_submenu_page' );

/**
 * Attach Settings Page
 *
 * @since 3.0
 */
require_once( SSTOPB_PATH . 'inc/php/settings_page.php' );

/**
 * Load scripts and style sheet for settings page
 *
 * @since 3.1
 */
function ssttbutton_load_scripts_admin($hook) {

    // Return if the page is not a settings page of this plugin
    if ( 'settings_page_simple-scroll-to-top-button' != $hook ) {
        return;
    }

    // Style sheet
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style( 'ssttbutton-admin-css', SSTOPB_URL . 'inc/css/admin.css' );
    wp_enqueue_style( 'ssttbutton-font-awesome-css', SSTOPB_URL . 'inc/lib/font-awesome-4.5.0/css/font-awesome.min.css', 'screen' );
    wp_enqueue_style( 'ssttbutton-bootstrap', SSTOPB_URL . 'inc/css/bootstrap.css' );
    wp_enqueue_style( 'ssttbutton-bootstrap-theme', SSTOPB_URL . 'inc/css/bootstrap-theme.css' );

    // JavaScript
    wp_enqueue_script( 'ssttbutton-admin-js', SSTOPB_URL . 'inc/js/admin.js', array('wp-color-picker'), false, true );
    wp_enqueue_script( 'ssttbutton-back-to-top-button', SSTOPB_URL . 'inc/js/smoothscroll.js', array('jquery'), false, true );
    wp_enqueue_script( 'ssttbutton-bootstrap-checkbox', SSTOPB_URL . 'inc/js/bootstrap-checkbox.min.js' );

}
add_action( 'admin_enqueue_scripts', 'ssttbutton_load_scripts_admin' );

/**
 *  Load scripts and style sheet for front end of website
 *
 * @since 3.1
 */
function ssttbutton_load_scripts_frontend() {

    // Read options from BD
    $options = get_option( 'ssttbutton_settings' );

    // Enqueue script and style sheet of button on front end
    if ( !empty($options['enable_button']) AND $options['enable_button'] == 'ON' ) {

        if ( $options['display-button'] == '' || $options['display-button'] == 'Home Page Only' && is_home() || $options['display-button'] == 'Home Page Only' && is_front_page() ) {

            // Style sheet
            wp_enqueue_style( 'ssttbutton-font-awesome-css', SSTOPB_URL . 'inc/lib/font-awesome-4.5.0/css/font-awesome.min.css', 'screen' );
            wp_enqueue_style( 'ssttbutton-front-css', SSTOPB_URL . 'inc/css/front.css' );

            // JavaScript
            wp_enqueue_script( 'ssttbutton-back-to-top-button', SSTOPB_URL . 'inc/js/smoothscroll.js', array('jquery'), false, true );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'ssttbutton_load_scripts_frontend' );

/**
 * Register settings
 *
 * @since 0.1
 */
function ssttbutton_register_settings() {
	register_setting( 'ssttbutton_settings_group', 'ssttbutton_settings' );
}
add_action( 'admin_init', 'ssttbutton_register_settings' );

/**
 * Generate the CSS of button from options and add it to head section of website
 *
 * @since 3.0
 */
function ssttbutton_css_options() {

    // Read options from BD and declare variables
    $options = get_option( 'ssttbutton_settings' );

    if (!empty($options['background-color'])) {
        $backgroun_color = $options['background-color'];
    } else {
        $backgroun_color = "#000000";
    }

    if (!empty($options['symbol-color'])) {
        $symbol_color = $options['symbol-color'];
    } else {
        $symbol_color = "#ffffff";
    }

    if (!empty($options['size_button'])) {
        $size_button = $options['size_button'];
    } else {
        $size_button = "32";
    }

    ?>
        <style type="text/css">
            #ssttbutton {
                <?php if ( !empty($options['transparency_button']) AND $options['transparency_button'] == 'on' ) {
                    echo '
                        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
                        filter: alpha(opacity=50);
                        -moz-opacity: .5;
                        -khtml-opacity: .5;
                        opacity: .5;
                    ';
                } ?>
                font-size: <?php echo $size_button; ?>px;
            }
            .ssttbutton-background {
                color: <?php echo $backgroun_color; ?>;
            }
            .ssttbutton-symbol {
                color: <?php echo $symbol_color; ?>;
            }
        </style>
    <?php
}
add_action( 'wp_head' , 'ssttbutton_css_options' );

/**
 * Add DIV container with button to footer.
 *
 * @since 1.0
 */
function ssttbutton_add_container() {

    // Read options from BD and declare variables
    $options = get_option( 'ssttbutton_settings' );
    
    ?>
        <a id="ssttbutton" href="#top">
            <span class="fa-stack fa-lg">
                <i class="ssttbutton-background fa <?php if ( !empty( $options['background_button'] ) ) { echo $options['background_button']; } else { echo 'fa-circle'; }  ?> fa-stack-2x"></i>
                <i class="ssttbutton-symbol fa <?php if ( !empty( $options['image_button'] ) ) { echo $options['image_button']; } else { echo 'fa-hand-o-up'; }  ?> fa-stack-1x"></i>
            </span>
        </a>
    <?php
}
add_action( 'wp_footer', 'ssttbutton_add_container', 999 );

/**
 * Delete options on uninstall
 *
 * @since 0.1
 */
function ssttbutton_uninstall() {
    delete_option( 'ssttbutton_settings' );
}
register_uninstall_hook( __FILE__, 'ssttbutton_uninstall' );

?>