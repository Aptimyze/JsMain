    $(document).ready(function()
{
        $("#currPwd").val("");
        $("#newPwd").val("");
        $("#cnewPwd").val("");
        if(!ISBrowser("UC") && !ISBrowser("AndroidNative"))
            $("#currPwd").focus();
        var vhght = $( window ).height();
	   $('#changePass').css('height',vhght);
	var f=0;
        var errorMes="";
	var farray={0:"password",1:"text"};
	var sarray={0:"Show",1:"Hide"};
	$("#currPwd").bind("paste keyup change",function(event){
             		if($("#currPwd").val().length >0 || event.type=="paste")
			{
                            $("#showHide1").show();
                            if($("#newPwd").val().length >0 && $("#cnewPwd").val().length >0)
                                $("#saveBtn").removeClass("bggrey").addClass("bg7");
                            else
                                $("#saveBtn").removeClass("bg7").addClass("bggrey");
                        }
			else
			{
                            $("#showHide1").hide();
                            $("#saveBtn").removeClass("bg7").addClass("bggrey");
                        }
	});
        $("#newPwd").bind("paste keyup change",function(event){
             		if($("#newPwd").val().length >0 || event.type=="paste")
			{
                            $("#showHide2").show();
                            if($("#currPwd").val().length >0 && $("#cnewPwd").val().length >0)
                                $("#saveBtn").removeClass("bggrey").addClass("bg7");
                            else
                                $("#saveBtn").removeClass("bg7").addClass("bggrey");
                        }
			else
			{
                            $("#showHide2").hide();
                            $("#saveBtn").removeClass("bg7").addClass("bggrey");
                        }
	});
        $("#cnewPwd").bind("paste keyup change",function(event){
             		if($("#cnewPwd").val().length >0 || event.type=="paste")
			{
                            $("#showHide3").show();
                            if($("#currPwd").val().length >0 && $("#cnewPwd").val().length >0)
                                $("#saveBtn").removeClass("bggrey").addClass("bg7");
                            else
                                $("#saveBtn").removeClass("bg7").addClass("bggrey");
                        }
			else
			{
                            $("#showHide3").hide();
                            $("#saveBtn").removeClass("bg7").addClass("bggrey");
                        }
	});
	$("#showHide1").bind("click",function(){
			$("#currPwd").attr("type",farray[f]);
			$("#showHide1").html(sarray[f]);
			f=f?0:1;
                        $("#currPwd").focus();
	});$("#showHide1").hide();
        $("#showHide2").bind("click",function(){
			$("#newPwd").attr("type",farray[f]);
			$("#showHide2").html(sarray[f]);
			f=f?0:1;
                        $("#newPwd").focus();
	});$("#showHide2").hide();
        $("#showHide3").bind("click",function(){
			$("#cnewPwd").attr("type",farray[f]);
			$("#showHide3").html(sarray[f]);
			f=f?0:1;
                        $("#cnewPwd").focus();
	});$("#showHide3").hide();
$("#saveBtn").bind(clickEventType,function(){
   var current=$("#currPwd").val();
   var newPass=$("#newPwd").val();
   var cnewPass=$("#cnewPwd").val();
        if(current&&cnewPass)
        {
           if(newPass.length>=8)
           {
                if(checkCommonPassword(newPass) && checkPasswordUserName(newPass))
                {
                    if(cnewPass==newPass)
                    {
                        $.ajax({
                                     url:"/api/v1/api/PassChange",
                                     type: "POST",
                                     datatype:'json',
                                     cache: true,
                                     async:false,
                                     data:{curpass:escape(current),newpass:escape(newPass)},
                                     success: function(result){
                                         if(result.responseStatusCode==0)
                                         {
                                             ShowTopDownError([result.responseMessage]);
                                             setTimeout(function(){
                                             window.location.href="/static/settings";},1000);
                                         }
                                         else
                                             errorMes=result.responseMessage;
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
            errorMes="";
        if(errorMes)
            ShowTopDownError([errorMes]);
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

function checkPasswordUserName(pass)
{
	var email = $("#emailStr").val();
	var end = email.indexOf('@');
    var username = email.substr(0,end);
    return (String(pass) != String(username) && String(pass) != String(email));
}