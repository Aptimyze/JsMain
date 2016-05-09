<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include_once("connect.inc");
$db=connect_db();
$dbS=connect_slave();
eduMap($db,$dbS);

function eduMap($db,$dbS)
{
	$sql_get="SELECT OLD_VALUE ,VALUE FROM newjs.EDUCATION_LEVEL_NEW ";
	$res_get=mysql_query($sql_get,$dbS)or mysql_error1(mysql_error($dbS));
	while($row_get=mysql_fetch_array($res_get))
	{
		$old_value[$row_get["VALUE"]]=$row_get["OLD_VALUE"];
	}
	$sql="SELECT PROFILEID,EDU_LEVEL_NEW FROM newjs.JPROFILE WHERE EDU_LEVEL='' AND EDU_LEVEL_NEW!='' AND EDU_LEVEL_NEW!='22'";
	//TEST
		//$sql.="LIMIT 2";
	//TEST
	$res=mysql_query($sql,$dbS) or mysql_error1(mysql_error($dbS));
	while($row=mysql_fetch_array($res))
	{
		$j=$row['PROFILEID'];
		$i=$row["EDU_LEVEL_NEW"];	
		$sql_update="UPDATE newjs.JPROFILE SET EDU_LEVEL=$old_value[$i] WHERE PROFILEID=$j";
		$res_update=mysql_query($sql_update,$db)or mysql_error1(mysql_error($db));
	}


	$sql="SELECT PROFILEID,EDU_LEVEL_NEW FROM newjs.SEARCH_FEMALE WHERE EDU_LEVEL='' AND EDU_LEVEL_NEW!='' AND EDU_LEVEL_NEW!='22'";
	//TEST
		//$sql.="LIMIT 2";
	//TEST
	$res=mysql_query($sql,$dbS) or mysql_error1(mysql_error($dbS));
	while($row=mysql_fetch_array($res))
	{
		$j=$row['PROFILEID'];
		$i=$row["EDU_LEVEL_NEW"];	
		$sql_update="UPDATE newjs.SEARCH_FEMALE SET EDU_LEVEL=$old_value[$i] WHERE PROFILEID=$j";
		$res_update=mysql_query($sql_update,$db)or mysql_error1(mysql_error($db));
	}

	$sql="SELECT PROFILEID,EDU_LEVEL_NEW FROM newjs.SEARCH_MALE WHERE EDU_LEVEL='' AND EDU_LEVEL_NEW!='' AND EDU_LEVEL_NEW!='22'";
	//TEST
		//$sql.="LIMIT 2";
	//TEST
	$res=mysql_query($sql,$dbS) or mysql_error1(mysql_error($dbS));
	while($row=mysql_fetch_array($res))
	{
		$j=$row['PROFILEID'];
		$i=$row["EDU_LEVEL_NEW"];	
		$sql_update="UPDATE newjs.SEARCH_MALE SET EDU_LEVEL=$old_value[$i] WHERE PROFILEID=$j";
		$res_update=mysql_query($sql_update,$db)or mysql_error1(mysql_error($db));
	}

}
function mysql_error1($msg)
{
	global $db;
	echo $msg;
	mail("sandeep.samudrala@jeevansathi.com","Error in sql query",$msg);
	die;
}
