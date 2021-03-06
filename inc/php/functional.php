<?php

/**
 * Prevent Direct Access
 */
defined( 'ABSPATH' ) or die( "Restricted access!" );

/**
 * Generate the button
 * @return string
 */
function spacexchimp_p008_generator() {

    // Put value of plugin constants into an array for easier access
    $plugin = spacexchimp_p008_plugin();

    // Put the value of the plugin options into an array for easier access
    $options = spacexchimp_p008_options();

    // Declare variables
    $transparency = $options['transparency_button'];
    $background_button = $options['background_button'];
    $image_button = $options['image_button'];

    // Generate transparency
    if ( $transparency === true ) {
        $transparency_html = 'ssttbutton-transparent';
    } else {
        $transparency_html = ''; // Empty value
    }

    // Generate button
    ?>
        <a
            id="ssttbutton"
            href="#top"
            class="<?php echo $transparency_html; ?>"
        >
            <span class="fa-stack fa-lg">
                <i class="ssttbutton-background fa <?php echo $background_button; ?> fa-stack-2x"></i>
                <i class="ssttbutton-symbol fa <?php echo $image_button; ?> fa-stack-1x"></i>
            </span>
        </a>
    <?php
}

/**
 * Callback for checking if the current page matches the selected one
 * @return boolean ('true' or 'false')
 */
function spacexchimp_p008_load_on() {

    // Put value of plugin constants into an array for easier access
    $plugin = spacexchimp_p008_plugin();

    // Put the value of the plugin options into an array for easier access
    $options = spacexchimp_p008_options();

    // Declare variables
    $load_on = $options['display-button'];

    // Return 'true' if the current page matches the selected one
    if ( $load_on == '' ) {
        return true;
    } elseif ( $load_on == 'Home Page Only' ) {
        if ( is_home() OR is_front_page() ) {
            return true;
        }
    }

    // Return 'false' if nothing matches
    return false;
}

/**
 * Autoload option
 */
function spacexchimp_p008_autoload() {

    // Check if the current page matches the selected one
    $load_on = spacexchimp_p008_load_on();
    if ( $load_on === true ) {
        spacexchimp_p008_generator();
    }
}

/**
 * Inject the button into the website's frontend (footer section)
 */
add_action( 'wp_footer', 'spacexchimp_p008_autoload', 999 );
