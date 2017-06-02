var originalEditData={};
var mutualMatchCount;
var dppSuggestionsData={};
var changingEditData={};
var jsonEntry="OnClick";
var sliderCurrentPage="";
var underScreenStr="";
var filterJson="";
var albumPresent=0;
var editWhatsNew = {'FamilyDetails':'5','Edu':'3','Occ':'4','AstroData':'2','FocusDpp':'7'};
var bCallCreateHoroscope = false;
var editSectionArr = new Array("Album","Details","Kundli","Education","Career","Family","Lifestyle","Contact","Dpp","FILTER");
var editInArr = {};
editInArr['Details'] = new Array("YOURINFO","basic","critical","Ethnicity","Appearance","SpecialCases");
editInArr['Kundli'] = new Array("HOROSCOPE_MATCH","RASHI","NAKSHATRA","MANGLIK");
editInArr['Education'] = new Array("EDUCATION","CollegeDetails");
editInArr['Career']= new Array("JOB_INFO","CarrerDetails","FuturePlans");
editInArr['Family']=new Array("FAMILYINFO","Family","Parents","Siblings");
editInArr['Lifestyle']=new Array("Habits","Assets","Skills","hobbies","Interests","Favourite");
editInArr['Contact']=new Array("PROFILE_HANDLER_NAME","EMAIL","ALT_EMAIL","PHONE_MOB","ALT_MOBILE","PHONE_RES","TIME_TO_CALL_START");
editInArr['Dpp']=new Array("SPOUSE","BasicDetails","Religion","EduAndOcc","Lifestyle");
var editValArr={};
editValArr["YOURINFO"]=new Array("YOURINFO");
editValArr["basic"] = new Array("NAME","COUNTRY_RES","STATE_RES","CITY_RES","GENDER","DTOFBIRTH","MSTATUS","RELATION");
editValArr["critical"] = new Array("DTOFBIRTH","MSTATUS");
editValArr["Ethnicity"]=new Array("RELIGION","CASTE","DIOCESE","SUBCASTE","SECT","MTONGUE","NATIVE_COUNTRY","NATIVE_STATE","ANCESTRAL_ORIGIN","GOTHRA");
editValArr["BeliefSystem"]=new Array("BAPTISED","READ_BIBLE","OFFER_TITHE","SPREADING_GOSPEL","ZARATHUSHTRI","PARENTS_ZARATHUSHTRI","AMRITDHARI","CUT_HAIR","TRIM_BEARD","WEAR_TURBAN","CLEAN_SHAVEN","MATHTHAB","NAMAZ","ZAKAT","FASTING","UMRAH_HAJJ","QURAN","SUNNAH_BEARD","SUNNAH_CAP","HIJAB","HIJAB_MARRIAGE","WORKING_MARRIAGE");
editValArr["Appearance"]=new Array("HEIGHT","COMPLEXION","BTYPE","WEIGHT");
editValArr["SpecialCases"]=new Array("HANDICAPPED","NATURE_HANDICAP","THALASSEMIA","HIV");
editValArr["EDUCATION"]=new Array("EDUCATION");
editValArr["CollegeDetails"]=new Array("EDU_LEVEL_NEW","DEGREE_PG","PG_COLLEGE","DEGREE_UG","COLLEGE","SCHOOL");
//editValArr["Kundli"]=new Array("HOROSCOPE_MATCH","RASHI","NAKSHATRA","MANGLIK");
editValArr["JOB_INFO"]=new Array("JOB_INFO");
editValArr["CarrerDetails"]=new Array("COMPANY_NAME","OCCUPATION","INCOME");
editValArr["FuturePlans"]=new Array("MARRIED_WORKING","GOING_ABROAD");
editValArr["FAMILYINFO"]=new Array("FAMILYINFO");
editValArr["Family"]=new Array("FAMILY_VALUES","FAMILY_TYPE","FAMILY_STATUS","PARENT_CITY_SAME");
editValArr["Parents"]=new Array("FAMILY_BACK","MOTHER_OCC","FAMILY_INCOME");
editValArr["Siblings"]=new Array("T_BROTHER","T_SISTER");
editValArr["Habits"]=new Array("DIET","SMOKE","DRINK","OPEN_TO_PET");
editValArr["Assets"]=new Array("OWN_HOUSE","HAVE_CAR");
editValArr["Skills"]=new Array("HOBBIES_LANGUAGE","FAV_FOOD");
editValArr["hobbies"]=new Array("HOBBIES_HOBBY");
editValArr["Interests"]=new Array("HOBBIES_INTEREST");
editValArr["Favourite"]=new Array("HOBBIES_MUSIC","HOBBIES_BOOK","HOBBIES_DRESS","FAV_MOVIE","HOBBIES_SPORTS","HOBBIES_CUISINE","FAV_BOOK","FAV_TVSHOW","FAV_VAC_DEST");
editValArr["SPOUSE"]=new Array("SPOUSE");
editValArr["BasicDetails"]=new Array("P_HEIGHT","P_AGE","P_MSTATUS","P_HAVECHILD","P_COUNTRY","P_CITY","P_MATCHCOUNT");
editValArr["Religion"]=new Array("P_RELIGION","P_CASTE","P_SECT","P_MTONGUE","P_MANGLIK");
editValArr["EduAndOcc"]=new Array("P_EDUCATION","P_OCCUPATION_GROUPING","P_INCOME_RS","P_INCOME_DOL");
editValArr["Lifestyle"]=new Array("P_DIET","P_SMOKE","P_DRINK","P_COMPLEXION","P_BTYPE","P_CHALLENGED","P_NCHALLENGED");
 $("document").ready(function() {

 	getFieldsOnCal();
    setTimeout(function() {
		if($('#listShow').val()==1)
         $("#AlbumMainTab").click(); 
    },100);
   setTimeout(function(){
    var editSection = getSearchQureyParameter('EditWhatNew');
    var ediSec = getSearchQureyParameter('editSec');
    
    var index = null;
    if(editSection && editWhatsNew.hasOwnProperty(editSection)) {
      index = parseInt(editWhatsNew[editSection]);
    }
    if(ediSec && editWhatsNew.hasOwnProperty(ediSec) && !index) {
      index = parseInt(editWhatsNew[ediSec]);
    }
    if(bxslider && index) {
      bxslider.gotoSlide(index);
	//showCalDppSugg(index);
    }
   },200);
});
function appendDppCal()
{
	$("#mainContent").append('<div id="commonOverlay" class="jsmsOverlay overlayZ11 dn"><div id="overlayHead" class="bg1"> <div class="txtc pad15"> <div class="posrel"> <div class="fontthin f19 white">Desired Partner Profile</div> <i id="closeFromDesiredPartnerProfile" class=" posabs mainsp srch_id_cross " style="right:0; top:0px;" onclick="closeDppCal();"></i> </div> </div> </div> <div id="overlayMid" class="bg4 pad3 flowauto fullheight"> <div id="mainHeading" class="color8 fontreg f18 txtc pb10">Relax Your Criteria</div> <div id="dppDescription" class="txtc color8 fontlig f17"></div> <div id="dppSuggestions" class="mb60"></div> </div> <div id="foot" class="posfix fullwid bg7 btmo"> <div class="scrollhid posrel"> <input type="submit" id="upgradeSuggestion" class="fullwid dispbl lh50 txtc f16 pinkRipple white" value="Upgrade Desired Partner Profile"> </div> </div></div>');
}
function setDppDataSuggestions()
{
                        showLoader();
                        $.ajax({
                        url:"/api/v1/profile/dppSuggestionsCAL",
                        datatype:'json',
                        cache: true,
                        asyn:true,
                        success: function(result){
                            if(result)
                            {
                                result=JSON.parse(result);
                                dppSuggestionsData=result;
                                $('body').css('background-color', '#fff');
                                if(dppSuggestionsData['dppData'].length>1)
                                {
                                        appendData(dppSuggestionsData);
					hideLoader();
					if(mutualMatchCount<100)
					{             
						$("#ed_slider").addClass("dn");
				                $("#commonOverlay").removeClass("dn");
					}
                                }
                            }
                        }
                        });
}
function appendData(obj) {
    if (obj.Description != null || obj.Description != undefined) {
	$("#dppDescription").empty();
        $("#dppDescription").append(obj.Description);
    }
    $.each(obj.dppData, function(index, elem) {
        if (elem) {
            if (elem.heading && elem.data) {
		$('#suggest_' + elem.type).remove();
                $("#dppSuggestions").append('<div class="brdr1 pad2 dispnone" id="suggest_' + elem.type + '"><div id="heading_' + elem.type + '" class="txtc fontreg pb10 color8 f16">' + elem.heading + '</div></div>');
                if (elem.range == 0) {
                    $.each(elem.data, function(index2, elem2) {
                        $("#suggest_" + elem.type).removeClass("dispnone").append('<div class="suggestOption brdr18 fontreg txtc color8 f16 dispibl" value="' + index2 + '">' + elem2 + '</div>');
                    });
                } else if (elem.type == "AGE") {
                    if (elem.data.HAGE != undefined && elem.data.LAGE != undefined) {
			$("#LAGE_HAGE").remove();
                        $("#suggest_" + elem.type).removeClass("dispnone").append('<div id="LAGE_HAGE" class="suggestOption suggestOptionRange brdr18 fontreg color8 f16 txtc" value="' + elem.data.LAGE + '_' + elem.data.HAGE + '">' + elem.data.LAGE + 'years - ' + elem.data.HAGE + 'years  </div>');
                    }
                } else if (elem.type == "INCOME") {
                    if (elem.data.LDS != undefined && elem.data.LDS != null && elem.data.HDS != undefined && elem.data.HDS != null) {
			$("#LDS_HDS").remove();
                        $("#suggest_" + elem.type).removeClass("dispnone").append('<div id="LDS_HDS" class="suggestOption suggestOptionRange2 brdr18 fontreg color8 f16 txtc" value="' + elem.data.LDS + '_' + elem.data.HDS + '">' + elem.data.LDS + ' - ' + elem.data.HDS + '</div>');
                    }
                    if (elem.data.LRS != undefined && elem.data.LRS != null && elem.data.HRS != undefined && elem.data.HRS != null) {
			$("#LRS_HRS").remove();
                        $("#suggest_" + elem.type).removeClass("dispnone").append('<div id="LRS_HRS" class="suggestOption suggestOptionRange2 brdr18 fontreg color8 f16 txtc" value="' + elem.data.LRS + '_' + elem.data.HRS + '">' + elem.data.LRS + ' - ' + elem.data.HRS + '</div>');
                    };
                    if (elem.data.LRS == "No Income" && elem.data.LDS == "No Income" && elem.data.HRS == "and above" && elem.data.HDS == "and above") {
                        $("#LDS_HDS").remove();
                        $("#LRS_HRS").addClass("bothData");
                    }
                }
            }
        }
    });
    setTimeout(function() {
        $(".suggestOption").each(function() {
            $(this).off("click").on("click", function() {
                $(this).toggleClass("suggestSelected");
            });
        });
        $("#upgradeSuggestion").off("click").on("click", function() {
            if ($(".suggestSelected").length == 0) {
                ShowTopDownError(["Please select at least one suggestion."]);
            } else {
                var sendObj = [];
                $("#dppSuggestions").children().each(function(index, element) {
                    var type = $(this).attr("id").split("_")[1],
                        objFinal, valueArr;
                    if (type == "AGE" && $("#LAGE_HAGE").hasClass("suggestSelected")) {
                        valueArr = $(this).find(".suggestOptionRange").attr("value");
                        objFinal = {
                            "type": type,
                            "data": {
                                "LAGE": valueArr.split("_")[0],
                                "HAGE": valueArr.split("_")[1]
                            }
                        };
                        sendObj.push(objFinal);
                    } else if (type == "INCOME") {
                        var LDS, HDS, LRS, HRS, dataArr;
                        if ($("#LDS_HDS").hasClass("suggestSelected") && $("#LRS_HRS").hasClass("suggestSelected") == false) {
                            LDS = $("#LDS_HDS").attr("value").split("_")[0], HDS = $("#LDS_HDS").attr("value").split("_")[1];
                            dataArr = {
                                "LDS": LDS,
                                "HDS": HDS
                            };
                        } else if ($("#LRS_HRS").hasClass("suggestSelected") && $("#LDS_HDS").hasClass("suggestSelected") == false) {
                            if ($("#LRS_HRS").hasClass("bothData")) {
                                dataArr = {
                                    "LRS": "No Income",
                                    "HRS": "and above",
                                    "LDS": "No Income",
                                    "HDS": "and above"
                                };
                            } else {
                                LRS = $("#LRS_HRS").attr("value").split("_")[0], HRS = $("#LRS_HRS").attr("value").split("_")[1];
                                dataArr = {
                                    "LRS": LRS,
                                    "HRS": HRS
                                };
                            }
                        } else if ($("#LRS_HRS").hasClass("suggestSelected") && $("#LDS_HDS").hasClass("suggestSelected")) {
                            LDS = $("#LDS_HDS").attr("value").split("_")[0], HDS = $("#LDS_HDS").attr("value").split("_")[1], LRS = $("#LRS_HRS").attr("value").split("_")[0], HRS = $("#LRS_HRS").attr("value").split("_")[1];
                            dataArr = {
                                "LRS": LRS,
                                "HRS": HRS,
                                "LDS": LDS,
                                "HDS": HDS
                            };
                        }
                        objFinal = {
                            "type": type,
                            "data": dataArr
                        };
                        sendObj.push(objFinal);
                    } else {
                        valueArr = [];
                        $(element).find(".suggestSelected").each(function(index2, element2) {
                            valueArr.push($(this).attr("value"));
                        });
                        if (valueArr.length != 0) {
                            objFinal = {
                                "type": type,
                                "data": valueArr
                            };
                            sendObj.push(objFinal);
                        }
                    }
                });
                var url = JSON.stringify(sendObj).split('"').join("%22");
			closeDppCal();
	      showLoader();
                $.ajax({
                    url: '/api/v1/profile/dppSuggestionsSaveCAL?dppSaveData=' + url,
                    type: 'POST',
                    success: function(response) {
			      showLoader();
			window.location.href = "/profile/viewprofile.php?ownview=1#Dpp";
				hideLoader();
                    },
                    error: function(response) {
			      showLoader();
				window.location.href = "/profile/viewprofile.php?ownview=1#Dpp";
				hideLoader();
			}
                });
            }
        });
        startTouchEvents(1)
    }, 50);

}

function closeDppCal()
{
	$("#ed_slider").removeClass("dn");
	$("#commonOverlay").addClass("dn");
}
var albumNoPhotoStr="";
(function($){
var mobEditPage=(function(){
	
	function mobEditPage(){
	appendDppCal();
	var ele=this;
	var pageJson;
	var sliderDiv;
	$.ajax({
          url: "/api/v1/profile/editprofile?sectionFlag=all",
          dataType: 'json',
          cache: true,
          async: true,
          success: function(result) {
			  
		if(CommonErrorHandling(result))
		{
			result=formatJsonOutput(result);
			
			for( var k in result.Dpp.BasicDetails.OnClick )
			{
				if ( result.Dpp.BasicDetails.OnClick[k]['key'] == "P_MATCHCOUNT")
				{
					mutualMatchCount = parseInt((result.Dpp.BasicDetails.OnClick[6]['value'])).toLocaleString();
					/*
				   	variable to store threshold for mutual match count.
				    */
				   	var mutualMatchCountThreshold = 100;

					$("#mutualMatchCountMobile").css("padding","2px");
					$("#mutualMatchCountMobile").text(parseInt((result.Dpp.BasicDetails.OnClick[k]['value'])).toLocaleString());
					$("#mutualMatchCountMobile").attr("data-value",parseInt((result.Dpp.BasicDetails.OnClick[k]['value'])));
					if(mutualMatchCount<100)
					{
						$("#increaseMatch").remove();
						$("#mutualMatchCountMobile").parent().append("<p id='increaseMatch'><a onClick='setDppDataSuggestions();' class='color2'>Increase Matches</a></p>");
					}

                    if ( parseInt($("#mutualMatchCountMobile").text().replace(",","") ) >= mutualMatchCountThreshold )
                    {
                    	$("#mutualMatchCountMobile").css('color', '')
                    	$("#mutualMatchCountMobile").removeClass("bg7");
                    	$("#mutualMatchCountMobile").addClass("dpbg1");
                    }
                    else
                    {
                    	$("#mutualMatchCountMobile").css('color', 'white')
                    	$("#mutualMatchCountMobile").removeClass("dpbg1");
                    	$("#mutualMatchCountMobile").addClass("bg7");
                    }
				}
			}

			changingEditData=ele.pageJson=result;
			originalEditData=JSON.parse(JSON.stringify(changingEditData));
			ele.sliderDiv=$( "#sw" ).html();
			ele.ed_sliderDiv=$( "#ed_slider" ).html();
			underScreenStr=$("#under_screening").html();
			ele.CreatePage();
			
			SlideToCurrentPage();
                        $.ajax({
                        url:"/api/v1/profile/filter?sectionFlag=all",
                        datatype:'json',
                        cache: true,
                        asyn:true,
                        success: function(result){
                            if(CommonErrorHandling(result))
                            {
                                result=formatJsonOutput(result);
                                filterJson=result;
                            }	
                        }
                        });
			PhotoUpload();
                        privacybind();
                        // check for showing email verification link sent confirmation
                        if(typeof editFieldArr != 'undefined')
                        {
                        if(Object.keys(editFieldArr).length==1 && (editFieldArr.ALT_EMAIL == result.Contact.ALT_EMAIL.outerSectionValue) && editFieldArr.ALT_EMAIL) 
                                    showAlternateConfirmLayerMS(editFieldArr.ALT_EMAIL);
                        if(Object.keys(editFieldArr).length==1 && (editFieldArr.EMAIL == result.Contact.EMAIL.outerSectionValue) && editFieldArr.EMAIL) 
                                    showAlternateConfirmLayerMS(editFieldArr.EMAIL);
                        }
                        
                            setTimeout(function(){stopLoader()},200);
		}
		else{
			//setTimeout(function(){stopLoader()},200);
			 return;
		}
		
	  }
        });
        
	}
	
	
	mobEditPage.prototype.createTabs=function(pageName){
		var sliderDiv=this.sliderDiv;
		var originalDiv=this.sliderDiv;
		var i=1;
                var prev,current="";
                var flag1=0;
		$.each(this.pageJson, function(key, value)
		{      
                        prev=current;
                         current=key;
                        if(key=="Dpp")
                            current="Desired Partner";
                        if(key=="Details")
                        {
                                current="Basic Info";
                        }
			//sliderDiv=sliderDiv.replace('sw', key+'_info_slider');
			sliderDiv=sliderDiv.replace(/subHeadTab/g, key+'SubHead');
			sliderDiv=sliderDiv.replace(/innerSubHeadTab/g, key+'innerSubHead');
			sliderDiv=sliderDiv.replace(/leftTabName/g, key+'leftTab');
			sliderDiv=sliderDiv.replace(/MainTabName/g, key+'MainTab');
			sliderDiv=sliderDiv.replace(/RightTabName/g, key+'RightTab');
			sliderDiv=sliderDiv.replace(/EditSection/g, key+'EditSection');
			sliderDiv=sliderDiv.replace(/sliderName/g, key+'slidername');
			sliderDiv=sliderDiv.replace(/LeftTabValue/g, prev);
			sliderDiv=sliderDiv.replace(/MainTabValue/g, current);
                        if((key=="Education"||key=="Kundli")&&(flag1==0))
                        {
                            if(key=="Kundli")
                                editWhatsNew["FocusDpp"] = "8";
                            $("#DetailsRightTab").html(current);
                            flag1=1;
                        }
                        else
                            $("#"+prev+"RightTab").html(current);
			if(i==1)
				$( "#sw" ).html(sliderDiv);
			else
				$( "#sw" ).append(sliderDiv);
                        $("#"+key+"leftTab").click(function()
                        {
                            bxslider.PrevSlide();
                        });
                        $("#"+key+"RightTab").click(function()
                        {
                            bxslider.NextSlide();
/*
				var hashVal=document.location.hash.replace(",historyCall","");
				var actualHash=hashVal.replace("#","");
				if(!sliderCurrentPage)
					sliderCurrentPage=$("#"+actualHash+"slidername").attr("index");
				showCalDppSugg(sliderCurrentPage);
*/
                        });
			i=2;
			var topbarh=$("#topbar").height();
			var sectiontab=$("#"+key+"SubHead").height();
			var screenH=$(window).height();
			
			if(key=="Dpp" || key=="Kundli")
				$("#"+key+"EditSection").height(screenH-sectiontab-topbarh-50);
			else
				$("#"+key+"EditSection").height(screenH-sectiontab-topbarh);
			
			
			
			sliderDiv=originalDiv;
		});
		
	};
	
	mobEditPage.prototype.createSectionsHomepage=function(pageName){
		var ele=this;
		var options={};
		var st=0;
		
		if(!albumPresent)
			albumNoPhotoStr=$("#albumPage").html();
		$("#albumPage").remove();
		$.each(this.pageJson, function(key, value)
		{
			if($.inArray(key,editSectionArr)>-1)
			{
			st++;
			var sliderDiv=$( "#"+key+"EditSection" ).html();
			var originalDiv=sliderDiv;
			
			var i=1;
						
			if(key=="Album")
			{
				$( "#"+key+"EditSection" ).html(albumNoPhotoStr);
				var topbarh=$("#topbar").height();
				var sectiontab=$("#"+key+"SubHead").height();
				var screenH=$(window).height();
				var screenW=$(window).width();
				var screenT=sectiontab;
				var privacyH=$("#privacyoptionshow").height(); 
				var total=$("#addPhotoAlbumPage").height(); 
				var divHgt = $(".overlay_pu.posabs").height();
				var editHgt=$("#"+key+"EditSection").height();
				$("#"+key+"privacyOption").height(total-(sectiontab+topbarh));
				$("#"+key+"picture").height(screenH);
				$("#"+key+"overlay").height(screenH);
				$("#"+key+"picture").width(screenW);
				$("#"+key+"overlay").width(screenW);
				$("#"+key+"overlay").css({"top":"sectiontab"});
					i=2;
	$("#privacy_button").bind("click",function()
	{
		$( "#"+key+"EditSection" ).height(privacyH);
		$("#privacyoptionshow").show();
		$("#privacyOption").hide();
		$("#topbar").hide();
		$("#AlbumSubHead").hide();
}
);

	if(typeof(fromCALphoto)!='undefined' && fromCALphoto == '1')
	{ 
	    var newUrl=document.location.href.replace('fromCALphoto','');
	    history.pushState('', '', newUrl);
		$( "#"+key+"EditSection" ).height(privacyH);
		$("#privacyoptionshow").show();
		$("#privacyOption").hide();
		$("#topbar").hide();
		$("#AlbumSubHead").hide();
	}

	$("#privacyoptionclose").click(function()
       	{
				$( "#"+key+"EditSection" ).height(editHgt);
                $("#privacyoptionshow").hide();
                $("#privacyOption").show();
                $("#topbar").show();
	$("#AlbumSubHead").show();
	SkipToSection();
});
			}
			else
			{
				if(value!=null)
				$.each(value, function(k ,v)
				{
					if($.inArray(k,editInArr[key])>-1)
					{
					if(v.outerSectionKey!="NameoftheProfileCreator")
					{
					
					sliderDiv=sliderDiv.replace('EditFieldName', v.outerSectionKey+'_name');
					sliderDiv=sliderDiv.replace('EditFieldLabelValue', v.outerSectionKey+'_value');				
					sliderDiv=sliderDiv.replace(/OVERLAYID/g,key+","+k);
          sliderDiv=sliderDiv.replace(/ARROWID/g, v.outerSectionKey+'Arrow');
					if(i==1)
						$( "#"+key+"EditSection" ).html(sliderDiv);
					else
						$( "#"+key+"EditSection" ).append(sliderDiv);
					$( "#"+v.outerSectionKey+'_name' ).text(v.outerSectionName);
                                        if(v.outerSectionNameSubHeading){
                                                $( "#"+v.outerSectionKey+'_name' ).append("<span class='f13 dispibl'>"+v.outerSectionNameSubHeading+"</span");
                                        }
					
					var emptyFields=0;
						var jsonCnt=0;
					var sectionStr="";					
					
					//Email (Verify link or Verified text)
					if(v.outerSectionKey=='EmailId' && v.OnClick[1].verifyStatus==0 && v.OnClick[1].label_val!="" && v.OnClick[1].label_val!=null)
					{
						$( "#"+v.outerSectionKey+'_name' ).append("<div id='EmailVerify' class='padl10 dispibl color2'>Verify</div>");
                                                bindEmailButtons();
					}
					else if(v.outerSectionKey=='EmailId' && v.OnClick[1].verifyStatus==1 && v.OnClick[1].label_val!="" && v.OnClick[1].label_val!=null)
					{
						$( "#"+v.outerSectionKey+'_name' ).append("<div id='EmailVerified' class='padl10 dispibl color4'>Verified</div>");                                              
					}
					
					//alternateEmail (Verify link or Verified text)
					if(v.outerSectionKey=='AlternateEmailId' && v.OnClick[2].verifyStatus==0 && v.OnClick[2].label_val!="" && v.OnClick[2].label_val!=null)
					{
						$( "#"+v.outerSectionKey+'_name' ).append("<div id='altEmailVerify' class='padl10 dispibl color2'>Verify</div>");
                                                bindEmailButtons();
					}
					else if(v.outerSectionKey=='AlternateEmailId' && v.OnClick[2].verifyStatus==1 && v.OnClick[2].label_val!="" && v.OnClick[2].label_val!=null)
					{
						$( "#"+v.outerSectionKey+'_name' ).append("<div id='altEmailVerified' class='padl10 dispibl color4'>Verified</div>");                                              
					}

					if(v.singleKey)
					{
						
						
						jsonCnt=1;
						sectionStr=v.outerSectionValue;
						
						if(v.OnClick.length==1)
						{
							if(!v.OnClick[0].label_val)
							{
								sectionStr=getPlaceholder(v.OnClick[0].key);
								emptyFields++;
								if(!sectionStr)
									sectionStr=NOT_FILLED_IN;
							}
							else
								sectionStr=v.OnClick[0].label_val;
							
							
							if(v.OnClick[0].screenBit==1 && v.OnClick[0].label_val!=""){
								$("#"+v.outerSectionKey+"_name").append(underScreenStr);
							}
						}
						else
						{
							if(v.outerSectionKey=="NameoftheProfileCreator" && v.OnClick[0].screenBit==1 && v.OnClick[0].label_val!="")
							{
								$("#"+v.outerSectionKey+"_name").append(underScreenStr);
							}
							if(v.outerSectionKey=="NameoftheProfileCreator" && v.OnClick[0].label_val=="")
							{
									sectionStr=NOT_FILLED_IN;
									emptyFields=1;
							}
							if(v.outerSectionKey=="SuitableTimetoCall" && v.OnClick[v.OnClick.length-1].label_val=="")
							{
								sectionStr=NOT_FILLED_IN;
                                                                emptyFields=1;
							}
								
						}				
					}
					else
					{
						
											var c=0,d=0;
						
						$.each(v.OnClick, function(k1 ,v1)
						{
						if($.inArray(v1.key,editValArr[k])>-1)
						{
							var sectionArr={};
							if(v1.label_val!="N_B")
							{
															if((c==1)&&(d<=3))
															{
																return;
																d++;
															}
								
												
									//console.log(v1.key);				
								if(v1.key=="YOURINFO" || v1.key=="JOB_INFO" || v1.key=="SPOUSE" || v1.key=="EDUCATION" || v1.key=="FAMILYINFO"){
									//console.log(v1.key);
									sectionArr=checkEmpty(v1.label_val,v1.label_val);
									sectionArr[0]=readMore(sectionArr[0],v1.key);
									
									if(v1.screenBit==1 && v1.label_val!=""){
										$( "#"+v.outerSectionKey+"_name" ).append(underScreenStr);
									}
										
								}
								else
								{
									if(!v1.hidden)
										sectionArr=checkEmpty(v1.label,v1.label_val);
									//console.log(sectionStr);
								}
								
								if(getPlaceholder(v1.key) && !v1.label_val)
									sectionStr=getPlaceholder(v1.key);
									
								if(!v1.hidden)
								{
									jsonCnt++;
									sectionStr+=sectionArr[0]+", ";
								}
								if(sectionArr[1])
									emptyFields++;
								if(v1.key=="AMRITDHARI")
								{   
									if((v1.value=='Y')||(v1.value=='')||(v1.value==null))
									{
									  c=1;
									}
								}
							}
						}
						});
							
							sectionStr = sectionStr.replace(/,\ +$/, '');
					}
					
					if(jsonCnt==emptyFields && jsonCnt>0)
					$( "#"+v.outerSectionKey+'_name' ).parent().addClass("notfilled");
					
					$( "#"+v.outerSectionKey+'_value' ).html(sectionStr);
					

					//to be done
					//$( "#"+v.OnClick.key+'_name' ).bind.Click();
					
					i=2;
					sliderDiv=originalDiv;
  				  }
				}
				});
				
				if(st==1)
					options.offsetTop=$("#"+key+"EditSection").offset().top;
				$("#"+key+"EditSection").OnlyVertical(options);
			}
			}
		});
		
		slide();
		overlaySet();
		SkipToSection();
		var filterButton=$("#filterButton").html();
		$("#DppEditSection").after(filterButton);
    var dppHint = $("#dppToolTip").html();
    $("#DppEditSection").prepend(dppHint);
    var dppMatchalertToggle = $("#dppMatchalertToggle").html();
    $("#DppEditSection").prepend(dppMatchalertToggle);
    //Check horoscope button exist, if yes then add functionality
    if($("#horoscopeButton").length){
      var horoscopeButton=$("#horoscopeButton").html();
      $("#KundliEditSection").after(horoscopeButton);
    
      $('.js-createHoroscope').on('click',onHoroscopeButtonClick);
      if(typeof(fromCALHoro)!='undefined' && fromCALHoro == '1')
      {
      	var newUrl=document.location.href.replace('fromCALHoro','');
	    history.pushState('', '', newUrl);

      	onHoroscopeButtonClick();
        }
    }
    
	};
	
	mobEditPage.prototype.CreatePage=function(){
		var str="";
		var i=1;
			var ele=this;
			ele.createTabs();
			//ele.createSections();
			ele.createSectionsHomepage();	
	};
	
	mobEditPage.prototype.CreateTab=function(){
	};
	mobEditPage.prototype.CreateSection=function(){
	};
	
	this.mobEditPage=mobEditPage;
}).call(this);

})(jQuery)


	
function formatJsonOutput(result)
{
	delete(result.AUTHCHECKSUM);    
	delete(result.hamburgerDetails);
	delete(result.phoneDetails);
	delete(result.responseMessage);
	delete(result.responseStatusCode);
        delete(result.imageCopyServer);
        delete(result.cache_flag);
        delete(result.cache_interval);
        delete(result.resetCache);
        delete(result.flagForAppRatingControl);
	delete(result.xmppLoginOn);
	$.each(result, function(key, value)
	{
		if($.inArray(key,editSectionArr)<=-1)
		{
			delete(result[key]);
		}
	});
	return result;
}

function sendAjaxForToggleMatchalertLogic(setValue)
{
    $.ajax({
          url: "/api/v1/search/matchAlertToggleLogic",
          dataType: 'json',
          method: "POST",
          cache: true,
          async: true,
          data:{logic:setValue},
          success: function(result) {
	  }
    });
}
function toggleDppMatchalerts(setValue){
            $("#toggleButton").toggleClass("filter-off").toggleClass("filter-on");
            
            if($("#toggleButton").hasClass("filter-on"))
            { 
                sendAjaxForToggleMatchalertLogic("history");
            }
            else
            {  
                sendAjaxForToggleMatchalertLogic("dpp");
            }
}

function readMore(string,keyName)
{
	string=string.trim();
	var readMoreStr="";
	if(string.length>100){
	readMoreStr= [string.slice(0, 100), "<span id=\""+keyName+"_less\" class=\"dn\" >", string.slice(100)].join('');
	readMoreStr=readMoreStr+"</span><span id=\""+keyName+"_more\" onClick=\"ToggleMore(\'"+keyName+"\')\" class=\"color2\"> ...more</span>";
	return readMoreStr;
	}
	else
		return string;
}

function showAlternateConfirmLayerMS(email){
                var altEmail = typeof email !='undefined' ? email :   $("#AlternateEmailId_value").eq(0).text().trim();
                var obj = $("#emailSentConfirmLayer");
                var msg = obj.find("#altEmailDefaultText").eq(0).val().replace(/\{email\}/g,altEmail);
                obj.find("#emailConfirmationText").eq(0).text(msg);
                obj.show();
                var tempOb=$("#altEmailinnerLayer");
                tempOb.css('margin-left','-'+$(tempOb).width()/2+'px')
                    .css('margin-top','-'+$(tempOb).height()/2+'px');   

    
    
    
    
}

function bindEmailButtons(){
    $("#altEmailVerify,#EmailVerify").unbind();
    $("#altEmailVerify,#EmailVerify").click(function(event)
    {
      event.stopPropagation();
      $("#newLoader").show();
      
      var this_id = $(this).attr('id'),emailType='',email='';
      if(this_id == 'altEmailVerify')
      {
          emailType=2;
          email=$("#AlternateEmailId_value").eq(0).text().trim();
      }
      else 
      {
          emailType=1;
          email=$("#EmailId_value").eq(0).text().trim();
          
      }
      var ajaxData={'emailType':emailType};
      $.ajax({
                                url:'/api/v1/profile/sendEmailVerLink',
                                dataType: 'json',
                                data: ajaxData,
                                type: "POST",
                                success: function(response) 
                                {
                                    $("#newLoader").hide();
                                    showAlternateConfirmLayerMS(email);
                                }
    
            });
    });
}

   /**
    * function is called when a field needs to be opened
    */
  function getFieldsOnCal()
  {
    fieldIdMappingArray = {
      "name" : { "type": "text","mobileDivFieldId":"BasicDetails_name","mobileFieldId":"NAME"},
    	// contact section
      "EMAIL" : { "type": "text","mobileDivFieldId":"EmailId_name","mobileFieldId":"EMAIL"},
      "ALT_EMAIL" : { "type": "text","mobileDivFieldId":"AlternateEmailId_name","mobileFieldId":"ALT_EMAIL"},
    };


    mobileSectionArray = {"education":"Education","basic":"Details",
    	"career":"Career","lifestyle":"Lifestyle","contact":"Contact","family":"Family","dpp":"Dpp"
    }

    section = getUrlParameter('section');
    fieldName = getUrlParameter('fieldName');
    if ( typeof section !== 'undefined' && mobileSectionArray.hasOwnProperty(section))
    {
      fieldType = '';
      mobileDivFieldId = '';
      mobileFieldId = '';
      if ( typeof fieldName !== 'undefined' && fieldIdMappingArray.hasOwnProperty(fieldName) )
      {
        fieldType = fieldIdMappingArray[fieldName].type;
        mobileDivFieldId = fieldIdMappingArray[fieldName].mobileDivFieldId;
        mobileFieldId = fieldIdMappingArray[fieldName].mobileFieldId;
      }
      openFieldsOnCal(mobileSectionArray[section],fieldType,mobileFieldId,mobileDivFieldId);        
    }
    
  }

  /**
   * opens a field
   * @param  {String} section the section which must be clicked
   * @param  {String} fieldType dropdown or text
   * @param  {String} fieldType dropdown or text
   * @param  {String} fieldDivId   the parent id
   */
  function openFieldsOnCal(section,fieldType,fieldId,fieldDivId) 
  {
  	var timeoutFieldCheck = 100;
  	var timeoutDropdown = 100;
    if ( fieldDivId === '')
    {
    	window.location.replace(window.location.href+"#"+section);
  	}
  	else
  	{
  		var checkExist = setInterval(function() 
        {
            if ($('#'+fieldDivId).length) 
            {
                $("#"+fieldDivId).click();
                if ( fieldType == 'text' && $("#"+fieldId).length )
                {
                	setTimeout(function() {   
    	            	$("#"+fieldId).focus();
					}, timeoutDropdown);
                }
                else if ( fieldType == 'dropdown' && $("#"+fieldId).length )
                {
                	setTimeout(function() {   
    	            	$("#"+fieldId).click();
					}, timeoutDropdown);

                }
                clearInterval(checkExist);
            }


        }, timeoutFieldCheck);
  	}
  }

  /**
   * function is used to get url get parameters
   * @return {String}      get parameter
   */
	function getUrlParameter(sParam) {
	var sPageURL = decodeURIComponent(window.location.search.substring(1)),
	    sURLVariables = sPageURL.split('&'),
	    sParameterName,
	    i;

	for (i = 0; i < sURLVariables.length; i++) {
	    sParameterName = sURLVariables[i].split('=');

	    if (sParameterName[0] === sParam) {
	        return sParameterName[1] === undefined ? true : sParameterName[1];
	    }
	}
};
