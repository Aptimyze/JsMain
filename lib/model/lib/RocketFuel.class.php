<?php
class RocketFuel
{

	/** 
	 * This function is used to fecth pixelcode for conversion after registration. Currently it is called from fto/offer in case mobile and page 3 registration in case of desktop registration 
	 * */
	 public static function fetchRocketFuelCode($page="") {
		 $uid=uniqid();
		 if($page=="regPage1")
		 {
			 $pixelcode= "<script>
   $( document ).ready(function() {
   var img = $('<img />').attr({'src': 'http://20548335p.rfihub.com/ca.gif?rb=8177&ca=20548335&ra=".$uid."', 'alt':'Rocket Fuel', 'height' :'0', 'width' :'0' ,style:'display:none'}).appendTo(\"body\");
    });
</script>";
		}
		else if($page=="regPage2")
		{
			$pixelcode= "<script>
   $( document ).ready(function() {
   var img = $('<img />').attr({'src': 'http://20548337p.rfihub.com/ca.gif?rb=8177&ca=20548337&ra=".$uid."', 'alt':'Rocket Fuel', 'height' :'0', 'width' :'0',style:'display:none' }).appendTo(\"body\");
    });
</script>";
		}
		else if($page=="regPage3")
		{
			$pixelcode= "<script>
   $( document ).ready(function() {
   var img = $('<img />').attr({'src': 'https://20548343p.rfihub.com/ca.gif?rb=8177&ca=20548343&ra=".$uid."', 'alt':'Rocket Fuel', 'height' :'0', 'width' :'0',style:'display:none' }).appendTo(\"body\");
    });
</script>";
		}
		else if($page=="upload")
		{
			$pixelcode= "<script>
   $( document ).ready(function() {
   var img = $('<img />').attr({'src': 'http://20548339p.rfihub.com/ca.gif?rb=8177&ca=20548339&ra=".$uid."', 'alt':'Rocket Fuel', 'height' :'0', 'width' :'0',style:'display:none' }).appendTo(\"body\");
    });
</script>";
		}

		return $pixelcode;
	}
}
?>
