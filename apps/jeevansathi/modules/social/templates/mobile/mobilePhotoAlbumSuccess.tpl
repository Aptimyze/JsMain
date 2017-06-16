<script>
	var purposeOfAlbumView = "~$purposeOfAlbumView`";
	var SITE_URL = "~$SITE_URL`";
</script>
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
<script>
	~if $toReload eq 1`
		if(ISBrowser("UC") || ISBrowser("AndroidNative"))
			location.reload(true);
	~/if`
</script>
