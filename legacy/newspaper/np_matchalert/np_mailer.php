<?php
/*********************************************************************************************
* FILE NAME	: np_mailer.php
* DESCRIPTION	: Populates alerts.NP_MAILER after retrieving matches for the users
* CREATION DATE	: 19 May, 2005
* CREATEDED BY	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

function mainact()
{
        $trunc_sending="TRUNCATE TABLE alerts.NP_MAILER";
        mysql_query($trunc_sending) or logerror1("Error in truncating alerts.NP_MAILER. ".mysql_error(),$trunc_sending,"","");

	$sql_isall="SELECT VALUE,ISALL FROM newjs.CASTE ";
        $result_caste=mysql_query($sql_isall) or logerror1("Error in select from newjs.CASTE. ".mysql_error(),$sql_isall,"","");
        while($casterow=mysql_fetch_array($result_caste))
        {
                if ($casterow['ISALL']=='Y')
                        $current=$casterow['VALUE'];
                $ISALL[$current][]=$casterow['VALUE'];
        }
                                                                                                                            
        $sql_isgrp="SELECT VALUE,ISGROUP,GROUPID FROM newjs.CASTE ";
        $result_isgrp=mysql_query($sql_isgrp) or logerror1("Error in select from newjs.CASTE. ".mysql_error(),$sql_isgrp,"","");
        $current=0;
        while($row_isgrp=mysql_fetch_array($result_isgrp))
        {
                if ($row_isgrp['ISGROUP']=='Y')
                {
                        $current=$row_isgrp['VALUE'];
                        $curgroupid=$row_isgrp['GROUPID'];
                }
                if($row_isgrp['GROUPID']==$curgroupid)
                        $ISGROUP[$current][]=$row_isgrp['VALUE'];
        }

	$sql_unsub="SELECT ID FROM jsadmin.AFFILIATE_MAIN WHERE UNSUBSCRIBE='Y'";
//	$sql_unsub = "SELECT a.ID FROM jsadmin.UNSUBSCRIBE u LEFT JOIN jsadmin.AFFILIATE_MAIN a ON a.EMAIL = u.EMAIL WHERE a.EMAIL IS NOT NULL AND u.SOURCE='N'";
	$res_sql_unsub=mysql_query($sql_unsub) or logerror1("Error in selecting ProfileID from affiliate_main. ".mysql_error(),$sql_unsub,"","");
	while($row_unsub=mysql_fetch_array($res_sql_unsub))
	{
		$unsub[]=$row_unsub['ID'];
	}

	if(count($unsub)>=1)
        {
                $unsub_arr=implode("','",$unsub);
        }
        else
        {
                $unsub_arr="";
        }


	$sql_user="SELECT jsadmin.AFFILIATE_DATA.PROFILEID,jsadmin.AFFILIATE_DATA.USERNAME,jsadmin.AFFILIATE_DATA.AGE,jsadmin.AFFILIATE_DATA.GENDER,jsadmin.AFFILIATE_DATA.CASTE,jsadmin.AFFILIATE_DATA.MSTATUS,jsadmin.AFFILIATE_DATA.EMAIL FROM jsadmin.AFFILIATE_DATA LEFT JOIN newjs.JPROFILE ON (jsadmin.AFFILIATE_DATA.EMAIL = newjs.JPROFILE.EMAIL) WHERE newjs.JPROFILE.EMAIL IS NULL";
	if($unsub_arr!="")
	{
		$sql_user.=" AND jsadmin.AFFILIATE_DATA.PROFILEID NOT IN ('$unsub_arr')";
	}
//	$res_user=mysql_query($sql_user) or logerror1("Error in selecting Profileid,name,age,etc from jsadmin.AFFILIATE_DATA".mysql_error(),$sql_notmem,"","");
	
	if($res_user=mysql_query($sql_user))
	{
		while($row_user=mysql_fetch_array($res_user))
		{
			$id=$row_user['PROFILEID'];
			$name=$row_user['USERNAME'];
			$age=$row_user['AGE'];
			$gender=$row_user['GENDER'];
			$caste=$row_user['CASTE'];
			$mstatus=$row_user['MSTATUS'];
			$email=$row_user['EMAIL'];
			if($gender=='M')
			{
				$tar_gender='F';
				if($age=="")
				{
					$lage="";
					$hage="";
				}
				else
				{
					$lage=$age-5;
					$hage=$age;
				}
			}
			elseif($gender=='F')
			{
				$tar_gender='M';
				if($age=="")
				{
					$lage="";
					$hage="";
				}
				else
				{
					$lage=$age;
					$hage=$age+5;
				}
			}

			$sql_d="SELECT USER FROM alerts.NP_LOG WHERE RECEIVER='$id'";
//	                $result_d=mysql_query($sql_d) or logerror1("Error in selecting user from NP_LOG. ".mysql_error(),$sql_d,"","");
			if($result_d=mysql_query($sql_d))
			{
	        	        while($myrow_d=mysql_fetch_array($result_d))
		                {
        		        	$arr[]=$myrow_d['USER'];
	               		}
	        	        mysql_free_result($result_d);
			}
			else
			{
				logerror1("Error in selecting user from NP_LOG. ".mysql_error(),$sql_d,"","");
			}
                
			if(count($arr) >= 1)
        	        	$queryadd=implode("','",$arr);
	                else
                		$queryadd="";

			$sqlsearch=blindsearch($tar_gender,$lage,$hage,$mstatus,$caste,$ISALL,$ISGROUP);
			if($queryadd!="")
                	$sqlsearch.= " AND PROFILEID NOT IN ('$queryadd')";
                	$sqlsearch.= " ORDER BY MOD_DT DESC LIMIT 10 ";
//			$ressearch=mysql_query($sqlsearch) or logerror1("Error in blindsearch. ".mysql_error(),$sqlsearch,"","");
			if($ressearch=mysql_query($sqlsearch))
			{
				$count_rows=mysql_num_rows($ressearch);
				$n=0;
				while($foundmatch=mysql_fetch_array($ressearch))
				{
					$n++;
					$match[]=$foundmatch['PROFILEID'];
					$profilefound=$foundmatch['PROFILEID'];
					$sql_c1="INSERT delayed INTO alerts.NP_TEMPLOG (RECEIVER,USER,DATE) VALUES ('$id','$profilefound',CURDATE())";
	              		  	$result_c1=mysql_query($sql_c1) or logerror1("Error in inserting data into NP_TEMPLOG. ".mysql_error(),$sql_c1,"","");
				}

				$sql_final="INSERT INTO alerts.NP_MAILER VALUES($id,'$match[0]','$match[1]','$match[2]','$match[3]','$match[4]','$match[5]','$match[6]','$match[7]','$match[8]','$match[9]','N')";
				mysql_query($sql_final) or logerror1("Error in insertion in alerts.NPMAILER. ".mysql_error(), $sql_final,"","");

				if($n<=2)
        		        {
	        	        	$sql_xx="DELETE FROM alerts.NP_LOG where RECEIVER=$id";
                			mysql_query($sql_xx) or logerror1("Error in deleting alerts.NP_LOG. ".mysql_error(),$sql_xx,"","");
	                	}

				unset($match);	
				unset($arr);
			}
			else
			{
				logerror1("Error in blindsearch. ".mysql_error(),$sqlsearch,"","");
			}
		}
	}
	else
	{
		logerror1("Error in selecting Profileid,name,age,etc from jsadmin.AFFILIATE_DATA".mysql_error(),$sql_notmem,"","");
	}

	$sql_111="INSERT INTO alerts.NP_LOG SELECT * FROM alerts.NP_TEMPLOG";
        mysql_query($sql_111) or logerror1("Error in inserting data into alerts.NP_LOG. ".mysql_error(),$sql_111,"","");

}

function blindsearch($gend,$lage,$hage,$mstatus,$caste,$ISALL,$ISGROUP)
{
	$query="SELECT PROFILEID FROM  "; 
	if($gend=='M')
	{
		$query.=" newjs.SEARCH_MALE WHERE ";
	}
	elseif($gend=='F')
	{
		$query.=" newjs.SEARCH_FEMALE WHERE  ";
	}

	if($lage!="" && $hage!="")
	{
		$query.=" AGE BETWEEN '$lage' AND '$hage'  ";
	}
	
	if($mstatus=='N')
	{
		$query.=" AND MSTATUS='$mstatus' ";
	}
	else
	{
		$query.=" AND MSTATUS IN ('N','W','D','S','O')  " ;
	}

	if($caste!="")
	{
		$query.=" AND ";
		if(count($ISGROUP[$caste])>1)
        	{
                	$casteq=implode("','",$ISGROUP[$caste]);
                	$query.=" CASTE IN ('$casteq') ";
        	}
        	elseif(count($ISALL[$caste])>1)
        	{
                	$casteq=implode("','",$ISALL[$caste]);
                	$query.=" CASTE IN ('$casteq') ";
        	}
        	else
        	{
                	$query.=" CASTE='$caste' ";
        	}
	}
        return $query;
}

?>
