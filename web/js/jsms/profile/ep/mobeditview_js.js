var originalEditData={};
var changingEditData={};
var jsonEntry="OnClick";
var sliderCurrentPage="";
var underScreenStr="";
var filterJson="";
var albumPresent=0;
var editWhatsNew = {'FamilyDetails':'5','Edu':'3','Occ':'4','AstroData':'2'};
var bCallCreateHoroscope = false;
 $("document").ready(function() {


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
    }
   },200);
   
});
var albumNoPhotoStr="";
(function($){
var mobEditPage=(function(){
	
	function mobEditPage(){
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
					/*
				   	variable to store threshold for mutual match count.
				    */
				   	var mutualMatchCountThreshold = 100;

					$("#mutualMatchCountMobile").css("padding","2px");

					$("#mutualMatchCountMobile").text(parseInt((result.Dpp.BasicDetails.OnClick[k]['value'])).toLocaleString());
					$("#mutualMatchCountMobile").attr("data-value",parseInt((result.Dpp.BasicDetails.OnClick[k]['value'])));

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
			//console.log(changingEditData);
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
					
					var emptyFields=0;
						var jsonCnt=0;
					var sectionStr="";					

					//Email (Verify link or Verified text)
					if(v.outerSectionKey=='EmailId' && v.OnClick[1].verifyStatus==0 && v.OnClick[1].label_val!="")
					{
						$( "#"+v.outerSectionKey+'_name' ).append("<div id='EmailVerify' class='padl10 dispibl color2'>Verify</div>");
                                                //bindAlternateEmailButton();
					}
					else if(v.outerSectionKey=='EmailId' && v.OnClick[1].verifyStatus==1 && v.OnClick[1].label_val!="")
					{
						$( "#"+v.outerSectionKey+'_name' ).append("<div id='EmailVerified' class='padl10 dispibl color4'>Verified</div>");                                              
					}
					
					//alternateEmail (Verify link or Verified text)
					if(v.outerSectionKey=='AlternateEmailId' && v.OnClick[2].verifyStatus==0 && v.OnClick[2].label_val!="")
					{
						$( "#"+v.outerSectionKey+'_name' ).append("<div id='altEmailVerify' class='padl10 dispibl color2'>Verify</div>");
                                                bindAlternateEmailButton();
					}
					else if(v.outerSectionKey=='AlternateEmailId' && v.OnClick[2].verifyStatus==1 && v.OnClick[2].label_val!="")
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
				});
				
				if(st==1)
					options.offsetTop=$("#"+key+"EditSection").offset().top;
				$("#"+key+"EditSection").OnlyVertical(options);
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

function bindAlternateEmailButton(){
    $("#altEmailVerify").unbind();
    $("#altEmailVerify").click(function(event)
    {
        
      event.stopPropagation();
      showLoader();
      var ajaxData={'emailType':'2'};
      $.ajax({
                                url:'/api/v1/profile/sendEmailVerLink',
                                dataType: 'json',
                                data: ajaxData,
                                type: "POST",
                                success: function(response) 
                                {
                                    hideLoader(1);
                                    showAlternateConfirmLayerMS();
                                }
    
            });
    });
}