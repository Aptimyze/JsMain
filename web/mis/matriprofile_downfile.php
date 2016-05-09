<?php
//$filepath = $target;

$filepath = "/usr/local/matri_profiles/".$id.".doc";

$filename = basename($filepath);

$filesize = filesize($filepath);

header('Content-type:application/msword');
header('Content-Disposition:attachment; filename="'. $filename . '"');
header('Content-Length:'.$filesize);
header('Content-Location:'.$target);
header('Content-Description : File Transfer');

$contents = file_get_contents($filepath);

//echo $contents;
                                                                                                                             
$file = $id.".doc";
readfile('/usr/local/matri_profiles/'.$file);
//$openfile = fopen("$file","w+");                                                                                           //fwrite("$openfile","$contents");
//fclose("$openfile");

?>
