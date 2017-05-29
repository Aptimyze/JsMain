<?php
include("connect.inc");
//print_r($_POST);
if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	if($submit=="populate")
	{
		$sql="select PIXELCODE from MIS.PIXELCODE where GROUPNAME='$source'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$RES=mysql_fetch_array($res);
		if(mysql_num_rows($res)>0)
			die($RES['PIXELCODE']);
		else
			die("no entry");
	}
	else if($submit=="Submit")
	{
		if($val==1)
		{
			$sql="insert into MIS.PIXELCODE (GROUPNAME,PIXELCODE) VALUES ('$srcgp','$pixelcode')";
		}
		else
		{
			$sql="update MIS.PIXELCODE set PIXELCODE='$pixelcode' where GROUPNAME='$srcgp'";
		}
		mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$msg="Pixelcode saved for $srcgp";
		$smarty->assign("msg",$msg);

	}
	else if($submit=="Delete")
	{
		$sql="delete from MIS.PIXELCODE where GROUPNAME='$srcgp'";
                mysql_query_decide($sql) or die("$sql".mysql_error_js());
                $msg="Pixelcode deleted for $srcgp";
                $smarty->assign("msg",$msg);
		
	}
   //     else
   //     {
                $sql="SELECT DISTINCT GROUPNAME FROM MIS.SOURCE order by GROUPNAME";
                $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                while($row=mysql_fetch_array($res))
                {
                        $srcarr[]=$row['GROUPNAME'];
                }

                $smarty->assign("srcarr",$srcarr);
                $smarty->display("pixel_code_insertion.htm");
     //   }




}
else
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>

