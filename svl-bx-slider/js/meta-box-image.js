/*
 * Attaches the image uploader to the input field
 */
jQuery(document).ready(function($){
	//Sortable
	$('.bx_wrap').sortable();
	// Instantiates the variable that holds the media library frame.
	var meta_image_frame;
	//Them Slider
	$("#them-slider-button").on("click", clone);
	$("body").on("click","[id*=delete-slider-button]", remove);
	var cloneIndex = $(".clonedInput").length;

	function clone(){
		//console.log($("#clonedInput0").clone());
		$(".bx_wrap .slider_list").first().clone()
	        .appendTo(".bx_wrap")
	        .attr("id", "clonedInput" +  cloneIndex)
	        .attr('slider-data',cloneIndex)
	        .find("*")
	        .each(function() {
	            $(".has_image",this).attr('src',meta_image.dir+meta_image.default_img);
	            $(this).not(':input[type=button], :input[type=submit], :input[type=reset]').val('');
	            if($(this).prop("id").length > 0){
	            	var id = $(this).attr('id');
	            	var classImage = $(this).attr('class');
	            	if(classImage == 'bx_image'){
	            		$(this).attr('id','bx_image'+cloneIndex);
	            	}else{
	            		$(this).attr('id',id+cloneIndex);
	            	}
	            }
	        });
	    cloneIndex++;
	}
	function remove(){
		var cloneIndex = $(".clonedInput").length;
		if(cloneIndex > 1){
			$(this).parents(".clonedInput").fadeOut(300, function() { 
		    	$(this).remove();
		    })
		}else{
			alert('BxSlider ít nhất phải có 1 slider');
		}
	    
	}
	//Delete images
	$('body').on('click','[id*=delete-image-button]',function(e){
		e.preventDefault();
		var data_slider = $(this).parent().parent().attr('slider-data');
		$('#bx_image'+data_slider).val('');
		$('#clonedInput'+data_slider+' .js-image').html('');
	});
	// Runs when the image button is clicked.
	$('body').on('click','[id*=meta-image-button]',function(e){

		// Prevents the default action from occuring.
		e.preventDefault();
		var data_slider = $(this).parent().parent().attr('slider-data');
		// Sets up the media library frame
		meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
			title: meta_image.title,
			button: { text:  meta_image.button },
			library: { type: 'image' },
			multiple: false
		});

		// Runs when an image is selected.
		meta_image_frame.on('select', function(){
			// Grabs the attachment selection and creates a JSON representation of the model.
			var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
			// Sends the attachment URL to our custom image input field.
			$('#bx_image'+data_slider).val(media_attachment.url);
			$('#clonedInput'+data_slider+' .js-image').html('<img class="has_image" src="'+media_attachment.url+'">');
		});
		// Opens the media library frame.
		meta_image_frame.open();
	});
});