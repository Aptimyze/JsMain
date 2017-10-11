<?php

//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

include_once("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
include_once(JsConstants::$alertDocRoot."/new_matchalert/populate_new_inc_V2.php");
include_once(JsConstants::$alertDocRoot."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
include_once(JsConstants::$alertDocRoot."/classes/globalVariables.Class.php");
include_once(JsConstants::$alertDocRoot."/commonFiles/incomeCommonFunctions.inc");
$SITE_URL=JsConstants::$siteUrl;

$mysqlObj=new Mysql;
$db=$mysqlObj->connect("alerts");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
mysql_select_db("matchalerts",$db) or die(mysql_error());
pouplate_partnerprofile('F');
function pouplate_partnerprofile($gender)
{
	global $_SERVER;
	global $active_db,$previous_db,$db_conns;
	$matchalertServer = 1;


	$jpartnerObj=new Jpartner();
	$mysqlObj=new Mysql;

	if(!$noOfActiveServers)
	{
		global $noOfActiveServers,$slave_activeServers;
	}

        for($i=0;$i<$noOfActiveServers;$i++)
        {
                $myDbName=$slave_activeServers[$i];
                //$myDbName=$activeServers[$i];
                $myDbArray[$myDbName]=$mysqlObj->connect("$myDbName");
        }
	global $db;
	$active_db=$db;
	$previous_db=$db;

	//$sql="UPDATE matchalerts.SEARCH_FEMALE SET INCOME_SORTBY=IF(INCOME>=16 && INCOME<=18,INCOME-9,IF(INCOME=15,0,IF(INCOME>=12 && INCOME<=14,9,IF(INCOME=11,8,IF(INCOME>=8 && INCOME<=10,INCOME-4,INCOME)))))";
	//for($i=0;$i<2;$i++)
	{
		if($gender=='F')
			$table="SEARCH_FEMALE";
		else
			$table="SEARCH_MALE";


                $sql_1="SELECT PROFILEID,CASTE,INCOME FROM matchalerts.$table";
		$result_1=mysql_query($sql_1,$db) or logerror1("Error in matchalert_partnerprofile.php",$sql_1);
		while($myrow_1=mysql_fetch_array($result_1))
		{
			$profileid=$myrow_1["PROFILEID"];
	                $rec_caste=$myrow_1["CASTE"];
			$incomeSortBy = getTrendsSortBy($myrow_1["INCOME"]);

			unset($update_parent_rel);

			/*
			if($rec_caste)
			{
        	                $update_parent_rel=8;

				$sql_religion="SELECT PARENT FROM CASTE WHERE VALUE='$rec_caste'";
				$res_religion=mysql_query($sql_religion,$db) or logerror1("Error in matchalert_partnerprofile.php",$sql_religion);
				if($myrow_religion=mysql_fetch_array($res_religion))
				{
					$parent_rel=$myrow_religion["PARENT"];
					if(in_array($parent_rel,array(7,1,9)))
						$update_parent_rel=1;
					else
						$update_parent_rel=$parent_rel;
				}
			}
			*/
			//echo $myDbName;die;
			//$myDbName=getProfileDatabaseConnectionName($profileid,'slave',$mysqlObj);

			//temp
				if($profileid%3==0)
					$myDbName="11Slave";
				elseif($profileid%3==1)
					$myDbName="211Slave";
				else
					$myDbName="303Slave";
			//temp

			//$mysqlObj->ping($myDbArray[$myDbName]);
			@mysql_ping($myDbArray[$myDbName]);
			$myDb=$myDbArray[$myDbName];

                        $jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);//************myDb*********
                        if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj))
			{
				$update_parent_rel=$jpartnerObj->getPARTNER_RELIGION();
				$child=$jpartnerObj->getCHILDREN();
				$lage=$jpartnerObj->getLAGE();
				$hage=$jpartnerObj->getHAGE();
				$lheight=$jpartnerObj->getLHEIGHT();
				$hheight=$jpartnerObj->getHHEIGHT();
				$handicapped=$jpartnerObj->getHANDICAPPED();
                                if(trim($handicapped)=='')
                                        $handicapped='';


                                if(strstr($handicapped,'N'))
                                {
                                        if(strstr($handicapped,'1') || strstr($handicapped,'2') || strstr($handicapped,'3') || strstr($handicapped,'4'))
                                                $handicapped='';
                                        else
                                                $handicapped='N';
                                }
                                elseif(strstr($handicapped,'1') || strstr($handicapped,'2') || strstr($handicapped,'3') || strstr($handicapped,'4'))
                                        $handicapped='Y';


				$btype_str=$jpartnerObj->getPARTNER_BTYPE();

				$castetempstr=$jpartnerObj->getPARTNER_CASTE();

				unset($caste);
				unset($caste_str);
				unset($seCaste);
				if($castetempstr)
					$caste=explode("','",$castetempstr);
				if(is_array($caste))
				{
					$casteStr=implode("','", $caste);
					$casteStr = trim($casteStr,"'',"); //an error case
					$seCaste=get_all_caste($casteStr,'',1,$db);
					if(is_array($seCaste))
						$caste_str="'".implode("','",$seCaste)."'";
				}
				unset($country_usa);
				unset($city_usa);
				unset($city_str);
				unset($country_india);
				unset($city_india);
				unset($city_strarr);
				$city_str=$jpartnerObj->getPARTNER_CITYRES();
                                $City_Res=display_format1($city_str);
                                if(is_array($City_Res))
                                {
                                        for($i=0;$i<count($City_Res);$i++)
                                        {
                                                if(is_numeric($City_Res[$i]))
                                                {
                                                        $country_usa=1;
                                                        $city_usa[]=$City_Res[$i];
                                                }
                                                elseif(strlen($City_Res[$i])==2)
                                                {
                                                        $country_india=1;
                                                        $citysql="select SQL_CACHE VALUE from newjs.CITY_NEW where VALUE like '$City_Res[$i]%'";
                                                        $cityresult=mysql_query($citysql,$db);
                                                        while($cityrow=mysql_fetch_array($cityresult))
                                                        {
                                                                $city_india[]=$cityrow["VALUE"];
                                                        }
                                                        mysql_free_result($cityresult);
                                                }
                                                else
                                                {
                                                        $country_india=1;
                                                        $city_india[]=$City_Res[$i];
                                                }
                                        }
                                        if($country_india && is_array($city_india))
                                                $city_strarr[]=implode($city_india,"','");
                                        if($country_usa)
                                                $city_strarr[]=implode($city_usa,"','");
                                        if(is_array($city_strarr))
                                                $city_str="'".implode($city_strarr,"','")."'";

                                }

				$comp_str=$jpartnerObj->getPARTNER_COMP();
				$countryres_str=$jpartnerObj->getPARTNER_COUNTRYRES();
				$diet_str=$jpartnerObj->getPARTNER_DIET();
				$drink_str=$jpartnerObj->getPARTNER_DRINK();
				$educationnew_str=$jpartnerObj->getPARTNER_ELEVEL_NEW();
				$income_str=$jpartnerObj->getPARTNER_INCOME();
				$manglik_str=$jpartnerObj->getPARTNER_MANGLIK();
				$mstatus_str=$jpartnerObj->getPARTNER_MSTATUS();
				$mtongue_str=$jpartnerObj->getPARTNER_MTONGUE();
				$occ_str=$jpartnerObj->getPARTNER_OCC();
				$smoke_str=$jpartnerObj->getPARTNER_SMOKE();
				$relation_str=$jpartnerObj->getPARTNER_RELATION();

				$updatestr="UPDATE matchalerts.$table SET INCOME_SORTBY='$incomeSortBy',PARTNER_RELIGION=\"$update_parent_rel\",PARTNER_CHILD=\"$child\",PARTNER_LAGE=\"$lage\",PARTNER_HAGE=\"$hage\",PARTNER_LHEIGHT=\"$lheight\",PARTNER_HHEIGHT=\"$hheight\",PARTNER_HANDICAPPED=\"$handicapped\",PARTNER_BTYPE=\"$btype_str\",PARTNER_CASTE=\"$caste_str\",PARTNER_CITYRES=\"$city_str\",PARTNER_COMP=\"$comp_str\",PARTNER_COUNTRYRES=\"$countryres_str\",PARTNER_DIET=\"$diet_str\",PARTNER_DRINK=\"$drink_str\",PARTNER_ELEVEL_NEW=\"$educationnew_str\",PARTNER_INCOME=\"$income_str\",PARTNER_MANGLIK=\"$manglik_str\",PARTNER_MSTATUS=\"$mstatus_str\",PARTNER_MTONGUE=\"$mtongue_str\",PARTNER_OCC=\"$occ_str\",PARTNER_SMOKE=\"$smoke_str\",PARTNER_RELATION=\"$relation_str\" WHERE PROFILEID=$profileid";
				mysql_query($updatestr,$db) or logerror1("Error in matchalert_partnerprofile.php",$updatestr);
			}
                        else
                        {
                                $updatestr="UPDATE matchalerts.$table SET INCOME_SORTBY='$incomeSortBy' WHERE PROFILEID=$profileid";
                                mysql_query($updatestr,$db) or logerror1("Error in matchalert_partnerprofile.php",$updatestr);
                        }

		}
                $sqlE="UPDATE matchalerts.$table SET PARTNER_HANDICAPPED='' WHERE PARTNER_HANDICAPPED=\"'\"";
                mysql_query($updatestr,$db) or logerror1("Error in matchalert_partnerprofile.php",$updatestr);

	}
}

function display_format1($str)
{
        if($str)
        {
                $str=trim($str,"'");

                $arr=explode("','",$str);
                return $arr;
        }

}

?>
