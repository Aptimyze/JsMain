~include_partial('global/header')`

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<br />
<form action="~sfConfig::get('app_site_url')`/operations.php/photoScreening/sendMail?cid=~$cid`" method="post" onsubmit = "return checkComment();">

<!-- carry on variables for screening-->
<input type="hidden" name=cid value="~$cid`">
<input type="hidden" name="profileid" value="~$profileid`">
<input type="hidden" name="source" value="~$source`">
<input type="hidden" name="username" value="~$username`">
<input type="hidden" name="deletedPhotos" value="~$deletedPhotos`">
<input type="hidden" name="approvedPhotos" value="~$approvedPhotos`">
<input type="hidden" name="totalPhotos" value="~$totalPhotos`">
<input type="hidden" name="actualCountOfPics" value="~$actualCountOfPics`">
<input type="hidden" name="havePhotoValue" value="~$userType`">
<input type="hidden" name="emailAdd" value="~$emailAdd`">
<!-- carry on variables for screening-->
<table align = "center" width = "700px">
~if $errMessage`
<tr><td><p style = "color: red">You did not selected any reason for deletion.</p></td></tr>
~/if`
<tr><td><p>Please select atleast one reason for deletion of photos.</p></td></tr>
<tr><td><br /></td></tr>
<tr><td><input type = "checkbox" name = "deleteReason[]" value = "the photo is not clear" id = "reason1">The photo is not clear.</td></tr>
<tr><td><br /></td></tr>
<tr><td><input type = "checkbox" name = "deleteReason[]" value = "we find that the photo you have submitted is inappropriate" id = "reason2">We find that the photo you have submitted is inappropriate.<br /></td></tr>
<tr><td><br /></td></tr>
<tr><td><input type = "checkbox" name = "deleteReason[]" value = "the photo is of a well known personality. If the photo is yours then submit a proof of identity" id = "reason3">The photo is of a well known personality. If the photo is yours then submit a proof of identity.<br /></td></tr>
<tr><td><br /></td></tr>
<tr><td><center><input type = "submit" value = "SUBMIT"></center></td></tr>
</table>
</form>
</body>
<script>
function checkComment()
{
	if (document.getElementById("reason1").checked || document.getElementById("reason2").checked || document.getElementById("reason3").checked)
		return true;
	else
	{
		alert("Please select atleast 1 reason");
		return false;
	}
}
</script>
~include_partial('global/footer')`
