var fbPhotosArr={};
var albumData;
function displayConfirmationMessage(message,showErrorBox,pictureid)
{
	if(showErrorBox==1)
	{
		errorCount++;
		var listHtml = $("#error").html();
		  listHtml = listHtml.replace(/{{ERRORTEXT}}/g, message);
		  listHtml = listHtml.replace(/{{COUNT}}/g, errorCount);
		if(pictureid)
		{
			$(".uploading"+pictureid).parent().after(listHtml);
		}
		else
		{
			$('#photoFolder').append(listHtml);
		}
		$("#photoFolder li#previewTxt").remove();
		$("#errorText").html(message);
	}
}

function sendAjaxRequest(count,filename) 
{
    var pUrl;
    if (typeof imageCopyServer === 'undefined' || imageCopyServer == '')
        pUrl = "/api/v3/social/uploadPhoto";
    else
        pUrl = "/"+imageCopyServer+"/api/v3/social/uploadPhoto";
    //alert(imageCopyServer+" :: "+pUrl);
    currentEvent = count;
    dataToBeSent = formDataArray[count];
    statusArr[completed + 1] = 2;
    ajaxCurrentRequest = $.myObj.ajax({
        url: pUrl,
        type: "POST", // Type of request to be send, called as method
        timeout: 600000,
        data: dataToBeSent, // Data sent to server, a set of key/value pairs representing form fields and values 
        contentType: false, // The content type used when sending data to the server. Default is: "application/x-www-form-urlencoded"
        cache: false, // To unable request pages to be cached
        processData: false, // To send DOMDocument or non processed data file it is set to false (i.e. data should not be in the form of string)
        xhr: function() {  // Custom XMLHttpRequest
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) { // Check if upload property exists
                myXhr.upload.addEventListener('progress', progressHandlingFunction, false); // For handling the progress of the upload

            }
            return myXhr;
        }, success: function(d)                 // A function to be called if request succeeds
        {
//            CommonErrorHandling(d);
            completed++;
            if (d.label == "Successfully uploaded" && d.PICTUREID!=null) 
	    {
		successfulUploads++;
		compLoadedFinalImagesArr[d.PICTUREID] = compLoadedImagesArr[count];
                $('.uploading' + completed).removeClass("opa50 uploading"+completed).addClass("uploading"+d.PICTUREID);
                $('.uploadingBar' + completed).remove();
                $('.uploading' + completed).css("opacity", "1");
                $('.uploadingPercent' + completed).remove();
		$('.dp' + completed).attr("data-pictureid",d.PICTUREID).removeClass("dp"+completed).addClass("dp"+d.PICTUREID);
		bindCardEvents(d.PICTUREID);
                statusArr[completed] = 1;
                PictureIdArr[completed] = d.PICTUREID;
		nowUploaded++;
		$("#nowUploaded").html(nowUploaded + " of "+ nowUploadingTotal );
                var percentUpload = (nowUploaded / nowUploadingTotal) * 100;
		animateLoader(percentUpload);
		if(maxPhotosPresent())
		{
			disableUpload();
		}
		if(profilePicPictureId==null||profilePicPictureId=='')
		{
			getProfilePic();
		}
            }
            else {
                statusArr[completed] = 1;
                PictureIdArr[completed] = 0;
                $('.uploading' + completed).css("opacity", "0.1");
                $('.uploadingBar' + completed).hide();
		var message = "Something went wrong please try again";
		if(filename)
                        message = getMessageWithFileName(filename,message);
		displayConfirmationMessage(message,1);
		$(".uploading"+completed).parent().remove();
                //ajaxRequestToTrack(profileId,"action","RETRY");
            }
            $('.uploadingBar' + completed).hide();
        },
        beforeSend: function() {
            $('.uploadingPercent' + currentEvent).css({width: 0 + "%"});
        },
        complete: function() {
		lastAction="UPLOAD";
		completedPerTime++;
		afterUpload();
		setChangePhotoButton();
        },
        error: function(result,status) {
            completed++;
            $('.uploading' + completed).css("opacity", "0.1");
            statusArr[completed] = 1;
            PictureIdArr[completed] = 0;
		displayConfirmationMessage("Something went wrong please try again",1);
		$(".uploading"+completed).parent().remove();
		
        }
    });
}
function handleContinueMessage()
{
	var message;
	switch(lastAction)
	{
		case "UPLOAD":
			message = nowUploaded +" of "+nowUploadingTotal+" photo(s) uploaded successfully!";
			break;
		case "LOAD":
			if(maxPhotosPresent())
			{
				message = "Delete one or more photos, if you wish to add more";
			}
			else if(showConf==1)
				message = "Your profile pic has been set successfully";
			break;
		case "DELETE_SUCCESS":
			message = "Photo deleted successfully";
			break;
		case "DELETE_ERROR":
			message = "Something went wrong, please try again";
			break;
		case "MAX_PHOTO":
			message = "Delete one or more photos, if you wish to add more";
			break;
	}
	if(message)
		showContinueMessage(message);
	else
	{
		$(".continueDiv").hide();
	}
}
function afterUpload()
{
	if(uploadPerTime==completedPerTime)
	{
		hideLoader();
		handleContinueMessage();
	}
}
function showMessageZeroMorePhoto() {
    newPhotoCount = successfulUploads - newDeletedCount;
    totalCount = (parseInt(alreadyPhotoCount) + parseInt(newPhotoCount)- parseInt(oldDeletedCount));

    //added for showing messages when photo count is less than 3
    if ( totalCount < 3 && totalCount > 0 )
    {
        $('#morePhotoUploaded p:first').prop('id',"");
        $('#morePhotoUploaded button:first').prop('id',"");

        $('#lessPhotoUploaded p:first').prop('id',"continueText");
        $('#lessPhotoUploaded button:nth-child(2)').prop('id',"continue");

        $("#morePhotoUploadedMessage").hide();
        $("#lessPhotoUploadedMessage").show();
        $("#lessPhotoUploaded").show();
        $("#morePhotoUploaded").hide();
    }
    else
    {
        $('#lessPhotoUploaded p:first').prop('id',"");
        $('#lessPhotoUploaded button:nth-child(2)').prop('id',"");

        $('#morePhotoUploaded p:first').prop('id',"continueText");
        $('#morePhotoUploaded button:first').prop('id',"continue");

        $("#lessPhotoUploadedMessage").hide();

        $("#lessPhotoUploaded").hide();
        
        $("#morePhotoUploadedMessage").show();
        $("#morePhotoUploaded").show();
    }
   
}

function showContinueMessage(message)
{
    showMessageZeroMorePhoto();
	$("#nowUpload").hide();
	$("#continueText").html(message);
	$(".continueDiv").show();
}
function progressHandlingFunction(e) {

    if (e.lengthComputable) {
        var percent = (e.loaded / e.total) * 100;
        $('.uploadingPercent' + currentEvent).css({width: percent + "%"});
    }
    return false;
}
function checkFlag(now,filename) {
    var current = now - 1;
    if (statusArr[current] != 1 && statusArr[current] != 3) {
        window.setTimeout(checkFlag, 1000, now); /* this checks the flag every 100 milliseconds*/
    } else if (statusArr[now] != 1 && statusArr[now] != 3) {
        sendAjaxRequest(now,filename);
    }
    else {
    }
}

function setProfilePicProcess(pictureID) {
    $.myObj.ajax({
        type: 'POST',
        url: '/api/v1/social/setProfilePhoto',
        data: {"pictureId": pictureID},
        success: function(d) {
		lastAction="LOAD";
            //CommonErrorHandling(d);
            if (d.label == "Successfully set profile pic") {
		profilePicPictureId=pictureID;
		$(".js-overlay").hide();
		$(".selectPhoto").hide();
		$("body").removeClass("scrollhid");
                window.location="/social/addPhotos?cropper=1";
            }
            else if (d == "LOGOUT") {
                displayConfirmationMessage("Login to perform this action");
            }
            else {
                displayConfirmationMessage("setting profile pic unsuccessfull");
		setProfilePicErrorHandling();
            }
        },
        beforeSend: function() {
//            $(".loader").show();
        },
        complete: function() {
  //          $(".loader").hide();
        },
        error: function(result,status) {
		if(status==="timeout")
			displayConfirmationMessage("Timeout");
		setProfilePicErrorHandling();
        }
    });
}
function getProfilePic()
{
    $.myObj.ajax({
        type: 'POST',
        url: '/api/v1/social/getProfilePhoto',
        success: function(d) {
                lastAction="LOAD";
            //CommonErrorHandling(d);
            if (d.label == "success profile pic") {
                profilePicPictureId = d.profilePicPictureId;
            }
            else if (d == "LOGOUT") {
                displayConfirmationMessage("Login to perform this action");
            }
        },
        beforeSend: function() {
//            $(".loader").show();
        },
        complete: function() {
  //          $(".loader").hide();
        },
        error: function(result,status) {
                if(status==="timeout")
                        displayConfirmationMessage("Something went wrong please try again");
                setProfilePicErrorHandling();
        }
    });
}

function setProfilePicErrorHandling()
{
	var message = "Something went wrong please try again";
	$("#selectProfilePhotoError").html(message);
	$("#selectOtherPhoto").show();
	$(".phsel").removeClass("phsel");
}
function deletionProcess(pictureID) {
showCommonLoader();
    $.myObj.ajax({
        type: 'POST',
        url: '/api/v3/social/deletePhoto',
        data: {"pictureId": pictureID, "perform": 'mobDeletePhoto'},
        success: function(d) {
		if(profilePicPictureId==pictureID)
		{
			profilePicPictureId=null;
			havePhoto=null;	
		}
            //CommonErrorHandling(d);
            if (d.label == "Successfully deleted") {
		lastAction="DELETE_SUCCESS";
		if(pictureID in compLoadedFinalImagesArr)
			newDeletedCount++;
		else
			oldDeletedCount++;
		enableUpload();
    newPhotoCount = successfulUploads - newDeletedCount;
    totalCount = (parseInt(alreadyPhotoCount) + parseInt(newPhotoCount)- parseInt(oldDeletedCount));
		$(".selectPhotoLi"+pictureID).remove();
		delete compLoadedFinalImagesArr[pictureID];
                $(".uploading" + pictureID).parent().remove();
//                albumCaptionTotal--;
  //              albumCaptionNow--;
    //            var currentPic = albumCaptionNow;

//                while (currentPic < imageArray.length) {
  //                  var nextPic = currentPic + 1;
    //                imageArray[currentPic] = imageArray[nextPic];
      //              currentPic++;
      //          }
     //           imageArray = imageArray.splice(0, imageArray.length - 1);
            }
            else if (d == "LOGOUT") {
                displayConfirmationMessage("Login to perform this action");
            }
            else {
			lastAction="DELETE_ERROR";
                        displayConfirmationMessage("deletion error");
            }
        },
        beforeSend: function() {
            $(".loader").show();
            //CancelProcess();
        },
        complete: function() {
		hideCommonLoader();
		setChangePhotoButton();
		handleContinueMessage();
        },
        error: function(result,status) {
		lastAction="DELETE_ERROR";
		if(status==="timeout")
                        displayConfirmationMessage("deletion error");
        }
    });
}
function maxPhotosPresent()
{
		newPhotoCount = successfulUploads - newDeletedCount;
		totalCount = (parseInt(alreadyPhotoCount) + parseInt(newPhotoCount)- parseInt(oldDeletedCount));
                if (totalCount>=20) // image type check
                {
                    return true;
                }
		return false;
}
function ajaxRequestToTrack(profileId, trackType, trackInfo) {
            $.myObj.ajax({
                url: "/api/v3/social/MobPhotoTracking?time="+Date.now()+"", // Url to which the request is send
                type: "POST", // Type of request to be send, called as method
                data: {"profileId": profileId, "trackType" : trackType,"trackInfo" : trackInfo}, // Data sent to server, a set of key/value pairs representing form fields and values 
                timeout: 10000,
                success: function(d)            // A function to be called if request succeeds
                {
                }
            });
}

function setNoPhotoFrames()
{
	if(havePhoto!="Y"&&havePhoto!="U")
	{
		listHtml = $("#addPhoto").html();
		for(i=0;i<4;i++)
			$('#photoFolder').append(listHtml);
	}
}
function showPhotoLoader() 
{
	$(".photoLoader").show();
    $(".loaderLinearPhoto").animate({ width: '5%' }, 'slow');
}
function animateLoader(percent)
{
	$(".loaderLinearPhoto").animate({ width: percent + '%' }, 'slow');
}
function hideLoader()
{
    $(".loaderLinearPhoto").stop().animate({
        width: "100%"
    }, "fast", function() {
        $('.photoLoader').fadeOut(1000, function() {
        });
    });
}
function disableUpload()
{
	$(".fromComp").addClass("pubg9").removeClass("bg_pink cursp");
	$(".fromComp > div:first-child").removeClass("pubg3").addClass("pubg8");
	$(".fromFb").addClass("pubg9").removeClass("pubg4").remove("cursp");
	$(".fromFb > div:first-child").removeClass("pubg5").addClass("pubg8");
	handleContinueMessage();
}
function enableUpload()
{
	$(".fromComp").removeClass("pubg9").addClass("bg_pink cursp");
	$(".fromComp > div:first-child").addClass("pubg3").removeClass("pubg8");
	$(".fromFb").removeClass("pubg9").addClass("pubg4 cursp");
	$(".fromFb > div:first-child").addClass("pubg5").removeClass("pubg8");
}
function setChangePhotoButton()
{
    newPhotoCount = successfulUploads - newDeletedCount;
    totalCount = (parseInt(alreadyPhotoCount) + parseInt(newPhotoCount)- parseInt(oldDeletedCount));
	if(totalCount>1)
		$(".changePhoto").show();
	else
		$(".changePhoto").hide();
}
function importFbRequest(val)
{
	 var pUrl;
    if (typeof imageCopyServer === 'undefined' || imageCopyServer == '')
        pUrl = "/api/v3/social/importFb";
    else
        pUrl = "/"+imageCopyServer+"/api/v3/social/importFb";
    //alert(imageCopyServer+" : "+pUrl);
    $.myObj.ajax({
        type: 'POST',
        url: pUrl,
        timeout: 120000,
	data:{urlToSave:val},
        success: function(d) {
            completed++;
            if (d.label == "success upload" && d.uploaded==true && d.PICTUREID!=null)
            {
                successfulUploads++;
                compLoadedFinalImagesArr[d.PICTUREID] = val;
                $('.uploading' + completed).removeClass("opa50 uploading"+completed).addClass("uploading"+d.PICTUREID);
                $('.uploadingBar' + completed).remove();
                $('.uploading' + completed).css("opacity", "1");
                $('.uploadingPercent' + completed).remove();
                $('.dp' + completed).attr("data-pictureid",d.PICTUREID).removeClass("dp"+completed).addClass("dp"+d.PICTUREID);
                bindCardEvents(d.PICTUREID);
                statusArr[completed] = 1;
                PictureIdArr[completed] = d.PICTUREID;
                nowUploaded++;
                $("#nowUploaded").html(nowUploaded + " of "+ nowUploadingTotal );
                var percentUpload = (nowUploaded / nowUploadingTotal) * 100;
                animateLoader(percentUpload);
                if(maxPhotosPresent())
                {
                        disableUpload();
                }
		if(profilePicPictureId==null||profilePicPictureId=='')
		{
			getProfilePic();
		}
	    }
            else {
                statusArr[completed] = 1;
                PictureIdArr[completed] = 0;
                $('.uploading' + completed).css("opacity", "0.1");
                $('.uploadingBar' + completed).hide();
                displayConfirmationMessage("Something went wrong please try again",1);
                $(".uploading"+completed).parent().remove();
                //ajaxRequestToTrack(profileId,"action","RETRY");
            }
            $('.uploadingBar' + completed).hide();
        },
        beforeSend: function() {
            $('.uploadingPercent' + currentEvent).css({width: 0 + "%"});
        },
        complete: function() {
                lastAction="UPLOAD";
                completedPerTime++;
                afterUpload();
                setChangePhotoButton();
        },
        error: function(result,status) {
            completed++;
            $('.uploading' + completed).css("opacity", "0.1");
            statusArr[completed] = 1;
            PictureIdArr[completed] = 0;
		displayConfirmationMessage("Something went wrong please try again",1);
                $(".uploading"+completed).parent().remove();

        }
    });

}
function toggleTick(ele)
{
        newPhotoCount = successfulUploads - newDeletedCount;
        totalCount = (parseInt(alreadyPhotoCount) + parseInt(newPhotoCount)- parseInt(oldDeletedCount));
        var canBeUploaded = 20-totalCount;

	var albumPosition = $(".js-importAlbum").not('.cursp').parent().index();
	var picPosition = ele.index();
	var picUrl = ele.children("i.js-tick").attr("data-fbid");

	if(ele.children("i.js-tick").hasClass("phsel"))
	{
		ele.children("i.js-tick").removeClass("phsel");
		delete fbImportUrls["'"+albumPosition+"_"+picPosition+"'"];
		delete fbImportData["'"+albumPosition+"'"]["'"+picPosition+"'"];
		var fbSelected=Object.keys(fbImportUrls).length;
		$("#js-fbCountDiv").html(fbSelected+"/"+canBeUploaded+" Selected");
		return;
	}

	var fbSelected=Object.keys(fbImportUrls).length;

	if(fbSelected>=canBeUploaded)
	{
		$("#js-fbCountDiv").html("You have already selected maximum number of photos(20)");
		return;
	}
	if(!ele.children("i.js-tick").hasClass("phsel"))
	{
		ele.children("i.js-tick").addClass("phsel");
		if(!fbImportData["'"+albumPosition+"'"])
			fbImportData["'"+albumPosition+"'"]={};
		fbImportUrls["'"+albumPosition+"_"+picPosition+"'"]=picUrl;
		fbImportData["'"+albumPosition+"'"]["'"+picPosition+"'"]=picUrl;
		var fbSelected=Object.keys(fbImportUrls).length;
		$("#js-fbCountDiv").html(fbSelected+"/"+canBeUploaded+" Selected");
	}
}

function uploadFb()
{
        $('html,body').animate({scrollTop:$("#profileImageId").offset().top}, 0);
	createPreviewTextHtm();
	$(".addPhotoBlankDivs").remove();

	showPhotoLoader();

	newPhotoCount = successfulUploads - newDeletedCount;
	totalCount = (parseInt(alreadyPhotoCount) + parseInt(newPhotoCount)- parseInt(oldDeletedCount));

        var canBeUploaded = 20-totalCount;
	var fbToBe = Object.keys(fbImportUrls).length;
        if(fbToBe<canBeUploaded)
                canBeUploaded = fbToBe;

        uploadPerTime=canBeUploaded;
        completedPerTime = 0;
        nowUploadingTotal = canBeUploaded;
        nowUploaded = 0;

        $(".continueDiv").hide();
        $("#nowUploaded").html(nowUploaded + " of "+ nowUploadingTotal );
        $("#nowUpload").show();

	var i = 0;
	$.each(fbImportUrls,function(ind,value)
	{
		if(i < canBeUploaded)
		{
			count++;
			compLoadedImagesArr[count]=value;
			setPhotoInHtm(value,"22",count);
			removePreviewText();
			importFbRequest(value);
			i++;
		}
	});
	$("#js-fbCountDiv").html("");	
	fbImportData={};
	fbImportUrls={};
}

function getMessageWithFileName(filename,message)
{
	return "<div class='textTru' style='max-width:140px;display:inline-block;'>"+filename+"</div> <div>"+message+"</div>";
}
function loadPhotos(key)
{
        $("#js-addImportPhotos").html('');
        $(".js-ImportLoader2").show();
        $(".js-addImportPhotos").hide();
        $("#js-addImportAlbum").addClass("js-disabled");
        $("#js-addImportAlbum").removeClass("js-disabled");
        var albumStructure = $("#js-addImportPhotosInd").html();

        $("#js-addImportPhotos").html('');
        var cnt = 0;
        $.each(albumData[key]['allPhotos'], function(key1, val1)
        {
                
                var mapObj = {
                        '{photo_number}':removeNull(key1),
                        '{saveUrl}': removeNull(albumData[key]['allPhotosToSave'][key1]),
                };
                var albumTuple = $.ReplaceJsVars(albumStructure, mapObj);
                $("#js-addImportPhotos").append(albumTuple);
                var htm = "<img src="+removeNull(val1)+" class='pudim5 brdr-1'/>";
                if (key1 == 0)
                {
                        $(".photonumber").append(htm);
                }
                else
                {
                        $(".photonumber"+key1).append(htm);
                }
                cnt++;
        });
        if(fbImportData["'"+key+"'"])
        {
                $.each(fbImportData["'"+key+"'"], function(k,v)
                {
                        var pos=parseInt(k.replace(/'/g,""))+1;
                        $("#js-addImportPhotos li:nth-child("+pos+")").children("i").addClass("phsel");
                });
        }
        $(".js-ImportLoader2").hide();
        $(".js-addImportPhotos").show()
	$(".js-importAlbum").addClass('cursp');
	$("#album"+albumData[key]['albumId']).removeClass('cursp');
}
function loadAlbums()
{
        var albumStructure = $("#js-addImportAlbumInd").html();
        var albumOffset = 0;
        if(albumsCount!=0)
        {
        $.each(albumData,function(key,val){
                var mapObj = {
                        '{importAlbumName}': removeNull(val.albumName),
                        '{importAlbumCount}': removeNull(val.photosCountInAlbum),
                        '{importAlbumId}': removeNull(val.albumId),
                        '{importAlbumOffset}': key
                };
                var albumTuple = $.ReplaceJsVars(albumStructure, mapObj);
                $("#js-addImportAlbum").append(albumTuple);
                var htm = "<img src="+removeNull(val.allPhotos[0])+" class='pudim3 vtop'/>";
                $("#album"+val.albumId).append(htm);
                ++albumOffset;
        });
                $(".js-ImportLoader").hide();
                $(".js-ImportLoaderHide").show();
                $(".js-fbImport").show();
        }
        else
        {
                $("#albumImportLoader").html('<div>You have no albums in your facebook account. To add photos from another source <a href="/social/addPhotos?uploadType=F">Click here</a></div>');
        }
        $(".js-importAlbum").addClass('cursp');
}
function createFbPage()
{
	hideLayers();
	$(".js-fbImport").show();
	if(!albumData.hasOwnProperty("0"))
	{
		var msg = '<div>Something went wrong, please try again</div>';
		$("#albumImportLoader").html(msg);
		$(".js-importAlbum").addClass('cursp');
		$(".js-ImportLoaderHide").hide();
		$(".js-ImportLoader").show();
		$("#uploadFb").hide();
	}
	else if(!albumData['0'].hasOwnProperty("albumId"))
	{
		var msg = '<div>No albums present in loggedin facebook profile</div>';
		$("#albumImportLoader").html(msg);
		$(".js-importAlbum").addClass('cursp');
		$(".js-ImportLoaderHide").hide();
		$(".js-ImportLoader").show();
		$("#uploadFb").hide();
	}
	else
	{
        $("#js-addImportAlbum").html('');
        $("#js-addImportPhotos").html('');
        $("#selectedAlbumPointer").css('top', '30px');
        albumsCount = albumData.length;
        loadImportedPhotosSlider(albumsCount);
        loadAlbums();
        setActiveAlbumPointer(0);
        loadPhotos(0);
        $("body").on("click",'.js-importAlbum',function(){
                var offset = $(this).data("id");
                if($(this).hasClass('cursp'))
                {
                        setActiveAlbumPointer(offset);
                        loadPhotos(offset);
                }
        });
	}
}
