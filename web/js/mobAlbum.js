$(document).ready(function(){
	//alert($(window ).width());
	widthRotate=$("#imgMaxWid0").width();

	/* load next image as well */
	if($("#imageId1"))
	{
		imageLoadedString=imageLoadedString+"'1',";
		$("#imageId1").attr("src",img_arr[1]);
	}
	/* load next image as well */

	$('.nextImage').click(function(e){    
	e.preventDefault;
	callNext();
	});

	$('.prevImage').click(function(e){
	e.preventDefault;
	callPrev(e);
	});
});

function callNext()
{
	callNextPrevEvent('N');
	var rotator = $('#rotator .allImages');
	rotator.children('.mainImageHolder').first().animate({marginLeft:"-="+widthRotate+"px"}, function(){
	    $(this).appendTo(rotator).removeAttr("style");
	});
}
function callPrev()
{
	callNextPrevEvent('P');
	var rotator = $('#rotator .allImages');
	rotator.children('.mainImageHolder').last().prependTo(rotator).removeAttr("style").css("margin-left", '-'+widthRotate+'px').animate({marginLeft:"0"});
}


/** Call Next Prev Event **/
function callNextPrevEvent(nextPrevAction)
{

	var oldImageCounter = currentImage;

/* test */
//$("#imgMaxWid"+oldImageCounter).removeClass("album-holdr");
/* test */
        if(nextPrevAction == 'P' && currentImage == 0)
                currentImage = noOfPics - 1;
        else if (nextPrevAction == 'N' && currentImage == (noOfPics - 1))
                currentImage = 0;
        else
        {
                if(nextPrevAction == 'P')
                {
                        currentImage -- ;
                }       
                else if(nextPrevAction == 'N')
                {
                        currentImage ++ ;
                }
        }
	if($("#imageId"))
	{
                if(nextPrevAction == 'P')
			currentImageNext = currentImage-1;
		else
			currentImageNext = currentImage+1;

		if(imageLoadedString.indexOf("'"+currentImage+"'")=='-1')
		{
			imageLoadedString=imageLoadedString+"'"+currentImage+"',";
			$("#imageId"+currentImage).attr("src",img_arr[currentImage]);
		}
		if(imageLoadedString.indexOf("'"+currentImageNext+"'")=='-1')
		{
			imageLoadedString=imageLoadedString+"'"+currentImageNext+"',";
			$("#imageId"+currentImageNext).attr("src",img_arr[currentImageNext]);
		}
	}

/* test */
//$("#imgMaxWid"+currentImage).addClass("album-holdr");
/* test */

        currentImageFromIndex1 = currentImage+1;
        $("#currentPicCount").html(currentImageFromIndex1);
}
/** Call Next Prev Event **/


/*** Handling Swipe right and left ***/
$(function() {      
      $("#rotator").swipe( { swipeLeft:swipeLeft, swipeRight:swipeRight, allowPageScroll:"auto"} );

      function swipeLeft(event, direction, distance, duration, fingerCount) {
	callNext();
      }
    
      function swipeRight(event, direction, distance, duration, fingerCount) {
	callPrev();
      }
    });
/*** Handling Swipe right and left ***/
