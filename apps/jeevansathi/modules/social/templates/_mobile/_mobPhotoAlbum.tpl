~if $selectTemplate eq 0`
<div id="addPhotoAlbumPage" class="bg7" style="background-color:rgb(255,255,255)">
~else`
<div id="addPhotoAlbumPageSub" class="bg7" style="background-color:rgb(255,255,255)">
~/if`
<div id="privacyOption">
 ~if $selectTemplate eq 0`
 <div id="FadedRegion" class="posabs tapoverlay" style="display:none"></div> 
 
  <!--start:div-->
  <div class="photoheader fullwid bg10 posfix zind102" style="z-index:1002;">
    <div class="pad5">

      <div class="fl wid20p pt4"><a href="/profile/mainmenu.php" bind-slide="1"><i class="mainsp arow2"></i></a></div>
      <div class="fl wid60p txtc color5  fontthin f19">~$username`</div>
      <div class="clr"></div>
    </div>
  </div>
  ~/if`
  <!--end:div--> 
  <!--start:user image-->
  <div class="posrel"> 
    <!--start:overlay-->
    <div style="position:fixed;top:0;"> 
    <img id="Albumpicture" src="~$profilepicurl`" class="classimg pu_blurred" border="0"> </div>
    <div id="Albumoverlay" class="web_dialog_overlay" style="height: 100%; width: 100%; z-index: 101; /* position: fixed; */">
</div>
    
    <!--end:overlay--> 
    <!--start:options-->
    <div class="overlay_pu posabs" style="overflow:hidden;">
    
    ~if $selectTemplate eq 0`
      <div class="posrel pt50">
        <div class="fullwid pad2 fontlig bg2">
          <div class="pad1">
          <a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?ownview=1&check=1#Album">
            <div class="fl wid61p  txtr color5 fontlig f14"> Album<span class="arow4"></span> </div></a>
	    ~if $gender eq M`
				<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?ownview=1#Details">
            	<div class="fl wid39p color5 txtr fontlig f12 pt2 opa70">Groom's Details</div></a>
            ~else`
            <a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?ownview=1#Details">
                <div class="fl wid39p color5 txtr fontlig f12 pt2 opa70">Bride's Details</div></a>
            ~/if`
            <div class="clr"></div>
          </div>
         
        </div>
      </div>
       ~/if`
      <div style="padding:20px 0px; text-align:center"> 
       <div style="width:150px;height:180px;position:relative">      
        <a href="~sfConfig::get('app_site_url')`/social/MobilePhotoAlbum">
        ~if $picturecheck eq 1`
        <div class="img-responsive img-circle brdr19" style="width:150px; height:150px; overflow:hidden">
      	<img src="~$profilepicurl`"></div>
      	~else`
      	<img src="~$profilepicurl`" class="img-responsive img-circle brdr19" style="width:150px; height:150px">
      	~/if`
      	</a>
        <div class="photocount fontlig" style="top:19px; left:120px">~$alreadyPhotoCount`</div>
        
        <div class="txtc lh50" style=" width:100%;">
        <div id="privacy_button" class="color1 fontlig f14 up_sprite lockicon padl15 dispibl" style="width:100%">Set Photo Privacy</div>
        </div>
      </div>
      </div>
<a href="~sfConfig::get('app_site_url')`/social/MobilePhotoAlbum?setProfilePic=1">
      <div class="fullwid pad8">
        <div class="fl wid20p"> <i class="mysp_spr change"></i></div>
        <div class="fl pad8 wid80p">
          <div class="brdr5">
            <div class="pad10"> <div class="f14 color1 fontthin">Change Profile Photo</div> </div>
          </div>
        </div>
        <div class="clr"></div>
      </div>
</a>
 

<div class="addphotofromalbum fullwid" style="padding:10px 0 10px 15px">
      <a href="~sfConfig::get('app_site_url')`/social/MobilePhotoUpload">
      
        <div class="fl wid20p"> <i class="mysp_spr addicon"></i></div></a>
        <div class="addphotofromalbum fl pad8 wid80p">
        <a href="~sfConfig::get('app_site_url')`/social/MobilePhotoUpload">
          <div class="brdr5">
            <div class="pad10"> 
				<div class="f14 color1 fontthin">Add Photos</div> 
			</div>
          </div>
          </a>
        </div>
        <div class="clr"></div>

      </div>
      
              
    </div>
    <!--end:options--> 
  </div>
  </div>
  <!--end:user image--> 
~if $selectTemplate eq 0`
~include_partial("social/mobile/_jsms_imageupload_option")`
~/if`

<div id="privacyoptionshow" style="display:none;" class="posrel outerdiv" style="overflow: hidden;">
    <div class="posabs puoverlay"></div>
        <img src="~$profilepicurl`" class="classimg1 pu_blurred">
    <div class="posabs fullwid zind102 fontlig white f14" style="top:30%">
        <div id="all" class="lh30 f16 pad5">Photo Privacy Settings</div>
        <div id="privacyoption"><div id="visibleAll" class="fullwid puwbg_white" style="padding:10px 15px 3px;">
                <div class="fl wid80p pt7">Visible to All (Recommended)</div>
            <div id="checkLoader1" ~if $privacy eq 'C'`style="visibility:hidden;"~/if` class="fr posrel pt3">
		<div id="check1" ~if $privacy eq 'C'`style="visibility:hidden;"~/if` class="fr posabs"><i class="up_sprite whitecheck"></i></div>
		<img src="~sfConfig::get('app_site_url')`/images/jsms/commonImg/loader.gif" ~if $privacy neq 'C'`style="visibility:hidden;"~/if`></div>
            <div class="clr"></div>
        </div>
	<div id="allaccept" class="hgt2"></div>
                <div id="visibleLimit"  class="fullwid puwbg_white pad5">
                <div class="fl wid80p">Visible to those you have accepted or expressed interest in</div>

            <div id="checkLoader2"~if $privacy neq 'C'`style="visibility:hidden;"~/if` class="fr posrel">
		<div id="check2" ~if $privacy neq 'C'`style="visibility:hidden;"~/if` class="fr posabs"><i class="up_sprite whitecheck"></i></div>
		<img src="~sfConfig::get('app_site_url')`/images/jsms/commonImg/loader.gif" ~if $privacy eq 'C'`style="visibility:hidden;"~/if`>
	    </div>

            <div class="clr"></div></div>
        </div>
    </div>
    <div class="posabs zind102 fullwid btmo">
         <div id="privacyoptionclose" class="bg7 white lh30 fullwid dispbl txtc lh50 fontlig ">Done</div>
    </div>
</div>
</div>
<script>
$(function() {
  //var vhgt = $(window).height();
  //var divhgt = $('div.fullwid.bg10.posfix.zind102').outerHeight();
 // var effhgt = vhgt-divhgt;
 // $('.overlay_pu.posabs').css({"height":effhgt, "overflow":"auto"});
});
</script>
