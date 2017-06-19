<?php
/************************************************************************************************************************
*    DESCRIPTION        : Interface To send Matchalerts to a user of a particular date.
*    CREATED BY         : lavesh
***********************************************************************************************************************/
include("connect.inc");
$db=connect_slave81();

if($mailer)
{
	$path = $_SERVER['DOCUMENT_ROOT']."/jsadmin/resend_match_alert_mailer.php $pid $resenddate >> /dev/null &";
	$cmd = "/usr/bin/php -q ".$path;
	passthru($cmd);

	$smarty->assign("flag",2);
}
elseif($CMDGo)
{
	$i=0;
	$lastdayofmonth= getlastdayofmonth($mm,$yy);
	$start=mktime(0,0,0,$mm,01,$yy);
	$end=mktime(0,0,0,$mm,$lastdayofmonth,$yy);

        $zero=mktime(0,0,0,01,01,2005);
        $sgap=ceil(($start-$zero)/(24*60*60));
        $egap=ceil(($end-$zero)/(24*60*60));

	$sql="SELECT count(*) AS CNT , DATE FROM matchalerts.LOG where RECEIVER=$pid AND DATE BETWEEN $sgap and $egap GROUP BY DATE ";
	$result=mysql_query($sql,$db) or die(mysql_error());
	while($row=mysql_fetch_array($result))
	{
		$cnt=$row["CNT"];
		$dt=$row["DATE"];

		$zero=mktime(0,0,0,01,01,2005);
		$date=date("Y-m-d",($dt*24*60*60 + $zero));		
		$arr[$i]['SNO']=$i+1;
		$arr[$i]['CNT']=$cnt;
		$arr[$i]['DATE']=$date;
		$arr[$i]['MATCHALERTSDATE']=$dt;
		$i++;
	}
	$smarty->assign("arr",$arr);
	$smarty->assign("pid",$pid);
	if(is_array($arr))
		$smarty->assign("flag",1);
}
else
{
	for($i=0;$i<12;$i++)
	{
	$mmarr[$i]=$i+1;
	if(strlen($mmarr[$i])==1)
		$mmarr[$i]= "0".$mmarr[$i];
	}
	for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("yyarr",$yyarr);
	$smarty->assign("flag",3);
}
$name= getname($cid);
$smarty->assign("name",$name);
$smarty->assign("cid",$cid);
$smarty->assign("pid",$pid);
$smarty->display("resend_match_alert.htm");

function getlastdayofmonth($mm,$yy)
{
        if($mm<10)
                $mm="0".$mm;

        switch($mm)
        {
                case '01' : $ret='31';
                        break;
                case '02' :
                        $check=date("L",mktime(0,0,0,$mm,31,$yy));
                        if($check)
                                $ret='29';
                        else
                                $ret='28';
                        break;
                case '03' : $ret='31';
                        break;
                case '04' : $ret='30';
                        break;
                case '05' : $ret='31';
                        break;
                case '06' : $ret='30';
                        break;
                case '07' : $ret='31';
                        break;
                case '08' : $ret='31';
                        break;
                case '09' : $ret='30';
                        break;
                case '10' : $ret='31';
                        break;
                case '11' : $ret='30';
                        break;
                case '12' : $ret='31';
                        break;
        }
        return $ret;
}
?>
