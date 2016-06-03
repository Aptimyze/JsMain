<?php
//$filepath = $target;
include("../mis/connect.inc");
//$db=mysql_connect("localhost","root","Km7Iv801");
//mysql_select_db("newjs",$db);
//echo $username;
if($temp==1)
{
	$cuts=1;
}
$filepath = "/usr/local/matri_profiles/".$cuts.'_'.$username."_".$id.".doc";//echo $filepath;
$filename = basename($filepath);
$filesize = filesize($filepath);
header('Content-type:application/msword');
header('Content-Disposition:attachment; filename="'. $filename . '"');
header('Content-Length:'.$filesize);
header('Content-Location:'.$target);
header('Content-Description : File Transfer');
$contents = file_get_contents($filepath);
//echo $contents;
$file = $cuts.'_'.$username."_".$id.".doc";
readfile('/usr/local/matri_profiles/'.$file);
//readfile('/usr/local/matri_profiles/'.$cuts.'_'.$username.'_'.$id.'.doc');
//$openfile = fopen("$file","w+"); //fwrite("$openfile","$contents");
//fclose("$openfile");
?>

