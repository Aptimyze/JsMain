<?
/*********************************************************************************************
* FILE NAME     : profileofchatrequest.php
* DESCRIPTION   : Derives Data of Profile for chat window
* CREATION DATE : 1st December, 2005
* CREATED BY    : NIKHIL TANDON
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
$filename = "http://www.jeevansathi.com/P/profileofchatrequest1.php?sendersid=" . $_GET['sendersid'];
$handle = fopen($filename, "r");
if($handle)
{
        $contents = fgets($handle, 1024);
        fclose($handle);
}
else
        echo "cannot open file";
echo $contents;
?>
