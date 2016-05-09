<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
include_once($curFilePath."../connect.inc");
$myDbSlave = connect_slave();
mysql_query_decide("set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000",$myDbSlave);
$myDb = connect_db();
mysql_query_decide("set session wait_timeout=365000,interactive_timeout=365000,net_read_timeout=365000",$myDb);
ini_set('max_execution_time','0');

$tableArr = array(
    'AP_DPP_FILTER_ARCHIVE' => array(
        'SET' => 'LINCOME,HINCOME,PARTNER_INCOME',
        'DB' => 'Assisted_Product',
        'KEY'=>'DPP_ID'
    ),
     'AP_TEMP_DPP'=> array(
        'SET' => 'LINCOME,HINCOME,PARTNER_INCOME',
        'DB' => 'Assisted_Product',
        'KEY'=>'PROFILEID'
    ),
     'JPROFILE'=> array(
        'SET' => 'INCOME,FAMILY_INCOME',
        'DB' => 'newjs',
        'KEY'=>'PROFILEID'
    ),
     'SEARCH_AGENT'=> array(
        'SET' => 'LINCOME,HINCOME,INCOME',
        'DB' => 'newjs',
        'KEY'=>'ID'
    ),
     'SEARCH_MALE'=> array(
        'SET' => 'INCOME',
        'DB' => 'newjs',
        'KEY'=>'PROFILEID'
    ),
     'SEARCH_FEMALE'=> array(
        'SET' => 'INCOME',
        'DB' => 'newjs',
        'KEY'=>'PROFILEID'
    ),
	 'leads_cstm'=> array(
        'SET' => 'income_c',
        'DB' => 'sugarcrm',
        'KEY'=>'id_c'
    ),
    'inactive_leads_cstm'=> array(
        'SET' => 'income_c',
        'DB' => 'sugarcrm_housekeeping',
        'KEY'=>'id_c'
    ),
    'connected_leads_cstm'=> array(
        'SET' => 'income_c',
        'DB' => 'sugarcrm_housekeeping',
        'KEY'=>'id_c'
    )
    
);

foreach($tableArr as $key=>$val)
{
	
	$col = explode(',',$val[SET]);
	foreach($col as $k=>$v)
	{
		if($v == 'PARTNER_INCOME')
		{
			$colName[] = "$v LIKE \"%'1'%\"";
		}
		else if($key =='SEARCH_AGENT' && $v == 'INCOME')
		{
			$colName[] = "$v =1 OR $v LIKE \"1,%\" OR $v LIKE \"%,1\" OR $v LIKE \"%,1,%\" ";
		}
		else
		{
			$colName[] = "$v = '1'" ;
		}
		$colnamesarr[]=$v;
		
	}
	$colStr = implode(' OR ',$colName);
	$colnames=implode(",",$colnamesarr);
	unset($colName);
	unset($colnamesarr);
	$inArray=null;
	
	$sql = "SELECT $val[KEY],$colnames FROM $val[DB].$key WHERE $colStr";
//echo "\n ssql $sql\n";
	$res = mysql_query_decide($sql,$myDbSlave) or die(mysql_error($myDbSlave));
	
	while($row = mysql_fetch_assoc($res))
	{
		foreach($row as $kk=>$vv)
		{
//			echo "\n$key   $kk\n";
			if($kk==$val[KEY])
				$keyvalue=$vv;
			else
				$valueUpdateArr[]=getCorrectIncome($kk,$vv);
		}
		//print_r($valueUpdateArr);
		$valueUpdate=implode(",",$valueUpdateArr);
		unset($valueUpdateArr);
		$inArray[$keyvalue] =$valueUpdate ;
	}
	
	
	foreach($inArray as $kk=>$vv)
	{
		$sqlUpdate="update $val[DB].$key set $vv where $val[KEY]='$kk'";
		//echo "\n Update sql   ".$sqlUpdate."\n";
		mysql_query_decide($sqlUpdate,$myDb) or cronlogError($val);
	}
	
	unset($setArr);
	unset($colName);
	unset($inArray);
}




$sql1 = "UPDATE scoring.incgen SET income = '2' WHERE income = '1'";
$sql2 = "UPDATE scoring_new.incomeXgender SET income = '2' WHERE income = '1'";
$myDb = connect_db();
mysql_query_decide($sql1,$myDb);
mysql_query_decide($sql2,$myDb);


function cronlogError($val)
{
	global $error;
	$file = 'income_error.txt';
	if(file_exists($file))
		$str = file_get_contents($file);
	$str .= "Error while updating '$val[SET]'->'$val[DB]' \n";
	file_put_contents($file, $str);
}
function getCorrectIncome($key,$val)
{
	if($val)
	{
		if(str_replace(",","",$val)==$val)
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
			{
				if(str_replace("'","",$val)==$val)
					$val=implode(",",$act);
				else
					$val="'".implode("','",$act)."'";
			}
			
		}
	}
	return " $key=\"$val\"";
	
}		
?>
