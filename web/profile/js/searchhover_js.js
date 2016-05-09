/*
Simple Image Trail script- By JavaScriptKit.com
Visit http://www.javascriptkit.com for this script and more
This notice must stay intact
*/

var offsetfrommouse=[15,15]; //image x,y offsets from cursor position in pixels. Enter 0,0 for no offset
var displayduration=0; //duration in seconds image should remain visible. 0 for always.
var currentimageheight = 270;	// maximum image size.
if (document.getElementById || document.all){
	document.write('<div id="trailimageid">');
	document.write('</div>');
}

function gettrailobj(){
if (document.getElementById)
return document.getElementById("trailimageid").style
else if (document.all)
return document.all.trailimagid.style
}

function gettrailobjnostyle(){
if (document.getElementById)
return document.getElementById("trailimageid")
else if (document.all)
return document.all.trailimagid
}


function truebody(){
return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

//Symfony Photo Modification
function showtrail(photochecksum,username,photo_type,height,url,PHOTO_URL){
	var imagename= url;

	if(username!='')
		var title="&nbsp; Photo of "+username;

	if (height > 0){
		currentimageheight = height;
	}

	document.onmousemove=followmouse;

	cameraHTML = '';

	newHTML = '<div style="padding: 5px; background-color: #FFF; border: 1px solid #888; font: normal 11px verdana,arial">';
	/*if(username!='')
		newHTML = newHTML + title ;*/
	newHTML = newHTML + '<div align="center" style="padding: 2px 2px 2px 2px;">';
	newHTML = newHTML + '<img src="' + imagename + '" border="0"></div>';

	if(username!='')
		newHTML = newHTML + title ;
	newHTML = newHTML + '</div>';
	gettrailobjnostyle().innerHTML = newHTML;
	gettrailobj().display="inline";
}
function showtrail_tooltip(text,tipheight,tipwidth){
	currentimageheight=55
	//offsetfrommouse=[0,0]; //image x,y offsets from cursor position in pixels. Enter 0,0 for no offset
        document.onmousemove=followmouse_tooltip;

	//if(currentimageheight!='')
	        newHTML = '<div style="width:'+tipwidth+'px; height:'+tipheight+'px; padding-left:1px; background-color: #FDFBE6; border: 1px solid; font:normal 11px verdana,arial; color:#857e33">'+text+'</div>';
		//alert(newHTML)
	        //newHTML = '<div style="width:250px; height:50px; padding: 0px; background-color: #FDFBE6; border: 1px solid">'+text+'</div>';
        gettrailobjnostyle().innerHTML = newHTML;
        gettrailobj().display="inline";
}


function hidetrail(){
	gettrailobj().innerHTML = " ";
	gettrailobj().display="none"
	document.onmousemove=positiontip;
	//document.onmousemove=""
	gettrailobj().left="-500px"

}

function followmouse(e){
	var xcoord=offsetfrommouse[0]
	var ycoord=offsetfrommouse[1]
	var docwidth=document.all? truebody().scrollLeft+truebody().clientWidth : pageXOffset+window.innerWidth-15
	var docheight=document.all? Math.min(truebody().scrollHeight, truebody().clientHeight) : Math.min(window.innerHeight)

	//if (document.all){
	//	gettrailobjnostyle().innerHTML = 'A = ' + truebody().scrollHeight + '<br>B = ' + truebody().clientHeight;
	//} else {
	//	gettrailobjnostyle().innerHTML = 'C = ' + document.body.offsetHeight + '<br>D = ' + window.innerHeight;
	//}

	if (typeof e != "undefined"){
		if (docwidth - e.pageX < 380){
		//alert('if1');
			xcoord = e.pageX - xcoord - 190; // Move to the left side of the cursor
		} else {
		//alert('else1');
			xcoord += e.pageX;
		}
		if (docheight - e.pageY < (currentimageheight + 110)){
		//alert('if2');
			ycoord += e.pageY - Math.max(0,(110 + currentimageheight + e.pageY - docheight - truebody().scrollTop));
		} else {
		//alert('else2');
			ycoord += e.pageY;
		}
		//alert(xcoord);
		//alert( ycoord);

	} else if (typeof window.event != "undefined"){
		if (docwidth - event.clientX < 380){
		//alert('ifx');
			xcoord = event.clientX + truebody().scrollLeft - xcoord - 190; // Move to the left side of the cursor
		} else {
		//alert('elsex');
			xcoord += truebody().scrollLeft+event.clientX
		}
		if (docheight - event.clientY < (currentimageheight + 110)){
		//alert('ify');
			ycoord += event.clientY + truebody().scrollTop - Math.max(0,(110 + currentimageheight + event.clientY - docheight));
		} else {
		//alert('elsey');
			ycoord += truebody().scrollTop + event.clientY;
		}

		if(ycoord<200)
			ycoord=205;
	}
	var docwidth=document.all? truebody().scrollLeft+truebody().clientWidth : pageXOffset+window.innerWidth-15
	var docheight=document.all? Math.max(truebody().scrollHeight, truebody().clientHeight) : Math.max(document.body.offsetHeight, window.innerHeight)
		if(ycoord < 0) { ycoord = ycoord*-1; }

	gettrailobj().left=xcoord+"px"
	gettrailobj().top=ycoord+"px"

	//alert(ycoord);

}
function followmouse_tooltip(e){
	var xcoord=offsetfrommouse[0]
	var ycoord=offsetfrommouse[1]
	var docwidth=document.all? truebody().scrollLeft+truebody().clientWidth : pageXOffset+window.innerWidth-15
	var docheight=document.all? Math.min(truebody().scrollHeight, truebody().clientHeight) : Math.min(window.innerHeight)

	//if (document.all){
	//	gettrailobjnostyle().innerHTML = 'A = ' + truebody().scrollHeight + '<br>B = ' + truebody().clientHeight;
	//} else {
	//	gettrailobjnostyle().innerHTML = 'C = ' + document.body.offsetHeight + '<br>D = ' + window.innerHeight;
	//}

	if (typeof e != "undefined"){
		if (docwidth - e.pageX < 380){
		//alert('if1');
			xcoord = e.pageX - xcoord - 190; // Move to the left side of the cursor
		} else {
		//alert('else1');
			xcoord += e.pageX - 310;
		}
		if (docheight - e.pageY < (currentimageheight + 110)){
		//alert('if2');
			ycoord += e.pageY - Math.max(0,(110 + currentimageheight + e.pageY - docheight - truebody().scrollTop));
		} else {
		//alert('else2');
			ycoord += e.pageY;
		}
		//alert(xcoord);
		//alert( ycoord);

	} else if (typeof window.event != "undefined"){
		if (docwidth - event.clientX < 380){
		//alert('if');
			xcoord = event.clientX + truebody().scrollLeft - xcoord - 280; // Move to the left side of the cursor
		} else {
		//alert('else');
			xcoord += truebody().scrollLeft+event.clientX - 310;
		}
		if (docheight - event.clientY < (currentimageheight + 110)){
			ycoord += event.clientY + truebody().scrollTop - Math.max(0,(110 + currentimageheight + event.clientY - docheight));
		} else {
			ycoord += truebody().scrollTop + event.clientY;
		}
	}

	var docwidth=document.all? truebody().scrollLeft+truebody().clientWidth : pageXOffset+window.innerWidth-15
	var docheight=document.all? Math.max(truebody().scrollHeight, truebody().clientHeight) : Math.max(document.body.offsetHeight, window.innerHeight)
		if(ycoord < 0) { ycoord = ycoord*-1; }
	gettrailobj().left=xcoord+"px"
	gettrailobj().top=ycoord+"px"

}




