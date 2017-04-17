~include_partial('global/header')`
 <br>
 
~if $noProfileFound`
<div align="center" ><b>No More Profiles to be Screened. Please try after some time. </b> </div>
~elseif $alreadyAlloted`
<div align="center" ><b>This profile is under screening by a screening user in last 30minutes. </b> </div>
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
~if $photoArr['screened']`
        ~include_partial("screenedCrousel",["screened"=>$photoArr['screened']])`
~/if`
<br/>
 <form name="list" id="ScreenForm" enctype="multipart/form-data"  action="~sfConfig::get('app_site_url')`~$imageCopyServer`/operations.php/photoScreening/uploadScreeningAction?cid=~$cid`"  method="POST">

   <input type=hidden name="profileid" value="~$profileData['PROFILEID']`">
   <input type = "hidden" name= "emailAdd" value = "~$profileData['EMAIL']`">
   <input type=hidden name="source" value="~$source`">
   <input type=hidden name="cid" value="~$cid`">
   <input type=hidden name="pictureIDs" value="~$photoArr['pictureIDs']`">
   <input type=hidden name="screenedPictureIDs" value="~$photoArr['screenedPictureIDs']`">
   <input type=hidden name="username" value="~$name`">
   <input type = "hidden" name = "titlePicIdString" value = "~$titlePicIdStr`">
   ~if $photoArr['screenedProfilePicId'] neq ''`
		<input type = "hidden" name = "screenedProfilePicId" value = "~$photoArr['screenedProfilePicId']`">
   ~else`
		<input type = "hidden" name = "screenedProfilePicId" value = "~$screenedProfilePicId`">
   ~/if`
   <input type="hidden" name="havePhotoValue" value="~$profileData['HAVEPHOTO']`">
   

   
   <table width=760 align="CENTER" cellspacing="0px;">
        <tr class="formhead topDetails" style="background:#EFEFD3;">
	<td>Username : ~$profileData['USERNAME']`</td>
	<td>Gender : <font class="red" style="font-size:16px;" >~$profileData['GENDER']` (~$profileData['AGE']`)</font></td>
	</tr>  
        
        <tr class="deleteReasonShow" id="deleteReasonsArea">
            <td>
                <table>
                    <tr> <td colspan="2">Please select reason for picture deletion - <br></td>
                    </tr>

                    ~foreach from=PictureStaticVariablesEnum::$DELETE_REASONS item=valueReason key=keyReason`
                    ~assign var = "tabIndex" value = $tabIndex+1`	   
                    <tr class="deleteReasonShow" style="display: none;">
                        <td><input name="deleteReason[]" class="deleteReasonShow" id="deleteReasons~$keyReason`" value='~$keyReason`' type="checkbox" tabIndex="~$tabIndex`"></td><td> ~$valueReason`<br></td>
                    </tr>

                    ~/foreach`
                    <tr>
                        <td colspan="2">
                            <span style="color: red;">*Alteast 1 reason is required</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>    
        
        <tr >
            <td>  &nbsp;&nbsp;</td>
	</tr>         

        ~assign var = "tabIndex" value = 0`
        ~assign var = "radioCount" value = 1`
	~if $photoArr['profilePic']['mainPicUrl']`
        <tr class="deleteReasonHide">
            <td colspan="2" id="profilePicHead">
                Profile Picture
            </td>
        </tr>                   
        <tr class="deleteReasonHide" >
            <td class="picShow underScreening" id="MainProfilePic">
        <center><img src="~$photoArr['profilePic']['mainPicUrl']['url']`" id="IMG~$photoArr['profilePic']['pictureId']`" class="album-frame" style="max-width:500px;"></img></center></td>
        
            <td  id="MainProfilePicOptions">
                <span class="formhead inTableRow">Profile Picture</span><br/><br/><br/>
                <input type="hidden" name="titleNonScr_~$photoArr['profilePic']['pictureId']`"  value="~$photoArr['profilePic']['mainPicUrl']['title']`" maxlength='30'  tabIndex="~$tabIndex`" ><br><br>
                <input name="profilePic_~$photoArr['profilePic']['pictureId']`"  onclick="ProfileMainAction('approve');" class="profileMain approve" id="profilePicApprove" ~if $photoArr['profilePic']['mainPicUrl']['bit'] eq '2'` checked="true" ~/if` value='APPROVE' type="radio" tabIndex="~$tabIndex`"> Approve<br/>
                <input name="profilePic_~$photoArr['profilePic']['pictureId']`"  onclick="ProfileMainAction('delete');" class="profileMain delete" id="profilePicDelete" value='DELETE' type="radio" tabIndex="~$tabIndex`"> Delete<br/>
                <input name="profilePic_~$photoArr['profilePic']['pictureId']`"  onclick="ProfileMainAction('mainEdit');" class="profileMain edit" onformchange="" id="profilePicEdit" value='EDIT' type="radio" tabIndex="~$tabIndex`"> Send this for Edit<br><br>
                <input type="hidden" name="screenBit_~$photoArr['profilePic']['pictureId']`" value="~$photoArr['profilePic']['mainPicUrl']['bit']`">
                ~if $photoArr['OldProfilePicPresent'] neq '1'`
					<input type="radio" checked="true" value="~$photoArr['profilePic']['pictureId']`" id="profile~$incCounter`" name="set_profile_pic">Set as Profile Picture<br>
				~else`
					<input type="radio" style="display:none" checked="true" value="~$photoArr['profilePic']['pictureId']`" id="profile~$incCounter`" name="set_profile_pic"><br>
					<input type="radio" value="RETAIN" id="profilePicRetain" class="profileMain retain" name="profilePic_~$photoArr['profilePic']['pictureId']`"  tabIndex="~$tabIndex`" onclick="ProfileMainAction('retain');">Retain as Album Picture<br>
				~/if`
                <input type="hidden" name="rotate_~$photoArr['profilePic']['pictureId']`"  value="0">
                ~if $photoArr['profilePic']['mainPicUrl']['localpath']`
                    <img src="~sfConfig::get('app_img_url')`/images/left-rotate.png" onclick=rotateImg('~$photoArr['profilePic']['pictureId']`','left') >
                    <img src="~sfConfig::get('app_img_url')`/images/right-rotate.png" onclick=rotateImg('~$photoArr['profilePic']['pictureId']`','right') >
                ~/if`
                <br><input type="checkbox" checked="true" value="~$photoArr['profilePic']['pictureId']`" name="watermark[]">Add Watermark<br>
            </td>
   </tr>                    
        ~assign var = "tabIndex" value = tabIndex+1`

        ~foreach from=$photoArr['profilePic']['profileType'] item=picData key=type`
		~if $picData['url']`
                <tr id = "profilephotoBlock" align="CENTER" class="fieldsnew depends underScreening">
			<td style="border-left: solid 4px #B0CBE2;">	
				<img src="~$picData['url']`" height="~$picData['h']`px" width="~$picData['w']`px" style="border-bottom: 15px solid #FFF;"></img>
			</td>
			
			<td  style="border-right: solid 4px #B0CBE2; width:210px;" id="profilephoto" align="left">
				Profile photo (~$picData['w']`x~$picData['h']` size)<br><br>	
                                <input name="~$type`" id="~$type`Approve"  class="approve ProfileType" value='APPROVE' type="radio" ~if $picData['bit'] eq '2'` checked="true" ~/if` tabIndex="~$tabIndex`"> Approve
                                <input name="~$type`" id="~$type`Delete"  class="deleteType ProfileType" value='DELETE' type="radio" style="display:none;">
                                <input name="~$type`" id="~$type`Edit"  class="edit ProfileType" value='EDIT' type="radio" tabIndex="~$tabIndex`"> Send for Edit
                                <input type="hidden" name="screenBit_~$type`" value="~$picData['bit']`">
                                <br><br><input type="checkbox" checked="true" value="1" name="watermark[watermarkOnType][~$type`]">Add Watermark<br>
                                ~assign var = "tabIndex" value = $tabIndex+1`
			</td>
		</tr>
                <tr  class="deleteReasonHide"><td colspan="2" class="depends" id="leftRightBorders"><br/></td>
                </tr>
                ~/if`
                ~/foreach`
                    <tr  class="deleteReasonHide">
                        <td colspan="2" style="border-top:solid 4px #B0CBE2;"><br/></td>
                    </tr>
                ~/if`
                ~if $photoArr['nonScreened']`
                    <tr  class="deleteReasonHide">
                        <td colspan="2" style="background: #00C300; padding:5px;">Non Screened Album Photos</td>
                    </tr>
                ~assign var = "picNumber" value =0`
                ~foreach from=$photoArr["nonScreened"] item=picData key=pictureID`
                ~assign var = "picNumber" value =$picNumber+1`     
                <tr align="CENTER" class="fieldsnew underScreening">
			<td>	
					<img src="~$picData['url']`" id="IMG~$pictureID`" height="200" width="150"></img>
			</td>

			<td align="left" id="photo~$picNumber`">
				<span style="color:blue;font-size:15px"><b>Photo ~$picNumber`</b></span> <br><br>

					<input type="hidden" name="titleNonScr_~$pictureID`"  tabIndex="~$tabIndex`" value="~$picData['title']`" maxlength='30' ><br><br>
					<input name="albumPic_~$pictureID`" id="profpic" class="approve" value='APPROVE' type="radio" ~if $picData['bit'] eq '2'` checked="true" ~/if` tabIndex="~$tabIndex`"> Approve<br/>
                                        <input name="albumPic_~$pictureID`" class="profileMain delete" id="profpic" value='DELETE' type="radio" tabIndex="~$tabIndex`"> Delete<br/>
                                        <input name="albumPic_~$pictureID`" class="profileMain edit" id="profpic" value='EDIT' type="radio" tabIndex="~$tabIndex`"> Send this for Edit<br/><br/>
                                        <input type="hidden" name="screenBit_~$pictureID`" value="~$picData['bit']`">
                                       
											~if $photoArr['profilePic']['mainPicUrl'] || $source eq 'master'`
						                                            ~assign var = "isProfilePic" value=1`
						                                              ~if $photoArr['OldProfilePicPresent'] neq '1'`
						                                                <input type="radio" value='~$pictureID`' id="profile~$incCounter`" name="set_profile_pic">Set as Profile Picture<br><br>
						                                              ~else`
																		<input type="radio" style= "display:none" value='~$pictureID`' id="profile~$incCounter`" name="set_profile_pic"><br><br>
																	 ~/if`
						                                        ~else`
						                                            ~assign var = "isProfilePic" value=0`
						                                        ~/if`
						                 
                        ~assign var = "tabIndex" value = $tabIndex+1`		
				<input type="hidden" name="rotate_~$pictureID`"  value="0">
                                ~if $photoArr['nonScreened'][~$pictureID`]['localpath']`
                                        <img src="~sfConfig::get('app_img_url')`/images/left-rotate.png" onclick=rotateImg('~$pictureID`','left') >
                                        <img src="~sfConfig::get('app_img_url')`/images/right-rotate.png" onclick=rotateImg('~$pictureID`','right') ><br>
                                ~/if`
                <input type="checkbox" checked="true" value="~$pictureID`"  name="watermark[]">Add Watermark<br>
			</td>
		</tr>
                 <tr><td colspan="2" style=" background: #fff;"><br/></td>
                </tr>
                ~/foreach`
                ~/if`

	~if $search neq 1 && ($photoArr['nonScreened'] || $photoArr['profilePic'])`
        <tr class = "fieldsnew" align = "CENTER">
            <td colspan="2"><input type="submit" tabIndex="~$tabIndex`" name="Submit" id="formSubmit" class="formSubmitButton" onsubmit="formSubmit()" value="Submit"><br/><br/>&nbsp;&nbsp;&nbsp;
<font class="red" style="font-size:16px;" >~$profileData['GENDER']` (~$profileData['AGE']`)</font>
                <input class="deleteReasonHide" id="skipSubmit" type="submit" name="Skip" value="Skip">
            </td>
        </tr>    	
	~/if`
   </table>
   <br><br><br><br>
   <table width=760 align="CENTER" cellspacing="0px;">
       ~if $photoArr['screened']`
                    <tr  class="deleteReasonHide">				

                        <td colspan="2" style="background: #C30000; color: #fff; padding:5px;">Screened Album Photos</td>
                    </tr>
                ~assign var = "picNumber" value =0`
                ~foreach from=$photoArr["screened"] item=picData key=pictureID`
                ~assign var = "picNumber" value =$picNumber+1`     
                <tr align="CENTER" class="fieldsnew underScreening"><td>
					<img src="~$picData['url']`" height="200" width="150"></img>
			</td>

			<td align="left" id="photo~$picNumber`">
				<span style="color:blue;font-size:15px"><b>Screened photo ~$picNumber`</b></span> <br><br>
					~if $photoArr['OldProfilePicPresent'] neq '1'`
					<input name="screenedPicDelete[]" class="profileMain" id="screenedPicDelete" value='~$pictureID`' type="checkbox" tabIndex="~$tabIndex`"> Delete<br/><br/><br/><br/>
					~/if`		
                                        ~if $photoArr['profilePic']['mainPicUrl']  || $source eq 'master'`
                                            ~assign var = "isProfilePic" value=1`
                                             ~if $photoArr['OldProfilePicPresent'] neq '1'`
												<input type="radio" value='screened~$pictureID`' id="profile~$incCounter`" name="set_profile_pic">Set as Profile Picture<br><br>
											 ~else`
												<input type="radio" style="display:none" value='screened~$pictureID`' id="profile~$incCounter`" name="set_profile_pic"><br><br>
											 
											 ~/if`
                                        ~else`
                                            ~assign var = "isProfilePic" value=0`
                                        ~/if`
                            
                        		
				
			</td>
		</tr>
                 <tr><td colspan="2" style=" background: #fff;"><br/></td>
                </tr>
                ~/foreach`
                
                
                ~if $search neq 1`
                <tr class = "fieldsnew" align = "CENTER">
                    <td colspan="2"><input type="submit" tabIndex="~$tabIndex`" name="Submit" class="formSubmitButton" id="formSubmit" onsubmit="formSubmit()" value="Submit"><br/><br/>&nbsp;&nbsp;&nbsp;
                        <input class="deleteReasonHide" id="skipSubmit" type="submit" name="Skip" value="Skip">
                    </td>
                </tr>    	
                ~/if`
        ~/if`
   </table>
  </form>
   <div class="deleteReasonHide" id="allPicAction">
                <input name="profilePicAll" onclick="TotalMainAction('approve');" id="allPicApprove" value='APPROVEALL' type="radio" tabIndex="0"> Approve All<br>
                <input name="profilePicAll" onclick="TotalMainAction('delete');" class="profileMain" id="allPicDelete" value='DELETEALL' type="radio" tabIndex="0"> Delete All<br>
                <input name="profilePicAll" onclick="TotalMainAction('edit');" class="profileMain" id="allPicEdit" value='EDITALL' type="radio" tabIndex="0"> Send All for Edit
   </div>
   <div class="deleteReasonHide" id="countDisplay">
                ~$photoArr['screened']|count`
   </div>
~if !$photoArr['profilePic']['mainPicUrl'] && $source eq 'master'`
        ~assign var = "isProfilePic" value=0`
~/if`
<script>
    $(".formSubmitButton").click(function(event){
     var retainPic = $(":radio[value=RETAIN][id='profilePicRetain']:checked").size();
     var count=~$tabIndex+$isProfilePic`;
     if(retainPic==1)
     {
		var profilePicCount = $("td[id='profilephoto']").size();
		count = count - profilePicCount; // no of different sizes of image
	  }
       
    if($(":radio:checked").size()<count) {
    alert("Some of the Photos are not marked");
    event.preventDefault();return false;}
    
    else if($("input[value=DELETE]:checked").size()>0 && $("input[name='deleteReason[]']:checked").size()<1) {
    $(".underScreening").hide();
    $(".deleteReasonHide").hide();
    $(".deleteReasonShow").show();    
    $('#deleteReasons0').focus();
    event.preventDefault();
    return false;}
});
$(":input").change(function(event){
    
    var count = ~$photoArr['screened']|count`;
    var approveProfilePic = $(":radio[value=APPROVE][id='profilePicApprove']:checked").size();
    var approvePic = $(":radio[value=APPROVE][id='profpic']:checked").size();
    var editedProfilePic = $(":radio[value=EDIT][id='profilePicEdit']:checked").size();
    var editedPic = $(":radio[value=EDIT][id='profpic']:checked").size();
    var retainPic = $(":radio[value=RETAIN][id='profilePicRetain']:checked").size();
    var deletedPics = $(":checkbox[class='profileMain']:checked").size();
    
    var newCount=parseInt(count)+editedProfilePic+editedPic+approveProfilePic+approvePic+retainPic-deletedPics;
    $("#countDisplay").text(newCount);
    if(newCount<20)
        $(":input[type='submit']").show();
    else if(newCount==20)
        alert("Max Count Reached, no new photos can be approved.");
    else{
        alert("Max Count Reached, DELETE some Photos");
        $(":input[type='submit']").hide();
    }   
        
    
});

function rotateImg(pictureId,direction){
 if(direction=="right")
     degree = parseInt($(":input[name='rotate_"+pictureId+"']").val())+90;
 if(direction=="left")
     degree = parseInt($(":input[name='rotate_"+pictureId+"']").val())-90;
 
 $(":input[name='rotate_"+pictureId+"']").val(degree);
 $("#IMG"+pictureId).css({
'-webkit-transform': 'rotate(' + degree + 'deg)',
'-moz-transform': 'rotate(' + degree + 'deg)',
'-o-transform': 'rotate(' + degree + 'deg)',
'-ms-transform': 'rotate(' + degree + 'deg)',
'transform': 'rotate(' + degree + 'deg)'
});
}
    </script>
~/if`
~include_partial('global/footer')`
 </body>
