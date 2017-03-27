<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");

include("$docRoot/crontabs/connect.inc");
$db=connect_slave();

                $strArr   	   	=array(); 
		$cnt			=0;
		$totcnt			=0;
		$todayDate		=date("Y-m-d");
		$dateSel		=date("Y-m-d",strtotime("$todayDate -1 days"));

                $sql ="SELECT COUNT(*) as CNT,TYPE FROM MIS.LTF WHERE DATE>='$dateSel 00:00:00' AND DATE<='$dateSel 23:59:59' group by TYPE";
                $res = mysql_query_decide($sql,$db) or die($sql.mysql_error_js());
                while($row = mysql_fetch_array($res))
                {
			$cnt 	 	=$row['CNT'];
			$type	 	=$row['TYPE'];
			$strArr[]	="$type".'='."$cnt";
			$totcnt		+=$cnt;
                }
		$str  ="Total record added: $totcnt"."\n\n".
		$str  .=implode("\n",$strArr);
		
mail("manoj.rana@naukri.com,vibhor.garg@jeevansathi.com,rohan.mathur@jeevansathi.com","sugarcrm LTF status report for $dateSel ", "$str");

?>
