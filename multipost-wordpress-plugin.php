<?php
/*
* Plugin Name:       Multipost Wordpress Eklentisi
* Plugin URI:        https://multipost.com.tr
* Description:       Multipost sistemi için wordpress işlemlerini yapan bir eklenti.
* Version:           0.1.0
* Author:            Ahmet Eren YILDIRIM
* Author URI:        https://ahmeterenyildirim.com.tr/
* Update URI:        https://multipost.com.tr/wordpres-plugin/
*/
 

/**
 * Returns the version of this plugin.
 */
function get_multipost_plugin_version($request) {
    return rest_ensure_response( "0.1.0" );
}

add_action( 'rest_api_init', 
    function () {
        register_rest_route('multipost/v1', '/version', 
            [
                'methods' => 'GET',
                'callback' => 'get_multipost_plugin_version',
            ]
        );
    }
);


/**
 * Get a list of avaible custom fields for post. Empties are allowed, hiddens are not allowed.
 * Source: https://stackoverflow.com/questions/54014822/is-there-a-way-to-get-all-registered-meta-fields-in-a-post-types
 */
function get_avaible_custom_fields($data) {
    global $wpdb;

    $query = "
        SELECT DISTINCT($wpdb->postmeta.meta_key) 
        FROM $wpdb->posts 
        LEFT JOIN $wpdb->postmeta 
        ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
        WHERE $wpdb->posts.post_type = '%s' AND $wpdb->postmeta.meta_key NOT RegExp '(^[_0-9].+$)'
    ";
    $meta_keys = $wpdb->get_col($wpdb->prepare($query, "post"));

    return rest_ensure_response( $meta_keys );
}
add_action( 'rest_api_init', 
    function () {
        register_rest_route('multipost/v1', '/custom_fields', 
            [
                'methods' => 'GET',
                'callback' => 'get_avaible_custom_fields',
            ]
        );
    }
);


/**
 * Set a list of custom fields for a post. 
 */
function set_custom_fields_for_post($request) {
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'rest_not_logged_in', 'You are not logged in.', array( 'status' => 401 ) );
    }

    $arr = $request->get_param("fields");
    foreach($arr as $key => $value){
        update_post_meta( $request->get_param( 'id' ), $key, $value );
    }

    return rest_ensure_response( true );
}
add_action( 'rest_api_init', 
    function () {
        register_rest_route('multipost/v1', '/set_custom_fields', 
            [
                'methods' => 'POST',
                'callback' => 'set_custom_fields_for_post',
            ]
        );
    }
);