(function ($) {
   //To get the view port height
    function getViewportHeight() {
        var height = window.innerHeight; // Safari, Opera
        var mode = document.compatMode;

        if ( (mode || !$.support.boxModel) ) { // IE, Gecko
            height = (mode == 'CSS1Compat') ?
            document.documentElement.clientHeight : // Standards
            document.body.clientHeight; // Quirks
        }

        return height;
    }
    
    //Function to get scroll end 
      $.fn.scrollEnd = function(callback, timeout) {          
  $(this).scroll(function(){
    var $this = $(this);
    if ($this.data('scrollTimeout')) {
      clearTimeout($this.data('scrollTimeout'));
    }
    $this.data('scrollTimeout', setTimeout(callback,timeout));
  });
};

     //Function to check which element is in view
     function checkInview() {
        var vpH = getViewportHeight(),
            scrolltop = (document.documentElement.scrollTop ?
                document.documentElement.scrollTop :
                document.body.scrollTop),
            elems = [];
        
        // this is how it knows which elements to check for
        if(typeof($.cache) != "undefined")
        $.each($.cache, function () {
            if (this.events && this.events.inview) {
		   elems.push(this.handle.elem);
		  
            }
        });
        if (elems.length) {
		var gotElement = 0;
		var doneEle =[];
		var count = 0;
		var foundIndex= 1;
            $(elems).each(function () {
		    		    
                var $el = $(this),
                    top = $el.offset().top,
                    height = $el.height(),
                    inview = $el.data('inview') || false;
                    
                   var myid = $el.attr("id");
                   var found = $.inArray(myid, doneEle);
                   doneEle.push(myid);
                // Element is in view only if its top lies in the viewport area
               if (found== -1 && gotElement!=1 && (scrolltop <= top && top < (scrolltop +vpH))) {
		    gotElement = 1;
                    if (!inview) {
                        $el.data('inview', true);
                        $el.addClass("inview"); 
                        $el.trigger('inview', [ true ]);
                        foundIndex = count;

			// Set images src for inview elements and set global varible for uploading images
			//loadImageId = $(elems[foundIndex]).attr("id");
		//	loadNextImages();
			
                    }
                }
                else
                {
			$el.data('inview', false);
			$el.removeClass("inview");
                        $el.trigger('inview', [ false ]);
                }
                count++;
            });
	}
    }
    
	
	
	
    
    // kick the event to pick up any elements already in view.
    // note however, this only works if the plugin is included after the elements are bound to 'inview'
    $(function () {
        $(window).scrollEnd(function(){
		//$("div.tupleOuterDiv").each(function(i, obj) {
			//if($(this).attr("id")!="{tupleOuterDiv}")
				//$(this).bind("inview",callInview);
		//});
				
		checkInview();
	}, 100);
	
	
    });
})(jQuery);
//This function can be called when inview is triggered
function callInview(event, visible)
{
}

function getInViewId()
{
	if($(".inview").attr("id"))
		return $(".inview").attr("id");
	//else
	//	return loadImageId;
}
