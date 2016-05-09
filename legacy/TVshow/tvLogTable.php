<?php
include_once("../P/connect.inc");
$db=connect_db();
/*
the input url for this module will be 

/TVshow/tvLogTable.php?profileId=profileId&showcase_profileId=showcase_profileId

$profileId------>   the PROFILEID of the person caontacting 
$showcase_profileId---->  the profileId of the profile to be contacted i.e. the profile diplayed on the TV SHOW

on success it will print done
*/
/*
$profileId=12345;		for testing
$showcase_profileId=67890;	for testing
*/

function mysql_error1($msg)
{
        echo 'E';
        //TEMP
        //echo $msg;
        //TEMP
        global $phone;
        $msg.=" phone:$phone";
        mail("sandeep.samudrala@jeevansathi.com,lavesh.rawat@gmail.com","Error in PhoneCheck module",$msg);
        die;

}
if($profileId&&$showcase_profileId)
{
$sql_entry="INSERT INTO MIS.TVSHOWCASELOG (PROFILEID,SHOWCASE_PROFILEID,ENTRY_DT) VALUES('$profileId','$showcase_profileId',now())";
$res=mysql_query($sql_entry,$db) or mysql_error1("--E1--".mysql_error($db));
echo "done";
}

?>
