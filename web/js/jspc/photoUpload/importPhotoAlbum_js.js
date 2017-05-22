var currentTop="";  //current top position of importphotos vertical bar

/*shift imported Photos vertical bar to newTop
@param : thisElement,direction(up/down)
*/
function shiftImportedPhotosBar(thisElement,direction)
{
	$(thisElement).addClass("jsButton-disabled");
	currentTop = $("#js-addImportAlbum").css('top'); 
	var newTop;
	if(direction=="up")
		newTop = parseInt(currentTop.replace("px", "")) + importPhotosBarHeightPerShift;
	else
		newTop = parseInt(currentTop.replace("px", "")) - importPhotosBarHeightPerShift;
	$("#js-addImportAlbum").animate({top:""+newTop+"px"},300);
	handleImportedPhotoSlider(newTop);
}

/*load imported photos slider
@param : albumsCount
*/
function loadImportedPhotosSlider(albumsCount)
{
	var height = importPhotosBarHeightPerShift * Math.ceil(albumsCount/importPhotosBarCountPerShift);

	//initial top position of album pointer
	initialAlbumImportPointerTop = parseInt(($("#selectedAlbumPointer").css('top')).replace('px',''));

	//set height of import vertical bar  
	$("#js-addImportAlbum").css('height',height+'px');

	//set(enable/disable) shift up and down arrows initially
	handleImportedPhotoSlider(0);
}

/*enable or disable slider up and down arrows
@param : top(int value)
*/
function handleImportedPhotoSlider(top)
{
	if(top == 0)
		$("#shiftImportBarUp").addClass("js-disabled");
	else
		$("#shiftImportBarUp").removeClass("js-disabled");

	var lastTop = importPhotosBarHeightPerShift - (importPhotosBarHeightPerShift * Math.ceil(albumsCount/importPhotosBarCountPerShift)); //top of slider during last possible down shift
	
	if(top == lastTop)
		$("#shiftImportBarDown").addClass("js-disabled");
	else
		$("#shiftImportBarDown").removeClass("js-disabled");
}

/*set pointer towards selected album
@param : thisElement
*/
function setActiveAlbumPointer(offset)
{
	var newtop = initialAlbumImportPointerTop + ((offset % importPhotosBarCountPerShift) * 138);
	$("#selectedAlbumPointer").css('top',newtop + 'px');
}

/*enable shift arrow with class "jsButton-disabled" after interval
* @param : none
*/
function enableShiftArrowsAfterInterval()
{
	setTimeout(function(){$("#controlArrows").find(".jsButton-disabled").removeClass("jsButton-disabled"); },700);
}

$(document).ready(function() {
	//reset top of slider intitally
	$("#js-addImportAlbum").css('top','0px'); 

	//on click of shift up arrow
	$("#shiftImportBarUp").bind("click",function(){
		//shift bar' top up to new position
		shiftImportedPhotosBar(this,"up"); 

		//enable arrow after certain time to prevent subsequent clicks
		enableShiftArrowsAfterInterval(); 
	});

	//on click of shift down arrow
	$("#shiftImportBarDown").bind("click",function(){
		//shift bar' top down to new position
		shiftImportedPhotosBar(this,"down");

		//enable arrow after certain time to prevent subsequent clicks
		enableShiftArrowsAfterInterval();
	});
	showMessageZeroMorePhoto();
});
