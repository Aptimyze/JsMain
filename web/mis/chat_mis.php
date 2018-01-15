<?
include("connect.inc");
$db=connect_misdb();
$data=authenticated($cid);
$start=1;

if($Submit)
{
	$date_search="$year-$mon-$day";
	$sql="select count(*),ACTION from userplane.LOG_CHAT_REQUEST where DATE='$date_search' and ACTION !='I' group by ACTION";
	$res=mysql_query_decide($sql);
	$accept=0;
	$decline=0;
	$initiate=0;
	$timeout=0;
	while($row=mysql_fetch_array($res))
	{
		
			
		if($row['ACTION']=='A')
			$accept=$row[0];
		if($row['ACTION']=='D')
			$decline=$row[0];
		if($row['ACTION']=='T')
			$timeout=$row[0];
		$initiate=$initiate+$row[0];
	}
	$smarty->assign("TOTAL_INI",$initiate);
	$smarty->assign("TOTAL_ACC",$accept);
	$smarty->assign("TOTAL_DEC",$decline);
	$smarty->assign("TOTAL_TIME",$timeout);
	$smarty->assign("date",$date_search);
	$smarty->assign("FIRST_ROUND",1);
	
}
else if($date_search)
{
	$sql="SELECT *
FROM userplane.LOG_CHAT_REQUEST  where `DATE`='$date_search' and ACTION !='I' and MES!=''  order by `TIME`  limit $k,50";
	$res=mysql_query_decide($sql) or die(mysql_error_js());
	while($row=mysql_fetch_array($res))
	{
		$data_sr.="<TR><TD><a href='/jsadmin/showstat.php?cid=$cid&profileid=$row[SEN]'target='_blank'>$row[SENU]</a></TD><td><a href='/jsadmin/showstat.php?cid=$cid&profileid=$row[REC]'target='_blank'>$row[RECU]</a></td><TD>$row[SEN_P]</TD><TD>$row[REC_P]</TD><TD>$row[MES]</TD><TD>$row[ACTION]</TD></TR>";
	}
	$smarty->assign("date",$date_search);
	$smarty->assign("Next",$k+50);
	$smarty->assign("data_sr",$data_sr);
	$smarty->assign("SECOND_ROUND",1);
	
}


if(!isset($day))
		$day=date("d");
	if(!isset($mon))	
		$mon=date("m");
	if(!isset($year))
		$year=date("Y");
	for($i=1;$i<=31;$i++)
	{ 
			if($i==$day)
				$DAY.="<option value=$i selected='selected'>$i</option>";
		else
				$DAY.="<option value=$i >$i</option>";
	}
	for($i=1;$i<=12;$i++)
	{
		if($i==$mon)
			$MONTH.="<option value=$i selected='selected'>$i</option>";
		else
			$MONTH.="<option value=$i >$i</option>";
	}
	
	for($i=2003;$i<=date("Y");$i++)
	{
		if($i==$year)
			$YEAR.="<option value=$i selected='selected'>$i</option>";
		else
			$YEAR.="<option value=$i>$i</option>";
	}
		$smarty->assign("MONTH",$MONTH);
		$smarty->assign("DAY",$DAY);
		$smarty->assign("YEAR",$YEAR);	
		$smarty->assign("cid",$cid);
if(isset($data))
{
	$smarty->display("chat_mis.htm");
}
else
	$smarty->display("jsconnectError.tpl");
