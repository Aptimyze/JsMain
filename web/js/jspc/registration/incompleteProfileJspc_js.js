function prefillValues(){
   $('.js-tBox').each(function () {
     var type = $(this).attr("data-type");
     var Id = $(this).attr('id');
     if (type == "radio")
       prefillRadioFields(Id);
     if (type == "gridDropdown")
       prefillGridFields(Id);
     if (type == "text" || type == "autoSuggest")
       prefillTextFields(Id);
   });
   
   $("#clickAtLast").click();
   $('#clickAtLast').trigger('click');
   
   setTimeout(function(){
   $("#clickAtLast").click();
   $('#clickAtLast').trigger('click');
   },500);
   
   setTimeout(function(){
   $("#clickAtLast").click();
   $('#clickAtLast').trigger('click');
   $("#clickAtLast").focus();
   $(".formreg").css('visibility', 'visible');
   },500);
}
function prefillRadioFields(eleId){
    var idName = eleId.split("_")[0];
    var x = prefilledData[idName];
    if(x != 0 && x && x!='0'){
      setTimeout(function(){
      $("#"+eleId).mousedown();
      $("#"+eleId+" ul [data-dbval="+x+"]").mousedown();
      $("#"+eleId).focusout();
      }, 100);
    }
}
function prefillGridFields(eleId){
    var idName = eleId.split("_")[0];
    var x = prefilledData[idName];
    var click = "div";
//    if(idName=="city" && prefilledData["country"] && prefilledData["country"]!='51'){
//        if(!x){
//          x = prefilledData["country"];
//          click = "nfi";
//        }
//    }
    if(idName == 'cityReg' || (x != 0 && x && x!='0'))
    {
//            if(click == "nfi")
//              $("#NfiLink").mousedown();
//            else
              $("#"+idName+"-inputBox_set").mousedown().focus();
            if(idName == "mstatus" || idName == "height" || idName == "income"){
                setTimeout(function(){
                    $("#"+idName+"-multipleUls [data-dbval="+x+"]").children().mousedown();
                    if(isBrowserIE() === true)
                      $("#"+idName+"_error").html("");  
                },10);
            }
            else
                $("#"+idName+"-multipleUls [data-dbval="+x+"]").children().mousedown();
            if(idName == "religion"){
                setTimeout(function(){
                    prefillGridFields("caste_value");
                    prefillTextFields("subcaste_value");
                    prefillGridFields("casteMuslim_value");
                },0);
            }
            if((idName == "countryReg" && x == '128') || idName == "stateReg"){
                setTimeout(function(){
                    prefillGridFields("cityReg");
                },0);
            }
            if(idName == "countryReg" && x == '51'){
                setTimeout(function(){
                    prefillGridFields("stateReg");
                },0);
            }  
    }
    
}
function prefillTextFields(eleId){
    var idName = eleId.split("_")[0];
    var x = prefilledData[idName];
    if(x != 0 && x && x!='0'){
        setTimeout(function(){
        $("#"+eleId).mousedown();
        if(idName=="aboutme"){
            $("#"+eleId).html(x).click();
            inputData["yourinfo"] = x;
        }
        else if(idName=="pin")
            $("#"+idName+"_value").val(x).change();
        else if(idName == "subcaste"){
            $("#"+idName+"-inputBox_set").val(x).click();
            inputData["subcaste"] = x;
        }
        else
            $("#"+idName+"-inputBox_set").val(x).click();
        }, 100);
    }
}


