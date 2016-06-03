$(document).ready(function(){
  var currentPosition = parseInt(document.getElementById("sliderNo").value);	// Changed by Anand to keep the currentPosition of the slider according to current pic.
  var slideWidth = 324;
  var slides = $('.slide');
  var numberOfSlides = slides.length;

  // Remove scrollbar in JS
  $('#slidesContainer').css('overflow', 'hidden');

  // Wrap all .slides with #slideInner div
  slides
    .wrapAll('<div id="slideInner"></div>')
    // Float left to display horizontally, readjust .slides width
	.css({
      'float' : 'left',
      'width' : slideWidth
    });

  // Set #slideInner width equal to total width of all slides
  $('#slideInner').css({'width' : slideWidth * numberOfSlides, 'margin-left' : (-currentPosition)*slideWidth});	//margin-left added by Anand

  // Insert controls in the DOM
  $('#slideshow')
    //.prepend('<span class="control leftControl sldr_sp" id="leftControl">Clicking moves left</span>')
    .prepend("<span class=\"control leftControl_gr_arrow sldr_sp\" id=\"leftControl_gr_arrow\" onmouseover = \"changeColor('leftOver')\" onmouseout = \"changeColor('leftOut')\">Clicking moves left</span>")
    //.append('<span class="control rightControl sldr_sp" id="rightControl">Clicking moves right</span>')
    .append("<span class=\"control rightControl_gr_arrow sldr_sp\" id=\"rightControl_gr_arrow\" onmouseover = \"changeColor('rightOver')\" onmouseout = \"changeColor('rightOut')\">Clicking moves right</span>");

  // Hide left arrow control on first load
  manageControls(currentPosition);

  // Create event listeners for .controls clicks
  $('.control')
    .bind('click', function(){
    // Determine new position
	//currentPosition = ($(this).attr('id')=='rightControl') ? currentPosition+1 : currentPosition-1;
    	if ($(this).attr('id')=='rightControl_gr_arrow')
	{
		if (currentPosition == numberOfSlides-1)
			currentPosition = 0;
		else
			currentPosition = currentPosition+1;
	}
    	else if ($(this).attr('id')=='leftControl_gr_arrow')
	{
		if (currentPosition == 0)
			currentPosition = numberOfSlides-1;
		else
			currentPosition = currentPosition-1;
	}
	else
	{}

	// Hide / show controls
    manageControls(currentPosition);
    // Move slideInner using margin-left
    $('#slideInner').animate({
      'marginLeft' : slideWidth*(-currentPosition)
    });
	var i = 0;
	for (i=0;i<numberOfSlides;i++)
	{
		if (currentPosition==i)
		{
			document.getElementById("grey_box"+i).style.display = "none";
			document.getElementById("blue_box"+i).style.display = "inline";
			
		}
		else
		{
			document.getElementById("grey_box"+i).style.display = "inline";
			document.getElementById("blue_box"+i).style.display = "none";
		}
	}
  });

  // manageControls: Hides and Shows controls depending on currentPosition
  function manageControls(position){
    // Hide left arrow if position is first slide
	if(position==0 && numberOfSlides==1)
	{ 
	//	$('#leftControl').hide() 
		$('#leftControl_gr_arrow').hide() 
	//	$('#rightControl').hide() 
		$('#rightControl_gr_arrow').hide() 
	} 
/*	else if (position==0 && numberOfSlides>1)
	{ 
		$('#leftControl').show() 
		$('#leftControl_gr_arrow').hide() 
		$('#rightControl').hide() 
		$('#rightControl_gr_arrow').show() 
	}
	else if (position==numberOfSlides-1 && position!=0)
	{
		$('#leftControl').hide() 
		$('#leftControl_gr_arrow').show() 
		$('#rightControl').show() 
		$('#rightControl_gr_arrow').hide() 
	}*/
	else
	{
	//	$('#leftControl').hide() 
		$('#leftControl_gr_arrow').show() 
	//	$('#rightControl').hide() 
		$('#rightControl_gr_arrow').show() 
	}
  }	
});

function changeColor(name)
{
	if (name == "leftOver")
	{
		document.getElementById("leftControl_gr_arrow").className = "control leftControl_grn sldr_sp";
	}
	else if (name == "leftOut")
	{
		document.getElementById("leftControl_gr_arrow").className = "control leftControl_gr_arrow sldr_sp";
	}
	else if (name == "rightOver")
	{
		document.getElementById("rightControl_gr_arrow").className = "control rightControl_grn sldr_sp";
	}
	else if (name == "rightOut")
	{
		document.getElementById("rightControl_gr_arrow").className = "control rightControl_gr_arrow sldr_sp";
	}
	else
	{}
}
