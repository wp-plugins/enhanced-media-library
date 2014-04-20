(function($){
	
	if ( typeof wp.media !== 'undefined' )
	{	
		var media = wp.media,
		    l10n  = media.view.l10n;
		
		// Taxonomy AttachmentFilters
		media.view.AttachmentFilters.Taxonomy = media.view.AttachmentFilters.extend({	
			tagName:   'select',
			
			createFilters: function() {
				var filters = {};
				var that = this;
	
				_.each( that.options.termList || {}, function( term, key ) {
					var term_id = term['term_id'];
					var term_name = $("<div/>").html(term['term_name']).text();
					filters[ term_id ] = {
						text: term_name,
						priority: key+2
					};
					filters[term_id]['props'] = {};
					filters[term_id]['props'][that.options.taxonomy] = term_id;
				});
				
				filters.all = {
					text: that.options.termListTitle,
					priority: 1
				};
				filters['all']['props'] = {};
				filters['all']['props'][that.options.taxonomy] = 0;
	
				this.filters = filters;
			}
		});
		
		
		// Enhanced AttachmentBrowser
		
		var curAttachmentsBrowser = media.view.AttachmentsBrowser;
		
		media.view.AttachmentsBrowser = media.view.AttachmentsBrowser.extend({
			createToolbar: function() {
				
				curAttachmentsBrowser.prototype.createToolbar.apply(this,arguments);

				var that = this,
				filters = this.options.filters;
				i = 1;
				$.each(wpuxss_eml_taxonomies, function(taxonomy, values) 
				{
					if ( filters && values.term_list ) 
					{				
						that.toolbar.set( taxonomy+'-filters', new media.view.AttachmentFilters.Taxonomy({
							controller: that.controller,
							model: that.collection.props,
							priority: -80 + 10*i++,
							taxonomy: taxonomy, 
							termList: values.term_list,
							termListTitle: values.list_title,
							className: 'attachment-'+taxonomy+'-filters'
						}).render() );
						
						
					}
				});
			}
		});
	
	}
	
	
	$(document).on('change', 'input[name^="tax_input"]', function() 
	{
		var tax_list = [];
		var parent = $(this).closest('.term-list');
		
		parent.find('input[name^="tax_input"]:checked').each(function(i)
		{
			tax_list[i] = $(this).val();
		});
		
		var tax_string = tax_list.join(', ');
     
     		parent.next('input[name^="attachments"]').val(tax_string).change();
	});
	
})( jQuery );