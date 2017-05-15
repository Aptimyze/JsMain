jQuery.validator.setDefaults({
		errorElement: "div"
					});
$(document).ready(function () {
	$('<input>').attr({type: 'hidden',
				    	name: 'js_variable',
						value:'1'
	}).appendTo('form');
	change_tab_labels('Desktop');
	gender_display_selection();
	$('#reg_phone_mob_mobile').each(function( index, element ) {
		mobileIds.push($(element).attr("id"));
		});
	
	$('select[id^=reg_dtofbirth]').each(function( index, element ) {
		dobIds.push($(element).attr("id"));
		});
	$("#reg").validate({
		
		onkeyup : false,
				onfocusout: function(element) {
					$(element).valid(); 
		},
		highlight: function (element, errorClass, validClass) {
			if(element.name=="reg[gender]")
			{
				$(element.form).find("label[for=reg_gender]").css("color","red");
				
		   }     
			else if($.inArray($(element).attr("id"),mobileIds)!=-1)
			{
				$(element.form).find("label[for=reg_phone_mob]").css("color","red");
			}
			else if($.inArray($(element).attr("id"),phoneIds)!=-1)
			{
				$(element.form).find("label[for=reg_phone_res]").css("color","red");
			}
			else if($.inArray($(element).attr("id"),dobIds)!=-1)
			{
				$(element.form).find("label[for=reg_dtofbirth]").css("color","red");
			}
			else
	        $(element.form).find("label[for=" + element.id + "]").css("color","red");
            
		},
		unhighlight: function (element, errorClass, validClass) {
			if(element.name=="reg[gender]")
				$(element.form).find("label[for=reg_gender]")
		        .css("color","");			
			else if($.inArray($(element).attr("id"),phoneIds)!=-1)
			{
				$(element.form).find("label[for=reg_phone_res]")
			        .css("color","");
			}
			else if($.inArray($(element).attr("id"),mobileIds)!=-1)
			{
				$(element.form).find("label[for=reg_phone_mob]")
			        .css("color","");
			}
			else if($.inArray($(element).attr("id"),dobIds)!=-1)
			{
			 	var validator=this,result = true;
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
				 case 'gender':
				 err_name_str="gender_M_err";
				 for_name_str="reg_gender_M";
				 break;
		  		case 'day':
				case 'month':
				case 'year':
					err_name_str='dtofbirth_err';
					for_name_str='reg_dtofbirth_'+var_name_str;
					break;
				case 'mobile':
					err_name_str='phone_mob_err';
					break;
				case 'landline':
					err_name_str='phone_res_err';
					break;
				default:
				    err_name_str=var_name_str+'_err';
			}
		   	$('#'+err_name_str).attr('for',for_name_str);
		   	$('#'+err_name_str).html(error.html());
			$('#'+err_name_str).css('display','inline');
			return false;
		  }
		});    	
		$("#reg_city_res").rules("add",{
			checkSelectDropDown:true
		});
		$("#reg_caste").rules("add",{
			checkSelectDropDown:true
		});
                $("#reg_jamaat").rules("add",{
			required: true,
		});
                $("#reg_sectMuslim").rules("add",{
			required: true,
		});
		$("#reg_religion").rules("add",{
			checkSelectDropDown:true
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
		$("#reg_password").rules("add",{
		  required : true,
		  commonWords : true,
		  checkWithUserName :true,
		  minlength:8,
		  messages:
		  {
			required: $("#password_required").html(),
			minlength : $("#err_pass_invalid").html()
		  }          
		}); 
		$("#reg_pincode").rules("add",{
		  blankCheck : true,
		  digits : true,
		  minlength:6,
		  maxlength:6,
		  pinInitials : true,
		  messages:
		  {
			blankCheck: $("#err_pin_req").html(),
			pinInitials : SetPinIniMes,
			minlength : $("#err_pin_invalid").html(),
			maxlength : $("#err_pin_invalid").html(),
			digits :  $("#err_pin_invalid").html(),
		  }          
		}); 
		$('input[name="reg[gender]"]').rules("add", {
			required:true,
			messages:
			{
				required:$("#gender_required").html()
			}
		});
		$('input[name="reg[gender]"]').on('blur', function() {
			$("#reg").validate().element( this );
        });
		$("#reg_phone_mob_mobile").rules("add",{
			digits :true,
			MobileNumber :true,
			messages:
			{
				digits : $("#err_mobile_invalid").html()
			}
		});

		$('#reg_phone_res_std').rules("add",{
		  digits:true,
		  messages:
		  {
			digits : $("#err_phone_invalid").html()
		  }
		});
		$('#reg_phone_res_landline').rules("add",{
		  digits:true,
		  PhoneNumber :true,
		  messages:
		  {
			digits : $("#err_phone_invalid").html()
		  }
		});
		$('#reg_password').keyup(function(){
		passwordStrength(["Weak","Good","Strong","Not rated"]); 
		});
		$('#reg_mstatus').rules("add",{
		  mstatusMarried : true,
		  required: true,
		  messages:
		  {
			  required:$("#mstatus_required").html()
		  }
		});  
		$('#reg_dtofbirth_year').rules("add",{
		   required: true,    	  	
		   check_date_of_birth: true,
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
		$('#add_phone').click(function(){
				$('#phone_show').css('display','inline');
				$('#add_phone').css('display','none');
				$('#remove_phone').css('display','inline');
			}
		);
		$('#remove_phone').click(function(){
				$('#phone_show').css('display','none');
				$('#add_phone').css('display','inline');
				$('#remove_phone').css('display','none');
				$('#phone_res_err').css('display','none');
				$('#reg_phone_res_landline').val("");
				
		}
		);
		$("#reg_gender_F").click(function(){
			$("#reg_mstatus option[value='M']").remove();
			$('#have_child_section').css('display','none');
			$("#reg_havechildren").value='';
			updateYear('F');
		});
		$("#reg_gender_M").click(function(){
			if($("#reg_mstatus option[value='M']").length == 0)
			{
				$("#reg_mstatus").append("<option value='M'>Married</option>");
			}
			updateYear('M');
		});
		$('#reg_mstatus').change(function(){
				if(this.value!='N' && this.value!=''){
						$('#have_child_section').css('display','inline');
						 // dID("has_children").focus();
									 //dID("mtongue_submit_err").style.display="none";
					}else
					{
						$('#have_child_section').css('display','none');
						$("#reg_havechildren").value='';
					}
		}
		);
                $('#reg_caste').change(function(){
				if(this.value=='152'){
						$('#jamaat').css('display','inline');
					}else
					{
						$('#jamaat').css('display','none');
                                                $('#reg_jamaat').val('');
					}
		});
                $('#reg_religion').change(function(){
				if(this.value=='2'){
						$('#sectMuslim_section').css('display','inline');
					}else
					{
						$('#sectMuslim_section').css('display','none');
                                                $('#reg_sectMuslim').val('');
                                                $('#jamaat').css('display','none');
                                                $('#reg_jamaat').val('');
					}
		});
		$('#reg_havechild').rules("add",{
			haveChild : true
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
    $('#reg_height').rules("add",{
        required:true,
        messages:
        {
            required:$('#height_required').html()
        }    
    });
    $('#termsandconditions').rules("add",{
        required:true,
        messages:
        {
			required:"<div class=\"spacer1\"></div><div class=\"err\" style=\"margin-left:15px;\">You have to agree to the terms and conditions to proceed.</div>"
        }    
    });
    $("#reg_relationship").change(function(){
	change_tab_labels('Desktop');
	gender_display_selection();
});

	$("#reg_phone_mob_isd").change(function()
	{
		if($.inArray(this.value,isdCodes)!=-1) $("#reg_phone_mob_mobile").attr('maxLength','10');
		else $("#reg_phone_mob_mobile").attr('maxLength','15');
	});
	$("#reg_phone_res_isd").change(function()
	{
                if($.inArray(this.value,isdCodes)!=-1) $("#reg_phone_res_landline").attr('maxLength','10');
                else $("#reg_phone_res_landline").attr('maxLength','15');
        });
var timeOut=3000;
setTimeout(function(){ajax_leadi('M')},timeOut);
});
