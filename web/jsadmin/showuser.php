<?php

/************************************************************************************************************************
*    FILENAME           : showuser.php
*    INCLUDED           : connect.inc
*    DESCRIPTION        : displays the list of all the users
*    CREATED BY         : shobha
*    CHANGED ON		: June 24, 2006	
*    CHANGED BY         : amit
*    CHANGES		: search option is added	 
***********************************************************************************************************************/


include ("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

if (authenticated($cid))
{
	if($Go)
        {
		$i=0;
		$sql="SELECT * FROM jsadmin.PSWRDS";

                if($username!="")
                {
                        if($phrase=='U')
                        {
                                $sql .= " WHERE USERNAME LIKE '$username%'";
                        }
                        elseif($phrase=='E')
                        {
                                $sql .= " WHERE EMAIL LIKE '$username%'";
                        }
			elseif($phrase=='H')
			{
				$sql.=" WHERE HEAD_ID IN(SELECT EMP_ID FROM jsadmin.PSWRDS WHERE USERNAME='$username') AND ACTIVE='Y'";
			}
                }
		else
		{
			if($showall=='Y')
				$sql  = "select * from jsadmin.PSWRDS" ;
			else
				$sql="SELECT * FROM jsadmin.PSWRDS WHERE ACTIVE='Y'";
		}
		$result = mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($result))
		{
			$privilage=$row['PRIVILAGE'];
			$priv[$i]=explode("+",$privilage);
			for($j=0;$j<count($priv[$i]);$j++)
			{
				$label=label_select("PRIVILAGE",$priv[$i][$j],"jsadmin");
				$user[$i][$j]['PRIVILAGE']=$label[0];
			}
			$user[$i]["RESID"]=$row['RESID'];
			$user[$i]["USERNAME"]=$row['USERNAME'];
			$user[$i]["FIRSTNAME"]=$row['FIRST_NAME'];
			$user[$i]["LASTNAME"]=$row['LAST_NAME'];
			$user[$i]["CENTER"]=$row['CENTER'];
			$user[$i]["SIGN"]=$row['SIGNATURE'];
			$user[$i]["PHONE"]=$row['PHONE'];
			$user[$i]["EMAIL"]=$row['EMAIL'];  
			$user[$i]["MOD_DT"]=$row['MOD_DT'];
			$user[$i]["LAST_LOGIN_DT"]=$row['LAST_LOGIN_DT'];
			$user[$i]["ENTRYBY"]=$row['ENTRYBY'];                                                                     
			$user[$i]["ACTIVE"]=$row['ACTIVE'];
			$user[$i]["EMP_ID"]=$row['EMP_ID'];

			if($row['PHOTO_URL'])
				$user[$i]["PHOTO_URL"]=PictureFunctions::getCloudOrApplicationCompleteUrl($row['PHOTO_URL']);
        
	                $sql1 = "SELECT USERNAME FROM jsadmin.PSWRDS where EMP_ID = '$row[HEAD_ID]'";
                        $result1 = mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
                        $row1=mysql_fetch_array($result1);
                        $user[$i]["HEAD"]=$row1['USERNAME'];
			$i++;
		}
		
		$smarty->assign("SEARCH","YES");
		$smarty->assign("username",$username);
                $smarty->assign("phrase",$phrase);
		$smarty->assign("priv",$priv);
		$smarty->assign("cid",$cid);
		$smarty->assign("user",$user);
		$smarty->display("showuser.htm");
        }
        else
        {

                $smarty->assign("priv",$priv);
                $smarty->assign("cid",$cid);
//                $smarty->assign("user",$user);
                $smarty->display("showuser.htm");

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
