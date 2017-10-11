<?php

$flag_using_php5=1;
include("connect.inc");
include_once("ap_common.php");
include_once("ap_functions.php");
include_once("display_common.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/contact.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/cmr.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/thumb_identification_array.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/contacts_functions.php");

if(!authenticated($cid))
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
	die;
}

	global $listMainArray;
	$name = getname($cid);
	$smarty->assign("name",$name);

        /* Section to get role of the logged in user
        *  Roles defines as -> SE,QA,DISPATCHER,TELECALLER      
        */
        $role = fetchRole($cid);
        $smarty->assign("ROLE",$role);
        /* Ends */

	if($address=='1'){
		showContactDetails($matchid,$matchid);
		$smarty->display("ap_shippingAddress.htm");	
		die;
	}

	// Get the list of array of profiles whose print out has to be taken
	if($profileid && $list){
		$profilesArray=getList($profileid,$list,'','',$username,'','','');		
		$totCount =count($profilesArray);
	}

	$PRINT_LIST	='1';
	$list1		=$list;		
	$matchid1	=$matchid;

	$head =$smarty->fetch("ap_viewprofile_print_list_head.htm");
	echo $head;
	for($z=0;$z<$totCount;$z++)
	{
		$lead1='';
		if($profilesArray[$z]['LEAD_ID']!=''){
			$profileid1 =$profilesArray[$z]['LEAD_ID'];
			$lead1='1';
		}
		else{	
			$profileid1 =$profilesArray[$z]['PROFILEID'];
		}
		include("ap_viewprofile.php");
	}
	echo "</body></html>";


?>
