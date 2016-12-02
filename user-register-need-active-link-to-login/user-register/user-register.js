(function($){
	$(document).ready(function(){
		var fromRegister = $('.register_form_action');
		$.validator.addMethod("customemail2", 
			function(value, element) {
				return /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value);
			}, 
			"Sorry, I've enabled very strict email validation"
		);
		$('input[name="tel"]').mask("999-999-9999");
		fromRegister.validate({
			rules: {
				password: {
					required: true,
				    minlength: 6
				},
				re_password: {
					required: true,
					minlength: 6,
					equalTo: '.password'
				},
				firstname: 'required',
				lastname: 'required',
				company: 'required',
				address: 'required',
				city: 'required',
				state_province: 'required',
				postcode: 'required',
				country: 'required',
				email: {
					required: {
						depends:function(){
							$(this).val($.trim($(this).val()));
							return true;
						}						
					},
					customemail2: true
				},
				tel: 'required',				
				industry: 'required',				
				preferred_language: 'required',				
			},
			messages: {
				password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 6 characters long"
				},
				confirm_password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 6 characters long",
					equalTo: "Please enter the same password as above"
				},
				email: {
					required: "We need your email address",
					email: "Your email address must be in the format of name@domain.com"
				}
			},
			focusInvalid: false,
		    invalidHandler: function(form, validator) {
		        
		        if (!validator.numberOfInvalids())
		            return;
		        
		        $('html, body').animate({
		            scrollTop: $(validator.errorList[0].element).offset().top - 100
		        }, 500);
		        
		    }
		});
		fromRegister.bind('submit',function(){			
			if(!fromRegister.valid()) return;
			var formLoading = false;
			if(formLoading) return; //chỉ cho phép chạy đăng ký 1 lần khi ấn submit
			
			var form_data = $(this).serialize();
			
			$.ajax({
				type : "post",
				dataType : "json",
				url : devvn.ajaxurl,
				data : form_data,
				context: this,
				beforeSend: function(){
					formLoading = true;
					$(this).find('.submit_wrap').addClass('loading');
					$(this).find('input[type="submit"]').attr('disabled', 'disabled');
					$('.register_mess').removeClass('error').html('');
				},
				success: function(response) {
					$(this).find('input[type="submit"]').removeAttr('disabled');
					$(this).find('.submit_wrap').removeClass('loading');
					if(response.success){
						$(fromRegister)[0].reset();
						$('.register_mess').html(response.data);
						setTimeout(function(){
		            		window.location.assign(devvn.home_url);
	            		},1500);
					}else{						
						$('.register_mess').addClass('error').html(response.data);
					}	
				}
			})
			return false;
		});
	})
})(jQuery)