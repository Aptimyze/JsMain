/**
* Common Configs
*/
var maxUntickedClusterCounter = 5;
var maxTickedClusterCounter = 6; 

/**
* This section handle clusters to be displayed.
**/
function loadClusters(val,params){
    /**
    * allClustersHtml : store all clusters unit to be displayed.
    */        
    var allClustersHtml='';
	$("#ClusterTupleStructure").empty();
    if(typeof params!="undefined" && params["action"] == "noClusterSection")
    {
        var noClustersHtml='';
        var mapObj = noClusterSectionResultMapping(params),noClusterBasicStructure=$("#noClusterBasicStructure").html();
        $("#noClusterSection").html("");
        noClustersHtml+ = $.ReplaceJsVars(noClusterBasicStructure,mapObj);
        $("#noClusterSection").append(noClustersHtml);
        allClustersHtml+ = $("#noClusterSection").html();
    }
    else
    {
    	/**
    	* clusterStructure variables loads default cluster structure.
    	*/
    	var clusterStructure = $("#indClusterStructure").html(),sliderStructure=$("#sliderClusterStructure").html();

    	if(val!==null)
    	{
    		$.each(val, function( key1, val1 ) {
    			if(key1=='result_arr'){
    				$.each(val1, function( key2, val2) {
    					if(val2.isSlider=='true')
    					{                  
    						var mapObj = clusterSliderResultMapping(val2);
    						allClustersHtml+= $.ReplaceJsVars(sliderStructure,mapObj);
    					}
    					else
    					{
    						var mapObj = clustersResultMaping(val2);
    						allClustersHtml+= $.ReplaceJsVars(clusterStructure,mapObj);
    						}
    				});
    			}
    		});
    	}
    }
	
	if(allClustersHtml){
    	$("#ClusterTupleStructure").append(allClustersHtml);
    	allClustersHtml = '';
	}
}

/***
* This function will get all the mapping variables related to clusters.
* @param val : contains value of cluster array.
*/
function clustersResultMaping(val){
	/**
 	* html of single cluster initially set as ''.
 	*/
	var singleClusteHtml='';

	/**
 	* untickedClusterCounter : count of not clicked clusters
 	* maxUntickedClusterCounter : maximum unticked counter that can be shown
 	*/
	var untickedClusterCounter = 1 ;

	/**
 	* tickedClusterCounter : count of clicked clusters
 	* maxTickedClusterCounter : maximum clicked counter that can be shown
 	*/
	var tickedClusterCounter = 1 ;


	/**
	* count of extra clusters which will appear in more.
	*/
	var moreClusterCounter=0;
	var alwaysShowMore = 0;
	if(val.id==10 || val.id==13) //LATER
		alwaysShowMore = 1;

	$.each(val.arr2, function( key1, val1) {
		var ifclusterchoosen = (val1.isSelected == 'true') ? 'checked' : 'false';
		var tickClass = (val1.isSelected == 'true') ? 'selected' : '';
		/* maximum limit of clicked or unclicked counter is checked*/
		if(1)		
		{
			var showHide = '';
	       	 	if( (val1.isSelected=='false' && untickedClusterCounter <= maxUntickedClusterCounter) 
        	    	|| (val1.isSelected=='true' && tickedClusterCounter <= maxTickedClusterCounter) )
            			;
            		else
            		{
		                showHide = 'disp-none';
                		moreClusterCounter++;
		        }
			
			/*
			if(val.id==19 && val1.id=='1,2,3,4')
				val1.id='';
			*/
			var mapObj = {
				'{clusterTopLevelId}' : val.id,		
				'{tickClass}' : tickClass,
				'{clusterArrName}':val1.id,
				'{clusterOptName}':val1.label,
				'{clusterOptCount}':val1.count,
				'{ifclusterchoosen}':ifclusterchoosen,
				'{js-clusterOptionsShowHide}':showHide,
				'lastOneHere':'lastOneHere'
			};

			var clusterOptionsStructure = $("#clusterOptionsStructure").html();
			singleClusteHtml+= $.ReplaceJsVars(clusterOptionsStructure,mapObj);          

			/* clicked/unclicked counter is incremented*/
  			//console.log(untickedClusterCounter+"---"+tickedClusterCounter+"---"+ifclusterchoosen+"--->>"+val1.label+"::"+showHide);

            		if(val1.count!="") /*handling all cases*/
				ifclusterchoosen == 'false' ? untickedClusterCounter++ : tickedClusterCounter++;		
		}
		else
		{
			moreClusterCounter++;
		}
	});

	/* 
 	* Stores count of more like "+ 32 More". Show blank when more need not to be displayed.
 	*/	
 	if(alwaysShowMore==1)
 		moreClusterCounter = "More";
 	else
		moreClusterCounter = (moreClusterCounter == "0") ? "" : '+ '+moreClusterCounter+' More';

	/**
 	* Mapping of cluster structure variables to values to form individual cluster.
	*/
	var mapping={
			'{clusterName}':removeNull(val.label),
			'{clustersOptions}': singleClusteHtml,
			'{clusterTopLevelId}': val.id,
			'{clustersMoreCount}': moreClusterCounter,
			'{lastOneHere}':'xyz'
	};
	return mapping;
}


/***
* This function will get all the mapping variables related to cluster sliders.
* @param val : contains value of cluster array.
* @return : mapping
*/
function clusterSliderResultMapping(val) {
	var singleClusteHtml='', sliderTypeName="", sliderTypeNameId="", clusterOptionsStructure = $("#sliderBarStructure").html();
	var clusterID="";
	$.each(val.arr2, function( key1, val1 ) { 
		var sliderMinValue=val1.min,sliderMaxValue=val1.max,clusterID;
		if(val.id!=="11")
		    clusterID=val.id;

		if(val1.id==="Rupee"){
		    clusterID=val.id+"&"+val1.id.toLowerCase()+"=1";
		    sliderTypeName="INR";
		    sliderTypeNameId = sliderTypeName;
		}
		else if(val1.id==="Dollar"){
		    clusterID=val.id+"&"+val1.id.toLowerCase()+"=1";
		    sliderTypeName="DOLLAR";
		    sliderTypeNameId = sliderTypeName;
		}
		else{
		    sliderTypeNameId=(removeNull(val.label)).toUpperCase();
		}
		
		/** ---------mapping variables for slider--------
		*sliderTypeName: slider name("Height"/"Income"/"Age")
		*sliderTypeNameId: slider ID("HEIGHT"/"INR"/"DOLLAR"/"AGE")
		*sliderMinValue: Initial value of left end of slider
		*sliderMaxValue: Initial value of right end of slider
		**/
		var mapObj = {
			'{sliderTypeName}':sliderTypeName,
			'{sliderTypeNameId}':sliderTypeNameId,
			'{sliderMinValue}':sliderMinValue,
			'{sliderMaxValue}':sliderMaxValue,
			'{clusterID}':clusterID,
                        '{onlyClusterID}':clusterID.replace('&','').replace('=',''),
		};
       
		singleClusteHtml+= $.ReplaceJsVars(clusterOptionsStructure,mapObj);  
	});
	var mapping={
		'{clusterName}':removeNull(val.label),
		'{sliderSubTypesStructure}':singleClusteHtml  //sliderSubTypesStructure: structure for slider bar                
	};
	return mapping;
}

/**
* This function will auto click checkbox before cluster label (like Arts/Science) or count like (11685)
* This is handled by adding class js-clusterCheckboxSelector to it 
*/
$('body').on('click', '.js-clusterCheckboxSelector', function()
{
        $(this).prev().find('input').click();
        return false;
});


/**
* This function will 
* This is handled by adding class js-clusterCheckboxSelector to it 
*/
$('body').on('click', '.moreCluster', function()
{
        /**
        * Condition allows only more div having more text to open cluster layer
        */
        if($(this).text().replace("  ","")=="")
            return;
        /**
        * This function is set positioning for opened layer for filtering on click of more icon
        */
        var ScreenHgt = $(window).height();
        var LayerHgt = ScreenHgt - 160;
        $('.js-LayerCont').css('height', LayerHgt);
        
        /**
        * TO get id of current more link
        */
        var idOfElement = $(this).attr("id");
        idOfElement = parseInt(idOfElement.replace("moreid",""));
        //idOfElement=parseInt($($(this).parent("div").children("ul").children("li").children("input")[0]).attr("name").replace("appCluster",""));
        
        /**
        * Display filter layer with blank content
        */	
        $('.js-overlay').fadeIn();
        $('#filterlayer').show();
        $("#filterContent").html("");
        
        
        /**
        * Get and setting of Titile and other text for Filter Layer
        */
        var titleOfFilter = $($(this).parent("div").parent("div")).find(".clusterName").text();
        $("#filterlayer .title").text(titleOfFilter).attr("clusterId",idOfElement);
        $("#filterlayer #filterCount").text("0 selected");
        $("#filterContent").html("");
        
        
        /**
        * To Prepare Content for Education and Occupation
        * This is required because Occ and Edu have different patter than others in filter
        */
        if(titleOfFilter=="Occupation" || titleOfFilter=="Education"){
            $('#filterlayer').hide();
            //$('.overlay').hide();
            var resultOfInputElements = $('input[name="appCluster'+idOfElement+'[]"]');
            var catContent = {};
            
            
            var url= "/api/v1/search/perform";
            var requestParams = "clusterMore=1";
            if(titleOfFilter=="Education")
                var additionalUrl = "&moreLinkCluster=EDU_LEVEL_NEW&originalCluster=EDUCATION_GROUPING";
            if(titleOfFilter=="Occupation")
                var additionalUrl = "&moreLinkCluster=OCCUPATION&originalCluster=OCCUPATION_GROUPING";

	    var infoArr = {};
	    infoArr["titleOfFilter"] = titleOfFilter;
	    infoArr["action"] = "moreCluster";
	    infoArr["additionalUrl"] = additionalUrl;
            sendProcessSearchRequest(requestParams,infoArr);
        }   
        /**
        * To Prepare Content for FILTER Layer other than Education and Occupation category
        * Content Fetched from search page and processed no AJAX required
        */
        else{
            
            /**
            * Content for filter layer from SRP page
            */
            var resultOfInputElements = $('input[name="appCluster'+idOfElement+'[]"]');
            var resultOfInputElementsFormatted = {};
            var isAllChecked = "true";
            //ALL IS CHECKED or NOT
            if($(resultOfInputElements[0]).attr("checked")=="checked")
                var isCheckAll = 1;
            
           /**
            * Preparing data by keeping formatted content in an object
            */
            $.each(resultOfInputElements, function( index, value ) {
                    // Label for Option
                    var valueLabel = $(value).parents("li").children("a").text();
                    
                    // Count of search result for the Option
                    var countLabel = $(value).parents("li").children("span").text();
                    
                    // id Value for the Option
                    var codedValueLabel = $(value).parents("li").children("span").children("input").val();
                    
                    /**
                    * Mark selected oor not selected for options
                    */
                    
                    if($(value).attr("checked")=="checked")
                        var isChecked = true;
                    else{
                        var isChecked = false;
                        if(valueLabel!="All"){
                            isAllChecked="false";
                        }
                    }

                    
                    /**
                    * Preparing Object in format like returned from AJAX
                    */
                    if(valueLabel!="All"){
                        resultOfInputElementsFormatted[valueLabel]={"count":countLabel,"isSelected":isChecked,"label":valueLabel,"id":codedValueLabel};
                    }
                    
             });
          /**
           * Object in Final format for display, generalised from both conditions
           */
            var catContent = {"cat":{"valueLabel":titleOfFilter,"values":resultOfInputElementsFormatted,"details":{"id":"","isSelected":isAllChecked}}};
            displayClusterData(catContent,isCheckAll);
        }
        
});

/**
* This function is used call all display functions once data is ready for display.
*/
function displayClusterData(catContent,isCheckAll){
    var filterContents = moreFilterContentDisplay(catContent,isCheckAll);
    $("#filterContent").html(filterContents);
    var current = $(window).scrollTop();
    $(window).off('scroll');
    $(window).scroll(function() {
        $(window).scrollTop(current);
    });
    // Update count on top
    var countOfSelected = $(".filtersel").length;
    $("#filterCount").text(countOfSelected+" selected");
    $(".mCustomScrollbar").mCustomScrollbar({
         theme:"minimal"
    });
}


/**
* This function is used close more clicked filter layer.
*/
$('body').on('click', '.closepos', function()
{
    $('#filterlayer').hide();
    $('.js-overlay').fadeOut();
    $(window).off('scroll');
});


function formatClusterData(resultOfAjax,titleOfFilter){
    var allClustersList = resultOfAjax.clusters.result_arr;
                    var clusterListArr = {"HEAD":{},"SUB":{}};
                    
                    $.each(allClustersList, function( index, val ) {
                    if(val.label==titleOfFilter){
                        $.each(val.arr2, function( indexed, valued ) {
                           /**
                            * Heading of cluster more layer 
                            */
                            if(valued.isHeading=="Y"){
                                var markSubTrue = false;
                                var ID = valued.id;
                                clusterListArr["HEAD"][valued.label]=valued;
                            }
                            /**
                            * Sub part of heading
                            */
                            else{
                                var PID = valued.parentId;
                                var ID = valued.id;
                                /**
                                * is All is Selected mark Selected
                                */
                                
                                var Label = valued.label;
                                if(clusterListArr["SUB"].hasOwnProperty(PID) == false){
                                    clusterListArr["SUB"][PID] = {};   
                                }
                                clusterListArr["SUB"][PID][Label] = valued;
                                
                            }
                        });
                        } 
                    });
                    
                  /**
                    * Preparing objects in parent child relation for display
                    */
                   var catContent = {};
                    $.each(clusterListArr["HEAD"], function( index, valueLab ) {
                        resultOfInputSubElements = clusterListArr["SUB"][valueLab.id];
                        catContent[index] = {"valueLabel":index,"values":resultOfInputSubElements,"details":valueLab};
                       
                    });
                    var isCheckAll=0;
                    displayClusterData(catContent,isCheckAll);
}

/**
* This function is used to Display the content on click on more link on search Clusters
*/
function moreFilterContentDisplay(catContent,isCheckAll){
 
    var filterContents="";
    filterContents += "<div class='content js-LayerCont mCustomScrollbar'>";
    
    $.each(catContent, function( index, valueLab ) {
           
        /**
        * This generates HTML part for the more content Layer
        */
        filterContents += "<div class='clusterCategory'>\
                                <div> \
                                    <span  class='srpcolr5 fontlig";
                                    if(valueLab.details.isSelected=="true"){                            
                                        filterContents += " filterselAll ";
                                    }
        filterContents += " f14 catTitle' value='"+valueLab.details.id+"'>"+valueLab.valueLabel+"</span>\
                                    <span class='disp_ib pl10 colr2 opa60 fontreg f11 cursp selectAllSubOption' id='selectDeselectAll'"+valueLab.details.id+">";
                                    
                                    /**
                                    * Whether to show SELECT ALL or DESLECT ALL
                                    */
                                    if(valueLab.details.isSelected=="true"){
                                        filterContents += "DESELECT ALL";
                                    }
                                    else{
                                        filterContents += "SELECT ALL";
                                    }
                                    filterContents += "</span>\
                                                        </div>\
                                    <div class='pb30 ulinline filteropt'>\
                                    <ul class='clearfix fontlig f13'>";
        
                                    
                                    var valuesToShow = valueLab.values;
                                    
                                    /**
                                    * Sorting the content on alphabetical basis
                                    */
                                    valuesToShown = sortObject(valuesToShow);
                                    
                                    /**
                                    * Displaying each element of object
                                    */
                                   
                                    $.each(valuesToShown, function( cindex, valueElement ) {
                                        //console.log(cindex);
                                           filterContents += "<li";
                                          /**
                                           * Mark Checked for an option in more layer
                                           */
                                           if(valuesToShow[cindex].isSelected=='true' || valuesToShow[cindex].isSelected==true)
                                               filterContents += " class='filtersel' ";
                                           filterContents += " value='"+valuesToShow[cindex].id+"' ";
                                           filterContents += "><span>"+valuesToShow[cindex].label+"</span><span>"+valuesToShow[cindex].count+"</span></li>";
                                    });   
                                    filterContents += "</ul>\
                                </div>\
                            </div>";
                    });
                    filterContents += "</div>";     
                    return filterContents;
}

/**
* This function is used to sort an object on key basis in alphabetical order
*/
function sortObject(o) {
    var sorted = {},
    key, a = [],b = [],c = [];
    
    for (key in o) {
        if (o.hasOwnProperty(key)) {
                a.push(key);
        }
    }
    
    a.sort();
    
    for (key = 0; key < a.length; key++) {
        if(a[key]=="Other" || a[key]=="Others")
            b.push(a[key]);
        else
            c.push(a[key]);
    }
    for (key = 0; key < b.length; key++) {
        c.push(b[key]);
    }
    for (key = 0; key < c.length; key++) {
            sorted[c[key]] = o[c[key]];
    }
    
    return sorted;
}

/**
* This function will handle select all and desselect all functionality
*/
$('body').on('click', '.selectAllSubOption', function()
{
    if($(this).text()=="SELECT ALL" || $(this).text()==""){
        $($($($(this).parents(".clusterCategory")[0]).children()[1]).children("ul")).children("li").addClass("filtersel");
        $(this).text("DESELECT ALL");
        $($(this).parent().children("span")[0]).addClass("filterselAll");
    }
    else{
        $($($($(this).parents(".clusterCategory")[0]).children()[1]).children("ul")).children("li").removeClass("filtersel");
        $(this).text("SELECT ALL");
        $($(this).parent().children("span")[0]).removeClass("filterselAll");
    }
    // Update count on top
    var countOfSelected = $(".filtersel").length;
    $("#filterCount").text(countOfSelected+" selected");
});


/**
* This function will handle select and desselect funtionality
*/
$('body').on('click', '.filteropt ul li', function()
{ 
        // Action of colout change
        var classname = $(this).attr("class");
        if(classname=="filtersel")
            $(this).removeClass("filtersel");
        else
            $(this).addClass("filtersel");
        
        // Update Select All/Deselect all button
        var selectLi = $($($($(this).parents(".clusterCategory")[0]).children()[1]).children("ul"));
        if(selectLi.children("li").length==selectLi.children(".filtersel").length){
            $($(this).parents(".clusterCategory").children()[0]).children(".selectAllSubOption").text("DESELECT ALL");
            $($($(this).parents(".clusterCategory").children()[0]).children("span")[0]).addClass("filterselAll");
        }
        else{
            $($(this).parents(".clusterCategory").children()[0]).children(".selectAllSubOption").text("SELECT ALL");
            $($($(this).parents(".clusterCategory").children()[0]).children("span")[0]).removeClass("filterselAll");
        }
        
        // Update count on top
        var countOfSelected = $(".filtersel").length;
        $("#filterCount").text(countOfSelected+" selected");
        
        
});

/**
* Action to be taken when checkbox is clicked.
*/
var clearTimedOutVar;
$('body').on('click', '.js-cluster', function()
{
	var checkboxObj= this;
	var tempName = checkboxObj.name;
	var clusterName = tempName.replace("[]", "");
	var appCluster = clusterName.replace("appCluster","");
	var array = new Array();
	var atleastOneOptionSelected = false;
        //console.log($(this).parent().parent().parent().parent(".sideClusters"));
	handleClusterTick(this);
      
	/* last activity */
	if(this.name=='appCluster1[]')
	{
		var currentVal = this.value;
		var currentChe = this.checked
		$('input[name="'+tempName+'"]').each(function(i,el){
			if(currentChe===true)
			{
				if(currentVal=='3' && (el.value=='2' || el.value=='1'))
				{
					el.checked='checked';
					checkClusterOptions(el);
				}
				if(currentVal=='2' && (el.value=='1'))
				{
					el.checked='checked';
					checkClusterOptions(el);
				}
			}	
			else
			{	
				if(currentVal=='1' && (el.value=='2' || el.value=='3'))
				{
					el.checked='';
					uncheckClusterOptions(el);
				}
				if(currentVal=='2' && (el.value=='3'))
				{
					el.checked='';
					uncheckClusterOptions(el);
				}
			}	
		});
	}
	/* last activity */
	/* handling handicapped */
	if(this.name=='appCluster19[]')
	{
		var currentVal = this.value;
		var currentChe = this.checked
		if(currentVal=='1,2,3,4')
		{
			$('input[name="'+tempName+'"]').each(function(i,el){
				if(el.value!='ALL' && el.value!='1,2,3,4')
				{
					if(currentChe===true)
					{
						el.checked='checked';
						checkClusterOptions(el);
					}
					else
					{
						el.checked='';
						uncheckClusterOptions(el);
					}
				}
			});
		}	
		else if(currentVal!='ALL')
		{
			var uncheckHandi=0;
			$('input[name="'+tempName+'"]:not(:checked)').each(function(i,el){
			if(el.value!='ALL' && el.value!='1,2,3,4')
				uncheckHandi=1;
			});		
			$('input[name="'+tempName+'"]').each(function(i,el){
				if(el.value=='1,2,3,4')
				{
					if(uncheckHandi==1)
					{
						el.checked='';
						uncheckClusterOptions(el);
						//array[0]=null;
					}
					else
					{
						el.checked='checked';
						checkClusterOptions(el);
					}
				}	
			});
	 	}
	}
	/* handling handicapped */



	if(this.value=='ALL') //LATER
	{
		array.push(this.value);
		$('input[name="'+tempName+'"]:checked').each(function(i,el){
	    		if(el.value!='ALL') //LATER
		    	{
				el.checked='';
				uncheckClusterOptions(el);
			}
	    	});
	}
	else
	{
	    	$('input[name="'+tempName+'"]:checked').each(function(i,el){
        	if(el.value!='ALL') //LATER
	    	{
	    		array.push(el.value);	    		
	    		atleastOneOptionSelected = true;
	    	} 	
        	});
    	}

    /**
    * Select value (ALL ) if all options are removed.
    */
    if (array.length === 0) {
    		$('input[name="'+tempName+'"]:not(:checked)').each(function(i,el){
			if(el.value=='ALL')
			{
		    		el.checked='checked'
				checkClusterOptions(el);
			}
		});
    }

    /**
    * Remove all from selected option as soon as one non selected value is choosen.
    */
    if(atleastOneOptionSelected)
    {
    	$('input[name="'+tempName+'"]:checked').each(function(i,el){
        	if(el.value=='ALL') 
		{
	    		el.checked='';
			uncheckClusterOptions(el);
		}
        });
    }
    var appClusterVal = array.toString();

    /** 
    * Request Parameter Generation 
    **/
    parameterAndProcessRequest(array,appCluster,this);
});


/******************* Apply Filter on more *************/
$('body').on('click', '#applyClusterFilter', function()
{
    var array = new Array();
    var atleastOneOptionSelected=false;
    var appCluster = parseInt($("#filterlayer .title").attr("clusterId"));
    $('li[class="filtersel"]:visible').each(function(i,el){
        	array.push($(el).attr("value"));	    		
	    	atleastOneOptionSelected = true; 	
    });
    if(atleastOneOptionSelected){
        $('.filterselAll:visible').each(function(i,el){
		    if($(el).attr("value"))
	                    array.push($(el).attr("value").replace("@","")+"@");	    		
        });
    }
    
    parameterAndProcessRequest(array,appCluster,'',1);
    $(".closepos").click();
});

function parameterAndProcessRequest(array,appCluster,obj,fromMoreLayerCluster){
    showLoader(obj);
    clearTimeout(clearTimedOutVar);
    var appClusterVal = array.toString();
    var requestParams = "addRemoveCluster=1";
    var timedout=2000;
    if(fromMoreLayerCluster==1)
    {
        requestParams= requestParams+"&fromMoreLayerCluster=1";
	timedout=0;
    }
    requestParams+="&appClusterVal="+appClusterVal+"&appCluster="+appCluster;    
            
    var infoArr = {};
    infoArr["action"] = "Clusters";
    clearTimedOutVar = setTimeout(function(){sendProcessSearchRequest(requestParams,infoArr) },timedout);
}


function handleClusterTick(obj)
{
        if(obj.checked)
                checkClusterOptions(obj);
        else
                uncheckClusterOptions(obj);
}

function uncheckClusterOptions(obj)
{
        $($(obj).parent()).removeClass('selected');

}

function checkClusterOptions(obj)
{
        $($(obj).parent()).addClass('selected');
}


/**
* Show Hide Clusters
*/
$('body').on('click', '.clusterView', function(){
  if($(this).parent("div").children(".sideClusterHS:visible").length>0)
    $($($(this).children("div").children("div")[0]).children("i")).addClass("srpopenarrow").addClass("js-up");
  else
    $($($(this).children("div").children("div")[0]).children("i")).removeClass("srpopenarrow").removeClass("js-up");
        
  $($(this).parent("div").children(".sideClusterHS")).slideToggle("normal");
  
  

    
});

function moreLayerKeyHandling(e){
        if(e.keyCode == 27)    // ESC key
        { 
             $(".closepos").focus().click();
        }
}

/***
* This function will get all the mapping variables related to noClusterSection.
* @param parms : contains value of cluster array.
* @return : mapping
*/
function noClusterSectionResultMapping(params)
{
    var jshideCircleclass='disp-tbl';
    if(params["searchBasedParam"] == 'kundlialerts')
    {
         jshideCircleclass='disp-none';
    }
    var mapping={
        '{js-hideCircle}': removeNull(jshideCircleclass),
        '{heading}':removeNull(params["heading"]),
        '{totalCount}':removeNull(params["totalCount"]),
        '{message}':removeNull(params["message"])                 
    };
    return mapping;
}
