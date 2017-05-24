/////////////////////////////////////////////////////
//////////////////////Fixing Jquery.browser.msie... issue in autoSuggest_jq_js file
var matched, browser;

jQuery.uaMatch = function( ua ) {
    ua = ua.toLowerCase();

    var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
        /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
        /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
        /(msie) ([\w.]+)/.exec( ua ) ||
        ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
        [];

    return {
        browser: match[ 1 ] || "",
        version: match[ 2 ] || "0"
    };
};

matched = jQuery.uaMatch( navigator.userAgent );
browser = {};

if ( matched.browser ) {
    browser[ matched.browser ] = true;
    browser.version = matched.version;
}

// Chrome is Webkit, but Webkit is also Safari.
if ( browser.chrome ) {
    browser.webkit = true;
} else if ( browser.webkit ) {
    browser.safari = true;
}

jQuery.browser = browser;




////////////////////////////////////////////

var cityDefault='';
var nativeStateVal='all';

$("#india").bind('click',function(){

	restoreCountryList();
	
	activateIndia();
	
	assignValue_Country(countryDefault);
	$("#native_state").show();
	$('#native_country').hide();
	
	enableNativeState();
	$('#reg_native_state').removeClass("fadeOut");
	$("#native_city").hide();
	
	$("#reg_ancestral_origin").val("");
	$("#native_place").hide();

});
$("#out_india").bind('click',function(){	
	
	activateOutSide_India();
	
	if($('#reg_native_country').val()==51)
		assignValue_Country("");
	assignValue_City("");
	disableNativeState();
	$('#reg_native_state').addClass("fadeOut");
	$("#native_state").hide();
	$('#native_country').show();
	$('#native_city').hide();
	
	$("#native_place").hide();
	//Clear Native_Place Text
	$("#reg_ancestral_origin").val("");
	$("#err_ancestral_origin").html("");
	$("#err_ancestral_origin").css("display","none");
	//Update Country list
	removeIndia();
});

function activateIndia()
{
	$("#india").addClass("tabact");
	$("#india").removeClass("tabnotact");
	
	$("#out_india").removeClass("tabact");
	$("#out_india").addClass("tabnotact")
}

function activateOutSide_India()
{
	$("#out_india").addClass("tabact");
	$("#out_india").removeClass("tabnotact");
	
	$("#india").removeClass("tabact");
	$("#india").addClass("tabnotact");
}

function brotherCallBack()
{
	if($('#reg_t_brother').val().length == "")
	{
		$('#reg_m_brother').attr("disabled","disabled");
		$('#reg_m_brother').addClass("fadeOut");
		$('#married_field').css("display","block");
	}
	else
	{
		$('#reg_m_brother').removeClass("fadeOut");
		$("#reg_m_brother").removeAttr('disabled');
	}
}
function sisterCallBack()
{
	if($('#reg_t_sister').val().length == 0)
	{
		$('#reg_m_sister').attr("disabled","disabled");
		$('#reg_m_sister').addClass("fadeOut");
		$('#married_field_sis').css("display","block");
	}
	else
	{
		$('#reg_m_sister').removeClass("fadeOut");
		$("#reg_m_sister").removeAttr('disabled');
	}
}
function populate_married_count(count_for)
{
	var total_count = $("#reg_t_"+count_for).val();
	var married_count_id = $("#reg_m_" + count_for);

	married_count_id.find('option').remove();
	married_count_id.append('<option value="">Select</option>');
	for(var i=0;i<=total_count;i++)
	{
		if(i>3)
			married_count_id.append('<option value="3+">3+</option>');
		else
			married_count_id.append('<option value="'+i+'">'+i+'</option>');
	}
}

function married_field_brothers()
{
	var bro=$('#reg_t_brother').val();
	if(bro>0)
	{
		  $('#married_field').css("display","block");
	}
	else
		  $('#married_field').css("display","none");

	populate_married_count('brother');
	brotherCallBack();
}
function married_field_sisters()
{
	var sis=$('#reg_t_sister').val();
	if(sis>0)
	{
		  $('#married_field_sis').css("display","block");
	}
	else
		  $('#married_field_sis').css("display","none");

	populate_married_count('sister');
	sisterCallBack();
}

brotherCallBack();
sisterCallBack();


