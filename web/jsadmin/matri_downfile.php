<?php
/***************************************************************************************************************************
FILE NAME		: matri_downfile.php
DESCRIPTION		: This file is used to download a particular file. 
MODIFICATION DATE	: July 11th 2007.
MODIFIED BY		: Sriram Viswanathan.
***************************************************************************************************************************/
include("connect.inc");
include("matri_functions.inc");
if($temp==1)
{
	$cuts=1;
}
//path to the file which is to be downloaded.
$filepath = $FILE_PATH.$cuts.'_'.$username."_".$id.".doc";

//base name of file.
$filename = basename($filepath);

//finding the file size.
$filesize = filesize($filepath);

//setting the headers.
header('Content-type:application/msword');
header('Content-Disposition:attachment; filename="'. $filename . '"');
header('Content-Length:'.$filesize);
header('Content-Location:'.$target);
header('Content-Description : File Transfer');

//getting the file contents.
$contents = file_get_contents($filepath);

$file = $cuts.'_'.$username."_".$id.".doc";
readfile($FILE_PATH.$file);
?>

