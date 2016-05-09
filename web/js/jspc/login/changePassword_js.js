    $(document).ready(function()
{
        $("#currPwd").val("");
        $("#newPwd").val("");
        $("#cnewPwd").val("");
        $("#currPwd").focus();
        //var vhght = $( window ).height();
	   //$('#changePass').css('height',vhght);
	var f=0;
    var errorMes="";
/*
	$("#currPwd").bind("paste keyup change",function(event){
		if($("#currPwd").val().length >0 || event.type=="paste")
		{
		  //  $("#showHide1").show();
			if($("#newPwd").val().length >0 && $("#cnewPwd").val().length >0)
				$("#saveBtn").removeClass("applied1").addClass("bg_pink");
			else
				$("#saveBtn").removeClass("bg_pink").addClass("applied1");
		}
		else
		{
			//$("#showHide1").hide();
			$("#saveBtn").removeClass("bg_pink").addClass("applied1");
		}
	});
	$("#newPwd").bind("paste keyup change",function(event){
		if($("#newPwd").val().length >0 || event.type=="paste")
		{
		   // $("#showHide2").show();
			if($("#currPwd").val().length >0 && $("#cnewPwd").val().length >0)
			   $("#saveBtn").removeClass("applied1").addClass("bg_pink");
			else
				$("#saveBtn").removeClass("bg_pink").addClass("applied1");
		}
		else
		{
			//$("#showHide2").hide();
			$("#saveBtn").removeClass("bg_pink").addClass("applied1");
		}
	});
	$("#cnewPwd").bind("paste keyup change",function(event){
		if($("#cnewPwd").val().length >0 || event.type=="paste")
		{
			//$("#showHide3").show();
			if($("#currPwd").val().length >0 && $("#cnewPwd").val().length >0)
				$("#saveBtn").removeClass("applied1").addClass("bg_pink");
			else
				$("#saveBtn").removeClass("bg_pink").addClass("applied1");
		}
		else
		{
			//$("#showHide3").hide();
			$("#saveBtn").removeClass("bg7").addClass("bggrey");
		}
	});
*/
$("#saveBtn").click(function(){
   var current=$("#currPwd").val();
   var newPass=$("#newPwd").val();
   var cnewPass=$("#cnewPwd").val();
        if(current && cnewPass && newPass)
        {
           if(newPass.length>=8)
           {
                if(checkCommonPassword(newPass) && checkChangePasswordUserName(newPass))
                {
                    if(cnewPass==newPass)
                    {
						showCommonLoader();
                        $.ajax({
                                     url:"/api/v1/api/PassChange",
                                     type: "POST",
                                     datatype:'json',
                                     cache: true,
                                     async:false,
                                     data:{curpass:escape(current),newpass:escape(newPass)},
                                     beforeSend: function() {
									 },
                                     success: function(result){
                                         if(result.responseStatusCode==0)
                                         {
											 $("#changePasswordDiv").addClass("disp-none");
											 $("#successChangePassword").addClass("visb pb400 pt100");
											$("#successChangePasswordText").html(result.responseMessage);
                                             
                                         }
                                         else
                                         {
                                             $("#topError").html(result.responseMessage).addClass('visb');
                                              setTimeout(function(){
												 $("#topError").html("").removeClass('visb');
											},2000);
										 }
										 hideCommonLoader();
                                     }
                            });
                    }
                    else
                        errorMes="Newly entered passwords do not match";
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
        {
			if(current=="")
            {
				$("#topError").html("Please enter current password").addClass('visb');
				setTimeout(function(){
					$("#topError").html("").removeClass('visb');
				},2000);
			}
			else
			{
				errorMes="Newly entered passwords do not match";
			}
		}
        if(errorMes)
        {
			hideCommonLoader();
            $("#newError").html(errorMes).addClass('visb');
		  setTimeout(function(){
			 $("#newError").html("").removeClass('visb');
			 errorMes="";
		},2000);
		}
});
});
function colorText(textId)
{
    $(textId).removeClass("color20").addClass("errcolor");
    setTimeout(function(){
        $(textId).removeClass("errcolor").addClass("color20");
    },2000);
}

function checkCommonPassword(pass)
{
	var invalidPasswords = new Array("jeevansathi","matrimony","password","marriage","12345678","123456789","1234567890");
	if ($.inArray(pass.toLowerCase(),invalidPasswords)!=-1)
		return false;
	return true;
}

function checkChangePasswordUserName(pass)
{
	var email = $("#emailStr").val();
	var end = email.indexOf('@');
    var username = email.substr(0,end);
    return (String(pass) != String(username) && String(pass) != String(email));
}
