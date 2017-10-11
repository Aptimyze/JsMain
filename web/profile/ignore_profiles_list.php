<?php
/**
*       Filename        :       ignore_profiles_list.php
*       Description     :	To stop ignoring a profile
*       Modified by     :	Sadaf Alam
**/


	//to zip the file before sending it
	$zipIt = 0;

	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it
		
	include("connect.inc");
	include("search.inc");
	//include("display_result.inc");
	
	$db=connect_db();
	$data = authenticated($checksum);

	//Added for contact details on left panel
        if($data)
                login_relogin_auth($data);
        //Ends here

        /**************************for link tracking**********************/
        //link_track("ignore_profiles_list.php");
        /*********************************************************************************/

	if(isset($data))
	{
		$profileid=$data["PROFILEID"];
		if($multiple)
		{
			$rec_check=explode(",",$profilechecksum);
			$total_contact=count($rec_check);
			for($start=0;$start<count($rec_check);$start++)
			{
				$receiver_id=$rec_check[$start];
				$pid[]=getProfileidFromChecksum($receiver_id);
			}
		}
		if(is_array($pid))
		{
			$pidStr = implode(",",$pid);
			$sql="DELETE FROM IGNORE_PROFILE WHERE IGNORED_PROFILEID IN($pidStr) AND PROFILEID=$profileid";
		}
		else
			$sql="DELETE FROM IGNORE_PROFILE WHERE IGNORED_PROFILEID=$pid AND PROFILEID=$profileid";
		$result=mysql_query_decide($sql,$db) or logError("error",$sql);
		if($data["GENDER"]=="F")
			$smarty->assign("HE_SHE","He");
		else
			$smarty->assign("HE_SHE","She");
		$smarty->assign("USERNAME",$username);
		$reloadFirstPage=false;
		if($total_profile-$total_contact==0)
			$reloadFirstPage=true;
		if($reload == 1)
			$reloadFirstPage=false;
		$smarty->assign('reloadFirstPage',$reloadFirstPage);
		$smarty->display("ignored_confirmation.htm");
	}
	else
	{
		include_once("include_file_for_login_layer.php");
		$smarty->display("login_layer.htm");
	}

	// flush the buffer
	if($zipIt)
	ob_end_flush();
?>
