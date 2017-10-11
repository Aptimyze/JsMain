<?php
ini_set(max_execution_time,0);
ini_set(memory_limit,-1);
ini_set(mysql.connect_timeout,-1);
ini_set(log_errors_max_len,0);
include_once("connect.inc");
include_once("../profile/pg/functions.php");
$path_class=$_SERVER["DOCUMENT_ROOT"];
include_once("$path_class/classes/globalVariables.Class.php");
include_once("$path_class/classes/Mysql.class.php");
include_once("$path_class/classes/Memcache.class.php");

$mysqlObj=new Mysql;
$db=connect_misdb();
$db2=connect_master();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);
$data=authenticated($checksum);

if(isset($data)|| $JSIndicator)
{
	$searchType='';
	$searchFlag=0;
	$searchMonth='';
	$searchYear='';
	$monthDays=0;

$searchKeyArray=array('L'=>'View similar from home page',
'V'=>'View Similar Profile',
'I'=>'My Relevant Matches',
'A'=>'Advanced',
'E'=>'eClassified',
'G'=>'PG',
'H'=>'HomePage',
'I'=>'ISearch',
'J'=>'Clusters',
'K'=>'Keyword',
'M'=>'Mailer',
'N'=>'NRI',
'O'=>'Online',
'P'=>'Photo',
'Q'=>'Quick',
'R'=>'Cosmo',
'T'=>'Software',
'X'=>'Next Frm HmPg',
'S'=>'Similar Cont on Home Pg(recommend profiles)',
'Z'=>'Community',
'C'=>'Confirmation page',
'WC'=>'WAP Confirmation page',
'B'=>'Match Alert',
'U'=>'Who viewed',
'' => "Unknown",
'2'=>'Members looking for Me',
"4"=>"Search by Profileid",
"5"=>"Visitors page",
"7"=>"Favorites page",
"8"=>"Ignored members page",
'W' => "Featured Profile",
'11'=>"Myjs-Visitor alert section",
'A11'=>"App Myjs-Visitor alert section",
'12'=>"Myjs-horoscope uploaded section",
'13'=>"Myjs-photo uploaded section",
'14'=>"Myjs-Members online",
'15'=>"Myjs-match alert section",
'A15'=>"App Myjs-match alert section",
'10'=>"profile page with link like matrimonial-1679162Z4.htm",
'16'=>"Save search",
'W16'=>'WapSaveSearch',
'1'=>'Members I m looking for',
"20"=>'CC-Photo requests received',
"21"=>'CC-Photo requests sent',
"22"=>'CC-Horoscope requests received',
"23"=>'CC-Horoscope requests sent',
"24"=>'CC-Chat requests received/sent',
"25"=>'CC-Match alerts',
"26"=>'CC-Viewed Contacts',
"27"=>'CC-People who viewed my contacts',
"28"=>'CC-Intro calls',
"29"=>'WAP-Search',
"30"=>'View Similar from detailed profile page',
"31"=>'Kundli Alert',
"32"=>'CC-Kundli Alert',
"40" => 'Social Album',
 '33' => 'Desktop Offer Page EOI',
 '34' => 'Mobile Offer Page EOI',
'M11'=>'Mailer Photo Request',
'M4'=>'Welcome Mailer',
'M8'=>'Expiry Mailer',
'M6'=>'Reminder 1 Mailer',
'M7'=>'Reminder 2 Mailer',
'M9'=>'FTO Education Mailer',
'M2'=>'Contact Engine Mailer',
'M10'=>'Photo uploaded Mailer',
'M15'=>'Visitor Alert Mailer',
'M3'=>'Incomplete Mailers',
'M12'=>'Phone Verification Mailer',
'M5'=>'Instant EOI/Send Reminder Mailer',
'M13'=>'Write Messase Mailer',
'Ba'=>'Match Alert NT-NT Logic1',
'B1'=>'Match Alert NT-NT Logic1 Community Model',
'Bb'=>'Match Alert NT-NT Logic2',
'B2'=>'Match Alert NT-NT Logic2 Community Model',
'Bc'=>'Match Alert NT-NT Logic3',
'B3'=>'Match Alert NT-NT Logic3 Community Model',
'Bd'=>'Match Alert NT-NT Logic4',
'B4'=>'Match Alert NT-NT Logic4 Community Model',
'Be'=>'Match Alert NT-NT Logic5',
'Bf'=>'Match Alert NT-NT Logic6',
'Bg'=>'Match Alert NT-NT Logic7',
'Bh'=>'Match Alert NT-NT Logic8',
'Bi'=>'Match Alert NT-T Logic1',
'B5'=>'Match Alert NT-T Logic1 Community Model',
'Bj'=>'Match Alert NT-T Logic2',
'B6'=>'Match Alert NT-T Logic2 Community Model',
'Bk'=>'Match Alert NT-T Logic3',
'B7'=>'Match Alert NT-T Logic3 Community Model',
'Bl'=>'Match Alert NT-T Logic4',
'B8'=>'Match Alert NT-T Logic4 Community Model',
'Bm'=>'Match Alert NT-T Logic5',
'Bn'=>'Match Alert NT-T Logic6',
'Bo'=>'Match Alert NT-T Logic7',
'Bp'=>'Match Alert NT-T Logic8',
'Bq'=>'Match Alert T-NT Logic1',
'Br'=>'Match Alert T-NT Logic2',
'Bs'=>'Match Alert T-NT Logic3',
'Bt'=>'Match Alert T-T Logic1',
'Bu'=>'Match Alert T-T Logic2',
'Bv'=>'Match Alert T-T Logic3',
'WS'=>'WAP-Shortlist',
'WM'=>'WAP-Match Alert',
'IM'=>'iOSMatchAlertsCC',
'WV'=>'WAP-Visitor alerts',
'WO'=>'WAP - Others',
'W1'=>'WAP DPP Matches',
'I1'=>'iOSDpp',
'WQ'=>'WAP SEARCH',
'IQ'=>'iOS SEARCH BAND',
'WR'=> 'WAP-Members Looking for Me',
'IR'=> 'iOS-Members Looking for Me',
'AR'=> 'JSAA -Members Looking for Me',
'AV'=>'App Visitor Alert',
'AS'=>'App Shortlist',
'AM'=>'App Match Alert',
'AQ'=>'App Quick Search',
'A1'=>'App Dpp',
'AJ'=>'App Cluster',
'F'=>'New Matches Email',
'F1'=>'New Matches Email NT-NEW Logic1',
'F2'=>'New Matches Email NT-NEW Logic2',
'F3'=>'New Matches Email NT-NEW Logic3',
'F4'=>'New Matches Email T-NEW Logic1',
'F5'=>'New Matches Email T-NEW Logic2',
'F6'=>'New Matches Email T-NEW Logic3',
'T'=>'Two Way Match',
'WT'=>'Wap Two Way Match',
'AT'=>'JSAA Two Way Match',
'IT'=>'iOS Two Way Match',
'AU'=>"JUST JOINED MATCHES - APP",
'DU'=>"Just Joined Matches Desktop Site",
'WU'=>"Just Joined Matches Mobile Site",
'IU'=>'iOS Just Joined Matches',
'WMV'=>'JSMS My JS Visitor Alert Section',
'WMM'=>'JSMS My JS Match Alert Section',
'PCV'=>'JSMS Phonebook',
'CVS'=>'JSMS People Who Viewed My Contacts',
'ACVS'=>'Android app People Who Viewed My Contacts',
'CVS'=>'Android app PhoneBook',
'I17'=>'iOS Search by Profile Id',
'A17'=>'Android Search by Profile Id',
'IJ'=>'Ios Clusters',
'IV'=>'Ios Visitors',
'IS'=>'Ios Shortlisted Members',
'37'=>'Ios Photo Requests',
'PCI'=>'Ios PhoneBook',
'CVI'=>'Ios Contact Viewers',
'IMV'=>'Ios MyJs Visitors',
'IMM'=>'Ios MyJs Match Alerts',
'JJPC'=>'JSPC-Myjs Just Joined',
'DPMP'=>'JSPC-Myjs Desired Partner Matches',
'MSP'=>"JSPC-Myjs Shortlist",
'MPP'=>'JSPC-Myjs Photo request',
'M26'=>"JSPC-Contact I Viewed",
'M27'=>"Contact who Viewed",
'JJM' =>'JUST_JOINED_MYJS_JSMS',
'ACV'=>'Android app People Contacts I viewed',
'VM'=>"JSPC Verified DPP Matches",
'ACO'=>"View Similar Android",
'ICO'=>"View Similar IOS",
'CDS'=>'Contact Details SMS from JSPC',
'A16'=>'Android App Save Search',
'I16'=>'IOS Save Search',
'BN1'=>'T-Trend Based Match Alerts',
'BN2'=>'T-DPP Based Match Alerts',
'BN3'=>'NT-DPP Based Match Alerts',
'AVM'=>'Android Verified DPP Matches',
'IVM'=>'Ios Verified DPP Matches',
'MVM'=>'Mobile Verified DPP Matches',
'VMPC'=>'JSPC Myjs Verified DPP Matches',
'3'=>"Automated Contacts",
'VCD'=>'Contact View Attempts',
'VCDA'=>'Contact View Attempts Android',
'VCDI'=>'Contact View Attempts Ios',
'VCDM'=>'Contact View Attempts JSMS',
'PURM'=>'Photo Uploaded following request mailer',
'M42'=>'Shortlisted mailer',
'SSM'=>'Saved Search mailer',
"KA"=>'JSAA Kundli Alert',
"KI"=>'JSIOS Kundli Alert',
"KM"=>'JSMS Kundli Alert',
'PCN'=>'PC Chat New',
'KAM'=>'kundli Alert Mailer',
'ES2M'=>'Exclusive Servicing Phase II Mailer',
'LSR'=>'Last Search Results',
 'LSPC'=>'JSPC-Myjs Last Search',
 'DPMD'=>'JSPC-Myjs Last Search Desired Partner Matches',
"AMD"=>"Android match of the day",
"IMD"=>"Ios match of the day",
'MWV'=>'MATCHING_VISITORS_JSMS',
'MIV'=>'MATCHING_VISITORS_IOS',
'MV5'=>'MATCHING_VISITORS_JSPC',
'MAV'=>'MATCHING_VISITORS_ANDROID',
'BN4'=>'Community_Model_Matchalerts',
'BN5'=>'Dpp_Relaxation_Matchalerts',
'BN7'=>'LastSearch_Matchalerts',
'BNST'=>'Strict Dpp (Trends) Match Alerts',
'BNSN'=>'Strict Dpp (Non Trends) Match Alerts',
'BNRT'=>'Relaxed Dpp (Trends) Match Alerts',
'BNRN'=>'Relaxed Dpp (Non Trends) Match Alerts',
'MAA'=>'EOI-Android MatchAlert Notification',
'JJA'=>'EOI-Android Just Join Notification',
'ICP'=>'VIEW_SIMILAR_IOS_ON_PD',
'ACN'=>'Android chat new',
 'MOD' => 'Match of the day JSPC',
 'AMOD' => 'Android Match Of Day',
 'IMOD' => 'IOS Match Of Day',
 'WMOD' => 'Match of day JSMS',
 'PMM'=>'PAID_MEMBERS_MAILER',
 'PM'=>'PAID_MEMBERS',
 'APM'=>'Add Photo Mailer',
 'SPMA' => 'EOI_SIMILAR_PROFILES_MAIL_ACCEPTED',
 'SPMO' => 'EOI_SIMILAR_PROFILES_MAIL_OTHERS',
 'RAA' => 'Recent Activity Android',
 'RAI' => 'Recent Activity IOS',
 'CA' => 'ACCEPT_SIMILAR_PROFILES_PC');


        $index=array('A','D','C','I','E');
	
//	$searchTypeArray=array('View Similar Profile','My Relevant Matches','Advanced','eClassified','PG','HomePage','ISearch','Clusters','Keyword','Mailer','NRI','Online','Photo','Quick','Cosmo','Software','Next Frm HmPg','Similar Cont on Home Pg','Community');
	if(!$today)
		$today=date("Y-m-d");
	list($todYear,$todMonth,$todDay)=explode("-",$today);
	if($outside)
	{
		$CMDGo='Y';
		$searchType='ALL';
		$searchMonth=$todMonth;
		$searchYear=$todYear;
		$monthDays=$todDay;
	}
	if($CMDGo)
	{
		//$curdate=date('Y-m-d');
		$searchdate_timestamp= mktime(0,0,0,$monthEntered,31,$yearEntered);
		$searchdate=date("Y-m-d",$searchdate_timestamp);
		$days_left_expire= getTimeDiff1($today,$searchdate);

		$flag=0;
		if($days_left_expire>=0)
			$flag=1;
//NO NEED OF THIS VARIABLES AS WE DONT NEED REAL TIME DATA AS IT IS TOO SLOW
		$flag=0;
//NO NEED OF THIS VARIABLES AS WE DONT NEED REAL TIME DATA AS IT IS TOO SLOW
		$searchFlag=1;
		if($searchMonth=='')
			$searchMonth=$monthEntered;
		if($searchYear=='')
			$searchYear=$yearEntered;
		if($searchType=='')
		{
			$searchType=$typeEntered;
		}
		if($searchType!='ALL')
		{
			$searchTypePrint=$searchKeyArray[$searchType];
			
		}
		else
			$searchTypePrint='ALL';
		if($monthDays==0)
		{
			if(($searchMonth=='01')||($searchMonth=='03')||($searchMonth=='05')||($searchMonth=='07')
				||($searchMonth=='08')||($searchMonth=='10')||($searchMonth=='12'))
				$monthDays=31;
			elseif(($searchMonth=='04')||($searchMonth=='06')||($searchMonth=='09')||($searchMonth=='11'))
				$monthDays=30;
			elseif(($searchYear%4==0)&&($searchYear%100!=0)||($searchYear%400==0))
				$monthDays=29;
			else
				$monthDays=28;
		}
		$k=1;
		while($k<=$monthDays)
		{
			$monthDaysArray[]=$k;
			$k++;
		}
		if($searchType!="ALL")
		{
			if($flag)
			{
				
				//Contacts and Search flow traking new table is on 3 sharded servers.
				for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
				{
					$myDbName=getActiveServerName($activeServerId,'slave');
					$myDb=$mysqlObj->connect("$myDbName");

					$sql="SELECT  S.CONTACTID,DAY(C.TIME) AS DAYNO,C.TYPE AS TYPE   FROM MIS.SEARCH_CONTACT_FLOW_TRACKING_NEW AS S, newjs.CONTACTS AS C WHERE S.CONTACTID=C.CONTACTID  AND S.SEARCH_TYPE='$searchType' AND (C.TIME) BETWEEN '$todYear-$todMonth-$todDay 00:00:00' AND '$todYear-$todMonth-$todDay 23:59:59'";
					$result=$mysqlObj->executeQuery($sql,$myDb) or die(mysql_error_js($myDb));
					
					while($myrownew=mysql_fetch_assoc($result))
					{	
						$CONTACT_ID[$myrownew['CONTACTID']]["DAYNO"]=$myrownew['DAYNO'];
						$CONTACT_ID[$myrownew['CONTACTID']]["TYPE"]=$myrownew['TYPE'];
					}
				}
				
				if($CONTACT_ID)
					foreach($CONTACT_ID as $key=>$val)
					{
						$myrownew=$val;
						
						$day=$myrownew["DAYNO"];
						$type=$myrownew["TYPE"];
						if($myrownew["TYPE"]=='A')
							$acceptence+=1;
						elseif($myrownew["TYPE"]=='D')
							$decline+=1;
						elseif($myrownew["TYPE"]=='C')
							$cancel+=1;
						elseif($myrownew["TYPE"]=='I')
							$initiated+=1;
						$dataArraynew[$day][$type]++;
						$totDatanew+=1;
					}
					unset($CONTACT_ID);
				}
				

				$sqlnew="SELECT TYPE ,COUNT AS CNT ,DAY(DATE) AS DAYNO FROM MIS.TRACK_CONTACTSEARCH_FLOW WHERE SOURCE='$searchType' AND DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays'";
			 //$sql="SELECT COUNT(*) AS CNT ,DAY(C.TIME) AS DAYNO,C.TYPE AS TYPE   FROM MIS.SEARCH_CONTACT_FLOW_TRACKING_NEW AS S, newjs.CONTACTS AS C WHERE S.CONTACTID=C.CONTACTID  AND S.SEARCH_TYPE='$searchType' AND DATE(C.TIME) BETWEEN '$searchYear-$searchMonth-$monthDays 00:00:00' AND '$searchYear-$searchMonth-$monthDays 23:59:59' GROUP BY DATE(C.TIME), C.TYPE";
				
				
			//$sql="SELECT TOTAL,DAY(DATE) AS DAYNO FROM MIS.DAILY_CONTACTSEARCH_TOTAL WHERE SEARCH_TYPE='$searchType' AND DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays'";
				$resultnew=mysql_query_decide($sqlnew,$db2) or die(mysql_error_js());
			//$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
				while($myrownew=mysql_fetch_assoc($resultnew))
				{
					$day=$myrownew["DAYNO"];
					$type=$myrownew["TYPE"];
					if($myrownew["TYPE"]=='A')
						$acceptence+=$myrownew["CNT"];
					elseif($myrownew["TYPE"]=='D')
						$decline+=$myrownew["CNT"];
					elseif($myrownew["TYPE"]=='C')
						$cancel+=$myrownew["CNT"];
					elseif($myrownew["TYPE"]=='I')
						$initiated+=$myrownew["CNT"];
					$dataArraynew[$day][$type]=$myrownew["CNT"];
					$totDatanew+=$myrownew["CNT"];
				}
			/*while($myrownew=mysql_fetch_assoc($result))
                        {
                                $day=$myrownew["DAYNO"];
                                $type=$myrownew["TYPE"];
                                if($myrownew["TYPE"]=='A')
                                        $acceptence+=$myrownew["CNT"];
                                elseif($myrownew["TYPE"]=='D')
                                        $decline+=$myrownew["CNT"];
                                elseif($myrownew["TYPE"]=='C')
                                        $cancel+=$myrownew["CNT"];
                                elseif($myrownew["TYPE"]=='I')
                                        $initiated+=$myrownew["CNT"];

                                $dataArraynew[$day][$type]=$myrownew["CNT"];
                                $totDatanew+=$myrownew["CNT"];
                            }*/
			/*while($myrow=mysql_fetch_assoc($result))
			{
				$s=$myrow["DAYNO"];
				$dataArray[$s]=$myrow["TOTAL"];
				$totData+=$myrow["TOTAL"];
			}*/
			$smarty->assign('dataArraynew',$dataArraynew);
			$smarty->assign('totDatanew',$totDatanew);
			//$smarty->assign('dataArray',$dataArray);
			//$smarty->assign('totData',$totData);
		}
		else
		{
			if($flag)
			{
				//Contacts and Search flow traking new table is on 3 sharded servers.
				for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
				{
					$myDbName=getActiveServerName($activeServerId,'slave');
				$myDb=$mysqlObj->connect("$myDbName");
					$sql2="SELECT S.CONTACTID,S.SEARCH_TYPE AS SOURCE ,DAY(C.TIME) AS DAYNO,C.TYPE AS TYPE   FROM MIS.SEARCH_CONTACT_FLOW_TRACKING_NEW AS S, newjs.CONTACTS AS C WHERE S.CONTACTID=C.CONTACTID  AND   C.TIME BETWEEN '$todYear-$todMonth-$todDay 00:00:00' AND '$todYear-$todMonth-$todDay 23:59:59'" ;
					$result2=$mysqlObj->executeQuery($sql2,$myDb) or die(mysql_error_js($myDb));
					while($myrownew2=mysql_fetch_assoc($result2))
					{
						$CONTACT_ID[$myrownew['CONTACTID']]["DAYNO"]=$myrownew['DAYNO'];
						$CONTACT_ID[$myrownew['CONTACTID']]["TYPE"]=$myrownew['TYPE'];
						$CONTACT_ID[$myrownew['CONTACTID']]["SOURCE"]=$myrownew['SOURCE'];
					}
				}
				if($CONTACT_ID)
					foreach($CONTACT_ID as $key=>$val)
					{
						$myrownew2=$val;
						$s=$myrownew2["SOURCE"];
						$d=$myrownew2["DAYNO"];
						$t=$myrownew2["TYPE"];
						if($myrownew2["TYPE"]=='A')
						{
							$day_acceptence[$d]+=1;
							$acceptence[$s]+=1;
						}
						elseif($myrownew2["TYPE"]=='D')
						{
							$day_decline[$d]+=1;
							$decline[$s]+=1;
						}
						elseif($myrownew2["TYPE"]=='C')
						{
							$day_cancel[$d]+=1;
							$cancel[$s]+=1;
						}
						elseif($myrownew2["TYPE"]=='I')
						{
							$day_initiated[$d]+=1;
							$initiated[$s]+=1;
						}

						if($s=="Ba" || $s=="Bb" || $s=="Bc" || $s=="Bd" || $s=="Be" || $s=="Bf" || $s=="Bg" || $s=="Bh" || $s=="Bi" || $s=="Bj" || $s=="Bk" || $s=="Bl" || $s=="Bm" || $s=="Bn" || $s=="Bo" || $s=="Bp" || $s=="Bq" || $s=="Br" || $s=="Bs" || $s=="Bt" || $s=="Bu" || $s=="Bv" || $s=="B1" || $s=="B2" || $s=="B3" || $s=="B4" || $s=="B5" || $s=="B6" || $s=="B7" || $s=="B8" || $s=="BN1" || $s=="BN2" || $s=="BN3" || $s=="BN4" || $s=="BN5" || $s=="BN7" || $s== "BNST" || $s== "BNSN" || $s== "BNRT" || $s== "BNRN")
						{
							$dataArraynew["B"][$d][$t]+=1;
						}
						
						if($s=="F1" || $s=="F2" || $s=="F3" || $s=="F4" || $s=="F5" || $s=="F6")
						{
							$dataArraynew["F"][$d][$t]+=1;
						}
						
						$dataArraynew[$s][$d][$t]+=1;
						$totSearchMonthnew[$myrownew2["SOURCE"]]+=1;
						$totSearchDaynew[$myrownew2["DAYNO"]]+=1;
						$grandTotalnew+=1;

					}
				}
				$sqlnew2="SELECT SOURCE ,TYPE ,COUNT AS CNT ,DAY(DATE) AS DAYNO FROM MIS.TRACK_CONTACTSEARCH_FLOW WHERE DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays'";

			//$sql2="SELECT COUNT(*) AS CNT ,S.SEARCH_TYPE AS SOURCE ,DAY(C.TIME) AS DAYNO,C.TYPE AS TYPE   FROM MIS.SEARCH_CONTACT_FLOW_TRACKING_NEW AS S, newjs.CONTACTS AS C WHERE S.CONTACTID=C.CONTACTID  AND  C.TIME BETWEEN '$searchYear-$searchMonth-$monthDays 00:00:00' AND '$searchYear-$searchMonth-$monthDays 23:59:59' GROUP BY S.SEARCH_TYPE ,DATE(C.TIME),C.TYPE";
				
				
			//$sql2="SELECT TOTAL,SEARCH_TYPE,DAY(DATE) AS DAYNO FROM MIS.DAILY_CONTACTSEARCH_TOTAL WHERE DATE BETWEEN '$searchYear-$searchMonth-01' AND '$searchYear-$searchMonth-$monthDays'";
				$resultnew2=mysql_query_decide($sqlnew2,$db2) or die(mysql_error_js());
			//$result2=mysql_query_decide($sql2,$db) or die(mysql_error_js());
				while($myrownew2=mysql_fetch_assoc($resultnew2))
				{

					
					$s=$myrownew2["SOURCE"];
					$d=$myrownew2["DAYNO"];
					$t=$myrownew2["TYPE"];
					if($myrownew2["TYPE"]=='A')
					{
						$day_acceptence[$d]+=$myrownew2["CNT"];
						$acceptence[$s]+=$myrownew2["CNT"];
					}
					elseif($myrownew2["TYPE"]=='D')
					{
						$day_decline[$d]+=$myrownew2["CNT"];
						$decline[$s]+=$myrownew2["CNT"];
					}
					elseif($myrownew2["TYPE"]=='C')
					{
						$day_cancel[$d]+=$myrownew2["CNT"];
						$cancel[$s]+=$myrownew2["CNT"];
					}
					elseif($myrownew2["TYPE"]=='I')
					{
						$day_initiated[$d]+=$myrownew2["CNT"];
						$initiated[$s]+=$myrownew2["CNT"];
					}
					
					if($s=="Ba" || $s=="Bb" || $s=="Bc" || $s=="Bd" || $s=="Be" || $s=="Bf" || $s=="Bg" || $s=="Bh" || $s=="Bi" || $s=="Bj" || $s=="Bk" || $s=="Bl" || $s=="Bm" || $s=="Bn" || $s=="Bo" || $s=="Bp" || $s=="Bq" || $s=="Br" || $s=="Bs" || $s=="Bt" || $s=="Bu" || $s=="Bv" || $s=="B1" || $s=="B2" || $s=="B3" || $s=="B4" || $s=="B5" || $s=="B6" || $s=="B7" || $s=="B8" || $s=="BN1" || $s=="BN2" || $s=="BN3" || $s=="BN4" || $s=="BN5" || $s=="BN7"  || $s== "BNST" || $s== "BNSN" || $s== "BNRT" || $s== "BNRN")
					{
						$dataArraynew["B"][$d][$t]+=$myrownew2["CNT"];
					}

					if($s=="F1" || $s=="F2" || $s=="F3" || $s=="F4" || $s=="F5" || $s=="F6")
					{
						$dataArraynew["F"][$d][$t]+=$myrownew2["CNT"];
					}

					$dataArraynew[$s][$d][$t]=$myrownew2["CNT"];
					$totSearchMonthnew[$myrownew2["SOURCE"]]+=$myrownew2["CNT"];
					$totSearchDaynew[$myrownew2["DAYNO"]]+=$myrownew2["CNT"];
					$grandTotalnew+=$myrownew2["CNT"];
					$day_acceptence_total = array_sum($day_acceptence);
					$day_decline_total = array_sum($day_decline);
					$day_cancel_total = array_sum($day_cancel);
					$day_initiated_total = array_sum($day_initiated);
					for ($i=1; $i <=$monthDays ; $i++) { 
						if($day_acceptence[$i]-$dataArraynew["3"][$i]["A"])
							$totalSentArray["A"][$i] = $day_acceptence[$i]-$dataArraynew["3"][$i]["A"];
						if($day_acceptence[$i]-$dataArraynew["3"][$i]["D"])
							$totalSentArray["D"][$i] = $day_decline[$i]-$dataArraynew["3"][$i]["D"];
						if($day_acceptence[$i]-$dataArraynew["3"][$i]["C"])
							$totalSentArray["C"][$i] = $day_cancel[$i]-$dataArraynew["3"][$i]["C"];
						if($day_acceptence[$i]-$dataArraynew["3"][$i]["I"])
							$totalSentArray["I"][$i] = $day_initiated[$i]-$dataArraynew["3"][$i]["I"];
						if($totalSentArray["A"][$i]+$totalSentArray["D"][$i]+$totalSentArray["C"][$i]+$totalSentArray["I"][$i])
							$totalSentArray_total[$i] = $totalSentArray["A"][$i]+$totalSentArray["D"][$i]+$totalSentArray["C"][$i]+$totalSentArray["I"][$i];
					}
					$totalSentArray_totalAccept = array_sum($totalSentArray,'A');
					$totalSentArray_totalDecline = array_sum($totalSentArray,'D');
					$totalSentArray_totalCancel = array_sum($totalSentArray,'C');
					$totalSentArray_totalInitiate = array_sum($totalSentArray,'I');
					$grandTotalSentnew = $totalSentArray_totalAccept+$totalSentArray_totalDecline+$totalSentArray_totalCancel+$totalSentArray_totalInitiate;
				}
			 /*while($myrownew2=mysql_fetch_assoc($result2))
                        {
                                   
                                        $s=$myrownew2["SOURCE"];
                                        $d=$myrownew2["DAYNO"];
                                        $t=$myrownew2["TYPE"];
                                        if($myrownew2["TYPE"]=='A')
					{
						$day_acceptence[$d]+=$myrownew2["CNT"];
                                                $acceptence[$s]+=$myrownew2["CNT"];
					}
                                        elseif($myrownew2["TYPE"]=='D')
					{
						$day_decline[$d]+=$myrownew2["CNT"];
                                                $decline[$s]+=$myrownew2["CNT"];
					}
                                        elseif($myrownew2["TYPE"]=='C')
					{
						$day_cancel[$d]+=$myrownew2["CNT"];
                                                $cancel[$s]+=$myrownew2["CNT"];
					}
                                        elseif($myrownew2["TYPE"]=='I')
					{
						$day_initiated[$d]+=$myrownew2["CNT"];
                                                $initiated[$s]+=$myrownew2["CNT"];
					}


                                        $dataArraynew[$s][$d][$t]=$myrownew2["CNT"];
                                        $totSearchMonthnew[$myrownew2["SOURCE"]]+=$myrownew2["CNT"];
                                        $totSearchDaynew[$myrownew2["DAYNO"]]+=$myrownew2["CNT"];
                                        $grandTotalnew+=$myrownew2["CNT"];

                                    }*/
			/*while($myrow=mysql_fetch_assoc($result2))
			{		
					$s=$myrow["SEARCH_TYPE"];
					$d=$myrow["DAYNO"];
					$dataArray[$s][$d]=$myrow["TOTAL"];
					$totSearchMonth[$myrow["SEARCH_TYPE"]]+=$myrow["TOTAL"];
                                        $totSearchDay[$myrow["DAYNO"]]+=$myrow["TOTAL"];
					$grandTotal+=$myrow["TOTAL"];
					
				}*/

				unset($flag);
				$smarty->assign('grandTotalSentnew',$grandTotalSentnew);
				$smarty->assign('totalSentArray',$totalSentArray);
				$smarty->assign('totalSentArray_total',$totalSentArray_total);
				$smarty->assign('totalSentArray_totalAccept',$totalSentArray_totalAccept);
				$smarty->assign('totalSentArray_totalDecline',$totalSentArray_totalDecline);
				$smarty->assign('totalSentArray_totalInitiate',$totalSentArray_totalInitiate);
				$smarty->assign('totalSentArray_totalCancel',$totalSentArray_totalCancel);
				$smarty->assign('day_acceptence_total',$day_acceptence_total);
				$smarty->assign('day_decline_total',$day_decline_total);
				$smarty->assign('day_cancel_total',$day_cancel_total);
				$smarty->assign('day_initiated_total',$day_initiated_total);
				$smarty->assign('grandTotalnew',$grandTotalnew);
				$smarty->assign('totSearchDaynew',$totSearchDaynew);
				$smarty->assign('dataArraynew',$dataArraynew);
				$smarty->assign('totSearchMonthnew',$totSearchMonthnew);
				$smarty->assign('day_acceptence',$day_acceptence);
				$smarty->assign('day_decline',$day_decline);
				$smarty->assign('day_cancel',$day_cancel);
				$smarty->assign('day_initiated',$day_initiated);
			//$smarty->assign('grandTotal',$grandTotal);
			//$smarty->assign('totSearchDay',$totSearchDay);
			//$smarty->assign('dataArray',$dataArray);
			//$smarty->assign('totSearchMonth',$totSearchMonth);
			}
			$smarty->assign('acceptence',$acceptence);
			$smarty->assign('decline',$decline);
			$smarty->assign('cancel',$cancel);
			$smarty->assign('initiated',$initiated);
			$smarty->assign('monthDaysArray',$monthDaysArray);
			$smarty->assign('searchTypePrint',$searchTypePrint);
			$smarty->assign('monthDaysArray',$monthDaysArray);
			$smarty->assign('searchFlag',$searchFlag);
			$smarty->assign('searchMonth',$searchMonth);
			$smarty->assign('searchYear',$searchYear);
			$smarty->assign('searchKeyArray',$searchKeyArray);
			$smarty->assign("index",$index);
			$smarty->display("contactCount.htm");
		}
		else
		{
			$k=-4;
			while($k<=5)
			{
				$yearArray[]=$todYear+$k;
				$k++;
			}
			$monthArray=array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
			$typearr=array('Accepted','Decline','Cancel');
			$smarty->assign("index",$index);
			$smarty->assign("typearr",$typearr);
			$smarty->assign('yearArray',$yearArray);
			$smarty->assign('monthArray',$monthArray);
			$smarty->assign('todYear',$todYear);
			$smarty->assign('todMonth',$todMonth);
			$smarty->assign('searchFlag',$searchFlag);
			$smarty->assign('CHECKSUM',$checksum);
			$smarty->assign('searchKeyArray',$searchKeyArray);
			$smarty->display("contactCount.htm");
		}
	}
	else
	{
		$smarty->assign('$user',$username);
		$smarty->display("jsconnectError.tpl");
	}

	function getTimeDiff1($date1,$date2)
	{
		if($date2 > $date1)
		{
			list($yy1,$mm1,$dd1)= explode("-",$date1);
			list($yy2,$mm2,$dd2)= explode("-",$date2);
			$date1_timestamp= mktime(0,0,0,$mm1,$dd1,$yy1);
			$date2_timestamp= mktime(0,0,0,$mm2,$dd2,$yy2);
			$timestamp_diff= $date2_timestamp - $date1_timestamp;
			$days_diff= $timestamp_diff / (24*60*60);
			return $days_diff;
		}
		elseif($date2 == $date1)
			return 0;
		else
			return -1;
	}
	?>
