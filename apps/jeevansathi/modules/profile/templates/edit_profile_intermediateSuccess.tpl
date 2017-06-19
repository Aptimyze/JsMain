<style>
.ln1{float:left; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#0f7ea9; font-weight:bold;}
.ln1  a:link,.ln1  a:active, .ln1  a:visited, .ln1 a:hover{color:#0f7ea9;text-decoration:none;}
.ln2{float:right; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#0f7ea9; font-weight:bold;}
.ln2  a:link,.ln2  a:active, .ln2  a:visited, .ln2 a:hover{color:#0f7ea9;text-decoration:none;}
</style>
<script>
function close_layer()
{
$.colorbox.close();
window.location ="~$SITE_URL`/profile/viewprofile.php?ownview=1";
}
</script>
<input type="hidden" name="img_url" value="~$SITE_URL`/profile/images/registration_new">
<div class="pink" style="width:700px;height:auto;">
	<div class="topbg">
		<div class="rf pd b t12"><a class="blink1" href="#" onclick="close_layer();">Close [x]</a></div>
	</div>
	<div class="scrollbox2 t12" style="padding-left:12px;">
		<img src="~$IMG_URL`/images/confirm.gif" style="float:left; position:relative; top:15px;left:20px"/>
		<div style="margin-left:56px;padding-top:14px;">
			<div style="font-size:22px;color:#595959;line-height:32px;">You have successfully edited ~$editMessage`</div><br/>
			<div style="font-size:20px;color:#595959;"><u>Do you also want to edit</u></div><br/>
			<div style="width:434px; padding-left:30px; line-height:23px;">
				   <p class="ln1">
				   ~if $oldFlag neq 'PBI'`
				   					<a onclick="$.colorbox({href:'~$SITE_URL`/profile/editProfile?flag=PBI'});return false;" style="color:#117DAA;cursor:pointer;" class="thickbox">Basic information</a><br />
									~/if`
				   ~if $oldFlag neq 'PMF'`
								   <a onclick="$.colorbox({href:'~$SITE_URL`/profile/editProfile?flag=PMF'});return false;" style="color:#117DAA;cursor:pointer;" class="thickbox">About ~$yourself`</a><br />
									~/if`
				   ~if $oldFlag neq 'PMF'`
								   <a onclick="$.colorbox({href:'~$SITE_URL`/profile/editProfile?flag=PMF'});return false;" style="color:#117DAA;cursor:pointer;" class="thickbox">About ~$your` Family</a><br />
									~/if`
				   ~if $oldFlag neq 'PEO'`
								   <a onclick="$.colorbox({href:'~$SITE_URL`/profile/editProfile?flag=PEO'});return false;" style="color:#117DAA;cursor:pointer;" class="thickbox">About ~$your` Education</a><br />
									~/if`
				   ~if $oldFlag neq 'PEO'`
								   <a onclick="$.colorbox({href:'~$SITE_URL`/profile/editProfile?flag=PEO'});return false;" style="color:#117DAA;cursor:pointer;" class="thickbox">About ~$your` Occupation</a><br />
									~/if`
					</p>
				   <p class="ln2">
				   ~if $oldFlag neq 'PRE'`
				   <a onclick="$.colorbox({href:'~$SITE_URL`/profile/editProfile?flag=PRE'});return false;" style="color:#117DAA;cursor:pointer;" class="thickbox">Religion &amp; Ethnicity</a><br />
									~/if`
				   ~if $oldFlag neq 'CUH'`
								   <a onclick="$.colorbox({href:'~$SITE_URL`/profile/editProfile?flag=CUH'});return false;" style="color:#117DAA;cursor:pointer;" class="thickbox">Astro Details</a><br />
									~/if`
				   ~if $oldFlag neq 'PFD'`
									<a onclick="$.colorbox({href:'~$SITE_URL`/profile/editProfile?flag=PFD'});return false;" style="color:#117DAA;cursor:pointer;" class="thickbox">Family Details</a><br />
									~/if`
				   ~if $oldFlag neq 'PEO'`
									<a onclick="$.colorbox({href:'~$SITE_URL`/profile/editProfile?flag=PEO'});return false;" style="color:#117DAA;cursor:pointer;" class="thickbox">Education and Occupation</a><br />
									~/if`
				   ~if $oldFlag neq 'PLA'`
									<a onclick="$.colorbox({href:'~$SITE_URL`/profile/editProfile?flag=PLA'});return false;" style="color:#117DAA;cursor:pointer;" class="thickbox">Lifestyle and Attributes</a><br />
									~/if`
				   ~if $oldFlag neq 'PHI'`
									<a onclick="$.colorbox({href:'~$SITE_URL`/profile/editProfile?flag=PHI'});return false;" style="color:#117DAA;cursor:pointer;" class="thickbox">Hobbies and interests</a>
									~/if`
					</p>                      
				   </div>
		</div>
</div>

	<div class="clear"></div>
<div style="border:1px #F0CED6; border-top-style:solid" class="sp12"></div>
<div style="text-align:center;width:100%" onclick="close_layer();return false;"><input type="Submit" value="Close" class="fs18 green_btn_2" style="height:35px;width:101px;"></div>
	<div class="sp12"></div>
