/*
 * Attaches the image uploader to the input field
 */
jQuery(document).ready(function($){

	// Instantiates the variable that holds the media library frame.
	var meta_image_frame;
	//Them Slider
	$("#them-slider-button").on("click", clone);
	$("button.remove").on("click", remove);
	
	var regex = /^(.*)(\d)+$/i;
	var cloneIndex = $(".clonedInput").length;

	function clone(){
		$(this).parents(".clonedInput").clone()
	        .appendTo(".bx_wrap")
	        .attr("id", "clonedInput" +  cloneIndex)
	        .find("*")
	        .each(function() {
	            var id = this.id || "";
	            var match = id.match(regex) || [];
	            if (match.length == 3) {
	                this.id = match[1] + (cloneIndex);
	            }
	        })
	        .on('click', 'button.clone', clone)
	        .on('click', 'button.remove', remove);
	    cloneIndex++;
	}
	function remove(){
	    $(this).parents(".clonedInput").remove();
	}
	//Delete images
	$('#delete-image-button').click(function(e){
		e.preventDefault();
		
		$('#bx_image').val('');
		$('.has_image').css('display','none');
		$('.js-image').css('display','none');
	});
	// Runs when the image button is clicked.
	$('#meta-image-button').click(function(e){

		// Prevents the default action from occuring.
		e.preventDefault();

		// If the frame already exists, re-open it.
		if ( meta_image_frame ) {
			meta_image_frame.open();
			return;
		}

		// Sets up the media library frame
		meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
			title: meta_image.title,
			button: { text:  meta_image.button },
			library: { type: 'image' }
		});

		// Runs when an image is selected.
		meta_image_frame.on('select', function(){

			// Grabs the attachment selection and creates a JSON representation of the model.
			var media_attachment = meta_image_frame.state().get('selection').first().toJSON();

			// Sends the attachment URL to our custom image input field.
			$('#bx_image').val(media_attachment.url);
			$('.has_image').css('display','none');
			$('.js-image').css('display','block').append('<img class="has_image" src="'+media_attachment.url+'">');
		});

		// Opens the media library frame.
		meta_image_frame.open();
	});
});
