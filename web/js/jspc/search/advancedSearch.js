var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"100%"},
      '.chosen-select-no-search' : {disable_search:true,width:"100%"}
    
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
var incomeLDOL = 0;
function fillValuesInChosen(ele){

  
    valueToFill = parseValueForChosen($(ele).attr("data"));
    if(valueToFill != "" && valueToFill != null){
      $(ele).val(valueToFill);
      $(ele).trigger("chosen:updated");


      var getID =  $(ele).attr('id');

      $('#'+getID+'-rem').css('visibility','visible');

    }
    else
    {
      $(ele).val([]);
      $(ele).trigger("chosen:updated");
    }
 
}
function covertInArray(value){
    var arr = value.split(",");
    
     if(arr.indexOf("DM") !==-1 ){
      var arrOut = [];
      for(var i=0;i<arr.length;i++){
        if(arr[i] == "DM"){
          arrOut[i] = "";
        } 
      }
      arr = arrOut;
    }
    
    return arr;
}
function parseValueForChosen(fieldValue){
    
    if(typeof(fieldValue) == "string" && fieldValue.indexOf(",")!=-1){
      return covertInArray(fieldValue);
    }
if(typeof(fieldValue) == "string" && fieldValue=="DM"){
      return "";
    }
    
    return fieldValue;
}
    
function fillValuesInRange(ele){
    
    valueToFill = JSON.parse($(ele).attr("data"));
    var id = valueToFill['VALUE'];
    $(ele).find('span').html(valueToFill['LABEL']);
    $(ele).find('ul #'+id).addClass('js-selected');
  
}

function disableRangeOption(fieldName,minValue){

    var listId = '#'+fieldName;
    
    var arrOption = $(listId + ' li');
    var maxOption = null;
    var markDisabledClass = "color12 bg-white cusorNone";
    
    var specialCheck = fieldName.indexOf('Income') !== -1 ? true : false;
    
    for(var i=0;i<arrOption.length;i++){
      var value = parseInt($(arrOption[i]).attr('id'));
      var bHasClass = $(arrOption[i]).hasClass(markDisabledClass);
      
      minValue = parseInt(minValue);
      
      if(value < minValue && bHasClass == false){
        $(arrOption[i]).addClass(markDisabledClass);
      }
      
      if(bHasClass && value >= minValue ){
        $(arrOption[i]).removeClass(markDisabledClass);
      }
      
      if(value == minValue ){
        maxOption = arrOption[i];
      }
      //For income choose one group higher
      if(specialCheck && value == minValue){
        $(arrOption[i]).addClass(markDisabledClass);
        maxOption = arrOption[i+1];
      }
      
      if(specialCheck && value === 19 /*and above option*/){
        $(arrOption[i]).removeClass(markDisabledClass);
      }
    }
    
    //Now check for selected value in agemax and reset it if required
    var domEle = $(listId+' li.js-selected');
    if(domEle.length === 0){
      $(maxOption).trigger("click");
      return;
    }
    
    //In case of income checl equal to also
    if(specialCheck && parseInt($(domEle).attr('id')) <= parseInt(minValue) )
    {
      $(maxOption).trigger("click");
    }
    else if(parseInt($(domEle).attr('data')) < parseInt(minValue) )
    {
      $(maxOption).trigger("click");
    }
  }

function disableFieldsOption(fieldId,value){
  
    if(fieldId == "Gender"){
      disableRangeOption("Min_Age",value);
    }
    if(fieldId == "Min_Age"){
      disableRangeOption("Max_Age",value);
    }
    if(fieldId == "Min_Height"){
      disableRangeOption("Max_Height",value);
    }
    if(fieldId == "rsLIncome"){
      disableRangeOption("rsHIncome",value);
    }
     if(fieldId == "doLIncome"){
      disableRangeOption("doHIncome",value);
    }
}

function updateCastOption(religionValArray){
  var casteField = $('#partner_caste_arr');
  if(false === religionValArray instanceof Array && typeof(religionValArray) == "string" && religionValArray.length ){
    var temp = religionValArray;
    religionValArray = [temp];
  }
  
  if(true === religionValArray instanceof Array && religionValArray.length === 0 ){
    //May be need to empty the option value
    $(casteField).html("");
    $(casteField).append('<option class="textTru chosenDropWid"  value="0">Others</option>');
    $(casteField).trigger("chosen:updated");
    return ;
  }
  var isCasteVisible = false;
  $(casteField).html("");
  if(religionValArray != null){
    var labelCheckArray = [];
    
    for(var i=0;i<religionValArray.length;i++){
    
      if(casteData.hasOwnProperty(religionValArray[i]) === false)
        continue;
      labelCheckArray.push(religionValArray[i]);
      isCasteVisible = true; 
      var casteArray = casteData[religionValArray[i]];
      for(var j=0;j<casteArray.length;j++){
        var key = casteArray[j]['VALUE'];
        var valueLabel = casteArray[j]['LABEL'];
        
        $(casteField).append('<option class="textTru chosenDropWid" value= \"' + key+ '\">' + valueLabel + '</option>');
        
      }
    }
    if(!loggedIn)
    {
      var sectArr = ["2","3"];
      var casteLabel="Caste";
      
      if(labelCheckArray.length==1 && $.inArray(labelCheckArray[0],sectArr)>-1)
      {
        casteLabel="Sect";
      }
      else if(labelCheckArray.length==2 && ($.inArray(labelCheckArray[0],sectArr)>-1 && $.inArray(labelCheckArray[1],sectArr)>-1))
      {
        casteLabel="Sect";
      }
      $("#partner_caste_arrParent").find("label").html(casteLabel);
    }
  }
  $(casteField).trigger("chosen:updated");
  var getRemId =$(casteField).attr('id');
  $('#'+getRemId+'-rem').css('visibility','hidden');
  
  if(isCasteVisible === false){
    showHideField('#partner_caste_arr',false,true);
  }
  else{
    showHideField('#partner_caste_arr',true);
  }
}

function prePopulateDependant(childId,parentId)
{
  var dependantValueToFill = $('#'+childId).attr("data");
  $('#'+parentId).trigger("change");
  if(dependantValueToFill)
  {
    $('#'+childId).attr("data",dependantValueToFill);
    fillValuesInChosen($('#'+childId));
  }
}

function showHideField(fieldSelector,bShow,clearField){
  var parentField = fieldSelector+'Parent';
  if($(fieldSelector).length === 0 && $(parentField).length === 0)
    return ;
  
  if(bShow){
    $(parentField).show();
  }
  else{
    $(parentField).hide();
    $(fieldSelector+'-rem').css('visibility','hidden');
  }
  
  //IF Chosen then empty  all selected value and update the list
  if(typeof(clearField) != 'undefined' && 
     typeof($(fieldSelector).attr('multiple')) != "undefined" && 
     !bShow)
   {
    //Chosen Plugin Field
    $(fieldSelector).val([]);
    $(fieldSelector).trigger("chosen:updated");
    
    
  }
}

function showHideRem2(param)
{
  

  var remID = param.attr('id');
  
  if(param.val()!=null)
  {
    $('#'+remID+'-rem').css('visibility','visible');
  }
  else
  {
     $('#'+remID+'-rem').css('visibility','hidden');
  }
}





$('#partner_country_arr').on("change",function (){

    var values =$(this).val();
    var cityField = '#partner_city_arr';

	  if(values instanceof Array !== false && values.indexOf("51") !== -1 ){
	    showHideField(cityField,true);
	  }
	  else if(typeof values == "string" && values.indexOf("51") !== -1) 
	  {
	    showHideField(cityField,true);
	  }
	  else
	  {
	    showHideField(cityField,false,true);
	  }
  });
$('#partner_mstatus_arr').on("change",function(){
    var values = $(this).val();
    var hasChildrenField='#partner_hchild_arr';
    if(values instanceof Array !== false && (values.length > 1 || values.indexOf("N") === -1)){
      showHideField(hasChildrenField,true);
    }
    else
      showHideField(hasChildrenField,false,true);
  });

  //Binding Religion Section
  $('#partner_religion_arr').on("change",function(){
   updateCastOption($(this).val());     
  });

  $('.js-torem').on("change",function(){
      showHideRem2($(this));
  });

 


  $('.js-selfSelect').on("click",function(){
    $(this).toggleClass("activeopt");
  });
  
  $('#Submit').on("click",function(){
    $form = $("<form action='/search/advanceSearch' name='searchForm' method='post' style='display:none;'></form>");
    
    $('.js-frmfld').each(function(){
      $value="";
      if($(this).is('select'))
      {
        if($(this).val())
          $value= $(this).val();
      }
      else if($(this).hasClass("js-fielddd"))
      {
        $value =  JSON.parse($(this).attr('data'))["VALUE"];
      }
      else if($(this).is('input'))
        $value = $(this).val();
      else if($(this).hasClass("js-selfSelect"))
      {
        if($(this).hasClass("activeopt"))
          $value= $(this).attr("data");
      }
      else
        $value= $(this).attr("data");
      $form.append('<input name="'+$(this).attr("id")+'" value="'+$value+'">');
      
    });
    
    $form.append('<input name="json" value="jspc">');
    
    $('#advSearch_form').html($form);
    document.forms['searchForm'].submit();
  });
  
  

  $('.js-toggle ul li').on("click",function(){
    var parent = $(this).parents(".js-toggle");
    if(!$(this).hasClass("activeopt"))
    {
        $(this).addClass("activeopt");
        $(this).siblings().removeClass("activeopt");
        $(parent).attr("data",$(this).attr("data"));
        if($(parent).attr("id")=="Gender")
        {
          if($(parent).attr("data")=="M") // for groom hide work after marriage and settle abroad 
          {
              showHideField('#partner_wstatus_arr',false,true);
              showHideField('#partner_settle_abroad_arr',false,true);
              disableFieldsOption('Gender',21); // Min Age for Groom LATER
          }
          else
          {
              showHideField('#partner_wstatus_arr',true);
              showHideField('#partner_settle_abroad_arr',true);
              disableFieldsOption('Gender',18);
            
          }
          
          disableFieldsOption('Min_Age',JSON.parse($('#Min_Age').attr('data'))["VALUE"]);
          
        }
    }
  });
function populateAdvanceSearchForm(){
  $(".chosen-container").on('keyup',function(e) {
      $(".chosen-container .chosen-results li").css('float','none');
    });
    $('#srchform [multiple]').each(function(){
      fillValuesInChosen(this);
    });
    $('#srchform .js-fielddd').each(function(){
      fillValuesInRange(this);
    });
    $("#srchform").removeClass("vis-hid");
    prePopulateDependant('partner_caste_arr','partner_religion_arr');
    prePopulateDependant('partner_hchild_arr','partner_mstatus_arr');
    prePopulateDependant('partner_city_arr','partner_country_arr');

    var gender = $("#Gender").attr("data");
    if(gender=="M") // for groom hide work after marriage and settle abroad 
    {
        showHideField('#partner_wstatus_arr',false,true);
        showHideField('#partner_settle_abroad_arr',false,true);
        disableFieldsOption('Gender',21); // Min Age for Groom LATER
    }

    disableFieldsOption('Min_Age',JSON.parse($('#Min_Age').attr('data'))["VALUE"]);
    disableFieldsOption('Min_Height',JSON.parse($('#Min_Height').attr('data'))["VALUE"]);
    disableFieldsOption('rsLIncome',JSON.parse($('#rsLIncome').attr('data'))["VALUE"]);
    disableFieldsOption('doLIncome',JSON.parse($('#doLIncome').attr('data'))["VALUE"]);
    incomeLDOL = JSON.parse($('#doLIncome').attr('data'))["VALUE"];
    $('.js-openSection').each(function(){
      openSection(this);
    });
}
 $(document).ready(function() {
  if(searchList == ""){
    populateAdvanceSearchForm();
  }
  $(document).on('click', '.deleteSearch',function () {
    searchIdToDelete = $(this).attr('data');
    rowRel = $(this).attr('rel');
    liCount = $(".searchlist li").length;
    $.myObj.ajax({
      type: "POST",
      url: "/api/v1/search/saveSearchCall?perform=delete",
      data: {searchId: searchIdToDelete},
      dataType: "json",
      beforeSend: function (xhr) {
        showCommonLoader();
      },
      success: function (result, status, xResponse) {
        hideCommonLoader(); 
        if (result.saveDetails.errorMsg != "null") {
          $(".element" + rowRel).slideUp("normal", function () {
            $(".element" + rowRel).remove();
          }); 
          if(liCount == 1){
            showZeroDiv();
          }
        }
      }
    });
  });
});
var getBottomMoreOpt;


$(document).mousedown(function (e)
{
    $(".js-fielddd").each(function(){
      
      if($(this).has(e.target).length==0)
      {
        
        if(!$(this).find(".hide1").hasClass("disp-none"))
          $(this).find(".hide1").addClass("disp-none");
      }
    });
    
});


function OnScrollChange(event){
   if (checkVisible($('#moreVisArea'))) {
       $('#srchscroll').removeClass( "advsti" );
    } else {
       $('#srchscroll').addClass( "advsti" );
    }
}
function checkVisible( elm, eval ) {
    eval = eval || "visible";
    var vpH = $(window).height(),  st = $(window).scrollTop(), y = $(elm).offset().top, elementHeight = $(elm).height();
    if(st>y)
    {
         return true
    }
    else if (eval == "visible") return ((y < (vpH + st)) && (y > (st - elementHeight)));
}
//function to make an ajax for search by profile id
function callApiForProfileSearched(){
        userPro = $.trim($("#advSearchProId").val()).toUpperCase();;
        showCommonLoader();
        $.myObj.ajax({
          method: "POST",
          url : '/api/v1/profile/detail?stype=WO',
          async:true,
          cache:true,
          dataType: 'json',
          data: {username : userPro ,fromSearchByPId:1},
          success:function(response){
              if(response.responseStatusCode == 0){
                $("#advSearchProIdBox").removeClass("errinp");
                $("#advtitleErr").html("");
                window.location.href = "/profile/viewprofile.php?stype=WO&username="+userPro;
              }
              else{
                $("#advSearchProIdBox").addClass("errinp");
                $("#advtitleErr").html(response.responseMessage).addClass("errcolr").removeClass("grey5");
                $("#advSearchProId").focus();
              }
              hideCommonLoader();
          }
        });
    }


function openSection(ele)
{
      var getVal = $(ele).attr('id'); 
      var getEle = $(ele);    
      if($(ele).parent().hasClass("bg-white"))
      {
        $('.'+getVal+'form').slideUp( "slow", function(){ 
        $(getEle).parent().removeClass('bg-white');
        $(getEle).children().removeClass('advsub');    });
                
      }
      else
      {
        $(ele).parent().addClass('bg-white');
        $(ele).children().addClass('advsub');
        $('.'+getVal+'form').slideDown( "slow");
      }
      
  
}
function setSavedSearchListing(searchData) {
  $("#bottomThinBar").css("left","33.3%");
  var searchLiStructure = $('#searchList').html();
  $('.searchlist').html("");
  if(searchData.saveDetails.details != '' && searchData.saveDetails.details != null){
    $.each(searchData.saveDetails.details, function (key, val) {
      var mapObj = {
        '{eleCount}': removeNull(key),
        '{searchTitle}': removeNull(val.SEARCH_NAME),
        '{searchDetails}': removeNull(val.dataString),
        '{searchId}': removeNull(val.ID)
      };
      searchListLi = $.ReplaceJsVars(searchLiStructure, mapObj);
      $('.searchlist').append(searchListLi);
    });
  }else{
    showZeroDiv();
  }
  $('#savedsearchlist').show();
  $('#srchform').addClass('disp-none');
}
function showZeroDiv(){
  $('.searchlist').html("");
  $('.searchlist').append($('.div-Zero').html());
}
if(searchList != ""){
  setSavedSearchListing(searchList);
}

$(function(){
  $(document).on('click', '#redirectSearch', function(){
     window.location.href = "/search/AdvancedSearch";
  });
  //script for the top tab with 2 options
  $('.srcopt1').click(function(){
    
    var currentTab = $(this).attr('id');
    $('.hide').fadeOut(500,function(){ $('#'+currentTab+'form').fadeIn(500)});
    
    
    
  });
        
        //click on top div
        $("#searchByIdSection").click(function(){
          $('#srchform').fadeOut(500,function(){ 
            $('#savedsearchlist').fadeOut();
            $('#srchbyidform').fadeIn(500)
          });
          $("#bottomThinBar").css("left",LeftMargin);
        });
        
        $("#searchSection").click(function(){
          window.location.href = "/search/AdvancedSearch";
        });
        
        $("#savedSearchSection").click(function(){
          $.myObj.ajax({
            type: "POST",
            url: "/api/v1/search/saveSearchCall?perform=listing",
            dataType: "json",
            beforeSend: function( xhr ) {
                   showCommonLoader(); 
            },
            success: function (result, status, xResponse) {
              hideCommonLoader();
              if(result.saveDetails.errorMsg!="null"){
                setSavedSearchListing(result);
                $('#srchbyidform').fadeOut(500,function(){ 
                $('#srchform').fadeOut();
                $('#savedsearchlist').fadeIn(500)
              });
              }
            }
          });
        });
        
        $("#advsearchByIdBtn").click(function(){
          if($.trim($("#advSearchProId").val()).length == 0){
            $("#advSearchProIdBox").addClass("errinp");
            $("#advtitleErr").html("Required").addClass("errcolr").removeClass("grey5");
            $("#advSearchProId").focus();  
          }
          else
            callApiForProfileSearched();
        });
  
  //script for opening the closed tabs on advance search form
  $('.advopt').click(function(){
      openSection(this);
  });
  
  
  
//script for fixing the search btn at the bottom
   //$(document).on("scroll", OnScrollChange);  
  
  $('.js-fielddd').click(function(ev){
    
    var dd = $(this).find(".js-dd");
  
    if($(dd).hasClass("disp-none"))
    {
      
      $(dd).removeClass("disp-none");
    }
    else{
      
      $(dd).addClass("disp-none");
    }
    var selectedOption = $(this).find('li.js-selected');
    
    if(selectedOption.length == 1 && (parseInt($(selectedOption).position().top) >= 160 || parseInt($(selectedOption).position().top) < 0)){
    
      $(selectedOption).parent().parent().scrollTop($(selectedOption).position().top);
    }
    ev.stopPropagation();
  });
  
  $('.js-fielddd').find("li").click(function(ev){
    
    if($(this).hasClass('color12') === true){
            return false;
          }
    var val ='{"VALUE":"'+$(this).attr('id')+'","LABEL":'+JSON.stringify($(this).attr('data'))+'}';
    var myele = $(this).parents(".js-fielddd");
    $(myele).find('ul .js-selected').removeClass('js-selected');
    $(myele).attr('data',val);
    fillValuesInRange(myele);
    disableFieldsOption($(myele).attr("id"),$(this).attr('id'));
    $(myele).find(".js-dd").addClass("disp-none");
    ev.stopPropagation();
    if($(this).parent("ul").hasClass("list-minlincome") && $(this).attr('id') != 0){
            var myele1 = $("#doLIncome");
            if(myele1.find('ul .js-selected').attr("id") == 0 && incomeLDOL !=12){
                myele1.find('ul .js-selected').removeClass('js-selected');
                myele1.find('ul').find("#12").addClass('js-selected');
                var datalabel = myele1.find('ul .js-selected').attr("data");
                var val1 ='{"VALUE":"12","LABEL":"'+datalabel+'"}';
                $(myele1).attr('data',val1);
                fillValuesInRange(myele1);
        }
    }
  });

  $('.js-remall').click(function(){

    var getID = $(this).attr('id').split('-')[0];  
   
    $('#'+getID).val([" "]).trigger('chosen:updated'); 
    if(getID == 'partner_religion_arr')
    {
       updateCastOption($('#partner_religion_arr').val());
       $('#partner_caste_arr-rem').css('visibility','hidden');
    }
    else if(getID=='partner_country_arr')
    {
      $('#partner_country_arr').trigger('change');
      $('#partner_city_arr-rem').css('visibility','hidden');
      

    }
    else if(getID=='partner_mstatus_arr')
    {
       $('#partner_mstatus_arr').trigger('change');
       $('#partner_hchild_arr-rem').css('visibility','hidden');
    }

    $('#'+getID+'-rem').css('visibility','hidden');

 });
  
});
