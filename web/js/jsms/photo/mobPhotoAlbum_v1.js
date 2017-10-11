$('#searchHeader').slideUp()
var nua = navigator.userAgent;
var is_android = ((nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 && nua.indexOf('AppleWebKit') > -1) && !(nua.indexOf('Chrome') > -1) && !(nua.indexOf('UCBrowser') > -1));
var albumCaptionTotal;
var albumCaptionNow;
var thisSlideElement;
var imageArray;
var tapOverlay = '<div class="posabs tapoverlay"></div>';
var errMessage = "Something went wrong. Please try again later.";
var aa = new Date().getTime().toString();


document.addEventListener('DOMContentLoaded', function() {
	// Set up PhotoSwipe, setting "preventHide: true"
	var thumbEls = Code.photoSwipe('a', '#Gallery', {preventHide: true});
	Code.PhotoSwipe.Current.show(0);
}, false);


function deletePic(pictureID) {
	
	var boxToShow = '<div id="ConfirmSubmission" style="top:300%;z-index:105" class="posabs fullwid"><div class="pad1"><div class="bg4"><div class="pad15 txtc brdr1 f16 color3 fontlig"><div class="pad12">Once deleted you will not be able to retrieve photo</div></div><div class="clearfix f19 pad2"><div class="fl wid49p txtc" onclick="deletionProcess('+pictureID+')"><a class="fontlig color2" href="javascript:void(0);">Delete</a></div><div class="fr wid49p txtc"  onclick="CancelProcess()"><a class="fontlig color2" href="javascript:void(0);">Cancel</a></div></div></div></div></div>';
	$(boxToShow).prependTo(".ps-caption");
	$(tapOverlay).prependTo(".ps-caption");
	$(tapOverlay).prependTo("#inverted-contain");
	thisSlideElement.isBusy=true;
}

function CancelProcess() {
	$("#ConfirmSubmission").remove();
	$(".posabs.tapoverlay").remove();
	thisSlideElement.isBusy=false;
}

function setProfilePic(pictureID) {
    setProfilePicProcess(pictureID);
}

function addMorePic() {
    window.location=SITE_URL + "/social/MobilePhotoUpload?selectFile=1";
}
function goBack(link) {
    history.back();
    //window.location=link;
}


function deletionProcess(pictureID) {
    $.ajax({
        type: 'POST',
        url: SITE_URL+'/social/MobPhoto',
        data: {"pictureId": pictureID, "perform": 'mobDeletePhoto'},
        timeout: 10000,
        success: function(d) {
	    CommonErrorHandling(d);
            if (d.label == "Successfully deleted") {
                $(".pic" + pictureID).remove();
                albumCaptionTotal--;
                albumCaptionNow--;
                var currentPic = albumCaptionNow;
                if (albumCaptionTotal == 0)
                    location.reload(); 
                else if (albumCaptionTotal == albumCaptionNow)
                {
                    albumCaptionNow++;
                    thisSlideElement.showPrevious();
                }
                else
                    thisSlideElement.showNext();

                while (currentPic < imageArray.length) {
                    var nextPic = currentPic + 1;
                    imageArray[currentPic] = imageArray[nextPic];
                    currentPic++;
                }
                imageArray = imageArray.splice(0, imageArray.length - 1);
            }
            else if (d == "LOGOUT") {
                displayConfirmationMessage("Login to perform this action");
            }
            else {
                displayConfirmationMessage(errMessage);

            }
        },
        beforeSend: function() {
            $(".loader").show();
            CancelProcess();
        },
        complete: function() {
            $(".loader").hide();
        },
        error: function(result) {
            displayConfirmationMessage(errMessage);
        }
    });
}


function setProfilePicProcess(pictureID) {
    $.ajax({
        type: 'POST',
        url: SITE_URL+'/social/MobPhoto',
        timeout: 10000,
        data: {"pictureId": pictureID, "perform": 'mobSetProfilePic'},
        success: function(d) {
	    CommonErrorHandling(d);
            if (d.label == "Successfully set profile pic") {
                window.location=SITE_URL + "/profile/viewprofile.php?ownview=1";
            }
            else if (d == "LOGOUT") {
                displayConfirmationMessage("Login to perform this action");
            }
            else {
                displayConfirmationMessage(errMessage);
            }
        },
        beforeSend: function() {
            $(".loader").show();
        },
        complete: function() {
            $(".loader").hide();
        },
        error: function(result) {
		displayConfirmationMessage(errMessage);
        }
    });
}

window.onload = function() {
	$(".panzoom").panzoom();
}
