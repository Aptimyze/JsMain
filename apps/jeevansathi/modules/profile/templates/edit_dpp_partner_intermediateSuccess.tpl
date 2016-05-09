<script>
function close_layer()
{
$.colorbox.close();
window.location ="~$SITE_URL`/profile/dpp";
}
</script>
<input type="hidden" name="img_url" value="~$SITE_URL`/profile/images/registration_new">
<div class="pink_edit" style="width:700px;height:auto;">
	<div class="topbg_edit">
		<div class="rf pd b t12"><a class="link_edit" href="#" onclick="close_layer();return false;">Close [x]</a></div>
	</div>
	<div class="edit_scrollbox2_1 t12" style="padding-left:12px;">
		<img src="~$IMG_URL`/images/confirm.gif" style="float:left; position:relative; top:15px;left:20px"/>
		<div style="margin-left:56px;padding-top:14px;">
			<div style="font-size:22px;color:#595959;line-height:32px;">You have successfully edited desired partner profile details</div><br/>
			<div style="font-size:20px;color:#595959;"><u>Do you also want to edit</u></div><br/>
			<div class="row4" style="line-height:25px;font-size:16px;">
				~if $oldFlag neq 'PPA'`
				<a onclick="$.colorbox({href:'~$SITE_URL`/profile/edit_dpp.php?width=700&flag=PPA&FLAG=partner&profilechecksum=&gli=&APeditID='});return false;" style="color:#117DAA;cursor:pointer;" class="thickbox">About Desired Partner Profile</a><br/>
				~/if`
				~if $oldFlag neq 'PPBD'`
				<a onclick="$.colorbox({href:'~$SITE_URL`/profile/edit_dpp.php?width=700&flag=PPBD&FLAG=partner&profilechecksum=&gli=&APeditID='});return false;" style="color:#117DAA;cursor:pointer;" class="thickbox">Basic Details of Desired Partner</a><br/>
				~/if`
				~if $oldFlag neq 'PPRE'`
				<a onclick="$.colorbox({href:'~$SITE_URL`/profile/edit_dpp.php?width=700&flag=PPRE&FLAG=partner&profilechecksum=&gli=&;APeditID='});return false;" style="color:#117DAA;cursor:pointer;" class="thickbox">Religion & Ethnicity of Desired Partner</a><br/>
				~/if`
				~if $oldFlag neq 'PPEO'`
				<a onclick="$.colorbox({href:'~$SITE_URL`/profile/edit_dpp.php?width=700&flag=PPEO&FLAG=partner&profilechecksum=&gli=&;APeditID='});return false;" style="color:#117DAA;cursor:pointer;" class="thickbox">Education & Occupation of Desired Partner</a><br/>
				~/if`
				~if $oldFlag neq 'PPLA'`
				<a onclick="$.colorbox({href:'~$SITE_URL`/profile/edit_dpp.php?width=700&flag=PPLA&FLAG=partner&profilechecksum=&gli=&;APeditID='});return false;" style="color:#117DAA;cursor:pointer;" class="thickbox">Lifestyle & Attributes of Desired Partner </a>
				~/if`
			</div>
		</div>
</div>

	<div class="clear"></div>
<div style="border:1px #F0CED6; border-top-style:solid" class="sp12"></div>
<div style="text-align:center;width:100%" onclick="$.colorbox.close();return false;"><input type="Submit" value="Close" class="green_btn_2" style="height:35px;width:101px;font-size:18px;"></div>
	<div class="sp12"></div>
