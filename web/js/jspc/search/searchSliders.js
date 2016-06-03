/**
* Invokes range sliders 
* @param : none
*/
function invokeSliderCluster(){
  
  var sliderIDArr=["height","age","income","income","income_dol"],sliderID="",minInterval=0;
  $.each(JSON.parse(searchdata), function(id, obj) { 
    
    if($.inArray(id, sliderIDArr) > -1) 
    {     
        var incomeLabelText="",rangeLabelsArr = [],rangeValuesArr=[],minInterval,initialLeftValue,initialRightValue,initialLeftLabel,initialRightLabel,clusterID="";
        sliderID=id.toUpperCase();
        if(id=="income")
        {
          minInterval=1;
          sliderID="INR";
        } 
        if(id=="income_dol") 
        {
          minInterval=1;
          sliderID="DOLLAR";
        }  
        //initial values of slider ends taken from hidden input fields in searchCluster.tpl
        initialLeftValue=$("#"+sliderID+"Hiddenminfield") .val();
        initialRightValue= $("#"+sliderID+"Hiddenmaxfield").val();
        clusterID=$("#"+sliderID+"HiddenID").val();        
        $.each(obj, function(key, value) {
          if(id=="income")
            incomeLabelText=(value.LABEL).replace(/^Rs./, '₹ ');
          else
            incomeLabelText=value.LABEL;     
          rangeLabelsArr.push(incomeLabelText);
          rangeValuesArr.push((value.VALUE).toString());
      }); 
      initialLeftLabel=$.inArray(initialLeftValue,rangeValuesArr);
      initialRightLabel=$.inArray(initialRightValue,rangeValuesArr);
      //console.log(clusterID);
      //call to createRangeSlider function to create slider
      $("#"+sliderID+"slider").createRangeSlider(sliderID,clusterID,rangeLabelsArr,rangeValuesArr,"#"+sliderID+"minfield","#"+sliderID+"maxfield",initialLeftLabel,initialRightLabel,minInterval);      
    } 
  }); 
}

/**
* Invokes search Summary
*/
function invokeSearchSummary(summarySummary){
    if($("#searchSummaryText").length>0)
	    $("#searchSummaryText").html(summarySummary);
}
