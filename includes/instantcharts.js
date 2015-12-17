jQuery(document).ready(function($) {

	// Uploading files
	var file_frame;

	jQuery.fn.upload_sizingchart_image = function( button ) {
		var button_id = button.attr('id');
		var field_id = button_id.replace( '_button', '' );

		// If the media frame already exists, reopen it.
		if ( file_frame ) {
		  file_frame.open();
		  return;
		}

		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
		  title: 'Select a sizing chart',
		  button: {
		    text: 'Use this image',
		  },
		  multiple: false
		});

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
		  var attachment = file_frame.state().get('selection').first().toJSON();
		  jQuery("#"+field_id).val(attachment.id);
		  jQuery("#sizingchartdiv img").attr('src',attachment.url);
		  jQuery( '#sizingchartdiv img' ).show();
		  jQuery( '#' + button_id ).attr( 'id', 'remove_sizingchart_image_button' );
		  jQuery( '#remove_sizingchart_image_button' ).text( 'Remove sizing chart' );
		});

		// Finally, open the modal
		file_frame.open();
	};

	jQuery('#sizingchartdiv').on( 'click', '#upload_sizingchart_image_button', function( event ) {
		event.preventDefault();
		jQuery.fn.upload_sizingchart_image( jQuery(this) );
	});

	jQuery('#sizingchartdiv').on( 'click', '#remove_sizingchart_image_button', function( event ) {
		event.preventDefault();
		jQuery( '#upload_sizingchart_image' ).val( '' );
		jQuery( '#sizingchartdiv img' ).attr( 'src', '' );
		jQuery( '#sizingchartdiv img' ).hide();
		jQuery( this ).attr( 'id', 'upload_sizingchart_image_button' );
		jQuery( '#upload_sizingchart_image_button' ).text( 'Add sizing chart' );
	});

});
