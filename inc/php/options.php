<?php

/**
 * Callback function that returns an array with the value of the plugin options
 * @return array
 */
function spacexchimp_p008_options() {

    // Put value of plugin constants into an array for easier access
    $plugin = spacexchimp_p008_plugin();

    // Retrieve options from database
    $options = get_option( $plugin['settings'] . '_settings' );

    // Make the "$options" array if the plugin options data in the database is not exist
    if ( ! is_array( $options ) ) {
        $options = array();
    }

    // Create an array with options
    $array = $options;

    // Set default value if option is empty
    $list = array(
        'hidden_scrollto' => '0',
        'display-button' => '',
        'transparency_button' => '',
        'scroll_duration' => '300',
        'background-color' => '#ff4f7d',
        'symbol-color' => '#fff',
        'size_button' => '32',
        'background_button' => 'fa-circle',
        'image_button' => 'fa-arrow-up',
    );
    foreach ( $list as $name => $default ) {
        $array[$name] = !empty( $options[$name] ) ? $options[$name] : $default;
    }

    // Sanitize data


    // Modify data
    $array['transparency_button'] = ( $array['transparency_button'] == 'on' ) ? true : false ;

    // Return the processed data
    return $array;
}
