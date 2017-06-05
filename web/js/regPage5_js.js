$(document).ready(function () {
	
	$("#reg").validate({
		ignore: ":checkbox",
		focusCleanup :false,
		onkeyup : false,
		onfocusout: false
		});    	
		
		$("#reg_p_lage").rules("add",{
			checkAge:true
		});
		$("#reg_p_lheight").rules("add",{
			checkHeight:true
		});
		$("#reg_p_lrs").rules("add",{
			checkRupee:true
		});
		$("#reg_p_lds").rules("add",{
			checkDollar:true
		});
	});

//City validation
jQuery.validator.addMethod("checkAge", function(value,element) {

	$("#reg_p_age").css("display","none");
	$("#reg_p_age").children(".err_msg").html('');	
	if($("#reg_p_lage").val()>$("#reg_p_hage").val())
	{
		$("#reg_p_age").css("display","block");
		$("#reg_p_age").children(".err_msg").html('Please enter valid Age range !');
		return false;
	}		
		
	return true;	
},"");
jQuery.validator.addMethod("checkHeight", function(value,element) {
	lhgt=parseInt($("#reg_p_lheight").val());
	hhght=parseInt($("#reg_p_hheight").val());
	$("#reg_p_height").css("display","none");
        $("#reg_p_height").children(".err_msg").html('');
	if(lhgt>hhght)
	{
		$("#reg_p_height").css("display","block");
		$("#reg_p_height").children(".err_msg").html('Please enter valid Height range !');
		return false;
	}		
		
	return true;	
},"");
jQuery.validator.addMethod("checkRupee", function(value,element) {
	var lrs=parseInt($("#reg_p_lrs").val());
	var hrs=parseInt($("#reg_p_hrs").val());
	$("#reg_p_hrs_err").css("display","none");
        $("#reg_p_hrs_err").children(".err_msg").html('');
	
	if(lrs!="" && hrs!="")
	{
		if(lrs>hrs && hrs!=19)
		{
			$("#reg_p_hrs_err").css("display","block");
		        $("#reg_p_hrs_err").children(".err_msg").html('Maximum income should be greater than minimum income.');
			return false;
		}
	}
	if((isNaN(lrs) && hrs>=0) || (lrs>=0 && isNaN(hrs)))
	{
		$("#reg_p_hrs_err").css("display","block");
                $("#reg_p_hrs_err").children(".err_msg").html('Please enter both the values to define income range.');
		return false;
	}
		
	return true;	
},"");
jQuery.validator.addMethod("checkDollar", function(value,element) {
	$("#reg_p_hds_err").css("display","none");
        $("#reg_p_hds_err").children(".err_msg").html('');
	var lrs=parseInt($("#reg_p_lds").val());
	var hrs=parseInt($("#reg_p_hds").val());
	if(lrs!="" && hrs!="")
	{
		if(lrs>hrs && hrs!=19)
		{
			$("#reg_p_hds_err").css("display","block");
                        $("#reg_p_hds_err").children(".err_msg").html('Maximum income should be greater than minimum income.');
			return false;
		}
	}
	if((isNaN(lrs) && hrs>=0) || (lrs>=0 && isNaN(hrs)))
	{
		$("#reg_p_hds_err").css("display","block");
		$("#reg_p_hds_err").children(".err_msg").html('Please enter both the values to define income range.');
		return false;
	}
		
	return true;	
},"");


function ajaxFunction(strSubmit){
        var ajaxRequest;  // The variable that makes Ajax possible!
        
        try{
                // Opera 8.0+, Firefox, Safari
                ajaxRequest = new XMLHttpRequest();
        } catch (e){
                // Internet Explorer Browsers
                try{
                        ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e) {
                        try{
                                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (e){
                                // Something went wrong
                                alert("Your browser broke!");
                                return false;
                        }
                }
        }
        var queryString = "registration_page5.php?ajax=1";
        
         ajaxRequest.onreadystatechange = function(){
                if(ajaxRequest.readyState == 4)
                {
                        strResponse=ajaxRequest.responseText;
                        switch (ajaxRequest.status) {
                   // Page-not-found error
                   case 404:
                           alert('Error: Not Found. The requested URL ' + 
                                   strURL + ' could not be found.');
                           break;
 // Display results in a full window for server-side errors
                   case 500:
                           handleErrFullPage(strResponse);
                           break;
                   default:
                           // Call JS alert for custom error or debug messages
                           if (strResponse.indexOf('Error:') > -1 || 
                                   strResponse.indexOf('Debug:') > -1) {
                                   alert(strResponse);
                           }
                           // Call the desired result function
                 }      
                document.getElementById("count").innerHTML=ajaxRequest.responseText;
                }
        }

        var str1=strSubmit; 
        ajaxRequest.open('POST',queryString,true);
        ajaxRequest.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        ajaxRequest.send(str1); 
}
function fill_default_val()
{
}

function addOption(selectbox,text,value )
{
        var optn = document.createElement("OPTION");
        optn.text = text;
        optn.value = value;
        selectbox.options.add(optn);
}


function apply_thickbox_class()
{
	imgLoader = new Image();// preload image
        imgLoader.src = tb_pathToImage;
        $('.thickbox').colorbox();
}

function changeCount(ele)
{
	str=$(ele).val();
	document.getElementById("wordCount").innerHTML=str.length;
																
}

function submit_skip_page(pg)
{
	if(pg=='pg6')
		document.form1.action=document.form1.action+'?skip_to_next_page6=1',document.form1.submit();
	if(pg=='fto')
		document.form1.action=document.form1.action+'?skip_to_fto=1',document.form1.submit();
		
	return false;
}
