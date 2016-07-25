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
			
			//console.log(result);
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
                            if(value["basic"]["OnClick"][2]["label_val"]==="Male")
                                current="Groom's Details";
                            else
                                current="Bride's Details";
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
});
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
					
					sliderDiv=sliderDiv.replace('EditFieldName', v.outerSectionKey+'_name');
					sliderDiv=sliderDiv.replace('EditFieldLabelValue', v.outerSectionKey+'_value');				
					sliderDiv=sliderDiv.replace(/OVERLAYID/g,key+","+k);
          sliderDiv=sliderDiv.replace(/ARROWID/g, v.outerSectionKey+'Arrow');
					if(i==1)
						$( "#"+key+"EditSection" ).html(sliderDiv);
					else
						$( "#"+key+"EditSection" ).append(sliderDiv);
					$( "#"+v.outerSectionKey+'_name' ).text(v.outerSectionName)
					
					var emptyFields=0;
						var jsonCnt=0;
					var sectionStr="";
					
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
    //Check horoscope button exist, if yes then add functionality
    if($("#horoscopeButton").length){
      var horoscopeButton=$("#horoscopeButton").html();
      $("#KundliEditSection").after(horoscopeButton);
    
      $('.js-createHoroscope').on('click',onHoroscopeButtonClick);
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
	return result;
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
