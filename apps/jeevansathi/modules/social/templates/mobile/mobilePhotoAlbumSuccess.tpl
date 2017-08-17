<script>
	var purposeOfAlbumView = "~$purposeOfAlbumView`";
	var SITE_URL = "~$SITE_URL`";
</script>
~if $showLayer`
<div id = "conditionalPhotoLayer">
	<div class="fullwid" style="background-color: #000;height:736px" >
		<div class="posabs" style="top:5%;left:5%">
			<div class="up_sprite puback" onclick="goBack();"></div>
		</div>
	</div>
	<div class="setshare white wid90p posabs txtc">
	<p class="mb20">It has been a while since you registered on Jeevansathi, hence we require you to add a photo to be able to see other members' album.</p>

      <p>If you have privacy concerns, you can make your photo visible on only on acceptance through privacy settings.</p>
		
	</div>
	<div class="posfix fullwid btmo">
		<a href="/social/MobilePhotoUpload" class="bg7 txtc white fullwid dispbl lh50 border0">Uplaod Photo</a>
	</div>
</div>
~else`
	<div class="loader" style="display: none; position: absolute; height: 100%; width:100%; opacity: 0.8; background:#000; z-index:1009;">
        <img src="~$SITE_URL`/images/jsms/commonImg/loader.gif" style="position: absolute; left: 50%; top: 40%; margin:0 auto;">
</div>
<div id="Gallery" style="display: none;">

        <div class="gallery-row">
                ~foreach from = $mob_img_url item = photo key=k`
                <div class="gallery-item pic~$pictureId.$k`"><a href="~$photo`"><img src="" alt="~$k+1`/~$countPics`,~$pictureId.$k`,~$goBackLink`" /></a></div>
                ~/foreach`
        </div>
</div>
~/if`
<script>
	~if $toReload eq 1`
		if(ISBrowser("UC") || ISBrowser("AndroidNative"))
			location.reload(true);
	~/if`
</script>
