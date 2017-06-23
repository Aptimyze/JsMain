~include_partial('global/header')`
 <br>
 
~if $noProfileFound`
<div align="center" ><b>No More Profiles to be Screened. Please try after some time. </b> </div>
~elseif $alreadyAlloted`
<div align="center" ><b>This profile is under screening by a screening user in last 30minutes. </b> </div>
~elseif $noPhotosFound`
<div align="center" ><b>This profile has no photos to be screened. </b> </div>
~else`
 <form name="list" id="ScreenForm" enctype="multipart/form-data"  action="~sfConfig::get('app_site_url')`~$imageCopyServer`/operations.php/screening/uploadScreenDocument?cid=~$cid`"  method="POST">

   <input type=hidden name="profileid" value="~$profileid`">
   <input type=hidden name="source" value="~$source`">
   <input type=hidden name="cid" value="~$cid`">
   <input type=hidden name="username" value="~$name`">   
   <input type=hidden name="docPath" value="~$documentPath`">   
   <input type=hidden name="prevMstatus" value="~$prevMstatus`">   

   
   <table width=760 align="CENTER" cellspacing="0px;">
        <tr class="formhead topDetails" style="background:#EFEFD3;">
	<td colspan="2">Username : ~$username`</td>
	</tr>
        <tr>
<td>  &nbsp;&nbsp;</td>
</tr>
        <tr id = "profilephotoBlock" align="CENTER" class="fieldsnew depends underScreening">
                <td style="border-left: solid 4px #B0CBE2; border-top: solid 4px #B0CBE2;">
                        ~if $contentType eq "pdf"`
                                Click <a href="~$documentURL`" download>Here</a> to download file for screening.
                        ~else`
                        <img src="~$documentURL`" height="300px" width="200px" style="border-bottom: 15px solid #FFF;"></img>
                        ~/if`
                </td>

                <td  style="border-right: solid 4px #B0CBE2;border-top: solid 4px #B0CBE2; width:210px;" id="profilephoto" align="left">
                        <input name="docVerified" id="Approve"  class="approve ProfileType" value='APPROVE' type="radio" required> Approve
                        <input name="docVerified" id="Delete"  class="deleteType ProfileType" value='Decline' type="radio" required> Decline
                </td>
        </tr>
        <tr class="deleteReasonHide">
<td style="border-top:solid 4px #B0CBE2;" colspan="2"><br></td>
</tr>
        <tr class = "fieldsnew" align = "CENTER">
            <td colspan="2"><input type="submit" name="Submit" id="formSubmit" class="formSubmitButton" onsubmit="formSubmit()" value="Submit">
            </td>
        </tr>    
   </table>
  </form>
~/if`
~include_partial('global/footer')`
 </body>
