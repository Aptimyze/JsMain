function clear_mobile(){
	if($("#mobile").val()=="Enter your mobile number"){
		$("#mobile").val("");
	}
	$("#mobile").css("color","black");
	return true;
}
function dID(elem){
	return document.getElementById(elem);
}
function check_mobile(){
		var docF=document.form1;
		var filter  = /^[0-9]+$/;
		var err_i=0;
		var con_code=docF.country_code_mob.value.replace('+','');
		if(docF.mobile.value == "")
		{
			err_i++;
				dID("mobile_error1").style.display = 'inline';
				dID("mobile_error2").style.display = 'none';
				dID("mobile_error3").style.display = 'none';
			dID("mobile_error4").style.display = 'none';
			dID("country_code_err").style.display = 'none';
			$("#mobile").focus();
		}
		else if(!filter.test(docF.mobile.value) && docF.mobile.value != "")
		{
			err_i++;

			dID("mobile_error1").style.display = 'none';
			dID("mobile_error2").style.display = 'inline';
			dID("mobile_error3").style.display = 'none';
			dID("mobile_error4").style.display = 'none';
			dID("country_code_err").style.display = 'none';
			$("#mobile").focus();
		}
		else if(docF.mobile.value.length < 8 && docF.mobile.value != "" && docF.country_code_mob.value != "+91")
		{
			err_i++;
			dID("mobile_error1").style.display = 'none';
			dID("mobile_error2").style.display = 'none';
			dID("mobile_error3").style.display = 'none';
			dID("mobile_error4").style.display = 'inline';
			dID("country_code_err").style.display = 'none';
			$("#mobile").focus();
		}
		else if(docF.mobile.value.length < 10 && docF.mobile.value != "" && docF.country_code_mob.value == "+91")
		{
			err_i++;
			dID("mobile_error1").style.display = 'none';
			dID("mobile_error2").style.display = 'none';
			dID("mobile_error4").style.display = 'none';
			dID("mobile_error3").style.display = 'inline';
			dID("country_code_err").style.display = 'none';
			$("#mobile").focus();
		}
		else
		if(!filter.test(con_code) ||(con_code.length >5)){
			err_i++;
			dID("mobile_error1").style.display = 'none';
			dID("mobile_error2").style.display = 'none';
			dID("mobile_error4").style.display = 'none';
			dID("mobile_error3").style.display = 'none';
			dID("country_code_err").style.display = 'inline';
			$("#country_code").focus();
		}
		if(err_i){
			dID("mobile_submit_err").style.display = 'inline';
			return false;
		}
		else{ 
			dID("mobile_submit_err").style.display = 'none';
			return true;
		}
}
