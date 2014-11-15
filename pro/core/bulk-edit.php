<?php




/**
 *  wpuxss_eml_prepare_attachment_for_js
 *
 *  @since    2.0
 *  @created  30/07/14
 */

add_filter( 'wp_prepare_attachment_for_js', 'wpuxss_eml_prepare_attachment_for_js', 10, 2 );

function wpuxss_eml_prepare_attachment_for_js( $response, $attachment )
{    
    foreach ( get_attachment_taxonomies($attachment->ID) as $taxonomy ) 
    {
        $terms = wp_get_object_terms($attachment->ID, $taxonomy);
        
        $values = array();
        
        foreach ( $terms as $term )
            $values[] = $term->slug;

        $response['taxonomies'][$taxonomy] = $values;
    }
    
    return $response;
}




/** 
 *  wpuxss_eml_print_media_templates
 *
 *  @since    2.0
 *  @created  03/08/14
 */

add_action( 'print_media_templates', 'wpuxss_eml_print_media_templates' );

function wpuxss_eml_print_media_templates() { ?>

    <script type="text/html" id="tmpl-attachment-grid-view">
    
        <div class="attachment-preview js--select-attachment type-{{ data.type }} subtype-{{ data.subtype }} {{ data.orientation }}">
            <div class="eml-attacment-inline-toolbar">
                <# if ( data.can.save && data.sizes && data.buttons.edit ) { #>
                    <i class="eml-icon dashicons dashicons-edit edit" data-name="edit"></i>
                <# } #>
            </div>
            <div class="thumbnail">
                <# if ( data.uploading ) { #>
                    <div class="media-progress-bar"><div style="width: {{ data.percent }}%"></div></div>
                <# } else if ( 'image' === data.type && data.sizes ) { #>
                    <div class="centered">
                        <img src="{{ data.size.url }}" draggable="false" alt="" />
                    </div>
                <# } else { #>
                    <div class="centered">
                        <# if ( data.image && data.image.src && data.image.src !== data.icon ) { #>
                            <img src="{{ data.image.src }}" class="thumbnail" draggable="false" />
                        <# } else { #>
                            <img src="{{ data.icon }}" class="icon" draggable="false" />
                        <# } #>
                    </div>
                    <div class="filename">
                        <div>{{ data.filename }}</div>
                    </div>
                <# } #>
            </div>
            <# if ( data.buttons.close ) { #>
                <a class="close media-modal-icon" href="#" title="<?php esc_attr_e('Remove'); ?>"></a>
            <# } #>
        </div>
        <# if ( data.buttons.check ) { #>
            <a class="check" href="#" title="<?php esc_attr_e('Deselect'); ?>" tabindex="-1"><div class="media-modal-icon"></div></a>
        <# } #>
        <#
        var maybeReadOnly = data.can.save || data.allowLocalEdits ? '' : 'readonly';
        if ( data.describe ) {
            if ( 'image' === data.type ) { #>
                <input type="text" value="{{ data.caption }}" class="describe" data-setting="caption"
                    placeholder="<?php esc_attr_e('Caption this image&hellip;'); ?>" {{ maybeReadOnly }} />
            <# } else { #>
                <input type="text" value="{{ data.title }}" class="describe" data-setting="title"
                    <# if ( 'video' === data.type ) { #>
                        placeholder="<?php esc_attr_e('Describe this video&hellip;'); ?>"
                    <# } else if ( 'audio' === data.type ) { #>
                        placeholder="<?php esc_attr_e('Describe this audio file&hellip;'); ?>"
                    <# } else { #>
                        placeholder="<?php esc_attr_e('Describe this media file&hellip;'); ?>"
                    <# } #> {{ maybeReadOnly }} />
            <# }
        } #>
        
    </script>
    
    <script type="text/html" id="tmpl-attachments-details">

        <h3><?php _e( 'Attachments Details', 'eml' ); ?></h3>
        
        <form class="compat-item">
            <table class="compat-attachment-fields">                       
            
            <?php foreach( get_taxonomies_for_attachments() as $taxonomy ) : 
            
                $t = (array) get_taxonomy($taxonomy);
                if ( ! $t['public'] || ! $t['show_ui'] )
                    continue;
                if ( empty($t['label']) )
                    $t['label'] = $taxonomy;
                if ( empty($t['args']) )
                    $t['args'] = array();
                    
                if ( $t['hierarchical'] ) 
                {
                    ob_start();
                    
                        wp_terms_checklist( 0, array( 'taxonomy' => $taxonomy, 'checked_ontop' => false, 'walker' => new Walker_Media_Taxonomy_Checklist() ) );
                        
                        if ( ob_get_contents() != false )
                            $html = '<ul class="term-list">' . ob_get_contents() . '</ul>';
                        else
                            $html = '<ul class="term-list"><li>No ' . $t['label'] . '</li></ul>';
                    
                    ob_end_clean();
                    
                    $t['input'] = 'html';
                    $t['html'] = $html; ?>
                    
                    <tr class="compat-field-<?php echo $taxonomy; ?>">
                        <th scope="row" class="label">
                            <label for="attachments-<?php echo $taxonomy; ?>"><span class="alignleft"><?php echo $t['label']; ?></span><br class="clear" /></label>
                        </th>
                        <td class="field"><?php echo $t['html']; ?></td>
                    </tr>
                
                <?php } ?>
                
            <?php endforeach; ?>
            
            </table>
            
        </form>
        
    </script>

    <script type="text/html" id="tmpl-media-bulk-selection">
    
        <div class="selection-info">
            <span class="count"></span>
            <a class="select-all" href="#"><?php _e( 'Select All', 'eml' ); ?></a>
            <# if ( data.editable ) { #>
                <a class="edit-selection" href="#"><?php _e( 'Edit Selection', 'eml' ); ?></a>
            <# } #>
            <# if ( data.clearable ) { #>
                <a class="clear-selection" href="#"><?php _e( 'Deselect All', 'eml' ); ?></a>
            <# } #>
            <# if ( ! data.uploading ) { #>
                <?php if ( ! MEDIA_TRASH ): ?>
                    <a class="delete-selected" href="#"><?php _e( 'Delete Selected', 'eml' ); ?></a>
                <?php endif; ?>
            <# } #>
        </div>
        
        <div class="selection-view"></div>
        
    </script>
    
<?php }




/** 
 *  wpuxss_eml_save_attachments
 *
 *  @since    2.0
 *  @created  09/08/14
 */
 
add_action( 'wp_ajax_eml-save-attachments', 'wpuxss_eml_save_attachments', 0 );

function wpuxss_eml_save_attachments() {
        
    if ( empty( $_REQUEST['attachments'] ) ) {
        wp_send_json_error();
    }
        
    $attachments = $_REQUEST['attachments'];
    $new_attachments = array();
    
    check_ajax_referer( 'eml-bulk-edit-nonce', 'nonce' );
    
    foreach ( $attachments as $attachment_id ) {
        
        if ( ! current_user_can( 'edit_post', intval($attachment_id) ) ) continue;
        
        if ( ! $attachment = get_post( intval($attachment_id) ) ) continue;
        
        if ( 'attachment' != $attachment->post_type ) continue;
        
        foreach( get_taxonomies_for_attachments() as $taxonomy ) {
            
            if ( isset($_REQUEST['tax_input']) && isset( $_REQUEST['tax_input'][ $taxonomy ] ) ) {
                
                $wp_all_terms = get_terms( $taxonomy, array('hide_empty' => false) );

                $all_terms = array();
                $terms2add = array();
                $terms2remove = array();
                $indeterminate = array();
                
                foreach ( $wp_all_terms as $term ) {
                    $all_terms[$term->slug] = $term->slug;
                }
                    
                $terms = $_REQUEST['tax_input'][ $taxonomy ];
                
                foreach( $terms as $term ) {
                    
                    if ( 'false' === $term[1] ) {
                        $terms2add[] = $term[0];
                    }
                    else {
                        $indeterminate[] = $term[0];
                    }
                }
                
                $terms2remove = array_diff( $all_terms, array_merge( $terms2add, $indeterminate ) );
                
                if( ! empty($terms2remove) ) {
                    wp_remove_object_terms( intval($attachment_id), $terms2remove, $taxonomy );
                }
                    
                if( ! empty($terms2add) ) {
                    wp_set_object_terms( intval($attachment_id), $terms2add, $taxonomy, true );
                }
            }
            else {
                wp_set_object_terms( intval($attachment_id), null, $taxonomy, false );
            }
        }
        
        // TODO: try to reduce fields number of the response
        $new_attachments[$attachment_id] = wp_prepare_attachment_for_js( $attachment_id );
    }
    
    $new_attachments = array_filter( $new_attachments );
    
    wp_send_json_success( $new_attachments );    
}




/** 
 *  wpuxss_eml_remove_attachments
 *
 *  @since    2.0
 *  @created  08/10/14
 */
 
add_action( 'wp_ajax_eml-remove-attachments', 'wpuxss_eml_remove_attachments', 0 );

function wpuxss_eml_remove_attachments() {
        
    if ( empty( $_REQUEST['attachments'] ) ) {
        wp_send_json_error();
    }
        
    $attachments = $_REQUEST['attachments'];
    $removed = array();
    
    check_ajax_referer( 'eml-bulk-edit-nonce', 'nonce' );
    
    foreach ( $attachments as $attachment_id ) {
        
        if ( ! current_user_can( 'delete_post', intval($attachment_id) ) ) continue;
        
        if ( ! $attachment = get_post( intval($attachment_id) ) ) continue;
        
        if ( 'attachment' != $attachment->post_type ) continue;

        $removed[$attachment_id] = wp_delete_attachment( intval($attachment_id) );
    }
    
    $removed = array_filter( $removed );
    
    wp_send_json_success( $removed );    
}


?>