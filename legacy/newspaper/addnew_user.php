<?php

include ("connect.inc");
$empty=1;
dbsql2_connect();

if(authenticated($cid))
{
	$db1	= db_connect();
	$db2	= dbsql2_connect();

	$name=getname($cid);
        if($submit)
	{
		if (trim($USERNAME)=="")
		{	
			$empty=0;
                        $smarty->assign('check_name',1);
		}
		if (trim($PASSWORD)=='')
                {
                        $empty=0;
                        $smarty->assign('check_passwd','1');
                }

		if (trim($EMAIL)=="" || checkemail($EMAIL))
                {
                        $empty=0;
                        $smarty->assign('check_email',1);
                }
		if (!$CENTER)
                {
			$empty=0;
                        $smarty->assign('check_center','1');
                }
                if(!$PRIVILAGE)
                {
                        $empty=0;
                        $smarty->assign('check_priv','1');
                }                  

		$sql="SELECT COUNT(*) as cnt FROM jsadmin.PSWRDS WHERE USERNAME='$USERNAME'";
		//$res=mysql_query($sql) or die("$sql".mysql_error());
		$res=mysql_query($sql,$db2) or die("$sql".mysql_error());
		$row=mysql_fetch_array($res);
		if($row['cnt']>0)
		{
			$empty=0;
			$smarty->assign("user_exists","1");
		}

		if ($empty==0)
		{
			$smarty->assign('USERNAME',$USERNAME);
        		$smarty->assign('PASSWORD',$PASSWORD);
                        $smarty->assign('EMAIL',$EMAIL);

                        $options=create_dd($PRIVILAGE,"privilege");
			$center=create_dd($CENTER,"branch");

                        $smarty->assign('options',$options);
			$smarty->assign('center',$center);
         	  	$smarty->assign('ACTIVE',$ACTIVE);
	                $smarty->assign('cid',$cid);
                        $smarty->display('addnew_user.htm');
		}
                else
		{     
			if (!$ACTIVE)
                	{
                        	$ACTIVE='N';
                	}

			if(is_array($PRIVILAGE))
			{
				$PRIVSTR = implode("+",$PRIVILAGE);
			}
			
			$sql = "INSERT INTO jsadmin.PSWRDS (USERNAME,PASSWORD,EMAIL,PRIVILAGE,CENTER,ACTIVE,MOD_DT,ENTRYBY) VALUES ('$USERNAME','$PASSWORD','$EMAIL','$PRIVSTR','$CENTER','$ACTIVE',NOW(),'$name') ";
		
			mysql_query($sql,$db2) or die(mysql_error());
			$msg= " Record Inserted<br>  ";
                        $msg .="<a href=\"showuser.php?cid=$cid\">";
                        $msg .="Continue </a>";
                        $smarty->assign("MSG",$msg);
                        $smarty->display("jsadmin_msg.tpl");
		}
	}
	else
        {
		$priv_val=create_dd("","privilege");
		$center=create_dd("","branch");
		$smarty->assign('options',$priv_val);
		$smarty->assign('center',$center);
		$smarty->assign('cid',$cid);
		$smarty->display("addnew_user.htm");				  
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
