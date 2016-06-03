<?php
/***************************************************************************************************************
* FILE NAME     : cellsearch.php
* DESCRIPTION   : Generates an XML output according to a given query string.
* CREATION DATE : 8 December, 2005
* CREATED BY    : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/
//mail("vikas@jeevansathi.com","acl call",$_SERVER['REQUEST_URI']);
include "../../profile/connect.inc";
include "../../profile/contact_sms.inc";
include "cellsearch.inc";
include("cellsearch_array.php");
		
$PAGELEN=10;

$db=connect_db();


if($act=="BRDS" || $act=="BRDG" || $act=="SRCH")
{
		$sql[]="SELECT PROFILEID FROM ";

		if($act=='BRDG')
		{
			$sql[]=" newjs.SEARCH_MALE ";
		}
		else if($act=='BRDS')
		{
			$sql[]=" newjs.SEARCH_FEMALE ";
		}
		else if($gender=="M")
		{
                        $sql[]=" newjs.SEARCH_MALE ";
                }
		else if($gender=="F")
                {
                        $sql[]=" newjs.SEARCH_FEMALE ";
                }
		else
		{
			die("parameter missing");
		}

		if($age!='')
		{
			if($age=='above 50')
			{
				$sql[]=" AGE > 50 ";
			}
			else
			{
				list($lage,$hage)=explode("-",$age);

				if($lage && $hage)
					$sql[]=" AGE BETWEEN '".$lage."' AND '".$hage."' ";
				else
					$sql[]=" AGE = '".$lage."' ";
			}
		}

		$searchCaste="";

		if($keyword!="")
		{
			$open_field_sms=explode(" ",$keyword);

			for($tmpss=0;$tmpss<count($open_field_sms);$tmpss++)
			{
				$sql_open="SELECT VALUE,TYPE FROM newjs.GLOSSARY WHERE LABEL='".$open_field_sms[$tmpss]."'";
	                        $res_open=mysql_query_decide($sql_open) or queryDieLog(mysql_error_js(),$sql_open);
				if(mysql_num_rows($res_open)>0)
				{
					$row_open=mysql_fetch_array($res_open);
					if($row_open['TYPE']=='CASTE')
						$CASTE[]=$row_open['VALUE'];
					if($row_open['TYPE']=='MTONGUE')
						$MTONGUE[]=$row_open['VALUE'];
//					if($row_open['TYPE']=='RELIGION')
//						$RELIGION=$row_open['VALUE'];
				}
			}

                        if(is_array($CASTE) && !in_array("All",$CASTE))
                        {
                                $seCaste=get_all_caste($CASTE);
                                if(is_array($seCaste))
                                        $searchCaste="'" . implode($seCaste,"','") . "'";
                        }
		}

		if($caste!="")
		{
			$open_field=explode("/",$caste);
			
			$sql_open="SELECT VALUE,TYPE FROM newjs.GLOSSARY WHERE LABEL='".$open_field[0]."'";
			$res_open=mysql_query_decide($sql_open) or queryDieLog(mysql_error_js(),$sql_open);
			if(mysql_num_rows($res_open)>0)
			{
				$row_open=mysql_fetch_array($res_open);
				if($row_open['TYPE']=='CASTE')
				{
					$CASTE=$row_open['VALUE'];
				}
				else if($row_open['TYPE']=='MTONGUE')
				{
					$MTONGUE=$row_open['VALUE'];
				}
			}

			$sql_open="SELECT VALUE,TYPE FROM newjs.GLOSSARY WHERE LABEL='".$open_field[1]."'";
                        $res_open=mysql_query_decide($sql_open) or queryDieLog(mysql_error_js(),$sql_open);
                        if(mysql_num_rows($res_open)>0)
                        {
                                $row_open=mysql_fetch_array($res_open);
                                if($row_open['TYPE']=='CASTE')
                                {
                                        $CASTE=$row_open['VALUE'];
                                }
                                else if($row_open['TYPE']=='MTONGUE')
                                {
                                        $MTONGUE=$row_open['VALUE'];
                                }
                        }

			if(!is_array($CASTE) && $CASTE!="" && $CASTE!="All")
			{
				$tempcaste=$CASTE;
				unset($CASTE);
				$CASTE[0]=$tempcaste;
			}
											
			if(is_array($CASTE) && !in_array("All",$CASTE))
			{
				$seCaste=get_all_caste($CASTE);
				if(is_array($seCaste))
					$searchCaste="'" . implode($seCaste,"','") . "'";
			}
		}

//		if($searchCaste=="" && ($religion!="" || $RELIGION!=""))
		if($searchCaste=="" && $religion!="")
		{
//			if($religion)
//			{
				$sql_rel="SELECT VALUE FROM newjs.RELIGION WHERE LABEL='".$religion."'";
				$res_rel=mysql_query_decide($sql_rel) or queryDieLog(mysql_error_js(),$sql_rel);
				$row_rel=mysql_fetch_array($res_rel);
				$religion_val=$row_rel['VALUE'];
/*
			}
			else if($RELIGION)
				$religion_val=$RELIGION;
*/

			$sql_cache="select SQL_CACHE VALUE from CASTE where PARENT='".$religion_val."' and ISALL='Y'";
			$res_cache=mysql_query_decide($sql_cache);
														
			$res_row=mysql_fetch_array($res_cache);
			$CASTE[0]=$res_row["VALUE"];
														
			$seCaste=get_all_caste($CASTE);
			if(is_array($seCaste))
				$searchCaste="'" . implode($seCaste,"','") . "'";
		}

		if($searchCaste!="")
		{
			if(strstr($searchCaste,","))
				$sql[]=" CASTE IN ($searchCaste) ";
			else
				$sql[]=" CASTE=$searchCaste ";
		}

		$mstatus=strtoupper($mstatus);
		if($mstatus!="ALL" && $mstatus!="ANY" && $mstatus!="")
		{
			if($mstatus=="NEVER MARRIED")
				$sql[]=" MSTATUS='N' ";
			else if($mstatus=="MARRIED")
				$sql[]=" MSTATUS IN ('W','D','S') ";
		}

		if($MTONGUE!="")
		{
			if(is_array($MTONGUE))
			{
				$temparr=$MTONGUE;
				unset($MTONGUE);
				$MTONGUE=implode($temparr,"','");
			}

			$sql[]=" MTONGUE ='$MTONGUE' ";
		}

		if($MSISDN)
		{
			if(strlen($MSISDN)>10)
	                {
                	        $MSISDN=ltrim($MSISDN,"9");
                        	$MSISDN=ltrim($MSISDN,"1");
	                }
        	        $cell=$MSISDN;

			$sqlContact="SELECT PROFILEID,GENDER FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."'";
			$resContact=mysql_query_decide($sqlContact) or queryDieLog(mysql_error_js(),$sqlContact);
			$rowContact=mysql_fetch_array($resContact);
			$cellProfileid=$rowContact['PROFILEID'];
			$cellGender=$rowContact['GENDER'];

			$sqlContact="SELECT RECEIVER FROM newjs.CONTACTS WHERE SENDER='".$cellProfileid."'";
			$resContact=mysql_query_decide($sqlContact) or queryDieLog(mysql_error_js(),$sqlContact);
			while($rowContact=mysql_fetch_array($resContact))
			{
				$donotDisplay[]=$rowContact['RECEIVER'];
			}

			$sqlContact="SELECT SENDER FROM newjs.CONTACTS WHERE RECEIVER='".$cellProfileid."'";
                        $resContact=mysql_query_decide($sqlContact) or queryDieLog(mysql_error_js(),$sqlContact);
                        while($rowContact=mysql_fetch_array($resContact))
                        {
                                $donotDisplay[]=$rowContact['SENDER'];
                        }

			if(count($donotDisplay)>1)
				$dontDisplay=implode("','",$donotDisplay);
			else
				$dontDisplay=$donotDisplay[0];

			if($dontDisplay)
				$sql[]=" PROFILEID NOT IN ('".$dontDisplay."') ";
		}

		$sql1=$sql[0].$sql[1];

		if(count($sql)>2)
			$sql1.=" WHERE ";

		for($sstemp=2;$sstemp<=count($sql)-1;$sstemp++)
		{
			$sql1.=$sql[$sstemp]." AND";
		}

		$sql1=rtrim($sql1,"AND");

		$sql1.=" ORDER BY SORT_DT DESC LIMIT 24";

		unset($sql);
//		echo $sql1;

		if($res=mysql_query_decide($sql1))		//in order to avoid "not a valid result resource"
		{
			if(mysql_num_rows($res)>0)	//if there is no result satisfying the criterea
			{
				$results=displayresult($res,0,"cellsearch.php","","",1,"","","");
					
				$Ret = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
				$Ret.="<ProfileList>\n";	
				echo $Ret;
				unset($Ret);

				$arr_search=array('&','<','>',"'",'"');
				$arr_repl=array('&amp;','&lt;','&gt;','&apos;','&quot;');

				for($a=0;$a<count($results);$a++)
				{
					unset($shortdesc);
					unset($longdesc);

					if($results[$a]['USERNAME'])
						$shortdesc=$results[$a]['USERNAME'];

					$shortdesc=str_replace($arr_search,$arr_repl,$shortdesc);
					$shortdesc=substr($shortdesc,0,13);

//					$longdesc=$shortdesc.",".$results[$a]['CASTE'].",".$results[$a]['RESIDENCE'];
					if($shortdesc)
					{
						$longdesc=$shortdesc;

						if($results[$a]['AGE'])
							$longdesc.=",".str_replace($arr_search,$arr_repl,$results[$a]['AGE']);

						if($results[$a]['HEIGHT'])
							$longdesc.=",".$results[$a]['HEIGHT'];

						if($results[$a]['CASTE'])
							$longdesc.=",".str_replace($arr_search,$arr_repl,$results[$a]['CASTE']);

						if($results[$a]['MTONGUE'])
							$longdesc.=",".str_replace($arr_search,$arr_repl,$results[$a]['MTONGUE']);

						if($results[$a]['DEGREE'])
							$longdesc.=",".str_replace($arr_search,$arr_repl,$results[$a]['DEGREE']);

						if($results[$a]['OCCUPATION'])
							$longdesc.=",".str_replace($arr_search,$arr_repl,$results[$a]['OCCUPATION']);

						if($results[$a]['RESIDENCE'])
							$longdesc.=",".str_replace($arr_search,$arr_repl,$results[$a]['RESIDENCE']);

						if($cellGender=="F" && $results[$a]['INCOME'])
						{
							$longdesc.=",".str_replace($arr_search,$arr_repl,$results[$a]['INCOME']);
						}
						else if($cellGender=="M")
						{
							if($results[$a]['BODY'])
								$longdesc.=",".str_replace($arr_search,$arr_repl,$BODYTYPE[$results[$a]['BODY']]);
							if($results[$a]['COMPLEXION'])
								$longdesc.=",".str_replace($arr_search,$arr_repl,$COMPLEXION[$results[$a]['COMPLEXION']]);
						}
					}

//					$longdesc=str_replace($arr_search,$arr_repl,$longdesc);
					$longdesc=substr($longdesc,0,120);

					$Ret="\t<Profile>\n";
					$Ret.="\t\t<Profileid>".$results[$a]['PROFILEID']."</Profileid>\n";

					if($shortdesc)
						$Ret.="\t\t<ShortDesc>".$shortdesc."</ShortDesc>\n";

					if($longdesc)
						$Ret.="\t\t<LongDesc>".$longdesc."</LongDesc>\n";

					$Ret.="\t</Profile>\n";
					echo $Ret;
					unset($Ret);
				}
				$Ret="</ProfileList>\n";

				echo $Ret;
			}
			else
			{
				echo "no results";
				$qs=$_SERVER['REQUEST_URI'];
				noResultLog($qs);
			}
		}
		else
		{
			queryDieLog(mysql_error_js(),$sql1);
		}
}
else if($act=="STTS")
{
	if($MSISDN && $pwd)
	{
		if(strlen($MSISDN)>10)
		{
			$MSISDN=ltrim($MSISDN,"9");
			$MSISDN=ltrim($MSISDN,"1");
		}
		$cell=$MSISDN;
			
		$sql="SELECT PROFILEID,LAST_LOGIN_DT,GENDER,SUBSCRIPTION FROM newjs.JPROFILE WHERE PHONE_MOB = '".$cell."' AND PASSWORD='".$pwd."'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	
		//if in db
		if(mysql_num_rows($res)>0)
		{	//proceed to display data according to statusopt

			$row=mysql_fetch_array($res);
			$cellprofile=$row['PROFILEID'];
			$last_login_date=$row['LAST_LOGIN_DT'];
			$cellpaid=$row['SUBSCRIPTION'];
			$sender_details=array("GENDER"=>$row['GENDER']);

			//find profiles waiting and contacted

			$sql="SELECT RECEIVER FROM newjs.CONTACTS WHERE SENDER='".$cellprofile."' AND TYPE='A' AND TIME > '".$last_login_date." 00:00:00' ORDER BY TIME DESC LIMIT 24";
			$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
			$count_by_me=mysql_num_rows($res);

			$sql="SELECT SENDER FROM newjs.CONTACTS WHERE RECEIVER='".$cellprofile."' AND TYPE='I' AND TIME > '".$last_login_date." 00:00:00' ORDER BY TIME DESC LIMIT 24";
			$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
			$count_by_them=mysql_num_rows($res);

			echo $count_by_me." profiles shown interest.<BR>".$count_by_them." profiles waiting for you.";
		}
		//wrong password and not registered logic comes here
		else
		{
			$sql="SELECT PASSWORD FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."'";
			$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	
			if(mysql_num_rows($res)>0)
			{
				$row=mysql_fetch_array($res);
				$PASSWORD=$row['PASSWORD'];

				if($PASSWORD!=$pwd)
					echo "wrong";
			}
			else
			{
				//please register
				echo "not registered";
			}
		}
	}
	else if($MSISDN && $statusopt)
	{
		if(strlen($MSISDN)>10)
                {
                        $MSISDN=ltrim($MSISDN,"9");
                        $MSISDN=ltrim($MSISDN,"1");
                }
                $cell=$MSISDN;

		$sql="SELECT PROFILEID,LAST_LOGIN_DT,SUBSCRIPTION,GENDER FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);

		if(mysql_num_rows($res)<=0)
		{
			echo "not registered";
			exit;
		}

		$row=mysql_fetch_array($res);

		$cellprofile=$row['PROFILEID'];
		$last_login_date=$row['LAST_LOGIN_DT'];
		$cellpaid=$row['SUBSCRIPTION'];
		$sender_details=array("GENDER"=>$row['GENDER']);

		if($statusopt=="BY_ME" || $statusopt=="LISTW")
		{
			//Profiles you contacted and who have accepted you.

			$sql="SELECT RECEIVER FROM newjs.CONTACTS WHERE SENDER='".$cellprofile."' AND TYPE='A' AND TIME > '".$last_login_date." 00:00:00' ORDER BY TIME DESC LIMIT 24";
			$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);

			if(mysql_num_rows($res))
			{
				$results=displayresult($res,0,"cellsearch.php","","",1,"","","");

				$Ret = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
				$Ret.="<ProfileList>\n";
				echo $Ret;
				unset($Ret);
                                                                                                                            
				$arr_search=array('&','<','>',"'",'"');
				$arr_repl=array('&amp;','&lt;','&gt;','&apos;','&quot;');
                                                                                                                            
				for($a=0;$a<count($results);$a++)
				{
					unset($shortdesc);
					unset($longdesc);

                                        if($results[$a]['USERNAME'])
                                                $shortdesc=$results[$a]['USERNAME'];

					$shortdesc=str_replace($arr_search,$arr_repl,$shortdesc);
					$shortdesc=substr($shortdesc,0,13);
                                                                                                                            
//					$longdesc=$shortdesc.",".$results[$a]['CASTE'].",".$results[$a]['RESIDENCE'];
					if($shortdesc)
					{
						$longdesc=$shortdesc;

						if($results[$a]['AGE'])
							$longdesc.=",".$results[$a]['AGE'];

						if($results[$a]['HEIGHT'])
							$longdesc.=",".$results[$a]['HEIGHT'];

						if($results[$a]['CASTE'])
							$longdesc.=",".$results[$a]['CASTE'];

						if($results[$a]['MTONGUE'])
							$longdesc.=",".$results[$a]['MTONGUE'];

						if($results[$a]['DEGREE'])
							$longdesc.=",".$results[$a]['DEGREE'];

						if($results[$a]['OCCUPATION'])
							$longdesc.=",".$results[$a]['OCCUPATION'];

						if($results[$a]['RESIDENCE'])
							$longdesc.=",".$results[$a]['RESIDENCE'];
					}

					$longdesc=str_replace($arr_search,$arr_repl,$longdesc);
					$longdesc=substr($longdesc,0,120);
													    
					$Ret="\t<Profile>\n";
					$Ret.="\t\t<Profileid>".$results[$a]['PROFILEID']."</Profileid>\n";

					if($shortdesc)
						$Ret.="\t\t<ShortDesc>".$shortdesc."</ShortDesc>\n";
                                                                                                                            
					if($longdesc)
						$Ret.="\t\t<LongDesc>".$longdesc."</LongDesc>\n";
                                                                                                                            
					$Ret.="\t</Profile>\n";
					echo $Ret;
					unset($Ret);
				}
				$Ret="</ProfileList>\n";
													    
				echo $Ret;
			}
			else
			{
				echo "no results";
			}
		}
		else if($statusopt=="BY_THEM" || $statusopt=="LISTC")
		{
			//display profiles who contacted you.

			$sql="SELECT SENDER FROM newjs.CONTACTS WHERE RECEIVER='".$cellprofile."' AND TYPE='I' AND TIME > '".$last_login_date." 00:00:00' ORDER BY TIME DESC LIMIT 24";
			$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);

			if(mysql_num_rows($res))
			{
				$results=displayresult($res,0,"cellsearch.php","","",1,"","","");
													
				$Ret = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
				$Ret.="<ProfileList>\n";
				echo $Ret;
				unset($Ret);
                                                                                                                            
				$arr_search=array('&','<','>',"'",'"');
				$arr_repl=array('&amp;','&lt;','&gt;','&apos;','&quot;');
                                                                                                                            
				for($a=0;$a<count($results);$a++)
				{
					unset($shortdesc);
					unset($longdesc);

                                        if($results[$a]['USERNAME'])
                                                $shortdesc=$results[$a]['USERNAME'];
                                                                                                                            
					$shortdesc=str_replace($arr_search,$arr_repl,$shortdesc);
					$shortdesc=substr($shortdesc,0,13);
                                                                                                                            
//					$longdesc=$shortdesc.",".$results[$a]['CASTE'].",".$results[$a]['RESIDENCE'];
					if($shortdesc)
                                        {
						$longdesc=$shortdesc;

						if($results[$a]['AGE'])
							$longdesc.=",".$results[$a]['AGE'];

						if($results[$a]['HEIGHT'])
							$longdesc.=",".$results[$a]['HEIGHT'];

						if($results[$a]['CASTE'])
							$longdesc.=",".$results[$a]['CASTE'];

						if($results[$a]['MTONGUE'])
							$longdesc.=",".$results[$a]['MTONGUE'];

						if($results[$a]['DEGREE'])
							$longdesc.=",".$results[$a]['DEGREE'];

						if($results[$a]['OCCUPATION'])
							$longdesc.=",".$results[$a]['OCCUPATION'];

						if($results[$a]['RESIDENCE'])
							$longdesc.=",".$results[$a]['RESIDENCE'];
					}

					$longdesc=str_replace($arr_search,$arr_repl,$longdesc);
					$longdesc=substr($longdesc,0,120);
                                                                                                                            
					$Ret="\t<Profile>\n";
					$Ret.="\t\t<Profileid>".$results[$a]['PROFILEID']."</Profileid>\n";
                                                                                                                            
					if($shortdesc)
						$Ret.="\t\t<ShortDesc>".$shortdesc."</ShortDesc>\n";
                                                                                                                            
					if($longdesc)
						$Ret.="\t\t<LongDesc>".$longdesc."</LongDesc>\n";
                                                                                                                            
					$Ret.="\t</Profile>\n";
					echo $Ret;
					unset($Ret);
				}
				$Ret="</ProfileList>\n";
                                                                                                                            
				echo $Ret;
			}
			else
			{
				echo "no results";
			}
		}
	}
	else
	{
		echo "parameters missing";
	}
}
else if($profileid && $act=="")
{
	if(strlen($MSISDN)>10)
	{
		$MSISDN=ltrim($MSISDN,"9");
		$MSISDN=ltrim($MSISDN,"1");
	}
	$cell=$MSISDN;
														     
	$sql="SELECT PROFILEID,LAST_LOGIN_DT,GENDER,SUBSCRIPTION FROM newjs.JPROFILE WHERE PHONE_MOB = '".$cell."' AND PASSWORD='".$pwd."'";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	
	if(mysql_num_rows($res)>0)
	{
		$row=mysql_fetch_array($res);

		$cellprofile=$row['PROFILEID'];
		$last_login_date=$row['LAST_LOGIN_DT'];
		$sender_details=array("GENDER"=>$row['GENDER']);
		$cellpaid=$row['SUBSCRIPTION'];

		$contact_status=get_contact_status($cellprofile,$profileid);
		
		if($contact_status=="")
		{
			$tempvar="";
			if(can_contact($cellprofile,$profileid,$sender_details,$tempvar))
			{
					make_initial_contact($cellprofile,$profileid,"","","","","","");
					echo "done";
			}
			else
			{
				echo "error";
			}
		}
		else if($contact_status=="I")
		{
			$tempvar="";
			if(can_contact($cellprofile,$profileid,$sender_details,$tempvar))
			{
					make_initial_contact($cellprofile,$profileid,"","",1,"","","");
					echo "done";
			}
			else
			{
				echo "error";
			}

		}
		else if($contact_status=="RI")
		{
			send_response($cellprofile,$profileid,"A","","","");
			echo "done";
		}
		else if($contact_status=="RA" || $contact_status=="A")
		{
			if($cellpaid)
			{
				$sql="select EMAIL,SHOWPHONE_RES,SHOWPHONE_MOB,CONTACT,PHONE_RES,PHONE_MOB,SHOWADDRESS,MESSENGER_ID,MESSENGER_CHANNEL,SHOWMESSENGER,PARENTS_CONTACT,SHOW_PARENTS_CONTACT from JPROFILE where PROFILEID='$profileid'";
				$result=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
				$row=mysql_fetch_array($result);
                                                                                                                             
				$contactmsg="The details of the user are:\n";
													     
				if($row['SHOWPHONE_RES']=="Y" && $row['PHONE_RES']!="")
					$contactmsg.="Phone: ".$row['PHONE_RES']."\n";
                                                                                                                             
				if($row['SHOWPHONE_MOB']=="Y" && $row['PHONE_MOB']!="")
					$contactmsg.="Mobile: ".$row['PHONE_MOB']."\n";
                                                                                                                             
				if($row['CONTACT']!="" && $row['SHOWADDRESS']=="Y")
					$contactmsg.="Address: ".$row['CONTACT'];
                                                                                                                             
				if($row['EMAIL']!="")
					$contactmsg.=" E-Mail: ".$row['EMAIL'];

				echo $contactmsg;
			}
			else
			{
				echo "upgrade";
			}
		}
		else
		{
			echo "error";
		}
	}
	else
	{
		$sql="SELECT PASSWORD FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
														     
		if(mysql_num_rows($res)>0)
		{
			$row=mysql_fetch_array($res);
			$PASSWORD=$row['PASSWORD'];
                                                                                                                             
			if($PASSWORD!=$pwd)
				echo "wrong";
		}
		else
		{
			//please register
			echo "not registered";
		}
	}
}
else if($act=="FIND")
{
        $sql[]="SELECT PROFILEID FROM ";
                                                                                                                             
        if($gender=="M")
        {
                $sql[]=" newjs.SEARCH_MALE ";
        }
        else if($gender=="F")
        {
                $sql[]=" newjs.SEARCH_FEMALE ";
        }
        else
        {
                die("parameter missing");
        }
                                                                                                                             
        if($age!='')
        {
                list($lage,$hage)=explode("-",$age);
                                                                                                                             
                if($lage && $hage)
                        $sql[]=" AGE BETWEEN '".$lage."' AND '".$hage."' ";
                else
                        $sql[]=" AGE = '".$lage."' ";
        }
                                                                                                                             
        if($keyword!="")
        {
                $open_field_sms=explode(" ",$keyword);
                                                                                                                             
                for($tmpss=0;$tmpss<count($open_field_sms);$tmpss++)
                {
                        $sql_open="SELECT VALUE,TYPE FROM newjs.GLOSSARY WHERE LABEL='".$open_field_sms[$tmpss]."'";
                        $res_open=mysql_query_decide($sql_open) or queryDieLog(mysql_error_js(),$sql_open);
                        if(mysql_num_rows($res_open)>0)
                        {
                                $row_open=mysql_fetch_array($res_open);
                                if($row_open['TYPE']=='CASTE')
                                        $CASTE[]=$row_open['VALUE'];
                                if($row_open['TYPE']=='MTONGUE')
                                        $MTONGUE[]=$row_open['VALUE'];
                        }
                }
                                                                                                                             
                if(is_array($CASTE) && !in_array("All",$CASTE))
                {
                        $seCaste=get_all_caste($CASTE);
                        if(is_array($seCaste))
                                $searchCaste="'" . implode($seCaste,"','") . "'";
                }
        }
                                                                                                                             
        if($searchCaste!="")
        {
                if(strstr($searchCaste,","))
                        $sql[]=" CASTE IN ($searchCaste) ";
                else
                                $sql[]=" CASTE=$searchCaste ";
        }
                                                                                                                             
        if($MTONGUE!="")
        {
                if(is_array($MTONGUE))
                {
                        $temparr=$MTONGUE;
                        unset($MTONGUE);
                        $MTONGUE=implode($temparr,"','");
                }
                $sql[]=" MTONGUE ='$MTONGUE' ";
        }
                                                                                                                             
	if($MSISDN)
	{
		if(strlen($MSISDN)>10)
		{
			$MSISDN=ltrim($MSISDN,"9");
			$MSISDN=ltrim($MSISDN,"1");
		}
		$cell=$MSISDN;
	
		//Getting phone user's profileid, gender and subscription	
		$sqlContact="SELECT PROFILEID,GENDER,SUBSCRIPTION FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."'";
		$resContact=mysql_query_decide($sqlContact) or queryDieLog(mysql_error_js(),$sqlContact);
		$rowContact=mysql_fetch_array($resContact);
		$cellProfileid=$rowContact['PROFILEID'];
		$cellGender=$rowContact['GENDER'];

		if(strstr($rowContact['SUBSCRIPTION'],"F"))
			$cellUserIsPaid="Y";
	
		//Profiles contacted by phone user should not be displayed in search results.	
		$sqlContact="SELECT RECEIVER FROM newjs.CONTACTS WHERE SENDER='".$cellProfileid."'";
		$resContact=mysql_query_decide($sqlContact) or queryDieLog(mysql_error_js(),$sqlContact);
		while($rowContact=mysql_fetch_array($resContact))
		{
			$donotDisplay[]=$rowContact['RECEIVER'];
		}
                                                                                            
		//Profiles who have contacted phone user should also not be displayed in search results
		$sqlContact="SELECT SENDER FROM newjs.CONTACTS WHERE RECEIVER='".$cellProfileid."'";
		$resContact=mysql_query_decide($sqlContact) or queryDieLog(mysql_error_js(),$sqlContact);
		while($rowContact=mysql_fetch_array($resContact))
		{
			$donotDisplay[]=$rowContact['SENDER'];
		}
	}

	//All user's who have set BLOCKALL should not be displayed in search results
	$sqlBlock="SELECT PROFILEID FROM newjs.SMS_BLOCK WHERE BLOCK_STATUS='BLOCKALL'";
	$resBlock=mysql_query_decide($sqlBlock) or queryDieLog(mysql_error_js(),$sqlBlock);
	while($rowBlock=mysql_fetch_array($resBlock))
	{
		$donotDisplay[]=$rowBlock['PROFILEID'];
	}

	//All user's who have blocked only the phone user should not be displayed in search results
	$sqlBlock="SELECT PROFILEID,BLOCKED_USERS FROM newjs.SMS_BLOCK WHERE BLOCK_STATUS='BLOCKONLY'";
	$resBlock=mysql_query_decide($sqlBlock) or queryDieLog(mysql_error_js(),$sqlBlock);
	while($rowBlock=mysql_fetch_array($resBlock))
	{
		$arrayToMatch=explode(",",$rowBlock['BLOCKED_USERS']);

		if(in_array($cellProfileid,$arrayToMatch))
			$donotDisplay[]=$rowBlock['PROFILEID'];

		unset($arrayToMatch);
	}

	$sqlBlock="SELECT PROFILEID,BLOCKED_USERS FROM newjs.SMS_BLOCK WHERE BLOCK_STATUS='BLOCKALLEXCEPT'";
	$resBlock=mysql_query_decide($sqlBlock) or queryDieLog(mysql_error_js(),$sqlBlock);
	while($rowBlock=mysql_fetch_array($resBlock))
	{
		$arrayToMatch=explode(",",$rowBlock['BLOCKED_USERS']);

		if(!in_array($cellProfileid,$arrayToMatch))
			$donotDisplay[]=$rowBlock['PROFILEID'];

		unset($arrayToMatch);
	}


	if(count($donotDisplay)>1)
		$dontDisplay=implode("','",$donotDisplay);
	else
		$dontDisplay=$donotDisplay[0];

	if($dontDisplay)
		$sql[]=" PROFILEID NOT IN ('".$dontDisplay."') ";
														     
        $sql[]=" HAVE_PHONE_MOB = 'Y' ";
                                                                                                                             
        $sql1=$sql[0].$sql[1];
                                                                                                                             
        if(count($sql)>2)
	{
		if($cellUserIsPaid=="Y")
	                $sql1.=" WHERE GET_SMS='Y' AND ";
		else
			$sql1.=" WHERE GET_SMS='Y' AND E_RISHTA='Y' AND ";
	}
                                                                                                                             
        for($sstemp=2;$sstemp<=count($sql)-1;$sstemp++)
        {
                $sql1.=$sql[$sstemp]." AND";
        }
                                                                                                                             
        $sql1=rtrim($sql1,"AND");
                                                                                                                             
        $sql1.=" ORDER BY SORT_DT DESC LIMIT 24";
                                                                                                                             
        unset($sql);
//        echo $sql1."<BR>";
                                                                                                                            
        if($res=mysql_query_decide($sql1))             //in order to avoid "not a valid result resource"
        {
                if(mysql_num_rows($res)>0)      //if there is no result satisfying the criterea
                {
                        $results=displayresult($res,0,"cellsearch.php","","",1,"","","");
                                                                                                                             
                        $Ret = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
                        $Ret.="<ProfileList>\n";
                        echo $Ret;
                        unset($Ret);
                                                                                                                             
                        $arr_search=array('&','<','>',"'",'"');
                        $arr_repl=array('&amp;','&lt;','&gt;','&apos;','&quot;');
                                                                                                                             
                        for($a=0;$a<count($results);$a++)
                        {
                                unset($shortdesc);
                                unset($longdesc);
                                                                                                                             
                                if($results[$a]['USERNAME'])
                                        $shortdesc=$results[$a]['USERNAME'];
                                                                                                                             
                                $shortdesc=str_replace($arr_search,$arr_repl,$shortdesc);
                                $shortdesc=substr($shortdesc,0,13);
                                                                                                                             
                                if($shortdesc)
                                {
                                        $longdesc=$shortdesc;

					if($results[$a]['AGE'])
						$longdesc.=",".str_replace($arr_search,$arr_repl,$results[$a]['AGE']);
                                                                                                                             
					if($results[$a]['HEIGHT'])
						$longdesc.=",".$results[$a]['HEIGHT'];

                                        if($results[$a]['CASTE'])
                                                $longdesc.=",".str_replace($arr_search,$arr_repl,$results[$a]['CASTE']);
                                                                                                                             
					if($results[$a]['MTONGUE'])
						$longdesc.=",".str_replace($arr_search,$arr_repl,$results[$a]['MTONGUE']);

					if($results[$a]['DEGREE'])
						$longdesc.=",".str_replace($arr_search,$arr_repl,$results[$a]['DEGREE']);

					if($results[$a]['OCCUPATION'])
						$longdesc.=",".str_replace($arr_search,$arr_repl,$results[$a]['OCCUPATION']);

                                        if($results[$a]['RESIDENCE'])
                                                $longdesc.=",".str_replace($arr_search,$arr_repl,$results[$a]['RESIDENCE']);

					if($cellGender=="F" && $results[$a]['INCOME'])
					{
						$longdesc.=",".str_replace($arr_search,$arr_repl,$results[$a]['INCOME']);
					}
					else if($cellGender=="M")
					{
						if($results[$a]['BODY'])
							$longdesc.=",".str_replace($arr_search,$arr_repl,$BODYTYPE[$results[$a]['BODY']]);
						if($results[$a]['COMPLEXION'])
							$longdesc.=",".str_replace($arr_search,$arr_repl,$COMPLEXION[$results[$a]['COMPLEXION']]);
					}
                                }
                                                                                                                             
//                                $longdesc=str_replace($arr_search,$arr_repl,$longdesc);
                                $longdesc=substr($longdesc,0,120);
                                                                                                                             
                                $Ret="\t<Profile>\n";
                                $Ret.="\t\t<Profileid>".$results[$a]['PROFILEID']."</Profileid>\n";
                                $Ret.="\t\t<Username>".$results[$a]['USERNAME']."</Username>\n";
                                                                                                                             
                                if($results[$a]['SHOWPHONE_MOB']=="Y")
                                        $Ret.="\t\t<UserPhone>".$results[$a]['PHONE_MOB']."</UserPhone>\n";
                                                                                                                             
                                if($shortdesc)
                                        $Ret.="\t\t<ShortDesc>".$shortdesc."</ShortDesc>\n";
                                                                                                                             
                                if($longdesc)
                                        $Ret.="\t\t<LongDesc>".$longdesc."</LongDesc>\n";
                                                                                                                             
                                $Ret.="\t</Profile>\n";
                                echo $Ret;
                                unset($Ret);
                        }
                        $Ret="</ProfileList>\n";
                                                                                                                             
                        echo $Ret;
                }
                else
                {
                        echo "No Results were found";
                        $qs=$_SERVER['REQUEST_URI'];
                        noResultLog($qs);
                }
        }
        else
        {
                queryDieLog(mysql_error_js(),$sql1);
        }
}
else if($act=="CHAT" && $MSISDN)
{
	if($username)
	{
		if(strlen($MSISDN)>10)
		{
			$MSISDN=ltrim($MSISDN,"9");
			$MSISDN=ltrim($MSISDN,"1");
		}
		$cell=$MSISDN;

		//Getting profileid,subscription of cell user
		$sql="SELECT PROFILEID,SUBSCRIPTION,USERNAME FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		$row=mysql_fetch_array($res);
		$cellProfileid=$row['PROFILEID'];
		$cellUsername=$row['USERNAME'];

		if(strstr($row['SUBSCRIPTION'],'F'))
			$cellUserIsPaid='Y';

		$sqlName="SELECT * FROM newjs.NAMES WHERE USERNAME='".$username."'";
		$resName=mysql_query_decide($sqlName) or queryDieLog(mysql_error_js(),$sqlName);

		if(mysql_num_rows($resName)>1)
		{
			$sql="SELECT PROFILEID,PHONE_MOB,SUBSCRIPTION FROM newjs.JPROFILE WHERE USERNAME='".$username."'";
			$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
			$row=mysql_fetch_array($res);
			$triedUser=$row['PROFILEID'];
			$triedUserPhone=$row['PHONE_MOB'];

			if(strstr($row['SUBSCRIPTION'],'F'))
				$contactedUserIsPaid='Y';
		}
		else
		{
			$rowName=mysql_fetch_array($resName);

			$sql="SELECT PROFILEID,PHONE_MOB,SUBSCRIPTION FROM newjs.JPROFILE WHERE USERNAME='".$rowName['USERNAME']."'";
                        $res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
                        $row=mysql_fetch_array($res);
                        $triedUser=$row['PROFILEID'];
                        $triedUserPhone=$row['PHONE_MOB'];

			if(strstr($row['SUBSCRIPTION'],'F') || strstr($row['SUBSCRIPTION'],'V'))
				$contactedUserIsPaid='Y';
		}

		$sql="SELECT * FROM newjs.SMS_BLOCK WHERE PROFILEID='".$triedUser."'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		$row=mysql_fetch_array($res);

		if($cellUserIsPaid=="Y" || $contactedUserIsPaid=="Y")
		{
			if($row['BLOCK_STATUS']=="BLOCKALL")
			{
				echo "blocked";
			}
			else if($row['BLOCK_STATUS']=="UNBLOCKALL")
			{
				echo "Mobile Number:".$triedUserPhone."\nMobile Username:".$cellUsername;
				update_sms_block($cellProfileid,$triedUser);
			}
			else if($row['BLOCK_STATUS']=="BLOCKALLEXCEPT")
			{
				$blockedUserArr=explode(",",$row['BLOCKED_USERS']);

				if(in_array($cellProfileid,$blockedUserArr))
				{
					echo "Mobile Number:".$triedUserPhone."\nMobile Username:".$cellUsername;
					update_sms_block($cellProfileid,$triedUser);
				}
				else
				{
					echo "blocked";
				}
			}
			else if($row['BLOCK_STATUS']=="BLOCKONLY")
			{
				$blockedUserArr=explode(",",$row['BLOCKED_USERS']);

				if(in_array($cellProfileid,$blockedUserArr))
				{
					echo "blocked";
				}
				else
				{
					echo "Mobile Number:".$triedUserPhone."\nMobile Username:".$cellUsername;
					update_sms_block($cellProfileid,$triedUser);
				}
			}
			else
			{
				echo "Mobile Number:".$triedUserPhone."\nMobile Username:".$cellUsername;
				update_sms_block($cellProfileid,$triedUser);
			}
		}
		else
		{
			echo "pay";
		}
	}
	else
	{
		echo "error";
	}
}
else if($act=="BLOCKALL" && $MSISDN)
{
        if(strlen($MSISDN)>10)
        {
                $MSISDN=ltrim($MSISDN,"9");
                $MSISDN=ltrim($MSISDN,"1");
        }
        $cell=$MSISDN;

	$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."'";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	$row=mysql_fetch_array($res);
	$cell_profileid=$row['PROFILEID'];

	$sql="REPLACE INTO newjs.SMS_BLOCK (PROFILEID,BLOCK_STATUS,BLOCKED_USERS) VALUES('".$cell_profileid."','BLOCKALL','')";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);

	echo "done";
}
else if($act=="UNBLOCKALL" && $MSISDN)
{
	if(strlen($MSISDN)>10)
        {
                $MSISDN=ltrim($MSISDN,"9");
                $MSISDN=ltrim($MSISDN,"1");
        }
        $cell=$MSISDN;

	$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."'";
        $res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
        $row=mysql_fetch_array($res);
        $cell_profileid=$row['PROFILEID'];

	$sql="REPLACE INTO newjs.SMS_BLOCK (PROFILEID,BLOCK_STATUS,BLOCKED_USERS) VALUES ('".$cell_profileid."','UNBLOCKALL','')";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);

	echo "done";
}
else if($act=="BLOCKALLEXCEPT" && $MSISDN)
{
	if($username)
	{
		if(strlen($MSISDN)>10)
		{
			$MSISDN=ltrim($MSISDN,"9");
			$MSISDN=ltrim($MSISDN,"1");
		}
		$cell=$MSISDN;

		$sql="SELECT PROFILEID,USERNAME FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		$row=mysql_fetch_array($res);
		$cell_profileid=$row['PROFILEID'];
		$cell_username=$row['USERNAME'];

		$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='".$username."'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		$row=mysql_fetch_array($res);
		$block_user=$row['PROFILEID'];

		if($block_user!='')
		{
			$sql="SELECT * FROM newjs.SMS_BLOCK WHERE PROFILEID='".$cell_profileid."'";
			$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
			$row=mysql_fetch_array($res);

			if($row['BLOCK_STATUS']=="BLOCKALLEXCEPT")
			{
				$profiles=$row['BLOCKED_USERS'].",".$block_user;
			}
			else
			{
				$profiles=$block_user;
			}

			$sql="REPLACE INTO newjs.SMS_BLOCK (PROFILEID,BLOCK_STATUS,BLOCKED_USERS) VALUES ('".$cell_profileid."','BLOCKALLEXCEPT','".$profiles."')";
			$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);

			echo "done";
		}
		else if($block_user=="")
		{
			echo "no user";
		}
	}
	else
	{
		die("error");
	}
}
else if($act=="BLOCKONLY" && $MSISDN)
{
	if($username)
	{
		if(strlen($MSISDN)>10)
                {
                        $MSISDN=ltrim($MSISDN,"9");
                        $MSISDN=ltrim($MSISDN,"1");
                }
                $cell=$MSISDN;
                                                                                                                             
                $sql="SELECT PROFILEID,USERNAME FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."'";
                $res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
                $row=mysql_fetch_array($res);
                $cell_profileid=$row['PROFILEID'];
		$cell_username=$row['USERNAME'];

		$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='".$username."'";
		$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
		$row=mysql_fetch_array($res);
		$block_user=$row['PROFILEID'];

		if($block_user!='')
		{
			$sql="SELECT * FROM newjs.SMS_BLOCK WHERE PROFILEID='".$cell_profileid."'";
			$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
			$row=mysql_fetch_array($res);

			if($row['BLOCK_STATUS']=="BLOCKONLY")
			{
				$profiles=$row['BLOCKED_USERS'].",".$block_user;
			}
			else
			{
				$profiles=$block_user;
			}
                                                                                                                             
			$sql="REPLACE INTO newjs.SMS_BLOCK (PROFILEID,BLOCK_STATUS,BLOCKED_USERS) VALUES ('".$cell_profileid."','BLOCKONLY','".$profiles."')";
			$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);

			echo "done";
		}
		else if($block_user=="")
		{
			echo "no user";
		}
	}
	else
	{
		die("error");
	}
}
else if($act=="VIEWBLOCK" && $MSISDN)
{
	/***************************************************************************************************
		DATA DESCRIPTION : The steps given below are for the case when 
				 : user tries to view his blocked users.
	***************************************************************************************************/

	if(strlen($MSISDN)>10)
	{
		$MSISDN=ltrim($MSISDN,"9");
		$MSISDN=ltrim($MSISDN,"1");
	}
	$cell=$MSISDN;
														     
	$sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE PHONE_MOB='".$cell."'";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	$row=mysql_fetch_array($res);
	$cell_profileid=$row['PROFILEID'];		//profileid of the cell phone user

	$sql="SELECT BLOCK_STATUS, BLOCKED_USERS FROM newjs.SMS_BLOCK WHERE PROFILEID='".$cell_profileid."'";
	$res=mysql_query_decide($sql) or queryDieLog(mysql_error_js(),$sql);
	$row=mysql_fetch_array($res);
	$blocked_users_arr=explode(",",$row['BLOCKED_USERS']);

	if(count($blocked_users_arr)>1)
		$blocked_users=implode("','",$blocked_users_arr);	//Profile IDs of users who have been blocked
	else
		$blocked_users=$blocked_users_arr[0];


	//We need to find the username of all the users who have been blocked. 
	//This is accomplished in the steps below.

	$sqlUname="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID IN ('".$blocked_users."')";
	$resUname=mysql_query_decide($sqlUname) or queryDieLog(mysql_error_js(),$sqlUname);
	while($rowUname=mysql_fetch_array($resUname))
	{
		$blockedUnameArr[]=$rowUname['USERNAME'];
	}

	if(count($blockedUnameArr)>1)
		$blockedUname=implode(",",$blockedUnameArr);
	else
		$blockedUname=$blockedUnameArr[0];

	if($row['BLOCK_STATUS']=="BLOCKALL")
	{
		echo "User has blocked all other users.";
	}
	else if($row['BLOCK_STATUS']=="UNBLOCKALL")
	{
		echo "User has un-blocked all other users.";
	}
	else if($row['BLOCK_STATUS']=="BLOCKALLEXCEPT")
	{
		echo "User has blocked all users except- ".$blockedUname;
	}
	else if($row['BLOCK_STATUS']=="BLOCKONLY")
	{
		echo "User has blocked only- ".$blockedUname;
	}
	else
	{
		echo "No block status available";
	}
}
else
{
	echo "error";
}

/*
function noResultLog($qs)
{
	$sql_log="INSERT INTO newjs.SMS_QUERYLOG VALUES('',now(),'".$qs."')";
	$res_log=mysql_query_decide($sql_log) or die("There has been some problem due to which request cannot be processed.");
}

function queryDieLog($sqlerr,$sqlquery)
{
//	$errormsg="echo \"\n".date("Y-m-d G:i:s",time() + 37800)."\t:\t".$sqlerr."\nQuery:\t".$sqlquery."\" >> /usr/local/apache/sites/jeevansathi.com/htdocs/tieups/airtel/logerror.txt";
	$errormsg="echo \"\n".date("Y-m-d G:i:s",time() + 37800)."\t:\t".$sqlerr."\nQuery:\t".$sqlquery."\" >> /var/www/html/tieups/airtel/logerror.txt";

	passthru($errormsg);

	die("The request cannot be processed");
}

function update_sms_block($cellUserProfileid,$triedUserProfileid)
{
	$sql_update="SELECT * FROM SMS_BLOCK WHERE PROFILEID='".$cellUserProfileid."'";
	$res_update=mysql_query_decide($sql_update) or queryDieLog(mysql_error_js(),$sql_update);
	$row_update=mysql_fetch_array($res_update);

	if($row_update['BLOCK_STATUS']=='BLOCKALL')
	{
		$sql_update="REPLACE INTO newjs.SMS_BLOCK(PROFILEID,BLOCK_STATUS,BLOCKED_USERS) VALUES ('".$cellUserProfileid."','BLOCKALLEXCEPT','".$triedUserProfileid."')";
		$res_update=mysql_query_decide($sql_update) or queryDieLog(mysql_error_js(),$sql_update);
	}
	else if($row_update['BLOCK_STATUS']=='BLOCKALLEXCEPT')
	{
		$blockedUsersArr=explode(",",$row_update['BLOCKED_USERS']);

		if(!in_array($triedUserProfileid,$blockedUsersArr))
		{
			$BLOCKED_USERS=$row_update['BLOCKED_USERS'].",".$triedUserProfileid;

			$sql_replace="REPLACE INTO newjs.SMS_BLOCK(PROFILEID,BLOCK_STATUS,BLOCKED_USERS) VALUES ('".$cellUserProfileid."','BLOCKALLEXCEPT','".$BLOCKED_USERS."')";
			$res_replace=mysql_query_decide($sql_replace) or queryDieLog(mysql_error_js(),$sql_replace);
		}
	}
	else if($row_update['BLOCK_STATUS']=='BLOCKONLY')
	{
		$blockedUsersArr=explode(",",$row_update['BLOCKED_USERS']);

		if(in_array($triedUserProfileid,$blockedUsersArr))
		{
			$newBlockedUsers="";

			for($tmpcnt=0;$tmpcnt<count($blockedUsersArr);$tmpcnt++)
			{
				if($blockedUsersArr[$tmpcnt]!=$triedUserProfileid)
					$newBlockedUsers=$newBlockedUsers.",".$blockedUsersArr[$tmpcnt];
			}

			$sql_replace="REPLACE INTO newjs.SMS_BLOCK(PROFILEID,BLOCK_STATUS,BLOCKED_USERS) VALUES ('".$cellUserProfileid."','BLOCKONLY','".$newBlockedUsers."')";
			mysql_query_decide($sql_replace) or  queryDieLog(mysql_error_js(),$sql_replace);
		}
	}
}
*/
?>
