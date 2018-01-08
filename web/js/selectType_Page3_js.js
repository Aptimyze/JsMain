var commonAllUrl="/static/autoMobileSelect";
var commonUrl="/static/autoSelect";

//Native City
var cityNativeOption={url:commonUrl+"?t=native_city",autofill:true,onlyAjax:true,matchAlgo:true,defaultValue:cityDefault,dependant:true,id:"reg_native_city",customFunction:CallNativeCity,type:'CITY',placeholder_text:"Select City"};
$("#reg_native_city").AutoSug(cityNativeOption);


//Native Country
var 
countryNativeOption={url:commonUrl+"?t=country",autofill:true,onlyAjax:true,matchAlgo:true,defaultValue:countryDefault,id:"reg_native_country",stay_open:false,customFunction:CallNativeCountry,type:'COUNTRY',placeholder_text:"Select Country",autofill:false};
$("#reg_native_country").AutoSug(countryNativeOption);	

//Native State
var stateNativeOption={url:commonUrl+"?t=native_state",onlyAjax:true,matchAlgo:true,defaultValue:cityDefault,id:"reg_native_state",stay_open:false,dependantOption:cityNativeOption,preCheckCall:"CallStateVisible",customFunction:CallNativeState,placeholder_text:"Select State",autofill:false,type:'STATE'};
$("#reg_native_state").AutoSug(stateNativeOption);	


function CallStateVisible()
{
	return true;
}

function CallNativeState(val)
{
	if(val.length==2)
	{
		$('#native_city').show();
		$('#reg_native_city').val("");
		$('#reg_native_city').trigger("liszt:updated");
		$('#reg_native_country').val(countryDefault);
		$("#reg_native_country").trigger("liszt:updated");
	}
	else 
	{
		$('#native_place').hide();
	}
	
}

function CallNativeCity(val)
{
	if(val==0)//Other Case
	{
		$('#native_place').show();
	}
	else
	{
		$("#reg_ancestral_origin").val("");
		$('#native_place').hide();
	}
}

function CallNativeCountry(val)
{
	$('#native_place').hide();
}
