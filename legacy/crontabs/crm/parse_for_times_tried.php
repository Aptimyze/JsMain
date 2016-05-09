<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


include("../connect.inc");
$db=connect_db();

$filename = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_2006-01-25.txt";
//$filename = "/home/shiv/crm/bulk_csv_crm_data_2006-01-22.txt";

$fp = fopen($filename,"r");
if(!$fp)
{
        die("no file pointer");
}

$whole_file=fread($fp,filesize($filename));

$rows_arr=explode("\n",$whole_file);

$rows_cnt=count($rows_arr);

for($i=1;$i<$rows_cnt-1;$i++)
{
	$cols_arr=explode(",",$rows_arr[$i]);

	$pid=$cols_arr[0];
	$pid=str_replace("\"","",$pid);
	$profile_arr[]=$pid;
	unset($cols_arr);
}

unset($rows_arr);

$profile_cnt=count($profile_arr);

for($i=0;$i<$profile_cnt;$i++)
{
	$sql="SELECT COUNT(*) as cnt FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profile_arr[$i]'";
	$res=mysql_query($sql) or die("$sql".mysql_error());
	$row=mysql_fetch_array($res);
	$cnt=$row['cnt'];

	if($cnt==0)
	{
		$final_profile_arr[]=$profile_arr[$i];
	}
}
unset($profile_arr);
//print_r($final_profile_arr);

if($final_profile_arr)
{
	$profile_str=implode("','",$final_profile_arr);
	$sql="UPDATE incentive.MAIN_ADMIN_POOL SET TIMES_TRIED=TIMES_TRIED+1 WHERE PROFILEID IN ('$profile_str')";
	mysql_query($sql) or die("$sql".mysql_error());

	echo mysql_affected_rows();
}
?>
