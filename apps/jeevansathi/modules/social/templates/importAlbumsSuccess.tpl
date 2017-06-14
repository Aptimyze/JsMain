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

<form name=list>
<div id="loading1" name="lll" >
<span id="loading" style="display:none" >
<img src='~sfConfig::get("app_img_url")`/images/loading.gif' >
</span>
</div>
<div id="fullblock">
~if !$noAlbumsError`
<div style="width:780px;">
<span style="float:right;">
	<a href="~sfConfig::get('app_site_url')`/social/loadingLayer/~if $importSite eq facebook`facebook~elseif $importSite eq flickr`flickr~else`picasa~/if`?fromAlbumPage=1" id="submit_button_thickbox2" class="naukri_btnup15 sprteup layerTag" onClick="return SetCookie('import_aid_~$importSite`','1');">&nbsp;</a>
	<a href="" id="submit_button2" style="display:none" class="naukri_btnup15 sprteup" onClick="return SetCookie('import_aid_~$importSite`','1');">&nbsp;</a>
</span>
<span style="margin: 0pt 10px;font-size: 150%; color: black;">Select albums to Import to Jeevansathi.com</span>
<br>
<span style="margin: 0px 10px;font-size: 130%; color: #898989;">~sfConfig::get("app_photo_formats")` | upto ~sfConfig::get("app_max_photo_size")`&nbsp;MB | ~$limit` more ~if $limit eq 1` photo ~else` photos ~/if` allowed
</span>
</div>

<p class="clr_4"></p>
<p class="clr_4"></p>
<!--error starts here -->
<div id="error_block" class="ylerror no_b" style="line-height:27px;display:none;font-size:16px;">
<div class="naukri_btnup9 sprteup fl" style="margin:0px 12px 4px 12px;"></div>
<span id="error1"> </span> <br>
<p class="clr_2"></p>
</div>
<p class="clr_4"></p>
<!--error ends here -->

<div align="right" style="padding-right:180px;" >
<b>
<a class="gry" id="sel1" >Select all</a>
<a href="#" id="sel2" onclick="selectAll()" style="display:none">Select all</a>
<a color="blue" >&nbsp;|&nbsp;</a>
<a class="gry" id="desel1" style="display:none">Deselect all</a>
<a href="#" id="desel2" onclick="deselectAll()" >Deselect all</a>
</b>
</div>
~/if`
<!--upload starts here -->
~if $noAlbumsError` <h1> ~$noAlbumsError`<br><br>To add photos from another source <a TARGET="_top" href='~sfConfig::get("app_site_url")`/social/addPhotos'>Click here</a> </h1> ~/if`
~foreach from=$photo_array item=pri key=k`
~if $k mod 4 eq 0` <table ><tr> ~/if`
<td valign="bottom" align="left" style="margin:0 20px 0 20px;" width="200">
<div class="fl" style="padding-top:16px;"><img style="border: 1px solid rgb(204, 204, 204); padding: 5px;" src="~$pri`" ~if $importSite eq flickr`width="130"~/if`>
	<br>
	<div style=" padding-top:16px;"> 
		<input name=~$albumIdArr[$k]` type="hidden"> 
		<p class="fl" style="float:left;">
			<input name="albumid" type="checkbox" value="~$photosCountInAlbum[$k]`#~$albumIdArr[$k]`" checked="checked"   onClick= "checkedBoxes(~$k`);">
		</p>
		<p class="fl"> &nbsp;
			<div style="width:150px;float:left;" > ~$albumNameArr[$k]`<br>
			<span style="color:#898989;">~$photosCountInAlbum[$k]` ~if ~$photosCountInAlbum[$k]` gt 1`Photos~else`Photo~/if`</span>
			</div>  
		</p>
	</div>
</div>
</td>
~if $k mod 4 eq 3 || $k eq $noOfAlbums1`</tr> </table> ~/if`
~/foreach`

<br><br>
~if !$noAlbumsError`
<div style="font-size:16px;" align="center"> 
<!--<b><span id="alb_chck" style="display:bold" value="~$noOfAlbums`" name="~$noOfAlbums`" >~$noOfAlbums`&nbsp;</span> ~if $noOfAlbums gt 1`albums~else`album~/if` selected </b><br>-->
<br>
<a href="~sfConfig::get('app_site_url')`/social/loadingLayer/~if $importSite eq facebook`facebook ~elseif $importSite eq flickr`flickr ~else`picasa ~/if`" id="submit_button_thickbox" class="naukri_btnup15 sprteup layerTag" onClick="return SetCookie('import_aid_~$importSite`','1');">&nbsp;</a>
<a href="" id="submit_button" style="display:none" class="naukri_btnup15 sprteup" onClick="return SetCookie('import_aid_~$importSite`','1');">&nbsp;</a>
~/if`
<p class=" clr_2"></p>
<br>

</div>
<!-- upload ends here -->
</div>
</form>

 <!--right part strat here-->
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
<script language="javascript">
var album=null;

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

function SetCookie(c_name,expiredays)
{
	album=null;
	var i=0;
	var j=0;
	var cookieValue;

	var exdate=new Date()
	exdate.setMinutes ( exdate.getMinutes() + 60*24 );
        expiredays=1;

	while(document.list.albumid[i])
	{
		if(document.list.albumid[i].checked)
		{
			j++;
//		        var exdate=new Date();
//		        exdate.setDate(exdate.getDate()+expiredays);
			if(album==null)
				album=document.list.albumid[i].value;
			else
				album=album+"|"+document.list.albumid[i].value;
		}
		/*
		if(j==0)
		document.cookie = c_name + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
		*/
		i++;
	}


	if(document.list.albumid[0]&&j)
	        document.cookie=c_name+ "=" +album+"@1"+((expiredays==null) ? "" : ";expires="+exdate)+";path=/";

	if(document.list.albumid.checked)
	{
		j++;
//		var exdate=new Date()
//		exdate.setDate(exdate.getDate()+expiredays)
			album=document.list.albumid.value;

		document.cookie=c_name+ "=" +album+"@1"+
		((expiredays==null) ? "" : ";expires="+exdate)+";path=/";
	}

	if(j==0)
	{
		document.getElementById("error1").innerHTML="Please select atleast one album to continue to the next page.";
		document.getElementById("error_block").style.display="block";
		document.getElementById("error_block").scrollIntoView();
		return false;
	}
	else
	{
		window.scroll(100,100);
		return true;
	}
}

function selectAll()
{
	document.getElementById("error_block").style.display="none";
	var i=0;

	while(document.list.albumid[i])
	{
		document.list.albumid[i].checked=true;
		i++;
	}

	document.list.albumid.checked=true;
	document.getElementById("sel1").style.display="";
	document.getElementById("sel2").style.display="none";
	document.getElementById("desel1").style.display="none";
	document.getElementById("desel2").style.display="";
	document.getElementById("submit_button_thickbox").style.display="block";
	document.getElementById("submit_button").style.display="none";
	document.getElementById("submit_button_thickbox2").style.display="block";
	document.getElementById("submit_button2").style.display="none";
}

function deselectAll()
{
	var i=0;

	while(document.list.albumid[i])
	{
		document.list.albumid[i].checked=false;
		i++;
	}

	document.list.albumid.checked=false;;

	document.getElementById("sel1").style.display="none";
	document.getElementById("sel2").style.display="";
	document.getElementById("desel1").style.display="";
	document.getElementById("desel2").style.display="none";
	document.getElementById("submit_button").style.display="block";
	document.getElementById("submit_button_thickbox").style.display="none";
	document.getElementById("submit_button2").style.display="block";
	document.getElementById("submit_button_thickbox2").style.display="none";
}

function checkedBoxes(k)
{
	var i=0;
	var j=0;

	if(document.list.albumid[i])
	{
		while(document.list.albumid[i])
		{
			if(document.list.albumid[i].checked)
				j++;
			i++;
		}
	}
	else if(document.list.albumid.checked)
	{
		i++;
		j++;
	}

//	document.getElementById("alb_chck").innerHTML=j;
	if(j==0)
	{
		document.getElementById("desel1").style.display="";
		document.getElementById("desel2").style.display="none";
		document.getElementById("submit_button").style.display="block";
		document.getElementById("submit_button_thickbox").style.display="none";
		document.getElementById("submit_button2").style.display="block";
		document.getElementById("submit_button_thickbox2").style.display="none";
	}
	else
	{
		document.getElementById("sel1").style.display="none";
		document.getElementById("sel2").style.display="";
		document.getElementById("desel1").style.display="none";
		document.getElementById("desel2").style.display="";
		document.getElementById("submit_button_thickbox").style.display="block";
		document.getElementById("submit_button").style.display="none";
		document.getElementById("submit_button_thickbox2").style.display="block";
		document.getElementById("submit_button2").style.display="none";
	}
	if(i==j)
	{
		document.getElementById("sel1").style.display="";
		document.getElementById("sel2").style.display="none";
	}
		
	if(j)
		document.getElementById("error_block").style.display="none";

}

function limitExceeded()
{
        if(~$limit` <= 0)
        {
                window.location = "~sfConfig::get('app_site_url')`/social/addPhotos";
        }
}

limitExceeded();
</script>
