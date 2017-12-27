var isdCodes = ["0", "91","+91"];
var errorIndex;
var ErrorMsg = new Array();
ErrorMsg[0] = $("#err_mobile_invalid").html();
ErrorMsg[1] = $("#err_mobile_length_international").html();
ErrorMsg[2] = $("#err_mobile_isd").html();
ErrorMsg[3] = $("#err_phone_isd").html();
ErrorMsg[4] = $("#err_phone_invalid").html();
var corrections = {
		"gamil.com" : "gmail.com",
		"gmai.com" :"gmail.com",
		"gmil.com":"gmail.com",
		"gmal.com":"gmail.com",
		"gmaill.com":"gmail.com",
		"gmail.co":"gmail.com",
		"gail.com":"gmail.com",
		"gmail.om":"gmail.com",
		"gmali.com":"gmail.com",
		"gmail.con":"gmail.com",
		"gmail.co.in":"gmail.com",
		"gmail.cm":"gmail.com",
		"gmail.in":"gmail.com",
		"gimal.com":"gmail.com",
		"gnail.com":"gmail.com",
		"gimail.com":"gmail.com",
		"g.mail.com":"gmail.com",
		"gmailil.com":"gmail.com",
		"gmail.cim":"gmail.com",
		"gemail.com":"gmail.com",
		"gmall.com":"gmail.com",
		"gmail.com.com":"gmail.com",
		"gmeil.com":"gmail.com",
		"gmsil.com":"gmail.com",
		"gmail.comn":"gmail.com",
		"gmail.cpm":"gmail.com",
		"gimel.com":"gmail.com",
		"gmailo.com":"gmail.com",
		"gmile.com":"gmail.com",
		"fmail.com":"gmail.com",
		"yhoo.com":"yahoo.com",
		"yaho.com":"yahoo.com",
		"yahool.com":"yahoo.com",
		"yhaoo.com":"yahoo.com",
		"yahoo.co":"yahoo.com",
		"yaoo.com":"yahoo.com",
		"yhaoo.co.in":"yahoo.com",
		"yahoo.com.in":"yahoo.co.in",
		"yamil.com":"ymail.com",
		"yhoo.in":"yahoo.in",
		"yahho.com":"yahoo.com",
		"yahoo.com.com":"yahoo.com",
		"redifmail.com":"rediffmail.com",
		"reddifmail.com":"rediffmail.com",
		"reddffmail.com":"rediffmail.com",
		"rediffmaill.com":"rediffmail.com",
		"rediffmai.com":"rediffmail.com",
		"rediffmal.com":"rediffmail.com",
		"reddiffmail.com":"rediffmail.com",
		"redifffmail.com":"rediffmail.com",
		"rediffimail.com":"rediffmail.com",
		"rediiffmail.com":"rediffmail.com",
		"rediifmail.com":"rediffmail.com",
		"rediffmil.com":"rediffmail.com",
		"rediffmail.co":"rediffmail.com",
		"rediffmail.con":"rediffmail.com",
		"rediffmail.cm":"rediffmail.com",
		"rediffmial.com":"rediffmail.com",
		"redffimail.com":"rediffmail.com",
		"rdiffmail.com":"rediffmail.com",
		"radiffmail.com":"rediffmail.com"

};


var autoCorrect = 1;
var mesIdTrue = 0;
var email = '';
var isd_regex = /^[+]?[0-9]+$/;
var smallCase_regex = /^[a-z]+$/;
var upperCase_regex = /^[A-Z]+$/;
var specialChars_regex = /^.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]+$/;
var email_regex = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
var digit_regex = /^[0-9]+$/;
//used when user selects "Looking for" to change selected gender.
var action = "";
var min_age;
var dispError = function()
{
	return ErrorMsg[errorIndex];
}
var minAgeError = function()
{
	return "<label>&nbsp;</label><div class=\"err_msg\">You must be at least "+min_age+" years of age.</div>"
}

// undo corrected mail on auto correct
function revertEmail()
{
	$("#reg_email").val(email);
	$("#autocorrect").css("display","none");
	return false;

}

// message on auto correction of email
var autoEmailCorrectMes = function(){
	var emailNew = $("#reg_email").val();
	str="<div class='clr'></div><label>&nbsp;</label><div id ='autocorrect' style='display:block';color:'#ffffff';>We have auto-corrected your email to </br><div class='clr'></div><label>&nbsp;</label>EMAIL_VAL <a id='auto' style='color:#117dAA' name='undo' href='#' onclick='return revertEmail()'>Undo </a></div>";
	str=str.replace("EMAIL_VAL",$("#reg_email").val());
	return str;
};
// message on auto correction of email
var autoEmailCorrectMesMobile = function(){
	var emailNew = $("#reg_email").val();
	str="<span id ='autocorrect' style='display:inline';color:'#ffffff';><small></small>We have auto-corrected your email to EMAIL_VAL <a id='auto' style='color:#117dAA' name='undo' href='#' onclick='return revertEmail()'>Undo </a></span>";
	str=str.replace("EMAIL_VAL",$("#reg_email").val());
	$("#email_err").css("padding-bottom","0em");
	return str;
};



//City validation
jQuery.validator.addMethod("checkSelectDropDown", function(value,element) {

	var jID=$("#"+$(element).attr("id"));
	if(jID.parent().css("display")!="none")
	{
		if(!jID.val() && value==0)
		{
			return selectDropDownError($(element),0);

		}
		else if(value==1)
		{
			return selectDropDownError($(element),1);

		}
	}
	return true;
},NullFunction);
function NullFunction()
{

}
function selectDropDownError(elem,show)
{

	if($.trim($(elem).html())!="")
	{
		prevelem=$(elem).prev();
		//alert('hi'+$(elem).html());
		elem=$(elem).next().next();
		prevelem.css("color","red");
		$(elem).css('display','inline');
		if(show)
		{
			prevelem.css("color","");
			$(elem).css('display','none');
		}
		else
			return false;
	}
	return true;
}


// invalid domain check on email
jQuery.validator.addMethod("invalidDomain", function(value, element) {
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
},$("#err_email_del").html());

// regex pattern check on email
jQuery.validator.addMethod("emailPattern", function(value, element) {

       var email = $.trim($("#reg_email").val());
       if(!email_regex.test(email))
			return false;
        else
			return true;

},$("#err_email_del").html());

// auto correct  mis spelt domain names
jQuery.validator.addMethod("autocorrect", function(value, element) {
	var newEmail = $("#reg_email").val();
	if(email == newEmail)
	{
		autoCorrect = 0;
	}

	if($("#reg_email").is(":focus") || autoCorrect == 0)
	{
		return true;
	}
	email = $("#reg_email").val();
	var domain = email.split('@');
	oldDom = domain[1];
	oldDom = oldDom.toLowerCase();
	if (!corrections[oldDom])
	{
		return true;
	}

	var stringToReplace = corrections[oldDom];
	$("#reg_email").val(domain[0] + '@' +stringToReplace) ;
	return false;

},autoEmailCorrectMes);
jQuery.validator.addMethod("autocorrect_mob", function(value, element) {
	var newEmail = $("#reg_email").val();
	if(email == newEmail)
	{
		autoCorrect = 0;
	}

	if($("#reg_email").is(":focus") || autoCorrect == 0)
	{
		return true;
	}
	email = $("#reg_email").val();
	var domain = email.split('@');
	oldDom = domain[1];
	oldDom = oldDom.toLowerCase();
	if (!corrections[oldDom])
	{
		return true;
	}

	var stringToReplace = corrections[oldDom];
	$("#reg_email").val(domain[0] + '@' +stringToReplace) ;
	return false;

},autoEmailCorrectMesMobile);


jQuery.validator.addMethod("isdCode", function(value,element) {
	return this.optional(element) ||  isd_regex.test(value);
},$("#err_mobile_invalid").html());

jQuery.validator.addMethod("PhoneNumber", function(value,element) {
		if (!checkPhone()) {
			return false;
		}
		else {
			return true
		}
	},dispError);

jQuery.validator.addMethod("MobileNumber", function(value,element) {
	return ((value=='' && $('#reg_phone_res_landline').val().length>0) || checkMobile());
	},dispError);

function checkMobile()
{
	var mobileISD = $("#reg_phone_mob_isd");
	var mobileNumber = $("#reg_phone_mob_mobile");
	if($.inArray(mobileISD.val(),isdCodes)!= -1 && mobileNumber.val().length!=10 || (mobileISD.val() && !isd_regex.test(mobileISD.val())))
	{
		errorIndex = 0;
		return false;
	}
	else if($.inArray(mobileISD.val(),isdCodes)==-1 && (mobileNumber.val().length<6 || mobileNumber.val().length>14))
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
function checkPhone()
{
	var landlineISD = $("#reg_phone_res_isd");
	var landlineSTD = $('#reg_phone_res_std');
	var landlineNumber = $('#reg_phone_res_landline');
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

jQuery.validator.addMethod("commonWords",function(value,element)
{
	return checkCommonPassword(value);
},$("#err_pass_common").html());

jQuery.validator.addMethod("onlyNumeric",function(value,element)
{
	return !$.isNumeric(value);
},$("#err_pass_only_numeric").html());

jQuery.validator.addMethod("checkWithUserName",function(value,element)
{
	return checkPasswordUserName(value);
},$("#err_pass_email").html());

jQuery.validator.addMethod("mstatusNotSelected", function(value, element, arg){
    return arg != value;
   }, $("#mstatus_required").html());

jQuery.validator.addMethod("mstatusMarried",function(value,element){
    var religion = $("#reg_religion").val();
	if(value == "M")
	{
		 if(religion)
         {
                 if(religion!="2")
                 return false;
         }
	}
	return true;
	},$("#mstatus_error_muslim").html());

jQuery.validator.addMethod("haveChild",function(value,element){
    var mstatus = $("#reg_mstatus").val();
	if(!value && mstatus!="N")
	{
		return false;
    }
	return true;
	},$("#havechild_required").html());
jQuery.validator.addMethod("check_date_of_birth", function(value, element) {
    var gender = $('input:radio[name="reg[gender]"]:checked').val();
	if (!checkMinAge(gender)) {
		return false;
	}
	else {
		return true
	}
},minAgeError);

jQuery.validator.addMethod("check_date_of_birth_minreg", function(value, element) {
    var gender = updateGender($("#reg_relationship").val());
	if (!checkMinAge(gender)) {
		return false;	}
	else {
		return true
	}
},minAgeError);

jQuery.validator.addMethod("checkCaste",function(value,element){
    var religion = $("#reg_religion").val();
	if(jQuery.inArray(religion,[5,6,7,8,10]) ==  -1 && value=='')
		return false;
	else
	return true;
	},$('#caste_required').html());

var passwordStrength =  function(ratingMessages)
{
	var pass = $("#reg_password").val();
	var score = 0;
	if(pass.length==0)
		updatePasswordBar(5,ratingMessages);
	else if(pass.length < 8 || !checkCommonPassword(pass) || !checkPasswordUserName(pass) || $.isNumeric(pass))
	{
		updatePasswordBar(0,ratingMessages);
		return;
	}
	if(pass.length >=8 )
		score+= 1;
	if(pass.length>=8 && !(digit_regex.test(pass) || smallCase_regex.test(pass) || upperCase_regex.test(pass) || specialChars_regex.test(pass)))
		score+= 1;
	if(score>0)
		updatePasswordBar(score,ratingMessages);
}
function updatePasswordBar(rating,ratingMessages) {
	var ratingClasses = new Array(6);
	ratingClasses[0] = 'weak';
	ratingClasses[1] = 'good';
	ratingClasses[2] = 'strong';
	ratingClasses[3] = 'notRated';
	var bar = document.getElementById('strength-bar');
	if (bar) {
		var message = document.getElementById('passwdRating');
		var barLength = document.getElementById('passwdBar').clientWidth;
		bar.className = ratingClasses[rating];
		if (rating >= 0 && rating <= 2) {
			bar.style.width = (barLength * (parseInt(rating) + 1.0) / 3.0) + 'px';
			message.innerHTML = ratingMessages[rating];
		}
		else {
			bar.style.width = 0;
			rating = 3;
			message.innerHTML = '';
		}
	}
}

//To be called by AutoSelect js file, when focus/blur event called
function SelectDropDownErrors(val,element)
{
	$.validator.methods["checkSelectDropDown"].call(null,val, element);
}

/*Function to change tab labels, depending on Create profie for selection*/
function change_tab_labels(page)
{
	var i,j,selected_val,label;
	var selected_val = $("#reg_relationship").val();
	var relationshipLabel;
	var label_arr = new Array('_basicInfo','_religionEthnicity');

	switch(selected_val)
	{
		case '1':
		case '1D':
			label = 'self';
			relationshipLabel = 'Your Basic';
			break;
		case '2':
			label = 'son';
			action = 'male_select';
			relationshipLabel = "Son's Basic";
			break;
		case '2D':
			label = 'daughter';
			action = 'female_select';
			relationshipLabel = "Daughter's Basic";
			break;
		case '3':
			label = 'father';
			action = 'male_select';
			break;
		case '3D':
			label = 'mother';
			action = 'female_select';
			break;
		case '4':
		case '4D':
			label='friend';
			relationshipLabel = 'Basic';
			break;
		case '5':
			label='marriageBureau';
			relationshipLabel = 'Basic';
			break;
		case '6':
			label='brother';
			action = 'male_select';
			relationshipLabel = "Brother's Basic";
			break;
		case '6D':
			label='sister';
			action = 'female_select';
			relationshipLabel = "Sister's Basic";
			break;
		default:
			relationshipLabel = "Basic";
		}
		if(relationshipLabel)
		{
			$("#personal_heading").html(relationshipLabel+" Profile Details");
			$("#social_heading").html(relationshipLabel+" Social Details");
			$("#contact_heading").html(relationshipLabel+" Contact Details");
			if(page=="Desktop")
				relationshipLabel=relationshipLabel.replace("Basic","");
			if(relationshipLabel!='Profile')
			{
				if(page == 'Desktop')
					$("label[for=reg_dtofbirth]").html((relationshipLabel=='Basic'?"":relationshipLabel)+" Date of Birth :");
				else if(page == 'MinReg')
					$("label[for=reg_dtofbirth]").html("* "+relationshipLabel+" Date of Birth:");
				else
					$("label[for=reg_dtofbirth]").html(relationshipLabel+" Date of Birth ");
			}
			else
			{   if(page == 'Desktop')
					$("label[for=reg_dtofbirth]").html("Date of Birth :");
				else if(page == 'MinReg')
				    $("label[for=reg_dtofbirth]").html("* Date of Birth of Boy/Girl:");
				else
				    $("label[for=reg_dtofbirth]").html("Date of Birth ");
			}
		}

		if(typeof(label) != "undefined")
		{
			var i1 = selected_val.length;
			var j1 = label_arr.length;
			for(i=0; i<i1; i++)
			{
				for(j=0; j<j1; j++)
				{
					var div_id = selected_val[i] + label_arr[j];
					if($("#"+div_id).lenght!=0)
					{
						if(selected_val[i] == label)
							$("#"+div_id).css('display','block');
						else
							$("#"+div_id).css('display','none');
					}
				}
			}
		}
}

/*Function to toggle gender selection, depending on Looking for selection*/
function gender_display_selection()
{
	var a=8;
	if(action=='male_select')
	{

		$('input:radio[name="reg[gender]"]')[0].checked = true;
		$("#gender_section").css('display','none');
		$("#gender_padding").css('padding',0);
		updateYear('M');
		//$("#reg_mstatus").css('display','inline');
		/* End of the Section*/

	}
	else if(action=='female_select')
	{
		$('input:radio[name="reg[gender]"]')[1].checked = true;
		$("#gender_section").css('display','none');
		$("#gender_padding").css('padding',0);
		updateYear('F');
		//$("#reg_mstatus").css('display','inline');

	}
	else
	{
		$("#gender_section").css('display','block');
		$("#gender_padding").css('padding','');
	}

	action = "";
}

/*Function to send forgot password info*/
function send_username_password(to_send_email)
{
	to_send_email = escape(to_send_email);
	var to_post = to_send_email;
	var data1={"to_send_email":to_send_email,"forgot_password":1};
	var url1="/profile/registration_ajax_validation.php";
	$.ajax({
		type: 'POST',
		url: url1,
		data: data1,
		success:function(data){
		        response = data;
		        eval(email_sent_notify(response));}
	 });

}

/*Function to notify that email has been sent.*/
function email_sent_notify(data)
{
	var message = new Array();

	message.push("<label class=\"l1\">&nbsp;</label>");
	message.push("<div class=\"err_msg\" style=\"width:280px;\">");
	message.push(data);
	message.push('</div></div>');

	$("#email_err").html(message.join(''));

}
//Update ISD Code fields for mobile and landline.
function UpdateISD(data)
{
	var isdcode=data[0][1];
	$("#reg_phone_mob_isd").val(isdcode);
	$("#reg_phone_res_isd").val(isdcode);
	if($("#reg_phone_mob_isd").val()=='+91')
	{
		$("#verify_message_mobile").css('display','inline');
		$("#verify_message_phone").css('display','inline');
		$("#reg_phone_mob_mobile").attr('maxLength','10');
		$("#reg_phone_res_landline").attr('maxLength','10');
	}
	else
	{
		$("#verify_message_mobile").css('display','none');
		$("#verify_message_phone").css('display','none');
		$("#reg_phone_mob_mobile").attr('maxLength','15');
		$("#reg_phone_res_landline").attr('maxLength','15');
	}
}
function UpdateSTD(data)
{
	var stdcode =  data[0][1];
	$("#reg_phone_res_std").val(stdcode);
}

function checkMinAge(gender)
{
	var day = $("#reg_dtofbirth_day").val();
    var month = $("#reg_dtofbirth_month").val();
    var year = $("#reg_dtofbirth_year").val();
	if(gender=='F')
		min_age = 18;
	else
		min_age = 21;

    var mydate = new Date();
    mydate.setFullYear(year, month-1, day);

    var currdate = new Date();
    currdate.setFullYear(currdate.getFullYear() - min_age);
    return currdate > mydate;
}

function checkCommonPassword(pass)
{
	var invalidPasswords = new Array("jeevansathi","matrimony","password","marriage","vibhor1234","omsairam","jaimatadi","abcd1234","Parvezkk","priyanka","Jeevansathi@123","pytw2560","waheguru","jeevansathi123","js123456","Jeevansathi.com","India@123","P@ssw0rd","ABHIshek","pass@123","jeevan123","welcome@123","Mayank2463","Welcome123","abc123","password123","qwertyuiop","india123","Password@123","nehaavyan123","abcd@1234","pd592001","shaadi@123","YASU4333","Krishna","Jeevan@123","Radhika02","anik.singh","JABALPUR123","qwerty","sairam","SINGH4345","rahul123","sachin","rahul@123","iloveyou","ganesh","saibaba","jeevansaathi","harekrishna","hariom","himanshu","shaadi123","pooja123","singh123","qwerty123","kareenakhan23","sonu1234","sunita","deepak","abcdefgh","sanjay","mummypapa","chaman111","Qwerty@123","priyanka123","Kaushal69sc@gmail.com","goodluck","rajkumar","rajusohel","pankaj");
	if ($.inArray(pass.toLowerCase(),invalidPasswords)!=-1)
		return false;
	return true;
}

function checkPasswordUserName(pass)
{
	var email = $("#reg_email").val();
	var end = email.indexOf('@');
    var username = email.substr(0,end);
    return (String(pass) != String(username) && String(pass) != String(email));
}

function updateYear(gender)
{
	if(gender=='M')
		start=21;
	else
		start = 18;
	var curYear = (new Date()).getFullYear();
	var yearSel=$('#reg_dtofbirth_year').val();
	$('#reg_dtofbirth_year').empty().append($('<option />').val('Year').html('Year'));
	for (i=curYear-start; i>=curYear-70; i--)
	{
		if(i==yearSel)
		$('#reg_dtofbirth_year').append('<option value="'+i+'" selected="selected">'+i+'</option>');
		else
		$('#reg_dtofbirth_year').append($('<option />').val(i).html(i));
	}
}

function updateGender(relationship)
{
    switch(relationship)
    {
	    case '1':
	    case '2':
	    case '4':
	    case '6':
	        gender='M';
		    break;
	    case '1D':
	    case '2D':
	    case '4D':
	    case '6D':
	        gender='F';
		    break;
	 }
	 return gender;
}

var ArrayPincode={'DE00':{0:["1100","2013","1220","2010","1210","1245"],1:4,2:"Please provide a pincode that belongs to Delhi"},"MH04":{0:["400","401","410","421","416"],1:3,2:"Please provide a pincode that belongs to Mumbai"},"MH08":{0:["410","411","412","413"],1:3,2:"Please provide a pincode that belongs to Pune"}};
function SetPinIniMes()
{
	$("#err_pin_delhi div").html(ArrayPincode[$("#reg_city_res").val()][2]);
	return $("#err_pin_delhi").html();
}
function checkPinInitials(pin)
{
	var city_pin=$("#reg_city_res").val();

	var initial = pin.substring(0,ArrayPincode[city_pin][1]);

	if(jQuery.inArray(initial,ArrayPincode[city_pin][0])==-1)
	{
		return false;
	}
	return true;
}

jQuery.validator.addMethod("pinInitials",function(value,element)
{
	var validPin = true;
	if((ArrayPincode[$("#reg_city_res").val()]) && $("#reg_country_res").val() == '51')
		var checkPin = true;
	else
		var checkPin = false;

	if(checkPin)
		var validPin = checkPinInitials(value);
	return (validPin);

},SetPinIniMes);

jQuery.validator.addMethod("blankCheck",function(value,element)
{
	if(ArrayPincode[$("#reg_city_res").val()] && $("#reg_country_res").val() == '51' && value == '')
		return false;
	return true;


},$("#err_pin_req").html());
