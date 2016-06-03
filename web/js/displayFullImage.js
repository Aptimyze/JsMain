var objImage = new Image();

function display_image(id,index,total)
{
	document.getElementById("slider").style.display = "none";
	document.getElementById("loader").style.display = "block";

	var pictureIndex = parseInt(index)+1;
	if (index == 0)
	{
		document.getElementById("select_profile_link").style.display = "none";
		document.getElementById("profile_text").style.display = "block";
		document.getElementById("select_profile_link").getElementsByTagName("input")[0].checked = true;
	}
	else
	{	
		document.getElementById("select_profile_link").style.display = "block";
		document.getElementById("profile_text").style.display = "none";
		document.getElementById("select_profile_link").getElementsByTagName("input")[0].checked = false;
	}

	document.getElementById("profileBtn").value = index;
	document.getElementById("pictureIndex").innerHTML=pictureIndex;
	var zzz = document.getElementById("keyword_layer0").getElementsByTagName("input");
	var j = 0;
	for (j=0;j<zzz.length;j++)
	{
		var box_id = "value"+j;
		document.getElementById(box_id).checked = false;
	}
	
	var params = "picId="+id;
	var url = "/social/imageDetails";
	sendRequest('POST',url,params);		
}

function display_image_action(action,total)
{
	document.getElementById("slider").style.display = "none";
        document.getElementById("loader").style.display = "block";
	var currentIndex = document.getElementById("pictureIndex").innerHTML;
	var photoIds = document.getElementById("allPhotoIds").value;
        var photoIdsArray = photoIds.split(",");
	var zzz = document.getElementById("keyword_layer0").getElementsByTagName("input");
	var j = 0;
	for (j=0;j<zzz.length;j++)
	{
		var box_id = "value"+j;
		document.getElementById(box_id).checked = false;
	}

	if (action == 'next')
	{
		if (currentIndex==total)
		{
			currentIndex=1;
		}
		else
		{
			currentIndex=parseInt(currentIndex)+1;
		}
		var id = parseInt(currentIndex)-1;
		document.getElementById("pictureIndex").innerHTML=currentIndex;
        	var picId = photoIdsArray[id];
		if (id == 0)
		{
			document.getElementById("select_profile_link").style.display = "none";
			document.getElementById("profile_text").style.display = "block";
			document.getElementById("select_profile_link").getElementsByTagName("input")[0].checked = true;
		}
		else
		{	
			document.getElementById("select_profile_link").style.display = "block";
			document.getElementById("profile_text").style.display = "none";
			document.getElementById("select_profile_link").getElementsByTagName("input")[0].checked = false;
		}
		document.getElementById("profileBtn").value = id;
	}
	else if (action == 'previous')
	{
		if (currentIndex==1)
                {
                        currentIndex=total;
                }
                else
                {
                        currentIndex=parseInt(currentIndex)-1;
                }
                var id = parseInt(currentIndex)-1;
                document.getElementById("pictureIndex").innerHTML=currentIndex;
                var picId = photoIdsArray[id];
		if (id == 0)
		{
			document.getElementById("select_profile_link").style.display = "none";
			document.getElementById("profile_text").style.display = "block";
			document.getElementById("select_profile_link").getElementsByTagName("input")[0].checked = true;
		}
		else
		{	
			document.getElementById("select_profile_link").style.display = "block";
			document.getElementById("profile_text").style.display = "none";
			document.getElementById("select_profile_link").getElementsByTagName("input")[0].checked = false;
		}
		document.getElementById("profileBtn").value = id;
	}
	else
	{
	}
	
	var params = "picId="+picId;
        var url = "/social/imageDetails";
        sendRequest('POST',url,params);
}

function modify_title()
{
	document.getElementById("picture_title").style.display = "none";
	document.getElementById("edit_picture_title").style.display = "block";
	document.getElementsByName("edit_picture_title")[0].focus();
	document.getElementById("add_title").style.display = "none";
	document.getElementById("edit_title").style.display = "none";
}

function modify_keywords()
{
	var keywords = document.getElementById("picture[0]").value;
	if (keywords)
	{
		var keywords_array = keywords.split(",");
		var i = 0;
		for (i=0;i<keywords_array.length;i++)
		{
			var index = parseInt(keywords_array[i])-1;
			var id = "value"+index;
			document.getElementById(id).checked = true;
		}
	}
	//document.getElementById("picture_keywords").style.display = "none";
	document.getElementById("edit_picture_keywords").style.display = "inline";
	document.getElementById("add_keywords").style.display = "none";
	document.getElementById("edit_keywords").style.display = "none";
}

function save_data(type)
{
	var currentIndex = document.getElementById("pictureIndex").innerHTML;
        var photoIds = document.getElementById("allPhotoIds").value;
        var currentPic_Type = document.getElementById("currentPic_Type").value;
        var photoIdsArray = photoIds.split(",");
	var id = parseInt(currentIndex)-1;
	var picId = photoIdsArray[id];
	if (type == "title")
	{
		var title = document.getElementById("edit_picture_title").getElementsByTagName("input")[0].value;
		var pic_keywords = document.getElementById("picture[0]").value;
		if (title == "-" || title.indexOf("**-**") >= 0)
		{
			alert("Such symbol is not allowed");
			document.getElementById("edit_picture_title").getElementsByTagName("input")[0].focus();
			return false;
		}
		document.getElementById("edit_picture_title").style.display = "none";
		document.getElementById("savingLoader1").style.display = "inline";
		var params = "picId="+picId+"&title="+escape(title)+"&type="+type+"&picType="+currentPic_Type+"&keywds="+pic_keywords;
	}
	else if (type == "keywords")
	{
		var title = document.getElementById("edit_picture_title").getElementsByTagName("input")[0].value;
		document.getElementById("edit_picture_keywords").style.display = "none";
		document.getElementById("picture_keywords").style.display = "none";
		document.getElementById("savingLoader2").style.display = "inline";
		var pic_keywords = document.getElementById("picture[0]").value;
		var params = "picId="+picId+"&title="+title+"&type="+type+"&picType="+currentPic_Type+"&keywds="+pic_keywords;
	}
	else
	{}
	var url = "/social/updatePictureDetails";
	sendRequest('POST',url,params);
}

$("#create_pp_button").colorbox({iframe:true, fastIframe:false, innerWidth:"700px", innerHeight:"400px", onComplete:function(){$.colorbox.resize({innerWidth:$(".cboxIframe").contents().find('.pink').width(),innerHeight:$(".cboxIframe").contents().find('.pink').height()})}, overlayClose:"reload", escKey:"reload"});
