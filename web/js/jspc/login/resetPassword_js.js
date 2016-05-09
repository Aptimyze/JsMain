var change_color=0;
$(document).ready(function()
{
	
	$("#password1").bind("paste keyup change",function(event){
		if($("#password1").val().length >0 || event.type=="paste")
		{
			if($("#cnewPwd").val().length >0)
				$("#saveBtn").removeClass("applied1").addClass("bg_pink");
		}
		else
			$("#saveBtn").removeClass("bg_pink").addClass("applied1");
	});
	$("#cnewPwd").bind("paste keyup change",function(event){
		if($("#cnewPwd").val().length >0 || event.type=="paste")
		{
			if($("#password1").val().length >0)
				$("#saveBtn").removeClass("applied1").addClass("bg_pink");
		}
		else
			$("#saveBtn").removeClass("bg_pink").addClass("applied1");
	});
    $("#password1").focus();

$("#saveBtn").click(function(){
   var newPass=$("#password1").val();
   var cnewPass=$("#cnewPwd").val();
   var errorMes="";
        if(cnewPass)
        {
           if(newPass.length>=8)
           {
                if(checkCommonPassword(newPass) && checkResetPasswordUserName(newPass))
                {
                    if(cnewPass==newPass)
                    {
						showCommonLoader();
                        $("form").submit();
					}
                    else
                        errorMes="Passwords do not match";
                }
                else
                    errorMes="The password you have chosen is not secure";
           }
           else if(newPass.length==0)
               errorMes="";
           else
               errorMes="Length of New Password should be atleast 8 characters";
        }    
        else
            errorMes="";
        if(errorMes)
         {
			$("#topError").html(errorMes).addClass('visb');
			setTimeout(function(){
			 $("#topError").html("").removeClass('visb');
			 errorMes="";
			},2000);
		}
});
});
function checkCommonPassword(pass)
{
	var invalidPasswords = new Array("jeevansathi","matrimony","password","marriage","12345678","123456789","1234567890");
	if ($.inArray(pass.toLowerCase(),invalidPasswords)!=-1)
		return false;
	return true;
}

function checkResetPasswordUserName(pass)
{
	var email = $("#emailStr").val();
	var end = email.indexOf('@');
    var username = email.substr(0,end);
    return (String(pass) != String(username) && String(pass) != String(email));
}

