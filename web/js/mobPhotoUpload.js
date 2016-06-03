function handlePhotoSelect(evt) {
    var files = evt.target.files; // FileList object
	if(files.length>=1)
	{
		$("#uploadPhotoButton").hide();
		$("#uploadPhotoLoader").show();
		document.uploadPhotoForm.submit();
	}
  }
function handlePhotoSelect1(evt) {
	var files = evt.target.files;
	if(files.length>=1)
        {
                $("#uploadPhotoButton1").hide();
                $("#uploadPhotoLoader1").show();
                //document.uploadPhotoForm1.submit();
		$("#uploadPhotoForm1").submit();
        }
}

function handlePhotoFromPhotoRequestPage(id)
{
	$("#uploadPhotoButton"+id).hide();
     	$("#uploadPhotoLoader"+id).show();
    	//document.uploadPhotoForm1.submit();
     	$("#uploadPhotoForm"+id).submit();
}
