/**
* display slider left and right ends' values 
* @param : fromValue,toValue,leftValueField,rightValueField
*/
$.fn.displaySliderValues = function(fromValue,toValue,leftValueField,rightValueField) {
	if(leftValueField !== undefined && rightValueField !== undefined)
	{
		$(leftValueField).val(fromValue);
		$(rightValueField).val(toValue); 
	}
};

/**
* Creates range slider 
* @param : sliderID,clusterID,valueArray(label values),leftValueFieldID,rightValueFieldID,initialLeftValue,initialRightValue,minInterval)
* sliderId: extra param just for console....LATER
*/
$.fn.createRangeSlider = function(sliderID,clusterID,rangeLabelsArr,rangeValuesArr,leftValueField,rightValueField,initialLeftValue,initialRightValue,minInterval){ 
	if (typeof minInterval === "undefined" || minInterval === null) { 
	minInterval = 0; 
    }
	$(this).ionRangeSlider({
	type: "double",                                //no of slider handles
	grid: false,                                   //partition lines in slider not required
	min: 0,                                        //miminum value of slider
	max: rangeLabelsArr.length - 1,                //maximum value of slider
	from: initialLeftValue,                        //initial position of left slider
	to: initialRightValue,                         //initial position of right slider
	min_interval: minInterval,                     //miminum gap limit between slider handles
	hide_min_max: false,                            //hide min max labels
	force_edges:true,                              //force labels to remain inside container
	values: rangeLabelsArr,                         //values to be populated as labels
        clusterID:clusterID,

    /**
    * function executed on start before updating slider values to set min-max input fields
    * @param: slider data
    */
    onStart : function (data) {
		$(this).displaySliderValues(data.from_value,data.to_value,leftValueField,rightValueField);   
    } ,
    
    /**
    * function executed on update of slider values to set min-max input fields
    * @param: slider data
    */
	onChange : function (data) {
		$(this).displaySliderValues(data.from_value,data.to_value,leftValueField,rightValueField);
		clearTimeout(clearTimedOutVar);	
	},

    /**
    * function executed at the end after update of slider values to set min-max input fields
    * @param: slider data
    */
	onFinish : function (data) {
	    if(leftValueField !== undefined && rightValueField !== undefined)
		{
			$(this).displaySliderValues(data.from_value,data.to_value,leftValueField,rightValueField); 
			//to get mapping 
			var initialLeftLabel=$(leftValueField).val(),initialRightLabel=$(rightValueField).val();
			if(typeof rangeLabelsArr[0]==="number")
			{
				initialLeftLabel=parseInt(initialLeftLabel);
				initialRightLabel=parseInt(initialRightLabel);
			}
            var array = new Array();
            var temp = rangeValuesArr[$.inArray(initialLeftLabel,rangeLabelsArr)]+"$"+rangeValuesArr[$.inArray(initialRightLabel,rangeLabelsArr)];
            array.push(temp);
            parameterAndProcessRequest(array,clusterID,this);

			/*
			console.log(clusterID);
			console.log(temp);
			console.log(sliderID+" left end at: "+rangeValuesArr[$.inArray(initialLeftLabel,rangeLabelsArr)]);
			console.log(sliderID+" right end at: "+rangeValuesArr[$.inArray(initialRightLabel,rangeLabelsArr)]);
			*/
		}  	
	}
    });  
}; 

