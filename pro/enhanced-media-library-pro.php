<?php

if ( is_admin() )
{
    include_once( 'core/bulk-edit.php' );
    include_once( 'core/options-pages.php' );
    include_once( 'core/update.php' );
}





/**
 *  wpuxss_eml_pro_admin_enqueue_scripts
 *
 *  @since    2.0
 *  @created  04/09/14
 */

add_action( 'admin_enqueue_scripts', 'wpuxss_eml_pro_admin_enqueue_scripts' );

function wpuxss_eml_pro_admin_enqueue_scripts() {
    
    global $wpuxss_eml_version,
           $wpuxss_eml_dir;
           
           
    $screen = get_current_screen();  
    $media_library_mode = get_user_option( 'media_library_mode', get_current_user_id() ) ? get_user_option( 'media_library_mode', get_current_user_id() ) : 'grid';

    
    if ( 'upload' === $screen->base && 'grid' === $media_library_mode ) {
        
        wp_dequeue_script( 'media' );
        wp_dequeue_script( 'wpuxss-eml-media-grid-script' );
        wp_enqueue_script(
            'wpuxss-eml-pro-media-grid-script',
            $wpuxss_eml_dir . 'pro/js/eml-media-grid.js',
            array( 'wpuxss-eml-media-models-script', 'wpuxss-eml-media-views-script' ),
            $wpuxss_eml_version,
            true
        );
    }

    if ( 'media' === $screen->base && 'add' === $screen->action ) {  
    
        wp_enqueue_media();
        
        if ( class_exists('acf') ) {
            
            acf_enqueue_scripts();
            
            add_action( 'admin_footer', 'wpuxss_eml_admin_footer', 1 );
            
            function wpuxss_eml_admin_footer() {
        
                // render post data
                acf_form_data(array( 
                    'post_id'	=> 0, 
                    'nonce'		=> 'attachment' 
                ));
            }
        }
        
        wp_enqueue_script(
            'wpuxss-eml-pro-media-new-script',
            $wpuxss_eml_dir . 'pro/js/eml-media-new.js',
            array( 'wpuxss-eml-bulk-edit-script' ),
            $wpuxss_eml_version,
            true
        );
    }
    
    
    // admin styles
    wp_enqueue_style( 
        'wpuxss-eml-pro-admin-custom-style', 
        $wpuxss_eml_dir . 'pro/css/eml-pro-admin.css',
        false, 
        $wpuxss_eml_version,
        'all' 
    );
      
}




/**
 *  wpuxss_eml_pro_enqueue_media
 *
 *  @since    2.0
 *  @created 04/09/14
 */
 
add_action( 'wp_enqueue_media', 'wpuxss_eml_pro_enqueue_media' );

function wpuxss_eml_pro_enqueue_media() 
{    

    global $wpuxss_eml_version,
           $wpuxss_eml_dir;


    wp_enqueue_script(
        'wpuxss-eml-bulk-edit-script',
        $wpuxss_eml_dir . 'pro/js/eml-bulk-edit.js',
        array( 'wpuxss-eml-media-models-script', 'wpuxss-eml-media-views-script' ),
        $wpuxss_eml_version,
        true
    );
    
    wp_localize_script( 
        'wpuxss-eml-bulk-edit-script', 
        'wpuxss_eml_pro_bulk_edit_nonce',
        wp_create_nonce( 'eml-bulk-edit-nonce' )
    );
    
    wp_localize_script( 
        'wpuxss-eml-bulk-edit-script', 
        'wpuxss_eml_pro_bulkedit_savebutton_off',
        get_option('wpuxss_eml_pro_bulkedit_savebutton_off')
    );
    
    $i18n_data = array(
        'toolTip_all' => __( 'ALL files belong to this item', 'eml' ),
        'toolTip_some' => __( 'SOME files belong to this item', 'eml' ),
        'toolTip_none' => __( 'NO files belong to this item', 'eml' ),
        'saveButton_success' => __( 'Changes saved.', 'eml' ),
        'saveButton_failure' => __( 'Something went wrong.', 'eml' ),
        'saveButton_text' => __( 'Save Changes', 'eml' ),
        'media_new_close' => __( 'Close', 'eml' ),
        'media_new_title' => __( 'Edit Uploaded Files', 'eml' ),
        'media_new_button' => __( 'Bulk Edit', 'eml' )
    );
    
    wp_localize_script( 
        'wpuxss-eml-bulk-edit-script', 
        'wpuxss_eml_i18n_data',
        $i18n_data
    );
}

?>