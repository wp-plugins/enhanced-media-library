window.wp = window.wp || {};

( function( $, _ ) {
    
    var ids = [],
        media = wp.media;
        
    
    
    
    media.view.MediaFrame.emlBulkEdit = media.view.MediaFrame.Select.extend({
        
        initialize: function() {
            
            _.defaults( this.options, {
                multiple : true,
                editing  : false,
                state    : 'library',
                mode     : [ 'eml-bulk-edit' ]
            });
            
            media.view.MediaFrame.Select.prototype.initialize.apply( this, arguments );
        },
        
        createStates: function() {
            
            var options = this.options;

            // Add the default states.
            this.states.add([
                // Main states.
                new media.controller.Library({
                    library       : media.query( options.library ),
                    title         : options.title,
                    priority      : 20,
                    
                    multiple      : options.multiple,
                    
                    content       : 'browse',
                    menu          : false,
                    router        : options.router,
                    toolbar       : 'bulk-edit',
                    
                    searchable    : options.searchable,
                    filterable    : options.filterable,

                    editable      : false,
                    
                    allowLocalEdits: true,
                    displaySettings: true,
                    displayUserSettings: true
                })
            ]);
        },
        
        bindHandlers: function() {

            media.view.MediaFrame.Select.prototype.bindHandlers.apply( this, arguments );

            this.on( 'toolbar:create:bulk-edit', this.createToolbar, this );
            this.on( 'toolbar:render:bulk-edit', this.bulkEditToolbar, this );
        },
        
        selectionStatusToolbar: function( view ) {

            view.set( 'selection', new media.view.Selection({
                controller: this,
                collection: this.state().get('selection'),
                priority:   -40,
            }).render() );
        },
        
        bulkEditToolbar: function( view ) {
            
            var controller = this;

            this.selectionStatusToolbar( view );

            view.set( 'bulk-edit', {
                
                style    : 'primary',
                priority : 80,
                text     : wpuxss_eml_i18n_data.media_new_close,

                click: function() {
                    controller.close();
                }
            });
        },
        
    });


    
    function emlUploadSuccess(fileObj, serverData) {
        
        serverData = serverData.replace(/^<pre>(\d+)<\/pre>$/, '$1');
        
        if ( serverData ) {
            ids.push( serverData );
        }
    }
    
    function emlUploadComplete(up, files) {
        
        if ( ! $('.eml-bulk-edit-button-container').length ) {
        
            $('.media-upload-form').after('<div class="eml-bulk-edit-button-container"><a href="#" class="button media-button button-primary button-large eml-bulk-edit-button">'+wpuxss_eml_i18n_data.media_new_button+'</a></div>');
        }
    }
    
    function emlUploadStart() {
        
        if ( $('.eml-bulk-edit-button-container').length ) {
            $('.eml-bulk-edit-button-container').remove();
        }
    }
    
    
    
    $( document ).ready( function() {
        
        var frame;
        
        uploader.bind('FilesAdded', function( up, files ) {
            emlUploadStart();
        });
        
        uploader.bind('FileUploaded', function(up, file, response) {
            emlUploadSuccess(file, response.response);
        });
        
        uploader.bind('UploadComplete', function() {
            emlUploadComplete();
        });
        
        
        
        $( document ).on( 'click', '.eml-bulk-edit-button', function( event ) {
            
            if ( event ) {
                event.preventDefault();
            }
            
            if ( ! _.isUndefined( frame ) ) {
                delete frame;
            }
            
            frame = media.frame = new media.view.MediaFrame.emlBulkEdit({
                title         : wpuxss_eml_i18n_data.media_new_title,
                library       : { post__in: ids },
                router        : false,
                searchable    : false,
                filterable    : false,
                uploader      : false
            }).open();
        });
    });
    
})( jQuery, _ );