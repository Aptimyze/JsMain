<?php
ini_set("max_execution_time","0");

/************************************************************************************************************************
*    FILENAME           : temp_search_male_onetime.php
*    DESCRIPTION        : WE HAVE CREATED A TEMPORARY TABLE TEMP_SEARCH_MALE ,SO THAT WE CAN REMOVE TOP 5 % OF CONTACTED                               RECEIVERS
*    CREATED BY         : lavesh
***********************************************************************************************************************/

//for 244 from where data is calculated.
include_once("connect_db.php");
                                                                                                                             
$db=connect_db();
                                                                                                                             
$sql = "SELECT PROFILEID FROM newjs.JPROFILE WHERE GENDER='M'";
$res = mysql_query_decide($sql) or die("Error while retrieving data from newjs.SEARCH_MALE".mysql_error_js());
                                                                                                                             
while($row=mysql_fetch_array($res))
{
        $pid=$row["PROFILEID"];

        $sql1="SELECT COUNT(*) AS CNT FROM newjs.CONTACTS WHERE RECEIVER='$pid'";
        $res1=mysql_query_decide($sql1) or die("Error while retrieving data from newjs.CONTACTS".mysql_error_js());
        $row1=mysql_fetch_array($res1);
        $cnt=$row1["CNT"];
                                                                                                                             
        if($cnt>0)
        {
                $sql2="INSERT INTO newjs.TEMP_SEARCH_MALE VALUES('','$pid','$cnt')";
                mysql_query_decide($sql2) or die("error insertion".mysql_error_js());
        }
}

?>

