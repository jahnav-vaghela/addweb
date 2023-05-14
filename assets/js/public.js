/*
	Ticker notification 
	public front load js script 
 */


jQuery.noConflict();
jQuery(document).ready(function(){
		
	const form 	= jQuery("form#addweb-filter-form");
	const content_div  	= jQuery("#content");


	if( typeof form === 'object' ){
		form.on('submit', filter_resources_with_ajax);
	}

	function filter_resources_with_ajax(){

		try {
			var dataArray = form.serializeArray();
			console.log(dataArray);

			var data = {
				'action': 'get_filter_html',
				'nonce' : ADDWEB.nonce,
				'dataArray' : dataArray
			};

			jQuery.post( ADDWEB.ajax_url,data, function( res ) {
				
				content_div.html(res);
				
			}).done(function() {
				//console.log( "second success" );
			}).fail(function() {
				//console.log( "error" );
			})
			.always(function() {
				//console.log( "finished" );
			});

			
		} catch (err) {
			console.log(err)
		} finally {
			return false;
		}

		return false;
	}
});