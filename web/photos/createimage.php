<?php

$profileid = $_GET['profileid'];

if($profileid) {

   $link = @mysql_connect("localhost", "root", "Km7Iv80l") or die("Could not connect: " . mysql_error_js());
   @mysql_select_db("jsadmin", $link);

   $query = "select IMG_TYPE,DATA from PICTURE where PROFILE_ID=$profileid";
   //$query = "select filetype, image from pictures where id = $id";
   $result = @mysql_query_decide($query);

   $data = @mysql_result($result,0,"DATA");
   $type = @mysql_result($result,0,"IMG_TYPE");
   
   Header( "Content-type: $type");    
   
   $size = 150;  // new image width
   $src = imagecreatefromstring($data); 
   $width = imagesx($src);
   $height = imagesy($src);
   $aspect_ratio = $height/$width;

   if ($width <= $size) {
     $new_w = $width;
     $new_h = $height;
   } else {
     $new_w = $size;
     $new_h = abs($new_w * $aspect_ratio);
   }

   $img = imagecreatetruecolor($new_w,$new_h); 
   imagecopyresized($img,$src,0,0,0,0,$new_w,$new_h,$width,$height);
 
   // determine image type and send it to the client    
   if ($type == "image/pjpeg") {    
     imagejpeg($img); 
   } else if ($type == "image/x-png") {
     imagepng($img);
   } else if ($type == "image/gif") {
     imagegif($img);
   }
   imagedestroy($img); 
   //mysql_close($link);
};
?>
