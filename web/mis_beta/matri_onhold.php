<?php
include("../mis/connect.inc");
$db=mysql_connect("localhost","root","Km7Iv80l");
mysql_select_db("billing",$db);
if(authenticated($checksum))
{
        $user=getname($checksum);
        $privilage=getprivilage($checksum);
        $priv=explode("+",$privilage);
        if(in_array('MPU',$priv))
        {

if($submit)
{
	
	$ts=time();
	$today=date('Y-m-d G:i:s',$ts);
	$sql="UPDATE MATRI_PROFILE SET STATUS='H', COMPLETION_TIME='$today' WHERE PROFILEID='$profileid'";
	mysql_query_decide($sql);
	$sql="INSERT INTO MATRI_ONHOLD(PROFILEID,USERNAME,ONHOLD_TIME,REASON) VALUES('$profileid','$username','$today','".addslashes($reason)."')";
	mysql_query_decide($sql);
	$smarty->assign("flag",1);
	$smarty->display("matri_onhold.htm");
}
else
{
	$smarty->assign("profileid",$profileid);
	$smarty->assign("username",$username);
	$smarty->assign("checksum",$checksum);
	$smarty->display("matri_onhold.htm");
}
        }
        else
        {
                echo "You don't have permission to view this mis";
                die();
        }
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
