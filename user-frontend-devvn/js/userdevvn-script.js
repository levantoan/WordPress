(function($){
	$(document).ready(function(){
		//Thêm validate Email cho Validate jQuery Plugin
		$.validator.addMethod("customemail", 
			function(value, element) {
				return /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value);
			}, 
			"Sorry, I've enabled very strict email validation"
		);
		$("form.devvn_form").validate({
			rules: {
				username: {
					required: true,
				    maxlength: 50
				},
				password: "required",
				register_email: {
					required: {
						depends:function(){
							$(this).val($.trim($(this).val()));
							return true;
						}						
					},
					customemail: true
				},
				user_login: {
					required: {
						depends:function(){
							$(this).val($.trim($(this).val()));
							return true;
						}						
					},
					customemail: true
				}
			},
			messages: {
				username: {
					required: "Please fill username",
				},
				password: "Please fill your password",
				register_email: {
					required: "We need your email address",
					email: "Your email address must be in the format of name@domain.com"
				}
			}
		});
		$('form#devvn_login').on('submit', function (e) {
			if (!$(this).valid()) return false;
			var url = window.location.href.split('?')[0];
			$formValue = $(this).serialize();
			$.ajax({
	            type: 'POST',
	            dataType: 'json',
	            url: userdevvn_object.ajaxurl,
	            data: $formValue,
	            beforeSend: function(){
	            	$('.submit_button').attr('disabled', 'disabled');
	            	$('.devvn-status').html('<p class="devvn-info">Loading...</p>');
	            },
	            success: function (data) {
	            	$('.submit_button').removeAttr('disabled');
	            	if(data.loggedin){
	            		$('form#devvn_login')[0].reset();
		            	$('.devvn-status').html('<p class="devvn-success">'+data.message+'</p>');
		            	setTimeout(function(){
		            		window.location.assign(url);
	            		},1000);
	            	}else{
		            	$('.devvn-status').html('<p class="devvn-error">'+data.message+'</p>');
	            	}
	            }	            
	        });
			e.preventDefault();
		});
		$('form#devvn_register').on('submit', function (e) {
			if (!$(this).valid()) return false;
			var url = window.location.href.split('?')[0];
			$formValue = $(this).serialize();
			$.ajax({
	            type: 'POST',
	            dataType: 'json',
	            url: userdevvn_object.ajaxurl,
	            data: $formValue,
	            beforeSend: function(){
	            	$('.submit_button').attr('disabled', 'disabled');
	            	$('.devvn-status').html('<p class="devvn-info">Loading...</p>');
	            },
	            success: function (data) {
	            	$('.submit_button').removeAttr('disabled');
	            	if(data.loggedin){
	            		$('form#devvn_register')[0].reset();
		            	$('.devvn-status').html('<p class="devvn-success">'+data.message+'</p>');
		            	setTimeout(function(){
		            		window.location.assign(url);
	            		},1000);
	            	}else{
		            	$('.devvn-status').html('<p class="devvn-error">'+data.message+'</p>');
	            	}
	            }	            
	        });
			e.preventDefault();
		});
		$('form#devvn_lost_password2').on('submit', function (e) {
			if (!$(this).valid()) return false;
			var url = window.location.href.split('?')[0];
			$formValue = $(this).serialize();
			$.ajax({
	            type: 'POST',
	            dataType: 'json',
	            url: userdevvn_object.ajaxurl,
	            data: $formValue,
	            beforeSend: function(){
	            	$('.submit_button').attr('disabled', 'disabled');
	            	$('.devvn-status').html('<p class="devvn-info">Loading...</p>');
	            },
	            success: function (data) {
	            	$('.submit_button').removeAttr('disabled');
	            	if(data.loggedin){
	            		$('form#devvn_lost_password')[0].reset();
		            	$('.devvn-status').html('<p class="devvn-success">'+data.message+'</p>');
		            	setTimeout(function(){
		            		window.location.assign(url);
	            		},1000);
	            	}else{
		            	$('.devvn-status').html('<p class="devvn-error">'+data.message+'</p>');
	            	}
	            }	            
	        });
			e.preventDefault();
		});
	})
})(jQuery);