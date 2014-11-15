<?php


/**
 *  wpuxss_eml_pro_site_transient_update_plugins
 *
 *  @since    2.0
 *  @created  13/10/14
 */
 
add_filter ('pre_set_site_transient_update_plugins', 'wpuxss_eml_pro_site_transient_update_plugins');

function wpuxss_eml_pro_site_transient_update_plugins( $transient )
{    
    global $wpuxss_eml_version;
    
    $url = 'http://wordpressuxsolutions.com/downloads/plugins/enhanced-media-library-pro/';
    
    $args = array(
        'timeout' => 15,
        'body' => array(
            'action' => 'get-version',
        )
    );
    
    $request = wp_remote_post( $url, $args );
    $new_version = maybe_unserialize( wp_remote_retrieve_body( $request ) );
    
    if ( version_compare( $new_version, $wpuxss_eml_version, '<=' ) ) {
        
        $info = new stdClass();
        
        $info->slug = 'enhanced-media-library-pro';
        $info->plugin = 'enhanced-media-library-pro/enhanced-media-library.php';
        $info->new_version = $new_version;
        $info->url = 'http://wordpressuxsolutions.com/enhanced-media-library/';
        $info->package = '';
        
        $transient->no_update['enhanced-media-library-pro/enhanced-media-library.php'] = $info;
        
        return $transient;
    }
    
    if ( ! empty( $new_version ) ) {
        
        $license_key = get_option( 'wpuxss_eml_pro_license_key', '' );
        
        $args = array(
            'action' => 'update',
            'key' => $license_key
        );
    
        $info = new stdClass();
        
        $info->slug = 'enhanced-media-library-pro';
        $info->plugin = 'enhanced-media-library-pro/enhanced-media-library.php';
        $info->new_version = $new_version;
        $info->url = 'http://wordpressuxsolutions.com/enhanced-media-library/';
        
        if ( ! empty( $license_key ) ) {
            $info->package = $url . '?' . build_query( $args );
        } else {
            $info->package = '';
        }
        
        $transient->response['enhanced-media-library-pro/enhanced-media-library.php'] = $info;
    }
    return $transient;
}




/**
 *  wpuxss_eml_pro_plugins_api
 *
 *  @since    2.0
 *  @created  13/10/14
 */
 
add_filter('plugins_api', 'wpuxss_eml_pro_plugins_api', 10, 3);

function wpuxss_eml_pro_plugins_api( $res, $action, $args ) {
    
    if ( ! isset( $args->slug ) || $args->slug != 'enhanced-media-library-pro' ) {
        return $res;
    }
    
    // getting info from the free version
    if ( $args->slug == 'enhanced-media-library-pro' ) {
        $args->slug = 'enhanced-media-library';
    }
    
    $url = $http_url = 'http://api.wordpress.org/plugins/info/1.0/';
    if ( $ssl = wp_http_supports( array( 'ssl' ) ) ) {
        $url = set_url_scheme( $url, 'https' );
    }

    $args = array(
        'timeout' => 15,
        'body' => array(
            'action' => $action,
            'request' => serialize( $args )
        )
    );
    $request = wp_remote_post( $url, $args );
    
    if ( $ssl && is_wp_error( $request ) ) {
        trigger_error( __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ) . ' ' . __( '(WordPress could not establish a secure connection to WordPress.org. Please contact your server administrator.)' ), headers_sent() || WP_DEBUG ? E_USER_WARNING : E_USER_NOTICE );
        $request = wp_remote_post( $http_url, $args );
    }
    
    if ( is_wp_error($request) ) {
        $res = new WP_Error('plugins_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ), $request->get_error_message() );
    } else {
        $res = maybe_unserialize( wp_remote_retrieve_body( $request ) );
        if ( ! is_object( $res ) && ! is_array( $res ) )
        $res = new WP_Error('plugins_api_failed', __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="https://wordpress.org/support/">support forums</a>.' ), wp_remote_retrieve_body( $request ) );
    }
    
    // getting info from the PRO version
    $url = 'http://wordpressuxsolutions.com/downloads/plugins/enhanced-media-library-pro/';
    
    $args = array(
        'timeout' => 15,
        'body' => array(
            'action' => 'get-info',
        )
    );

    $request = wp_remote_post( $url, $args );
    
    $body = maybe_unserialize( wp_remote_retrieve_body( $request ) );
    
    if ( ! empty($body) ) {
        
        $license_key = get_option( 'wpuxss_eml_pro_license_key', '' );
        
        $args = array(
            'action' => 'update',
            'key' => $license_key
        );

        $res->name          = $body->name; 
        $res->slug          = $body->slug; 
        $res->version       = $body->version; 
        $res->requires      = $body->requires; 
        $res->tested        = $body->tested; 
        $res->added         = $body->added; 
        $res->last_updated  = $body->last_updated; 
        $res->downloaded    = $body->downloaded;
        
        if ( ! empty( $license_key ) ) {
            $res->download_link = $url . '?' . build_query( $args );
        } else {
            $res->package = '';
        }
    }
    
    return $res;
}



/**
 *  wpuxss_eml_pro_in_plugin_update_message
 *
 *  @since    2.0
 *  @created  13/10/14
 */
 
add_action('in_plugin_update_message-enhanced-media-library-pro/enhanced-media-library.php', 'wpuxss_eml_pro_in_plugin_update_message', 10, 2 );

function wpuxss_eml_pro_in_plugin_update_message( $plugin_data, $r ) {
    
    $license_key = get_option( 'wpuxss_eml_pro_license_key', '' );
    
    if ( ! empty( $license_key ) ) {
        
        return;
    }

    echo '<br />' . sprintf( 
        __('To unlock updates, please enter your license key on the <a href="%s">Updates</a> page. You can see your license key in <a href="%s">Your Account</a>. If you don\'t have a licence key, your are welcome to <a href="%s">purchase it</a>.', 'eml'),
        admin_url('plugins.php?page=eml-updates-options'),
        'http://wordpressuxsolutions.com/account/',
        'http://wordpressuxsolutions.com/pricing/'
    );
}

?>