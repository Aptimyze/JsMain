<?php
$Imagefile = $_GET["Imagefile"];
/*Not required for new photo screening interface
$username = $_GET["username"];
$order = $_GET["order"];
$profileid = $_GET["profileid"];
$photo = $_GET["photo"];

$fileName=$username."-".$order.".jpg";
*/
$split= explode("uploads",$Imagefile);
header('Content-type:image/jpg');
//header('Content-Disposition: attachment; filename="' . $fileName .'"');
if(strstr($Imagefile,"NonScreenedImages"))
{
        $path=$split[1];//"172.16.3.156".$split[1];
        if(@file_exists($path))
        {
                readfile($path);
                die;
        }
}
$path=$Imagefile;
readfile($path);
die;
?>
