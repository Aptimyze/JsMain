~include_partial('global/header')`
<div id="main_cont">

<div id="container">
<!-- start search-->
<!--QUICK SEARCH STARTS-->
        <p class="clr_4"></p>
<div id="topSearchBand"></div>
~include_partial('global/sub_header')`
~include_partial('social_tabs')`
      
<p class="clr_18"></p>
</div>

<p class="clr_4"></p>
<div id="head1" style="margin: 0pt 10px;font-size: 150%; color: black;padding-left:-10px;">Select Photos to Import</div>
<p class="clr_2"></p>
<div id="head2" style="margin: 0px 10px;font-size: 130%; color: #898989;">~sfConfig::get("app_photo_formats")` | upto ~sfConfig::get("app_max_photo_size")`&nbsp;MB | <span id="pics_no" value="~$limit`" name="~$limit`" >&nbsp;~$limit`&nbsp;</span> allowed</div>

<p class="clr_4"></p>
<!--error starts here -->
<div id="error_block1" class="ylerror no_b" style="line-height:27px;display:none;font-size:16px;width:580px;">
<div class="naukri_btnup9 sprteup fl" style="margin:0px 12px 4px 12px;"></div>
You can select maximum <span id="number1"> </span>.<br>
<p class="clr_2"> </p>
</div>
<div id="error_block2" class="ylerror no_b" style="line-height:27px;display:none;font-size:16px;width:580px;">
<div class="naukri_btnup9 sprteup fl" style="margin:0px 12px 4px 12px;"></div>
<span id="error2"> </span><br>
<p class="clr_2"></p>
</div>
<p class="clr_4"></p>
<!--error ends here -->

<form name=list action="/social/saveImage" method="post" >
<span id="loading"> </span>
<div id="main">
<div class="gactainer" >
	<div class="npbtncont">
		<!--upload starts here -->
		~if $pageno neq 1`
		<a href="~sfConfig::get('app_site_url')`/social/loadingLayer/~if $importSite eq facebook`facebook ~elseif $importSite eq flickr`flickr ~else`picasa ~/if`" class="Pact layerTag" name="previous" onClick="return SetCookie('import_aid_~$importSite`', '1', 'P')" >&nbsp;&lt;&nbsp;Previous&nbsp;</a>
		~else`
		<input class="Pdis" value="< Previous" >&nbsp;</input>
		~/if`
		~$start` - ~$end` of ~$countArr`
		~if $pageno eq $pages`
		<input class="Ndis"  value="Next >" ></input>
		~else`
		<a href="~sfConfig::get('app_site_url')`/social/loadingLayer/~if $importSite eq facebook`facebook ~elseif $importSite eq flickr`flickr ~else`picasa ~/if`" class="Nact layerTag" name="next" onClick="return SetCookie('import_aid_~$importSite`', '1', 'N')" >&nbsp;Next&nbsp;&gt; </a>
		~/if`
	</div>
	<a class="naukri_btnup12 sprteup fr" name="submit1" value="" onClick="return numPicsSelected();">&nbsp;</a>
	<br style="clear:both;" >
</div>
<div class="gallgbgbx">
<ul class="gallery">

<br><br>
~if $errorMsg` <h1> ~$errorMsg` </h1> ~/if`
~foreach from=$pic item=p key=k`
<div class="fl" ~if $k%4 eq 0` style="padding:0 0 15px 0px;" ~else`style="padding:0 0 15px 34px;"~/if`>
		<input name="pictureid[]" type="checkbox" value="~$idList[$k]`" id="pictureid"  ~if $checkedPicsStr|contains:$idList[$k]` checked ~/if` onclick="checkLimit('~$limit`',~$k`)" /><br>
	<img src="~$p`" id="~$idList[$k]`" style="~$stylePadding[$k]`" height="~$imgHeight[$k]`" width="~$imgWidth[$k]`" > <br>
	<div style=" padding-top:6px;"> 
	</div>
</div>
~if $k%4 eq 3`
	<div class=" clr_2"></div>
~/if`
~/foreach`

<p class=" clr_2"></p>
</div>
<!-- upload ends here -->

<br style="clear:both;" >
<div class="gactainer">
        <p class=" clr_4"></p>
<center>
<a class="naukri_btnup12 sprteup" id="temp" value="" onClick="return numPicsSelected();">&nbsp;</a>
</center>
</div>
</div>
</div>
</form>

 <!--right part starts here-->
<div class="lf" style="width:160px;">
        <p class=" clr_4"></p>
         <p class=" clr_12"></p>     
</div>
<!--right part ends here-->
	<p class=" clr_2"></p>
  	<p class=" clr_18"></p>

<!--mid bottom content end -->

<p class=" clr_18"></p>
<!--Main container ends here-->	
</div>
~include_partial('global/footer')`
<script>
$('.layerTag').colorbox({initialWidth:"100px", initialHeight:"100px", overlayClose:false, escKey:false});
$("#cboxLoadingOverlay").css({'background':'none'});
$("#cboxContent").css({'background':'none'});
$("#cboxMiddleLeft").css({'background':'none'});
$("#cboxMiddleRight").css({'background':'none'});
$("#cboxTopLeft").css({'background':'none'});
$("#cboxTopCenter").css({'background':'none'});
$("#cboxTopRight").css({'background':'none'});
$("#cboxBottomLeft").css({'background':'none'});
$("#cboxBottomCenter").css({'background':'none'});
$("#cboxBottomRight").css({'background':'none'});

function SetCookie(c_name,expiredays,p_or_n)
{
var i,x,y,ARRcookies=document.cookie.split(";");
for (i=0;i<ARRcookies.length;i++)
  {
  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
  x=x.replace(/^\s+|\s+$/g,"");
  if (x==c_name)
	{
	var value=unescape(y);
	}
  }
var page='';
var exdate=new Date()
exdate.setMinutes ( exdate.getMinutes() + 60*24 );
expiredays=1;

var index=value.indexOf('@');
var pageno = value.substr(++index);
if(p_or_n == 'N')
	pageno++;
else
	pageno--;
var q=value.substr(0,index);

document.cookie=c_name+ "=" + q + pageno +
((expiredays==null) ? "" : ";expires="+exdate)+";path=/";
//	document.getElementById("loading").innerHTML="<img src='~sfConfig::get('app_img_url')`/images/loading.gif' >";
document.getElementById("error_block1").style.display="none";
document.getElementById("error_block2").style.display="none";
//	document.getElementById("main").style.display="none";
window.scroll(100,100);


if(p_or_n =='N')
	document.cookie="IMPORT_NEXTPAGE_~$importSite`=1" +((expiredays==null) ? "" : ";expires="+exdate)+";path=/";
else
	document.cookie="IMPORT_PREVPAGE_~$importSite`=1" +((expiredays==null) ? "" : ";expires="+exdate)+";path=/";

var picval=null;
var j;

if(document.list.elements["pictureid[]"])
{
	var count = document.list.elements["pictureid[]"];
	for(j=0;j<count.length;j++)
	{
		if(count[j].checked)
		{
			if(picval==null)
				picval=count[j].value;
			else
				picval=picval+"**"+count[j].value;
		}
	}
}

if(picval!=null)
document.cookie="IMPORT_PIC_~$importSite`=" +picval+((expiredays==null) ? "" : ";expires="+exdate)+";path=/";
return true;
}

function limitExceeded()
{
var sel=~sfConfig::get("app_max_no_of_photos") - $importLimit`;
if(~$limit` <= 0 && (sel == ~$limit`))
{
	window.location = "~sfConfig::get('app_site_url')`/social/addPhotos";
}
}
function checkLimit(limit,index)
{
var j=0;

if(document.list.elements["pictureid"])
{
	var count = document.list.elements["pictureid"];
	if(count.checked)
	j++;
}
if(document.list.elements["pictureid[]"])
{
	var count = document.list.elements["pictureid[]"];
	for(i=0;i<count.length;i++)
	{
		 if(count[i].checked)
			j++;
		}
	}
	var noOfPhotos = ~$limit`-j;
	if(noOfPhotos == 1)
		var photo = "&nbsp;more photo";
	else
		var photo = "&nbsp;more photos";
	document.getElementById("pics_no").innerHTML=noOfPhotos+photo;
	document.getElementById("error_block1").style.display="none";
	document.getElementById("error_block2").style.display="none";

	if(j>limit)
	{	
//		alert("you have already selected "+limit+" photos");
		count[index].checked=false;
		var sel=~sfConfig::get("app_max_no_of_photos") - $importLimit`;
		if(sel == 1)
			var text = "&nbsp;photo";
		else
			var text = "&nbsp;photos";
		document.getElementById("number1").innerHTML= sel+text;
		document.getElementById("error_block1").style.display="block";
		document.getElementById("error_block1").scrollIntoView();
		document.getElementById("pics_no").innerHTML=0;
	}
}

function getCheckboxByValue() 
{
/*
        var inputs = document.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i ++)
	{
		~foreach from=$checkedPics item=photo`
                if (inputs[i].type == 'checkbox'  && inputs[i].value == "~$photo`") 
		{
			inputs[i].checked = true;
                }
		~/foreach`
        }
*/
}
/*
function getLargePics()
{
	~foreach from=$largePicsPage item=page key=k`
	if(~$page` == ~$pageno`)
	{
		document.getElementById("~$largePicsLink[$k]`").border="4px;";
		document.getElementById("~$largePicsLink[$k]`").height="91";
		document.getElementById("~$largePicsLink[$k]`").width="91";
	}
	~/foreach`
}
*/

var idset='';
var totalImagesImported;
function numPicsSelected()
{
	var j=0;
        var saveImageArr=new Array();
        var k=0;

        if(document.list.elements["pictureid[]"])
	{
	        var count = document.list.elements["pictureid[]"];
	        for(i=0;i<count.length;i++)
	        {
	                if(count[i].checked)
			{
                               saveImageArr[k]=count[i].value;
                               j++;
                               k++;
			}
	        }
	}
	if(document.list.elements["pictureid"])
	{
		var count = document.list.elements["pictureid"];
		if(count.checked)
		{
			saveImageArr[k]=document.list.elements["pictureid"].value;
			j++;
			k++;
		}
	}

	if(~$limit`-j == ~sfConfig::get("app_max_no_of_photos") - $importLimit`)
	{
		document.getElementById("error2").innerHTML="Please select atleast one photo to continue to the next page.";
                document.getElementById("error_block2").style.display="block";
		document.getElementById("error_block2").scrollIntoView();
		return false;
	}
//start - populating list of photos (photo ids) to be saved
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++)
		{
			x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
			y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
			x=x.replace(/^\s+|\s+$/g,"");
			if (x=='IMPORT_SELPICS_~$importSite`')
			{
				var value=unescape(y);
				var array1 = value.split("|");

				var array2 = new Array();
				var finalList='';
				var flag = 0;
				for(var p=0; p < array1.length; p++)
				{
					array2 = array1[p].split("#");
					idlist = array2.pop();
					idlist=(idlist.split("**")).toString();
					pagelist = array2.pop();
					if(pagelist == ~$pageno`)
					{
						flag = 1;
						idlist=saveImageArr.toString();
					}
					else
						idlist=idlist;
					if(idlist!='')
						finalList=finalList + idlist + ",";
				}
				if(flag == 0 && saveImageArr != '')
				finalList = finalList + saveImageArr.toString() + ",";
				finalList = finalList.substr(0,finalList.length-1);

				saveImageArr = finalList.split(",");
				k=saveImageArr.length;
			}
	}
	//end - populating list of photos (photo ids) to be saved
        document.getElementById("error_block1").style.display="none";
        document.getElementById("error_block2").style.display="none";
        document.getElementById("head1").style.display="none";
        document.getElementById("head2").style.display="none";
        document.getElementById("loading").style.display="block";

	document.getElementById("loading").innerHTML='<span class = ""><br /><br /><br /><span style = "font-size:19px;margin-left:126px;">Importing photos in progress, please wait...</span><br /><br /><br /><div style="width:650px;"><div style="margin-right: 10px;margin-left:125px;"><div class="fl aypprg_br" ><span id = "progress_bar" style="width: 0%;" class="aypbar aypsprite"></span></div><br /><br /><br /><span style="margin-right: 10px;margin-left:125px;" id = "uploadProgressIndicator" ><b><span id = "uploadFileNo">0</span></b> of <b><span id = "totalFileToUpload">'+k+'</span></b> images imported.</span></div></div></span><p class="clr_4"></p>';

        document.getElementById("main").style.display="none";
	document.getElementById("temp").style.display="none";
        //var idset='';
        totalImagesImported=saveImageArr.length;
        for(i=0;i<saveImageArr.length;i++)
        {
                idset = idset + saveImageArr[i] + "|";
        }
	idset = idset.substr(0,idset.length-1);
        multipleRequests(idset);
	window.scroll(100,100);
        return false;
}
var importFileCounter=0;
function callSaveImage(total)
{
	window.location ="~sfConfig::get('app_site_url')`/social/saveImage?successCount="+total+"&importSite=~$importSite`";
}
function updateFileCounter(totalImagesImported)
{
        importFileCounter++;
        document.getElementById("uploadFileNo").innerHTML = importFileCounter;
	var percentVal = (importFileCounter*100)/totalImagesImported;
	var p=percentVal+'%';
	document.getElementById("progress_bar").style.width=p;
}

function checkImageStatus()
{
	var parameter;
	parameter="total="+totalImagesImported+"&saveImage="+idset;
        multipleRequests(parameter);
}
	
var imageStatusCounterForNoIncrement=0;
var randomAvoidIeCaching=232;
function multipleRequests(variable)
{
	var errorInSavingCounter = 0;
	var timedelay=1000;
	var minimumTimeDelay=5000;
	var timedelay1;
	if(variable.indexOf('total')!=-1)
	{
		randomAvoidIeCaching=randomAvoidIeCaching+23;
        	var to_post = variable;
			url1="/social/checkImageStatus?lavesh="+randomAvoidIeCaching;
                $.ajax(
                {
                        url: url1,
                        data: to_post,
                        success: function(response) 
                        {
		                if(response=='userTimedOut')
                        		show_loggedIn_window(); 
                		else
		                {
                		        if(response == 'E')
                                		errorInSavingCounter=errorInSavingCounter+1;
			                else
		                        {
                		                var lastestResponse=response;
		                                timedelay1=timedelay;
						if(lastestResponse==importFileCounter)
							imageStatusCounterForNoIncrement+=1;
						else
							imageStatusCounterForNoIncrement=0;

                		                while(lastestResponse>importFileCounter)
                                		{
			                                lastestResponse--;
                        		                setTimeout('updateFileCounter(totalImagesImported)',timedelay1);
                                        		timedelay1=timedelay1+timedelay;
		                                }
			                 }
					/*
					var percentVal = (importFileCounter*100)/totalImagesImported;
					var p=percentVal+'%';
					document.getElementById("progress_bar").style.width=p;
					*/
					timedelay1=timedelay1+timedelay+timedelay;
					if(timedelay1<minimumTimeDelay)
						timedelay1=minimumTimeDelay;
					if(totalImagesImported==response)
					{
						timedelay1+=3000;
						setTimeout('callSaveImage(totalImagesImported)',timedelay1);
					}
					else
					{
						imageStatusCounterForNoIncrement++;
						if(imageStatusCounterForNoIncrement==3)
							timedelay1=3*timedelay1;
						else if(imageStatusCounterForNoIncrement>3)
							timedelay1=4*timedelay1;
						if(imageStatusCounterForNoIncrement>=10)
						{
							if(importFileCounter==0)
								window.location = "/social/importFailed/~if $importSite eq facebook`facebook ~elseif $importSite eq flickr`flickr ~elseif $importSite eq picasa`picasa ~/if`";
							else
							{
								var total=totalImagesImported;
								var successCount=importFileCounter;
								var getparam="err="+successCount+"&total="+total+"&successCount="+successCount;
								window.location = '/social/saveImage?'+getparam+"&importSite=~$importSite`";
							}
						}
						setTimeout('checkImageStatus()',timedelay1);
					}
			        }
                        },
                        error: function(xhr) 
                        {
                                show_ajax_connectionErrorLayer();
                        }       
                });

	}
	else
	{
                $.ajax(
                {
                        url: "/social/saveImportImages",
                        data: "saveImage="+variable+"&ajaxRequest=1&importSite=~if $importSite eq facebook`facebook ~elseif $importSite eq flickr`flickr ~else`picasa ~/if`",
                        success: function(response) 
                        {
				if(response=='Done')
					setTimeout('checkImageStatus()',5000);
				if(response=='userTimedOut')
					show_loggedIn_window();	
                        },
                        error: function(xhr) 
                        {
				show_ajax_connectionErrorLayer();
                        }       
                });

	}
}

getCheckboxByValue();
//getLargePics();
limitExceeded();
checkLimit('~$limit`','0');
</script>
