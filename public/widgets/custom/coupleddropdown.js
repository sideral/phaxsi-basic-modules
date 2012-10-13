
jQuery(document).ready(function(){
	jQuery('SELECT.coupleddropdown').each(function(){
		var id = jQuery(this).attr('rel');
		var select = this;

		jQuery('#'+id).change(function(){
			jQuery(select).find('option').remove();
			jQuery(select).siblings('img').css('display','inline');

			jQuery.ajax({
				url: jQuery(select).attr('data-url'),
				data: {id: jQuery(this).val()},
				dataType: 'json',
				success: function(data){
					var dataset = data.dataset;

					var output = [];

					if(window['coupled_empty_'+select.id]){
						output.push('<option value="0">'+ window['coupled_empty_'+select.id] +'</option>');
					}

					jQuery.each(dataset, function(key, value)
					{
						output.push('<option value="'+ value[0] +'">'+ value[1] +'</option>');
					});

					jQuery(select).html(output.join(''));

					jQuery(select).siblings('img').css('display','none')

					jQuery(select).change();
				}
			});

		});

	});
});
