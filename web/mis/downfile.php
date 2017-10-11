<?php
$filepath = $target;

//$filemime = mime_content_type('$filepath');

$filename = basename($filepath);

$filesize = filesize($filepath);

header('Content-type:application/msword');
header('Content-Disposition:attachment; filename="'. $filename . '"');
header('Content-Length:'.$filesize);
header('Content-Location:'.$target);
header('Content-Description : File Transfer');

$contents = file_get_contents($filepath);

echo $contents;
                                                                                                                             
$file = $id.".doc";
                                                                                                                             
$openfile = fopen("$file","w+");
                                                                                                                             
fwrite("$openfile","$contents");
                                                                                                                             
fclose("$openfile");

?>
