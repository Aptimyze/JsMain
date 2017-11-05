<?php
include("connect.inc");
//include(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

if(authenticated($cid))
{
	$name = getname($cid);
        $privilage = explode("+",getprivilage($cid));
        if(in_array("AdSlEx",$privilage) || in_array("P",$privilage) || in_array("MG",$privilage))
                $hyperlink =true;
        else
		$hyperlink =false;

	if($submit && trim($mobno)!='')
	{
		$sql = "select PROFILEID, USERNAME, ACTIVATED, HAVEPHOTO, MOB_STATUS, LANDL_STATUS from newjs.JPROFILE where PHONE_MOB='$mobno' or PHONE_WITH_STD='$mobno'";
		$result = mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if(mysql_num_rows($result))
		{
			$i=0;
			while($row=mysql_fetch_array($result))
			{
				$users[$i]["USERNAME"] = $row['USERNAME'];
                                
                                if($row['ACTIVATED'] == 'N' && $row['HAVEPHOTO'] == 'Y'){
                                    if($row['MOB_STATUS'] == 'Y' || $row['LANDL_STATUS'] == 'Y')
                                        $users[$i]["ACTIVATED"] = 'Y';
                                    else{
                                        $phoneVerified = JsMemcache::getInstance()->get($row['PROFILEID']."_PHONE_VERIFIED");
                                        if(!$phoneVerified){
                                            $contactNumOb= new ProfileContact();
                                            $numArray=$contactNumOb->getArray(array('PROFILEID'=>$row['PROFILEID']),'','',"ALT_MOB_STATUS");
                                            if($numArray['0']['ALT_MOB_STATUS']=='Y')
                                                $users[$i]["ACTIVATED"] = 'Y';
                                        }
                                        
                                    }
                                }
                                if(!$users[$i]["ACTIVATED"])
                                    $users[$i]["ACTIVATED"] = $row['ACTIVATED'];
				$i++;
			}
		}
		else
			$smarty->assign('NO_RECORD','Y');
	
		$smarty->assign('lusers',$users);
		$smarty->assign('mobno',$mobno);
		$smarty->assign("flag","1");
		$smarty->assign("username",$name);
		$smarty->assign("cid",$cid);
		$smarty->assign("hyperlink",$hyperlink);
		$smarty->display('mobcheckuser.htm');
	}
	else
	{
		$smarty->assign("username",$name);
		$smarty->assign("cid",$cid);
		$smarty->display('mobcheckuser.htm');
	}
}
else
{
	$msg="Your session has been timed out  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";	
	$smarty->assign("MSG",$msg);	
	$smarty->display("jsadmin_msg.tpl");

}
?>
