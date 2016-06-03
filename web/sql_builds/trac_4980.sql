use MIS;
update MIS.PIXELCODE set PIXELCODE="<script language=\"JavaScript\" type=\"text/javascript\">
if(!alreadyCalledPixel)
{
	(function($){
	if($)
	{
	$(document).ready(function(){
	setTimeout(function(){
	if(location.protocol == \"https:\"){
		          var script_src='https://s.yimg.com/wi/ytc.js';
		        }
		        else{
		          var script_src='http://d.yimg.com/wi/ytc.js';
		        }
	$.ajax({
	  url: script_src,
	  dataType: 'script',
	  cache: true, 
	  async: true,
	  success: function() {
	var scripttag=document.createElement
			var g_YWA_funcs = {
			  doSiteRetargeting: function(){
			    try{
			      YAHOO.ywa.I13N.fireBeacon([
				{
				  \"projectId\" : \"10001619333563\",
				  \"coloId\" : \"SP\",
				  \"properties\" : {
				    \"pixelId\" : \"15706\",
				    \"qstrings\" : {}
				  }
				}
			      ]);
			//RT_pixel
			    }catch(e){
			      if (window.console && window.console.warn) {
				window.console.warn(e.message || \"Unknown error\");
			      }
			      return;
			    }
			  },
			  doConversion: function(){
			    try{
			      YAHOO.ywa.I13N.fireBeacon([
				{
				  \"projectId\" : \"10001619333563\",
				  \"coloId\" : \"SP\",
				  \"properties\" : {
				    \"pixelId\" : \"15043\",
				    \"qstrings\" : {}
				  }
				}
			      ]);
			//CV_pixel
			    }catch(e){
			      if (window.console && window.console.warn) {
				window.console.warn(e.message || \"Unknown error\");
			      }
			      return;
			    }
			  }
			};
			g_YWA_funcs.doConversion();
	  }
	});
	},4000);
	});
	}
	})(jQuery);
}
var alreadyCalledPixel=1;
</script>" where GROUPNAME="Yahoo_CPA_oct";

