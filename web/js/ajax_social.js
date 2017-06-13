function createRequestObject()
{
	var ajaxRequest;  // The variable that makes Ajax possible!
        try
        {
                // Opera 8.0+, Firefox, Safari
                ajaxRequest = new XMLHttpRequest();
        }
        catch (e)
        {
                // Internet Explorer Browsers
                try
                {
                        ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
                }
                catch (e)
                {
                        try
                        {
                                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        catch (e)
                        {
                                // Something went wrong
                                alert("Your browser broke!");
                                return false;
                        }
                }
        }
	return ajaxRequest;
}

//Make the XMLHttpRequest Object
var http = createRequestObject();

function sendRequest(method, url, params)
{
	var $result;
	if(method == 'get' || method == 'GET')
	{
		http.open(method,url,true);
		http.onreadystatechange = handleResponse;
		http.send(null);
	}
	else 
        {
                http.open(method,url,true);
                http.onreadystatechange = handleResponse;
		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		http.setRequestHeader("Content-length", params.length);
                http.send(params);
        }
}

function handleResponse()
{
        if(http.readyState == 4 && http.status == 200)
	{
		var response = http.responseText;
		if(response.substr(0,4)=="http")
		{
			var responseArray = response.split("**-**"); 
			//document.getElementById("display_main_pic").innerHTML = "<img src = \""+responseArray[0]+"\" />";
			document.getElementById('display_main_pic_div').src=responseArray[0];
			document.getElementById('display_main_pic_div').style.maxWidth='460px';
                        document.getElementById('display_main_pic_div').style.maxHeight='490px';
                        
			if(responseArray[8]<463)
				responseArray[8]=463;
			if(responseArray[9]<493)
				responseArray[9]=493;
			document.getElementById("transparent_image").width = "460";
			document.getElementById("transparent_image").height = "490";
                        document.getElementById("transparent_image").style.top = "0";
			document.getElementById("transparent_image").style.left = "0";

			document.getElementById("currentPicId").value = responseArray[3];
			document.getElementById("currentPic_Type").value = responseArray[7];
			document.getElementById("select_profile_link").getElementsByTagName("a")[0].href = "/social/profileLayer/view000"+responseArray[3]+"?rand="+Math.floor((Math.random()*1000000000)+1);
			document.getElementById("picture[0]").value = responseArray[4];
			document.getElementById("display_delete_link").innerHTML = "<a href=\"#\" class=\"no_b t12\" id = \"deleteThisPic\" onclick = \"checkProfPic('"+responseArray[3]+"','deleteThisPic"+responseArray[5]+"','profileBtn','"+responseArray[6]+"');\">Delete this photo&nbsp;</a>";

			if (responseArray[1])
			{
				document.getElementById("edit_title").style.display = "inline";
				document.getElementById("add_title").style.display = "none";
			}
			else
			{
				document.getElementById("edit_title").style.display = "none";
				document.getElementById("add_title").style.display = "inline";
			}
			document.getElementById("picture_title").innerHTML = responseArray[1];
			document.getElementById("edit_picture_title").getElementsByTagName("input")[0].value = responseArray[1];
			document.getElementById("picture_title").style.display = "block";
			document.getElementById("edit_picture_title").style.display = "none";
			if (responseArray[2])
			{
				document.getElementById("edit_keywords").style.display = "inline";
				document.getElementById("add_keywords").style.display = "none";
			}
			else
			{
				document.getElementById("edit_keywords").style.display = "none";
				document.getElementById("add_keywords").style.display = "inline";
			}
			document.getElementById("picture_keywords").innerHTML = responseArray[2];
			var output1 = responseArray[2].split(",");
        		var dropdown_result = "";
        		if (output1.length>2)
        		{
                		dropdown_result = output1[0]+", "+output1[1]+", ...";
        		}
			else if (output1.length==2)
			{
				dropdown_result = output1[0]+", "+output1[1];
			}
        		else
        		{
                		dropdown_result = responseArray[2];
        		}
			document.getElementById("dropdown0value").text = dropdown_result;
			document.getElementById("picture_keywords").style.display = "block";
			document.getElementById("edit_picture_keywords").style.display = "none";
			//image_timer(0);
                	document.getElementById("slider").style.display = "block";
                	document.getElementById("loader").style.display = "none";
		}
		else if (response.substr(0,9) == "updated_t")
		{
			var responseArray = response.split("**-**");
			if (responseArray[1])
                        {
                                document.getElementById("edit_title").style.display = "inline";
                                document.getElementById("add_title").style.display = "none";
                        }
                        else
                        {
                                document.getElementById("edit_title").style.display = "none";
                                document.getElementById("add_title").style.display = "inline";
                        }
                        document.getElementById("picture_title").innerHTML = responseArray[1];
                        document.getElementById("edit_picture_title").getElementsByTagName("input")[0].value = responseArray[1];
			document.getElementById("savingLoader1").style.display = "none";
                        document.getElementById("picture_title").style.display = "block";
                        //document.getElementById("edit_picture_title").style.display = "none";
		}	
		else if (response.substr(0,9) == "updated_k")
		{
			var responseArray = response.split("**-**");
			if (responseArray[2])
                        {
                                document.getElementById("edit_keywords").style.display = "inline";
                                document.getElementById("add_keywords").style.display = "none";
                        }
                        else
                        {
                                document.getElementById("edit_keywords").style.display = "none";
                                document.getElementById("add_keywords").style.display = "inline";
                        }
                        document.getElementById("picture_keywords").innerHTML = responseArray[1];
			var output1 = responseArray[1].split(",");
        		var dropdown_result = "";
        		if (output1.length>2)
        		{
                		dropdown_result = output1[0]+", "+output1[1]+", ...";
        		}
			else if (output1.length==2)
                        {
                                dropdown_result = output1[0]+", "+output1[1];
                        }

        		else
        		{
                		dropdown_result = responseArray[1];
        		}
			document.getElementById("dropdown0value").text = dropdown_result;
                        document.getElementById("picture[0]").value = responseArray[2];
			document.getElementById("savingLoader2").style.display = "none";
                        document.getElementById("picture_keywords").style.display = "block";
                        //document.getElementById("edit_picture_keywords").style.display = "none";
		}	
		else if (response == 'thumbnail_layer')
            	{
                    	document.getElementById("profile_layer_data").style.display = "none";
                      	document.getElementById("thumbnail_layer_data").style.display = "block";
                    	document.getElementById("loader_layer_data").style.display = "none";
                      	cropper1();
           	}
              	else if (response.substr(0,10) == 'layer_done')
               	{
			var responseArray = response.split("**-**");
			if (responseArray[1] == "view")
			{
                 		parent.location.href= "/social/viewAllPhotos/none";
			}
			else if (responseArray[1] == "save")
			{
                 		parent.location.href= "/social/addPhotos";
			}
			else
			{}
             	}
		else if (response == 'userTimedOut')
		{
                  	show_loggedIn_window();
		}
		else if (response == 'userTimedOutIframe')
		{
			parent.closeIframeAjaxError();
		}
		else if (response == "saved_album")
		{
			var total_count = document.getElementsByName("title_tag");
			for (i=0;i<total_count.length;i++)
        		{
				if ((document.getElementsByName("profPic"))[i].checked)
                		{
                     		   	var picId = (document.getElementsByName("picId_tag"))[i].value;
					break;
                		}
			}
			var profilePic_Id = document.getElementById("profilePic_Id").value;
        		var pic_id = "save000"+picId;
			if (picId == profilePic_Id)
                	{
                        	var url = "/social/loadingLayer/noLayer";
				$.colorbox({href:url, initialWidth:"100px", initialHeight:"100px", overlayClose:false, escKey:false});
                                $("#cboxLoadingOverlay").css({'background':'none'});
                                $("#cboxContent").css({'background':'none'});
                                $("#cboxMiddleLeft").css({'background':'none'});
                                $("#cboxMiddleRight").css({'background':'none'});
                                $("#cboxTopLeft").css({'background':'none'});
                                $("#cboxTopCenter").css({'background':'none'});
                                $("#cboxTopRight").css({'background':'none'});
                                $("#cboxBottomLeft").css({'background':'none'});
                                $("#cboxBottomCenter").css({'background':'none'});
                                $("#cboxBottomRight").css({'background':'none'});
                	}
                	else
                	{
                        	var url = "/social/profileLayer/"+pic_id+"?rand="+Math.floor((Math.random()*1000000000)+1);
				$.colorbox({href:url, iframe:true, fastIframe:false, innerWidth:"700px", innerHeight:"400px", onComplete:function(){$.colorbox.resize({innerWidth:$(".cboxIframe").contents().find('.pink').width(),innerHeight:$(".cboxIframe").contents().find('.pink').height()}); }});
                	}
                	document.getElementById("profilePic_Id").value = picId;
		}
		else if (response == "anand")
		{
			document.getElementById("output").innerHTML = response;
		}
		else
		{}
	}
        else if (http.readyState == 4 && http.status == 500) 
	{
		show_ajax_connectionErrorLayer();
	}
}

function image_timer(counter)
{
	counter = counter+50;
	if (counter>90)
	{
                	document.getElementById("slider").style.display = "block";
                	document.getElementById("loader").style.display = "none";
		return;
	}
	setTimeout("image_timer("+counter+");",1000);

}
