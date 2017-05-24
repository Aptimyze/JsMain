<style>
.scrollbox1{background-color:#FDF4F5;border:1px solid #FDF4F5;overflow:auto;padding:8px 2px 4px 8px;width:500px; height:240px;}
.yellow{ color:#ffc000;}
</style>

<div class="pink" style="width:590px;height:510px;">
	<div class="topbg">
		<div class="lf pd b t12">~if $noOfPics eq 1` Full View ~else`Album View~/if` - ~$USERNAME`
		</div>
		<div class="rf pd b t12">
			<a href="#" class="blink" onClick="$.colorbox.close();return false;">
				Close [x]
			</a>
		</div>
	</div>
	<div class="clear">
	</div>

	~if $noAlbumToDisplay`
	     <div style="margin-top: 190px;font-size:25px;text-align:center;">
	     Album visible if contact accepted
	     </div>
	~else`

	<!--LEFT ARROW-->
	<div style="float:left;width:36px;margin-top:190px;">
		<a href="#" onclick="javascript:call_img('P')">
			<img src="~sfConfig::get('app_img_url')`/images/photo-layer.gif" style="border:0px;">
		</a>
	</div>

	<!--IMAGE CONTAINER-->
	<div class="scrollbox1" style="width:500px;height:400px;float:left;border:0px;">
		<div style="padding:5px; text-align:center;width:350px;height:300px;" id="show_img">
			<table height="370px" width="470px">
				<tr>
					<td valign='center'>
						<img id="change_img" src="" style="border: 2px solid rgb(0, 0, 0); max-width: 480px; max-height: 515px;" onload="javascript:set_image()" >
						<input id="imageNo" type="hidden" value="">
					</td>
				</tr>
			</table>
		</div>
		<div style="padding:5px; text-align:center;align=center;display:none" id="show_loader" >
			<img  src="~sfConfig::get('app_img_url')`/img_revamp/loader_big.gif" border=0>
		</div>
		<br>
	</div>

	<!--RIGHT ARROW-->
	<div style="float:right;width:36px;margin-top:190px;">
		<a href="#" onclick="javascript:call_img('N')">
			<img src="~sfConfig::get('app_img_url')`/images/photo-layer-r.gif" style="border:0px;">
		</a>
	</div>

	<div class="sp12" style="border:1px #F0CED6; border-top-style:solid">
	</div>

	~if $noOfPics gte '1'`
	<div  style="text-align:center;width:100%">
		<div align="left">
			<table width="430" border="0" align="left" cellpadding="2" cellspacing="2" style="_margin-left:10px;display:inline;" >
				<tr id="titleVal" style="display:none" >
					<td width="14%" align="left" valign="top">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
					</td>
					<td width="86%" align="left" valign="top" style="padding-left:2px;">
						<span id="title" > </span>
					</td>
				</tr>
				<tr id="noTitleVal" style="display:none" >
					<td width="14%" align="left" valign="top">
						<br>
					</td>
					<td width="86%" align="left" valign="top" style="padding-left:2px;">
					</td>
				</tr>

				<tr id="keywordVal" style="display:none" >
					<td align="left" valign="top">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Keywords&nbsp;:
					</td>
					<td align="left" valign="top" style="padding-left:2px;">
						<span id="keyword" > </span>
					</td>
				</tr>
				<tr id="noKeywordVal" style="display:none" >
					<td align="left" valign="top">
						<br>
					</td>
					<td align="left" valign="top" style="padding-left:10px;">
					</td>
				</tr>

			</table>
			
			<div style="font-size:15px;text-align:center;float:none;clear:both;" >
				<span id="photoNo" >1</span> of ~$noOfPics` ~if $noOfPics gt 1`&nbsp;photos~else`&nbsp;photo~/if`.
			</div>
		</div>
	</div>
	~/if`
	~/if`
</div>

<script>
	var first_img=0;
	var img_arr=new Array;
	var title_arr=new Array;
	var keyword_arr=new Array;

	var indexVal = 0;
	~foreach from = $sf_data->getRaw('mainPicUrls') item = photo key=k`
		img_arr[indexVal]="~$photo`";
		title_arr[indexVal]="~$titleArr[$k]`";
		keyword_arr[indexVal]="~$keywords[$k]`";
		indexVal = indexVal + 1;
	~/foreach`

	var image_id=document.getElementById("change_img");
	var show_imgid=document.getElementById("show_img");
	var show_loaderid=document.getElementById("show_loader");
//	var photo_titleid=document.getElementById("photo_title");

	image_id.src=img_arr[0];
	document.getElementById("imageNo").value='0';
//	photo_titleid.innerHTML=title_arr[0];
	document.getElementById("title").innerHTML=title_arr[0];
	if(title_arr[0])
	{
		document.getElementById("titleVal").style.display='';
		document.getElementById("noTitleVal").style.display='none';
	}
	else
	{
		document.getElementById("titleVal").style.display='none';
		document.getElementById("noTitleVal").style.display='';
	}
	document.getElementById("keyword").innerHTML=keyword_arr[0];
	if(keyword_arr[0])
	{
		document.getElementById("keywordVal").style.display='';
		document.getElementById("noKeywordVal").style.display='none';
	}
	else
	{
		document.getElementById("keywordVal").style.display='none';
		document.getElementById("noKeywordVal").style.display='';
	}

	function set_image()
	{
		show_loaderid.style.display='none';
		show_imgid.style.visibility='visible';
		show_imgid.style.height="";
		show_imgid.style.width=""
	}
	function check_img_loaded()
	{
		show_loaderid.style.display='block';
		show_imgid.style.visibility='hidden';
		show_imgid.style.height="50px";
		show_imgid.style.width="15px";
	}
	function call_img(goto_img)
	{
		var currentImage = document.getElementById("imageNo").value;

		if(goto_img == 'P' && currentImage == 0)
		{
			currentImage = ~$noOfPics` - 1;
		}
		else if (goto_img == 'N' && currentImage == (~$noOfPics` - 1))
		{
			currentImage = 0;
		}
		else
		{
			if(goto_img == 'P')
			{
				currentImage -- ;
			}
			else if(goto_img == 'N')
			{
				currentImage ++ ;
			}
		}

		first_img = currentImage;

		check_img_loaded();

		if(image_id)
		{
			image_id.src=img_arr[first_img];
			document.getElementById("imageNo").value=first_img;
//			photo_titleid.innerHTML=title_arr[first_img];
			document.getElementById('title').innerHTML=title_arr[first_img];
			document.getElementById('keyword').innerHTML=keyword_arr[first_img];
			var photoNumber = first_img + 1;
			document.getElementById('photoNo').innerHTML=photoNumber;
			if(title_arr[first_img])
			{
				document.getElementById("titleVal").style.display='';
				document.getElementById("noTitleVal").style.display='none';
			}
			else
			{
				document.getElementById("titleVal").style.display='none';
				document.getElementById("noTitleVal").style.display='';
			}
			if(keyword_arr[first_img])
			{
				document.getElementById("keywordVal").style.display='';
				document.getElementById("noKeywordVal").style.display='none';
			}
			else
			{
				document.getElementById("keywordVal").style.display='none';
				document.getElementById("noKeywordVal").style.display='';
			}
			if(~$noOfPics`==1)
				set_image();
		}
		
	}
	<!--

	//Disable right mouse click Script
	//By Maximus (maximus@nsimail.com) w/ mods by DynamicDrive
	//For full source code, visit http://www.dynamicdrive.com

	var message="Function Disabled!";

	///////////////////////////////////
	function clickIE4(){
	if (event.button==2){
	//alert(message);
	return false;
	}
	}

	function clickNS4(e){
	if (document.layers||document.getElementById&&!document.all){
	if (e.which==2||e.which==3){
	//alert(message);
	return false;
	}
	}
	}

	if (document.layers){
	document.captureEvents(Event.MOUSEDOWN);
	document.onmousedown=clickNS4;
	}
	else if (document.all&&!document.getElementById){
	document.onmousedown=clickIE4;
	}

	//document.oncontextmenu=new Function("alert(message);return false")
	document.oncontextmenu=new Function("return false")

	// -->

</script>
