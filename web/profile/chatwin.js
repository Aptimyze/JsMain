/*
 File created by nikhil tandon for javascript functions at the client end chatwindow
*/

//var a=1;
var arr;
//var blink;
var zx=true;
var qDuration=600; var qCounter=0;
var site_r=0;
var site_title=document.title;

function quake()
{
	// the horizontal displacement
	var deltaX=1;
	// make sure the browser support the moveBy method 
	if (window.moveBy)
	{
		for (qCounter=0; qCounter<qDuration; qCounter++)
		{
			 // shake left
			if((qCounter%4)==0)
			{
				 window.moveBy(deltaX, 0);
			} // shake right
			else if ((qCounter%4)==2)
			{ window.moveBy(-deltaX, 0); }
			// speed up or slow down every X cycles
			if ((qCounter%30)==0)
			{
				 // speed up halfway
				 if(qCounter<qDuration/2) 
				{ deltaX++; }
				 // slow down after halfway of the duration
				 else { deltaX--; }
			} 
		}
 	}
}

function myFlash_DoFSCommand(command,args)
{
        try
        {
                systemParameters();
		if(command=="buzz")
		{
			window.focus();
			try{
			quake();
			}catch(e){}
                }
                if(browser=="MSIE" && version>5)
               	{
		         k=IsBrowserMinimized();
		}
                else if(browser=="MSIE")k=false;
                else  k=true;
	        if(k)
		{
                        if(command=="newtitlez")
                        {
                                newtitlez(args);
                        }
                        else if(command=="showtitle")
                        {
                                showtitle(args);
                        }
                }
                else
                {
                        return;
                }
	}
        catch(e)
        {}
}

function showtitle(args)
{
	zx=false;
	document.title="Chatwindow-Jeevansathi.com";
}
function sitetitle(args)
{
        zx=false;
        site_r=0;
        document.title=args;
}
function newtitlez(args)
{
	arr=args;
	//blink=0;
	zx=true;
	titlebar(0);
	return;		
}
function titlebar(val)
{
	var msg= arr;
	var speed = 500;
	var pos = val;

	if(!zx) msg1=site_title;
        else
		var msg1  = msg+" ******";
	if(site_r==1)
		var msg2  = "Chatwindow-Jeevansathi.com"+" -------";
	else
		var msg2 = site_title;
	if(pos == 0){
		masg = msg1;
		pos = 1;
	}
	else if(pos == 1){
		masg = msg2;
		pos = 0;
	}
	document.title = masg;
	if(zx&&browser=="MSIE"&&version>5)
	{
        	zx=IsBrowserMinimized();
	}
	if(zx)
	{
		timer = window.setTimeout("titlebar("+pos+")",speed);
	}
	else
	{
		if(site_r==1)
                        var msg2  = "Chatwindow-Jeevansathi.com"+" -------";
                else
                        var msg2 = site_title;
	}
}

function IsBrowserMinimized()
{
	try
	{
		if(typeof( window.createPopup ) == "undefined")
			return false;
		var popupWindow = window.createPopup();
		var left = window.screen.availWidth;
		var top = window.screen.availHeight;
		popupWindow.show( left, top, 1, 1, document.body );
		if( popupWindow.document.parentWindow.screenLeft == 0 )
		return true;
	}
	catch(err)
	{
		return false;
	}
	return false;
}
