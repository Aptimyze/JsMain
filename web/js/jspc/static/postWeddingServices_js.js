
function createCityOptionsList(cityId,optionsJson){

var cityOptionsJson=finalResponse.servicesData.data.cross_selling_section.categories[cityId];
var cityOptionsCount=cityOptionsJson.length;

  var listOptionsHtml='<div id="{{id}}" style="width:190px;" class="fl txtc ulRedirectionUrlBinding cursp" urltoredirect="{{urlToRedirect}}"> <div class="pt10"><img src="{{image}}" class="ulcImage " /><div class="fontmed pt25 ucl_color1 f15 ">{{contentHeading}}</div><div class="fontlig pt5 ucl_color1 f10 ">{{description}}</div></div></div>';
  var outerDiv='<div class="mauto clearfix pt50" style="{{width}}">';
  var  closeOuterDiv='</div>';
  var listCityOptionHtmlfinal="";
var width='';
var loopOf4='';
  for(var i = 0; i < cityOptionsCount; i++)
    { 
      
      if(i%4==0)
      {
        if(i>=4){
          listCityOptionHtmlfinal=listCityOptionHtmlfinal.replace(/\{\{width\}\}/,"width:"+width+"px");
          listCityOptionHtmlfinal+=closeOuterDiv;  
        }
        listCityOptionHtmlfinal+=outerDiv;
        width=190;
      }
      else
        width+=190;

        var listCityHtml1=listOptionsHtml.replace(/\{\{contentHeading\}\}/,cityOptionsJson[i].display_name);
        listCityHtml1=listCityHtml1.replace(/\{\{urlToRedirect\}\}/,cityOptionsJson[i].redirect_url);
        listCityHtml1=listCityHtml1.replace(/\{\{image\}\}/,cityOptionsJson[i].image_url);
        listCityHtml1=listCityHtml1.replace(/\{\{id\}\}/,cityOptionsJson[i].key_name);
        if(cityOptionsJson[i].redirect_url)
          listCityHtml1=listCityHtml1.replace(/\{\{description\}\}/,cityOptionsJson[i].description);
        else
          listCityHtml1=listCityHtml1.replace(/\{\{description\}\}/,'');
        listCityOptionHtmlfinal+=listCityHtml1;              
    }  
    if(width<760){
      listCityOptionHtmlfinal=listCityOptionHtmlfinal.replace(/\{\{width\}\}/,"width:"+width+"px");
      listCityOptionHtmlfinal+=closeOuterDiv;
    }
    $("#ulctabcontent").html(listCityOptionHtmlfinal);
     UrlRedirectionBinding();
}

function createMobCityOptionsList(cityId,optionsJson){
  var cityOptionsJson=finalResponse.servicesData.data.cross_selling_section.categories[cityId];
var cityOptionsCount=cityOptionsJson.length;

  var windowWidth=$(window).width();
  var listWidth=windowWidth/2;  
  var listOptionsHtml='<div id="{{id}}" style="width:'+listWidth+'px;" class="fl ulRedirectionUrlBinding txtc" urltoredirect="{{urlToRedirect}}"> <div class="pt10"><img src="{{image}}" class="ulcImage" /><div class="fontmed pt25 color2 f15 ">{{contentHeading}}</div><div class="fontlig pt5 ucl_color1 f11 ">{{description}}</div></div></div>';
  var outerDiv='<div class="mauto clearfix pt30" style="{{width}}">';
  var  closeOuterDiv='</div>';
  var listCityOptionHtmlfinal="";
var width='';
var loopOf2='';
  for(var i = 0; i < cityOptionsCount; i++)
    { 
      
      if(i%2==0)
      {
        if(i>=2){
          listCityOptionHtmlfinal=listCityOptionHtmlfinal.replace(/\{\{width\}\}/,"width:"+width+"px");
          listCityOptionHtmlfinal+=closeOuterDiv;  
        }
        listCityOptionHtmlfinal+=outerDiv;
        width=listWidth;
      }
      else
        width+=listWidth;

        var listCityHtml1=listOptionsHtml.replace(/\{\{contentHeading\}\}/,cityOptionsJson[i].display_name);
        listCityHtml1=listCityHtml1.replace(/\{\{urlToRedirect\}\}/,cityOptionsJson[i].redirect_url);
        listCityHtml1=listCityHtml1.replace(/\{\{image\}\}/,cityOptionsJson[i].image_url);
        listCityHtml1=listCityHtml1.replace(/\{\{id\}\}/,cityOptionsJson[i].key_name);
        if(cityOptionsJson[i].redirect_url)
          listCityHtml1=listCityHtml1.replace(/\{\{description\}\}/,cityOptionsJson[i].description);
        else
          listCityHtml1=listCityHtml1.replace(/\{\{description\}\}/,'');
        listCityOptionHtmlfinal+=listCityHtml1;              
    }  
    if(width<windowWidth){
      listCityOptionHtmlfinal=listCityOptionHtmlfinal.replace(/\{\{width\}\}/,"width:"+width+"px");
      listCityOptionHtmlfinal+=closeOuterDiv;
    }
    $("#ulctabcontent").html(listCityOptionHtmlfinal);
     UrlRedirectionBinding();
}


function MapUserCityToUrbanClapCity(city)
{
  if(city!="")
  {
    if(city=="new delhi")
      return "city_delhi_v2";
    else if(city=="delhi")
      return "city_delhi_v2";
    else if(city=="ahmedabad")
      return "city_ahmedabad_v2";
    else if(city=="bangalore")
      return "city_bangalore_v2";
    else if(city=="chennai")
      return "city_chennai_v2";
    else if(city=="hyderabad")
      return "city_hyderabad_v2";
    else if(city=="kolkata")
      return "city_kolkata_v2";
    else if(city=="mumbai")
      return "city_mumbai_v2";
    else if(city=="pune")
      return "city_pune_v2";
    else 
      return "";

  }
  else
    return "";
}

function createCityDropDownList(finalResponse,mapUserCity) {
      var combo = $("<select></select>").attr("id", "citiesListMob").addClass('ulcSelectMob ucl_bg1 color2 txtc');

    $.each(cityJson, function (i, val) {
        if(val.key==mapUserCity){
          defaultCityKey=val.key;
         combo.append("<option class='js-tab ' value="+val.key+">" + val.value + "</option>");
        }
        else{
            combo.append("<option class='js-tab ' value="+val.key+">" + val.value + "</option>");
        }
    });

    // OR
    $("#ucltab").append(combo);
     if(defaultCityKey){
       $("#citiesListMob").val(defaultCityKey);
       createMobCityOptionsList(defaultCityKey,finalResponse);

     }
}

function createCityTabList(finalResponse,mapUserCity){
var listCityHtml='<li id="{{id}}" class="js-tab cursp {{active}}" style="width:{{width}}"">{{cityName}}</li>';
      var listCityHtmlfinal="";
      
      var key='';
      var activeSet=false;
    for(var i = 0; i < cityCount; i++)
      {
        if(i==0)
          defaultCityKey= cityJson[0].key;

        width=(790/cityCount)-3;

        listCityHtml1=listCityHtml.replace(/\{\{id\}\}/,cityJson[i].key);
        if(cityJson[i].key==mapUserCity){
          listCityHtml1=listCityHtml1.replace(/\{\{active\}\}/,"active");
          defaultCityKey=cityJson[i].key;
          activeSet=true;
        }
        else
          listCityHtml1=listCityHtml1.replace(/\{\{active\}\}/,"");
        listCityHtml1=listCityHtml1.replace(/\{\{width\}\}/,width+"px");
        listCityHtml1=listCityHtml1.replace(/\{\{cityName\}\}/,cityJson[i].value);
        listCityHtmlfinal+=listCityHtml1;
      }
      $("#citiesList").html(listCityHtmlfinal);
    if(activeSet==false)
      $("#"+cityJson[0].key).addClass("active");
     if(defaultCityKey)
       createCityOptionsList(defaultCityKey,finalResponse);
}

function UrlRedirectionBinding()
{
  $('.ulRedirectionUrlBinding').click(function(){
    var url = $(this).attr("urltoredirect");
    if(url)
     {
      var win = window.open(url, '_blank');
      win.focus();
    }
  });
}

$(document).ready(function() {
  
    mapUserCity=MapUserCityToUrbanClapCity(userCity);
    defaultCityKey= cityJson[0].key;
    $("#jeevansathiLogo").addClass('ucl_bg1');
    $("#poweredBy").attr("urltoredirect",finalResponse.servicesData.data.cross_selling_section.branding.redirect_url);
    $("#ulExploreServices").attr("urltoredirect",finalResponse.servicesData.data.footer_section.cta.redirect_url);

    var DefaultOtherCityHtml=' <div id="otherCityContent" class=" pt50  mb20 txtc fontlig ucl_color1">   {{otherCityContent}}     </div>';
    $(".bg-4").removeClass('bg-4');
    
    if(isMob==1)
      DefaultOtherCityHtml=' <div id="otherCityContent" class=" pt50 padl10 padr10  mb20 txtc fontlig ucl_color1">   {{otherCityContent}}     </div>';
    $(".bg-4").removeClass('bg-4');

  if(finalResponse)
  {
      if(isMob==1)
        createCityDropDownList(finalResponse,mapUserCity);
      else
        createCityTabList(finalResponse,mapUserCity);
  }

  
 
  if(isMob==1)
  {
    $('#citiesListMob').change(function(){
      var getID =  $(this).val();
      if(getID!=="other")
          createMobCityOptionsList(getID,finalResponse);
        else{
          finalOtherHtml=DefaultOtherCityHtml.replace(/\{\{otherCityContent\}\}/,cityJson[cityCount-1].description  );
          $("#ulctabcontent").html(finalOtherHtml);
        }
    });
  }
  else
  {
      $('.js-tab').click(function(){
      
        $('.js-tab').each(function(){
          $(this).removeClass('active');
        });
        $(this).addClass('active');
        
        
        var getID = $(this).attr("id");
        
        $('.js-cont').each(function(){
          $(this).removeClass('active');
        });
        
        $('#'+getID).addClass('active');
        
        if(getID!=="other")
          createCityOptionsList(getID,finalResponse);
        else{
          finalOtherHtml=DefaultOtherCityHtml.replace(/\{\{otherCityContent\}\}/,cityJson[cityCount-1].description  );
          $("#ulctabcontent").html(finalOtherHtml);
        }
      });
  }
 UrlRedirectionBinding();
  
  
});