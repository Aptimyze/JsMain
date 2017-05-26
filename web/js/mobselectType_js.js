var commonAllUrl="/static/autoMobileSelect";
var commonUrl="/static/autoSelect";
var AllJson={};

//If Caste
if(casteAllowed)
{		
	var casteUrl=commonAllUrl+"?t=caste&d=null&m="+mtongueVal;
	$.get(casteUrl, {dataType: "json"},function (data){
			
				AllJson['caste']=data;
				$("#reg_religion").trigger("change");
				});
	
$("#reg_religion").AutoSug({"dependantID":"reg_caste","dependantJson":"caste","depParent":"caste_section"});
}

if(countryAllowed)
{
	/*var countryUrl=commonUrl+"?t=country&d=51";
	$.get(countryUrl, {dataType: "json"},function (data){
		$.each(data, function(index, itemData) {
			selected="";
							if(itemData[2]==1)
									selected="selected";
							$("#reg_country_res").append("<option value="+itemData[0]+"  "+selected+" >"+itemData[1]+"</option>");
						});
	});*/
	$("#reg_country_res").val(51);
	$("#reg_country_res").trigger("change");
}
if(cityAllowed && country=='51')
{
			//Caching caste for hindu
			/*var cityUrl=commonUrl+"?t=city&d=null&l=51";
			$.get(cityUrl, {dataType: "json"},function (data){
			$.each(data,function(index,itemData) {	
					selected = "";
					if(itemData[2]==1)
					selected = "selected";
					if(itemData[3])
					{
						$("#"+id).append("<optgroup label='"+itemData[1]+"'>");
					}
					else
						$("#reg_city_res").append("<option value="+itemData[0]+"  "+selected+" >"+itemData[1]+"</option>");
					});
			});
			*/
}
