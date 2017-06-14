var commonAllUrl="/static/autoMobileSelect";
var commonUrl="/static/autoSelect";
var AllJson={};


var nativeCity=commonAllUrl+"?t=native_city&l="+nativeStateVal;
$.get(nativeCity, {dataType: "json"},function (data){
			AllJson['native_city']=data;
			$("#reg_native_state").trigger("change");
});
	
$("#reg_native_state").AutoSug({"dependantID":"reg_native_city","dependantJson":"native_city","depParent":"native_city"});

function CallNativeCountry()
{
	$('#native_place').hide();
}

function CallNativeState()
{
	if($('#reg_native_state').val().length==2)
	{
		$('#native_city').show();
		$('#reg_native_city').val("");
		$('#reg_native_city').trigger("liszt:updated");	
	}
	$('#native_place').hide();
	
}

function CallNativeCity()
{
	if($('#reg_native_city').val()=="0")//Other Case
	{
		$('#native_place').show();
	}
	else
	{
		$("#reg_ancestral_origin").val("");
		$('#native_place').hide();
	}
}
$("#reg_native_city").change(function(){
	CallNativeCity();
});
$("#reg_native_state").change(function(){
	CallNativeState();
});
