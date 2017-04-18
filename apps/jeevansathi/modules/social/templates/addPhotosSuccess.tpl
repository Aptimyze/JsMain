  <header>
    <div class="cover1">
      <div class="container mainwid pt35">
        ~include_partial("global/JSPC/_jspcCommonTopNavBar")`
        <div class="f14 srppad6 ulinline clearfix">
        </div>
      <div class="pt50 pb30 txtc f22 colrw fontlig">Smile please!</div>
      </div>
    </div>
  </header>
~include_partial("social/addPhotos/_popup",[])`
~include_partial("social/addPhotos/_selectPhoto",[])`
~include_partial("social/addPhotos/_import",[])`
~include_partial("social/addPhotos/_cropper",[])`
<!--start:middle part-->
<div  class="pubg1 fontlig">
  <div class="container mainwid">
    <p class="txtr color11 f13  txtu pt7">
	<a href="/" class="color11" ~if $fromReg eq ''`style="visibility:hidden;"~/if`>I will do this later</a>
</p>
    <p class="txtc color11 f22 opa80" id="morePhotoUploadedMessage">Profile with photos get 8 times more responses</p>
    <p class="txtc color11 f22 opa80" id="lessPhotoUploadedMessage">75% of our users feel they need at least 3 photos to send an expression of interest...<a href="/social/addPhotos?uploadType=F" class="color11">Upload More</a></p>
    <!--start:div-->
    <div  class="mt23 pubg2 clearfix"> 
      <!--start:left-->
~include_partial("social/addPhotos/_uploadLeft",['havePhoto'=>$havePhoto,'ProfilePicUrl'=>$ProfilePicUrl])`
      <!--end:left--> 
      <!--start:right-->
~include_partial("social/addPhotos/_uploadRight",[])`
      <!--endt:right--> 
    </div>
    <!--end:div--> 
    <!--start:div-->
    <div class="bg-white fontlig"> 
~include_partial("social/addPhotos/_privacy",[])`
      <!--start:div one-->
~if $havePhoto neq 'Y' && $havePhoto neq 'U'`
~include_partial("social/addPhotos/_avoid",[])`
~/if`
      <!--end:div one--> 
<div class='photoLoader' style="display:none;"><div class='loaderLinearPhoto'></div></div>
     <!--start:div-->
     <div class="pup16 continueDiv" >

        <div class="pt30 pb24 clearfix pubdr10" id="morePhotoUploaded">
                <div class="fl wid70p">
                <p class="color11 opa90 pl40 f24 txtc" id="continueText"></p>
            </div>
            <div class="fr  wid25p">
                <div class="pr40"><button class="bg_pink f20 fontreg lh44 colrw cursp brdr-0 pup15" id="continue">Continue</button></div>
            </div>
        </div>


         <div class="pt30 pb24 clearfix pubdr10 color11 fontlig" id="lessPhotoUploaded" style="display:none;">
          <div class="fl wid50p pt2 pl20">
              <p class="f16" id="continueText"><p>
              <p class="f13 pt5">75% of our users feel they need at least 3 photos to send an expression of interest</p>
          </div>
          <div class="fr wid40p pr20">
            <div class="fullwid clearfix">
              <div class="fl">
                <div class="bg_pink lh44 pup15">
                  <a href="/social/addPhotos?uploadType=F" class="fontreg colrw f16">Upload More</a>
                </div>

              </div>
              <div class="fr  pt10 pr10">
                <button class="f14 fontreg cursp skipbtn1 color11" id="skip_continue">
                  Skip & Continue
                </button>
              </div>
            </div> 
           
          </div>
        </div>

     </div>
     <!--end:div-->


     <!--start:div-->
     <div class="pup16" style="display:none;" id="nowUpload">
        <div class="pt30 pb24 clearfix pubdr10">
                <div class="fl wid70p">
                <p class="color11 opa90 pl40 f24 txtc">Photos Uploaded <span id="nowUploaded"></span></p>
            </div>
        </div>
     </div>

     <!--end:div-->


      <!--start:div two-->
~include_partial("social/addPhotos/_photoDisplay",[])`
      <!--end:div two--> 
      <!--start:div four-->
      <div class="pubg7 pt20 pb20 txtc">
      	<p class="fontreg f17 pucolor3">Having trouble in upload?</p>
        <p class="f15 fontlig pt16">You can email your photos with your profile id to <span class="fontreg"><a href="mailto:photos@jeevansathi.com" style="color : #000000;">photos@jeevansathi.com</a></span></p>
      
      </div>
      <!--end:div four-->
    </div>
    <!--end:div--> 
    
  </div>
  <div class="hgt102"></div>
</div>
<!--end:middle part-->
  ~include_partial('global/JSPC/_jspcCommonFooter')`

<script type="text/javascript">
$(document).ready(function() {
	slider();
});
var fromCALphoto=~if $fromCALphoto == 1`'1'~else`'0'~/if`;
var imageCopyServer = "~$imageCopyServer`";
var profileId = ~$loggedInProfileId`;
var photoDisplay = "~$PHOTODISPLAY|decodevar`";
var photosDetails =jQuery.makeArray(~$urlsJson|decodevar`);
var pictureids = jQuery.makeArray(~$pictureidsJson|decodevar`);
var maxNoOfPhotos = ~sfConfig::get("app_max_no_of_photos")`;
var imageFormat = ["image/jpg", "image/jpeg"];
var hideOrNot = 0;      
var selectFile = "~$selectFileOrNot`";
var debug = true;
var alreadyPhotoCount = "~$totalImages`";
console.log(alreadyPhotoCount);
var appMaxPhotoSize = ~sfConfig::get("app_max_photo_size")`;
var havePhoto = "~$havePhoto`";
var profilePicPictureId = "~$profilePicPictureId`";
var uploadType = "~$uploadType`";
var cropper="~$cropper`";
var profilePicUrl = "~$ProfilePicUrl`";
var mainPicUrl = "~$mainPicUrl`";
var showMyjs = "~$showMyjs`";
var showConf = "~$showConf`";
var albumsCount = 0;
var importPhotosBarHeightPerShift = ~$importPhotosBarHeightPerShift`;
var importPhotosBarCountPerShift = ~$importPhotosBarCountPerShift`;
var initialAlbumImportPointerTop;
</script>
