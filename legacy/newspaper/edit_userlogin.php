<?php

/*************************************************************************************************************************
*    FILENAME        : edit_userlogin.php 
*    DESCRIPTION     : Edits the user information 
**************************************************************************************************************************/  
include ("connect.inc");
$empty=1;
dbsql2_connect();
  
if (authenticated($cid))
{	
	$db1	= db_connect();
	$db2	= dbsql2_connect();
	$name	= getname($cid);

	if ($submit)
	{
		if (trim($MOD_EMAIL)=="" || checkemail($MOD_EMAIL))
		{
			$empty=0;
			$smarty->assign('check_email',1);
		}
		if (!$MOD_PRIV)
                {
                        $empty=0;
                        $smarty->assign('check_priv',1);
                }
		if (!$MOD_CENTER)
                {
                        $empty=0;
                        $smarty->assign('check_center',1);
                }                                                                                                                              		
		if ($empty==0)
		{
			$options=create_dd($MOD_PRIV,"privilege");
			$center=create_dd(strtoupper($MOD_CENTER),"branch");
				
			$smarty->assign('EMAIL',$MOD_EMAIL);
			$smarty->assign('ACTIVE',$MOD_ACTIVE);
                        $smarty->assign("options",$options);					
			$smarty->assign('CENTER',$MOD_CENTER);		
			$smarty->assign('RESID',$RESID);
			$smarty->assign('cid',$cid);
			$smarty->assign('name',$name);
			$smarty->display('edit_userlogin.htm');
		}
		else
		{
			if(is_array($MOD_PRIV))
			{
				$privstr=implode("+",$MOD_PRIV);
			}
			if(!$MOD_ACTIVE)
				$MOD_ACTIVE='N';

			$sql= "UPDATE jsadmin.PSWRDS SET ";
			if($MOD_PASSWD)
			{
				$sql.="PASSWORD ='$MOD_PASSWD', ";
			}
			$sql.="EMAIL ='$MOD_EMAIL',PRIVILAGE ='$privstr', CENTER ='$MOD_CENTER', ACTIVE='$MOD_ACTIVE', MOD_DT=NOW(), ENTRYBY='$name'  WHERE RESID='$RESID' " ;
			mysql_query($sql,$db2) or die("$sql".mysql_error());
										       
			$msg= " Record Updated<br>  ";
			$msg .="<a href=\"showuser.php?cid=$cid&$name\">";
			$msg .="Continue </a>";
			$smarty->assign("MSG",$msg);
			$smarty->display("jsadmin_msg.tpl");
		}
	}
	else
	{                
		if($act)
		{
			$sql="update jsadmin.PSWRDS set ACTIVE='$act' , MOD_DT=NOW() , ENTRYBY='$name' where RESID='$RESID'";
			mysql_query($sql,$db2) or die(mysql_error());

			$msg= " Record Updated<br>  ";
			$msg .="<a href=\"showuser.php?cid=$cid&name=$name\">";
			$msg .="Continue </a>";                                
			$smarty->assign("MSG",$msg);
			$smarty->display("jsadmin_msg.tpl");
		}
		else
		{
			$sql = "SELECT USERNAME,EMAIL,CENTER,PRIVILAGE,ACTIVE FROM jsadmin.PSWRDS WHERE RESID='$RESID'" ;
			$result = mysql_query($sql,$db2) or die(mysql_error());
			$row=mysql_fetch_array($result);
			$privilage=$row['PRIVILAGE'];
			$priv=explode("+",$privilage);		
			$options=create_dd($priv,"privilege");
			$branch=strtoupper($row["CENTER"]);
			$center=create_dd($branch,"branch");

			$smarty->assign('USERNAME',$row["USERNAME"]);
			$smarty->assign('EMAIL',$row["EMAIL"]);
			$smarty->assign('ACTIVE',$row["ACTIVE"]);
			$smarty->assign("options",$options); 
			$smarty->assign("center",$center);
			$smarty->assign("cid",$cid);	
			$smarty->assign("name",$name);
			$smarty->assign("RESID",$RESID);
			$smarty->display('edit_userlogin.htm');
		}   
	}
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
