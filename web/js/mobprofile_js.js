function hidePhotoOption()
		{
			document.getElementById('photo_privacy_options').style.display="none";
			document.getElementById('photo_privacy_option1_img').style.display="none";
			document.getElementById('photo_privacy_option2_img').style.display="none";
		}
		function showPhotoOption()
		{
			document.getElementById('photo_privacy_options').style.display="block";;
		}
		function setPhotoOption()
		{	
		//Select Photo option////////////////////////
		if( option == 'F' || option ==  'A' || option=="")
		{
		document.getElementById('photo_privacy_options1').className="s-info-bar";
		document.getElementById('photo_privacy_options1').className=" s-info-bar privacy_option_act";
		document.getElementById('photo_privacy_options2').className="s-info-bar";
		document.getElementById('photo_privacy_options2').className=" s-info-bar privacy_option_noact";
		document.getElementById('photo_privacy_option1_img').style.display="block";
		}	
		if( option == 'C' || option ==  'H')
		{
		document.getElementById('photo_privacy_options2').className="s-info-bar";
		document.getElementById('photo_privacy_options2').className=" s-info-bar privacy_option_act";
		document.getElementById('photo_privacy_options1').className="s-info-bar";;
		document.getElementById('photo_privacy_options1').className=" s-info-bar privacy_option_noact";
		document.getElementById('photo_privacy_option2_img').style.display="block";
		}
		//////////////////////////////////////////////
		}	

if(person_self)
{
		hidePhotoOption();
		
		setPhotoOption();
		
		document.getElementById('photo_privacy_options1').onclick=function(){
			option="A";
			updatePrivacy();
			
		}
		
		document.getElementById('photo_privacy_options2').onclick=function(){
			option="C";
			updatePrivacy();
		}
	
		function updatePrivacy()
		{
			var randomnumber=Math.floor(Math.random()*11111);
			var url="/profile/change_photo_privacy.php?photo_display="+option+"&rnumber="+randomnumber;
			sendRequest("GET",url,onSuccessPrivacy);

		}
		function onSuccessPrivacy()
		{
				var response=http.responseText;
					if(response == 'A')
					{
						option=response;
						document.getElementById('photo_privacy_option1_img').style.display="block";
					}
					else if(response == 'C')
					{
						option=response;
						document.getElementById('photo_privacy_option2_img').style.display="block";
					}
	
			setPhotoOption();
		}

		
		var toggle=0;
		
		document.getElementById('photo_privacy').onclick=function(){
			toggle =!toggle;
			if(toggle)
				showPhotoOption();
			else
				hidePhotoOption();
					
		};
}
if(photoUploadSupport)
{
       document.getElementById("photoInput").addEventListener('change', handlePhotoSelect, false);
} 
