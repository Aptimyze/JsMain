<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


/*live*/
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
/*live*/
include($_SERVER['DOCUMENT_ROOT']."/commonFiles/incomeCommonFunctions.inc");

$mysqlObj=new Mysql;
global $noOfActiveServers;
$db=connect_db();

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
	//echo "shard".$activeServerId;echo "\n";
	$myDb=connect_737();
	$shDbNameM=getActiveServerName($activeServerId,"master");
	$shDbNameS=getActiveServerName($activeServerId,"slave");
	$shDbM=$mysqlObj->connect($shDbNameM);
	$shDbS=$mysqlObj->connect($shDbNameS);
	mysql_query('set session wait_timeout=50000',$myDb);
	mysql_query('set session wait_timeout=50000',$shDbM);
	$sql_main = "SELECT PROFILEID,PARTNER_INCOME FROM newjs.JPARTNER where LINCOME='' AND PARTNER_INCOME!=''";
	$res_main = mysql_query($sql_main,$shDbS) or die($sql_main.mysql_error($shDbS));
        while($row = mysql_fetch_array($res_main))
        {
		$pid = $row['PROFILEID'];
		$pincome_str = validate_str($row['PARTNER_INCOME']);
		if($pincome_str!='' && $pincome_str!="''")
		{
			$cur_sort_arr = current_ranges_sortby($pincome_str,$myDb);
			$cur_arr = make_ranges_continous($cur_sort_arr,$myDb,'arr');
			if($cur_arr['currency']!="both")
                        {
                                $map_arr = array();
                                $map_arr = get_mapped_values($cur_arr,$myDb);
				if($cur_arr['currency']=='dollar')
				{
					$cur_arr['minIR']=$map_arr['minIR'];
					$cur_arr['maxIR']=$map_arr['maxIR'];
				}
				else
				{
					$cur_arr['minID']=$map_arr['minID'];
                                        $cur_arr['maxID']=$map_arr['maxID'];
				}
				$cur_arr['currency']='both';
			}
			$istr = get_pincome_str($cur_arr,$myDb);
			if($istr=="'15'")
                       		$sql_up = "update newjs.JPARTNER set PARTNER_INCOME=\"$istr\",LINCOME='0',HINCOME='0',LINCOME_DOL='0',HINCOME_DOL='0' where PROFILEID='$pid'";
			else
				$sql_up = "update newjs.JPARTNER set PARTNER_INCOME=\"$istr\",LINCOME='$cur_arr[minIR]',HINCOME='$cur_arr[maxIR]',LINCOME_DOL='$cur_arr[minID]',HINCOME_DOL='$cur_arr[maxID]' where PROFILEID='$pid'";
			mysql_query($sql_up,$shDbM) or die($sql_up.mysql_error($shDbM));
		
			if($istr=="'15'")
				$sql_up_2 = "update Assisted_Product.AP_DPP_FILTER_ARCHIVE set PARTNER_INCOME=\"$istr\",LINCOME='0',HINCOME='0',LINCOME_DOL='0',HINCOME_DOL='0' where PROFILEID='$pid'";
			else
				$sql_up_2 = "update Assisted_Product.AP_DPP_FILTER_ARCHIVE set PARTNER_INCOME=\"$istr\",LINCOME='$cur_arr[minIR]',HINCOME='$cur_arr[maxIR]',LINCOME_DOL='$cur_arr[minID]',HINCOME_DOL='$cur_arr[maxID]' where PROFILEID='$pid'";
	mysql_query($sql_up_2,$db) or die($sql_up_2.mysql_error($db));

		}
        }
}


function validate_str($str)
{
	$arr = explode(",",$str);
	for($i=0;$i<count($arr);$i++)
	{
		if($arr[$i]!='')
		{
			$a=preg_replace("/[a-zA-Z!(\' ')@#$+^&*-\/]/", "", $arr[$i]);
			if($a!='')
				$arr1[]=stripslashes($a);
		}
	}
	$str1 = implode("','",$arr1);
	return "'$str1'";
}
?>
