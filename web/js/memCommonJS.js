$(document).ready(function()
{
	$("#excCall").click(function(event)
	{
		if(user.userType=="1")
		{
			$("#callBackLayer").css("top","-220px");
			$("#callBackLayer").css("left","-110px");
			$("#callPhNo").val("");
			$("#callEmail").val("");
			$("#emailErr").html("");
			$("#phoneErr").html("");
			$("#emailErr").attr("class"," ");
			$("#phoneErr").attr("class"," ");
			$("#callBackLayer").css("display","block");
		}
		else
		{
			$("#alertSuccess").css("top","-126px");
			$("#alertSuccess").css("left","-110px");
			$("#alertSuccess").css("display","block");
			$("#alert-data").html("Thank you for showing interest in our services.<br />Our matchmaking expert will contact you as soon as possible.");
			$.post("/membership/addCallBck",{'profileid':user.profileid},function(response)
                        {
                        });
			event.stopPropagation();
		}
	});
	// function for membership Alerts
        $("#excCallNew").click(function(event)
        {
                if(user.userType=="1")
                {
                        $("#callBackLayer").css("top","-154px");
                        $("#callBackLayer").css("left","546px");
                        $("#callPhNo").val("");
                        $("#callEmail").val("");
                        $("#emailErr").html("");
                        $("#phoneErr").html("");
                        $("#emailErr").attr("class"," ");
                        $("#phoneErr").attr("class"," ");
                        $("#callBackLayer").css("display","block");
                }
                else
                {
	                var execCallbackType    =$("[name=execCallbackType]").val();
        	        var tabVal              =$("[name=tabValue]").val();
                        var idContainer         =$("#memDetailsContainer").css("display");
                        var fs24CheckVal        =$(".offer-price-box .fs24").length;
			if(tabVal==1){
                                if(fs24CheckVal<1 && idContainer=='block'){
                                  if($(".mem-holi2").length && $("#subscriptions").length){
                                    $("#alertSuccess").css("top","100px");
                                  } else if($("#subscriptions").length) {
                                    $("#alertSuccess").css("top","-15px");
                                  } else if($(".mem-holi2").length){
                                    $("#alertSuccess").css("top","60px");
                                  } else {
                                    $("#alertSuccess").css("top","-60px");
                                  }
                                }else if(idContainer=='block'){
                                  if($(".mem-holi2").length && $("#subscriptions").length){
                                    $("#alertSuccess").css("top","100px");
                                  } else if($(".mem-holi2").length) {
                                    $("#alertSuccess").css("top","60px");
                                  } else {
                                    $("#alertSuccess").css("top","-14px");
                                  }
                                }else{
                                  $("#alertSuccess").css("top","-834px");
                                }

        	                $("#alertSuccess").css("left","546px");
			}
			else if(tabVal==2 || tabVal==3){
				$("#alertSuccess").css("z-index","6000");
                                $("#alertSuccess").css("top","-149px");
                                $("#alertSuccess").css("left","511px");
			}
                        $("#alertSuccess").css("display","block");
                        $("#alert-data").html("Thank you for showing interest in our services.<br />Our matchmaking expert will contact you as soon as possible.");
                        $.post("/membership/addCallBck",{'profileid':user.profileid,'execCallbackType':execCallbackType,'tabVal':tabVal},function(response)
                        {
                        });
                        event.stopPropagation();
                }
        });
	$(".close-layer").click(function()
        {
		$("#alertSuccess").css("display","none");
                $("#callBackLayer").css("display","none");
        });
	
	$('html').click(function()
        {
		$("#alertSuccess").css("display","none");
        });
        $(document).keypress(function(e)
        {
               if(e.keyCode==27)
               {
			$("#alertSuccess").css("display","none");
               }
        });
	

	$("#callBckSbmt").click(function()
        {
		var phNo		=$("#callPhNo").val();
		var email		=$("#callEmail").val();
		var selId		=$("input:radio[name=r1]:checked").attr("id");
		var execCallbackType 	=$("[name=execCallbackType]").val();
	        var tabVal 		=$("[name=tabValue]").val();

		if(phNo!="" && email!="")
		{ 
			if(validateCallBack(email,"email") && validateCallBack(phNo,"phone"))
			{
		                $.post("/membership/addCallBck",{'phNo':phNo,'email':email,'jsSelectd':selId,'execCallbackType':execCallbackType,'tabVal':tabVal},function(response)
				{
					$("#callBackLayer").css("display","none");
					if(execCallbackType=='JS_ALL'){
						$("#alertSuccess").css("top","-62px");
        	                		$("#alertSuccess").css("left","546px");
					}
					else{
                                                $("#alertSuccess").css("top","-126px");
                                                $("#alertSuccess").css("left","-110px");
					}
					$("#alertSuccess").css("display","block");
					$("#alert-data").html(response);
				});
			}
		}
		else
		{
			validateCallBack(phNo,"phone");
			validateCallBack(email,"email");
		}
        });

	$("#callEmail").change(function(){
		var email=$("#callEmail").val();
		validateCallBack(email,"email");
	});
	
	 $("#callPhNo").change(function()
	{
                var phNo=$("#callPhNo").val();
                validateCallBack(phNo,"phone");
        });
});

function validateCallBack(val,type) {
    var regEx;
    var field;
    var flag=true;
    if(type=="email")
    {
    	regEx=/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;
	field="Email ID";
    }
    else if(type=="phone")
    {
	regEx=/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/;
	field="phone number";
    }
    if( !regEx.test( val ) ) 
    {
                $("#"+type+"Err").attr("class","err-msg");
	        $("#"+type+"Err").html("<i class='icon sprte-mem'></i>Enter a valid "+field);
		flag=false;
   } else {
            $("#"+type+"Err").attr("class","");
            $("#"+type+"Err").html("");
      }
   return flag;
}
function execCallback(tabVal,execCallbackType)
{
	$("[name=execCallbackType]").val(execCallbackType);
	$("[name=tabValue]").val(tabVal);
}

