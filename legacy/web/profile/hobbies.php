<?php
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it
	
	include("connect.inc");
	
	$db=connect_db();
	$data=authenticated($checksum);
	if($data["BUREAU"]==1 && ($_COOKIE['JSMBLOGIN'] || $mbureau=="bureau"))
	{
		$fromprofilepage=1;
		mysql_select_db_js('marriage_bureau');
		include('../marriage_bureau/connectmb.inc');
		$mbdata=authenticatedmb($mbchecksum);
		if(!$mbdata)timeoutmb();
		$smarty->assign("source",$mbdata["SOURCE"]);
		$smarty->assign("mbchecksum",$mbdata["CHECKSUM"]);
		mysql_select_db_js('newjs');
		//$data=login_every_user($profileid);
		$mbureau="bureau1";
	}
	/*************************************Portion of Code added for display of Banners*******************************/
	//$regionstr=8;
	//include("../bmsjs/bms_display.php");
	$smarty->assign("data",$data["PROFILEID"]);
       $smarty->assign("bms_topright",18);
       $smarty->assign("bms_right",28);
       $smarty->assign("bms_bottom",19);
       $smarty->assign("bms_left",24);
       $smarty->assign("bms_new_win",32);
	/************************************************End of Portion of Code*****************************************/

	/**************************Added By Shakti for link tracking**********************/
	link_track("hobbies.php");
	/*********************************************************************************/


	//$db=connect_db();
	if($data)
	{
		$profileid=$data["PROFILEID"];
		
		if($CMDsubmit)
		{
			$arr=array();
			
			if(is_array($HOBBY))
				$arr=array_merge($arr,$HOBBY);
			
			if(is_array($INTEREST))
				$arr=array_merge($arr,$INTEREST);
				
			if(is_array($MUSIC))
				$arr=array_merge($arr,$MUSIC);
				
			if(is_array($BOOK))
				$arr=array_merge($arr,$BOOK);
				
			if(is_array($MOVIE))
				$arr=array_merge($arr,$MOVIE);
				
			if(is_array($SPORTS))
				$arr=array_merge($arr,$SPORTS);
				
			if(is_array($CUISINE))
				$arr=array_merge($arr,$CUISINE);
				
			if(is_array($LANGUAGE))
				$arr=array_merge($arr,$LANGUAGE);
				
			if($DRESS!="")
				$arr[]=$DRESS;
				
			$str=implode($arr,",");
			$value=implode($arr,"','");
			
			$sql="replace into JHOBBY (PROFILEID,HOBBY,ALLMUSIC,ALLBOOK,ALLMOVIE,ALLSPORTS,ALLCUISINE) values ('$profileid','$str','$radio_music','$radio_book','$radio_movie','$radio_sports','$radio_cuisine')";
			
			mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			if($value!="")
			{
				$sql="select KEYWORDS from HOBBIES where VALUE in ('$value')";
				$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
				
				while($myrow=mysql_fetch_array($result))
				{
					$keyword.=$myrow["KEYWORDS"] . " ";
				}
				
				$keyword=trim($keyword);
/******added to include age,height,caste,occupation and residency status in keywords field*************************/
				$sql="select KEYWORDS from JPROFILE where  activatedKey=1 and PROFILEID = '$profileid' ";
				$res=mysql_query_decide($sql) or die(mysql_error_js());
				$myrow=mysql_fetch_array($res);
				$keywords=$myrow["KEYWORDS"];	
				$pos=strpos($keywords,"|");
				if($pos)
					$key=substr($keywords,0,$pos);
				else
					$key=$keywords;
				$kn=$key."|".$keyword;

			$sql="update JPROFILE set KEYWORDS='".addslashes(stripslashes($kn))."',MOD_DT=now() where PROFILEID='$profileid'";
/*end of portion of code*/		
				mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			}
			
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
			if($mbureau=="bureau1")
			{
				$smarty->assign("mb_username_profile",$data["USERNAME"]);
				$smarty->assign("checksum",$data["CHECKSUM"]);
				$smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
			}
			else
		        {
				$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
			}
			$smarty->display("hob_confirm.htm");
		}
		else 
		{
			$sql="select * from JHOBBY where PROFILEID='$profileid'";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			if(mysql_num_rows($result) > 0)
			{
				$myrow=mysql_fetch_array($result);
			
				$myhobby=explode(",",$myrow["HOBBY"]);
				
				$smarty->assign("ALLMUSIC",$myrow["ALLMUSIC"]);
				$smarty->assign("ALLBOOK",$myrow["ALLBOOK"]);
				$smarty->assign("ALLMOVIE",$myrow["ALLMOVIE"]);
				$smarty->assign("ALLSPORTS",$myrow["ALLSPORTS"]);
				$smarty->assign("ALLCUISINE",$myrow["ALLCUISINE"]);
			}
			
			if(!is_array($myhobby))
				$myhobby=array();
				
			mysql_free_result($result);
			
			$sql="select SQL_CACHE VALUE,LABEL,TYPE from HOBBIES order by SORTBY";
			$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			while($myrow=mysql_fetch_array($result))
			{
				if($myrow["TYPE"]=="HOBBY")
				{
					if(in_array($myrow["VALUE"],$myhobby))
						$selected="1";
					else 
						$selected="";
						
					$hobby[]=array("LABEL" => $myrow["LABEL"],
							"VALUE" => $myrow["VALUE"],
							"SELECTED" => $selected);
				}
				elseif($myrow["TYPE"]=="INTEREST")
				{
					if(in_array($myrow["VALUE"],$myhobby))
						$selected="1";
					else 
						$selected="";
						
					$interest[]=array("LABEL" => $myrow["LABEL"],
							"VALUE" => $myrow["VALUE"],
							"SELECTED" => $selected);
				}
				elseif($myrow["TYPE"]=="MUSIC")
				{
					if(in_array($myrow["VALUE"],$myhobby))
						$selected="1";
					else 
						$selected="";
						
					$music[]=array("LABEL" => $myrow["LABEL"],
							"VALUE" => $myrow["VALUE"],
							"SELECTED" => $selected);
				}
				elseif($myrow["TYPE"]=="BOOK")
				{
					if(in_array($myrow["VALUE"],$myhobby))
						$selected="1";
					else 
						$selected="";
						
					$book[]=array("LABEL" => $myrow["LABEL"],
							"VALUE" => $myrow["VALUE"],
							"SELECTED" => $selected);
				}
				elseif($myrow["TYPE"]=="MOVIE")
				{
					if(in_array($myrow["VALUE"],$myhobby))
						$selected="1";
					else 
						$selected="";
						
					$movie[]=array("LABEL" => $myrow["LABEL"],
							"VALUE" => $myrow["VALUE"],
							"SELECTED" => $selected);
				}
				elseif($myrow["TYPE"]=="SPORTS")
				{
					if(in_array($myrow["VALUE"],$myhobby))
						$selected="1";
					else 
						$selected="";
						
					$sports[]=array("LABEL" => $myrow["LABEL"],
							"VALUE" => $myrow["VALUE"],
							"SELECTED" => $selected);
				}
				elseif($myrow["TYPE"]=="CUISINE")
				{
					if(in_array($myrow["VALUE"],$myhobby))
						$selected="1";
					else 
						$selected="";
	
					$cuisine[]=array("LABEL" => $myrow["LABEL"],
							"VALUE" => $myrow["VALUE"],
							"SELECTED" => $selected);
				}
				elseif($myrow["TYPE"]=="DRESS")
				{
					if(in_array($myrow["VALUE"],$myhobby))
						$selected="1";
					else 
						$selected="";
						
					$dress[]=array("LABEL" => $myrow["LABEL"],
							"VALUE" => $myrow["VALUE"],
							"SELECTED" => $selected);
				}
				elseif($myrow["TYPE"]=="LANGUAGE")
				{
					if(in_array($myrow["VALUE"],$myhobby))
						$selected="1";
					else 
						$selected="";
						
					$language[]=array("LABEL" => $myrow["LABEL"],
							"VALUE" => $myrow["VALUE"],
							"SELECTED" => $selected);
				}
			}
				
			$smarty->assign("HOBBY",$hobby);
			$smarty->assign("INTEREST",$interest);
			$smarty->assign("MUSIC",$music);
			$smarty->assign("BOOK",$book);
			$smarty->assign("MOVIE",$movie);
			$smarty->assign("SPORTS",$sports);
			$smarty->assign("CUISINE",$cuisine);
			$smarty->assign("DRESS",$dress);
			$smarty->assign("LANGUAGE",$language);
				
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
			$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
			if($mbureau=="bureau1")
			{
				$smarty->assign("mb_username_profile",$data["USERNAME"]);
				$smarty->assign("checksum",$data["CHECKSUM"]);
				$smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
				$smarty->assign("LEFTPANEL",$smarty->fetch("mb_side_links.htm"));
			}
			else
			{
				$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
				$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			}
			$smarty->display("hobbies_interests.htm");
		}
	}
	else 
	{
		TimedOut();
	}
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();

?>	
