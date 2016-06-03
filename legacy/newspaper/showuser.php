<?php

/************************************************************************************************************************
*    FILENAME           : showuser.php
*    INCLUDED           : connect.inc
*    DESCRIPTION        : displays the list of all the users
*    CREATED BY         : shobha 
***********************************************************************************************************************/


include ("connect.inc");
dbsql2_connect();

if (authenticated($cid))
{
	$db1	= db_connect();
	$db2	= dbsql2_connect();
	$i	= 0;

	$sql  = "select * from jsadmin.PSWRDS" ;
	$result = mysql_query($sql,$db2) or die("$sql".mysql_error());
	while($row=mysql_fetch_array($result))
	{
		$privilage = $row['PRIVILAGE'];
		$priv[$i] = explode("+",$privilage);

		for($j=0;$j<count($priv[$i]);$j++)
		{
			$label=label_select("PRIVILAGE",$priv[$i][$j],"jsadmin");
			$user[$i][$j]['PRIVILAGE'] = $label[0];
		}
		
		$name = branch_name($row['CENTER']);

                $user[$i]['CENTER'] 	= $name[0];                
		$user[$i]["RESID"]	= $row['RESID'];
		$user[$i]["USERNAME"]	= $row['USERNAME'];
		$user[$i]["EMAIL"]	= $row['EMAIL'];  
		$user[$i]["MOD_DT"]	= $row['MOD_DT'];
		$user[$i]["ENTRYBY"]	= $row['ENTRYBY'];                                                                    		     $user[$i]["ACTIVE"]     = $row['ACTIVE'];
		$i++;
	}
	
	$smarty->assign("priv",$priv);
	$smarty->assign("cid",$cid);
	$smarty->assign("user",$user);
	$smarty->assign("name",$name);
	$smarty->display("showuser.htm");
}
else
{
	$msg="Your session has been timed out<br>  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}

function branch_name($value)
{	
	$db  = db_connect();

	$sql = " select NAME from newjs.BRANCHES where VALUE = '$value'";
        $res = mysql_query($sql,$db) or die(mysql_error());
        $row = mysql_fetch_array($res);

	return $row;

}
?>
