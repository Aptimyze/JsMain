
<div class="acet">
~if $tempContact`
<div align="center"  id="PROFILE_ALBUM_TEMP"  style="width: 320px; font-family: Arial,Helvetica,sans-serif; font-size: 13px; border: 1px solid rgb(204, 204, 204); padding: 10px;" class="fr mt_20 b">You have already contacted ~$PROFILENAME`. We'll deliver the interest once your profile goes live
  <p class="sp8"></p>

</div>
~/if`

<div align="center" class="aceh fr mt_20 b" id="PROFILE_ALBUM_NO_CONTACT" ~if !(($type_of_contact eq "" && $tempContact eq '' ) || ($type_of_contact eq "RE" && $tempContact eq '' ))`style="display:none"~/if` >Liked this profile?
  <p class="sp8"></p>
<div id="expressLayer_~$profilechecksum`" class="layerce" >
<input type="button" style="width: 110px; " value="Express Interest" class="en_btn_clr b green_btn" >&nbsp;&nbsp;
</div>
</div>


<div align="center" class="aceh fr mt_20 b" id="PROFILE_ALBUM_I" ~if $type_of_contact neq "I"` style="display:none"~/if`>This profile has expressed interest in you
  <p class="sp8"></p>
<div class="layerce" style="width:206px;">
  <div  id="acceptLayer_~$profilechecksum`" style="display:inline;margin-right:35px">
<input type="button" style="width: 65px;" value="Accept" class="en_btn_clr b green_btn" id="make_contact_ai">
</div>
<div  id="notinterestLayer_~$profilechecksum`" style='display:inline;margin-left:0px'><input type="button" style="width: 102px; " value="Not Interested" class="en_btn_clr b green_btn" id="make_contact_ni">
</div>
</div>
</div>
<div id="PROFILE_ALBUM_RI">
<div align="center" class="aceh fr mt_20 b"  ~if $type_of_contact neq "RI"` style="display:none"~/if`>You have expressed interest in this profile
  <p class="sp8"></p>
<div id = "reminderLayer_~$profilechecksum`" class = "layerce">  
<input type="button" style="width: 110px; " value="Send Reminder" class="en_btn_clr b green_btn" id="make_contact_ri">&nbsp;&nbsp;
</div>
</div>
</div>


<div align="center" class="aceh fr mt_20 b"  id="PROFILE_ALBUM_A" ~if $type_of_contact neq "A"` style="display:none"~/if`>You have accepted interest from this profile
  <p class="sp8"></p>
<div class="layerce" style="width:206px;">
  <div id="detailsLayerSecond_~$profilechecksum`" style="display:inline;margin-right:15px;margin-left:0px">
<input type="button" style="width: 129px; " value="View Contact Details" class="en_btn_clr b green_btn" id="show_contact_a">
</div>

<div  id="writeLayerSecond_~$profilechecksum`" style='display:inline;margin-left:0px'>
<input type="button" style="width: 100px;" value="Send Message" class="en_btn_clr b green_btn" id="make_contact_a">
</div>
</div>
</div>


<div align="center" class="aceh fr mt_20 b"  id="PROFILE_ALBUM_RA" ~if $type_of_contact neq "RA"` style="display:none"~/if`>This member has accepted your interest
  <p class="sp8"></p>
<div class="layerce" style="width:206px;">
  <div id="detailsLayer_~$profilechecksum`"  style="display:inline;margin-right:15px;margin-left:0px">
<input type="button" style="width: 129px; " value="View Contact Details" class="en_btn_clr b green_btn" id="show_contact_ra">
</div>
<div  id="writeLayer_~$profilechecksum`" style='display:inline;margin-left:0px'>
<input type="button" style="width: 100px;" value="Send Message" class="en_btn_clr b green_btn" id="make_contact_ra">
</div>
</div>
</div>


<div align="center"  id="PROFILE_ALBUM_RD" ~if !( $type_of_contact eq "RD" || $type_of_contact eq "C")` style="display:none"~/if` style="width: 320px; font-family: Arial,Helvetica,sans-serif; font-size: 13px; border: 1px solid rgb(204, 204, 204); padding: 10px;" class="fr mt_20 b"><div align="left">This member is not interested in further communication with you</div>
</div>


<div align="left" class="aceh fr mt_20 b"  id="PROFILE_ALBUM_D" ~if $type_of_contact neq "D"` style="display:none"~/if`>You have declined any further communication with this member
  <p class="sp8"></p>
   <div align="center" id="declineacceptLayer_~$profilechecksum`" >
<input type="button" style="width: 132px; " value="Accept this member" class="en_btn_clr b green_btn" id="make_contact_d">
&nbsp;&nbsp;

</div>
</div>

<div align="left" class="aceh fr mt_20 b"  id="PROFILE_ALBUM_RC" ~if $type_of_contact neq "RC"` style="display:none"~/if`>You have cancelled any further communication with this member
  <p class="sp8"></p>
  <div align="center">
  <div  id="cancelAcceptLayer_~$profilechecksum`" class="layerce">
<input type="button" style="width: 132px; " value="Accept this member" class="en_btn_clr b green_btn" id="make_contact_rc">
&nbsp;&nbsp;
</div>
</div>
</div>
<style>
.en_btn_clr_alb{color:#ffffff;padding:4px;font-size:12px;}
</style>
<script>
var type_of_contact="~$type_of_contact`";
var ini_contact="~$contact_status_new`";
var fin_contact="";
var SITE_URL="~sfConfig::get("app_site_url")`";
var IMG_URL="~sfConfig::get("app_img_url")`";
var dp_checksum="~$checksum`";
var dp_profilechecksum="~$profilechecksum`";
var dp_imgurl=IMG_URL;
var stype="~$STYPE`";
var pr_view="";
var suggest_profile="~$suggest_profile`";
//var matchalert_mis_variable="~$matchalert_mis_variable`";
var matchalert_mis_variable="";
var CURRENTUSERNAME="~$CURRENTUSERNAME`";
</script>
<div class="ce_layer" style="display:inline;" id="exp_layer">
</div>
<div  class="lyr ce_layer"  id="alb_loader" align="center"  style="display:none">
	<div class="lyr_tp_cur"></div>
	<div id="call_directly" class="cnt">
	
	<a href="#" onclick="return stop_album_layer()" class="fr crs b">[x]</a>


	<div class="fl mt_15 f_11"></div>
	<table style="height:250px;"><tr><TD valign="middle" id="temp_cond">
	<img src="~$IMG_URL`/images/loader_big.gif"></img>
	</td></tr></table>
	</div>
	<div class="clr"></div>
	<div class="lyr_btm_cur"></div>
</div>


</div>
