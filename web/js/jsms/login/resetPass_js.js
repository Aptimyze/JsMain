var change_color=0;
$(document).ready(function()
{
        var hgt=$(window).height();
        $("#resetp").css("height",hgt);
	var f=1;
	var farray={0:"password",1:"text"};
	var sarray={0:"Show",1:"Hide"};
	$("#password1").bind("paste keyup change",function(event){
                        $("#saveBtn").addClass("opa50");
             		if($("#password1").val().length >0 || event.type=="paste")
			{
                            $("#showHide1").show();
                            if($("#password2").val().length >0)
                                $("#saveBtn").removeClass("opa50");
                        }
			else
				$("#showHide1").hide();
	});
        $("#password2").bind("paste keyup change",function(event){
                        $("#saveBtn").addClass("opa50");
             		if($("#password2").val().length >0 || event.type=="paste")
                        {
                            $("#showHide2").show();
                            if($("#password2").val().length >0)
                                $("#saveBtn").removeClass("opa50");
                        }
			else
				$("#showHide2").hide();
	});
	$("#showHide1").bind("click",function(){
			$("#password1").attr("type",farray[f]);
			$("#showHide1").html(sarray[f]);
			f=f?0:1;
                        $("#password1").focus();
	});$("#showHide1").hide();
        $("#showHide2").bind("click",function(){
			$("#password2").attr("type",farray[f]);
			$("#showHide2").html(sarray[f]);
			f=f?0:1;
                        $("#password2").focus();
	});$("#showHide2").hide();
$("#saveBtn").bind(clickEventType,function(){
   var newPass=$("#password1").val();
   var cnewPass=$("#password2").val();
   var errorMes="";
        if(cnewPass)
        {
           if(newPass.length>=8)
           {
                if(checkCommonPassword(newPass) && checkPasswordUserName(newPass))
                {
                    if(cnewPass==newPass)
                        $("form").submit();
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
            ShowTopDownError([errorMes]);
});
});
function checkCommonPassword(pass)
{
	var invalidPasswords = new Array("jeevansathi","matrimony","password","marriage","12345678","123456789","1234567890");
	if ($.inArray(pass.toLowerCase(),invalidPasswords)!=-1)
		return false;
	return true;
}

function checkPasswordUserName(pass)
{
	var email = $("#emailStr").val();
	var end = email.indexOf('@');
    var username = email.substr(0,end);
    return (String(pass) != String(username) && String(pass) != String(email));
}
