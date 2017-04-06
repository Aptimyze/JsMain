var count = 0;
var retry = 0;
var completed = 0;
var newPhotoCount = 0;
var currentEvent;
var formDataArray = [];
var statusArr = [];
var PictureIdArr = [];
var ajaxCurrentRequest;
var div;
var percentUploaded=0;
var autoScrollingDone = 0;
var compLoadedImagesArr = {};
var compLoadedFinalImagesArr = {};
var totalCount=0;
var uploadPerTime=0;
var completedPerTime = 0;
function uploadFromComputer()
{
            newPhotoCount = successfulUploads - newDeletedCount;
            totalCount = (parseInt(alreadyPhotoCount) + parseInt(newPhotoCount)- parseInt(oldDeletedCount));
    if (totalCount >= maxNoOfPhotos){
        displayConfirmationMessage('You can have only ' + maxNoOfPhotos + ' photos in your profile.<br>Delete some existing photos first');
        $("#addMore").hide();
    }
    else
        $("input[id='file']").click();
}
$(document).ready(function()
{
    if (window.File && window.FileList && window.FileReader) {

        $("#file").on("change", function(event) {
        $('html,body').animate({scrollTop:$("#profileImageId").offset().top}, 0);
            event.preventDefault();
            if($("#file").val()!="")
	    {
                createPreviewTextHtm();
		$(".addPhotoBlankDivs").remove();
            }

            var files = event.target.files;
		showPhotoLoader();
            newPhotoCount = successfulUploads - newDeletedCount;
            totalCount = (parseInt(alreadyPhotoCount) + parseInt(newPhotoCount)- parseInt(oldDeletedCount));
	var canBeUploaded = 20-totalCount;
	if(files.length==0)
		return;
	if(files.length<canBeUploaded)
		canBeUploaded = files.length;
	uploadPerTime=canBeUploaded;
	completedPerTime = 0;
	nowUploadingTotal = canBeUploaded;
	nowUploaded = 0;
        $(".continueDiv").hide();
	$("#nowUploaded").html(nowUploaded + " of "+ nowUploadingTotal );
	$("#nowUpload").show();
            for (var i = 0; (i < canBeUploaded) ; i++)
            {
                var file = files[i];                
                var fileType = file.type;                
                if(!fileType) 
		{
                        fileType="image/"+file.name.split(".")[1];
                        var fileTypeNull=1;
                }                
                /*if (imageFormat.indexOf(fileType) == -1) // image type check
                {
		lastAction="UPLOAD";
                    ajaxRequestToTrack(profileId,"fileTypeError","File Type-"+fileType);
		    var message = "Only jpg/jpeg images are supported";
		    if(file.name)
			message = getMessageWithFileName(file.name,message);
                    displayConfirmationMessage(message,1);
		    completedPerTime++;
                    continue;
                }*/
                if (maxPhotosPresent()) // image type check
                {
		    removePreviewText();
                    ajaxRequestToTrack(profileId,"maxCount","maxCount -"+maxNoOfPhotos);
                    displayConfirmationMessage('You can have only ' + maxNoOfPhotos + ' photos in your profile.<br>Delete some existing photos first',1);
		    completedPerTime++;
                    continue;
                }
                /*if (((file.size) > (appMaxPhotoSize*1048576))||((file.fileSize) > (appMaxPhotoSize*1048576))) // image size check
                {
		lastAction="UPLOAD";
                    ajaxRequestToTrack(profileId,"sizeError","File Size"+file.size / 1048576);
		    var message = "Image size cannot be more than " + appMaxPhotoSize + "MB";
		    if(file.name)
			message = file.name+" - "+message;
                    displayConfirmationMessage(message,1);
		    completedPerTime++;
                    continue;
                }*/
                var picReader = new FileReader();
                picReader.onload = (function (file){
		return function(event) 
		{
                    var picFile = event.target;
                    var imageData = picFile.result;
                    var formElement = document.getElementById("submitForm");
                    var dataToBeSent = new FormData();
		    dataToBeSent.append("photo",file);
		    dataToBeSent.append("uploadSource","desktopGallery");
		    dataToBeSent.append("perform","mobUploadPhoto");
                    count++;
		    newPhotoCount = successfulUploads - newDeletedCount;
		    totalCount = (parseInt(alreadyPhotoCount) + parseInt(newPhotoCount)- parseInt(oldDeletedCount));
		    compLoadedImagesArr[count]=imageData;
		    setPhotoInHtm(imageData,"22",count);
		    removePreviewText();
                    formDataArray[count] = dataToBeSent;
                    $("#file").val("");
                    if (count == 1) {
                        sendAjaxRequest(count,file.name);
                    }
                    else {
                        checkFlag(count,file.name);
                    }
                    //delete picFile;               
		}})(file);
		picReader.readAsDataURL(file);
            }
		afterUpload();
    });
}
});
