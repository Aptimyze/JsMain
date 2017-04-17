     (function($){
var saveDetail=(function(){
    function saveDetail(){
        this.editFieldArray = {};
        this.flag=0;
    }
    saveDetail.prototype.push = function(key,value)
    {
		if(Object.keys(this.editFieldArray).length>0)
			$('#SaveSub').removeClass("opa70");
       this.editFieldArray[key] = value;
    }
    saveDetail.prototype.pop = function(key)
    {
		delete this.editFieldArray[key];
		if(Object.keys(this.editFieldArray).length==0)
			$('#SaveSub').addClass("opa70");
		//console.log(this.editFieldArray);
    }
    saveDetail.prototype.has_value = function()
    {
		for(var prop in this.editFieldArray) {
					
			if(this.editFieldArray.hasOwnProperty(prop))
			{
				if(prop.slice(0,2)=="P_" || prop=="SPOUSE")		
					return "DPP";
				else
					return "EDIT_PROFILE";
			}
		 }
			return false;
    }
    saveDetail.prototype.pushContactJson = function(json)
    {
		this.editFieldArray=$.extend(true, {}, this.editFieldArray, json);
		if(Object.keys(this.editFieldArray).length>0)
			$('#SaveSub').removeClass("opa70");
		//console.log(this.editFieldArray);
    }
    saveDetail.prototype.submit= function()
    {   
        var ele=this;
        stopTouchEvents(1,1,1);
	ele.submitReal();
        //setTimeout(function(){ele.submitReal()},animationtimer);
    }
    saveDetail.prototype.submitReal=function()
    {
		editFieldArr=this.editFieldArray;
        for(akey in editFieldArr)
        {   
            if(akey == '')
                this.flag=1;
        }
        if(this.flag==0)
        {
			//showLoader();
        $.ajax({
          url: "/api/v1/profile/editsubmit",
          type: 'POST',
          datatype: 'json',
          headers: { 'X-Requested-By': 'jeevansathi' },       
          cache: true,
          async: true,
          data: {editFieldArr : editFieldArr},
          success: function(result) {

				if(CommonErrorHandling(result)||(result.hasOwnProperty("responseStatusCode") && result.responseStatusCode==1 && (result.error[0].indexOf("banned")!=-1 || result.error[0].indexOf("country")!=-1)))
				{
					if(result.hasOwnProperty("error") && result.error)
					{
						startTouchEvents();
						$("#validation_error").text("");
						if(result.error)
							$("#validation_error").text(result.error);
						
						setTimeout(function(){
							if($("#validation_error").text())
								ShowTopDownError([$("#validation_error").text()]);
					//setTimeout(function(){RemoveOverLayer();},animationtimer);
				},
				animationtimer);
					}
					else
					{
						RemoveOverLayer();

						result=formatJsonOutput(result);
						hideLoader();
					}
					//startTouchEvents();
					
					return true;

				}
				else
				{
			
				}
                                

			},
		  error: function() {
			hideLoader();
			$("#validation_error").text("something went wrong");
			setTimeout(function(){
				showErrorOverLayer("fromSubmit");
				setTimeout(function(){RemoveOverLayer();},animationtimer);
				},
				animationtimer);
			return true;
		
        }
         });
        this.flush();
    }}
saveDetail.prototype.submitDpp= function()
    {   
		editFieldArr=this.editFieldArray;
        for(akey in editFieldArr)
        {   
            if(akey == '')
                this.flag=1;
        }
        if(this.flag==0)
        {
			showLoader();
        $.ajax({
          url: "/api/v1/profile/dppsubmit",
          type: 'POST',
          datatype: 'json',
          cache: true,
          async: true,
          data: {editFieldArr : editFieldArr},
          success: function(result) {
			if(CommonErrorHandling(result))
			{
				hideLoader();
				return true;
			}
			else
			{
				hideLoader();
					$("#validation_error").text("");
					if(result.error)
						$("#validation_error").text(result.error);
						
					setTimeout(function(){
					if($("#validation_error").text())
						ShowTopDownError($("#validation_error").text());
					setTimeout(function(){RemoveOverLayer();},animationtimer);
					},
					animationtimer);
				return true;
			}
			},
			error: function() { 
			hideLoader();
			$("#validation_error").text("something went wrong");
			setTimeout(function(){
				showErrorOverLayer("fromSubmit");
				setTimeout(function(){RemoveOverLayer();},animationtimer);
				},
				animationtimer);
			return true;
			}
			
        });
        this.flush();
    }}
saveDetail.prototype.submitDppFilters= function()
    {   
		editFieldArr=this.editFieldArray;
        for(akey in editFieldArr)
        {   
            if(akey == '')
                this.flag=1;
        }
        if(this.flag==0)
        {
			stopTouchEvents(1,1,1);
        $.ajax({
          url: "/api/v1/profile/filtersubmit",
          type: 'POST',
          datatype: 'json',
          cache: true,
          async: true,
          data: {filterArr : editFieldArr},
          success: function(result) {
			if(CommonErrorHandling(result))
			{
				startTouchEvents(200);
                                $("#ed_slider").removeClass("dn");
                                $("#filterDpp").addClass('bottom_1');
                                setTimeout(function(){$("#filterDpp").addClass("dn");},animationtimer);
				return true;
			}
			else
			{
				startTouchEvents(100);
				hideLoader();
					$("#validation_error").text("");
					if(result.error)
						$("#validation_error").text(result.error);
						
					setTimeout(function(){
					if($("#validation_error").text())
						ShowTopDownError($("#validation_error").text());
					setTimeout(function(){RemoveOverLayer();},animationtimer);
					},
					animationtimer);
				return true;
			}
			},
			error: function() { 
			startTouchEvents(100);
			$("#validation_error").text("something went wrong");
			setTimeout(function(){
				showErrorOverLayer("fromSubmit");
				setTimeout(function(){RemoveOverLayer();},animationtimer);
				},
				animationtimer);
			return true;
			}
			
        });
        this.flush();
    }}
    saveDetail.prototype.flush=function()
    {
      this.editFieldArray ={};   
    }
    this.saveDetail=saveDetail;
}).call(this);

})(jQuery)

