<script type = "text/javascript">checkFlash();</script>
<!-- start header -->
~include_partial('global/header')`
<!--end header -->
<!--Main container starts here-->
<!--pink strip starts here-->
<div id="main_cont">	

<!-- start search-->
<!--QUICK SEARCH STARTS-->
        <p class="clr_4"></p>
<div id="topSearchBand"></div>
~include_partial('global/sub_header')`
~include_partial('social_tabs')`

<div class="lf t12 b" style="width:650px; margin-left:15px;">

<p class="clr_4"></p>
<div class = "fl" style = "width:550px" id = "iframe_loader"><center><img src = "~sfConfig::get('app_img_url')`/images/loader_big.gif" /></center></div>
<iframe onload = "resizeFrame();" src = "~sfConfig::get('app_site_url')`/social/compUploadFrame" width = "550px" id = "myframe" scrolling = "no" marginheight="0" frameborder="0" vspace="0" hspace="0" class="iframe" style = "visibility:hidden"></iframe>

</div>
  <!--right part strat here-->
  <!--right part ends here-->
<p class=" clr_2"></p>
  <p class=" clr_18"></p>
 
  
<!--mid bottom content end -->
<p class=" clr_18"></p>
<!--tabbing  start -->

<!--tabbing  end -->
<p class=" clr_18"></p>
</div>
<!--Main container ends here-->	
<!--Footer starts here and same as the footer of seo community pages-->
<!--<p class="clr_4"></p>-->
~include_partial('global/footer')`
<!--Footer ends here and same as the footer of seo community pages-->
