var keys = [37, 38, 39, 40];

function preventDefault(e) {
  e = e || window.event;
  if (e.preventDefault)
      e.preventDefault();
  e.returnValue = false;  
}

function keydown(e) {
    for (var i = keys.length; i--;) {
        if (e.keyCode === keys[i]) {
            preventDefault(e);
            return;
        }
    }
}

function wheel(e) {
  preventDefault(e);
}

function disable_scroll() {
  if (window.addEventListener) {
      window.addEventListener('DOMMouseScroll', wheel, false);
  }
  window.onmousewheel = document.onmousewheel = wheel;
  document.onkeydown = keydown;
}

function enable_scroll() {
    if (window.removeEventListener) {
        window.removeEventListener('DOMMouseScroll', wheel, false);
    }
    window.onmousewheel = document.onmousewheel = document.onkeydown = null;
}
var stopTimer=100;
var HamburgerTimer=2000;
function EventStopAlready()
{
    if($("#pageloader").hasClass("simple"))
         return true;
     else
         return false;
}
function stopTouchEvents(simple,dark,image)
{
	//document.ontouchstart = function(e){ console.log("rediff");e.preventDefault(); return false;}
	if(simple)
		$("#pageloader").addClass("simple");
	if(dark)
		$("#pageloader").addClass("dark");
	if(image)
		$("#pageloader").addClass("image");
	//$("#pageloader").swipe("disable");
	$(window).bind("touchstart",function(e){e.preventDefault();e.stopPropagation();});
	$(window).bind("touchmove",function(e){e.preventDefault();e.stopPropagation();});
	
}
function startTouchEvents(Timer)
{
	Timer=Timer?Timer:stopTimer;
	
	setTimeout(function(){
		
	$("#pageloader").removeClass("simple").removeClass("dark").removeClass("image"); },Timer);
	//$("#pageloader").swipe("enable");
	$(window).unbind("touchstart");
	$(window).unbind("touchmove");
}
function stopScrolling()
{return;
	$(document).unbind("touchmove");
	$(document).bind("touchmove",function(e){
	//if(!$("#overlay_2").has($(e.target)).length)
		e.preventDefault();
	
});

}
function startScrolling()
{
	$(document).unbind("touchmove");
}
function stopPropagation(ev)
{
	ev.preventDefault();
	ev.stopPropagation();
}
function disable_touch(){
	document.ontouchmove = function(e){ e.preventDefault(); }
}
function enable_touch(){
	document.ontouchmove = function(e){ return true; }
}
function disable_scrolling(){
	$("body").css("overflow","hidden");
	//$("body").css("position","fixed");
}
function enable_scrolling(){
	$("body").css("overflow","auto");
	//$("body").css("position","relative");
}
