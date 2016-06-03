<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


$flag_using_php5=1;
include_once("connect.inc");
$mysqlObj=new Mysql;

$backtime=mktime(0,0,0,date("m"),date("d")-1,date("Y")); // To get the time for previous days
$backdate=date("Y-m-d",$backtime);
for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $mysqlObj=new Mysql;
        $myDbName=getActiveServerName($activeServerId,'slave');
        $myDb=$mysqlObj->connect("$myDbName");
	$sql="SELECT COUNT(*) AS COUNT,SEARCH_TYPE,TYPE FROM newjs.CONTACTS AS C JOIN MIS.SIMILLAR_CONTACT_COUNT_FOR_ABTESTING AS S on S.CONTACTID=C.CONTACTID  WHERE DATE(C.TIME)='$backdate' GROUP BY SEARCH_TYPE,TYPE";
	//$sql="SELECT COUNT(*) AS COUNT,SEARCH_TYPE,TYPE FROM newjs.CONTACTS AS C JOIN MIS.SIMILLAR_CONTACT_COUNT_FOR_ABTESTING AS S on S.CONTACTID=C.CONTACTID  GROUP BY SEARCH_TYPE,TYPE";
	$result = $mysqlObj->executeQuery($sql,$myDb);
	while($row=$mysqlObj->fetchArray($result))
	{
		$cnt=$row["COUNT"];
		$stype=$row["SEARCH_TYPE"];
		$ctype=$row["TYPE"];
		$sum[$stype][$ctype]+=$cnt;			
	}
}

if(is_array($sum))
{
	$icnt0=$sum["CN"]['I'];
	$acnt0=$sum["CN"]['A'];
	$dcnt0=$sum["CN"]['D'];
	$ccnt0=$sum["CN"]['C'];

	$icnt2=$sum["CN2"]['I'];
	$acnt2=$sum["CN2"]['A'];
	$dcnt2=$sum["CN2"]['D'];
	$ccnt2=$sum["CN2"]['C'];

	$dbMaster=$mysqlObj->connect('master');
	$sql="INSERT INTO MIS.VIEWSIMILLAR_ABTESTING_CONTACTS(CONTACT_DATE,SEARCH_TYPE,ACCEPTANCE,DECLINE,INITIAL,CANCEL) VALUES ('$backdate','CN','$acnt0','$dcnt0','$icnt0','$ccnt0')"; 
	$result = $mysqlObj->executeQuery($sql,$dbMaster);

	$sql="INSERT INTO MIS.VIEWSIMILLAR_ABTESTING_CONTACTS(CONTACT_DATE,SEARCH_TYPE,ACCEPTANCE,DECLINE,INITIAL,CANCEL) VALUES ('$backdate','CN2','$acnt2','$dcnt2','$icnt2','$ccnt2')"; 
	$result = $mysqlObj->executeQuery($sql,$dbMaster);
}
?>
