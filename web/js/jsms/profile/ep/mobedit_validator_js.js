/*Function to trim specified characters from left*/
function ltrim(str, chars)
{
        chars = chars || "\\s";
        return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

var isdCodes = ["0", "91","+91"];

var mesIdTrue = 0;
var email = '';
var isd_regex = /^([0-9]{1,3})$/;///^[+]?[0-9]+$/;
var phonePatternIndia = /^([7-9]{1}[0-9]{9})$/;
var phonePatternOther = /^([1-9]{1}[0-9]{5,13})$/;
var smallCase_regex = /^[a-z]+$/;
var upperCase_regex = /^[A-Z]+$/;
var specialChars_regex = /^.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]+$/;
var email_regex = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
var digit_regex = /^[0-9]+$/;
//used when user selects "Looking for" to change selected gender.



jQuery.validator.setDefaults({
		errorElement: "div"
					});

// invalid domain check on email
jQuery.validator.addMethod("invalidDomain", function(value, element) {
	
	if($(element).attr("id") == "EMAIL" || $(element).attr("id") == "ALT_EMAIL")
	{
		var idVal = "#"+$(element).attr("id");
		var email=$.trim($(idVal).val());
	}	
	var invalidDomainArr = new Array("jeevansathi", "dontreg","mailinator","mailinator2","sogetthis","mailin8r","spamherelots","thisisnotmyrealemail","jsxyz","jndhnd");
	var start = value.indexOf('@');
	var end = value.lastIndexOf('.');
	var diff = end-start-1;
	var user = value.substr(0,start);
	var len = user.length;

	var domain = value.substr(start+1,diff).toLowerCase();
	if(jQuery.inArray(domain.toLowerCase(),invalidDomainArr) !=  -1)
		return false;
	else if(domain == 'gmail')
	{
		if(!(len >= 6 && len <=30))
			return false;
	}
	else if(domain == 'yahoo' || domain == 'ymail' || domain == 'rocketmail' )
	{
		if(!(len >= 4 && len <=32))
			return false;
	}
	else if(domain == 'rediff')
	{
		if(!(len >= 4 && len <=30))
			return false;
	}
	else if(domain == 'sify')
	{
		if(!(len >= 3 && len <=16))
			return false;
	}

	return true;
});

// regex pattern check on email
jQuery.validator.addMethod("emailPattern", function(value, element) {
	
	if(($(element).attr("id") == "EMAIL") || ($(element).attr("id") == "ALT_EMAIL" && value!=""))
	{
		var idVal = "#"+$(element).attr("id");		
	}
	else if($(element).attr("id") == "ALT_EMAIL" && value=="")
	{
		return true;
	}	
	   var email=$.trim($(idVal).val());
       if(!email_regex.test(email))
			return false;
        else
			return true;

});


// regex pattern check on name
jQuery.validator.addMethod("validate_name", function (value, element){
			var name_of_user=value;
			var allowed_chars = /^[a-zA-Z\.\,\s\']+$/;
			var name_of_user_invalid_chars = 0;
			if($.trim(name_of_user)!= "" && !allowed_chars.test(name_of_user))
			{
				return false;
			}
			else			
				return true;	
},"Please provide a valid Name");

// check if email and alternate email are same
jQuery.validator.addMethod("sameEmail", function (value, element){
	if($("#EMAIL").val().toLowerCase() == $("#ALT_EMAIL").val().toLowerCase() && ( $("#ALT_EMAIL").val().length > 0 ))
	{
		return false;
    }
    else
    return true;	
},"Both Emails are same");



var nameError = {"noSpace":"Please provide your first name along with surname, not just the first name","invalidChars":"Please provide a valid Full Name"};
var telNumberErrorNo = '';
jQuery.validator.addMethod("validate_custom_name", function (value, element){
			var name_of_user=value;

			var name = name_of_user.replace(/\./gi, " ");
			name = name.replace(/dr|ms|mr|miss/gi, "");
			name = name.replace(/\,|\'/gi, "");
			name = $.trim(name.replace(/\s+/gi, " "));

                        var allowed_chars = /^[a-zA-Z\s]+([a-zA-Z\s]+)*$/i;
			if($.trim(name)!= "" && !allowed_chars.test(trim(name)))
			{
                                telNumberErrorNo =  "invalidChars";
                                return false;
			}
			else{	
                                var nameArr = name.split(" ");
                                if(nameArr.length<2){
                                        telNumberErrorNo =  "noSpace";
                                        return false;
                                }else{
                                        return true;
                                }
                        }
},function(){return nameError[telNumberErrorNo];});
jQuery.validator.addMethod("MobileNumberVerify", function(value,element) {
        return (checkMobile(0));
        });
// mobile number pattern check on mobile phone
jQuery.validator.addMethod("MobileNumber", function(value,element) {
	return ((value=='' && $('#PHONE_RES').val().length>0) || checkMobile(0));
	});
function StateCityRequired(json){
	if(json[1].value=="51" && json[2].value=='')
		jsonError[jsonError.length]="Provide a valid state";
	else if((json[1].value=="51" && json[2].value!='' && json[3].value=='')|| json[1].value=="128" && json[3].value=='')
		jsonError[jsonError.length]="Provide a valid city";
	else
		return true;
	return false;	
}
function jamaatRequired(json){
	if(json[1].value=="152" && json[2].value=='')
	{
		jsonError[jsonError.length]="Provide a valid jamaat";
		return false;
	}
	return true;
}
// mobile or landline number should be there
jQuery.validator.addMethod("landlineOrMobileNumber", function(value,element) {
	if(($('#PHONE_MOB').val()=="" && $('#PHONE_RES').val()==""))
		return false;
	else
		return true;
	});
//Mobile And Alt Number should not be same
jQuery.validator.addMethod("sameAsPhoneNumber", function(value,element) {
	if(($('#PHONE_MOB').val()== $('#ALT_MOBILE').val()) && $('#PHONE_MOB').val()!="")
		return false;
	else
		return true;
	});
// mobile number pattern check on alternate mobile phone
jQuery.validator.addMethod("AltMobileNumber", function(value,element) {
	if($('#ALT_MOBILE').val())
		return( checkMobile(1));
	else
		return true;
	});
var proofError = {"docRequired":"Please attach Divorced Decree","invalidDoc":"Invalid file format"};
var NumberErrorNo = '';
jQuery.validator.addMethod("MstatusChange", function(value,element) {
	var MSTATUS = $('#MSTATUS').attr("value");
        if(MSTATUS == "D"){
                var MSTATUS_PROOF = $('#file_keyMSTATUS_PROOF')[0];
                if(typeof MSTATUS_PROOF.files == 'undefined' || typeof MSTATUS_PROOF.files[0] == 'undefined' || MSTATUS_PROOF.files[0] == null){
                        NumberErrorNo =  "docRequired";
                        return false;
                }
                var file = MSTATUS_PROOF.files[0];
                if (file && file.name.split(".")[1] == "jpg" || file.name.split(".")[1] == "JPG" || file.name.split(".")[1] == "jpeg" || file.name.split(".")[1] == "JPEG" || file.name.split(".")[1] == "PDF" || file.name.split(".")[1] == "pdf") {
                        NumberErrorNo =  "invalidDoc";
                } else {
                        return false;
                }
                if(file.size > 5242880) {
                        NumberErrorNo =  "invalidDoc";
                        return false;
                }
                return true;
        }else{
                return true;
        }
},function(){return proofError[NumberErrorNo];});

//check ISd
jQuery.validator.addMethod("isdCode", function(value,element) {
	value = value.replace(/^[0]+/g,"");
	return isd_regex.test(value);
});

// function for mobile/alternate number pattern check on mobile phone
function checkMobile(alt)
{
	if(alt)
	{
		var mobileISD = $("#ALT_ISD");
		var mobileNumber = $("#ALT_MOBILE");
	}
	else{
		var mobileISD = $("#ISD");
		var mobileNumber = $("#PHONE_MOB");
	}
	if($.inArray(mobileISD.val(),isdCodes)!= -1 && (mobileNumber.val().length!=10 || !phonePatternIndia.test(mobileNumber.val())))
	{
		errorIndex = 0;
		return false;
	}
	else if(mobileNumber.val().length<6 || mobileNumber.val().length>14 || !phonePatternOther.test(mobileNumber.val()))
	{
		errorIndex = 0;
		return false;
	}
	else if(mobileISD.val() == '')
	{
		errorIndex = 2;
		return false;
	}
	return true;
}
// residence landline number pattern check on landline phone
jQuery.validator.addMethod("PhoneNumber", function(value,element) {
		if (!checkPhone()) {
			return false;
		}
		else {
			return true
		}
	});
// function for lanldine number pattern check on lanldine phone
function checkPhone()
{
	var landlineISD = $("#ISD");
	var landlineSTD = $('#STD');
	var landlineNumber = $('#PHONE_RES');
	if(landlineISD.val() == '' && landlineNumber.val())
	{
		errorIndex = 3;
		return false;
	}
	else if($.inArray(landlineISD.val(),isdCodes) != -1)
	{
		if(landlineSTD.val() == ''  && landlineNumber.val().length !=0 )
		{
			errorIndex = 4;
			return false;
		}
		else if(ltrim(landlineSTD.val(),'0').concat(landlineNumber.val()).length != 10 && landlineNumber.val().length !=0 )
		{
			errorIndex = 4;
			return false;
		}
	}
	else if($.inArray(landlineISD.val(),isdCodes) == -1)
	{
		if((landlineSTD.val().concat(landlineNumber.val()).length < 6 || landlineSTD.val().concat(landlineNumber.val()).length >14) && landlineNumber.val().length !=0 )
		{
			errorIndex = 4;
			return false;
		}
	}
	else if(landlineISD.val() && !isd_regex.test(landlineISD.val()))
	{
		errorIndex = 4;
		return false;
	}
	return true;
}

var jsonError=[];
function validator(tabKey){
	validatorFormId= tabKey;
	jsonError=[];
		var validator=$("#"+tabKey).validate({
				onkeyup : false,
				onfocusout:false,
				errorPlacement: function (error, element) {
				//console.log((element.attr("id")));
				//console.log((error.text()));
				jsonError[jsonError.length]=error.text();
				},
				//debug:true;
				
				});	
	if(tabKey=="SuitableTimetoCall" || tabKey=="NameoftheProfileCreator" || tabKey=="EmailId" || tabKey=="MobileNo" || tabKey=="AlternateMobileNo" || tabKey=="LandlineNo" || tabKey == "AlternateEmailId")
	{
		$("#PROFILE_HANDLER_NAME").rules("add", {
			validate_name: true,
			messages:
			{
			validate_name: "Please provide a valid Name",
			}
		});
			$("#PROFILE_HANDLER_NAME").rules("remove", "required");


		$("#EMAIL").rules("add", {
			required: false, 
			invalidDomain: true,
			emailPattern: true,
			messages:
			{
				invalidDomain:"Please provide a valid Email Id",
				emailPattern:"Please provide a valid Email Id"
			}		
		});

		$("#ALT_EMAIL").rules("add", {
			//required: false, 
			invalidDomain: true,
			emailPattern: true,
			sameEmail:true,	
			messages:
			{
				invalidDomain:"Please provide a valid Alternate Email Id",
				emailPattern:"Please provide a valid Alternate Email Id"
			}	
		});
		
		$("#ISD").rules("add", {
			digits:true,
			isdCode :true,
			messages:
			{
			digits: "Please provide a valid ISD code",
			isdCode : "Please provide a valid ISD code"
			}
									
			});
		$("#STD").rules("add", {
			digits:true,
			messages:
			{
			digits: "Please provide a valid STD code"
			} 	
		});
		$("#PHONE_MOB").rules("add", {
			landlineOrMobileNumber:true,
			digits :true,
			MobileNumber :true,
			
			messages:
			{
			digits: "Provide a valid mobile number",
			MobileNumber: "Provide a valid mobile number",
			landlineOrMobileNumber:"Provide either a mobile number or a landline number",
			} 	
		});
			$("#PHONE_MOB").rules("remove", "required");

		$("#ALT_MOBILE").rules("add", {
			digits:true,
			AltMobileNumber :true,
			sameAsPhoneNumber: true,
			messages:
			{
			digits: "Provide a valid alternate mobile number",
			AltMobileNumber:"Provide a valid alternate mobile number",
			sameAsPhoneNumber: "Provide a valid alternate mobile number"
			} 
		});
			$("#ALT_MOBILE").rules("remove", "required");
		
		$("#PHONE_RES").rules("add", {
			digits:true,
			PhoneNumber :true,
			messages:
			{
			digits: "Provide a valid landline number",
			PhoneNumber: "Provide a valid landline number",
			} 				
		});
			$("#PHONE_RES").rules("remove", "required");

		
	}
	else if(tabKey=="AboutMe")
	{
		$("#YOURINFO").rules("add",{
				  required : true,
				  minlength:100,
				  messages:
				  {
					required: "Minimum 100 characters required",
					minlength : "Minimum 100 characters required"
				  } 
		}); 
	}
	else if(tabKey=="critical")
	{
		$("#file_keyMSTATUS_PROOF").rules("add",{
				  MstatusChange : true,
				  messages:
				  {
				  } 
		}); 
	}
	else if(tabKey=="BasicDetails")
	{
		$("#NAME").rules("add", {
			validate_custom_name: true,
                        required : true,
                        maxlength:40,
                        messages:
                        {
                              required: "Please provide a valid Full Name",
                              maxlength : "Maximum 40 characters are allowed"
                        }
		});
	}
	else if(tabKey=="Appearance")
	{
		$("#WEIGHT").rules("add", {
			digits: true,
			maxlength:3
		});
		$("#WEIGHT").rules("remove", "required");
	}
}


// about yourself field count display
var keyupStarted=0;
var newTime=-1;
function aboutFieldCount()
{
	if(newTime==-1)
		newTime=(new Date().getTime());
		
	var str = new String();
	str = trim($('#YOURINFO').val());
	str = trim_newline(str);
	
	$('#HEAD_AboutMe').text(str.length+" Characters");
	
	var tempTime=(new Date().getTime());
	var diff=tempTime-newTime;
	if(diff>1000)
		{
				$("#HEAD_AboutMe").text("About me");
		}
	else
		{
			if(keyupStarted==0)
			{
				
				keyupStarted=1;
				setTimeout(function() {
				CallKeyup();
				},1000);
			}
		}
		newTime=tempTime;
			
	
	submitObj.push("YOURINFO",$('#YOURINFO').val());
	//aboutFieldHide();
}
function CallKeyup()
{
	keyupStarted=0;
	$('#YOURINFO').trigger("keyup");
}
 

function trim_newline(string){
	return string.replace(/^\s*|\s*$/g, "");
}

function trim(inputString) {
   if (typeof inputString != "string") { return inputString; }
   var retValue = inputString;
   var ch = retValue.substring(0, 1);
   while (ch == " " || ch == '\n' || ch == '\t' || ch == '\r') {
      retValue = retValue.substring(1, retValue.length);
      ch = retValue.substring(0, 1);
   }
   ch = retValue.substring(retValue.length-1, retValue.length);
   while (ch == " " || ch == '\n' || ch == '\t' || ch == '\r') {
      retValue = retValue.substring(0, retValue.length-1);
      ch = retValue.substring(retValue.length-1, retValue.length);
   }
   while (retValue.indexOf("  ") != -1) {
      retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length);
   }
   return retValue;
}
