(function($){
	
	if ( typeof wp.media !== 'undefined' )
	{	
		var media = wp.media,
		    l10n  = media.view.l10n;
		
		$.each(wpuxss_eml_taxonomies, function(taxonomy, values) 
		{
		
			// Category AttachmentFilters
			media.view.AttachmentFilters[taxonomy] = media.view.AttachmentFilters.extend({
				tagName:   'select',
				className: 'attachment-'+taxonomy+'-filters',
				
				createFilters: function() {
					var filters = {};
		
					_.each( values.term_list || {}, function( text, key ) {
						
						filters[ key ] = {
							text: text,
							props: {
								taxonomy: taxonomy,
								term_id: key
							}
						};
					});
					
					filters[0] = {
						text:  values.list_title,
						props: {
							taxonomy: taxonomy,
							term_id: 0
						},
						priority: 10
					};
		
					this.filters = filters;
				},
				
				select: function() {
					var model = this.model,
						value = 0,
						props = model.toJSON();
		
					_.find( this.filters, function( filter, id ) {
						var equal = _.all( filter.props, function( prop, key ) {
							return prop === ( _.isUndefined( props[ key ] ) ? null : props[ key ] );
						});
		
						if ( equal )
							return value = id;
					});
		
					this.$el.val( value );
				}
			});
		
		});
		
		// Enhanced AttachmentBrowser
		media.view.AttachmentsBrowser = media.view.AttachmentsBrowser.extend({
			createToolbar: function() {
				var filters, FiltersConstructor;
	
				this.toolbar = new media.view.Toolbar({
					controller: this.controller
				});
	
				this.views.add( this.toolbar );
	
				filters = this.options.filters;
				if ( 'uploaded' === filters )
					FiltersConstructor = media.view.AttachmentFilters.Uploaded;
				else if ( 'all' === filters )
					FiltersConstructor = media.view.AttachmentFilters.All;
	
				if ( FiltersConstructor ) {
					this.toolbar.set( 'filters', new FiltersConstructor({
						controller: this.controller,
						model:      this.collection.props,
						priority:   -80
					}).render() );
				}
				
				var that = this;
				$.each(wpuxss_eml_taxonomies, function(taxonomy, values) 
				{
					if ( filters && values.term_list ) 
					{
						that.toolbar.set( taxonomy+'-filters', new media.view.AttachmentFilters[taxonomy]({
							controller: that.controller,
							model:      that.collection.props,
							priority:   -80
						}).render() );
					}
				});
	
	
				if ( this.options.search ) {
					this.toolbar.set( 'search', new media.view.Search({
						controller: this.controller,
						model:      this.collection.props,
						priority:   60
					}).render() );
				}
	
				if ( this.options.dragInfo ) {
					this.toolbar.set( 'dragInfo', new media.View({
						el: $( '<div class="instructions">' + l10n.dragInfo + '</div>' )[0],
						priority: -40
					}) );
				}
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