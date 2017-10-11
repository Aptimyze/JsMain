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

function sendRequest(method, url)
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
                http.send($result);
        }
}


function handleResponse()
{
        if(http.readyState == 4 && http.status == 200)
	{
		var response = http.responseText;
		if(response)
		{
			if(response == 'A')
			{
				document.getElementById("im1_1").style.display = "none";
				document.getElementById("im1_2").style.display = "block";
				document.getElementById("im2_2").style.display = "none";
			}
			else if(response == 'CASTE_DONE')
			{
				$.colorbox.close();
			}
			else if(response == 'C')
			{
				document.getElementById("im2_1").style.display = "none";
				document.getElementById("im1_2").style.display = "none";
				document.getElementById("im2_2").style.display = "block";
			}
			else if(response == 'Y')
                        {
                                document.getElementById("im3_1").style.display = "none";
                                document.getElementById("im3_2").style.display = "block";
                                document.getElementById("im4_2").style.display = "none";
				document.getElementById("im5_2").style.display = "none";
                        }
                        else if(response == 'N')
                        {
                                document.getElementById("im4_1").style.display = "none";
                                document.getElementById("im3_2").style.display = "none";
                                document.getElementById("im4_2").style.display = "block";
				document.getElementById("im5_2").style.display = "none";
                        }
			else if(response == 'D')
                        {
                                document.getElementById("im5_1").style.display = "none";
				document.getElementById("im3_2").style.display = "none";
                                document.getElementById("im4_2").style.display = "none";
                                document.getElementById("im5_2").style.display = "block";
                        }
                        else if(response == 'JM' || response == 'JL' || response == 'NJ')
			{
				document.getElementById('junk').value='';
				document.getElementById('mobile_span').style.display="none";
				document.getElementById('phone_span').style.display="none";

				if(response=='JM')
					document.getElementById('mobile_span').style.display="block";
				else if(response=='JL')	
					document.getElementById('phone_span').style.display="block";
				if(response !='NJ')
					document.getElementById('junk').value=response;
				
				document.getElementById('img_test1').style.display="none";
				document.getElementById('img_sav').style.display="block";
				return false;
                        }
			else if(response == 'exist')
			{
				document.getElementById('img_avail').style.display="none";
        			document.getElementById('img_sav').style.display="block";        
        			document.getElementById('img_test1').style.display="none";
				document.getElementById('my_exemail').style.display="block";
				return false;
			}
			else if(response == 'not')
			{
				if(document.getElementById('inv').value==1)
					document.getElementById('img_avail').style.display="none";
				else
					document.getElementById('img_avail').style.display="block";
                                document.getElementById('img_sav').style.display="block";
                                document.getElementById('img_test1').style.display="none";
                                document.getElementById('my_exemail').style.display="none";
			}
			else if(response == 'empty')
                        {
                                document.getElementById('img_avail').style.display="none";
                                document.getElementById('img_sav').style.display="block";
                                document.getElementById('img_test1').style.display="none";
                                document.getElementById('my_exemail').style.display="none";
                        }
			else if(response.length > 100)
			{
				var city_codes = response.split("isd");
				if(city_codes[0] != "fname")
				{
					document.getElementById("city_arr").innerHTML= city_codes[0];
					document.getElementById("ISD1").value = city_codes[1];
					document.getElementById("ISD2").value = city_codes[1];				
				}
			}
			else if(response.substr(0,3) == "std")
                        {
				var state_code = response.substr(3,response.length);
                                document.getElementById("State_Code").value= response.substr(3,response.length);
                        }
			else if(response == "saved_main")
			{
				if(document.getElementById("cup").value==1)
				{
                                        cancel_up();return;
				}
                                document.getElementById("saved").style.display="block";
				document.getElementById("loading").style.display="none";
                                document.getElementById("cancel_upload").style.display="none";
                                document.getElementById("addtitle").style.display="block";
                                document.getElementById("savephoto").style.display="block";
                                document.getElementById("loaded").style.display="block";
				document.getElementById("testImage").src=document.getElementById("photo_path").value;
				cropper();
			}
			else if(response == "saved_a1")
                        {
				if(document.getElementById("cup").value==1)
				{
                                        cancel_up(2);return;
				}
                                document.getElementById("saved").style.display="block";
                                document.getElementById("loading").style.display="none";
                                document.getElementById("cancel_upload").style.display="none";
                                document.getElementById("addtitle").style.display="block";
                                document.getElementById("savephoto").style.display="block";
                                document.getElementById("loaded").style.display="block";
                                document.getElementById("a1").src=document.getElementById("photo_path").value;
                        }
			else if(response == "saved_a2")
                        {
				if(document.getElementById("cup").value==1)
				{
                                        cancel_up(1);return;
				}
                                document.getElementById("saved").style.display="block";
                                document.getElementById("loading").style.display="none";
                                document.getElementById("cancel_upload").style.display="none";
                                document.getElementById("addtitle").style.display="block";
                                document.getElementById("savephoto").style.display="block";
                                document.getElementById("loaded").style.display="block";
                                document.getElementById("a2").src=document.getElementById("photo_path").value;
                        }
			else if(response == "saved_pro")
			{
				document.getElementById("final_loading_save").style.display="none";
				document.getElementById("thumb_layer").style.display="block";
				document.getElementById("testImage1").src=document.getElementById("photo_path").value;
				cropper1();
			}
			else if(response == "skip_pro")
			{
				document.getElementById("final_loading_skip").style.display = "none";
		                document.getElementById("thumb_layer").style.display = "block";
                		document.getElementById("replace").style.display = "none";
		                document.getElementById("no_replace").style.display = "block";
                		document.getElementById("testImage1").src=document.getElementById("photo_path").value;
		                cropper1();
			}
			else if(response == "special_case")
                        {
				skipLayer();
			}
			else if(response == "error1")
			{
				document.getElementById("error1").style.display = "block";
				document.getElementById("error_img").style.display = "block";
				document.getElementById("cancel_upload").style.display="none";
				document.getElementById("loading").style.display="none";
				document.getElementById("loaded").style.display="block";
			}
			else if(response == "error2")
			{
				document.getElementById("error2").style.display = "block";
				document.getElementById("error_img").style.display = "block";
				document.getElementById("cancel_upload").style.display="none";
                                document.getElementById("loading").style.display="none";
				document.getElementById("loaded").style.display="block";
			}
		}	
	}
}
                   
