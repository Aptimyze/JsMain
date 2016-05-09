
var checkboxHeight = "25";
var radioHeight = "25";
$(document).ready(function () {
	change_tab_labels('Mobile');
	gender_display_selection(); 
	$("#reg").validate({
		onkeyup : false,
		onfocusout: function(element) {
		$(element).valid(); 
		},
		groups: {
                        date_of_birth: "reg[dtofbirth][day] reg[dtofbirth][month] reg[dtofbirth][year]"
                },
        	errorElement: "div",
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
				default:
				    err_name_str=var_name_str+'_err';
			}
			$('#'+err_name_str).css('display','block');
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
				 autocorrect_mob: true
		});  
		$("#reg_password").rules("add",{
		  required : true,
		  commonWords : true,
		  checkWithUserName :true,
		  minlength:8,
		  messages:
		  {
			required: $("#password_required").html(),
			minlength : $("#err_pass_invalid").html(),
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
		$("#reg_gender_F").click(function(){
			updateYear('F');
		});
		$("#reg_gender_M").click(function(){
			updateYear('M');
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
	change_tab_labels('Mobile');
	gender_display_selection();
	});
$("#reg_phone_mob_isd").change(function()
{
	if($.inArray(this.value,isdCodes)!=-1) $("#reg_phone_mob_mobile").attr('maxLength','10');
	else $("#reg_phone_mob_mobile").attr('maxLength','15');
});
});

var Custom = {
	init: function() {
		var inputs = document.getElementsByName("reg[gender]"), span = Array(), textnode, option, active;
		for(a = 0; a < inputs.length; a++) {
			if((inputs[a].type == "checkbox" || inputs[a].type == "radio") && inputs[a].className == "styled") {
				span[a] = document.createElement("span");
				span[a].className = inputs[a].type;

				if(inputs[a].checked == true) {
					if(inputs[a].type == "checkbox") {
						position = "0 -" + (checkboxHeight*2) + "px";
						span[a].style.backgroundPosition = position;
					} else {
						position = "0 -" + (radioHeight*2) + "px";
						span[a].style.backgroundPosition = position;
					}
				}
				inputs[a].parentNode.insertBefore(span[a], inputs[a]);
				inputs[a].onchange = Custom.clear;
				if(!inputs[a].getAttribute("disabled")) {
					span[a].onmousedown = Custom.pushed;
					span[a].onmouseup = Custom.check;
				} else {
					span[a].className = span[a].className += " disabled";
				}
			}
		}
		
		
		document.onmouseup = Custom.clear;
	},
	pushed: function() {
		element = this.nextSibling;
		if(element){
			element.checked=true;
			$(element).trigger('click');
		}
		if(element.checked == true && element.type == "checkbox") {
			this.style.backgroundPosition = "0 -" + checkboxHeight*3 + "px";
		} else if(element.checked == true && element.type == "radio") {
			this.style.backgroundPosition = "0 -" + radioHeight*3 + "px";
		} else if(element.checked != true && element.type == "checkbox") {
			this.style.backgroundPosition = "0 -" + checkboxHeight + "px";
		} else {
		//	this.style.backgroundPosition = "0 -" + radioHeight + "px";
		}
	},
	check: function() {
		element = this.nextSibling;
		if(element.checked == true && element.type == "checkbox") {
			this.style.backgroundPosition = "0 0";
			element.checked = false;
		} else {
			if(element.type == "checkbox") {
				this.style.backgroundPosition = "0 -" + checkboxHeight*2 + "px";
			} else {
				this.style.backgroundPosition = "0 -" + radioHeight*2 + "px";
				group = this.nextSibling.name;
				inputs = document.getElementsByTagName("input");
				for(a = 0; a < inputs.length; a++) {
					if(inputs[a].name == group && inputs[a] != this.nextSibling) {
						inputs[a].previousSibling.style.backgroundPosition = "0 0";
					}
				}
			}
			element.checked = true;
		}
	},
	
};
window.onload = Custom.init;
