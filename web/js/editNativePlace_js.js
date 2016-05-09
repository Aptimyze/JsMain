$(document).ready(function(){	
	initJS();
});
function initJS()
{
	CallNativeCountry(); 
	assignValue_Country(countryDefault);
	$('#native_city').hide();
	if($('#reg_native_state').val())
	{
		$('#native_country').hide();
		$('#native_city').show();
		assignValue_Country(countryDefault);
		if($('#reg_native_city').val() == "0") // Other case
		{
			$('#native_place').show();
		}
	}
	else
	{
		if($('#reg_native_country').val()!=51)
		{
			if($('#reg_native_country').val() == '136')
				$('#native-place').show();
			
			$('#chk_outside_india').attr("checked","checked");
			disableNativeState();
		}
		else
		{
			$('#native_country').hide();
		}
	}	
        setTimeout(function(){
	  $('#reg_native_city_chzn').css("width",$('#reg_native_city').css("width")).css("min-width","200px");
	  $('#reg_native_state_chzn').css("width",$('#reg_native_state').css("width")).css("min-width","200px");
          $('#reg_native_state_chzn').css("width",$('#reg_native_state').css("width"));
	  $('#reg_native_country_chzn').css("width",$('#reg_native_country').css("width"));
	  $("#whole_native").css("opacity","1");
        },0);
}



$("#chk_outside_india").bind('click',function(){
	ShowHideCountry();
});
//Error Message on focusout event on open text field
$('#native_place').focusout(function(){
	var value = $('#reg_ancestral_origin').val();
	if(value == "")
	{
		$('#err_ancestral_origin').html('Please specify');
		$('#err_ancestral_origin').css('color','red');
		$('#err_ancestral_origin').css('display','block');
	}
	else
	{
		$('#err_ancestral_origin').css('display','none');
	}
});

function ShowHideCountry()
{
	if($("#chk_outside_india").prop("checked"))
	{
		if($('#reg_native_country').val()==51)
			assignValue_Country("");
		assignValue_City("");
		disableNativeState();
		
		$('#native_country').show();
		$('#native_city').hide();

		//Clear Native_Place Text
		$("#reg_ancestral_origin").val("");
		$("#err_ancestral_origin").html("");
		$("#err_ancestral_origin").css("display","none");
		//Update Country list
		removeIndia();
	}
	else
	{
		countryDefault = 51;
		restoreCountryList();
		assignValue_Country(countryDefault);
	
		$('#native_country').hide();
		
		enableNativeState();
		$("#native_city").hide();
		
		$("#reg_ancestral_origin").val("");
		
		$("#err_ancestral_origin").html("");
		$("#err_ancestral_origin").css("display","none");
	}	
}

function disableNativeState()
{
	//Disable State
	$("#reg_native_state").val("");
	$("#reg_native_state").attr("disabled","disabled");
	$("#reg_native_state").trigger("liszt:updated");
	$("#reg_native_state_chzn a span").css("color","#999");
}

function enableNativeState()
{
	$("#reg_native_state").removeAttr('disabled');
	$("#reg_native_state").trigger("liszt:updated");
	$("#reg_native_state_chzn a span").css("color","#000");
}

function assignValue_City(value)
{
	if(value.length == 0)
		value = "";
	$("#reg_native_city").val(value);
	$("#reg_native_city").trigger("liszt:updated");
}

function assignValue_Country(value)
{
	if(value.length == 0)
		value = "";
	$("#reg_native_country").val(value);
	$("#reg_native_country").trigger("liszt:updated");
}
var originalCountry = $('#reg_native_country').html();
var bDoitOnce = false;
function removeIndia()
{
	if(bDoitOnce)
		return;
	//Two occurence of India exist in list
	$("#reg_native_country").val(51);
	$("#reg_native_country option:selected").remove();

	$("#reg_native_country").val(51);
	$("#reg_native_country option:selected").remove();

	$("#reg_native_country").trigger("liszt:updated");
	bDoitOnce = true;
}

function restoreCountryList()
{
	$('#reg_native_country').html("");
	$("#reg_native_country").trigger("liszt:updated");
	$('#reg_native_country').html(originalCountry);
	$("#reg_native_country").trigger("liszt:updated");
	
	bDoitOnce = false;
}

