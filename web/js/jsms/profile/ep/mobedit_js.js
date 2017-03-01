stopScrolling();
	var filterBasicMap = {};
	var filterReligionMap = {};
	var filterEduMap = {};
//Showing overlay layer starts here//
var overLaySection="";
var overLayerParent="";
var submitObj=new saveDetail;
var staticTables=new SessionStorage;
var overLayCancelHtml="";
var validatorFormId="";
var storeJson={};
var hamburgerObj={};
var overlayObj={};
var NOT_FILLED_IN="Not filled in";

	function overlaySet(){
            
		pageJson=changingEditData;
		
		loaderHtml=$("#loader").html();
		//Orginal Template Stored
		overLayTemplate=$("#overLayer").html();
		cancelOverLayerTemplate=$("#cancelOverLayer").html();
		
		overLayHtml=$("#overLaySection").html();
		overLayCancelHtml=$("#PromptSectionName").html();
		
		//removing div
		$("#overLaySection").remove();
		
		//Parent Div
		overLayerParent=$("#overLayer").html();
		
		
		
		$("div[slideoverlayer]").each(function(){
			var attr=$(this).attr("slideoverlayer");
			var ele=this;
			
			$(this).bind(clickEventType,function(){
				
				setOverlayLocation();
				UpdateOverlayLayer(attr);
                                RemoveDnClass(pageJson,attr);
				 
				return false;
		});
		});
		
		
	}
function setOverlayLocation()
{
	historyStoreObj.push(function(){
		
		if($("#cancelOverLayer").hasClass("dn") && $("#ed_slider").hasClass("dn"))
		{
			$("#CancelSub").trigger("click");
			return true;
		}
		
		else if(!$("#cancelOverLayer").hasClass("dn") && $("#ed_slider").hasClass("dn"))
		{
			$("#discardAfterCancelOverlay").trigger("click");
			return true;
		}
		return false;
		
		
	},"#overlay");
}
function RemoveDnClass(json,attr)
{
    $("#overLayer").removeClass('dn');
    setTimeout(function(){showOverLayer(json,attr);},10)
}
function showOverLayer(json,attr)
{
    try{
	$("#overLayer").removeClass("right_1").css("width","100%").css('height',$(window).height()).css("overflow","auto");
	//setTimeout(function(){$("#overLayer").},10);
	setTimeout(function(){$("#ed_slider").addClass("dn");
		
		
		//$("#overlayContent").OnlyVertical({});
		
		
		},animationtimer);
	var height=$(window).height()-$("#overlayHead").outerHeight();
	
	$("#overlayContent").addClass("overlayc").height(height);
	
	$("#CancelSub").unbind(clickEventType);
	$("#SaveSub").unbind(clickEventType);
	$("#CancelSub").bind(clickEventType,function(){
		CancelSub(json,attr);
		
	});
	$("#SaveSub").bind(clickEventType,function(){
		SaveSub(json,attr);
	});
        
	var arr=attr.split(",");
	var dataFetch=[];
	var cntArr=0;
	$("[ehamburgermenu]").each(function(){
			dataFetch[cntArr]=$(this).attr('dshow');
			cntArr++;
                        if(hamburgerObj.hasOwnProperty(this))
                        {
                            
                            delete hamburgerObj[this];
                        }
                        
			hamburgerObj[this]=(new eHamburger(this,arr));	
                        
		});
	
	if(cntArr)
	{
				
		if(json[arr[0]][arr[1]].OnClick.length==1)
		{
				setTimeout(function(){
				$("[ehamburgermenu]").trigger("click");
			
				},400);
		}	
	}
	var json=changingEditData;
	var arr=attr.split(",");
	var key=json[arr[0]][arr[1]]["OnClick"];
        var z=0;
        $.each(key,function(k,v){
            if(key[k]["action"]==1)
            {
                if(overlayObj.hasOwnProperty(this))
                {
                  delete overlayObj[this];
                }
                        
		overlayObj[this]=(new OverlayDiv(this,v,z));
                z=z+1;
            }
        });
	var tabKey=json[arr[0]][arr[1]]["outerSectionKey"];
	validator(tabKey);	//showCenterLoader();
	if(arr[0]=="Contact")
	{
		if(arr[1]=="TIME_TO_CALL_START")
		{
			setTimeout(function(){
				$("[ehamburgermenu]").trigger("click");
			
				},animationtimer);
		}
		else
		{
			setTimeout(function(){
				try{
				$("#"+arr[1]).focus();
				var id=$("#"+arr[1])[0];
				if(id.value.length)
				id.setSelectionRange(id.value.length,id.value.length);
				
			}
			catch(e)
			{
			}
		},animationtimer);
		}
	}
	if(json[arr[0]][arr[1]].OnClick.length==1)
	{
		if(json[arr[0]][arr[1]].OnClick[0].action==4)
		{
			var height=$(window).height()-$("#overlayHead").outerHeight();
			
			var id=$("#"+json[arr[0]][arr[1]].OnClick[0].key)[0];
			$(id).height(height);
			setTimeout(function(){
				if(id.value.length)
				{
					$(id).focus();
					id.setSelectionRange(id.value.length,id.value.length);
					var topY=$(id)[0].scrollHeight;
					$(id).scrollTop(topY);
				}	
				
			},animationtimer);
			
		}
	}
    }catch(e)
    {
        
    }
        
}
function CancelSub(json,attr)
{
	if(submitObj.has_value())
	{
		updateAndShowCancelOverlay(json,attr);
		setTimeout(function(){setOverlayLocation();},animationtimer);
	}
	else{
    bCallCreateHoroscope = false;
		FlushChangedJson();
		submitObj.flush();
		RemoveOverLayer();
	}
}
function SaveSub(json,attr)
{
	var arr=attr.split(",");
	var key=json[arr[0]][arr[1]]["OnClick"];
	/*for(i=0;i<key.length;i++)
	{
		//console.log(key[i]["key"]);
		submitObj.push(key[i]["key"],key[i]["value"]);
	}*/
	var json={};
	var json=changingEditData;
	var tabKey=json[arr[0]][arr[1]]["outerSectionKey"];
	var isValid=true;
	var updatedJson="";
	var isValidStateCity;
	if(validatorFormId){
		isValid=$("#"+validatorFormId).valid();
	}
	if(validatorFormId=="BasicDetails")
	{
		isValidStateCity = StateCityRequired(key);
	}
	else
		isValidStateCity = true;
		
	if(isValid && isValidStateCity){
		var whereToSubmit=submitObj.has_value();
		if(whereToSubmit)
		{
			if(whereToSubmit=="DPP")
				submitObj.submitDpp();
			else
				submitObj.submit();
			
		}
		//RemoveOverLayer();
    callCreateHoroscope();
	}
	else
	{
		//showErrorOverLayer();
		ShowTopDownError(jsonError);
		jsonError=[];
	}
	
}



function UpdateErrorFields()
{
	
}
function RemoveOverLayer()
{
	$("#ed_slider").removeClass("dn");
	$("#overLayer").addClass('right_1');
        $.each(hamburgerObj,function(key,value)
        {
            delete hamburgerObj[key];
        });
        $.each(overlayObj,function(key,value)
        {
            delete overlayObj[key];
        });
	setTimeout(function(){$("#overLayer").addClass("dn");},animationtimer);
}
//Showing overlay layer ends here 


function UpdateOverlayLayer(attr)
{
	var json={};
	var json=changingEditData;
	//console.log(json[arr[0]][arr[1]]["OnClick"]);
	var arr=attr.split(",");
	var key=json[arr[0]][arr[1]]["OnClick"];
	var tabName=json[arr[0]][arr[1]]["outerSectionName"];
	if(arr[0]=="Contact")
		tabName="Contact Details";
	var tabKey=json[arr[0]][arr[1]]["outerSectionKey"];
	var htmlArr=new Array();
	for(i=0;i<key.length;i++)
	{
	if(($.inArray(key[i]["key"],editInArr[arr[0]])>-1)|| ($.inArray(key[i]["key"],editValArr[arr[1]])>-1))
	{
		var temp=overLayHtml;
		temp=temp.replace(/key_label/g,key[i]["key"]+"label");
		temp=temp.replace(/key_NAME/g,key[i]["key"]);
		temp=temp.replace(/key_value/g,key[i]["value"]);
		temp=temp.replace(/TAB_LABEL/g,key[i]["label"]);
                temp=temp.replace(/CboxArrow/g,"CboxArrow"+key[i]["key"]);
                temp=temp.replace(/CboxDiv/g,"CboxDiv"+key[i]["key"]);
                temp=temp.replace(/cOuter/g,"cOuter"+key[i]["key"]); 
                var classShowSettings = "dn";
                if(key[i]["showSettings"] == 1){
                       classShowSettings = "" ;
                       temp=temp.replace(/wid94p/g,"wid60p"); 
                       temp=temp.replace(/\{\{displaySettingsLabel\}\}/g,key[i]["settingData"]['display_string']); 
                       temp=temp.replace(/\{\{displaySettingsValue\}\}/g,key[i]["settingData"]['displayValue']); 
                       temp=temp.replace(/\{\{ONCLICK_EVENT\}\}/g,key[i]["settingData"]['callbackoverlay']+'(this);');
                       
                        if(key[i]["key"] == "NAME" && (key[i]["settingData"]['displayValue'] == "" || key[i]["settingData"]['displayValue'] == null || key[i]["settingData"]['displayValue'] == "null")){
                               submitObj.push("DISPLAYNAME","Y");
                               $("#showAll").attr('rel',"Y");
                        }
                }
                temp=temp.replace(/\{\{displaySettings\}\}/g,classShowSettings); 
		var notfilled="";
		var labelval=key[i]["label_val"];
		
		
		if(!labelval || labelval===null || labelval===undefined || labelval==="-" || labelval==="Select")
		{
			
			key[i]["label_val"]=labelval="";
			//if($.inArray(key[i]["key"],['NAME','GOTHRA','EDUCATION','COLLEGE'])==-1)
			if(key[i]["action"]==4)
				key[i]["label_val"]=labelval="";
			if(key[i]["action"]==2)
				key[i]["label_val"]=labelval=NOT_FILLED_IN;
			notfilled="notfilled";
			
		}	
			
		temp=temp.replace(/TAB_VALUE/g,labelval);
		
				
			temp=temp.replace(/NOTFILLED/g,notfilled);
		
		temp=UpdateOverlayTags(temp,key[i],i);
		
		htmlArr.push(temp);
					
	}
	}
	var tempStr=htmlArr.join("");
	var tempHtml=overLayerParent;
        tempHtml=tempHtml.replace(/TAB_HEAD/g,"HEAD_"+tabKey);
	tempHtml=tempHtml.replace(/TABS_NAME/g,tabName);
	tempHtml=tempHtml.replace(/SECTION_HTML/g,tempStr);
	tempHtml=tempHtml.replace(/FORM_NAME/g,tabKey);
	//tempHtml=tempHtml.replace(/validator_error/g,tabKey+"_validator_error");
	
	$("#overLayer").html("");
	$("#overLayer").html(tempHtml);
	//$("#overLayer").css("min-height",$(window).height()).css("background","white");
	$("#overLayer").css("background","white");
	$('#'+tabKey).submit(function() {
	  return false;
	});

	
}
function checkForLabelVal(html,json)
{
	
	var removingHtmlLabel=json.label_val.replace(/(<br>)|(<br \/>)|(<p>)|(<\/p>)/g, "").trim();
		removingHtmlLabel=removingHtmlLabel.replace(/(<BR>)/g,"\n").trim();
        removingHtmlLabel=removingHtmlLabel.replace(/"/g,"&quot;").replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/'/g,'&apos;');
        var parseHtml=$.parseHTML(removingHtmlLabel);
        var data=parseHtml?parseHtml[0].data:"";
	html=html.replace(/json_label_val/g,data);
	
	if(json.label_val==NOT_FILLED_IN)
		html=html.replace(/json_color_val/g,"color3");	
		

	html=html.replace(/json_color_val/g,"color3o");
	var placeholder=getPlaceholder(json.key)
	if(!placeholder)
		placeholder=NOT_FILLED_IN;
		
	html=html.replace(/json_label_placeholder/g,placeholder);
		
	
	
	return html;
}
function getPlaceholder(key)
{
	var text="";
	if(key=="YOURINFO")
		text="Introduce yourself. Write about your values, beliefs/goals, aspirations/interests and hobbies.";
	if(key=="EDUCATION")
		text="Which institutions have you attended? What courses/specializations have you studied?";
	if(key=="JOB_INFO")
		text="Where are you working currently? You may mention your current job and future career aspirations.";
	if(key=="FAMILYINFO")
		text="Write about your parents and brothers or sisters. Where do they live? What are they doing?";
	if(key=="SPOUSE")
		text="Write about your desired partner";
	return text;
}
function UpdateOverlayTags(string,json,indexPos)
{     
        string=string.replace(/\{\{dindex\}\}/,"dindexpos=\""+indexPos+"\"");
	if(json.action==2 ||json.action==3)
	{
		divOverlay=$("#divOverlay").html();
		divOverlay=divOverlay.replace(/json_key/g,json.key);
		divOverlay=divOverlay.replace(/json_value/g,json.value);
		
				
		divOverlay=checkForLabelVal(divOverlay,json);
		if(json.action==3){
			divOverlay=divOverlay.replace("showNonEditableOverLayer(0)","showNonEditableOverLayer(1)");		
		}
		
		string=string.replace(/\{\{inputDiv\}\}/g,divOverlay);
		
		if(json.action==3){
			string=string.replace(/\{\{backGroundColor\}\}/g,"bg3");
			string=string.replace(/\{\{displayArrow\}\}/g,"lock");
		}
	}
        if(json.action==1)
        {
            
            var ar=CBoxFields(json.key);
            str2="";
            checkOverlay=$("#checkOverlay").html();
            checkOverlay=checkOverlay.replace(/OverlayID/g,"Overlay"+json.key);
            checkOverlay=checkOverlay.replace(/default_val/g,"default"+json.key);
                for(j in ar){
                    str=$("#default_val").html();
                    str=str.replace(/default_key/g,"op"+json.key+j);
                     str=str.replace(/json_value/g,j.replace('\"','&quot;'));
                    str=str.replace(/json_label_val/g,ar[j]);
                    str2=str2+str;
                }
            checkOverlay=checkOverlay.replace(/default_key/g,"default_key"+json.key);
            if((json.key=="WORKING_MARRIAGE")&&(json.value=="N"))
                checkOverlay=checkOverlay.replace(/json_label_val/g,"No");
            checkOverlay=checkOverlay.replace(/json_label_val/g,json.label_val);
            checkOverlay=checkOverlay.replace(/\{\{overlayoptions\}\}/g,str2);
            string= string.replace(/\{\{inputDiv\}\}/g,checkOverlay);
        }
	var dhidden="";
		if(json.hidden)
			dhidden="dn";
	string=string.replace(/\{\{dhidden\}\}/g,dhidden);	
	if(json.key)	
	string=string.replace(/\{\{divid\}\}/g,json.key+"_TOP");
	if(json.action==2)
	{
		//json.key="country";
		//json.dependant="city";
		var dhide="single";
		var dselect="radio";
		
		string=string.replace(/\{\{ehamburgermenu\}\}/g,"ehamburgermenu=\""+1+"\"");
		string=string.replace(/\{\{dmove\}\}/,"dmove=\"right\"");
		string=string.replace(/\{\{dshow\}\}/g,"dshow='"+json.key+"'");
		
		
		if(json.dependant)
			dhide="decide";
		if(json.multi)
			dhide="multiple";
			
		if(dhide=="multiple")
			dselect="checkbox";
		var dcallback="UpdateSection";
		if(json.callBack)
			dcallback=json.callBack;
		//console.log(dhide+" "+dselect+" "+json.dependant);	
		string=string.replace(/\{\{dhide\}\}/g,"dhide='"+dhide+"'");
		string=string.replace(/\{\{dselect\}\}/g,"dselect='"+dselect+"'");
		if(json.dependant)
			string=string.replace(/\{\{dependant\}\}/g,"dependant='"+json.dependant+"'");
		string=string.replace(/\{\{dcallback\}\}/g,"dcallback='"+dcallback+"'");
		string=string.replace(/\{\{dindex\}\}/,"dindexpos=\""+indexPos+"\"");
		string=string.replace(/\{\{dindex\}\}/,"dindexpos=\""+indexPos+"\"");
	}
	else if(json.action==4)
	{
		textAreaOverlay=$("#textAreaOverlay").html();
		textAreaOverlay=checkForLabelVal(textAreaOverlay,json);
		textAreaOverlay=textAreaOverlay.replace(/json_key/g,json.key);
		
		if(json.key=="YOURINFO"){
			textAreaOverlay=textAreaOverlay.replace(/keyfunctionShow/g,"aboutFieldCount()");
		}
		else
			textAreaOverlay=textAreaOverlay.replace(/keyfunctionShow/g,"updateSectionContact(this)");
		string=string.replace(/\{\{inputDiv\}\}/g,textAreaOverlay);
		string=string.replace(/\{\{displayArrow\}\}/g,"dn");
		string=string.replace(/\{\{displayDiv\}\}/g,"dn");
	}
	else if(json.action==0)
	{
		var input="";
		if(json.key=="PHONE_MOB" || json.key=="PHONE_RES" || json.key=="ALT_MOBILE" )
		{
			
			
			textInputIsdOverlay=$("#textInputIsdOverlay").html();
			textInputStdOverlay=$("#textInputStdOverlay").html();
			
			textInputIsdOverlay=textInputIsdOverlay.replace(/json_key/g,json.key);
			textInputIsdOverlay=textInputIsdOverlay.replace(/json_label_val/g,json.label_val);
			textInputIsdOverlay=textInputIsdOverlay.replace(/keyfunctionShow/g,"updateSectionContact(this)");
			if(!json.value)
				phoneArray=["91","",""];
			else
				phoneArray=json.value.split(',');
			
			//console.log(phoneArray[0]);
			
			if(json.key=="PHONE_RES")
			{
				
				tempInputOverLay=textInputIsdOverlay;
				tempInputOverLay=tempInputOverLay.replace(/keyfunctionShow/g,"updateSectionContact(this)");
				
				
				
				input=tempInputOverLay.replace(/phoneArray/g,phoneArray[0]);
				
				
				tempInputStdOverlay=textInputStdOverlay;
				textInputMobileOverlay=textInputStdOverlay;
				
				tempInputStdOverlay=tempInputStdOverlay.replace(/phoneArray/g,phoneArray[1]);
				tempInputStdOverlay=tempInputStdOverlay.replace(/keyfunctionShow/g,"updateSectionContact(this)");
				tempInputStdOverlay=UpdateHtml(tempInputStdOverlay,{PLACEHOLDER:"Area Code",displayWidth:"wid20p"});
								
				input+=tempInputStdOverlay;
				textInputMobileOverlay=textInputMobileOverlay.replace(/phoneArray/g,phoneArray[2]?phoneArray[2]:"");
				textInputMobileOverlay=textInputMobileOverlay.replace(/keyfunctionShow/g,"updateSectionContact(this)");
				textInputMobileOverlay=UpdateHtml(textInputMobileOverlay,{PLACEHOLDER:"Number",displayWidth:"wid50p"});
				textInputMobileOverlay=textInputMobileOverlay.replace(/\{\{displayWidth\}\}/g,"wid50p");
				input+=textInputMobileOverlay.replace(/STD/g,json.key);

			}
			else if(json.key=="PHONE_MOB")
			{
				tempInputOverLay=textInputIsdOverlay;
				tempInputOverLay=tempInputOverLay.replace(/RES_ISD/g,"ISD");
				tempInputOverLay=tempInputOverLay.replace(/keyfunctionShow/g,"updateSectionContact(this)");
				input=tempInputOverLay.replace(/phoneArray/g,phoneArray[0]);
				
				textInputMobileOverlay=textInputStdOverlay;
				
				textInputMobileOverlay=textInputMobileOverlay.replace(/phoneArray/g,phoneArray[1]);
				textInputMobileOverlay=textInputMobileOverlay.replace(/keyfunctionShow/g,"updateSectionContact(this)");
				textInputMobileOverlay=UpdateHtml(textInputMobileOverlay,{PLACEHOLDER:"Number",displayWidth:"wid70p"});
				textInputMobileOverlay=textInputMobileOverlay.replace(/\{\{displayWidth\}\}/g,"wid80p");
				input+=textInputMobileOverlay.replace(/STD/g,json.key);
				
			}
			else if(json.key=="ALT_MOBILE")
			{
				tempInputOverLay=textInputIsdOverlay;
				tempInputOverLay=tempInputOverLay.replace(/RES_ISD/g,"ALT_ISD");
				tempInputOverLay=tempInputOverLay.replace(/keyfunctionShow/g,"updateSectionContact(this)");
				input=tempInputOverLay.replace(/phoneArray/g,phoneArray[0]);
				
				textInputMobileOverlay=textInputStdOverlay;
				
				textInputMobileOverlay=textInputMobileOverlay.replace(/phoneArray/g,phoneArray[1]);
				textInputMobileOverlay=textInputMobileOverlay.replace(/keyfunctionShow/g,"updateSectionContact(this)");
				textInputMobileOverlay=UpdateHtml(textInputMobileOverlay,{PLACEHOLDER:"Number",displayWidth:"wid70p"});
				textInputMobileOverlay=textInputMobileOverlay.replace(/\{\{displayWidth\}\}/g,"wid80p");
				input+=textInputMobileOverlay.replace(/STD/g,json.key);
				
			}
		}
		else{

			textInputOverlay=$("#textInputOverlay").html();
			textInputOverlay=textInputOverlay.replace(/json_key/g,json.key);
			textInputOverlay=textInputOverlay.replace(/keyfunctionShow/g,"updateSectionContact(this)");

			if(json.value)
				input= textInputOverlay.replace(/json_value/g,(json.value).replace(/\"/g,'&quot;'));
			else
				input= textInputOverlay.replace(/json_value/g,(json.label_val).replace(/\"/g,'&quot;'));

		}
		string=string.replace(/\{\{inputDiv\}\}/g,input);
		
		//Checks for contact tab.
		if(json.screenBit==1 && $.inArray(json.key,['EMAIL','PHONE_MOB','ALT_MOBILE','PHONE_RES','ALT_EMAIL'])==-1 && json.label_val!="")
			string=string.replace(/\{\{underScreening\}\}/g,underScreenStr);
		string=string.replace(/\{\{underScreening\}\}/g,"");
		string=string.replace(/\{\{displayArrow\}\}/g,"dn");
	}
	string=string.replace(/\{\{backGroundColor\}\}/g,"bg4");
	if(json.action==1)
            string=string.replace(/\{\{HS\}\}/g,"display:none");
        else
            string=string.replace(/\{\{displayArrow\}\}/g,"arow1");
	string=string.replace(/\{\{\w+\}\}/g,"");
	
	return string;
}
//showing hamburger menu


function updateAndShowCancelOverlay(json,attr)
{
	var arr=attr.split(",");
	var key=json[arr[0]][arr[1]]["OnClick"];
	var tabName=json[arr[0]][arr[1]]["outerSectionName"];
	
	var tempHtml=overLayCancelHtml;
	tempHtml=tempHtml.replace(/PromptSectionName/g,"overLayCancelSection");	
	tempHtml=tempHtml.replace(/TEXT1/g,"Would you like to save");
	tempHtml=tempHtml.replace(/TEXT2/g,"the changes?");
	tempHtml=tempHtml.replace(/TAB1_NAME/g,"Save");
	tempHtml=tempHtml.replace(/TAB2_NAME/g,"Discard");
	tempHtml=tempHtml.replace(/Action1/g,"saveAfterCancelOverlay");
	tempHtml=tempHtml.replace(/Action2/g,"discardAfterCancelOverlay");
	$("#cancelOverLayer").html(tempHtml);
	
	CommonOverlayDisplaySettings("cancelOverLayer","overLayCancelSection",1,"cancel");
	
	$("#saveAfterCancelOverlay").unbind(clickEventType);
	$("#discardAfterCancelOverlay").unbind(clickEventType);
	$("#saveAfterCancelOverlay").bind(clickEventType,function(){

		RemoveCancelOverLayer();
		SaveSub(json,attr);		
		FlushChangedJson();
		
	});
	$("#discardAfterCancelOverlay").bind(clickEventType,function(){
    bCallCreateHoroscope = false;
		FlushChangedJson();
		RemoveCancelOverLayer();
		RemoveOverLayer();
	});
	
}
function showFilterOverlayer()
{       
        $("#filterDpp").removeClass('dn').addClass("width","100%").addClass('height',$(window).height()).addClass("overflow","auto");
	setTimeout(function(){$("#filterDpp").removeClass("bottom_1");},10);
	setTimeout(function(){$("#ed_slider").addClass("dn");
		},animationtimer);
        //var ele= this;
        var filterDivShow="";
        var filterDiv;
        var tempval;
        var saveJson={};
        var clicked=0;
	$.each(pageJson["Dpp"]["BasicDetails"]["OnClick"],function(k,v){
		filterBasicMap[v["key"]] = k;
	});
	$.each(pageJson["Dpp"]["Religion"]["OnClick"],function(k,v){
		filterReligionMap[v["key"]] = k;
	});
	$.each(pageJson["Dpp"]["EduAndOcc"]["OnClick"],function(k,v){
                filterEduMap[v["key"]] = k;
        });
        filterJson.FILTER[0]["label"]=pageJson["Dpp"]["BasicDetails"]["OnClick"][filterBasicMap["P_AGE"]]["label"];
        filterJson.FILTER[0]["label_val"]=pageJson["Dpp"]["BasicDetails"]["OnClick"][filterBasicMap["P_AGE"]]["label_val"];
        filterJson.FILTER[1]["label"]=pageJson["Dpp"]["BasicDetails"]["OnClick"][filterBasicMap["P_MSTATUS"]]["label"];
        filterJson.FILTER[1]["label_val"]=pageJson["Dpp"]["BasicDetails"]["OnClick"][filterBasicMap["P_MSTATUS"]]["label_val"];
        filterJson.FILTER[2]["label"]=pageJson["Dpp"]["BasicDetails"]["OnClick"][filterBasicMap["P_COUNTRY"]]["label"];
        filterJson.FILTER[2]["label_val"]=pageJson["Dpp"]["BasicDetails"]["OnClick"][filterBasicMap["P_COUNTRY"]]["label_val"];
        filterJson.FILTER[3]["label"]=pageJson["Dpp"]["BasicDetails"]["OnClick"][filterBasicMap["P_CITY"]]["label"];
        filterJson.FILTER[3]["label_val"]=pageJson["Dpp"]["BasicDetails"]["OnClick"][filterBasicMap["P_CITY"]]["label_val"];
        filterJson.FILTER[4]["label"]=pageJson["Dpp"]["Religion"]["OnClick"][filterReligionMap["P_RELIGION"]]["label"];
        filterJson.FILTER[4]["label_val"]=pageJson["Dpp"]["Religion"]["OnClick"][filterReligionMap["P_RELIGION"]]["label_val"];
        filterJson.FILTER[5]["label"]=pageJson["Dpp"]["Religion"]["OnClick"][filterReligionMap["P_CASTE"]]["label"];
        filterJson.FILTER[5]["label_val"]=pageJson["Dpp"]["Religion"]["OnClick"][filterReligionMap["P_CASTE"]]["label_val"];
        filterJson.FILTER[6]["label"]=pageJson["Dpp"]["Religion"]["OnClick"][filterReligionMap["P_MTONGUE"]]["label"];
        filterJson.FILTER[6]["label_val"]=pageJson["Dpp"]["Religion"]["OnClick"][filterReligionMap["P_MTONGUE"]]["label_val"];
        filterJson.FILTER[7]["label"]= "Income";
        //Income will have two options $ and Rs
        tempval=pageJson["Dpp"]["EduAndOcc"]["OnClick"][filterEduMap["P_INCOME_RS"]]["label_val"]+"<br>";
        tempval+=pageJson["Dpp"]["EduAndOcc"]["OnClick"][filterEduMap["P_INCOME_DOL"]]["label_val"].replace(/&nbsp;/g,"");
        filterJson.FILTER[7]["label_val"]=tempval;
        $.each(filterJson.FILTER,function(key,value){
                filterDiv=$("#filterSection").html();
                filterDiv=filterDiv.replace(/filterLabel/g,value.label);
                filterDiv=filterDiv.replace(/filterOnOff/g,"filterbox"+value.key);
                filterDiv=filterDiv.replace(/filterValue/g,value.label_val);
                if(value.val=='Y')
                    filterDiv=filterDiv.replace(/filter-onoff filter-off/g,"filter-onoff filter-on");
                filterDivShow+=filterDiv;
        });
        $.each(filterJson.FILTER,function(key,value){
            saveJson[value.key]=value.val;
        });
        $("#filterSectionShow").html(filterDivShow);
        $.each(filterJson.FILTER,function(key,value){
                $("#filterbox"+value.key).bind(clickEventType,function(){
                        if(value.val=="N")
                        {
                            value.val="Y";
                            saveJson[value.key]="Y";
                            $(this).removeClass("filter-onoff filter-off").addClass("filter-onoff filter-on");
                        }
                        else
                        {
                            value.val="N";
                            saveJson[value.key]="N";
                            $(this).removeClass("filter-onoff filter-on").addClass("filter-onoff filter-off");
                        }
                        clicked=1;
                });
        });
        $( "#saveFilter" ).unbind();
        $("#saveFilter").bind(clickEventType,function(){
            if(clicked==1)
            {
                submitObj.pushContactJson(saveJson);
                submitObj.submitDppFilters();
            }
            else
            {
                $("#ed_slider").removeClass("dn");
                $("#filterDpp").addClass('bottom_1');
                setTimeout(function(){$("#filterDpp").addClass("dn");},animationtimer);
            }
        });
        setFilterOverlayLocation();
}
function setFilterOverlayLocation()
{
	historyStoreObj.push(function(){
		if($("#ed_slider").hasClass("dn"))
		{
			$("#ed_slider").removeClass("dn");
			$("#filterDpp").addClass('bottom_1');
			setTimeout(function(){$("#filterDpp").addClass("dn");},animationtimer);
			return true;
		}
		return false;
	},"#filter");
}
function RemoveCancelOverLayer()
{
	$("#ed_slider").removeClass("dn");
	CancelOverLayerAnimation(1);
	
}
function CancelOverLayerAnimation(close)
{
	if(close)
	{
		$("#cancelOverLayer").removeClass("top_2").addClass('top_3');
		setTimeout(function(){
			$("#cancelOverLayer").addClass("dn").removeClass("top_3").css("margin-top","").addClass("top_1");
			hideCancelBackgroundDiv();
			},animationtimer3s);
	}
	else
	{
		var height=$("#cancelOverLayer").outerHeight();
		var sh=Math.floor(($(window).height()-height)/2);
		
		$("#cancelOverLayer").removeClass("dn");
		setTimeout(function(){
			$("#cancelOverLayer").removeClass("top_1").css("margin-top",sh).addClass("top_2");
			},10);
	}
	
}
//SAving back old json
function FlushChangedJson()
{
	
	changingEditData=JSON.parse(JSON.stringify(originalEditData));
	//console.log('coming');
	
	//console.log(originalEditData);
	//console.log('end');
	submitObj.flush();
}

function CBoxFields(field)
{   
    var Cboxarr={
        "DIET":{"0":"Select",V:"Vegetarian",N:"Non Vegetarian",J:"Jain",E:"Eggetarian"},
        "SMOKE":{"0":"Select",Y:"Yes",N:"No",O:"Occasionally"},
        "DRINK":{"0":"Select",Y:"Yes",N:"No",O:"Occasionally"},
        "OPEN_TO_PET":{Y:"Yes",N:"No"},
        "OWN_HOUSE":{Y:"Yes",N:"No"},
        "HAVE_CAR":{Y:"Yes",N:"No"},
        "NAMAZ":{1:"5 times",2:"Only jummah",3:"Not regular",4:"During ramadan",5:"None"},
        "ZAKAT":{Y:"Yes",N:"No"},
        "FASTING":{1:"Ramadan & Sunnah",2:"Ramadan",3:"None"},
        "UMRAH_HAJJ":{1:"Umrah/Hajj",2:"Umrah",3:"None"},
        "QURAN":{1:"Daily",2:"Occasionally",3:"On Fridays",4:"None"},
        "SUNNAH_BEARD":{1:"Always",2:"After Nikah",3:"None"},
        "SUNNAH_CAP":{1:"Always",2:"During prayer",3:"Occasionally",4:"Only at functions",5:"None"},
        "HIJAB":{Y:"Yes",N:"No"},
        "HIJAB_MARRIAGE":{Y:"Yes",N:"No",D: "Not Decided"},
        "WORKING_MARRIAGE":{Y:"Yes",N:"No"},
        "AMRITDHARI":{Y:"Yes",N:"No"},
        "CUT_HAIR":{Y:"Yes",N:"No"},
        "TRIM_BEARD":{Y:"Yes",N:"No"},
        "WEAR_TURBAN":{Y:"Yes",N:"No",O:"Occasionally"},
        "CLEAN_SHAVEN":{Y:"Yes",N:"No"},
        "ZARATHUSHTRI":{Y:"Yes",N:"No"},
        "PARENTS_ZARATHUSHTRI":{Y:"Yes",N:"No"},
        "BAPTISED":{Y:"Yes",N:"No"},
        "READ_BIBLE":{Y:"Yes",N:"No"},
        "OFFER_TITHE":{Y:"Yes",N:"No"},
        "SPREADING_GOSPEL":{Y:"Yes",N:"No"},
        "MARRIED_WORKING":{Y:"Yes",N:"No"},
        "GOING_ABROAD":{Y:"Yes",N:"No",U:"Undecided"}
    };
    if(changingEditData["Details"]["Ethnicity"]["OnClick"][1]["label_val"]=="Muslim: Sunni")
          Cboxarr["MATHTHAB"]={1:"Hanafi",2:"Hanbali",3:"Maliki",4:"Shafi'I"};
    if(changingEditData["Details"]["Ethnicity"]["OnClick"][1]["label_val"]=="Muslim: Shia")
          Cboxarr["MATHTHAB"]={5:"Ismaili",6:"Ithna ashariyyah",7:"Zaidi",8:"Dawoodi Bohra"};
    return(Cboxarr[field]);
    
}


(function(){
    var OverlayDiv=(function(){
        function OverlayDiv(ele,key1,q)
        {         
                $("#"+key1.key+"label").removeClass("color3 fontlig  fl14").addClass("fl f13 fontlig color3 pt4");
                $("#"+key1.key+"label").css('padding','0px 0px 20px 0px');
                $("#cOuter"+key1.key).removeClass("dn");
                $("#cOuter"+key1.key).parent().parent().removeClass("pad2");
                $("#CboxArrow"+key1.key).removeClass("dn");
                $("#CboxArrow"+key1.key).parent().removeClass("wid94p");
                $("#CboxArrow"+key1.key).parent().css('width','100%');
                $("#"+key1.key+"_TOP").removeClass("brdr1");
                $("#default_key"+key1.key).removeClass("f17 fontthin pad2 brdr3");
                $("#default"+key1.key).addClass("fr f17 fontthin color2 padr15");
                $("#CboxDiv"+key1.key).removeClass("dn");
                $("#"+key1.key+"label").appendTo("#CboxDiv"+key1.key);
                $("#default"+key1.key).appendTo("#CboxDiv"+key1.key);
                $("#Overlay"+key1.key).appendTo("#cOuter"+key1.key);
                if(q%2!=0)
                {    $("#"+key1.key+"_TOP").removeClass("bg4");
                    $("#"+key1.key+"_TOP").addClass("bg5");
                }
                var ar=CBoxFields(key1.key);
                if((key1.value=="")||(key1.value==null)||(key1.value==0))
                {
                    $("#default"+key1.key).hide();
                    $("#CboxArrow"+key1.key).children().addClass("arow5");
                    $("#CboxArrow"+key1.key).children().removeClass("arow6");
                    if(key1.key=="AMRITDHARI")
                    {
                        $("#CUT_HAIR_TOP").hide();
                        $("#TRIM_BEARD_TOP").hide();
                        $("#WEAR_TURBAN_TOP").hide();
                        $("#CLEAN_SHAVEN_TOP").hide();
                    }
                }
                else
                {   
                    if(key1.key=="AMRITDHARI" && key1.value=="Y")
                    {
                        $("#CUT_HAIR_TOP").hide();
                        $("#TRIM_BEARD_TOP").hide();
                        $("#WEAR_TURBAN_TOP").hide();
                        $("#CLEAN_SHAVEN_TOP").hide();
                    }
                    $("#default"+key1.key).show();
                    $("#Overlay"+key1.key).hide();
                }
                $("#CboxDiv"+key1.key).unbind(clickEventType);
                $("#CboxDiv"+key1.key).bind(clickEventType,function(){
                    if((key1.key=="AMRITDHARI")&&($("#default_key"+key1.key).html()=="No"))
                      {
                          $("#CUT_HAIR_TOP").show();
                          $("#TRIM_BEARD_TOP").show();
                          $("#WEAR_TURBAN_TOP").show();
                          $("#CLEAN_SHAVEN_TOP").show();
                      }
                    $("#CboxArrow"+key1.key).children().removeClass("arow6");
                    $("#CboxArrow"+key1.key).children().addClass("arow5");
                    $("#Overlay"+key1.key).show();
                    $("#default"+key1.key).hide();
                    
                    var topY=$("#"+key1.key+"_TOP").position().top+$("#overlayContent").scrollTop()+$("#"+key1.key+"_TOP").outerHeight()+$("#overlayContent").position().top-$(window).height();
                    
                    
                    //var topY=$(window).height()-$("#"+key1.key+"_TOP").position().top+$("#"+key1.key+"_TOP").outerHeight()+$("#overlayContent").position().top-$("#overlayContent").scrollTop();
                     if(topY>0)
                     {
						 $("#overlayContent").animate({ scrollTop: topY }, 600);
						 
					 }
					 
                });
                for(j in ar){
					$("#op"+key1.key+j).unbind(clickEventType);
                    if((key1.key=="AMRITDHARI")&&(j=="Y"))
                      { 
							
                            $("#op"+key1.key+j).bind(clickEventType,function(){
                                $("#CUT_HAIR_TOP").hide();
                                $("#TRIM_BEARD_TOP").hide();
                                $("#WEAR_TURBAN_TOP").hide();
                                $("#CLEAN_SHAVEN_TOP").hide();
                                if(key1.value!="" && key1.value!=null && key1.value!=0)
                                {
                                    submitObj.push("CUT_HAIR","");
                                    submitObj.push("TRIM_BEARD","");
                                    submitObj.push("WEAR_TURBAN","");
                                    submitObj.push("CLEAN_SHAVEN","");
                                }
                            });
                      }
                    if((key1.key=="AMRITDHARI")&&(j=="N"))        
                       {
						   
                           $("#op"+key1.key+j).bind(clickEventType,function(){
                                $("#CUT_HAIR_TOP").show();
                                $("#TRIM_BEARD_TOP").show();
                                $("#WEAR_TURBAN_TOP").show();
                                $("#CLEAN_SHAVEN_TOP").show();
                            });
                     }
                    $("#op"+key1.key+j).bind(clickEventType,function()
                    {
						$(this).parent().parent().parent().removeClass('notfilled');
                        $("#CboxArrow"+key1.key).children().addClass("arow6");
                        $("#CboxArrow"+key1.key).children().removeClass("arow5");
                      $("#Overlay"+key1.key).hide();
                      $("#default_key"+key1.key).text($(this).text());

                      $("#default"+key1.key).show();
                      submitObj.push(key1.key,$(this).attr("value"));
                    });
                 }  
        }
        this.OverlayDiv=OverlayDiv;
    }).call(this);
    })();
    
function showErrorOverLayer(fromSubmit)
{
	var tempHtml=overLayCancelHtml;
	tempHtml=tempHtml.replace(/PromptSectionName/g,"overLayCancelSection");	
	tempHtml=tempHtml.replace(/TEXT1/g,"Error");
	tempHtml=tempHtml.replace(/TAB1_NAME/g,"ok");
	tempHtml=tempHtml.replace(/Action1/g,"closeErrorOverlay");
	validatorText=$("#validation_error").text();
	$("#cancelOverLayer").html(tempHtml);
	
	CommonOverlayDisplaySettings("cancelOverLayer","overLayCancelSection",1,"error");
	$("#validation_error").text(validatorText);
	$("#closeErrorOverlay").unbind(clickEventType);
	$("#closeErrorOverlay").bind(clickEventType,function(){
		if(fromSubmit)
			$("#overLayer").addClass("dn");
		else
			$("#overLayer").removeClass("dn");

		CancelOverLayerAnimation(1);
		
	});
}

function showNonEditableOverLayer(toShow)
{
	if(toShow){
		var tempHtml=overLayCancelHtml;
		tempHtml=tempHtml.replace(/PromptSectionName/g,"overLayNonEditableSection");	
		tempHtml=tempHtml.replace(/TEXT1/g,"This field is read-only");
		tempHtml=tempHtml.replace(/TEXT2/g,"Call us for change(s) in Gender,<BR> Date of Birth, Marital Status or Religion");
		tempHtml=tempHtml.replace(/TAB1_NAME/g,"Dismiss");
		tempHtml=tempHtml.replace(/TAB2_NAME/g,"<a href='tel:18004196299' title='call' alt='call' class='fontthin f17 color2'>Call</a>");
		tempHtml=tempHtml.replace(/Action1/g,"dismissNonEditableOverlay");
		tempHtml=tempHtml.replace(/Action2/g,"callNonEditableOverlay");
		
		$("#cancelOverLayer").html(tempHtml);
		$("#nonEditablePic").removeClass("dn");
		CommonOverlayDisplaySettings("cancelOverLayer","overLayNonEditableSection",1,"nonEditable");
		
		$("#dismissNonEditableOverlay").unbind(clickEventType);
		$("#dismissNonEditableOverlay").bind(clickEventType,function(){
			$("#overLayer").removeClass("dn");		
			CancelOverLayerAnimation(1);
			
			
		});
		
		$("#callNonEditableOverlay").unbind(clickEventType);
		$("#callNonEditableOverlay").bind(clickEventType,function(){
			$("#overLayer").removeClass("dn");		
			CancelOverLayerAnimation(1);
			
		});
	}
}

function showLoader()
{
	setTimeout(function(){$("#ed_slider").addClass("dn");},100);
	stopTouchEvents(1,1,1);
}
function hideLoader(noAjax)
{
	$( "#overLayer" ).html(overLayTemplate);
	$( "#overLayer" ).addClass("dn");
	$( "#ed_slider" ).addClass("vh");
	$( "#ed_slider" ).html(renderPage.ed_sliderDiv);
	$( "#cancelOverLayer" ).html(cancelOverLayerTemplate);
	albumPresent=1;
	if(noAjax)
	{
		renderPage.CreatePage();
		if(sliderCurrentPage){
		//setSliderLocation(sliderCurrentPage);
		bxslider.gotoSlide(sliderCurrentPage);
		}
		//else
		//setSliderLocation(0);
		stopLoader();
	}
	else{
		var renderAgain=new mobEditPage;
	}

}

function stopLoader()
{
	setTimeout(function(){$("#ed_slider").removeClass("dn");},animationtimer);
	startTouchEvents(100);
}
function CommonOverlayDisplaySettings(tabName,SectionName,background,action)
{
		$("#"+tabName).addClass("CancelOverlay");
		$("#"+SectionName).addClass("CancelOverlay");
		if(background){
			$("#cancelOverLayBackGround").css("min-height",screen.height);
			$("#cancelOverLayBackGround").addClass("web_dialog_overlay");
			$("#cancelOverLayBackGround").removeClass("dn");
		}
		CancelOverLayerAnimation();
		
		//$("#"+tabName).css("top",(screen.height/3));
		//setTimeout(function(){},animationtimer3s);		
		//setTimeout(function(){$("#"+tabName).removeClass("right_1");},animationtimer3s);
		
		setTimeout(function(){$("#ed_slider").addClass("dn");},animationtimer);
		if(action=="error")
		{
			$("#TEXT2_ID").remove();
			$("#TAB2_ID").remove();
			$("#Action2").remove();
			$("#TAB1_ID").removeClass("wid49p");
			$("#TAB1_ID").addClass("fullwid");
			$("#validation_error").removeClass("dn");
		}
}

function hideCancelBackgroundDiv(){
	$("#cancelOverLayBackGround").removeClass("web_dialog_overlay").addClass("dn");
}

function ToggleMore(keyName)
{
	event.stopPropagation();
	$("#"+keyName+"_more").addClass("dn");
	$("#"+keyName+"_less").removeClass("dn");
	return false;	
}



	function SkipToSection(){
	var ele=$("[maintab=\"1\"]");
	var htmldiv=$("#overlay_2_temp").children().first();
	var cross=$(htmldiv).wrap("<div></div>").parent().html();
	$(htmldiv).unwrap();
	htmldiv=$("#overlay_2_temp").children().last();
	var eachEle=$(htmldiv).wrap("<div></div>").parent().html();
	$(htmldiv).unwrap();
	
	$.each(ele,function(key,value)
	{
		$(value).unbind("click");
		$(value).bind("click",function(ev){
			historyStoreObj.push(function(){
				if($("#overlay_2").hasClass('dn'))
					return false;
				else
					$("#overlay_2").trigger("click");
				return true;	
			},"#skip");
		//$(value).swipe({longTapThreshold:longTapThreshold,longTap:function(ev){
			$(cross).swipe("destroy");
			ev.preventDefault();
			ev.stopPropagation();
			var i=1;
			var arr=new Array();
			arr[0]=cross;
			$.each(changingEditData,function(keys,values)
			{
				var keyName;
				var html=eachEle;
				var bold="";
				if(trim($(value).text())==keys)
					bold="b";
                                keyName=keys;
                                if(keys=="Dpp")
                                    keyName="Desired Partner";
				if(keys=="Details")
                                {
                                        keyName="Basic Info";
                                }	
				arr[i]=UpdateHtml(html,{"KEYNAME":keyName,"indexpos":i-1,"BOLD":bold});
				
				i++;
				
			});
			var ol2=$("#overlay_2");
			ol2.html(arr.join(""));
			ol2.removeClass("dn").addClass('animate');
			$("#overlay_2").css("height",($(window).height())-$("#topbar").outerHeight()).css("overflow","auto").css("top",$("#topbar").outerHeight());
			//$("#overlay_2").OnlyVertical({allowProp:1});
			$("#topbar").css("position","relative").css("z-index","1000");
			
			
			
		//}
		});
	});
	//$("#overlay_2").swipe({longTapThreshold:100,longTap:function(ev,target){
	$("#overlay_2").bind("click",function(ev){
		
		var target=ev.target;
		var index=$(target).attr("indexpos");
		$(this).removeClass("animate").addClass('dn');
		$("#topbar").css("position","").css("z-index","");
		
		if(typeof(index)!='undefined' && index!=-1)
			bxslider.gotoSlide(parseInt(index));
		ev.preventDefault();
		ev.stopPropagation();
	//}
	});
	
}

$(window).bind("resize",function(ev){
	if(EventStopAlready())
                return;
            
	if(!$("#ed_slider").hasClass('dn'))
		stopTouchEvents(1);

	//For save button
	if($("#SAVE_DONE").length && !$("#SAVE_DONE").hasClass("dn"))
	{
		$("#SAVE_DONE").css("display","");
		var topSD=$("#SAVE_DONE").position().top;
		if(topSD<300)
			$("#SAVE_DONE").css("display","none");
	}
	var topbarh=$("#topbar").height();
	var sectiontab=$("#"+key+"SubHead").height();
	var screenH=$(window).height();
	var screenW=$(window).width();
	var screenT=sectiontab;
	var tot=screenH-(sectiontab+topbarh);
	var key="Album";
	var divHgt = $(".overlay_pu.posabs").height();
	$("#"+key+"picture").height(screenH);
	$("#"+key+"overlay").height(screenH);
	$("#"+key+"picture").width(screenW);
	$("#"+key+"overlay").width(screenW);
	$("#"+key+"overlay").css({"top":"sectiontab"});
	
	if(screenH>415)
	$("#privacyoptionshow").height(screenH-sectiontab-topbarh);	
	
		if(!$("#overLayer").hasClass('dn'))
		{
			var height=$(window).height()-$("#overlayHead").outerHeight();
			$("#overlayContent").height(height);
			$("#overlayContent textarea").height(height);
			$("#overLayer").height($(window).height());
			var textAreaID=$("#overlayContent textarea");
			if(textAreaID.length)
			{
				if(height<300)
					$(textAreaID[0]).removeClass("minhgt300");
				else
					$(textAreaID[0]).removeClass("minhgt300");
				$(textAreaID[0]).scrollTop($(textAreaID[0]).outerHeight());
			}
				
		}
	
	setTimeout(function(){
		if(!$("#overLayer").hasClass('dn'))
		{
			var k=$("input:focus");
			if(k.length )
			{
				var hgt=$(k).position().top-height;
				//var hgt=$(k).position().top;
				if(hgt>0)
				{
//						$("#overlayContent").css("scrollTop",hgt);
						$("#overlayContent").scrollTop($("#overlayContent").scrollTop()+hgt);
						setTimeout(function(){
							},10);
				}		
			}
		}
		else
		{
			slide();
			SlideToCurrentPage();
			stopLoader();
			
		}	
			
		
		
	},animationtimer);	
	
	
});
function SlideToCurrentPage()
{
	var hashVal=document.location.hash.replace(",historyCall","");
	var actualHash=hashVal.replace("#","");
	if(!sliderCurrentPage)
				sliderCurrentPage=$("#"+actualHash+"slidername").attr("index");
			if(typeof(sliderCurrentPage)!='undefined'){
				//setSliderLocation(sliderCurrentPage);
				bxslider.gotoSlide(parseInt(sliderCurrentPage),-1);
			}
}

/**
 * callCreateHoroscope, 
 * if bCallCreateHoroscope is set then redirect to create horoscope page
 * @returns {undefined}
 */
function callCreateHoroscope()
{
  if(false === bCallCreateHoroscope) {
    return ;
  } 
  bCallCreateHoroscope = false;
  setTimeout(function(){
    ShowNextPage('/profile/mobhoroscope',0,1);
  },10);
  
}

/**
 * 
 * @returns {undefined}
 */
function onHoroscopeButtonClick()
{
  var horoMustValue = originalEditData.Kundli.HOROSCOPE_MATCH['OnClick']['0'].value
  if(horoMustValue.length){
    ShowNextPage('/profile/mobhoroscope',0,1);
    return;
  }
  bCallCreateHoroscope = true;
  $('[slideoverlayer="Kundli,HOROSCOPE_MATCH"]').trigger('click');
}
