<?php

$flag_using_php5=1;
include_once("connect.inc");
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
        if(!$j)
                $j=1;
        $PAGELEN=12;
        $pagination=1;

	$profileid="";
	$callreq_pid="";
	$list="CALLERS";

        /* Section to get role of the logged in user
        *  Roles defines as -> SE,QA,DISPATCHER,TELECALLER      
        *  GET patrameters: profileid, list, cid, callreq_pid
        */
        $role = fetchRole($cid);
        $name = getname($cid);
	$smarty->assign("ROLE",$role);
        $smarty->assign("name",$name);
        $smarty->assign("cid",$cid);

        if($role=='TC'){
        	$profileid=$_GET['profileid'];
		$matchid=$profileid;
		$callreq_pid=$_GET['callreq_pid'];
        }

	// Get the profiles who requested the intro call for particular matchid
	$count = getCallerUsers_count($name,$matchid);
        if($count)
        {
	        $profile_start=($j)*$PAGELEN-$PAGELEN;
                if($profile_start+11<$count)
        	        $profile_end=$profile_start+11;
                else
                	$profile_end=$count;

		// Check for call requester profileid already exist if GET parameter,if not fetch from AP_CALL_HISTORY table 
		$callreqFlag='';
		if($callreq_pid){
			$profilesArray['0']['PROFILEID'] =$callreq_pid;
			$callreqFlag='1';
		}
		else{
			$profilesArray = getCallerUsers_list($name,$matchid,$profile_start,$PAGELEN,'1');
                	//$profilesArray=getList($profileid,$list,$contactArray,$leadArray,$username,$profile_start,$PAGELEN,1);
		}
                display_resultProfiles($profilesArray,'0',$matchid,$cid,$j,$count,$list,$callreqFlag);
                pagination($j,$count,$PAGELEN,"");
	}

	fetchLeftPanelLinks($role,$cid,$matchid,'',$list,'',$callreq_pid);
	getTitle($list,$count,'SET',$setDate);
        $curPage="ap_callUsers.php?cid=$cid&profileid=$matchid&list=$list";
        $smarty->assign("CUR_PAGE",$curPage);

        $smarty->assign("cid",$cid);
        $smarty->assign("profileid",$matchid);
        $smarty->assign("list",$list);
	$smarty->assign("role",$role);
	$smarty->assign("disable_chk",'1');
	
	$smarty->display("ap_list.htm");
?>
