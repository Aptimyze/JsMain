<?php
include("connect.inc");
$db2=connect_master();
if(authenticated($cid))
{
	

	$filename="deferral.xls";
        $fd = fopen($filename, "r");
                                                                                                 
        $filesize_var=filesize($filename);
	$contents = fread($fd, $filesize_var);


        fclose($fd) or die("Cannot close the file .");

        header("Content-Type: application/vnd.ms-excel");
	header('Content-Length: '.$filesize_var); 
	header('Content-Size: '.$filesize_var); 
        header("Content-Disposition:attachment; filename=deferral.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $output = $header."\n".$contents;
}
else
{
        $smarty->display("jsconnectError.tpl");
}
                                                                                                 

?>

