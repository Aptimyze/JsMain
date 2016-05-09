<?php

/************************************************************************************************************************
*    FILENAME           : showuser.php
*    INCLUDED           : connect.inc
*    DESCRIPTION        : displays the list of all the users
*    CREATED BY         : shobha 
***********************************************************************************************************************/

include ("includes/bms_connect.php");
$ip=FetchClientIP();
//$data=authenticatedBms($id,$ip,"banadmin");
if ($site == 'JS')
        $data=authenticatedBms($id,$ip,"banadmin");
else
        $data=authenticatedBms($id,$ip,"99acresadmin");
$smarty->assign("site",$site);
                                                                                                                            
if ($data)
{     
	$bmsheader=fetchHeaderBms($data);
        $bmsfooter=fetchFooterBms();
        $smarty->assign("bmsheader",$bmsheader);
        $smarty->assign("bmsfooter",$bmsfooter);
	
	$i=0;
	if ($site == '99acres')
		$sql  = "select * from bms2.USERS WHERE SITE ='99acres'" ;
	else
		$sql  = "select * from bms2.USERS" ;
	$result = mysql_query($sql) or die("$sql".mysql_error());
	while($row=mysql_fetch_array($result))
	{
		
		$privilege = $row["USER_PRIVILEGE"];
		$sql1 = "select LABEL from bms2.PRIVILEGES where VALUE = '$privilege'";
		$res = mysql_query($sql1) or logErrorBms("continue",$sql,"yes");
		$row1 = mysql_fetch_array($res);
		$priv = $row1['LABEL'];
		$user[$i]["PRIVILAGE"]=$priv;//$row['USER_PRIVILEGE'];
		$user[$i]["USERID"]=$row['USERID'];

		if($privilege == 'client')
		{
			$user[$i]["USERNAME"]=$row['USERNAME']."&nbsp;*";
		}
		else
		{
			$user[$i]["USERNAME"]=$row['USERNAME'];
		}

		$user[$i]["PASSWORD"]=$row['PASSWORD'];
		$user[$i]["EMAIL"]=$row['EMAIL'];  
		$user[$i]["MOD_DT"]=$row['MOD_DT'];
		//$user[$i]["PASSWORD"]=$row['PASSWORD'];
		$user[$i]["ACTIVE"]=$row['ACTIVE'];
		$i++;
	}
	$smarty->assign("priv",$priv);
	$smarty->assign("id",$id);
	$smarty->assign("user",$user);
	$smarty->display("./$_TPLPATH/bms_showuser.htm");
}
else
{
	TimedOutBms();
}
?>
