<?php




/**
 *  wpuxss_eml_pro_on_admin_init
 *
 *  @since    2.0
 *  @created  01/10/14
 */
 
add_action( 'admin_init', 'wpuxss_eml_pro_on_admin_init' );

function wpuxss_eml_pro_on_admin_init() {

    // plugin settings: bulk edit
    register_setting( 
        'wpuxss_eml_pro_bulkedit', //option_group
        'wpuxss_eml_pro_bulkedit_savebutton_off', //option_name
        'wpuxss_eml_pro_bulkedit_savebutton_off_validate'
    );
    
    // plugin settings: updates
    register_setting( 
        'wpuxss_eml_pro_updates', //option_group
        'wpuxss_eml_pro_license_key', //option_name
        'wpuxss_eml_pro_license_key_validate'
    );
}




/**
 *  wpuxss_eml_pro_bulkedit_savebutton_off_validate
 *
 *  @type     callback function
 *  @since    2.0
 *  @created  01/10/14
 */ 
 
function wpuxss_eml_pro_bulkedit_savebutton_off_validate( $input ) {

    $input = ( $input == 1 ) ? 1 : 0;
    
    return $input;
}




/**
 *  wpuxss_eml_pro_license_key_validate
 *
 *  @type     callback function
 *  @since    2.0
 *  @created  13/10/14
 */

function wpuxss_eml_pro_license_key_validate( $input ) {
    
    $url = 'http://wordpressuxsolutions.com/downloads/plugins/enhanced-media-library-pro/';

    $args = array(
        'timeout' => 15,
        'body' => array(
            'action' => 'activate-license',
            'key' => $input
        )
    );
    
    $request = wp_remote_post( $url, $args );
    $is_active = maybe_unserialize( wp_remote_retrieve_body( $request ) );
    
    if ( ! $is_active )  {
        
        $input = '';
    }
    
    return $input;
}





/**
 *  wpuxss_eml_pro_admin_menu
 *
 *  @since    2.0
 *  @created  01/10/14
 */

add_action('admin_menu', 'wpuxss_eml_pro_admin_menu');

function wpuxss_eml_pro_admin_menu() {
    
    $eml_bulkedit_options_suffix = add_submenu_page(
        'eml-taxonomies-options', 
        __('Bulk Edit','eml'), 
        __('Bulk Edit','eml'), 
        'manage_options', 
        'eml-bulkedit-options',
        'wpuxss_eml_pro_print_bulkedit_options' 
    );
    
    $eml_updates_options_suffix = add_plugins_page(
        __('EML Updates','eml'), 
        __('EML Updates','eml'), 
        'manage_options', 
        'eml-updates-options',
        'wpuxss_eml_pro_print_updates_options' 
    );
}





/**
 *  wpuxss_eml_pro_print_bulkedit_options
 *
 *  @type     callback function
 *  @since    2.0
 *  @created  01/10/14
 */

function wpuxss_eml_pro_print_bulkedit_options() {
    
    if ( ! current_user_can( 'manage_options' ) ) { 
        wp_die( __('You do not have sufficient permissions to access this page.','eml') ); 
    } ?>
    
    <div id="wpuxss-eml-global-options-wrap" class="wrap">    

        <h2><?php _e('Bulk Edit','eml'); ?></h2>
        
        <?php settings_errors(); ?>
        
        <div id="poststuff">
        
            <div id="post-body" class="metabox-holder columns-2">

                <div id="postbox-container-2" class="postbox-container">
                
                    <form method="post" action="options.php" id="wpuxss-eml-form-bulkedit">
    
                        <?php settings_fields( 'wpuxss_eml_pro_bulkedit' ); ?>
                        <?php do_settings_sections( 'wpuxss_eml_pro_bulkedit' ); ?>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Turn off \'Save Changes\' button','eml'); ?></th>
                                <td> 
                                    <fieldset>
                                        <legend class="screen-reader-text"><span><?php _e('Turn off \'Save Changes\' button','eml'); ?></span></legend>
                                        <label for="wpuxss_eml_pro_bulkedit_savebutton_off"><input name="wpuxss_eml_pro_bulkedit_savebutton_off" type="checkbox" id="wpuxss_eml_pro_bulkedit_savebutton_off" value="1" <?php checked( true, get_option('wpuxss_eml_pro_bulkedit_savebutton_off'), true ); ?> /> <?php _e('Save changes on the fly','eml'); ?></label>
                                        <p class="description"><?php _e( 'Any click on a taxonomy checkbox during media files bulk edition will lead to an <strong style="color:red">immediate saving</strong> of the data. Please, be careful! You have much greater chance to <strong style="color:red">accidentally perform wrong re-assigning</strong> of a lot of your media files / taxonomies with this option turned on.', 'eml' ); ?></p>
                                        <p class="description"><?php _e( 'Strongly NOT recommended option if you work with more than hundred of files at a time.', 'eml' ); ?></p>
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                                             
                        <?php submit_button(); ?>
    
                    </form>
                    
                </div>
                
                <div id="postbox-container-1" class="postbox-container">
                
                    <?php wpuxss_eml_print_credits(); ?>
                
                </div>
            
            </div>
            
        </div>
        
    </div>
 
    <?php
}





/**
 *  wpuxss_eml_pro_print_updates_options
 *
 *  @type     callback function
 *  @since    2.0
 *  @created  13/10/14
 */
 
function wpuxss_eml_pro_print_updates_options() {
    
    if ( ! current_user_can( 'manage_options' ) ) { 
        wp_die( __('You do not have sufficient permissions to access this page.','eml') ); 
    } ?>
    
    <div id="wpuxss-eml-global-options-wrap" class="wrap">    

        <h2><?php _e('Updates','eml'); ?></h2>
        
        <?php settings_errors(); ?>
        
        <div id="poststuff">
        
            <div id="post-body" class="metabox-holder columns-2">

                <div id="postbox-container-2" class="postbox-container">
                
                    <form method="post" action="options.php" id="wpuxss-eml-form-updates">
                    
                        <?php settings_fields( 'wpuxss_eml_pro_updates' ); ?>
                        
                        <div class="postbox">
                            <h3 class="hndle"><?php _e('Enhanced Media Library PRO License','eml'); ?></h3>
                            
                            <?php 
                            $license_key = get_option( 'wpuxss_eml_pro_license_key', '' );
                            $site_transient = get_site_transient('update_plugins');
                            $plugin_basename = 'enhanced-media-library-pro/enhanced-media-library.php';
                            $url = 'http://wordpressuxsolutions.com/downloads/plugins/enhanced-media-library-pro/';
                            $args = array(
                                'action' => 'update',
                                'key' => $license_key
                            ); ?>
                            
                            <div class="inside">
                            
                                <?php if ( empty( $license_key ) ) : 
                                    if ( isset( $site_transient->response[$plugin_basename] ) ) {
                                        $site_transient->response[$plugin_basename]->package = '';
                                    }
                                    
                                    if ( isset( $site_transient->no_update[$plugin_basename] ) ) {
                                        $site_transient->no_update[$plugin_basename]->package = '';
                                    }
                                    
                                    set_site_transient( 'update_plugins', $site_transient ); ?>
                            
                                    <p><?php _e('To unlock updates, please enter your license key below. You can see your license key in <a href="http://wordpressuxsolutions.com/account/">Your Account</a>. If you don\'t have a licence key, your are welcome to <a href="http://wordpressuxsolutions.com/pricing/">purchase it</a>.','eml'); ?></p>
                                    
                                    <table class="form-table">
                                        <tr>
                                            <th scope="row"><label for="wpuxss_eml_pro_license_key"><?php _e('License Key','eml'); ?></label></th>
                                            <td> 
                                                <?php if ( isset( $_GET['settings-updated'] ) ) : ?>
                                                    <p class="error">Something goes wrong. Please try again.</p>
                                                <?php endif; ?>
                                                
                                                <input name="wpuxss_eml_pro_license_key" type="text" id="wpuxss_eml_pro_license_key" value="" />
                                                <?php submit_button( __('Activate License','eml') ); ?>
                                            </td>
                                        </tr>
                                    </table>
                                
                                <?php else : 
                                    
                                    if ( isset( $site_transient->response[$plugin_basename] ) ) {
                                        $site_transient->response[$plugin_basename]->package = $url . '?' . build_query( $args );
                                    }
                                    
                                    if ( isset( $site_transient->no_update[$plugin_basename] ) ) {
                                        $site_transient->no_update[$plugin_basename]->package = $url . '?' . build_query( $args );
                                    }
                                    
                                    set_site_transient( 'update_plugins', $site_transient ); ?>
                                
                                    <p><?php _e('Your license is active!','eml'); ?></p>
                                
                                <?php endif; ?>

                            </div>
                        </div>
                        
                    </form>
                
                </div>
                
                <div id="postbox-container-1" class="postbox-container">
                
                    <?php wpuxss_eml_print_credits(); ?>
                
                </div>
                
            </div>
            
        </div>
        
    </div>
    
    <?php
}

?>