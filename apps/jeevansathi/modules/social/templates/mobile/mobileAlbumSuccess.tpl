	<!-- Sub Title -->
	<section class="s-info-bar">
		<div class="pgwrapper pgwrapperAlb">
			<span class="pull-left">~if $selfProfile`My Album~else`Album of <a href="~sfConfig::get('app_site_url')`/profile/viewprofile.php?profilechecksum=~$PROFILECHECKSUM`">~$USERNAME`</a>~/if` <span class="s-info">[<span id="currentPicCount">~$currentPicNumber`</span> of ~$countPics`]</span></span>
			~if $NAVIGATOR`
			~$BREADCRUMB|decodevar`
                        ~else if $httpRef`
			<a href="~$httpRef`" class="pull-right btn pre-next-btn" style="width:auto">Go back</a>
			~else`
			<a href="javascript:void(0)" onclick="goBack();" class="pull-right btn pre-next-btn" style="width:auto">Go back</a>
			~/if`
		</div>
	</section>

	<!-- PhotoAlbum -->
	<section>
		<div class="pgwrapper pgwrapperAlb" id="rotator">
			<div class="allImages">
				~assign var="loops" value=0`
				~section name="anyName" start=0 loop=$countPics`
					~if $loops eq 0`
					<div class="mainImageHolder album-holdr widthAlbum disableSave" id="imgMaxWid~$loops`" style="position:relative;">
					~else`
					<div class="mainImageHolder album-holdr widthAlbum disableSave" id="imgMaxWid~$loops`">
					~/if`
						~if $countPics gt 1`
							<div style="position:absolute; top:40%;left:2%">
			                        	        <a class="prevImage"><div style="background:url(~JsConstants::$imgUrl`/images/mobilejs/photo-arrow.png) no-repeat ; width:36px; height:54px;"></div></a>
					        	</div>
							<div style="position:absolute; top:40%; right:2%;">
								<a class="nextImage"><div style="background:url(~JsConstants::$imgUrl`/images/mobilejs/photo-arrow.png) no-repeat 1px -64px ; width:36px; height:54px;"></div></a>
							</div>
						~/if`

						~if $loops eq 0`
						<img id="imageId~$loops`" src="~$mob_img_url`" border="0" oncontextmenu="return false;" galleryimg="NO">
						~else`
						<img id="imageId~$loops`" src="~sfConfig::get('app_img_url')`/img_revamp/loader_big.gif" border="0" oncontextmenu="return false;" galleryimg="NO">
						~/if`
					</div>	
					~assign var="loops" value=$loops+1`
				~/section`
			</div>
		</div>
	</section>
	<!-- PhotoAlbum -->

<script>
var widthRotate,currentImageNext;
var imageLoadedString = "'0',";
var currentImage=0,currentImageFromIndex1,img_arr=new Array;
var indexVal = 0;
var noOfPics = ~$noOfPics`
~foreach from = $sf_data->getRaw('mainPicUrls') item = photo key=k`
        img_arr[indexVal]="~$photo`";
        indexVal = indexVal + 1;
~/foreach`
</script>
