$(function(){
    if(!ISBrowser("UC") && !ISBrowser("AndroidNative"))
        $("#useremail").focus();
    $("#useremail").bind("keydown",function(e){
       if(e.keyCode == 13)
       {    e.preventDefault();
           $("#sendLink").click();
       }
    });
$("#sendLink").bind(clickEventType,function(){
   var email=$("#useremail").val();
   if(email)
   {
            if(validateEmail(email))
                         {       

                                 $.ajax({
                                     url:"/api/v1/api/forgotlogin",
                                     type: "POST",
                                     datatype:'json',
                                     cache: true,
                                     async:false,
                                     data:{email:email.trim()},
                                     success: function(result){
                                         if(result.responseStatusCode==0)
                                         {
                                             document.location.href="/static/forgotPassword?success=1";
                                             return;
                                         }
                                         else
                                             ShowTopDownError([result.responseMessage]);
                                     }
                                 });
                         }
             else
                 ShowTopDownError(["Provide a valid email address"]);
    }
    else
        ShowTopDownError(["Provide your email address"]);
});
function validateEmail(email) {
    var x = $.trim(email);
    var re = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
    return re.test(x);
    }
});    