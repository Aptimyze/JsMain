

<div id="addPhotoAlbumPage" class="bg7">
 ~if $selectTemplate eq 0`
        <div id="FadedRegion" class="posabs tapoverlay" style="display:none"></div>  
        ~/if`
            <!--start:div-->
	<div id="containerDiv">
        <div class="photoheader fullwid bg10">
               ~if $selectTemplate eq 0`
               <div class="pad5">
      <div class="fl wid20p pt4"><a href="/profile/mainmenu.php" bind-slide="1"><i class="mainsp arow2"></i></a></div>
      <div class="fl wid60p txtc color5  fontthin f19">~$username`</div>
      <div class="clr"></div>
    </div>
    ~/if`
        </div>
        <!--end:div-->
        <!--start:pink bg section-->
        <!--start:div-->
        
                ~if $selectTemplate eq 0`
                <div class="fullwid pad2 fontlig">
                 <div class="pad1">
            <div class="fl wid61p  txtr color5 fontlig f14"> Album<span class="arow4"></span> </div>
	    ~if $gender eq M`
				<a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?ownview=1#Details">
            	<div class="fl wid39p color5 txtr fontlig f12 pt2 opa70">Groom's Details</div></a>
            ~else`
            <a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?ownview=1#Details">
                <div class="fl wid39p color5 txtr fontlig f12 pt2 opa70">Bride's Details</div></a>
            ~/if`
            <div class="clr"></div>
          </div>
          ~/if`
        </div>
              <!--end:div-->
              <!--Start:no photo-->
        <div class="txtc pu_pad1">
                <div id="addPhotoMobile" class="posrel no_pic_dim">
                <a href="~sfConfig::get('app_site_url')`/social/MobilePhotoUpload">
		~if $gender eq F`                     	    
                       	  <img src="~sfConfig::get("app_img_url")`/images/jsms/photo/noPhotoFemale142_142.png">
		~else`
                       	  <img src="~sfConfig::get("app_img_url")`/images/jsms/photo/noPhotoMale142_142.png">
		~/if` 
                       	<i class="up_sprite posabs pu_addmore"></i>
                       	</a>
               	</div>
        </div>
              <!--end:no photo-->
        <div class="txtc fontthin white ">
                <div class="f20 lh50">Your photos are secure</div>
                <div class="f14 lh25">Add some good quality photos to your profile</div>
		<div class="f14 lh25">Profiles with photo get 10 times better response</div>
        </div>
            <!--end:pink bg section-->
            <div class="pad5"></div>
	</div>
</div>
~if $selectTemplate eq 0`
~include_partial("social/mobile/_jsms_imageupload_option")`
~/if` 
