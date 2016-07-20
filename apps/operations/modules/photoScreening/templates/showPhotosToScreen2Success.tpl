~include_partial('global/header')`
 <br>
 <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
~if $noProfileFound`
<div align="center" ><b>No More Profiles to be Screened. Please try after some time. </b> </div>
~elseif $noPhotosFound`
<div align="center" ><b>This profile has no photos to be screened. </b> </div>
~else`
	~if $search neq 1`
		<table width="600" border="1" cellspacing="1" cellpadding='3' ALIGN="CENTER" >
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
<br>
</br>
 <form name="list" enctype="multipart/form-data" action="~sfConfig::get('app_site_url')`~$imageCopyServer`/operations.php/photoScreening/uploadScreeningAction?cid=~$cid`" method="POST">
 
   <input type=hidden name="profileid" value="~$profileData['PROFILEID']`">
   <input type = "hidden" name= "emailAdd" value = "~$profileData['EMAIL']`">
   <input type=hidden name="source" value="~$source`">
   <input type=hidden name="cid" value="~$cid`">
   <input type=hidden name="mailid" value="~$mailid`">
   <input type=hidden name="username" value="~$name`">
   <input type=hidden name="mail" value="~$mail`">
   <input type = "hidden" name = "titlePicIdString" value = "~$titlePicIdStr`">
   <input type = "hidden" name = "screenedProfilePicId" value = "~$screenedProfilePicId`">
   <input type="hidden" name="havePhotoValue" value="~$profileData['HAVEPHOTO']`">
   <table width=760 align="CENTER" cellspacing="25" >

	<tr class="formhead" ~if $profileData['USERPAID'] eq '1'` style="background:#00FF00;"~/if`>
	<td>Username : ~$profileData['USERNAME']`</td>
	<td>Gender : <font class="red">~$profileData['GENDER']`</font></td>
	</tr>    	

	~assign var = "tabIndex" value = 1`
	~if $search neq 1`
		<tr id = "profilephotoBlock" align="CENTER" class="fieldsnew" ~if $nonscreenedProfilePicId eq ''` style="display:none;" ~/if`>
			<td>	
				~if $nonscreenedProfilePicture`
					<img src="~JsConstants::$localImageUrl`photo/photo_nonscreened_serve_locally.php?username=~$profileData['USERNAME']`&order=p&Imagefile=~$nonscreenedProfilePicture`" height="200" width="150"></img>
				~/if`
			</td>
			
			<td id="profilephoto" align="left">
				Profile photo (150x200 size)<br><br>	
				<input name="profilePic" id="profpic" value='nonscreenedProfilePicture' type="file" tabIndex="~$tabIndex`"><br><br>	
				~assign var = "tabIndex" value = $tabIndex+1`
			</td>
		</tr>
		<tr id = "thumbnailBlock" align="CENTER" class="fieldsnew" ~if $nonscreenedProfilePicId eq ''` style="display:none" ~/if`>
			<td>
				~if $nonscreenedThumbnail`
					<img src="~JsConstants::$localImageUrl`photo/photo_nonscreened_serve_locally.php?username=~$profileData['USERNAME']`&order=t&Imagefile=~$nonscreenedThumbnail`" height="60" width="60"></img>
				~/if`
			</td>
			
			<td id="thumbnail" align="left">
				Thumbnail (60x60 size)<br><br>
				<input name="thumbnail" id="thumb" value='nonscreenedThumbnail' type="file" tabIndex="~$tabIndex`"><br><br>	
				~assign var = "tabIndex" value = $tabIndex+1`
			</td>
		</tr>
	~/if`

~assign var = "photoCounter" value = 1`
	<tr align="center" ><td>~if $screenedPicId` <br><br><b> Screened Photos </b> <br>  ~/if`</td></tr>

	~foreach from=$screenedPicId item=photo key=k`
		<tr align="CENTER" class="fieldsnew">
			<td>	
				<img src="~$screenedPicUrl[$k]`" height="200" width="150"></img>
			</td>
			
			<td align="left" id='screenedCounter~$photoCounter`' >
				<span style="color:blue;font-size:15px"><b>Photo ~$photoCounter`</b></span> <br><br>
				~if $titlePicIdStr|contains:$photo` <b> Only Title to be screened </b> <br>  ~/if`<br><br>
				~if $mail eq 1 || $master eq 1 || $titlePicIdStr|contains:$photo`
					Title: <input type="text" name="titleScr[]" value="~$screenedTitle[$k]`" maxlength='30' ><br><br>
				~else`
					<input type="hidden" name="titleScr[]" value="~$screenedTitle[$k]`"><br><br>
					Title: ~$screenedTitle[$k]`<br><br>
				~/if`
				~if $mail eq 1 || $master eq 1`
					<input type="checkbox" value='~$photo`' name="deletePhotoScr[]">Delete<br><br>
					<input name="uploadPhotoScr[]" value='~$photo`' type="file" ~if $photo eq $screenedProfilePicId` id="scrProfilePicId" onChange="setProfilePicture('P');"~/if`><br><br>	      		       		
				~/if`
					<input type="hidden" value='~$photo`' name="picIdScr[]">
				~if $search neq 1`
				<input type="radio" value='~$photo`' name="set_profile_pic"  ~if $photo eq $screenedProfilePicId` ~if $nonscreenedProfilePicId eq ''` checked ~/if` onclick="setProfilePicture('P')" ~else` onclick="setProfilePicture('N')" ~/if` >Set as Profile Picture <br><br>
				~/if`
			</td>
		</tr>
		~if $photo eq $screenedProfilePicId`
				<tr align="CENTER" class="fieldsnew">
					<td>	
						<img src="~$screenedProfilePicture`" height="200" width="150"></img>
					</td>
					
					<td align="left">
						Profile photo (150x200 size)<br><br>	
					</td>
				</tr>
				<tr align="CENTER" class="fieldsnew">
					<td>
						<img src="~$screenedThumbnail`" height="60" width="60"></img>
					</td>
					
					<td align="left">
						Thumbnail (60x60 size)<br><br>
					</td>
				</tr>

		~/if`
~assign var = "photoCounter" value = $photoCounter+1`
	~/foreach`
	
~assign var = "incCounter" value = 0`
~if $nonscreenedPicId[0]` <input type="hidden" value="1" name="comp"> ~/if`

	~assign var = "tabIndexNon" value = 1`
	~foreach from=$nonscreenedPicId item=photo key=k`
		<tr align="center" ><td>~if $k eq 0` <br><br><b> Nonscreened Photos </b><br> ~/if`</td></tr>
		<tr align="CENTER" class="fieldsnew">
			<td>	
				~if $search eq 1`
					<img src="~$nonscreenedPicUrl[$k]`" height="200" width="150"></img>
				~else`
					<img src="~JsConstants::$localImageUrl`photo/photo_nonscreened_serve_locally.php?username=~$profileData['USERNAME']`&order=~$tabIndexNon`&Imagefile=~$nonscreenedPicUrl[$k]`" height="200" width="150"></img>
					 ~assign var = "tabIndexNon" value = $tabIndexNon+1`
				~/if`
			</td>

			<td align="left" id="photo~$incCounter`">
				<span style="color:blue;font-size:15px"><b>Photo ~$photoCounter`</b></span> <br><br>

				~if $search eq 1`
					Title: ~$nonscreenedTitle[$k]`<br><br>
				~else`
					Title: <input type="text" name="titleNonScr[]" value="~$nonscreenedTitle[$k]`" maxlength='30' ><br><br>
					<input type="checkbox" value='~$photo`' id="deletePhotoNonScr" name="deletePhotoNonScr[]">Delete<br><br>
					<!--<input name="photoupload~$incCounter`" id="photoupload~$incCounter`" value='~$photo`' type="file"><br><br>-->
					<input name="uploadPhotoNonScr[]" value='~$photo`' id="uploadPhotoNonScr" type="file" tabIndex="~$tabIndex`"><br><br>
					 ~assign var = "tabIndex" value = $tabIndex+1`
					<input type="radio" value='~$photo`' id="profile~$incCounter`" name="set_profile_pic" ~if $photo eq $nonscreenedProfilePicId` checked ~/if` onclick="setProfilePicture('N')" >Set as Profile Picture<br><br>
					<input type="hidden" value='~$photo`' name="picIdNonScr[]">
					<input type="hidden" value='~$nonscreenedKeywords[$k]`' name="keywordNonScr[]">
				~/if`

			</td>
		</tr>
		~if $search eq 1`
			~if $nonscreenedProfilePicId eq $photo`
				<tr id="profilephoto" align="CENTER" class="fieldsnew" >
					<td>	
						~if $nonscreenedProfilePicture`
							<img src="~$nonscreenedProfilePicture`" height="200" width="150"></img>
						~/if`
					</td>
					
					<td align="left">
						Profile photo (150x200 size)<br><br>	
					</td>
				</tr>
				<tr id="thumbnail" align="CENTER" class="fieldsnew" >
					<td>
						~if $nonscreenedThumbnail`
							<img src="~$nonscreenedThumbnail`" height="60" width="60"></img>
						~/if`
					</td>
					
					<td align="left">
						Thumbnail (60x60 size)<br><br>
					</td>
				</tr>
			~/if`
		~/if`
	~assign var = "photoCounter" value = $photoCounter+1`
	~assign var = "incCounter" value = $incCounter+1`
	~/foreach`
	
     <tr>

	~if $mail eq 1`

		~foreach from=$imageAttachments item=photo key=k`
			<tr align="center" ><td>~if $k eq 0` <br><br><b> Image Attachments </b><br> ~/if`</td></tr>
			<tr align="CENTER" class="fieldsnew">
				<td>
					<img src="~sfConfig::get('app_site_url')`/uploads/MailImages/~$photo`" height="200" width="150"></img>
				</td>

				 <td align="left" id="photo~$incCounter`" BGCOLOR=''>
					<span style="color:blue;font-size:15px"><b>Photo ~$photoCounter`</b></span> <br><br>
					Title: <input type="text" name="titleNonScr[]" value="" maxlength='30' ><br><br>
					<input type="checkbox" value='attach~$k`' name="deletePhotoNonScr[]">Delete<br><br>
					<input name="uploadPhotoNonScr[]" value='attach~$k`' type="file"><br><br>	      		       		
					<input type="radio" value='attach~$k`' id="profile~$incCounter`" name="set_profile_pic" onclick="setProfilePicture('N')" >Set as Profile Picture<br><br>
					<input type="hidden" value='attach~$k`' name="picIdNonScr[]">
					<input type="hidden" value='~$photo`' name="mailImagesNamesMapping[attach~$k`]">
				</td>
			</tr>
			~assign var = "incCounter" value = $incCounter+1`
			~assign var = "photoCounter" value = $photoCounter+1`
		~/foreach`
		 
		~foreach from=$appAttachments item=photo key=k`
			~if $k eq 0`<tr align="center" ><td> <br><br><b> Application Attachments </b><br> </td></tr>~/if`
			<tr align="CENTER" class="fieldsnew">
				<td>	
					<a href="~sfConfig::get('app_site_url')`/jsadmin/download.php?type=doc&f=~$photo`\">Download</a>
				</td>
				<td>	
					~$photo`
				</td>
			</tr>
		~/foreach`
	~/if`
	~if $mail eq 1 || $master eq 1`
		~if $addMore`
			~section name=foo loop=$addMore`
				<tr id="addMore~$smarty.section.foo.iteration`" align="CENTER" class="fieldsnew" style="display:none">
					<td>
						~if $smarty.section.foo.iteration neq $addMore`
							<b>
								<a id="add~$smarty.section.foo.iteration`" href="#" onclick=" ~assign var='counter' value= $smarty.section.foo.iteration+ 1`;document.getElementById('addMore~$counter`').style.display='';document.getElementById('add~$smarty.section.foo.iteration`').style.display='none';return false;" > Add More
								</a>
							</b>
						~/if`
					</td>
					<td align="left" id="photo~$incCounter`" BGCOLOR=''>
						<span style="color:blue;font-size:15px"><b>Photo ~$photoCounter`</b></span> <br><br>
						Title: <input type="text" name="titleNonScr[]" value="" maxlength='30' ><br><br>
						<input type="checkbox" value='addmore~$smarty.section.foo.iteration`' name="deletePhotoNonScr[]">Delete<br><br>
						<input name="uploadPhotoNonScr[]" value='addmore~$smarty.section.foo.iteration`' type="file"><br><br>	      		       	
						<input type="radio" value='addmore~$smarty.section.foo.iteration`' id="profile~$incCounter`" name="set_profile_pic" onclick="setProfilePicture('N')"  >Set as Profile Picture<br><br>
						<input type="hidden" value='addmore~$smarty.section.foo.iteration`' name="picIdNonScr[]">
					</td>
				</tr>
			~assign var = "incCounter" value = $incCounter+1`
			~assign var = "photoCounter" value = $photoCounter+1`
			~/section`
		
			<tr align="CENTER" class="fieldsnew">
				<td>
					<b>
						<a id="button1" href='#' onclick="document.getElementById('addMore1').style.display='';document.getElementById(this.id).style.display='none';return false;" >Add More</a>
					</b>
				</td>
				<td>
					&nbsp;
				</td>
			</tr>
		~/if`
	~/if`

	~if $search neq 1`
		<tr class = "fieldsnew" align = "CENTER">
		<td colspan="2"><input type="submit" tabIndex="~$tabIndex`" name="Submit" value="Upload" onclick="return checkIfAllSelected(~sfConfig::get('app_max_no_of_photos')`)">&nbsp;&nbsp;&nbsp;
		<input type="submit" name="Skip" value="Skip"></td>
		</tr>    	
	~/if`
   </table>
  </form>

~/if`
~include_partial('global/footer')`
 </body>
