function dppAge(json,realJson,indexPos)
{

	var ele=$(this).find("div[data=1]");
	var i=0;
	var ampm="";
	var label=new Array();
	var val=new Array();
	var pl=json['p_lage'];
	var ph=json['p_hage'];
	$.each(pl,function(key,value){
		label[0]=value;
		val[0]=key;
	});
	$.each(ph,function(key,value){
		label[1]=value;
		val[1]=key;
	});
	
	realJson[jsonEntry][indexPos].value=val.join(",");
	realJson[jsonEntry][indexPos].label_val=label[0]+" - "+label[1]+" years of age";
	
	$(ele[0]).html(realJson[jsonEntry][indexPos].label_val);
	UpdateCommonOverlay(ele[0]);
	CommonOverlayEditUpdate(val[0],"P_LAGE");
	CommonOverlayEditUpdate(val[1],"P_HAGE");
}
function dppHeight(json,realJson,indexPos)
{

	var ele=$(this).find("div[data=1]");
	var i=0;
	var ampm="";
	var label=new Array();
	var val=new Array();
	var pl=json['p_lheight'];
	var ph=json['p_hheight'];
	$.each(pl,function(key,value){
		label[0]=key;
		val[0]=value;
	});
	$.each(ph,function(key,value){
		label[1]=key;
		val[1]=value;
	});
	
	
	realJson[jsonEntry][indexPos].value=val.join(",");
	realJson[jsonEntry][indexPos].label_val=label[0]+" - "+label[1];
	
	$(ele[0]).html(realJson[jsonEntry][indexPos].label_val);
	UpdateCommonOverlay(ele[0]);
	
	CommonOverlayEditUpdate(val[0],"P_LHEIGHT");
	CommonOverlayEditUpdate(val[1],"P_HHEIGHT");
}
function dppCountry(json,realJson,indexPos)
{
	var ele=$(this).find("div[data=1]");
	var arr=new Array();;
	var i=0;
	
		$("#P_CITY_TOP").addClass("dn");
	$.each(json['p_country'],function(key,value){
		arr[i]=value;
		
		if(parseInt(value)==51 || parseInt(value)==128)
		{
				$("#P_CITY_TOP").removeClass("dn");
		}
		i++;	
	});
	storeJson['p_city']=arr.join(",");
	UpdateSection.call(this,json,realJson,indexPos);
}
function dppReligion(json,realJson,indexPos)
{
		//console.log("update religion");
	//console.log(json);

	var ele=$(this).find("div[data=1]");
var arr=new Array();
var i=0;
find=0;
var change=1;
var removeCaste=0;
$("#P_CASTE_TOP").addClass('dn');
$("#P_SECT_TOP").addClass('dn');

var realReligionStr=realJson.OnClick[0].value;


	$.each(json["p_religion"],function(key,value){
		arr[i]=parseInt(value);
		if(parseInt(arr[i])==2 || parseInt(arr[i])==3)
			find++;
                if(parseInt(arr[i])==5||parseInt(arr[i])==6||parseInt(arr[i])==10||parseInt(arr[i])==8||parseInt(arr[i])==7)
                    removeCaste++;
		if(realReligionStr.indexOf(value)!=-1 || realReligionStr =="" )
		{
			change=0;
		}
		i++;	
	});
	if(find==arr.length-removeCaste && arr.length>0)
		$("#P_SECT_TOP").removeClass('dn');
	else if(arr.length>0)
		$("#P_CASTE_TOP").removeClass('dn');
        if(removeCaste==arr.length)
        {
            $("#P_SECT_TOP").addClass('dn');
            $("#P_CASTE_TOP").addClass('dn');
        }
	storeJson['p_caste']=arr.join(",");	
	if(change)
	{
		$("#P_SECT").addClass("color2").removeClass("color3o").html(NOT_FILLED_IN).attr("value","");
		$("#P_CASTE").addClass("color2").removeClass("color3o").html(NOT_FILLED_IN).attr("value","");
	}
	if(!json["p_caste"])
		 CommonOverlayEditUpdate("","P_CASTE");
	if($("#P_SECT_TOP").hasClass("dn") && $("#P_CASTE_TOP").hasClass("dn") )
            dppCaste({"p_caste":{}},realJson,1);
	UpdateSection.call(this,json,realJson,indexPos);
}

function updateDppChallenge(json,realJson,indexPos)
{       
        var flag=0;
        var ele=$("#P_NCHALLENGED");
	$("#P_NCHALLENGED_TOP").addClass("dn");
	$.each(json["p_challenged"],function(key,value){
		if(value!='N'&&value!='3'&&value!='4')
                    flag=1;
	});
        if(flag==1)
            $("#P_NCHALLENGED_TOP").removeClass("dn");
        else
        {
            realJson["OnClick"][6]["value"]="";
            CommonOverlayEditUpdate("","p_nchallenged");
            $("#P_NCHALLENGED").html(NOT_FILLED_IN).attr("value","");
        }
	UpdateSection.call(this,json,realJson,indexPos);
	return;
}

function dppCaste(json,realJson,indexPos)
{
	$("#P_SECT").addClass("color3o").removeClass("color2");
	$("#P_CASTE").addClass("color3o").removeClass("color2");
	UpdateSection.call(this,json,realJson,indexPos);
}
function updateTimeToCall(json,realJson,indexPos)
{
	//console.log("time to call");
	//console.log(json);

	var ele=$(this).find("div[data=1]");
	var i=0;
	var ampm="";
	var label=new Array();
	var keyname=new Array();
	
	var tts=json['time_to_call_start'];
	var tte=json['time_to_call_end'];
	label[0]=TimeStartUpdate(tts,"TIME_TO_CALL_START","time_to_call_start","start_am_pm");
	label[1]=TimeStartUpdate(tte,"TIME_TO_CALL_END","time_to_call_end","end_am_pm");
	
	
	realJson[jsonEntry][indexPos].value=label.join(",");
	realJson[jsonEntry][indexPos].label_val=label.join(" to ");
	//console.log(realJson);
	$(ele[0]).html(label.join(" to "));
	UpdateCommonOverlay(ele[0]);
}
function TimeStartUpdate(tts,keyName,label1,label2)
{
	var label="";
	var submitKey={};
	$.each(tts ,function(key,value){
		var tempArr=value.split(" ");
		var keyArr=tempArr[0].split(":");
		
		var ampm="PM";
		if(tempArr[1]!=ampm)
			ampm="AM";
		
		submitKey[keyName]={};
		submitKey[keyName][label1]=keyArr[0];
		submitKey[keyName][label2]=ampm.toLowerCase();
		label=value;
	});
	submitObj.pushContactJson(submitKey);
	return label;
}
function updateEducation(json,realJson,indexPos)
{
	
	if(json)
	{
		var val=-1;
		var findpg=0;
		var findug=0;
		$.each(json["edu_level_new"],function(key,value){
		val=value;	
		});
			if(val!=-1)
			{
				var data=(JSON.parse(staticTables.getData("DEGREE_PG")));
				$.each(data[0],function(k1,v1){
					$.each(v1,function(key,value){	
						if(parseInt(key)==parseInt(val))
						{
							findpg=1;
							findug=1;
						}
					});
				});
				if(!findpg){
					var data=(JSON.parse(staticTables.getData("DEGREE_UG")));
					$.each(data[0],function(k1,v1){
						$.each(v1,function(key,value){
							if(parseInt(key)==parseInt(val) && parseInt(key)!=23 && parseInt(key)!=24)
							{
								findug=1;
								findpg=0;
								
							}
						});
					});
				}
			}
			
			if(findpg)
			{
				$("#DEGREE_PG_TOP").removeClass("dn");
				$("#PG_COLLEGE_TOP").removeClass("dn");
				if(realJson.OnClick[1].label_val =="N_B")
				{
					$("#DEGREE_PG").text("");
				}
				if(realJson.OnClick[2].label_val =="N_B")
				{
					$("#PG_COLLEGE").val("");
				}
				
			}
			else
			{
				$("#DEGREE_PG_TOP").addClass("dn");
				$("#PG_COLLEGE_TOP").addClass("dn");
			}
			if(findpg || findug)
			{
			
				$("#DEGREE_UG_TOP").removeClass("dn");
				$("#COLLEGE_TOP").removeClass("dn");
				if(realJson.OnClick[1].label_val =="N_B")
				{
					$("#DEGREE_UG").text("");
				}
				if(realJson.OnClick[2].label_val =="N_B")
				{
					$("#COLLEGE").val("");
				}
        if(!findpg)//For reseting rest pg fields
        {
          json['degree_pg'] = "";
          json['pg_college'] = "";
        }
			}
			else
			{
				$("#DEGREE_UG_TOP").addClass("dn");
				$("#COLLEGE_TOP").addClass("dn");
        if(!findpg)//For reseting rest pg fields
        {
          json['degree_pg'] = "";
          json['pg_college'] = "";
        }
        if(!findug)//For reseting rest ug fields
        {
          json['degree_ug'] = "";
          json['college'] = "";
        }
        
			}
			
	}
	UpdateSection.call(this,json,realJson,indexPos);

}
function updateSibling(json,realJson,indexPos)
{
		//console.log("update sibling");
	//console.log(json);

	
	var ele=$(this).find("div[data=1]");
	var labelArr=new Array();
	var valueArr=new Array();
	var t_sibling="t_sister";
	var m_sibling="m_sister";
	if(realJson[jsonEntry][indexPos].key=="T_BROTHER")
	{
		t_sibling="t_brother";
		m_sibling="m_brother";
	}	
	i=0;
	$.each(json[t_sibling],function(key,value){
		valueArr[i]=value;
		labelArr[i]=key;
		i++;
			CommonOverlayEditUpdate(valueArr.join(","),t_sibling.toUpperCase());
	});
	if(!json[m_sibling])
	{
		//console.log("coming");
		//console.log(json);
		json[m_sibling]=json[t_sibling];
	}
	$.each(json[m_sibling],function(key,value){
		valueArr[i]=value;
		labelArr[i]=key;
		i++;
			CommonOverlayEditUpdate(value,m_sibling.toUpperCase());
	});
	
	
		if(realJson[jsonEntry][indexPos].key=="T_BROTHER")
			if(valueArr[0]>0)
				label=labelArr[0]+" brother"+(valueArr[0]>1?"(s)":"")+" of which married "+labelArr[1];
			else if(labelArr[0]=="None")
				label=labelArr[0];
			else	
				label=labelArr[0]+" brother";
		if(realJson[jsonEntry][indexPos].key=="T_SISTER")
			if(valueArr[0]>0)
				label=labelArr[0]+" sister"+(valueArr[0]>1?"(s)":"")+" of which married "+labelArr[1];
			else if(labelArr[0]=="None")
				label=labelArr[0];
			else
				label=labelArr[0]+" sister";
		
		CommonJsonUpdate(ele,realJson,indexPos,labelArr[0]+","+labelArr[1],label);
}
function UpdateSection(json,realJson,indexPos)
{
	//console.log("update sction");
	//console.log(json);
	//console.log(realJson);
	var ele=$(this).find("div[data=1]");
	
	var labelArr=new Array();
		var valueArr=new Array();
		$i=0;
	var typeis="";
	$.each(json,function(k,v)
	{	
		typeis=k;
		$in=0;
		var inValueArr=new Array();
		var inLabelArr=new Array();
		$.each(v,function(key,value){
			inValueArr[$in]=valueArr[$i]=value;
			inLabelArr[$in]=labelArr[$i]=key;
			$i++;
			$in++;
		});
		if(k=="p_sect")
			k="p_caste";
		
		CommonOverlayEditUpdate(inValueArr.join(","),k.toUpperCase());
	});

	
	//For caste,sect append religion.
	if(typeis=="caste" || typeis=="sect")
		labelArr[0]=$("#RELIGION").html()+": "+labelArr[0];
	var joinStr=", ";
		if(typeis=="p_hrs" || typeis=="p_hds")
		{
			//If and above is selected.
			if(parseInt(valueArr[1])==19)
				joinStr=" ";
			else
				joinStr=" to ";
		}
			
	
	CommonJsonUpdate(ele,realJson,indexPos,valueArr.join(","),labelArr.join(joinStr));

}
function UpdateCountryCitySection(json,realJson,indexPos)
{
	var ele=$(this).find("div[data=1]");
	var valueStr="";
	var i=0;
	var cityPresent=0;
	$.each(json,function(k,v)
	{
		$.each(v,function(key,value){
			if(i==0){
				keyStr=key;
				ValueStr=value;
			}
			else{
				keyStr= keyStr+" - "+key;
				ValueStr=ValueStr+","+value;
			}
			i++;
			CommonOverlayEditUpdate(value,k.toUpperCase());
			if(k=="city_res")
				cityPresent=1;
		});
		
	});
	if(!cityPresent)
		CommonOverlayEditUpdate("","CITY_RES");
	CommonJsonUpdate(ele,realJson,indexPos,ValueStr,keyStr);
}
function CommonJsonUpdate(ele,realJson,indexPos,value,label)
{
	//console.log(realJson);
	realJson[jsonEntry][indexPos].value=value;
	realJson[jsonEntry][indexPos].label_val=label;
	//console.log(realJson);
	$(ele[0]).html(label);
	if(!label && realJson[jsonEntry][indexPos].key.indexOf("P_")!=-1)
		$(ele[0]).html("Doesn't matter");
		
	$(ele[0]).attr("value",value);
	UpdateCommonOverlay(ele[0]);
}
function CommonOverlayEditUpdate(value,key)
{
            submitObj.push(key.toUpperCase(),value);	
}
function UpdateCommonOverlay(ele)
{
	$(ele).parent().removeClass("notfilled");
}
function updateSectionContact(obj)
{
	var tabId=obj.id;
	var tabValue=obj.value;
	var oriValue=tabValue;
	var allowed=0;
	if(tabId=="ISD" || tabId=="RES_ISD" || tabId=="ALT_ISD" || tabId=="WEIGHT")
		allowed=3;
	if(tabId=="PHONE_MOB" || tabId=="ALT_MOBILE")
		allowed=14;
	if(tabId=="PHONE_RES")
		allowed=10;
	
	if(allowed)
	{
		
		tabValue=tabValue.replace(/([^0-9])+/i, "");
		var exp=eval("/([0-9]{1,"+allowed+"})[\\w]*/i");
		tabValue=tabValue.replace(exp, "$1");
		if(oriValue!=tabValue)
			$(obj).val(tabValue);
			
	}
	if(tabId=="ISD" || tabId=="RES_ISD" || tabId=="ALT_ISD")
	{
		if(!(oriValue==$("#ISD").val() && oriValue==$("#ALT_ISD").val() && oriValue==$("#RES_ISD").val()))
		if(oriValue==tabValue)
		{
			$("#ISD").val(tabValue);
			$("#RES_ISD").val(tabValue);		
			$("#ALT_ISD").val(tabValue);	
			
		}
		else
			$(obj).val(tabValue);
	}
	
	var newJson={};
	if(tabId=="PHONE_MOB" || tabId=="ISD" || tabId=="PHONE_RES" || tabId=="RES_ISD" || tabId=="STD"){
			newJson["PHONE_MOB"]={};
			newJson["PHONE_MOB"]["mobile"]=$("#PHONE_MOB").val();
			newJson["PHONE_MOB"]["isd"]=$("#ISD").val();
			newJson["PHONE_RES"]={};
			newJson["PHONE_RES"]["isd"]=$("#RES_ISD").val();
			newJson["PHONE_RES"]["std"]=$("#STD").val();
			newJson["PHONE_RES"]["landline"]=$("#PHONE_RES").val();
	}
	else if(tabId=="ALT_MOBILE" || tabId=="ALT_ISD"){
			newJson["ALT_MOBILE"]={};
			newJson["ALT_MOBILE"]["isd"]=$("#ALT_ISD").val();
			newJson["ALT_MOBILE"]["mobile"]=$("#ALT_MOBILE").val();
	}
	else if(tabId=="EMAIL"){
		if(pageJson.Contact.EMAIL.OnClick[1].label_val!=$("#EMAIL").val())
			newJson["EMAIL"]=$("#EMAIL").val();
		else
			submitObj.pop('EMAIL');
	}
	else if(tabId=="PROFILE_HANDLER_NAME")
		newJson["PROFILE_HANDLER_NAME"]=$("#PROFILE_HANDLER_NAME").val();
	else {
		newJson[tabId]=$("#"+tabId).val();
		//console.log($("#"+tabId).val());
	}
	
	submitObj.pushContactJson(newJson);

	
}
function updateChallenge(json,realJson,indexPos)
{      
	$("#NATURE_HANDICAP_TOP").addClass("dn");
	$.each(json.handicapped,function(key,value){
		if(parseInt(value)==1 || parseInt(value)==2)
		{
			$("#NATURE_HANDICAP_TOP").removeClass("dn");
		}
	});
	UpdateSection.call(this,json,realJson,indexPos);
	return;
}
function updateHandicap(json,realJson,indexPos)
{
	var keys=$("#HANDICAPPED").html();
	var val=$("#HANDICAPPED").attr("value");
	var kv={};
	var ele=$(this).find("div[data=1]");
	kv[keys]=val;
	var newJson={"handicapped":kv};
	CommonOverlayEditUpdate(val,"handicapped");
	$.each(json.nature_handicap,function(key,value){
		CommonJsonUpdate(ele,realJson,indexPos,value,key);
		CommonOverlayEditUpdate(value,"nature_handicap");
	});
	
	
	//UpdateSection.call(this,json,realJson,indexPos);
	
	return;
}
	
function updateLifestyle(json,realJson,indexPos)
{
	var ele=$(this).find("div[data=1]");
	var ValueStr="";
	var keyStr="";
	var i=0;
	$.each(json,function(k,v)
	{
		$.each(v,function(key,value){
			if(i==0){
				keyStr=key;
				ValueStr=value;
			}
			else{
				keyStr= keyStr+" , "+key;
				ValueStr=ValueStr+","+value;
			}
			i++;
			//CommonOverlayEditUpdate(value,k.toUpperCase());
		});
		
	});
		CommonJsonUpdate(ele,realJson,indexPos,ValueStr,keyStr);
		if(realJson.OnClick[0].key=="HOBBIES_LANGUAGE")
			CommonOverlayEditUpdate(realJson.OnClick[0].value,"hobbies_language");
		else
			CommonOverlayEditUpdate(pageJson.Lifestyle.Skills.OnClick[0].value,"hobbies_language");
			
		if(realJson.OnClick[0].key=="HOBBIES_HOBBY")
			CommonOverlayEditUpdate(realJson.OnClick[0].value,"hobbies_hobby");
		else
			CommonOverlayEditUpdate(pageJson.Lifestyle.hobbies.OnClick[0].value,"hobbies_hobby");	
			
		if(realJson.OnClick[0].key=="HOBBIES_INTEREST")
			CommonOverlayEditUpdate(realJson.OnClick[0].value,"hobbies_interest");
		else
			CommonOverlayEditUpdate(pageJson.Lifestyle.Interests.OnClick[0].value,"hobbies_interest");
			
		if(realJson.OnClick[0].key=="HOBBIES_MUSIC")
			CommonOverlayEditUpdate(realJson.OnClick[0].value,"hobbies_music");	
		else
			CommonOverlayEditUpdate(pageJson.Lifestyle.Favourite.OnClick[0].value,"hobbies_music");	
			
		if(realJson.OnClick[0].key=="HOBBIES_BOOK")
			CommonOverlayEditUpdate(realJson.OnClick[0].value,"hobbies_book");
		else
			CommonOverlayEditUpdate(pageJson.Lifestyle.Favourite.OnClick[1].value,"hobbies_book");
			
		if(realJson.OnClick[0].key=="HOBBIES_DRESS")
			CommonOverlayEditUpdate(realJson.OnClick[0].value,"hobbies_dress");
		else
			CommonOverlayEditUpdate(pageJson.Lifestyle.Favourite.OnClick[2].value,"hobbies_dress");
			
		if(realJson.OnClick[0].key=="HOBBIES_SPORTS")
			CommonOverlayEditUpdate(realJson.OnClick[0].value,"hobbies_sports");
		else
			CommonOverlayEditUpdate(pageJson.Lifestyle.Favourite.OnClick[4].value,"hobbies_sports");	
			
		if(realJson.OnClick[0].key=="HOBBIES_CUISINE")
			CommonOverlayEditUpdate(realJson.OnClick[0].value,"hobbies_cuisine");
		else
			CommonOverlayEditUpdate(pageJson.Lifestyle.Favourite.OnClick[5].value,"hobbies_cuisine");
			
}

/**
 * updateNative
 * @param {type} json
 * @param {type} realJson
 * @param {type} indexPos
 * @returns {undefined}
 */
function updateNative(json,realJson,indexPos)
{
  var process =false;

  //Remove Not from India from Output
  if (json.hasOwnProperty('native_state') && json['native_state'].hasOwnProperty('Outside India')) {
    delete json['native_state']['Outside India'];
    json['native_city'] = {};
  }
  
  var bFromIndia = 0;
  //Remove From India from Output
  if (json.hasOwnProperty('native_country') && json['native_country'].hasOwnProperty('From India')) {
    delete json['native_country']['From India'];
    bFromIndia = 1;
  }
  
  //Check do we need to process this request
  $.each(json,function(key,value){
    if(typeof value == "object" && Object.keys(value).length){
      process =true;
    }
  });
  
  if(false == process){
    return ;
  }
  
  if (json.hasOwnProperty('native_state') && false === json.hasOwnProperty('native_city')) {
    json['native_city'] = {};
  }
  
  //If From India got selected then update fields and hide country field and show state field
  if(bFromIndia){
    $('#NATIVE_STATE_TOP').removeClass('dn');
    $('#NATIVE_COUNTRY_TOP').addClass('dn');
    var pos = $('#NATIVE_STATE_TOP').attr('dindexpos');
    //Update State and city values
    UpdateSection.call($('#NATIVE_STATE_TOP'),json,realJson,pos);
    json['native_country']['India'] = '51';
  }
  
  //Update Ancestral Origin(please specifiy field), basically reset its value
  if(json.hasOwnProperty('native_city')  && json['native_city'].hasOwnProperty('Others')) {
    $('#ANCESTRAL_ORIGIN_TOP').removeClass('dn');
  } else {
    $('#ANCESTRAL_ORIGIN_TOP').addClass('dn');
  }
  
  if(json.hasOwnProperty('native_city') || json.hasOwnProperty('native_country')) {
    $('#ANCESTRAL_ORIGIN').attr('value','');
    $('#ANCESTRAL_ORIGIN_TOP').find('#underscreening').addClass('dn');
    var pos = $('#ANCESTRAL_ORIGIN_TOP').attr('dindexpos');
    UpdateSection.call($('#ANCESTRAL_ORIGIN_TOP'),{'ancestral_origin':''},realJson,pos);
  }

	
  UpdateSection.call(this,json,realJson,indexPos);
	return;
}
