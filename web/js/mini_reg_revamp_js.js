var user_login =1;
var dobIds = [];
jQuery.validator.setDefaults({
		errorElement: "div"
					});

function ajaxLeadCapture()
{
	var data1={"email":$('#reg_email').val(),"mobile":$('#reg_phone_mob_mobile').val(),"source":$('#reg_source').val()};
	var url1="/register/minireglead";
	$.ajax({
		type: 'POST',
		url: url1,
		data: data1,
	 });
}

$(document).ready(function () {
		$('select[id^=reg_dtofbirth]').each(function( index, element ) {
		dobIds.push($(element).attr("id"));
		});
		$("#minireg").validate({
		onkeyup : false,
				onfocusout: function(element) {
					$(element).valid(); 
		},  
		highlight: function (element, errorClass, validClass) {
			if($(element).attr("id")=="reg_phone_mob_mobile")
			{
				$(element.form).find("label[for=reg_phone_mob]")
			        .css("color","red");
			}
			else if($.inArray($(element).attr("id"),dobIds)!=-1)
			{
				$(element.form).find("label[for=reg_dtofbirth]")
			        .css("color","red");
			}
			else
	        $(element.form).find("label[for=" + element.id + "]")
	        .css("color","red");
            
		},
		unhighlight: function (element, errorClass, validClass) {
		
			if($(element).attr("id")=="reg_phone_mob_mobile")
			{
				$(element.form).find("label[for=reg_phone_mob]")
			        .css("color","");
			}
			else if($.inArray($(element).attr("id"),dobIds)!=-1)
			{
				var validator = this,result = true;
				$(element).siblings("select").each(function (idx, el) {
            				if (validator.invalid[el.name] !== undefined) {
                				result = false;
           				 }
   				});
				if(result)
				{
					$(element.form).find("label[for=reg_dtofbirth]")
			        	.css("color","");
				}	
			}
			else
	        $(element.form).find("label[for=" + element.id + "]")
	        .css("color","");
	    },
		groups: {
                        date_of_birth: "reg[dtofbirth][day] reg[dtofbirth][month] reg[dtofbirth][year]"
                },
	    errorPlacement: function(error, element) {
		   var var_name=element.attr('name');
		   var var_name_str=var_name.substring(var_name.lastIndexOf("[")+1,var_name.lastIndexOf("]"));
		   var err_name_str='';
		   var for_name_str='reg_'+var_name_str;
		   switch(var_name_str){
		  		case 'day':
				case 'month':
				case 'year':
					err_name_str='dtofbirth_err';
					for_name_str='reg_dtofbirth_'+var_name_str;
					break;
				case 'mobile':
					err_name_str='phone_mob_err';
					break;
			}
			$('#'+err_name_str).css('display','inline');
			$('#'+err_name_str).attr('for',for_name_str);
			$('#'+err_name_str).html(error.html());
		  }
		
		});    	
		
		$("#reg_email").rules("add", {
				required: true,
				messages: {
						required: $("#email_required").html()
				},
				 emailPattern: true,
				 invalidDomain: true,
				 autocorrect: true
		});  
		  
		$("#reg_phone_mob_mobile").rules("add",{
			required: true,    	  	
			MobileNumber : true,
			digits :true,
			messages:
			{
				required:$("#err_mobile_invalid").html(),
				digits : $("#err_mobile_invalid").html()
			}
		});

		$('#reg_dtofbirth_year').rules("add",{
		   required: true,    	  	
		   check_date_of_birth_minreg: true,
		  messages:
		  {
			required : $("#dtofbirth_required").html()
		  }
		});  
		$('#reg_dtofbirth_month').rules("add",{
		   required: true,    	  	
		  messages:
		  {
			required : $("#dtofbirth_required").html()
		  }
		});  
		$('#reg_dtofbirth_day').rules("add",{
		   required: true,    	  	
		  messages:
		  {
			required : $("#dtofbirth_required").html()
		  }
		});  
		
		$('#reg_mtongue').rules("add",{
			required:true,
			messages:
			{
				required:$('#mtongue_required').html()
			}    
		});
    $('#reg_relationship').rules("add",{
        required:true,
        messages:
        {
            required:$('#relationship_required').html()
        }    
    });
	$("#reg_relationship").change(function(){
		change_tab_labels('MinReg');
		var gender = updateGender(this.value);
		updateYear(gender);
	});   
});
