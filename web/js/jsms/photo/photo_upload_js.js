function showUploadOption() {
	$("#FadedRegion" ).show();
	$("#optionForUpload").show();
    $("#FadedRegion").css("z-index","1003"); 
	$("#addPhotoAlbumPage").css("overflow","hidden");          
}
function hideUploadOption(param) {
	if(param=="goBack")
		window.location=SITE_URL + '/profile/viewprofile.php?ownview=1';
        $("#FadedRegion" ).hide();
	$("#optionForUpload").hide(); 
	$("#addPhotoAlbumPage").css("overflow","auto");
}
function selectUploadOption() {
	//hideUploadOption();
	setTransition_AlbumToUpload();
	hideOrNot=0;
	$("#addMoreButton").focus().trigger("click");
	if(hideOrNot==0)
		setTransition_UploadToAlbum();
}
function setTransition_AlbumToUpload() {
	$("#addPhotoAlbumPage").hide();
	$("#photoUploadProgress").show();
}
function setTransition_UploadToAlbum() {
	$("#addPhotoAlbumPage").show();
	$("#photoUploadProgress").hide();
}
$(document).ready(function(){
	PhotoUpload();
});

function PhotoUpload(){
showUploadOption();
	$("#addPhotoMobile").click(function(){
		showUploadOption();
	});
	if(selectFile == 1) 
		$("#addPhotoMobile").trigger("click");

	$("#FadedRegion").click(function(){
		//window.location=SITE_URL + '/profile/viewprofile.php?ownview=1';
		//hideUploadOption("goBack");
		history.back();
	});
	$("#gallery").click(function(){
		selectUploadOption();
	});
	$("#camera").click(function(){
		$("#file").attr("capture","camera");
		selectUploadOption();
		$("#file").removeAttr("capture");
	});
	$(".addphotofromalbum").click(function() 
	{
		 showUploadOption();
	});
	$("#privacy_button").bind("click",function()
	{
	$("#privacyoptionshow").show();
	$("#addPhotoAlbumPage").hide();
});
	$("#privacyoptionclose").click(function()
       	{
                $("#privacyoptionshow").hide();
                $("#addPhotoAlbumPage").show();
});

};

