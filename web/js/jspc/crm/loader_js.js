/**
*  This function wil show the loader.
* @param : type
**/
function showLoader(type)        
{
	if(type=='show')
	{
		$('#resultsLoaderTop').show();
	}
	else
	{
		$('#resultsLoaderTop').hide();
	}
}

/*
 * Function to show common Loader
 */
function showLineLoader(divElement) {
    if (typeof divElement == "undefined") divElement = "body";
    //coverProcessing
    $(divElement).prepend("<div class='commonLoader'><div class='coverProcessing'></div><div class='loaderLinear'></div></div>");
    $('.coverProcessing').fadeIn(1000);
    //Scrolling
    var current = $(window).scrollTop();
    $(window).scroll(function() {
        $(window).scrollTop(current);
    });
    $('.coverProcessing').css("position", "absolute").css("top", current);
    $(".loaderLinear").animate({
        width: '20%'
    }, 'slow', function() {
        $(".loaderLinear").animate({
            width: "40%"
        }, 4000, function() {
            $(".loaderLinear").animate({
                width: "95%"
            }, 20000, function() {});
        });
    });
}
/*
 * Function to hide common Loader
 */
function hideLineLoader() {
    $(window).off('scroll');
    $(".loaderLinear").stop().animate({
        width: "100%"
    }, "fast", function() {
        $('.commonLoader').fadeOut(1000, function() {
            $('.commonLoader').remove();
        });
    });
}
