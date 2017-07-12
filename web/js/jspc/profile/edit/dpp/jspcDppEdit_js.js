var hideRegSaveButton = 0;
function getWidth(param) {
    var Twidth = $('#' + param)[0].getBoundingClientRect().width;
    return Twidth;
}
function ShowDropDpp(param) {
    var Nwidth = getWidth(param);
    var Nleft = (Nwidth / 2) - 8;
    $('.drop-' + param).css({
        'display': 'block',
        'left': Nleft
    });
    
    $('#' + param + ' .dppbox').show();
        
    //Check any selected option is available & if yes then scroll down to that
    var selectedOption = $('#' + param + ' .dppbox li.js-selected');

    if(selectedOption.length == 1 && 
       (parseInt($(selectedOption).position().top) > 160 || parseInt($(selectedOption).position().top) < 0)
      ){
      $(selectedOption).parent().parent().scrollTop($(selectedOption).position().top);
    }
}
var saveArray = {};
//function to set filter for dpp 
//it calls filter submit api
function setFilters(filterId){
  var editFieldArr = {};
  var status = "Y";
  if($("#"+filterId).hasClass("filterset"))
    status = "N";
  //get current status of all filters and build up the array which is to be submitted
  $('.filter,.filterset').each(function(){
    if(filterId == $(this).attr('id'))
      editFieldArr[$(this).attr('id').split("-")[0]] = status;
    else{
      if($(this).hasClass("filter"))
        editFieldArr[$(this).attr('id').split("-")[0]] = "N";
      else
        editFieldArr[$(this).attr('id').split("-")[0]] = "Y";  
    }
  });  
  $.myObj.ajax({
          url: "/api/v1/profile/filtersubmit",
          type: 'POST',
          datatype: 'json',
          cache: true,
          async: true,
          data: {filterArr : editFieldArr},
          success: function(data) {
            if(data.responseStatusCode == 0){
                setUnsetFilter(status,filterId);
            }
          }
  });
}
function showHideRemLabel(param)
{
 
 var getID = param.attr('id').split('-')[1];
 
 if(param.val()!=null)
 {
   $('#'+getID+'-rem').css('visibility','visible');
 }
 else
 {
   $('#'+getID+'-rem').css('visibility','hidden');
 }
 if(   $('#suggest_'+getID).length != 0    )
 {
   $('#suggest_'+getID).remove();
 }
}

$(function(){
  
	//this function hides the prefilled values and shows the forms fields	
	$('.editclk').click(function(){
            var getfieldID = $(this).attr('id');
                    $('.'+getfieldID+' .posthide').fadeOut(200,"linear",function(){ 
                    $('.'+getfieldID+' .prehide').fadeIn(200,"linear");
                    $('.'+getfieldID+' label').addClass("labelpadding");
            });

                    //Dpp suggestions: start
                    var editCatogary = getfieldID.split("edit")[1];
                    if(editCatogary == "edu"){
                      editCatogary = "education";
                    }
                    if(editCatogary == "basic" || editCatogary == "religion" || editCatogary == "education" && typeof(changeCatogarySuggestion) ==  "function") {
                      changeCatogarySuggestion(editCatogary);
                    }
             //Dpp suggestions : end

            //fill default values into chosen multiselect
	          fillValuesInChosen(getfieldID);
      
            //fill value in range default divs
            fillRangeValues(getfieldID);
            
            //fill value in textbox
            fillTextAreaValues(getfieldID);

            //to hide reg save button on edit click
            hideRegSaveButton++;
            if(hideRegSaveButton>0)
              $('#regSaveButton').hide();
            
      if(getfieldID.indexOf("life") != -1){
        dppAppEvents.toggleNatureHandicap(dppApp.get('p_challenged'));
      }
      
      if(getfieldID.indexOf("basic") != -1){
        dppAppEvents.onCountryChange(dppApp.get('p_country'));
        dppAppEvents.onMaritalChange($('#dpp-p_mstatus').val());
        dppAppEvents.updateRangeUI('agemin',dppApp.get('p_age')[0]);
        dppAppEvents.updateRangeUI('heightmin',dppApp.get('p_height')[0]);
      }
      
      if(getfieldID.indexOf("religion") != -1){
        dppAppEvents.updateCastOption($('#dpp-p_religion').val(),""); 
        var casteValues = dppApp.get('p_caste');
        $('#dpp-p_caste').val(casteValues);
        $('#dpp-p_caste').trigger("chosen:updated");
      }

      if(getfieldID.indexOf("edu") != -1){
        dppAppEvents.updateRangeUI('incomemin',dppApp.get('p_income')[0]);
        dppAppEvents.updateRangeUI('incomedolmin',dppApp.get('p_income')[2]);
        toggleIncomeRangeDol('incomeRangeDol_edit'); 
      }       
	});
        
        $("#closeFromBackend").bind("click",function(){
					window.close();
				});
        //bind click on save button
        $(".js-saveBtn").bind("click",function(){
          var sectionId = $(this).attr('id').split("-")[1];
          $('div[data-sectionid = "'+sectionId+'"] .suggestMain').each(function(index, element) {
            $(this).remove();
          });
         /* setTimeout(function(){
            if($('div[data-sectionid = "'+sectionId+'"] .suggestMain').length != 0){
              $('div[data-sectionid = "'+sectionId+'"] .suggestMain').each(function(index, element) {
                $(this).remove();
              }); 
            }
          },500);*/

          //to show reg save button when all edit sections have been saved
          hideRegSaveButton--;
          if(hideRegSaveButton == 0)
            $('#'+'regSaveButton').show();
          saveSectionsFields(sectionId);
        });
        
        //this function is to set filters on filter button click
        $('.filter,.filterset').click(function(){
          setFilters($(this).attr('id'));
        });
        
        
	//this function shows the drop down for range type
	$('.dppselopt').click(function() {
		$('.hide1').hide();
        var getDppAttr = $(this).attr('data-attr');
        ShowDropDpp(getDppAttr);
        });
        
        //this function shows the drop down for range type on tab click
	$('.js-rangeBox').bind("blur",function() {
          $(" .dppbox,.dpp-up-arrow" ).hide();
        });
        
        //on click of dropdown for range values
        $("ul li:not('.search-field')").bind("click",function(ev){
          
          //If Opacity is present then treat as disabled option
          if($(this).hasClass('opa50') === true){
            return false;
          }
          
          section = $(this).closest('.js-editId').attr("data-sectionId");
          parentDiv = $(this).parent().parent().parent();
          divWithId = parentDiv.find(".js-rangeDiv1,.js-rangeDiv2");
          if(divWithId.length == 0)
             return ;
          fieldName = divWithId.attr("id").split("-")[1];
          parentFieldId = parentDiv[0].id;
          afterLH = fieldName.split("_")[1];
          fieldVal = $(this).attr("data-dbVal");

          //add suggestion on change age and income
          var catogaryParent = parentDiv.closest(".pt20").attr("id").split("dpp-p_")[1].split("Parent")[0];
          if(parentDiv.attr("id").indexOf("dol") != -1) {
            catogaryParent = "incomeDol";
          }
          if(catogaryParent == "age" && $("#ageRange").attr("suggest-select") != 1 || catogaryParent == "income" && $("#incomeRangeRs").attr("suggest-select") != 1|| catogaryParent == "incomeDol"&& $("#incomeRangeDol").attr("suggest-select") != 1)
            changeNonChosenSuggestion(catogaryParent)
          else {
            setTimeout(function() {
              if(catogaryParent == "income"){
                  $("#incomeRangeRs").removeAttr("suggest-select");
              } else if(catogaryParent == "age") {
                  $("#ageRange").removeAttr("suggest-select");
              } else if(catogaryParent == "incomeDol") {
                  $("#incomeRangeDol").removeAttr("suggest-select");
              }
            },100);
          }

          if(afterLH == "income") {//Income 
            afterLH = divWithId.attr("data-income");//either Rs or Dol,but update rest 
            
            var lRs,hRs,lDs,hDs;
            var lRsDom = $('.list-incomemin .js-selected');
            var hRsDom = $('.list-incomemax .js-selected');
            var lDsDom = $('.list-incomedolmin .js-selected');
            var hDsDom = $('.list-incomedolmax .js-selected');
            if(afterLH == "rs"){
              //Get all Rs & ds and store
              lRs = divWithId.hasClass("js-rangeDiv1")? fieldVal : $(lRsDom).attr('data-dbval');
              hRs = divWithId.hasClass("js-rangeDiv2")? fieldVal : $(hRsDom).attr('data-dbval');
              
              lDs = $(lDsDom).attr('data-dbval');
              hDs = $(hDsDom).attr('data-dbval');     
              
            }else if(afterLH == "ds"){
              //Get all Rs & ds and store
              
              lDs = divWithId.hasClass("js-rangeDiv1")? fieldVal : $(lDsDom).attr('data-dbval');
              hDs = divWithId.hasClass("js-rangeDiv2")? fieldVal : $(hDsDom).attr('data-dbval');
              
              lRs = $(lRsDom).attr('data-dbval');
              hRs = $(hRsDom).attr('data-dbval');
              
            }
            dppApp.setForSave(section,'p_lrs',lRs);
            dppApp.setForSave(section,'p_hrs',hRs);
            dppApp.setForSave(section,'p_lds',lDs);
            dppApp.setForSave(section,'p_hds',hDs);
            dppApp.set("p_income",lRs+","+hRs+","+lDs+","+hDs);
            
            parentDiv.find(".hide1").hide();
            
          }
          else{//Age and Height
                        
            var minRange,maxRange;
            var minRangeDom = $('.list-'+afterLH+'min .js-selected');
            var maxRangeDom = $('.list-'+afterLH+'max .js-selected');
            
            minRange = divWithId.hasClass("js-rangeDiv1")? fieldVal : $(minRangeDom).attr('data-dbval');
            maxRange = divWithId.hasClass("js-rangeDiv2")? fieldVal : $(maxRangeDom).attr('data-dbval');
            
            dppApp.setForSave(section,'p_l'+afterLH,minRange);
            dppApp.setForSave(section,'p_h'+afterLH,maxRange);
            dppApp.set("p_"+afterLH,minRange+","+maxRange);
            
            parentDiv.find(".hide1").hide();
          }
          
          parentDiv.find(".js-rangeDiv1 span,.js-rangeDiv2 span").html($(this).html());
          
          //Remove any previous selected and add new selected
          var prevSelected = $(this).parent().find(".js-selected")
          if(prevSelected.length)  
            $(prevSelected[0]).removeClass("js-selected");
          
          $(this).addClass("js-selected");
          
          //Run Logic of hiding options
          dppAppEvents.updateRangeUI(parentFieldId,fieldVal);
          
          return false;
        });
        
        //click on chosen musltiple type dropdown
        $("div [multiple]").bind("change",function(){
          section = $(this).closest('.js-editId').attr("data-sectionId");
          fieldName = $(this).attr('id').split("-")[1];
          fieldVal = $(this).val();
          dppApp.setForSave(section,fieldName,fieldVal);
          dppApp.set(fieldName,fieldVal);
        });
        
        //on change of textarea fields
        $(".js-txtarea").bind("input",function(){
          section = $(this).closest('.js-editId').attr("data-sectionId");
          fieldName = $(this).attr('id').split("-")[1];
          fieldVal = $(this).val();
          dppApp.setForSave(section,fieldName,fieldVal);
          dppApp.set(fieldName,fieldVal); 
        });

        //start:remove all option from chosen
   $('.js-resetall').click(function(){
     var getID = $(this).attr('id').split('-')[0];      
     

     $('#dpp-'+getID).val([" "]).trigger('chosen:updated');
     $('#dpp-'+getID).trigger('change');
     $('#'+getID+'-rem').css('visibility','hidden');



     if(   $('#suggest_'+getID.split('_')[1]).length != 0    )
     {
         $('#suggest_'+getID.split('_')[1]).remove();
     }
  
   });

   $('.js-torem').on("change",function(){   
       showHideRemLabel($(this));     
   });

	
});

$(document).mousedown(function (e)
{
    var container = new Array();
    container.push($('.hide1'));
    
    $.each(container, function(key, value) {
		if (!$(value).is(e.target) // if the target of the click isn't the container...
            && $(value).has(e.target).length === 0) // ... nor a descendant of the container
        {
            $(value).hide();
        }
    });
});

var config = {
'.chosen-select'           : {},
'.chosen-select-deselect'  : {allow_single_deselect:true},
'.chosen-select-no-single' : {disable_search_threshold:10},
'.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
'.chosen-select-width'     : {width:"100%"}
}
for (var selector in config) {
$(selector).chosen(config[selector]);
}

$(document).ready(function() {
handleBack();    
slider();
$(".chosen-container").on('keyup',function(e) {
$(".chosen-container .chosen-results li").addClass("chosenfloat").removeClass("chosenDropWid");
});

//showing the threshold for mutual match count
$("#mutualMatchCount").css("padding","2px");
showMutualCount(mutualMatchCount,parseInt($("#mutualMatchCount").data('value')).toLocaleString());

//$(".chosen-container.chosen-container-multi").on('mousedown',function(e) {
//  $(".chosen-container .chosen-results li").removeClass("highlighted");
//});

//Hide Defaults 
dppAppEvents.initFields();
if(typeof(openSection) != "undefined" && openSection !=""){
  $("#"+mapForEditWhat[openSection]).trigger("click");
  $("body, html").scrollTop($("#"+mapForEditWhat[openSection]).position().top+138);
}
$("#loadLate").css('visibility','visible');
if(isBrowserIE() === false)
  $(".js-txtarea").attr('placeholder','What are you looking into a partner?');
  
    // $("#unchk_dpp").on("click",function(){
    //     $("#boxDiv").removeClass("move");
    //     sendAjaxForToggleMatchalertLogic("dpp");
    // });
    // $("#chk_dpp").on("click",function(){
    //     $("#boxDiv").addClass("move");
    //     sendAjaxForToggleMatchalertLogic("history");
    // });


    $('#mutualMatchCountCheckBox').click(function(){
      if (this.checked) {
        sendAjaxForToggleMatchalertLogic("history");
      }
      else
      {
        sendAjaxForToggleMatchalertLogic("dpp");
      }
  });


    isScrolledIntoView();

  $(document).on("scroll", isScrolledIntoView);
});

function showMutualCount(id,value) {
  var mutualMatchCountThreshold = 100;

  $(id).text(value);
  $(id).attr("data-value",value);

  if (  parseInt( value.replace(",","") ) >= mutualMatchCountThreshold )
  {
    $(id).removeClass("js-selected");
    $(id).addClass("dppnbg1");
  }
  else
  {
    $(id).removeClass("dppnbg1");
    $(id).addClass("js-selected"); 
  }
}


//click on more to show full prefilled text data
$(function(){
    
       
    $('.js-moreclk').click(function(){
        var getName= $(this).attr('id').split("_")[1];
        $("#shortContent_"+getName).hide();
        $("#fullContent_"+getName).show();
        $(this).addClass("hideMore").addClass("js-saveShow");
    });
    
});

function isScrolledIntoView()
  {
    var docViewTop = $(window).scrollTop();
      var docViewBottom = docViewTop + $(window).height();
    var elemN = $("#newdppT");
    var elemN2 = $('#countScroll');
    
    var elemTop = elemN.offset().top;
      var elemBottom = elemTop + elemN.height();
    
    if((elemBottom <= docViewBottom) && (elemTop >= docViewTop))
    {
      
      if(elemN2.hasClass('posnd'))
      {
        elemN2.removeClass('posnd');
      }
      
      
    }
    else
    {
      var findleft = $('#midsec').offset().left;
      elemN2.addClass("posnd").css('left',findleft);
      
    }
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

//to auto fill data in multiselect type fields on click of edit button in a particular section
function fillValuesInChosen(sectionId){
  $('.'+sectionId+' [multiple]').each(function(){
    fieldName = $(this).attr('id').split("-")[1];
    valueToFill = dppApp.get(fieldName);
    if(valueToFill != "" && valueToFill != null){
      $(this).val(valueToFill);
      $(this).trigger("chosen:updated");
      
      //show remove label if values are present
     var getID = $(this).attr('id').split('-')[1];
     $('#'+getID+'-rem').css('visibility','visible');

    }
    else
    {
      $(this).val([]);
      $(this).trigger("chosen:updated");
    }
  });
}

//to auto fill data in range type fields on click of edit button in a particular section
function fillRangeValues(sectionId){
  $('.'+sectionId+' .js-rangeDiv1,' +'.'+sectionId+  ' .js-rangeDiv2').each(function(){ 
    fieldName = $(this).attr('id').split("-")[1];
    valueToFill = dppApp.get(fieldName)[0];
    var incomeLogic = false;
    if(fieldName == 'p_income' && $(this).attr('data-income') == "ds"){
      incomeLogic = true;
       valueToFill = dppApp.get(fieldName)[2];
    }
    //check if it is for maximum range value
    if($(this).hasClass("js-rangeDiv2"))
      valueToFill = dppApp.get(fieldName)[1]; 

    if($(this).hasClass("js-rangeDiv2") && incomeLogic){
        valueToFill = dppApp.get(fieldName)[3];
    }

    if(valueToFill != ""){
        $(this).parent().find("ul li").each(function(){
          if($(this).attr("data-dbVal") == valueToFill){
            valueToFill = $(this).html();
            $(this).addClass("js-selected");
          }
          else
            $(this).removeClass("js-selected");  
        });
    }
    else{
        //put first value if no value in casse of min range
        valueToFill = $(this).parent().find("ul li:first").html();
        //put last value if no value in casse of max range
        if($(this).hasClass("js-rangeDiv2"))
        {
          valueToFill = $(this).parent().find("ul li:last").html();
          $(this).parent().find("ul li:last").addClass("js-selected");
        }
    }
    $(this).find('span').html(valueToFill);
  }); 
}

//to fill value in textarea fields
function fillTextAreaValues(sectionId){
  if($("."+sectionId+" .js-txtarea").length === 0)
    return ;

  fieldName = $("."+sectionId+" .js-txtarea").attr('id').split("-")[1];
  $("."+sectionId+" .js-txtarea").html(dppApp.get(fieldName));
}

//to save fields in a particular section with api call
function saveSectionsFields(sectionId){
  callAfterDppChange();
  var editFieldArr = {};
  $('.'+sectionId+" .prehide").each(function(){  
  });
  editFieldArr = dppApp.getForSave(sectionId);
  if(editFieldArr && Object.keys(editFieldArr).length != 0){
    $('.overlayload').css('top',$(document).scrollTop());
    $(".js-loaderShow").removeClass("disp-none");
    if(sectionId.indexOf("editedu") != -1){
     toggleIncomeRangeDol('incomeRangeDol_save');
    }
    ifBackend = getSearchQureyParameter("fromBackend");
    $.myObj.ajax({
            url: "/api/v1/profile/dppsubmit",
            type: 'POST',
            datatype: 'json',
            cache: true,
            async: true,
            updateChatListImmediate:true,
            updateNonRosterChatGroups:["dpp"],
            data: {editFieldArr : editFieldArr,getData : "dpp",fromBackend:ifBackend},
            success: function(data) { 
              if(typeof data == "string")
                data = JSON.parse(data);
              if(data.responseStatusCode == 0){
                // reset city filter if india removed from country list
                testCountry = 'P_COUNTRY' in editFieldArr;
                testRELIGION = 'P_RELIGION' in editFieldArr;
                if((testCountry === true && dppApp.getFilterCheck('P_CITY') == 1) ){
                        setFilters('CITY_RES-filter');
                }else{
                        if(testRELIGION === true && dppApp.getFilterCheck('P_CASTE') == 1){
                                setFilters('CASTE-filter');
                        }
                }
                dppApp.fieldsToSave[sectionId] = {};
                displayUserFilledData(data,sectionId);
                incomeValueReset(sectionId,data);
                $(".js-loaderShow").addClass("disp-none");
                $('.'+sectionId+' .prehide').fadeOut(200,"linear",function(){ 
                  $('.'+sectionId+' .posthide:not(.hideMore,.msgscr)').fadeIn(200,"linear");
                });
              }
              for (var ke in data) {

                if ( data[ke] !== null )
                {
                  if ( data[ke].key == "P_MATCHCOUNT")
                   {
                      showMutualCount(mutualMatchCount,(data[ke].value).toLocaleString());
                   }  
                }
              }           
            }
    });
  }
  else{
    $('.'+sectionId+' .prehide').fadeOut(200,"linear",function(){ 
      $('.'+sectionId+' .posthide:not(.hideMore,.msgscr)').fadeIn(200,"linear");
      $('.'+sectionId+' .js-saveShow').fadeIn(200,"linear");
    }); 
    if(dppApp.get("spouse_screen") == "1" && dppApp.get("spouse")!="")
      $(".scrmsg").show();
  }
}

function displayUserFilledData(responseArr,sectionId){
  $('.'+sectionId+' [js-deValue1]').each(function(){
    if(typeof responseArr[$(this).attr("js-deValue1")] != "undefined")
      $(this).children().children().first().html(responseArr[$(this).attr("js-deValue1")]['label_val'].substr(0,58));
    
    if(typeof responseArr[$(this).attr("js-deValue1")] != "undefined" && 
       responseArr[$(this).attr("js-deValue1")]['label_val'].length > 58)
      $(this).find(".js-moreclk").show();
    else
      $(this).find(".js-moreclk").hide();  
  });
  $('.'+sectionId+' [js-deValue2]').each(function(){
    if(typeof responseArr[$(this).attr("js-deValue2")] != "undefined"){
      $(this).children().html(responseArr[$(this).attr("js-deValue2")]['label_val']);
      if(responseArr[$(this).attr("js-deValue2")]['label_val'] == "")
        $(this).children().html("-");  
    }
    if(typeof responseArr[$(this).attr("js-deValue2")] != "undefined" && responseArr[0]['label_val']!="" && responseArr[0]['screenBit'] == "1" ){
        $(".scrmsg").show();
    }
  });
  if(responseArr['ap_screen_msg'])
    $(".js-apMes").html(responseArr['ap_screen_msg']).show();
}

//for resetting income mapping values after save
function incomeValueReset(sectionId,response){
  if(sectionId == "editedu"){
    toSetArr = response[$(".js-incomefield").attr("js-deValue1")]['value'];
    dppApp.set("p_income",toSetArr);
  }
}

dppAppEvents = function(){
  
  
  //Basic Details Section
  var countryField                      = '#dpp-p_country';
  var cityField                         = '#dpp-p_city';
  var maritalField                      = '#dpp-p_mstatus';
  var hasChildrenField                  = '#dpp-p_havechild';
  //LifeStyle Section
  var religionField                     = '#dpp-p_religion';
  var casteField                        = '#dpp-p_caste';
  
  //LifeStyle Section
  var dietField                         = '#dpp-p_diet';
  var drinkField                        = '#dpp-p_drink';
  var smokeField                        = '#dpp-p_smoke';
  
  var challengedField                   = "#dpp-p_challenged";
  var natChallengedField                = '#dpp-p_nchallenged';
  
  var chosenUpdateEvent                 = "chosen:updated"
 
 
 
 /*
 * Bussiness Logic which work on diet Change
 *   1) Drink
 *   2) Smoke
 * @param {type} event
 * @param {type} param
 * @returns {undefined}
 */
function onCountry(values){
  
  dppApp.setFilterCheck('P_CITY',0);
  if(values instanceof Array !== false && values.indexOf("51") !== -1){
    showHideField(cityField,true);
  }
  else if(typeof values == "string" && values.indexOf("51") !== -1) 
  {
    showHideField(cityField,true);
  }
  else
  {
    showHideField(cityField,false,true);
    dppApp.setFilterCheck('P_CITY',1);
  }
  
}

/*
 * Business logic which works for showing 'Have Children' condtionally based on values of Marital Status Selected
 * @param {type} values Must br in an array
 * @returns {undefined}
 */
function onMarital(values){
  
  
  if((values instanceof Array !== false || (values != '' && values != null)) && 
     (values.length > 1 || values.indexOf("N") === -1)
    ){
    showHideField(hasChildrenField,true);
  }
  else
    showHideField(hasChildrenField,false,true);
  
}

/*
 * showHideField , Function for Show and Hide given field
 * @param {type} fieldSelector
 * @param {type} bShow
 * @returns {undefined}
 */
function showHideField(fieldSelector,bShow,clearField){
  
  var parentField = fieldSelector+'Parent';
  if($(fieldSelector).length === 0 && $(parentField).length === 0)
    return ;
  
  if(bShow){
    $(parentField).show();
  }
  else{
    $(parentField).hide();
  }
  
  //IF Chosen then empty  all selected value and update the list
  if(typeof(clearField) != 'undefined' && 
     typeof($(fieldSelector).attr('multiple')) != "undefined" && 
     !bShow)
   {
    //Chosen Plugin Field
    $(fieldSelector).val([]);
    $(fieldSelector).trigger(chosenUpdateEvent);
    
    //Trigger change to store updated value in dppApp
    $(fieldSelector).trigger("change");
  }
}
 
 /*
 * Bussiness Logic which work on diet Change
 *   1) Drink
 *   2) Smoke
 * @param {type} event
 * @param {type} param
 * @returns {undefined}
 */
function onDiet(event,param){
  
  if(typeof(param) != "object")
    return ;
  
  if(param && typeof(param.selected) != "undefined" ){
    var choice = param.selected;
    var values = $(this).val();//Chosen Return An array of value
    
    //If Vegetarian And Jain is not selected then add Jain Option also
    if(choice == "V" && values.indexOf("J") === -1){
      values[values.length] = "J"; 
    }
    
    //If Non-Vegetarian And Eggeterian is not selected then add Eggeterian also
    if(choice == "N" && values.indexOf("E") === -1){
      values[values.length] = "E"; 
    }
    
    //If Eggeterian And Vegetarian or Jain is not selected then add Vegetarian and Jain also
    if(choice == "E" && (values.indexOf("V") === -1 || values.indexOf("J") === -1)){
      values[values.length] = "V";
      values[values.length] = "J";
    }
    
    $(this).val(values);
    $(this).trigger(chosenUpdateEvent);
  }
}
  
/*
 * Bussiness Logic which work on following fields
 *   1) Drink
 *   2) Smoke
 * @param {type} event
 * @param {type} param
 * @returns {undefined}
 */
function onYesAndOccasionally(event,param){
  
  if(typeof(param) != "object")
    return ;
  
  if(param && typeof(param.selected) != "undefined" ){
    var choice = param.selected;
    var values = $(this).val();//Chosen Return An array of value
    
    //If Yes And Occasionally not presenr then add Occasionally
    if(choice == "Y" && values.indexOf("O") === -1){
      values[values.length] = "O"; 
    }
    
    //If No And Occasionally not present then add Occasionally
    if(choice == "O" && values.indexOf("N") === -1){
      values[values.length] = "N"; 
    }
    
    $(this).val(values);
    $(this).trigger(chosenUpdateEvent);
  }
}

function toggleNatureHandicap(arrValues){
  
  var bShowField = false;
  if(typeof(arrValues) == "undefined" || arrValues === null){
    showHideField(natChallengedField,false,true);
    return ;
  }
  
  if(arrValues instanceof Array === false && 
     typeof(arrValues) == "string"        &&
     (arrValues.indexOf('1') !== -1 || arrValues.indexOf('2') !== -1 )
   )
   {
    //Check string value with comma seprated values
    bShowField = true;
  }
  else//Value is array
  {
    bShowField = (arrValues.length > 0 && (arrValues.indexOf('1') !== -1 || arrValues.indexOf('2') !== -1 ) ) 
                 ? true 
                 : false ;
  }
  
  if(bShowField){
    showHideField(natChallengedField,true);
    previousKey = $(challengedField +'Parent [js-deValue1]').attr("js-deValue1");
    $(natChallengedField +'Parent [js-deValue1]').attr("js-deValue1",parseInt(previousKey)+1);
    $(natChallengedField +'Parent [js-deValue2]').attr("js-deValue2",parseInt(previousKey)+1)
  }else{
    showHideField(natChallengedField,false,true);
    //ToDo Need to empty the stored value in dppApp also
  }
}

/*
 * updateCastOption as per given religion value array
 * @param {type} religionValArray
 * @returns {undefined}
 */
function updateCastOption(religionValArray,type){
    dppApp.setFilterCheck('P_CASTE',0);
  if(false === religionValArray instanceof Array && typeof(religionValArray) == "string" && religionValArray.length ){
    var temp = religionValArray;
    religionValArray = [temp];
  }
  
  if(true === religionValArray instanceof Array && religionValArray.length === 0 ){
    //May be need to empty the option value
    if(type != "suggest") {
      $(casteField).html(""); 
    }
    $(casteField).append('<option class="textTru chosenDropWid"  value="0">Others</option>');
    $(casteField).trigger(chosenUpdateEvent);
    return ;
  }
  var isCasteVisible = false;
  if(type != "suggest") {
    $(casteField).html(""); 
  }
  if(religionValArray != null){
  for(var i=0;i<religionValArray.length;i++){
    
    if(dppCaste.hasOwnProperty(religionValArray[i]) === false)
      continue;
    
    isCasteVisible = true; 
    var casteArray = dppCaste[religionValArray[i]][0];
    for(var j=0;j<casteArray.length;j++){
      var key = Object.keys(casteArray[j])[0];
      var valueLabel = casteArray[j][key];
      if($("#dpp-p_caste .chosenDropWid[value='"+key+"']").length == 0 ) {
       $(casteField).append('<option class="textTru chosenDropWid" value= \"' + key+ '\">' + valueLabel + '</option>');
      }
      
    }
  }
  }else{
          dppApp.setFilterCheck('P_CASTE',1);
  }
  $(casteField).trigger(chosenUpdateEvent);
  
  if(isCasteVisible === false){
    showHideField(casteField,false,true);
  }
  else{
    showHideField(casteField,true);
  }
}

/*
 * disableRangeOption
 * @param {string} fieldName
 * @param {string} minValue
 * @returns {undefined}
 */
function disableRangeOption(fieldName,minValue){
    
    var listId = '.list-'+fieldName+'max';
    var arrOption = $(listId + ' li');
    var maxOption = null;
    var markDisabledClass = "opa50 bg-white cusorNone";
    
    var specialCheck = fieldName.indexOf('income') !== -1 ? true : false;
    
    for(var i=0;i<arrOption.length;i++){
      var value = parseInt($(arrOption[i]).attr('data-dbVal'));
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
    if(specialCheck && parseInt($(domEle).attr('data-dbVal')) <= parseInt(minValue) )
    {
      if($(domEle).attr('data-dbval') != 19   )
      {
        $(maxOption).trigger("click");
      }
    }
    else if(parseInt($(domEle).attr('data-dbVal')) < parseInt(minValue) )
    {
      $(maxOption).trigger("click");
    }
  }


/*
 * disableFieldsOption
 * @param {string} fieldId
 * @param {string} value
 * @returns {undefined}
 */
	function disableFieldsOption(fieldId,value){
    if(fieldId == "agemin"){
      disableRangeOption("age",value);
    }
    if(fieldId == "heightmin"){
      disableRangeOption("height",value);
    }
    if(fieldId == "incomemin"){
      disableRangeOption("income",value);
    }
     if(fieldId == "incomedolmin"){
      disableRangeOption("incomedol",value);
    }
  }


function initFields(){
  onCountry(dppApp.get('p_country'));
  onMarital(dppApp.get('p_mstatus'));
  toggleNatureHandicap(dppApp.get('p_challenged'));
  updateCastOption(dppApp.get('p_religion'),""); 
  
}

//Bind Events////////////////////////////////////////////
  //Basic Details Section
  $(countryField).on("change",function onCountryChangeEvent(){
    onCountry($(this).val());
  });

  $(maritalField).on("change",function onMaritalChangeEvent(){
    onMarital($(this).val());
  });

  //Binding Religion Section
  $(religionField).on("change",function onReligionChange(){
      updateCastOption($(this).val(),"");
      valueToFill = dppApp.get("p_caste");
      if(valueToFill != ""){
        $(casteField).val(valueToFill);
        $(casteField).trigger("chosen:updated");
        
      }
      $(casteField).trigger("change");
  });
  //Binding Drink and Smoke Logic
  $(dietField).on("change",onDiet);
  $(drinkField).on("change",onYesAndOccasionally);
  $(smokeField).on("change",onYesAndOccasionally);
  $(challengedField).on("change",function onChallengedChange(event,param){
    toggleNatureHandicap($(this).val());
  })
////////////////////////////////////////////////////////////////////////////////////////////
return {
  toggleNatureHandicap : toggleNatureHandicap,
  onCountryChange   : onCountry,
  onMaritalChange   : onMarital,
  updateCastOption  : updateCastOption,
  updateRangeUI     : disableFieldsOption,
  initFields        : initFields  
}
}();

function setUnsetFilter(status,filterId){
    var filterTextId = filterId.split("-")[0];
    if(status == "Y"){
                  $("#"+filterId).addClass("filterset").removeClass("filter");
                  $("#"+filterId).children().addClass("colrw").html("Strict Filter ON");
                  $(".js-"+filterId).html($("#"+filterTextId+"-hint").val()+" set as strict filter");
                }
                else{
                  $("#"+filterId).removeClass("filterset").addClass("filter");  
                  $("#"+filterId).children().removeClass("colrw").html("Strict Filter OFF");
                  $(".js-"+filterId).html("Setting "+$("#"+filterTextId+"-hint").val()+" as strict filter?");
                }
}


 /*
 * showing Income Range in Dol on click of edit button
 * @param {string} parentId
 * @returns {undefined}
 */
   function toggleIncomeRangeDol(parentId){
    var parentDivId = parentId.split("_")[0];
    var buttonClick = parentId.split("_")[1];
    if(buttonClick == "edit")
     $("#"+parentDivId).removeClass("hideMore");
    else
     $("#"+parentDivId).addClass("hideMore"); 
   }


//Handling History Back
function handleBack() {
  if (typeof (historyStoreObj) === "undefined") {
    return;
  }
  
  if (typeof (disableBack) === "undefined" ) {
    return;
  }
  
  if (disableBack === "0"  || disableBack.length == "0") {
    return;
  }
  
  // Declare Varibales
  var overlay       = '.js-regOverlay';
  var overlayMsg    = '.js-regOverlayMsg';
  var overlayClose  = '.js-regOverlayClose';
  var displayNone   = 'disp-none';
  var msgTimeout    = 5000;
  var timeoutId     = null;
  //Function to show hide overlay 
  function showHideOverlay(bShow)
  {
    if(0 === $(overlay).length)
      return false;
    
    if(true === bShow){
      $(overlay).removeClass(displayNone);
      $(overlayMsg).removeClass(displayNone);
    }
    else if(false === bShow)
    {
      $(overlay).addClass(displayNone);
      $(overlayMsg).addClass(displayNone);
    }
  }
  
  //Binding Close Button on overlay
  $(overlayClose).on('click',function(){
    if(null !== timeoutId){
      clearTimeout(timeoutId);
      timeoutId = null;
    }
    showHideOverlay(false);
  });
  
  //Show Back Btn Msg
  var showBrowserBackMsg = function () {
    showHideOverlay(true);
    historyStoreObj.push(onBrowserBack, "#dpp");
    timeoutId = setTimeout(function(){
      showHideOverlay(false);
      timeoutId = null;
    },msgTimeout);
  }
  
  //Function callback when browser back will called
  var onBrowserBack = function () {
    if (location.href.indexOf("register") != -1) {
      showBrowserBackMsg();
      return true;
    }
    return false;
  }

  historyStoreObj.push(onBrowserBack, "#dpp");
  
};

var _dppType = ["religion","city", "caste", "mtongue", "education"];
var _parentCatogary = [{
  "parent": "basic",
  "sub": ["city","age"]
}, {
  "parent": "religion",
  "sub": ["religion","caste", "mtongue"]
}, {
  "parent": "education",
  "sub": ["education","income"]
}],queryInput = [];

    //change suggestion on adding or deleting chosen option
    function changeSuggestion(elem, state) {
      var type = "",typeElem, typeDataArray = [];
      elem= elem.split("<em>").join("").split("</em>").join("");
      setTimeout(function() {
        if (state == "add") {
          $(".search-choice").each(function(index, element) {
            if ($(this).find("span").html() == elem) {
              typeElem = $(this).closest(".pt20");
              type = $(this).closest(".pt20").attr("id").split("_")[1].split("Parent")[0];
              if (_dppType.indexOf(type) != -1) {
                typeDataArray = $("#dpp-p_" + type).val();
              }
            }
          });
        } else if (state == "remove") {
          var type = elem.split("_")[1].split("Parent")[0];
          if (_dppType.indexOf(type) != -1 && $("#dpp-p_" + type).val() != undefined ) {
            typeDataArray = $("#dpp-p_" + type).val();
          }
        }
        
        if(type == "religion" && $("#dpp-p_caste").val()==null) {
          $("#suggest_caste .suggestBoxList").html('<div class="f14 nc-color2 mlneg7">No suggestions found</div>');
        }
        if (typeDataArray.length != 0) {
          $("#dpp-p_"+type+"Parent").parent().find(".js-saveBtn").attr('disabled','disabled');
          $("#loader_" + type).removeClass("disp-none");
          $("#suggest_" + type + " .suggestBoxList").html("");
          var obj = [{
            "type": type.toUpperCase(),
            "data": typeDataArray
          }];
          $.each(obj, function(index, elem) {
            queryInput.push(elem);
          });
          getApiResponse();
        }
      }, 30);
}

        function getApiResponse() {
          var finalObj = [],len,
          temp, dataPresent, response = [],url="",str="";
      //time lag for multiple select
      setTimeout(function() {
        if (queryInput.length != 0) {
          len = queryInput.length;
          for (var i = 0; i < len; i++) {
            temp = queryInput.pop();
            dataPresent = false;
            $.each(finalObj, function(index2, elem2) {
              if (elem2.type == temp.type) {
                dataPresent = true;
              }
            });
            if (dataPresent == false) {
              finalObj.push(temp);
            }
          }
          str = JSON.stringify(finalObj).split('"').join('%22');
          url = "/api/v1/profile/dppSuggestions?Param="+str;
          $.ajax({
            type: "POST",
            url: url,
            cache: false,
            timeout: 5000,
            success: function(result) {
              $("#dpp-p_"+finalObj[0].type.toLowerCase()+"Parent").parent().find(".js-saveBtn").removeAttr('disabled');
              if(result && result != "" && JSON.parse(result)[0] && JSON.parse(result).responseMessage == "Successful") {
                response = JSON.parse(JSON.parse(result)[0]);
                appendSuggestionList(response);
              } else {
                showCustomCommonError("Something went wrong. Please try again after some time.",1500);
              }             
            },
            error:function(result){
              $("#dpp-p_"+finalObj[0].type.toLowerCase()+"Parent").parent().find(".js-saveBtn").removeAttr('disabled');
              showCustomCommonError("Something went wrong. Please try again after some time.",1500);
            }
          });
        }
      }, 500);

}
    // append API response in suggestion
    function appendSuggestionList(response) {
      
      var type = "",dataPresent;
      //appending age and income suggestion
      $.each(response, function(index, elem) {
       if(elem.type == "INCOME") {
        if(elem.data == undefined) {
          $("#suggest_new_INCOME").remove();
        }
         if ($("#suggest_new_" + elem.type).length == 0 && elem.data) {
           $('<div class="edwid2 fl ml193 pt10 suggestMain" id="suggest_new_' + elem.type + '"><div class="fontlig f12 wid134 disp_ib color11 mr10">Suggested (click to add)</div><div class="disp_ib suggestParentBox wid345 disp_none vtop"><div class="suggestBoxList"></div></div></div>').insertAfter("#incomeRangeDol");
         } 
         if(elem.data) {
          if(elem.data.LRS && elem.data.HRS){
           $("#suggestRs").remove();
           $("#suggest_new_" + elem.type + " .suggestBoxList").append('<div id="suggestRs" class="fontlig f14 disp_ib color11 cursp suggestBoxNew"><span id="suggestLRS">'+elem.data.LRS+'</span>&nbsp;-&nbsp;<span id="suggestHRS">'+elem.data.HRS+'</span></div>');
          }
          if(elem.data.LDS && elem.data.HDS) {
           $("#suggestDol").remove();
           $("#suggest_new_" + elem.type + " .suggestBoxList").append('<div id="suggestDol" class="fontlig f14 disp_ib color11 cursp suggestBoxNew"><span id="suggestLDS">'+elem.data.LDS+'</span>&nbsp;-&nbsp;<span id="suggestHDS">'+elem.data.HDS+'</span></div>');   
          } 
         }
         
       }
       if(elem.type == "AGE") {
         if(elem.data == undefined) {
          $("#suggest_new_AGE").remove();
         }
         if ($("#suggest_new_" + elem.type).length == 0 && elem.data) {
           $('<div class="edwid2 fl ml193 pt10 suggestMain" id="suggest_new_' + elem.type + '"><div class="fontlig f12 wid134 disp_ib color11 mr10">Suggested (click to add)</div><div class="disp_ib suggestParentBox wid345 disp_none vtop"><div class="suggestBoxList"></div></div></div>').insertAfter("#ageRange");
         } 
         if(elem.data) {
          if(elem.data.LAGE && elem.data.HAGE){
           $("#suggestAge").remove();
           $("#suggest_new_" + elem.type + " .suggestBoxList").append('<div id="suggestAge" class="fontlig f14 disp_ib color11 cursp suggestBoxNew"><span id="suggestLAGE">'+elem.data.LAGE+'</span>&nbsp;years&nbsp;-&nbsp;<span id="suggestHAGE">'+elem.data.HAGE+'</span>&nbsp;years</div>');
          }
         }
         
       }
     });
    //binding click on age and income suggestion
     $(".suggestBoxNew").each(function(index, element) {
         $(element).off("click").on("click", function() {
            $("#ageRange").attr("suggest-select",1);
            var range = $(this).attr("id").split("suggest")[1];
            if($("#"+range+"Range")) {
                $(".list-agemin li").each(function(){
                  if($("#suggestLAGE").html() == $(this).html())
                     $(this).click();
               });
               $(".list-agemax li").each(function(){
                   if($("#suggestHAGE").html() == $(this).html())
                     $(this).click();
               });
               $("#suggest_new_AGE").remove();  
             }
             if($("#incomeRange"+range)) {
               if(range == "Rs") {
                 $("#incomeRangeRs").attr("suggest-select",1);
                 $(".list-incomemax li").each(function(){
                   if($("#suggestHRS").html() == $(this).html())
                     $(this).click();
                 });
                 $(".list-incomemin li").each(function(){
                   if($("#suggestLRS").html() == $(this).html())
                     $(this).click();
                 });
                 $("#suggestRs").remove();
                 if($("#suggestDol").length == 0) {
                   $("#suggest_new_INCOME").remove();  
                 }
               } else if(range == "Dol") {
                 $("#incomeRangeDol").attr("suggest-select",1);
                 $(".list-incomedolmin li").each(function(){
                   if($("#suggestLDS").html() == $(this).html())
                     $(this).click();
                 });  
                 $(".list-incomedolmax li").each(function(){
                   if($("#suggestHDS").html() == $(this).html())
                     $(this).click();
                 });  
                 $("#suggestDol").remove();
                 if($("#suggestRs").length == 0) {
                   $("#suggest_new_INCOME").remove();  
                 }
               }
             }
         });
     });


      $.each(response, function(index, elem) {
        type = elem.type.toLowerCase();
        if(elem.data) {
            if (Object.keys(elem.data).length != 0) {
            if ($("#suggest_" + type).length == 0) {
              $('<div class="edwid2 fl ml193 pt10 suggestMain" id="suggest_' + type + '"><div class="fontlig f12 wid134 disp_ib color11 mr10 vtop">Suggested (click to add)</div><div class="disp_ib suggestParentBox wid345 disp_none vtop"><div id="loader_' + type + '"><img src="IMG_URL/images/jspc/commonimg/dppLoader.gif"></div><div class="suggestBoxList"></div></div></div>').insertAfter("#dpp-p_" + type + "Parent #multiselect");
            }
            $("#suggest_" + type + " .suggestBoxList").html("");

            $.each(Object.keys(elem.data), function(index2, elem2) {
              dataPresent = false;
              if($("#dpp-p_" + type).val() != null) {
                $.each($("#dpp-p_" + type).val(), function(index3,elem3){
                  if(elem3 == elem2) {
                    dataPresent = true;
                  }
                });
              }
              if (dataPresent == false && $("#dpp-p_"+type+" option[value='"+elem2+"']").length != 0) {
                $("#suggest_" + type + " .suggestBoxList").append('<div class="fontlig f14 disp_ib color11 cursp suggestBox" index-val="' + elem2 + '">' + $("#dpp-p_"+type+" option[value='"+elem2+"']").html() + '</div>');
              }
            });
            $("#suggest_" + type + " .suggestBox").each(function(){
              if($(this).attr("index-val").indexOf(",") != -1) {
                var data = $(this).html(),indexVal = $(this).attr("index-val");
                $(this).remove();
                $("#suggest_" + type + " .suggestBoxList").prepend('<div class="fontlig f14 disp_ib color11 cursp suggestBox" index-val="'+indexVal+'">'+data+'</div>');
              }
            });
            //binding click on each suggestion
            if($("#suggest_" + type + " .suggestBoxList div").length != 0) {
              $(".suggestBox").each(function(index, element) {
                $(element).off("click").on("click", function() {
                  var newVal = $(this).attr("index-val"),
                  parentText = $(this).closest(".suggestMain").attr("id").split("suggest_")[1],
                  currentValArr = $("#dpp-p_" + parentText).val(),
                  htmlStr = $(this).html(),parentSection = $("#dpp-p_"+parentText).closest(".js-editId").attr("data-sectionid");
                  if(currentValArr != undefined) {
                    currentValArr.push(newVal);
                  } else {
                    currentValArr = newVal;
                  }
                  $("#dpp-p_" + parentText).val(currentValArr).trigger("chosen:updated");
                  $(this).remove();
                  changeSuggestion(htmlStr, "add"); 
                  dppApp.set("p_"+parentText,$("#dpp-p_" + parentText).val());
                  dppApp.setForSave(parentSection,"p_"+parentText,$("#dpp-p_" + parentText).val());
                  if(parentText == "religion") {
                    dppAppEvents.updateCastOption(currentValArr,"suggest"); 
                  }

                  if($('#dpp-p_'+parentText).val()!=null)
                 {
                   $('#p_'+parentText+'-rem').css('visibility','visible');
                 }
                });
              });
            
            } else if($("#suggest_" + type + " .suggestBoxList div").length == 0) {
              $("#suggest_" + type+ " .suggestBoxList").html('<div class="f14 nc-color2 mlneg7">No suggestions found</div>');
            }
          } else {
            $("#suggest_" + type+ " .suggestBoxList").html('<div class="f14 nc-color2 mlneg7">No suggestions found</div>');
          } 
        } else {
            $("#suggest_" + type+ " .suggestBoxList").html('<div class="f14 nc-color2 mlneg7">No suggestions found</div>');
          } 
        
        $("#loader_" + type).addClass("disp-none");
      });

}
    //when a user click on edit button of a parent catogary
    function changeCatogarySuggestion(type) {
      setTimeout(function() {
        var obj, typeDataArray;
        $.each(_parentCatogary, function(index, elem) {
          if (elem.parent == type) {
            obj = [];
            //creating object for age and income on edit
            if(type == "basic") {
             obj.push({
               "type":"AGE",
               "data":[$("#dpp1-p_age span").html(),$("#dpp2-p_age span").html()]
             });
           } else if(type == "education") {
             obj.push({
               "type":"INCOME",
               "data":[$("#incomeRangeRs #dpp1-p_income span").html(),$("#incomeRangeRs #dpp2-p_income span").html(),$("#incomeRangeDol #dpp1-p_income span").html(),$("#incomeRangeDol #dpp2-p_income span").html()]
             });
           }
            $.each(elem.sub, function(index2, elem2) {
              typeDataArray = $("#dpp-p_" + elem2).val();
              if(typeDataArray != null) {
                obj.push({
                  "type": elem2.toUpperCase(),
                  "data": typeDataArray
                });  
              }
            });
            if(obj){
              $.each(obj, function(index, elem) {
                queryInput.push(elem);
              });
              getApiResponse();  
            }
          }
        });
      }, 30);
    }
    //extra function to create object on age and income change
    function changeNonChosenSuggestion(type) { 
     var obj;
     setTimeout(function() {
       if(type ==  "age") {
       obj = {
                 "type":"AGE",
                 "data":[$("#dpp1-p_age span").html(),$("#dpp2-p_age span").html()]
             }; 
       } else if(type == "income") {
         obj = {
                 "type":"INCOME",
                 "data":[$("#incomeRangeRs #dpp1-p_income span").html(),$("#incomeRangeRs #dpp2-p_income span").html(),$("#incomeRangeDol #dpp1-p_income span").html(),$("#incomeRangeDol #dpp2-p_income span").html()]
               };  
       } else if(type == "incomeDol") {
         obj = {
                 "type":"INCOME",
                 "data":[$("#incomeRangeRs #dpp1-p_income span").html(),$("#incomeRangeRs #dpp2-p_income span").html(),$("#incomeRangeDol #dpp1-p_income span").html(),$("#incomeRangeDol #dpp2-p_income span").html()]
               }; 
       }
       if(obj){
         queryInput.push(obj);
         getApiResponse();  
       }
     }, 30);
   }

