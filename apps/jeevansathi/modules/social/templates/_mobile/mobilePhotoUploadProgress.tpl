~if $alreadyPhotoCount eq 0`
~include_partial('social/mobile/_mobPhotoUpload',[gender=>~$gender`,selectTemplate=>~$selectTemplate`,username=>~$username`,alreadyPhotoCount=>~$alreadyPhotoCount`,selectFileOrNot=>~$selectFileOrNot`])`
~else`
~include_partial('social/mobile/_mobPhotoAlbum',[gender=>~$gender`,selectTemplate=>~$selectTemplate`,username=>~$username`,alreadyPhotoCount=>~$alreadyPhotoCount`,profilepicurl=>~$profilepicurl`,selectFile=>~$selectFile`,privacy=>~$privacy`,picturecheck=>~$picturecheck`])`
~/if`   
<div class="bg4">
        <div id="photoUploadProgress" class="posrel outerdiv" style="display:none; background:#fff;">
                <div class="fullwid padProgress">
                        <output id="result" class="classimg1" />
                        <div id="addMore" class="fl pu_mr1">
                                <div class="photobox brdr18 txtc posrel" style="display: block; overflow: hidden;">

                                        <form  id="submitForm" action="" method="post" enctype="multipart/form-data">
                                                <input type="file" name="photo" id="file" accept="image/*" style="width:0px;height:0px;position:absolute;" required />
                                                <input type="hidden" name="uploadSource" value="mobGallery" style="display:none;" required />
                                                <input type="hidden" name="perform" value="mobUploadPhoto" style="display:none;" required />
                                                <button class="photobox" id="addMoreButton" style="background:none; border:0">
                                                <a href="javascript: void(0)"><i class="up_sprite pu_plusicon"></i></a>
                                                </button>
                                        </form>

                                </div>
                        </div>

			~section name=i loop=3`
                        <div class="fl pu_mr1" style="visibility:hidden;">
                                <div class="photobox brdr18 txtc posrel" style="display: block; overflow: hidden;">	
				</div>
			</div>
			~/section`
                </div>
                <div class="posfix btmo fullwid">
                        <a href="javascript: void(0)" class="skipped color2 lh30 fullwid dispbl txtc lh50">Skip to Album</a>
                        <a href="javascript: void(0)" class="choosePP bg7 white lh30 fullwid dispbl txtc lh50">Choose Profile Picture</a>
                </div>
        </div>
</div>
<script>
	var hideOrNot = 0;	
        var selectFile = ~$selectFileOrNot`;
        var debug = true;
        var imageFormat = ["image/jpg", "image/jpeg","image/png"];
        var alreadyPhotoCount = ~$alreadyPhotoCount`;
        var appMaxPhotoSize = ~sfConfig::get("app_max_photo_size")`;
        var maxNoOfPhotos = ~sfConfig::get("app_max_no_of_photos")`;
        var SITE_URL = "~sfConfig::get('app_site_url')`";
        var imageCopyServer = "~$imageCopyServer`";
				$("body").css("background",'white');
</script>

