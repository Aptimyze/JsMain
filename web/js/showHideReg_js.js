$(document).ready(function()
{
	var f=0;
	var farray={0:"text",1:"password"};
	var sarray={0:"Hide",1:"Show"};
	$("#reg_password").bind("paste keyup change",function(event){
             		if($("#reg_password").val().length >0 || event.type=="paste")
			{
				$("#showHide").show();
			}
			else
			{
				$("#showHide").hide();
			}
	});
	$("#showHide").bind("click",function(){
			$("#reg_password").attr("type",farray[f]);
			$("#showHide").html(sarray[f]);
			f=f?0:1;
                        $("#reg_password").focus();
	});$("#showHide").hide();
});
