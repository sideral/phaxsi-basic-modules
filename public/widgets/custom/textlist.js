jQuery(document).ready(function(){
	
	jQuery('DIV.phaxsi-textlist a').click(function(){

		var count = jQuery('DIV.phaxsi-textlist INPUT').length;

		var max = jQuery('DIV.phaxsi-textlist').attr('data-max');
		var callback = eval(jQuery('DIV.phaxsi-textlist').attr('data-callback'));
		var name = jQuery('DIV.phaxsi-textlist').attr('data-name');

		jQuery('DIV.phaxsi-textlist DIV').append('<input type="text" class="form_input_text" value="" name="'+name+'['+count+']" /><br/>');
		
		if(callback){
			callback(jQuery('DIV.phaxsi-textlist INPUT:last'));
		}	

		if(max == count+1){
			$(this).hide();
		}

	});
});
