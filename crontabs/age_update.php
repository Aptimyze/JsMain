<?php 
	include_once(JsConstants::$docRoot."/../crontabs/connect.inc");
	include_once(JsConstants::$docRoot."/classes/Memcache.class.php");
	include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");
	include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
	include_once(JsConstants::$docRoot."/classes/Jpartner.class.php");
	include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");

	$myDb_Slave = connect_slave();
	$myDb_Master = connect_db();

	$mysqlObj=new Mysql;
	$jpartnerObj=new Jpartner;
	
	function updateAGE()
	{
		global $myDb_Master,$myDb_Slave,$jpartnerObj,$mysqlObj;
		
		mysql_query("set session wait_timeout=10000",$myDb_Master);
		mysql_query("set session wait_timeout=10000",$myDb_Slave);
		$updateDateFields = ProfileEnums::$updateSortDtForFields;
		$sql=   "SELECT PROFILEID, DTOFBIRTH, AGE,LAST_LOGIN_DT, FLOOR( DATEDIFF(	NOW( ) , DTOFBIRTH ) / 365.25 ) AS ACTUAL_AGE FROM JPROFILE WHERE FLOOR( DATEDIFF( 	NOW( ) , DTOFBIRTH ) / 365.25) <> AGE";
		$arrResultSet = mysql_query($sql,$myDb_Slave);
		$objUpdate = JProfileUpdateLib::getInstance();
                $searchObj = new NEWJS_SEARCH_SORT_DT();
		while($row = mysql_fetch_assoc($arrResultSet))
		{
			//echo "ACTUAL_AGE : ".$row['ACTUAL_AGE']." : AGE : ".$row['AGE'] ;
			$result = $objUpdate->editJPROFILE(array('AGE'=>$row['ACTUAL_AGE']),$row['PROFILEID'],'PROFILEID');
                        if($row['LAST_LOGIN_DT'] && $row['LAST_LOGIN_DT'] != "0000-00-00 00:00:00" && $row['LAST_LOGIN_DT'] != "0000-00-00"){
                                $finalTime = strtotime($row['LAST_LOGIN_DT'])+($updateDateFields["AGE"]*60*60);
                                $sortDate = date("Y-m-d H:i:s",$finalTime);
                                $searchObj->updateSortDate($row['PROFILEID'],$sortDate);
                        }
//			$sql="update JPROFILE set AGE=".$row['ACTUAL_AGE'] ." where PROFILEID=" . $row['PROFILEID'];
			//echo "\n $sql \n";
//			mysql_query($sql,$myDb_Master) or logError($sql);
			if (false === $result) {
				$sql="update JPROFILE set AGE=".$row['ACTUAL_AGE'] ." where PROFILEID=" . $row['PROFILEID'];
				logError($sql);
			}
			update_JPARTNER(array('PROFILEID'=>$row[PROFILEID],'AGE'=>$row['ACTUAL_AGE']));
		}
		
	}
	
	
	//Created By lavesh to update JPARTNER table for female profiles so that they do not have HAGE and LAGE below there age
	function update_JPARTNER($row)
	{
		global $myDb_Master,$jpartnerObj,$mysqlObj;
		
        if($row)
		{                                                                                                            
			$pid=$row['PROFILEID'];
			unset($myDb);
			unset($myDbName);
			$jpartnerObj->setPROFILEID($pid);
			$myDbName=getProfileDatabaseConnectionName($pid,'',$mysqlObj);
			$myDb=$mysqlObj->connect("$myDbName"); 
			$jpartnerObj->setPartnerDetails($pid,$myDb,$mysqlObj,"LAGE,HAGE,GENDER,DPP");
			if($jpartnerObj->isPartnerProfileExist($myDb,$mysqlObj))
			{
				$lage=$jpartnerObj->getLAGE();
				$hage=$jpartnerObj->getHAGE();
				$age=$row["AGE"];
				$gender=$jpartnerObj->getGENDER();
				$dpp=$jpartnerObj->getDPP();
			
				if($dpp!='O')
				//Profile has not used advance partner profile to specify its desired age range for partner.
				{
					$diff=$hage-$lage;

					if($gender=='M')
					//Logic to handle case when profile female age is less than 21(as range is not 5 in this case)
					{
						if($age>21)
							$diff1=5;
						elseif($age==19)
							$diff1=2;
						elseif($age==20)
							$diff1=3;
						else
							$diff1=4;

					}
					else
					//Logic to handle case when profile male age is less than25 (as range is not 7 in this case)
					{
						if($age>25)
							$diff1=7;
						else
							$diff1=($age-$lage-1);
					}

					if($diff!=$diff1)		
						$dpp='O';//Profile has specified its desired age range for partner from some form.	
				}
				
				if($gender=='F')
				{
					if($dpp!='O')
					//Profile has never specified its desired age range for partner.
					{
						$flag=1;

						if($age<26)//when age is less than 26 Lower age will be 18 and so not be incremented till range b/w lower age and upper age is 7.
							$lage=$lage;
						else
							$lage=$lage+1;

						$hage=$hage+1;
					}
				}
				else
				{

					if($dpp!='O')
					//Profile has never pecified its desired age range for partner.
					{
						$flag=1;

						if($age<22)//when age is less than 21 Lower age will be 18 and so not be incremented till range b/w lower age and upper age is 5.
							$lage=$lage;
						else
							$lage=$lage+1;

						$hage=$hage+1;
					}
					else
					//This Case is for when female profile specify its partner profile LAGE(or even HAGE) to be less than its age.
					{
						if($age>$lage)
						{
							$flag=1;
							$lage=$age;
							$hage=$hage+1;
							if($age>$hage)
								$hage=$age;
						}
					}
				}
				if($flag)
				{
					//echo "LAGE='$lage',HAGE='$hage' \n";
					$jpartnerObj->updatePartnerDetails($myDb,$mysqlObj,"LAGE='$lage',HAGE='$hage'");
					unset($flag);
				}
			}

		}
	}

	function EndScript($st_Time='')
	{
			$end_time = microtime(TRUE);
			$var = memory_get_usage(true);

			 if ($var < 1024)
					$mem =  $var." bytes";
			 elseif ($var < 1048576)
					$mem =  round($var/1024,2)." kilobytes";
			 else
					$mem = round($var/1048576,2)." megabytes";


			echo $mem ."\n";
			echo $end_time - $st_Time;

	}

	$st_Time = microtime(TRUE);
	updateAGE();
	EndScript($st_Time);
?>

