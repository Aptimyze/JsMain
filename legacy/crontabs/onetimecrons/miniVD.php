<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
$db_slave =connect_slave();
$master =connect_db();

$allHindiArr =array('10','19','33');
$allOtherArr =array('27','30','20','34','13','7','28','36','12','6');

$sdate ='2015-11-13';
$edate ='2015-11-15';

$serviceArr =array('P','C','NCP','ESP','X');
$lastLoginDt ='2015-10-14 00:00:00';

$b1_date1='2015-10-29 00:00:00';
$b1_date2='2015-11-12 23:59:59';

$b2_date1='2015-09-29 00:00:00';
$b2_date2='2015-10-28 23:59:59';

$b3_date1='2015-08-29 00:00:00';
$b3_date2='2015-09-28 23:59:59';

$b4_date1='2015-08-28 23:59:59';

$b1_date1 =strtotime($b1_date1);
$b1_date2 =strtotime($b1_date2);
$b2_date1 =strtotime($b2_date1);
$b2_date2 =strtotime($b2_date2);
$b3_date1 =strtotime($b3_date1);
$b3_date2 =strtotime($b3_date2);
$b4_date1 =strtotime($b4_date1);

	$sqlMain 	="select PROFILEID,ENTRY_DT,SUBSCRIPTION,MOB_STATUS,LANDL_STATUS,MTONGUE from newjs.JPROFILE WHERE LAST_LOGIN_DT>='$lastLoginDt' AND ACTIVATED IN('Y','H')";
        $resMain 	=mysql_query($sqlMain,$db_slave) or logError($sqlMain);
        while($rowMain	=mysql_fetch_array($resMain))
	{
		$profileid	=$rowMain['PROFILEID'];
	        $entryDt      	=$rowMain['ENTRY_DT'];
	        $subscription   =$rowMain['SUBSCRIPTION'];
		$mobStatus	=$rowMain['MOB_STATUS'];
		$lStatus	=$rowMain['LANDL_STATUS'];
		$mtongue	=$rowMain['MTONGUE'];

		if($mobStatus!='Y' && $lStatus!='Y')
			continue;
		if((strstr($subscription,"F")!="")||(strstr($subscription,"D")!=""))
			continue;
		
		$discount	='';
		$entryDt	=strtotime($entryDt);
		if(($entryDt>=$b1_date1) && ($entryDt<=$b1_date2)){
			if(in_array($mtongue,$allHindiArr)){
				$discount=10;
			}
			elseif(in_array($mtongue,$allOtherArr)){
				$discount=15;
			}
			else{
				$discount=25;
			}
			
		}
                else if(($entryDt>=$b2_date1) && ($entryDt<=$b2_date2)){

                        if(in_array($mtongue,$allHindiArr)){
                                $discount=15;
                        }
                        elseif(in_array($mtongue,$allOtherArr)){
                                $discount=30;
                        }
                        else{
                                $discount=50;
                        }
                }
                else if(($entryDt>=$b3_date1) && ($entryDt<=$b3_date2)){
                        if(in_array($mtongue,$allHindiArr)){
                                $discount=25;
                        }
                        elseif(in_array($mtongue,$allOtherArr)){
                                $discount=40;
                        }
                        else{
                                $discount=50;
                        }
                }
                else if($entryDt<=$b4_date1){
                        if(in_array($mtongue,$allHindiArr)){
                                $discount=35;
                        }
                        elseif(in_array($mtongue,$allOtherArr)){
                                $discount=50;
                        }
                        else{
                                $discount=60;
                        }
                }
		foreach($serviceArr as $key=>$serviceId){
			$sql1 ="INSERT INTO billing.VARIABLE_DISCOUNT_TEMP(`PROFILEID`,`SDATE`,`EDATE`,`SERVICE`,`3`,`6`,`12`) VALUES('$profileid','$sdate','$edate','$serviceId','$discount','$discount','$discount')";
			mysql_query($sql1,$master) or logError($sql1);
		}
	}




?>
