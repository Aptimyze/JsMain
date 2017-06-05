<style>
.c5b5b5b {
        color:#5b5b5b
}
.clr1 {
        clear:both;
        font-size:1px;
        height:1px;
        overflow:hidden
}
.uptmsg {
        background:#fcefa1;
        padding:5px 10px;
        color:#46981a;
        margin-top:15px
}
.ce05400 {
        color:#e05400
}
.s {
        background:url(~sfConfig::get('app_img_url')`/images/s.png) left 6px no-repeat;
        padding-left:10px
}
.ln {
        background:#b7b7b7;
        height:1px;
        line-height:1px;
        font-size:1px;
        overflow:hidden;
        margin:10px 0
}
</style>

~include_partial('global/header',[showGutterBanner=>1])`
<div id="main_cont">

<div id="container11">
<!-- start search-->
<!--QUICK SEARCH STARTS-->
        <p class="clr_4"></p>
<div id="topSearchBand"></div>
~include_partial('global/sub_header')`
~include_partial('social_tabs')`
</div>

  <!--Start_TwoTabs_Upload/Import Photos_All Photos-->
~if $currentState eq FTOSubStateTypes::FTO_ELIGIBLE_NO_PHONE_HAVE_PHOTO`
        ~include_partial('content1_verify_phone')`
~/if`
  <div class="lf t12 b" style="width:550px;padding:5px; margin-right:0px;">
    <div style="background-image:url(~sfConfig::get('app_img_url')`/images/ph-edit-bg.gif); background-repeat:repeat-x; width:550px; height:26px;">
      <div class="fl"> 
        <div class="filtp b" style="margin-top:5px;padding-left:10px">Upload / Import Photos |<a href="~sfConfig::get('app_site_url')`/social/viewAllPhotos/none"> All Photos [~$totalPics`]</a></div>
        <div> </div>
      </div>
      <!--End_TwoTabs_Upload/Import Photos_All Photos-->
    </div>
    <p class="clr_18" style="height:30px"></p>


~if $errorMsg eq 1`
<div style="font-size:20px; font-weight:normal;margin-left:10px; ">

You have reached your limit of ~sfConfig::get("app_max_no_of_photos")` photos.
<br>
Please&nbsp;
<a href="~sfConfig::get('app_site_url')`/social/saveImage?err=excessError" >delete some photos</a>
&nbsp;to add more photos.
<br>
</div>
	<div class="clr_15">&nbsp;</div>
	<div class="no_b" style="float:left; width:350px;position:absolute;margin-left:10px;">
		<u style="color:#118FC4;">
			<a  href="#" onclick="showPrivacyLayer(); return false;" style="font-size:16px;">
				Your Photos are Safe on Jeevansathi
			</a>
		</u>
		<div id="privacyLayer" style="display:none;position:relative;margin-top:-170px;">
			~include_partial('photo_privacy_layer',[])`
		</div>
	</div>
    <p class="clr_18"></p>
    <p class="clr_18"></p>
    <p class="clr_18"></p>
	<div style="margin-left:10px;">
	~include_partial('privacySettings',[PHOTODISPLAY=>~$PHOTODISPLAY`,WIDTH=>'100%'])`
	</div>
    <p class="clr_18"></p>
~else`
    <div class="protop5 c5b5b5b" style="margin-left:10px;" >Upload/Import your profile photo, photos of your friends, relatives, house, pets etc.</div>
    <p class="clr" style="height:11px"></p>

<div class="upldtp5a" style="height:auto">
      <div style="width:100%">
        <div class="clr_15">&nbsp;</div>
        <div class="upldtp6a">
          <div style="margin-bottom:13px">Upload from</div>
          <div><a href="~sfConfig::get('app_site_url')`/social/compUpload" class="naukri_btnup sprteup">&nbsp;</a></div>
          <div style="color:#706e71;font-size:12px;font-weight:400;margin-top:10px">~sfConfig::get("app_photo_formats")` | upto ~sfConfig::get("app_max_photo_size")`&nbsp;MB |  ~sfConfig::get("app_max_no_of_photos")` photos</div>
        </div>
        <div class="upldtp6" style="margin-left:33px;border-left:1px solid #000">
          <div style="margin-bottom:13px">Select Photos from</div>
          <div style="float:left; margin-right:12px;"> <a target="_top" href="~sfConfig::get('app_site_url')`/social/importPermission/flickr" class="naukri_btnup1 sprteup">&nbsp;</a>
            <p style="padding-top:8px; color:#3b5997; font-size:16px;"><a target="_top" href="~sfConfig::get('app_site_url')`/social/importPermission/flickr">Flickr</a></p>
          </div>
	<!--
          <div style="float:left;margin-right:12px;"> <a target="_top" href="~sfConfig::get('app_site_url')`/social/importPermission/picasa" class="naukri_btnup2 sprteup">&nbsp;</a>
            <p style="padding-top:6px; color:#3b5997; font-size:16px;"><a target="_top" href="~sfConfig::get('app_site_url')`/social/importPermission/picasa" >Picasa</a></p>
          </div>
-->
          <div style="float:left;margin-right:12px;margin-top:5px"> <a target="_top" href="~sfConfig::get('app_site_url')`/social/importPermission/facebook" class="naukri_btnup3 sprteup">&nbsp;</a>
            <p style="padding-top:9px; color:#3b5997; font-size:16px;"><a target="_top" href="~sfConfig::get('app_site_url')`/social/importPermission/facebook">Facebook</a></p>
          </div>
<div style="clear: both;font-size: 1px;height: 10px;overflow: hidden;">&nbsp;</div>
<div style="font-size:12px;color:black;font-weight:lighter;"> (We won't post anything on your wall)</div>

        </div>
        <div class="clr_8">&nbsp;</div>
      </div>
<!--
      <div class="no_b uptmsg"><strong>** Your Photos  are safe with Jeevansathi.com</strong></div>
-->
    </div>
<p class="clr_4"></p>
<div>
<!--upload computer & social ends here -->
<!--photo & video privacy starts here -->
    <div class="no_b" style="float:left; width:350px;position:absolute">
	<u style="color:#118FC4;">
		<a  href="#" onclick="showPrivacyLayer(); return false;" style="font-size:16px;">
			Your Photos are Safe on Jeevansathi
		</a>
	</u>
        <div id="privacyLayer" style="display:none;position:relative;margin-top:-170px;">
                ~include_partial('photo_privacy_layer',[])`
        </div>
    </div>
    <p class="clr_18"></p>
    <p class="clr_18"></p>
    <p class="clr_18"></p>
	~include_partial('privacySettings',[PHOTODISPLAY=>~$PHOTODISPLAY`,WIDTH=>530])`
<p class="clr_4"></p>
    <p class="clr_18"></p>

    <div style="width:98%">
      <div class="fl" style="width:240px">
        <div style="width:100%">
          <div style="margin-bottom:5px"><strong>Email your Photos to</strong></div>
          <div style="border:1px solid #989898;padding:5px;line-height:20px"> <a href="mailto:~sfConfig::get("app_photo_email")`?subject=~$sf_request->getAttribute('username')`">~sfConfig::get("app_photo_email")`</a><br />
            <span class="no_b">Please mention your profile ID in mail</span> </div>
        </div>
      </div>
      <div class="clr1">&nbsp;</div>
    </div>
  </div>

~/if`
<p class="clr_18"></p>
<p class="clr_18"></p>

</div>
<!--slider starts here -->
~include_partial('social_slider',[sliderNo=>0,tempCount=>$totalImages,allThumbnailPhotos=>$urls,picIdArr=>$picID,countOfPics=>$totalPics,whichPage=>'add'])`
<!--slider ends here-->

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
~include_partial('global/footer',[data=>$loggedInProfileId])`
<script>
function showPrivacyLayer()
{
$("#privacyLayer").toggle();
}
function hidePrivacyLayer()
{
$("#privacyLayer").hide();
}
$(function()
{
        $('input:radio[name=photo_display]').click(function() 
        {
                var option=$('input:radio[name=photo_display]:checked').val();
                if(option == 'A')
                {
                        $('#im1_1').show();
                        $('#im2_1').hide();
                }
                else if(option == 'C')
                {
                        $('#im2_1').show();
                        $('#im1_1').hide();
                }

                var randomnumber=Math.floor(Math.random()*11111)
                $.ajax(
                {
                        url: "/profile/change_photo_privacy.php",
                        data: "photo_display="+option+"&rnumber="+randomnumber,
                        //timeout: 5000,
                        success: function(response) 
                        {
                                if(response == 'A')
                                {
                                        $('#im1_1').hide();
                                        $('#im1_2').show();
                                        $('#im2_2').hide();
                                }
                                else if(response == 'C')
                                {
                                        $('#im2_1').hide();
                                        $('#im1_2').hide();
                                        $('#im2_2').show();
                                }
                                else if(response == 'X')
                                {
                                        show_loggedIn_window();
                                }
                                else if(response == 'A_E')
                                {
                                        show_ajax_connectionErrorLayer();
                                }
                        },
                        error: function(xhr) 
                        {
                                //alert('Error!  Status = ' + xhr.status + "TRY AGAIN");
                        }       
                });
        });
});
</script>
