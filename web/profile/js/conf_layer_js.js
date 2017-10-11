function testWipe(wp)
{
	var ua = navigator.userAgent
	var dom = (document.getElementById)? 1:0
	var ie4 = (document.all&&!dom)? 1:0
	var ie5 = (document.all&&dom)? 1:0
	var nn4 =(navigator.appName.toLowerCase() == "netscape" && parseInt(navigator.appVersion) == 4)
	var nn6 = (dom&&!ie5)? 1:0
	var sNav = (nn4||nn6||ie4||ie5)? 1:0
	if(!sNav) {
	var ps = navigator.productSub
	}
	var cssFilters = ((ua.indexOf("MSIE 5.5")>=0||ua.indexOf("MSIE 6")>=0)&&ua.indexOf("Opera")<0)? 1:0
	var Style=[],Text=[],Count=0,sbw=0,move=0,hs="",mx,my,scl,sct,ww,wh,obj,sl,st,ih,iw,vl,hl,sv,evlh,evlw,tbody
	var HideTip = "eval(obj+sv+hl+';'+obj+sl+'=0;'+obj+st+'=-800');document.getElementById('experience').style.visibility='visible';document.getElementById('degree').style.visibility='visible';"
	var doc_root = ((ie5&&ua.indexOf("Opera")<0||ie4)&&document.compatMode=="CSS1Compat")? "document.documentElement":"document.body"
	var PX = (nn6)? "px" :""
	var oldVal = new String();
	var oldVals = new Array();

	if(ie5||ie4)
	{
		if ((screen.width>=1024) && (screen.height>=768))
		{
			document.getElementById('outerDiv').style.left='540px';
			document.getElementById('outerDiv').style.top='410px';
		}
		else
		{
			document.getElementById('outerDiv').style.left='370px';
			document.getElementById('outerDiv').style.top='255px';
		}
	}
	else
	{	
		var left_h=document.documentElement.clientWidth-435;
		var top_h=(document.documentElement.clientHeight)-175;
		document.getElementById('outerDiv').style.left=left_h+'px';
		document.getElementById('outerDiv').style.top=top_h+'px';
	}
	
	outerLyr = new dynObj('outerDiv');
	//outerLyr.clipTo(0,0,0,0);
	//outerLyr.wipe("in corner",1000,1500);
														     
	outerLyr.clipTo(0,0,0,0);
	outerLyr.wipe(wp,600,800);
	//changeAction('C');
}
														     
function close_layer()
{
	document.getElementById('outerDiv').style.visibility='hidden';
	//alert(document.getElementById('outerDiv').style.visibility);
}

