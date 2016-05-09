var commonAllUrl="/static/autoMobileSelect";
var commonUrl="/static/autoSelect";

//For important caste
//fetching important caste
$.get(commonUrl+"?t=impcaste&d=null&l=1&noselect=1",{dataType:"json"},function(itemData){
	
	cacheResulted.addData('importantcaste',itemData);
});

//Hide caste
$("#reg_religion").trigger("click");
var cachedCasteUrl="";
$("#reg_mtongue").bind("change",function(){
	//$(this).unbind("change");
	UpdateCasteOrder();
	});


//End of mtongue check
////////////////////

//city
var cityOption={url:commonUrl+"?t=city",autofill:false,onlyAlax:false,matchAlgo:true,defaultValue:cityDefault,dependant:true,id:"reg_city_res",customFunction:CallCity,type:'CITY',placeholder_text:"Select or Type City"};
$("#reg_city_res").AutoSug(cityOption);
//country

//$("#reg_country_res").val(countryDefault);
var countryOption={url:commonUrl+"?t=country",onlyAlax:false,matchAlgo:true,defaultValue:countryDefault,stay_open:false,dependantOption:cityOption,preCheckCall:"CallCityVisible",customFunction:CallCountry,type:'COUNTRY',placeholder_text:"Select or Type Country",autofill:false};
$("#reg_country_res").AutoSug(countryOption);	
//if back button is pressed
$(document).ready(function(){
	$("#reg_country_res").val($("#reg_country_res").val()?$("#reg_country_res").val():countryDefault);
	$("#reg_country_res").trigger("liszt:updated");
  $("#reg_city_res").val($("#reg_city_res").val()?$("#reg_city_res").val():cityDefault);
	$("#reg_city_res").trigger("liszt:updated");
  
        });

//var mtongueOption={url:commonUrl+"?t=community",onlyAlax:false,matchAlgo:true,defaultValue:"",stay_open:false,type:'MTONGUE'};

//$("#reg_mtongue").AutoSug(mtongueOption);

//caste
var casteOption={url:commonUrl+"?t=caste",onlyAlax:false,matchAlgo:true,defaultValue:casteDefault,dependant:true,id:"reg_caste",type:'CASTE',customFunction:CallCaste,placeholder_text:"Select or Type Caste"};

//Caching caste for hindu

var casteUrl=commonAllUrl+"?t=caste";

$.get(casteUrl, {dataType: "json"},function (data){
			
				$.each(data, function(index, itemData) {

						casteUrl=commonUrl+"?t=caste&l="+index+"&d="+casteDefault;
						if(index==1)
							cachedCasteUrl=casteUrl;

				cacheResulted.addData(casteUrl,itemData);
				
			});
				});
				

$("#reg_caste").AutoSug(casteOption);
//religion
var religionOption={url:"",onlyAlax:false,matchAlgo:true,defaultValue:religionDefault,stay_open:false,dependantOption:casteOption,preCheckCall:"CallCasteVisible",autofill:false,type:'RELIGION',customFunction:CallReligion,placeholder_text:"Select or Type Religion"};
$("#reg_religion").AutoSug(religionOption);

//if back button is pressed
$(document).ready(function(){
	if($("#reg_religion").val()!=0)	$("#reg_religion").trigger("change");
	});


function CallReligion(val)
{
	if(!val)
	{
		$("#reg_caste").html("");
		$("#reg_caste").trigger("liszt:updated");
	}
	
}
function CallCasteVisible(val)
{
	
	casteOption.dependant=false;
	
	return true;
}

function CallCityVisible(val)
{
	if(val==51)
	return true;
	{
	$("#reg_city_res").html("");
		$("#reg_city_res").trigger("liszt:updated");
		return false;
	}
	
}
function CallCaste()
{
	var rel_v=$("#reg_religion").val();
	var html=$("#reg_caste").prev().html();
	var err_c_html=$("#caste_required div").html()?$("#caste_required div").html():"Please provide a caste";
	if(rel_v==2 || rel_v==3)
	{
		html=html.replace("Caste","Sect");
		this.options.placeholder_text="Select or Type Sect";
		err_c_html=err_c_html.replace("caste","sect");
	}
	else
	{
		html=html.replace("Sect","Caste");
		this.options.placeholder_text="Select or Type Caste";
		err_c_html=err_c_html.replace("sect","caste");
	}
	$("#caste_error_msg").html(err_c_html);
	$("#reg_caste").prev().html(html);
	if(!$("#reg_caste").html())
		$("#reg_caste").parent().parent().css("display","none");
	else
		$("#reg_caste").parent().parent().css("display","inline");
}
function CallCity()
{
	callPincode();	
		
	if(!$("#reg_city_res").html())
		$("#reg_city_res").parent().parent().css("display","none");
	else
	{
		$("#reg_city_res").parent().parent().css("display","inline");
		if($("#reg_city_res").val())
		{
			var stdUrl = commonUrl+"?t=stdcode&d=null&l="+$("#reg_city_res").val();
			if(cacheResulted.getData(stdUrl))
				UpdateSTD(cacheResulted.getData(stdUrl));
			else
			{	
				$.get(stdUrl, {dataType: "json"},function (data){
					cacheResulted.addData(stdUrl,data);
					UpdateSTD(data);	
				});
			}
		}
	}
}
function CallCountry()
{
	if($("#reg_country_res").val())
	{
		$("#reg_phone_mob_isd").val('');
		$("#reg_phone_res_isd").val('');					
		$("#reg_phone_res_std").val('');
		
		var isdUrl = commonUrl+"?t=isdcode&d=null&l="+$("#reg_country_res").val();
			if(cacheResulted.getData(isdUrl))
				UpdateISD(cacheResulted.getData(isdUrl));
			else
			{	
                                cacheResulted.addData(isdUrl,{"0":["","+91"]})
				$.get(isdUrl, {dataType: "json"},function (data){
					cacheResulted.addData(isdUrl,data);
					UpdateISD(data);
				});
			}
	}
}


function UpdateCasteOrder()
{
	
	var mtongueVal=$("#reg_mtongue").val();
	if(!cachedCasteUrl || !mtongueVal)
		return;
		
	var casteJson=cacheResulted.getData(cachedCasteUrl);
	var impCasteJson=cacheResulted.getData("importantcaste");
	var newJson={};
	newJson[0]=Array("","Please select",1,0);
	var cnt=1,found=0;
	if(impCasteJson)
	{
		
		cnt++;
		$.each(impCasteJson,function(key,val)
		{
			if(val[0]==mtongueVal)
			{
				newJson[cnt]=Array(val[1],val[3],0,0);
				cnt++;
			}
		});
		if(cnt>2)
		{
			newJson[1]=Array("optlabel","",0,1);
			newJson[cnt]=Array("actlabel","----",0,1);
			cnt++;
		}
	}
	if(casteJson)
	{
		var dntstart=0;
		//alert(casteJson[1]);
		if(casteJson[1][0]=="optlabel")
			dntstart=1;
		$.each(casteJson,function(key,val)
		{
			if(dntstart)
			{
				if(val[0]=='actlabel')
					dntstart=0;
			}
			else
			{
					newJson[cnt]=val;
					cnt++;
			}
		});
	}
	$("#reg_caste").html("");
	
	cacheResulted.addData(cachedCasteUrl,newJson);
	
	if($("#reg_religion").val())
		$("#reg_religion").trigger("change"),$("#reg_caste").trigger("liszt:updated");

}
$(document).ready(function(){
var val=$("#reg_country_res").val();
callPincode();
if(val!=51 && val)
	CallCityVisible(val);});

function callPincode()
{
	if($("#reg_pincode").val())
		$("#reg_pincode").trigger("blur");

	if(ArrayPincode[$("#reg_city_res").val()] && $("#reg_country_res").val() == '51')
		$("#reg_pincode").parent().css("display","inline");
	else
		$("#reg_pincode").parent().css("display","none");
		
}
