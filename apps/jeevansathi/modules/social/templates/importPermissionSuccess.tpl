<!-- start header -->
~include_partial('global/header')`
<div id="main_cont">
<!--end header -->
<div id="container">
<!-- start search-->
        <p class="clr_4"></p>
<div id="topSearchBand"></div>
~include_partial('global/sub_header')`
~include_partial('social_tabs')`
      
<p class="clr_18"></p>

</div>
<div class="fbup"><br>
    <div class="fbup1" align="center">
        <p class="fbup2"><img src="~sfConfig::get('app_img_url')`/images/~if $importSite eq facebook`fb-logo.png" "~elseif $importSite eq flickr`flickr-logo.png ""~else`picasa-logo.png ""~/if`" /></p>
        <p class="fbup3">Import my photos from ~if $importSite eq facebook`Facebook ~elseif $importSite eq flickr`Flickr ~else`Picasa ~/if`</p><br>
<br>
<br>
<br>
<p align="left" style="font-size:14px; color:#898989;padding-top:10px;">Now you can import photos from your ~if $importSite eq facebook`Facebook ~elseif $importSite eq flickr`Flickr ~else`Picasa ~/if`account to your Jeevansathi Profile. You can specify in  your privacy settings who can view your photos.
</p><br>

<a TARGET="_top" href="~sfConfig::get('app_site_url')`/social/import/~if $importSite eq facebook`facebook ~elseif $importSite eq flickr`flickr ~else`picasa ~/if`" onClick="return unsetCookies();" class="naukri_btnup11 sprteup fl" >&nbsp;</a><br><br>
<br>

<p class="b fl" style="color:#41A317; padding-top:12px;font-size:13px">~if $importSite eq flickr ||  $importSite eq picasa`<span style="margin-left:280px;">~else`<span style="margin-left:250px;">~/if`** Your Photos ~if $video`and Videos ~/if`are safe with Jeevansathi.com</span></p>
    </div>
</div>

        <p class=" clr_18"></p>

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
<script>
function unsetCookies()
{
	
	document.cookie = 'import_aid_~$importSite`=; expires=Thu, 01-Jan-70 00:00:01 GMT;path=/';
	document.cookie = 'IMPORT_SELPICS_~$importSite`=; expires=Thu, 01-Jan-70 00:00:01 GMT;path=/';
	document.cookie = 'IMPORT_PIC_~$importSite`=; expires=Thu, 01-Jan-70 00:00:01 GMT;path=/';

	window.location="~sfConfig::get('app_site_url')`/social/import/~if $importSite eq facebook`facebook ~elseif $importSite eq flickr`flickr ~else`picasa ~/if`";

	return true;
}
</script>
~include_partial('global/footer')`
