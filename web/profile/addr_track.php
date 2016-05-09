<?php

/***************************************************************************************************************
* FILE NAME     : addr_track.php
* DESCRIPTION   : Tracks the number of times email has been viewed and displays "adr_bg_img.png"
*****************************************************************************************************************/

include("connect.inc");
$db=connect_db();

$sql="UPDATE MIS.ADDR_EMAIL_TRACK SET COUNT=COUNT+1 WHERE ENTRY_DT=CURDATE()";
if(mysql_query($sql))
{
	if(mysql_affected_rows()==0)
        {
        	$sql="INSERT INTO MIS.ADDR_EMAIL_TRACK (COUNT,ENTRY_DT) VALUES ('1',CURDATE())";
        	mysql_query($sql);
        }
}

//$sql="INSERT INTO MIS.ADDR_EMAIL_TRACK (`PROFILEID`,`DATE`) VALUES('$profileid',CURDATE())";
//$res=mysql_query($sql) or logError("Error while inserting data into ADDR_EMAIL_TRACK",$sql);

header('Content-type: image/png');
//readfile("http://ser4.jeevansathi.com/profile/images/rd_strp_bg.gif");
readfile("$IMG_URL/profile/images/adr_bg_img.png");

?>
