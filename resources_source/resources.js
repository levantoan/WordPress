(function($){
	$(document).ready(function(){
		var formLogin = $('#form_login');
		var formRegister = $('#form_register');
		
		function mess_active(active,content,classDiv){
			if(active){
				if(classDiv){
					$('.form_mess').addClass('alert-success').html(content).fadeIn();
				}else{
					$('.form_mess').removeClass('alert-success').html(content).fadeIn();
				}				
			}else{
				$('.form_mess').removeClass(classDiv).fadeOut();
			}
		}
		
		$('.gotoForm').click(function(){
			mess_active(false);
			var formID = $(this).attr('href');			
			if(formID == '#form_login'){
				formRegister.fadeOut('400',function(){
					formLogin.fadeIn();
				});
			}else{
				formLogin.fadeOut('400',function(){
					formRegister.fadeIn();
				});
			}
			return false;
		});
		$('.btn-register').click(function(){
			if(!$('#form_register form').valid()) return false;
			mess_active(false);
			var receive_infor = 0;
			var nonce_register = $('#register_nonce').val();
			var email_register = $('#email_register').val();
			var company_register = $('#company_register').val();
			if ($('#receive_infor_f').is(":checked")){
				receive_infor = 'yes';
			}else{
				receive_infor = 'no';
			}
			$.ajax({
				type : "post",
				dataType : "json",
				url : og_array.ajax_url,
				data : {
					action	: "save_register_infor", 
					email 	: email_register, 
					company	: company_register,
					receive_infor_f : receive_infor,
					nonce	: nonce_register
				},
				context: this,
				beforeSend: function(){
					$('#form_register').addClass('loading');
				},
				success: function(response) {
					//console.log(response);
					if(response.success){
						formRegister.fadeOut('400',function(){
							formLogin.fadeIn();
							mess_active(true,response.data.mess,true);
						});
					}else{
						mess_active(true,response.data.mess,false);
					}
					$('#form_register').removeClass('loading');
				}
			});	
			
			return false;
		});
		$('.btn-login').click(function(){
			if(!$('#form_login form').valid()) return false;
			mess_active(false);
			
			var nonce_register = $('#login_nonce').val();
			var email_register = $('#email_login').val();
			$.ajax({
				type : "post",
				dataType : "json",
				url : og_array.ajax_url,
				data : {
					action	: "user_login_infor", 
					email 	: email_register,
					nonce	: nonce_register
				},
				context: this,
				beforeSend: function(){
					$('#form_login').addClass('loading');
				},
				success: function(response) {
					if(response.success){
						mess_active(true,response.data,true);
						setTimeout(function(){
							location.reload();
						},500);
					}else{
						mess_active(true,response.data,false);
					}
					$('#form_login').removeClass('loading');
				}
			});	
			
			return false;
		});
		$.validator.addMethod("customemail", 
			function(value, element) {
				return /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value);
			}, 
			"Please enter a valid email address."
		);
		$('#form_login form').validate({			 
			onfocusout: function(element) {
			  this.element(element);
			},
			rules: {
			  email_login: {
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
				email_login: "Please enter a valid email address."
			},
			errorElement: "div",
			errorPlacement: function(error, element) {
			  element.after(error);
			}
		});
		$('#form_register form').validate({			 
			onfocusout: function(element) {
			  this.element(element);
			},
			rules: {
				email_register: {
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
				email_login: "Please enter a valid email address."
			},
			errorElement: "div",
			errorPlacement: function(error, element) {
			  element.after(error);
			}
		});
		$('.save_download').click(function(){

			var data_user = $(this).attr('data-user');
			var data_fileid = $(this).attr('data-fileid');
			
			$.ajax({
				type : "post",
				dataType : "json",
				url : og_array.ajax_url,
				data : {
					action	: "count_download",
					user_id : data_user,
					file_id	: data_fileid
				},
				context: this,
				beforeSend: function(){
					//console.log(data_user+'a'+data_fileid);
				},
				success: function(response) {
					//console.log(response);
				}
			});
		});
		$('.login_to_download').click(function(){
			$('#exampleModal').modal();
			return false;
		});
	});
})(jQuery);