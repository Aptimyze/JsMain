var info , infostructure , bannerno = 1 , maxX,maxY,Interval_of_slide=739, Xend=0, Yend=0, Ymovement, Xmovement, slidepercent,slidebeg, stopwatch, Xbeg, Ybeg, xcoordinate, ycoordinate,width_moving;

function init(my_table_content,rotation_interval)
{
	bannerno = 0;
	var w = document.getElementById('structure').offsetWidth;
	w1= w + 'px';
	document.getElementById('banner-info').style.Width=w1;

	document.getElementById('td1').style.Width=w1;
	document.getElementById('td2').style.Width=w1;
	document.getElementById('td3').style.Width=w1;
	document.getElementById('td4').style.Width=w1;
	document.getElementById('td5').style.Width=w1;

	if(!document.getElementById)
		return;

	info = document.getElementById("info"); 
	infostructure = document.getElementById("info_structure"); 

	info.visibility="hidden";
	info.style.top=0;
	info.style.left=0;
	xcoordinate=0;
	ycoordinate=0;

	maxY=( info.offsetHeight - infostructure.offsetHeight>0 ) ? info.offsetHeight - infostructure.offsetHeight : 0 ;
	width_moving=my_table_content ? document.getElementById(my_table_content).offsetWidth:info.offsetWidth;
	maxX=(width_moving - infostructure.offsetWidth > 0 ) ? width_moving - infostructure.offsetWidth : 0;
	info.style.visibility="visible";
	setInterval("slide_movement()",rotation_interval);
 }

function newPosotion(X,Y)
{
	info = document.getElementById("info"); 
	infostructure = document.getElementById("info_structure");
	Xbeg = parseInt(info.style.left);
	if(Xbeg == "")
		Xbeg = 0;
	Ybeg = parseInt(info.style.top);
	Xend = -Math.max(Math.min(X, maxX), 0);
	Yend = -Math.max(Math.min(Y, maxY), 0);
	Ymovement = Yend - Ybeg;
	Xmovement =  Xend - Xbeg;
	slidepercent = Math.PI/(2 * Interval_of_slide);
	slidebeg = (new Date()).getTime();
	stopwatch = setInterval("actual_sliding()",10);
}

function actual_sliding() 
{
	var timepassed = (new Date()).getTime() - slidebeg;
	if (timepassed < Interval_of_slide) 
	{
		var x = Xbeg + Xmovement * Math.sin(slidepercent*timepassed);
		var y = Ybeg + Ymovement * Math.sin(slidepercent*timepassed);
		Changing_Pos(x, y);
	} 
	else
	{	
		clearInterval(stopwatch);
		Changing_Pos(Xend, Yend);
	}
}

function Changing_Pos(x,y)
{
	 if(typeof(x) == "number")
	 {
		info.style.left=x+"px";
		info.style.top=y+"px";
	 }
}

function slide_movement()
{
	if(bannerno < 5)
	{
		bannerno++;
		var w = document.getElementById('structure').offsetWidth
		w1= w + 'px';
		newPosotion(eval(bannerno-1) * w,0);
	}
	else
	{
		bannerno = 1;
		info.style.left="0px";
		info.style.top="0px";
	}
}
