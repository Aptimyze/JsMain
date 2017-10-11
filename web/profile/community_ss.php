<?
include("connect.inc");
$db=connect_slave();
 
$filename="COMMUNITY_SS_COUNT.csv";   //Name of the file
$filetype = "application/msexcel"; //Type of the file to send
$result="'Community name','Total Success Story Posted'\r\n";
$sql="select SEND_EMAIL,EMAIL from SUCCESS_STORIES where UPLOADED='A'";
$res=mysql_query_decide($sql);
while($row=mysql_fetch_assoc($res))
{
	$send_email=$row['SEND_EMAIL'];
	$email=$row['EMAIL'];
	if($send_email=="")
		$send_email=$email;
	$sql="select MTONGUE from JPROFILE where EMAIL='$send_email'";
	$res_cnt=mysql_query_decide($sql);
	if($row_cnt=mysql_fetch_row($res_cnt))
	{
		$sql="select LABEL from MTONGUE where VALUE='".$row_cnt[0]."'";
		$res1=mysql_query_decide($sql);
		if($row1=mysql_fetch_row($res1))
			$actual[$row1[0]]=$actual[$row1[0]]+1;
		
	}			
}
if(is_array($actual))
{
	foreach($actual as $key=>$val)
	{
		$result.="'$key','$val'";
		$result.="\r\n";
	
	}
	
	$message='';
    $Subject="Mantis id:0002188: No. of success stories community wise.";
    $from="JSTECH@jeevansathi.com";
    $to="puneet.chawla@jeevansathi.com,nikhil.dhiman@jeevansathi.com";

    $data=$result;
   
       send_email( $to,$message,$Subject,$from,'','',$data,$filetype,$filename,'');
}

?>