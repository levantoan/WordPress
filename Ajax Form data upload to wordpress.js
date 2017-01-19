$('.button_apply').on('click',function(){
  if(!formApply.valid()) return false;   
    var form_data = new FormData($('#form_step_wrap_2')[0]);	    	    
  var formValue = formApply.serialize();
  $.ajax({
    type : "post",
    dataType : "json",
    url : devvn_array.admin_ajax,
    data : form_data,
    processData: false,
    contentType: false,
    context: this,
    beforeSend: function(){
      $(this).val('Loading...').prop('disable',true);
    },
    success: function(response){
      /*console.log(response);
      return false;*/
      if(response.success) {	    	
        formApply.resetForm();
        window.location.href = devvn_array.home_url+'/thank-you';
      }else{
        alert('Fail! Please try again later.');
        $(this).val('Apply').removeAttr('disable');
      }
    }
  });
  return false;
});
