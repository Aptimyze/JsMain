var vwid = $(window).width();
var vhgt = $(window).height();
var divhgt = $("#containerDiv").height();
var screenH = $(window).height();
$('div.outerdiv').css("height", vhgt);

if(vhgt>344) {
$("#addPhotoAlbumPage").css("height", vhgt-99);
} 
if (vwid >= 280 && vwid <= 320)
{
    $('.photobox').css({"height":"86", "width":"86"});
}
else if (vwid >= 321 && vwid <= 360)
{
    $('.photobox').css({"height":"97", "width":"97"});
}
else if (vwid >= 361)
{
    $('.photobox').css({"height":"128", "width":"128"});
}

var count = 0;
var retry = 0;
var completed = 0;
var deletedCount = 0;
var newPhotoCount = 0;
var currentEvent;
var formDataArray = [];
var statusArr = [];
var PictureIdArr = [];
var ajaxCurrentRequest;
var div;
var percentUploadedAndroid=0;
var autoScrollingDone = 0;
var generatingPreview = '<div class="fl pu_mr1 pu_mr2 wrap"><div class="photobox brdr18 txtc posrel" style="color:#d9475c;font-size:14px;text-align:center;font-family:"Roboto Light, Arial, sans-serif, Helvetica Neue",Helvetica;"><br><br><center>Generating<br>Preview....</center></div></div>';
window.onload = function() {

    if (window.File && window.FileList && window.FileReader) {

        $("#file").on("change", function(event) {
	    event.preventDefault();
	    var output = document.getElementById("result");
            existingNode = document.getElementById("addMore");
            setTransition_AlbumToUpload();
            
	    if($("#file").val()!=""){
  		var generatingDiv = document.createElement("div");
		generatingDiv.innerHTML = generatingPreview;
		output.insertBefore(generatingDiv, existingNode);
            }
            else{
	    	    setTransition_UploadToAlbum();
	    }

	    var files = event.target.files;
	    for (var i = 0; i < files.length; i++)
            {
		setTransition_AlbumToUpload();
                var file = files[i];

		var fileType = file.type;
		if(!fileType) {
			//fileType = fileName.substring(fileName.lastIndexOf('-') + 1);
			fileType="image/"+file.name.split(".")[1];
			var fileTypeNull=1;
		}
        	if (imageFormat.indexOf(fileType) == -1) // image type check
                {
                    generatingDiv.innerHTML = "";
                    if($(".imagePreview:visible").length==0){
			setTransition_UploadToAlbum();
                    }
                    ajaxRequestToTrack(profileId,"fileTypeError","File Type-"+fileType);
                    displayConfirmationMessage("Only jpg/jpeg images are supported.");
                    $("#file").val("");
                    continue;
                }
                if ($(".imagePreview:visible").length>=20) // image type check
                {
                    generatingDiv.innerHTML = "";
                    ajaxRequestToTrack(profileId,"maxCount","maxCount -"+maxNoOfPhotos);
                    displayConfirmationMessage('You can have only ' + maxNoOfPhotos + ' photos in your profile.<br>Delete some existing photos first.');
                    $("#file").val("");
                    continue;
                }
                if (file.size / 1048576 > appMaxPhotoSize) // image size check
                {
                    generatingDiv.innerHTML = "";
                    if($(".imagePreview:visible").length==0){
			setTransition_UploadToAlbum();
                    }
                    ajaxRequestToTrack(profileId,"sizeError","File Size"+file.size / 1048576);
                    displayConfirmationMessage("Image size cannot be more than " + Math.round(file.size/1048576) + "MB.");
                    $("#file").val("");
                    continue;
                }
                hideOrNot = 1;
                hideUploadOption();
                var picReader = new FileReader();
                picReader.onload = function(event) {
                    var picFile = event.target;
                    var formElement = document.getElementById("submitForm");
                    var dataToBeSent = new FormData(formElement);
		    var imageData = picFile.result;
		    //delete picFile;		    
		    if(fileTypeNull == 1) {
			    imageData = imageData.replace("data:", "data:"+fileType+";");
		    }
                    count++;
                    var div = document.createElement("div");
                    div.innerHTML = '<div class="fl pu_mr1 pu_mr2 imagePreview wrap' + count + '">' + '<div class="photobox posrel">' + '<div class="posabs txtc fontlig color2 f14 lh30 pum35 dispnone retryMessage"><i class="up_sprite pu_retry pu_mt2"></i>Retry</div>' + "<a href='javascript: void(0)'><img class='pointerAuto classimg1 opa50 uploading" + count + "' src='" + imageData + "'" + "title='" + file.name + "'/></a>" + '<div id="delPic" class="posabs pu_pos1" onclick="deleteThisPhoto(' + count + ')"><a href="#" class="up_sprite pu_cross"></a></div>' + '<div class="posabs fullwid pu_pos2 stillUploading uploadingBar' + count + '">' + '<div class="wid80p brdr12">' + '<div class="bg7 hgt10 mrl0 uploadingPercent' + count + '" style="width:0px;"></div>' + '</div></div></div></div>';
		    output.insertBefore(div, existingNode);
		    generatingDiv.innerHTML = "";
                    formDataArray[count] = dataToBeSent;
		    $("#file").val("");
                    if (count == 1) {
                        sendAjaxRequest(count);
                    }
                    else {
                        checkFlag(count);
                    }
		    //delete imageData;
		    
		    if(autoScrollingDone == 0){
			    var topAddButtonToScroll=0;
			    
			    if($(".skipped").is(':visible'))
				    topAddButtonToScroll = $(".skipped").offset().top-100;
			    else
				    topAddButtonToScroll = $(".choosePP").offset().top-100;
			    if($("#addMoreButton").offset().top>=topAddButtonToScroll){
					$(".padProgress").animate({ scrollTop: $("#addMoreButton").offset().top+$("#addMoreButton").height()}, 600);
					autoScrollingDone = 1;
			    }
		    }
		    
                };
                picReader.readAsDataURL(file);   //Read the image
            }
        });
    }
    else if(debug) {
        console.log("Your browser does not support File API");
    }
}
$("#addMoreButton").click(function(event) {
    newPhotoCount = count - deletedCount - retry;
    var totalCount = (alreadyPhotoCount + newPhotoCount);
    if (totalCount >= maxNoOfPhotos){
        displayConfirmationMessage('You can have only ' + maxNoOfPhotos + ' photos in your profile.<br>Delete some existing photos first.');
        $("#addMore").hide();
    }
    else
        $("input[id='file']").click();
    event.preventDefault();
});

function sendAjaxRequest(count) {
    currentEvent = count;
    dataToBeSent = formDataArray[count];
    statusArr[completed + 1] = 2;
    var purl;
    
    if (typeof imageCopyServer === 'undefined' || imageCopyServer == '')
        purl = SITE_URL + "/social/MobPhoto";
    else
				purl = imageCopyServer+"/social/MobPhoto";
				//alert(purl);
    ajaxCurrentRequest = $.ajax({
        url: purl, // Url to which the request is send
        type: "POST", // Type of request to be send, called as method
        timeout: 300000,
        data: dataToBeSent, // Data sent to server, a set of key/value pairs representing form fields and values 
        contentType: false, // The content type used when sending data to the server. Default is: "application/x-www-form-urlencoded"
        cache: false, // To unable request pages to be cached
        processData: false, // To send DOMDocument or non processed data file it is set to false (i.e. data should not be in the form of string)
        xhr: function() {  // Custom XMLHttpRequest
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) { // Check if upload property exists
            var nua = navigator.userAgent;
	    var is_android = ((nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 && nua.indexOf('AppleWebKit') > -1) && !(nua.indexOf('Chrome') > -1));
	    if(is_android)
		myXhr.upload.addEventListener('progress', progressHandlingFunction("android"), false); // For handling the progress of the upload
	    else
		myXhr.upload.addEventListener('progress', progressHandlingFunction, false); // For handling the progress of the upload
		
            }
            return myXhr;
        }, success: function(d)  		// A function to be called if request succeeds
        {
	    CommonErrorHandling(d);
            completed++;
            if (d.label == "Successfully uploaded" && d.PICTUREID!=null) {
                $('.uploading' + completed).removeClass("opa50");
                $('.uploading' + completed).css("opacity", "1");
                $('.uploadingPercent' + completed).remove();
                statusArr[completed] = 1;
                PictureIdArr[completed] = d.PICTUREID;
            }
            else {
                statusArr[completed] = 1;
                PictureIdArr[completed] = 0;
                $('.uploading' + completed).css("opacity", "0.1");
                $('.wrap' + completed + ' .retryMessage').show();
                $('.uploadingBar' + completed).hide();
                $('.wrap' + completed).attr("onclick", "retryUpload(" + completed + ")");
                ajaxRequestToTrack(profileId,"action","RETRY");
            }
            $('.uploadingBar' + completed).hide();
        },
        beforeSend: function() {
            $('.uploadingPercent' + currentEvent).css({width: 0 + "%"});
            percentUploadedAndroid = 0;
        },
        error: function(result) {
            completed++;
            $('.uploading' + completed).css("opacity", "0.1");
            $('.wrap' + completed + ' .retryMessage').show();
            $('.wrap' + completed).attr("onclick", "retryUpload(" + completed + ")");
            $('.uploadingBar' + completed).hide();
            statusArr[completed] = 1;
            PictureIdArr[completed] = 0;
            ajaxRequestToTrack(profileId,"action","RETRY");
        }
    });
}
function ajaxRequestToDelete(deleteID, deleteThisIndex) {
    $.ajax({
        url: SITE_URL + "/social/MobPhoto?time="+Date.now()+"", // Url to which the request is send
        type: "POST", // Type of request to be send, called as method
        data: {"pictureId": deleteID, "perform": 'mobDeletePhoto'}, // Data sent to server, a set of key/value pairs representing form fields and values 
        timeout: 10000,
        success: function(d)  		// A function to be called if request succeeds
        {
	    CommonErrorHandling(d);
            if (d.label == "Successfully deleted") {
                $('.wrap' + deleteThisIndex).hide();
                statusArr[deleteThisIndex] = 3; //Delete
                deletedCount++;
                deleteAndBack();
                $("#addMore").show();
            }
            else {
                $('.wrap' + deleteThisIndex).show();
		setTransition_AlbumToUpload();
                displayConfirmationMessage("Something went wrong, Please try again.");
            }

        },
        beforeSend: function() {
		if($(".imagePreview:visible").length>1) {
			$('.wrap' + deleteThisIndex).hide();
		}
        },
        error: function(result) {
            $('.wrap' + deleteThisIndex).show();
	    setTransition_AlbumToUpload();
	    displayConfirmationMessage("Something went wrong, Please try again.");
        }
    });
}

function ajaxRequestToTrack(profileId, trackType, trackInfo) {
	    $.ajax({
		url: SITE_URL + "/social/MobPhotoTracking?time="+Date.now()+"", // Url to which the request is send
		type: "POST", // Type of request to be send, called as method
		data: {"profileId": profileId, "trackType" : trackType,"trackInfo" : trackInfo}, // Data sent to server, a set of key/value pairs representing form fields and values 
		timeout: 10000,
		success: function(d)  		// A function to be called if request succeeds
		{
		}
	    });
}

function progressHandlingFunction(e) {
	
	if(e=="android"){
	    function loaderForAndroidUpload(){
		    if(percentUploadedAndroid<41)
			percentUploadedAndroid = percentUploadedAndroid + 8;
		    else if(percentUploadedAndroid<61 && percentUploadedAndroid>40)
			percentUploadedAndroid = percentUploadedAndroid + 4;
		    else if(percentUploadedAndroid<81 && percentUploadedAndroid>60)
			percentUploadedAndroid = percentUploadedAndroid + 2;
		    else if(percentUploadedAndroid<86 && percentUploadedAndroid>80)
			percentUploadedAndroid = percentUploadedAndroid + 1;
		    else if(percentUploadedAndroid<91 && percentUploadedAndroid>85)
			percentUploadedAndroid = percentUploadedAndroid + 0.5;
		    $('.uploadingPercent' + currentEvent).css({width: percentUploadedAndroid + "%"});
		    if(percentUploadedAndroid<90)
			setTimeout(loaderForAndroidUpload, 500);
		    }
	    loaderForAndroidUpload();
	    
    }
    else if (e.lengthComputable) { 
        var percent = (e.loaded / e.total) * 100;
        $('.uploadingPercent' + currentEvent).css({width: percent + "%"});
    }
    return false;
}
function deleteThisPhoto(deleteThisIndex) {
	var uploadedDelete = 0;
    if ((statusArr[deleteThisIndex] == 2 || statusArr[deleteThisIndex] == 1) && (!PictureIdArr[deleteThisIndex] || (PictureIdArr[deleteThisIndex] && PictureIdArr[deleteThisIndex] == 0))) {
        ajaxCurrentRequest.abort();
        $('.wrap' + deleteThisIndex).remove();
        deletedCount++;
        $("#addMore").show();
    }
    else if (statusArr[deleteThisIndex] == 1 && PictureIdArr[deleteThisIndex] && PictureIdArr[deleteThisIndex] != 0) {
        ajaxRequestToDelete(PictureIdArr[deleteThisIndex], deleteThisIndex); 
		uploadedDelete = 1;
    }
    else {
        statusArr[deleteThisIndex] = 3; // 3 means picture deleted from array
        $('.wrap' + deleteThisIndex).remove();
        deletedCount++;
        $("#addMore").show();
    }
    newPhotoCount = count - deletedCount;
    deleteAndBack();
    
}
function deleteAndBack(){
	if($(".imagePreview:visible").length==0) {
		var nua = navigator.userAgent;
		var is_android = ((nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 && nua.indexOf('AppleWebKit') > -1) && !(nua.indexOf('Chrome') > -1) && !(nua.indexOf('UCBrowser') > -1))
		if(is_android)
		{
			location.reload(true);
		}
		else
		{
			setTransition_UploadToAlbum();
			showUploadOption();
		}
	    }
}
function checkFlag(now) {
    var current = now - 1;
    if (statusArr[current] != 1 && statusArr[current] != 3) {
        window.setTimeout(checkFlag, 1000, now); /* this checks the flag every 100 milliseconds*/
    } else if (statusArr[now] != 1 && statusArr[now] != 3) {
        sendAjaxRequest(now);
    }
    else {
    }
}
function retryUpload(which) {

    if (statusArr[which] == 1 && PictureIdArr[which] == 0) {
        count++;
        retry++;
        var retryThis = count;
        $('.wrap' + which).addClass('wrap' + retryThis).removeClass('wrap' + which);
        $('.uploadingPercent' + which).addClass('uploadingPercent' + retryThis).removeClass('uploadingPercent' + which);
        $('.uploadingBar' + which).addClass('uploadingBar' + retryThis).removeClass('uploadingBar' + which);
        $('.uploading' + which).addClass('uploading' + retryThis).removeClass('uploading' + which);
        $('.uploading' + retryThis).css("opacity", "0.8");
        $('.wrap' + retryThis).removeAttr("onclick");
        $('.wrap' + retryThis + " .pu_pos1").removeAttr("onclick").attr("onclick", "deleteThisPhoto(" + retryThis + ")");
        $('.uploadingPercent' + retryThis).css("width", "0px");
        $('.uploadingBar' + retryThis).show();
        $('.wrap' + retryThis + ' .retryMessage').hide();
        formDataArray[count] = formDataArray[which];
        checkFlag(count);
    }
    else {
    }
}

$('.choosePP').click(function(event) {
   if($('.stillUploading:visible').length>0 || $('.wrap:visible').length>0)
	displayConfirmationMessage("Uploading in progress, Please wait.....");
   else
	window.location=SITE_URL + "/social/MobilePhotoAlbum?setProfilePic=1";
});

$('.skipped').click(function(event) {
   if($('.stillUploading:visible').length>0 || $('.wrap:visible').length>0)
	displayConfirmationMessage("Uploading in progress, Please wait.....");
   else
	window.location=SITE_URL + "/profile/viewprofile.php?ownview=1";
});	
	
if(typeof alreadyPhotoCount !=="undefined" && alreadyPhotoCount==0){
    $('#photoUploadProgress').css({"height":vhgt-50});
    $('.padProgress').css({"height":vhgt-50});
	$('.skipped').css("display","none");
}
else{
    $('#photoUploadProgress').css({"height":vhgt-100});
    $('.padProgress').css({"height":vhgt-100});
	$('.skipped').css("display","block");
	$('.choosePP').text("Change Profile Photo");
}
