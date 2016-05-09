function saveTags()
{
	var i = 0,flag = 0;
	var total_count = document.getElementsByName("title_tag");
	var title_array = new Array();
	var picId_array = new Array();
	var picType_array = new Array();
	var keyword_array = new Array();
	var profilePic_id = "";
	
	for (i=0;i<total_count.length;i++)
	{
		title_array[i] = (document.getElementsByName("title_tag"))[i].value;
		if (title_array[i] == "-" || title_array[i].indexOf("**-**") >= 0)
                {
                        title_array[i] = "";
                }
		picId_array[i] = (document.getElementsByName("picId_tag"))[i].value;
		picType_array[i] = (document.getElementsByName("picType_tag"))[i].value;
		keyword_array[i] = (document.getElementsByName("picture_tag"))[i].value;
		if ((document.getElementsByName("profPic"))[i].checked)
		{
			flag = 1;
		}
	}
	if (flag == 0)
	{
		window.location.href= "/social/addPhotos";
	}
	
	var title_string = title_array.join("**-**");
	var picId_string = picId_array.join("**-**");
	var picType_string = picType_array.join("**-**");
	var keyword_string = keyword_array.join("**-**");

	var params = "title_array="+escape(title_string)+"&picId_array="+picId_string+"&picType_array="+picType_string+"&keyword_array="+keyword_string;
	var url = "/social/saveAlbumInfo?"+params;
	sendRequest('GET',url);	
}

function deletePic(picId,delId,profilePic,origProfPicId)
{
//pic to b deleted, div span id, this radio, profile pic id
	var inputs = document.getElementsByTagName('input');
	var pic_id = 0;
	
	if (delId.substr(0,6) == "reload")
	{
		var currentPicId = parseInt(document.getElementById("pictureIndex").innerHTML);
		var allPhotoIdsArray = (document.getElementById("allPhotoIds").value).split(",");
		if (currentPicId == 1 || currentPicId == allPhotoIdsArray.length)
		{
			var redirectParam = "none";
		}	
		else
		{
			var redirectParam = "id000"+allPhotoIdsArray[currentPicId];
		}
	
		document.getElementById("crossDelete").style.display = "none";
		document.getElementById("bottom_content").style.display = "none";
		document.getElementById("select_profile_link").style.display = "none";
		document.getElementById("display_delete_link").style.display = "none";
		document.getElementById("loaderSmallImage").style.display = "block";

		var noOfPics=parseInt(delId.substr(6));
		delId = delId.substr(0,6);
	
        	for (var i = 0; i < inputs.length; i ++)
        	{
                	if (inputs[i].type == 'radio')
			{
				if(inputs[i].checked) 
				{
					var ind = inputs[i].value;
					var photoIds = document.getElementById("allPhotoIds").value;
					var photoIdsArray = photoIds.split(",");
					pic_id = photoIdsArray[ind];//currently selected profile pic
				}
			} 
       		}
	}
	else
	{
			var loader_tag_element = "loaderImage"+delId.substr(3);
			document.getElementById(delId).style.display = "none";
			document.getElementById(loader_tag_element).style.display = "block";
		var noOfPics=0;

		if(document.list.elements["pictureId"])
	        {
	                var count = document.list.elements["pictureId"];

			var m;
			var flag=0;

	                for(m=0; m<count.length; m++)
	                {
	                        if(count[m].value == origProfPicId)
					flag=1;
	                }

        	}   
	
        	for (var i = 0; i < inputs.length; i ++)
        	{
                	if (inputs[i].type == 'radio')
			{
				noOfPics++;
				if(inputs[i].checked) 
				{
					var ind = inputs[i].value;
					var photoIds = document.getElementById("allPhotoIds").value;
					var photoIdsArray = photoIds.split(",");
					pic_id = photoIdsArray[ind];//currently selected profile pic
				}
			} 
       		}
	}
	if(flag == 1)
	{	
		if(noOfPics == 1 || origProfPicId != picId )
			pic_id = 0;
	}

	if (window.XMLHttpRequest)
		xmlhttp=new XMLHttpRequest();
	else
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			if(noOfPics == 1)
				callAdd();
			if (xmlhttp.responseText == "userTimedOutDelete")
			{
				show_loggedIn_window();
			}
			else
			{
				if (delId == "reload")
				{
					window.location.href= "/social/viewAllPhotos/"+redirectParam;
				}
				else
				{
					var loader_tag_element = "loaderImage"+delId.substr(3);
					document.getElementById(delId).innerHTML=xmlhttp.responseText;
					document.getElementById(loader_tag_element).style.display = "none";
					document.getElementById(delId).style.display = "block";
				}
			}
		}
	}
	xmlhttp.open("GET","/social/deletePic/"+picId+"/"+pic_id,true);
	xmlhttp.send();
}

function callAdd()
{
	window.location="/social/addPhotos";
}

function display_layer(name)
{
        document.getElementById(name).style.display = "block";
}

function hide_layer(name)
{
        document.getElementById(name).style.display = "none";
}

function hide_layer1(name)
{
        var index = name.substr(13);
        var i = 0;
	var j = 0;
        var output_tag = "picture["+index+"]";
	var dropdown_tag = "dropdown"+index+"value";
        var outputArr = new Array();
        var zzz = document.getElementById(name).getElementsByTagName("input");
        for (i=0;i<zzz.length;i++)
        {
                if(zzz[i].checked)
                {
                        outputArr[j] = zzz[i].value;
			j++; 
                }
        }
        output = outputArr.join(",");
        var dropdown_result = "";
        if (outputArr.length>2)
        {
                dropdown_result = zzz[outputArr[0]-1].nextSibling.nodeValue+", "+zzz[outputArr[1]-1].nextSibling.nodeValue+", ...";
        }
	else if (outputArr.length==2)
	{
		dropdown_result = zzz[outputArr[0]-1].nextSibling.nodeValue+", "+zzz[outputArr[1]-1].nextSibling.nodeValue;
	}
        else if (outputArr.length==1 && output!="")
	{
		dropdown_result = zzz[outputArr[0]-1].nextSibling.nodeValue;
	}
	else
        {
                dropdown_result = output;
        }
        document.getElementById(output_tag).value = output;
	document.getElementById(dropdown_tag).text = dropdown_result;
        document.getElementById(name).style.display = "none";
}

function checkProfPic(picId,delId,profilePic,origProfPicId)
{
//alert(document.getElementById(profilePic).checked);
//pic to b deleted, href id, this radio, profile pic id
	var inputs = document.getElementsByTagName('input');
	var pic_id = 0;
	
	if (delId.substr(0,13) == "deleteThisPic")
	{
		var noOfPics=parseInt(delId.substr(13));
		delId = delId.substr(0,13);
	
        	for (var i = 0; i < inputs.length; i ++)
        	{
                	if (inputs[i].type == 'radio')
			{
				if(inputs[i].checked) 
				{
					var ind = inputs[i].value;
					var photoIds = document.getElementById("allPhotoIds").value;
					var photoIdsArray = photoIds.split(",");
					pic_id = photoIdsArray[ind];//currently selected profile pic
				}
			} 
       		}
	}
	else
	{
		var noOfPics = 0;
        	for (var i = 0; i < inputs.length; i ++)
        	{
                	if (inputs[i].type == 'radio')
			{
				noOfPics++;
				if(inputs[i].checked) 
				{
					var ind = inputs[i].value;
					var photoIds = document.getElementById("allPhotoIds").value;
					var photoIdsArray = photoIds.split(",");
					pic_id = photoIdsArray[ind];//currently selected profile pic
				}
			}	 
       		}
	}

	if(noOfPics == 1 || origProfPicId != picId)
		pic_id = 0;
	if(document.getElementById(profilePic).checked && noOfPics > 1)
	{
		var url = "/social/deleteProfilePicLayer";
		document.getElementById(delId).href=url;
	}
	else
	{
		if (delId == "deleteThisPic")
		{
			var x = "reload"+noOfPics;
		}
		else
		{
			var value = delId.substr(6);
			var x = "val" + value;
		}
		var url = "/social/deleteLayer/"+picId+"/"+x+"/"+profilePic+"/"+origProfPicId;
		document.getElementById(delId).href=url;
	}
	
	if (delId == "deleteThisPic")
	{
		$.colorbox({href:url});
	}
}

function closeIframeAjaxError()
{
	show_loggedIn_window();
}
