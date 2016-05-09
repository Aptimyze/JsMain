function checkData()
{
	var lage = parseInt($.trim($("#lage").val()));
	var hage = parseInt($.trim($("#hage").val()));
	var lheight = parseInt($.trim($("#lheight").val()));
	var hheight = parseInt($.trim($("#hheight").val()));
	var lincome = parseInt($.trim($("#lincome").val()));
	var hincome = parseInt($.trim($("#hincome").val()));
	var flag = 0;

	if(lage>hage)
	{
		$("#age_error").show();
		flag = 1;
	}
	else
	{
		$("#age_error").hide();
	}

	if(lheight && hheight)
	{
		$("#height_error1").hide();
		if(lheight>hheight)
		{
			$("#height_error4").show();
			flag = 1;
		}
		else
		{
			$("#height_error4").hide();
		}
	}
	else
	{
		$("#height_error4").hide();
		if(lheight && !hheight)
		{
			$("#height_error1").show();
			$("#height_error3").show();
			$("#height_error2").hide();
			$("#height_error5").show();
			flag = 1;
		}
		else if(!lheight && hheight)
		{
			$("#height_error1").show();
			$("#height_error2").show();
			$("#height_error3").hide();
			$("#height_error5").hide();
			flag = 1;
		}
		else
		{
			$("#height_error1").hide();
		}
	}
	if((lincome || lincome=='0') && (hincome || hincome=='0'))
	{
		$("#income_error1").hide();
		if(hincome==19)
			hincome = 30;
                if(lincome>=hincome)
                {
                        $("#income_error4").show();
			flag = 1;
                }
                else
                {
                        $("#income_error4").hide();
                }
	}
	else
	{
		$("#income_error4").hide();
                if((lincome || lincome =='0') && !hincome)
                {
                        $("#income_error1").show();
                        $("#income_error3").show();
                        $("#income_error2").hide();
                        $("#income_error5").show();
			flag = 1;
                }
                else if(!lincome && (hincome || hincome=='0'))
                {
                        $("#income_error1").show();
                        $("#income_error2").show();
                        $("#income_error3").hide();
                        $("#income_error5").hide();
			flag = 1;
                }
                else
                {
                        $("#income_error1").hide();
                }
	}
	if(flag == 1)
		return false;
	else
		return true;
}

$(document).ready(function () {
	
	$("#search_by_profileid_btn").click(function(){
		setTimeout(function(){

		var username = $.trim($("#search_username").val());
		if(username)
		{
                    if (username.indexOf("@") > -1) {
                       $("#email_error").show();    
                       $("#boxContainer").height("100px");
                    }
                    else {
			$("#search_profile").attr('action', SITE_URL+"/profile/viewprofile.php?search=1&overwrite=1&stype=WO&username="+escape(username));
			$("#search_profile").submit();
                    }
		}
		return false;
		},1000);
	});

	$("#religion").change(function(){
		var religion = $("#religion").val();
		if(religion=="DONT_MATTER" || religion=="")
			var id = "religion0";
		else
			var id = "religion"+religion;
		$("#caste").html($("#"+id).val());
	});

	$("#more-btn").click(function(){
		if($("#btn-text").html()=="More options")
		{
			$("#btn-text").html("Close options");
			$("#icon_plus_minus").removeClass('iconplus').addClass('iconminus');
			$("#more_search_params").show();
			$("#more_options_btn").val("Y");
		}
		else
		{
			$("#btn-text").html("More options");
			$("#icon_plus_minus").removeClass('iconminus').addClass('iconplus');
			$("#more_search_params").hide();
			$("#more_options_btn").val("N");
		}
	});
});
