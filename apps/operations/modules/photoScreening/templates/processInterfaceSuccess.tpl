<!DOCTYPE html>
<head>
<meta content="IE=edge" http-equiv="X-UA-Compatible">
<meta http-equiv="content-language" content="en" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="shortcut icon" href="/favicon1.ico" />
<link rel="stylesheet" async=true type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="/min/?f=/css/jspc/common/commonJspc_css.css,/css/jspc/photoUpload/photoup_css.css,/css/jspc/photoUpload/cropper.css,/css/jspc/photoUpload/main.css" />

<script type="text/javascript" language="Javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>

<body>

<br>
 <div align="center" ><b>PICTURE PROCESS INTERFACE</b> </div>

~if $noProfileFound`
	<div align="center" ><b>No More Profiles to be Screened. Please try after some time. </b> </div>
~elseif $noPhotosFound`
	<div align="center" ><b>This profile has no photos to be screened. </b> </div>
~else`
<form name="list" id="ScreenForm" enctype="multipart/form-data"  action="~JsConstants::$siteUrl`/operations.php/photoScreening/uploadProcessScreening?name=~$name`&cid=~$cid`&source=~$source`"  method="POST">
~include_partial('photoScreening/cropper',["uploadUrl"=>$uploadUrl,"photoArr"=>$photoArr,"profileData"=>$profileData,"search"=>$search])`
	~if $search neq 1`
		<table width="600" border="1" cellspacing="0" cellpadding='3' ALIGN="CENTER" >
		    <tr class=label align=center>
			<td width=10%>&nbsp;Gender</td>
			<td width=10%>&nbsp;Age</td>
			<td width=10%>&nbsp;Country</td>
			<td width=10%>&nbsp;City</td>
			<td width=10%>&nbsp;Marital Status</td>
			<td width=10%>&nbsp;Ethinicty (State of Origin)</td>
			<td width=10%>&nbsp;Religion</td>
			<td width=10%>&nbsp;Caste</td>
			<td width=10%>&nbsp;Country of Birth</td>
			<td width=10%>&nbsp;City of Birth</td>
		    </tr>
		
		    <tr class=fieldsnew align=center ~if $profileData['USERPAID'] eq '1'` style="background:#00FF00;"~/if`> 
			<td>&nbsp;~$profileData['GENDER']`</td>
			<td>&nbsp;~$profileData['AGE']`</td>
			<td>&nbsp;~$profileData['COUNTRY_RES']`</td>
			<td>&nbsp;~if $profileData['CITY_RES'] neq ''` ~$profileData['CITY_RES']` ~else` Outside India ~/if`</td>
			<td>&nbsp;~$profileData['MSTATUS']`</td>
			<td>&nbsp;~$profileData['MTONGUE']`</td>
			<td>&nbsp;~$profileData['RELIGION']`</td>
			<td>&nbsp;~$profileData['CASTE']`</td>
			<td>&nbsp;~$profileData['COUNTRY_BIRTH']`</td>
			<td>&nbsp;~if $profileData['CITY_BIRTH'] neq ''` ~$profileData['CITY_BIRTH']` ~else` Not Specified ~/if`</td>
		    </tr>
		</table>
	~/if`
<br/>
~if $photoArr['screened']`
        ~include_partial("screenedCrousel",["screened"=>$photoArr['screened']])`
~/if`
	<input type=hidden name="profileid" value="~$profileData['PROFILEID']`" id="profileid">
	<input type = "hidden" name= "emailAdd" value = "~$profileData['EMAIL']`">
	<input type=hidden name="source" value="~$source`">
	<input type=hidden name="cid" value="~$cid`">
	<input type=hidden name="profileid" value="~$profileid`">
	<input type=hidden name="mailid" value="~$mailid`">
	<input type=hidden name="pictureIDs" value="~$photoArr['pictureIDs']`">
	<input type=hidden name="username" value="~$name`">
	<input type=hidden name="mail" value="~$mail`">
	<input type = "hidden" name = "screenedProfilePicId" value = "~$screenedProfilePicId`">
	<input type="hidden" name="havePhotoValue" value="~$profileData['HAVEPHOTO']`">
	<input type="hidden" name="cropBoxDimensionsArr" value="" id="cropBoxDimensionsArr">
	<input type="hidden" name="imageSource" value="" id="imageSource">
	<input type="hidden" name="imgPreviewTypeArr" value="" id="imgPreviewTypeArr">
	<input type="hidden" name="ops" value=false id="ops">
	<input type="hidden" name="hideCropper" value="~$hideCropper`" id="hideCropper">
	<table width=760 align="CENTER" cellspacing="0px;">
        	~assign var = "tabIndex" value = 1`
        	<tr class="formhead topDetails" style="background:#EFEFD3;">
			<td colspan="2">Username : <span tabIndex="~$tabIndex++`" selected="selected">~$profileData['USERNAME']`</span></td>
			<td colspan="2">Gender : <font style="font-size:16px;" class="red">~$profileData['GENDER']` (~$profileData['AGE']`)</font></td>
		</tr>  
        
		<tr><td colspan="4" align="center" style="font-size: 12px; font-weight:bold;"><br><a id="goBackInterface" href="~JsConstants::$siteUrl`/operations.php/photoScreening/screen?name=~$name`&cid=~$cid`&source=~$source`&switchProfile=~$profileData['PROFILEID']`">Go Back To Accept / Reject Interface</a></br></td></tr>   
		~assign var = "picCount" value=1`
		~if $photoArr['profilePic']['profileType']|count neq 0 || $photoArr['profilePic']['mainPicUrl']`
			<tr><td colspan="4" style="height:14px;"></td><tr>
        		<tr>
            			<td colspan="4" style="font-size: 14px; font-weight: bold; background: #B0CBE2;">
                		Album Picture  ~$picCount` (Download for Cropping Profile Pics)
            			</td>
			<tr>
			<tr><td colspan="4" style="height:14px;"></td><tr>
			<tr id="ProfilePic">
				<td>
				<img src="~$uploadUrl`~$photoArr['profilePic']['OriginalProfilePicUrl']`" width="200" height="300" tabIndex="~$tabIndex++`"></img>
				</td>
				<td>
				<table>
					~foreach from=$photoArr['profilePic']['profileType'] key=key item=value name=attribute`  	
					<tr class="browse">
							<td>Cropped Pic (~$value["w"]` x ~$value["h"]`)</td>
							<td>
							<input name="uploadPhotoNonScr[~$key`]" id="browse~$key`" value='~$key`' type="file" tabIndex="~$tabIndex++`">
							</td>
							<td class="notUploaded" style="display:none">                                                                                                          <font color="red"> Please Browse!!</font>
                                                	</td>
							
					</tr>
					~/foreach`
					<tr><td> <input type="hidden" value='~$photoArr["profilePic"]["pictureId"]`' name="picIdNonScr[~$picCount`]"></td><tr>
					~if $photoArr['profilePic']['mainPicUrl']`
					<tr class="browse">
                                                <td>Main Pic (~$mainPicSize["w"]` x ~$mainPicSize["h"]`)</td>
                                                <td><input id="browseMainPic" name="uploadPhotoNonScr[~$picCount`]" value='mainPicUrl`' type="file" tabIndex="~$tabIndex++`">
							<br></br>
							<span class="notUploaded" style="display:none">                                                                                                          <font color="red"> Please Browse!!</font>
                                                        </span>
						</td>
					</tr>
					~/if`
				</table>
                                </td>
                        </tr>
		
				~assign var = "picCount" value=$picCount+1`	
		~/if`
		<tr><td colspan="4" style="height:14px;"></td><tr>
		~if $photoArr['nonScreened']`
			 <tr>
                                <td colspan="4" style="font-size: 14px; font-weight: bold; background: #B0CBE2;">
                                	Non Screened Album Pictures
                                </td>
                        <tr>
			<tr><td colspan="4" style="height:14px;"></td><tr>
			~foreach from=$photoArr['nonScreened'] key=key item=value name=attribute`
				<tr><td colspan="4" style="font-size: 14px; font-weight: bold;">Album Pic ~$picCount`</td></tr>
                                <tr id="Album~$picCount`">
                                        <br></br>
					<td>
        	                                <img src="~$uploadUrl`~$value['url']`" width="200" height="300" tabIndex="~$tabIndex++`"></img>
	                                </td>
                                        <td>
						<table>
                                                	<tr class="browse">
								<td>Cropped Album Pic (~$mainPicSize["w"]` x ~$mainPicSize["h"]`)</td>
                                        			<td>
									<input id="browse~$key`" value='~$key`' name="uploadPhotoNonScr[~$picCount`]" type="file" tabIndex="~$tabIndex++`">
									<input type="hidden" value='~$key`' name="picIdNonScr[~$picCount`]">
								</td>								
								<td class="notUploaded" style="display:none">
	                                              			<font color="red"> Please Browse!!</font>
                                        			</td>
							</tr>
						</table>
					</td>
					
                                </tr>
				<tr><td colspan="4" style="height:14px;"></td><tr>
				~assign var = "picCount" value=$picCount+1`	

                        ~/foreach`

		~/if`
		<tr align="center">
                                <td align="right"><input type="submit" id="submit" name="Submit" value="SUBMIT" tabIndex="~$tabIndex++`" >
				<td align="center"><input type="submit" id="skip" name="Skip" value="SKIP" tabIndex="~$tabIndex`" >
                </tr>
	</table>
</form>
~/if`
~include_partial('global/footer')`
<script>
        window.onload = function (){
		if($("#hideCropper").val()==true)
		{
			closeCropper();
		}
		$("#ScreenForm").submit(function(event)
                {
			var val = $("input[type=submit][clicked=true]").val();
			if(val =="SUBMIT")
			{
				var bool = true;
				var count = 0;
				$('tr.browse').each(function(){
					var id =$(this).find('input').attr('id'); 
					var filePath = $(this).find('input').val();
					if(filePath=="")
					{
						count++;
						$(this).find('.notUploaded').show();
					}
					
					}
				);
				if(count)
				{
					alert("Please upload  all Pictures!!");
					bool = false;
				}
				if(!bool)
                                 event.preventDefault();
			}
			else
			{
				sendOpsProcessCropperRequest();
			}

		});
		$("form input[type=submit]").click(function() {
   			 $("input[type=submit]", $(this).parents("form")).removeAttr("clicked");
    			$(this).attr("clicked", "true");
		});
	}
</script>
<script type="text/javascript" src="/min/?f=/js/jspc/common/commonJspc_js.js,/js/jspc/common/AjaxWrapper.js"></script>

<script type="text/javascript" src="/min/?f=/js/jspc/photoUpload/cropper.js,/js/jspc/photoUpload/main.js,/js/jspc/photoUpload/bootstrap.js,/js/jspc/photoUpload/tooltip.js"></script>
</body>
