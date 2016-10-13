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
        var flag = validateEmail(email)?'E':false;
        var phone = null;
        var isd = null;
        if(!flag)
        {
            var data = validateMobile(email);
            flag = data['flag']?'M':false;
            phone = data['phone'];
            isd = data['isd'];
        }
                        if(flag)
                         {       

                                 $.ajax({
                                     url:"/api/v1/api/forgotlogin",
                                     type: "POST",
                                     datatype:'json',
                                     cache: true,
                                     async:false,
                                     data:{'email':email.trim(), 'flag':flag, 'phone':phone, 'isd':isd},
                                     success: function(result){
                                         if(result.responseStatusCode==0)
                                         {
                                                message=result.responseMessage;
                                                document.location.href="/static/forgotPassword?success=1&message="+message;
                                                return;
                                         }
                                         else
                                             ShowTopDownError([result.responseMessage]);
                                     }
                                 });
                         }
             else
                 ShowTopDownError(["Provide a valid email address or phone number"]);
    }
    else
        ShowTopDownError(["Provide your email address or phone number"]);
});
function validateEmail(email) {
    var x = $.trim(email);
    var re = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
    return re.test(x);
}

function validateMobile(mobile) {
    var str = $.trim(mobile);
    // removes leading zeros
    str = str.replace(/^0+/, '');
    if(str.indexOf('-') > -1)
    {
        var result = str.split("-");
        // remove leading zeros from number
        result[1] = result[1].replace(/^0+/, '');
        str = result.join("-");
    }

    if(str.indexOf('+') > -1)
    {
        var result = str.split("+");
        // remove leading zeros from isd
        result[1] = result[1].replace(/^0+/, '');
        str = result.join("+");
    }
    var re = /^((\+)?[0-9]*(-)?)?[0-9]{7,}$/i;
    var isd = '';
    var phone = '';
    var data = new Array();
    if(re.test(str))
    {
        str = str.split('+').join('');
        if(str.indexOf('-') > -1)
        {
            isd = str.slice(0, str.indexOf('-'));
            phone = str.slice(str.indexOf('-')+1, str.length);
        }
        else
        {
            isd = '';
            phone = str;
        }
        data['flag'] = 1;
        data['phone'] = phone;
        data['isd'] = isd;
    }
    else
    {
        data['flag'] = 0;
    }
    return data;
}
});    