<?php
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it

	include("connect.inc");

	$lang=$_COOKIE["JS_LANG"];

	$db=connect_db();
        $data=authenticated($checksum);

	/*************************************Portion of Code added for display of Banners*******************************/
	if(strstr($_SERVER['HTTP_USER_AGENT'],'MSIE 5.5'))
		$smarty->assign("class","hand");
	else
		$smarty->assign("class","pointer");
        $smarty->assign("CHECKSUM",$checksum);

	$tracepath=mysql_real_escape_string($tracepath);

	if(!$tracepath)
		$tracepath=1;
	if($tracepath)
	{
		$i=0;
		$sql="SELECT ID,QUESTION,ANSWER FROM feedback.QADATA WHERE PARENT = '$tracepath' AND PUBLISH='Y'";
		$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","$sql","ShowErrTemplate");
		while($row=mysql_fetch_array($res))
		{	
			if(($row['ID']==0)||($row['ID']==15)||($row['ID']==19)||($row['ID']==18)||($row['ID']==60))
				$arr[$i]["chk"]=1;
			else
				$arr[$i]["chk"]=0;
			$arr[$i]["id"]=$row['ID'];
			$arr[$i]["name"]=$row['QUESTION'];
			$arr[$i]["answer"]=$row['ANSWER'];
			$i++;
		}
		$smarty->assign("trace1","0.".$tracepath);			
		$smarty->assign("linkarr",$arr);
	}
	$sql="SELECT ID,QUESTION FROM feedback.QADATA WHERE PARENT=0 AND PUBLISH='Y'";
	$res=mysql_query_decide($sql);
	$i=0;
	while($row=mysql_fetch_array($res))
	{
		$arrstart[$i]["id"]=$row['ID'];
		$arrstart[$i]["name"]=$row['QUESTION'];
		if($tracepath==$arrstart[$i]["id"])
			$current=$arrstart[$i]["id"];
		elseif(!$tracepath)
				$current=1;
		$smarty->assign("trace",0);
		$i++;
	}
	/*************************************Portion of Code added for display of Banners*******************************/
	$smarty->assign("data",$data["PROFILEID"]);
	$smarty->assign("bms_topright",18);
	$smarty->assign("bms_bottom",19);
	$smarty->assign("bms_left",24);
	$smarty->assign("bms_new_win",32);
	/************************************************End of Portion of Code*****************************************/
	include_once("sphinx_search_function.php");//to be tested later
	savesearch_onsubheader($data["PROFILEID"]);//to be tested later
	$smarty->assign("current",$current);
	$smarty->assign("arrstart",$arrstart);
	$smarty->assign("nonstyle",1);
	$smarty->assign("flagged",$flagged);
	$smarty->assign("NO_NAVIGATION","1");
	$smarty->assign("FOOT",$smarty->fetch("footer.htm"));//Added for revamp
	$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
	$smarty->assign("head_tab",'my jeevansathi');
	$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
	//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
	rightpanel($data);
	$smarty->assign("REVAMP_RIGHT_PANEL",$smarty->fetch("revamp_rightpanel.htm"));

	$smarty->display("faqs_new.htm");

	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
