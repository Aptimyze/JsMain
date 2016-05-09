<?php
$flag_using_php5 = 1;
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include_once($curFilePath."../connect.inc");
if(!$mysqlObj)
	$mysqlObj=new Mysql;
	ini_set('max_execution_time','0');
	$myDbSlave = $mysqlObj->connect($slave_activeServers[$shard]);
	mysql_query_decide("set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000",$myDbSlave);
	$myDb = $mysqlObj->connect($activeServers[$shard]);
	mysql_query_decide("set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000",$myDb);
$tableArr = array(
   
	 'JPARTNER'=> array(
        'SET' => 'LINCOME,HINCOME,PARTNER_INCOME',
        'DB' => 'newjs',
        'KEY'=>'PROFILEID'
    )
    
);

$shard = $argv[1];

foreach($tableArr as $key=>$val)
{
	
	$col = explode(',',$val[SET]);
	foreach($col as $k=>$v)
	{
		if($v == 'PARTNER_INCOME' || ($key =='SEARCH_AGENT' && $v == 'INCOME') )
		{
			$colName[] = "$v LIKE \"%'1'%\"";
			
			//$setArr[] = "$v = REPLACE($v,'\'1\'','\'2\'')";
		}
		else
		{
			$colName[] = "$v ='1'";
			//$setArr[] = "$v=IF($v ='1','2',$v) ";
		}
		$colnamesarr[]=$v;
		
	}
	$colStr = implode(' OR ',$colName);
	$colnames=implode(",",$colnamesarr);
	unset($colName);
	unset($colnamesarr);
	$inArray=null;
	
	$sql = "SELECT $val[KEY],$colnames FROM $val[DB].$key WHERE $colStr";
	$res = mysql_query_decide($sql,$myDbSlave) or die(mysql_error($myDbSlave));
	
	while($row = mysql_fetch_assoc($res))
	{
		foreach($row as $kk=>$vv)
		{
			if($kk==$val[KEY])
				$keyvalue=$vv;
			else
				$valueUpdateArr[]=getCorrectIncome($kk,$vv);
		}
		$valueUpdate=implode(",",$valueUpdateArr);
		unset($valueUpdateArr);
		$inArray[$keyvalue] =$valueUpdate ;
	}
	
	
	foreach($inArray as $kk=>$vv)
	{
		$sqlUpdate="update $val[DB].$key set $vv where $val[KEY]='$kk'";
		mysql_query_decide($sqlUpdate,$myDb) or cronlogError($val);
	}
	//mysql_close($myDb);
	
	unset($setArr);
	unset($colName);
	unset($inArray);
}



function cronlogError($val)
{
	global $error;
	$file = 'income_error2.txt';
	if(file_exists($file))
		$str = file_get_contents($file);
	$str .= "Error while updating '$val[SET]'->'$val[DB]' \n";
	file_put_contents($file, $str);
}
function getCorrectIncome($key,$val)
{
	if($val)
	{
		if(str_replace("'","",$val)==$val)
		{
			if($val==1)
				$val=2;
		}
		else
		{
			$valueUpdate=str_replace("'","",$val);
			$arr=explode(",",$valueUpdate);
			$act=null;
			foreach($arr as $kk=>$vv)
			{
				if($vv==1)
					$act[]=2;
				else if($vv!=2)
				$act[]=$vv;
			}
			if($act)
				$val="'".implode("','",$act)."'";
			
		}
	}
	return " $key=\"$val\"";
	
}		
?>
