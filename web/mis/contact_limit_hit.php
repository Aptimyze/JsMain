<?
include("connect.inc");
$db=connect_misdb();
//$mon="3";
//$year="2007";
$data=authenticated($cid);
$start=1;
$arr["down"]['USERNAME']="ORDER BY `USERNAME` DESC";
$arr["down"]['DATE']="ORDER BY `DATE` DESC";
$arr["up"]['USERNAME']="ORDER BY `USERNAME` ASC";
$arr["up"]['DATE']="ORDER BY `DATE` ASC";

$sortby["down"]['USERNAME']="<a href='contact_limit_hit.php?sort=up&met=USERNAME&cid=$cid&user=$user' class='label'>USERNAME</a><img src='http://www.jeevansathi.com/mantis/images/down.gif'>";

$sortby["down"]['DATE']="<a href='contact_limit_hit.php?sort=up&met=DATE&cid=$cid&user=$user' class='label'>DATE</a><img src='http://www.jeevansathi.com/mantis/images/down.gif'>";

$sortby["up"]['USERNAME']="<a href='contact_limit_hit.php?sort=down&met=USERNAME&cid=$cid&user=$user' class='label'>USERNAME</a><img src='http://www.jeevansathi.com/mantis/images/up.gif'>";

$sortby["up"]['DATE']="<a href='contact_limit_hit.php?sort=down&met=DATE&cid=$cid&user=$user' class='label'>DATE</a><img src='http://www.jeevansathi.com/mantis/images/up.gif'>";

if(isset($data))
{
	if($Submit)
	{
		if($year=="")
			$year=date("Y");
		if($mon=="")
			$mon=date("m");
		if($day=="")
			$day=date("d");
		
		if($TYPE=='T')
		{

			$sql="Select PROFILEID,USERNAME,`DATE` from MIS.CONTACTS_FAULT_MONITOR where YEAR='$year' and MONTH='$mon' and DAY='$day' and TYPE='T'";
						
		}
		if($TYPE=='M')
		{
			$sql="Select PROFILEID,USERNAME,`DATE` from MIS.CONTACTS_FAULT_MONITOR where YEAR='$year' and MONTH='$mon' and TYPE='M'";
		}
		if($TYPE=='O')
		{
			$sql="Select PROFILEID,USERNAME,`DATE` from MIS.CONTACTS_FAULT_MONITOR where TYPE='O'";
		}
		setcookie("CONTACT_QUERY", $sql);
		
		
		$sql.="  order by `DATE` DESC";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js($db));
		$i=0;
		while($row=mysql_fetch_array($res))
		{
			$i++;
			$template.="<TR><TD  class=fieldsnew >$i</td><TD  class=fieldsnew ><a href='/jsadmin/showstat.php?cid=$cid&profileid=$row[PROFILEID]'>$row[USERNAME]</a></td><TD  class=fieldsnew >$row[DATE]</td></tr>";
		}
		if($i==0)
			$template.="<tr><TD colspan=3 class='label'> No Results found</td></tR>";
		$user_sort="<a  target='_blank' href='contact_limit_hit.php?sort=down&met=USERNAME&cid=$cid&user=$user' class='label'>USERNAME</a>";
		$date_sort="<a href='contact_limit_hit.php?sort=up&met=DATE&cid=$cid&user=$user' class='label'>DATE</a><img src='http://www.jeevansathi.com/mantis/images/down.gif'>";
		$smarty->assign("template",$template);
		$smarty->assign("user_sort",$user_sort);
		$smarty->assign("date_sort",$date_sort);
		$smarty->assign("cid",$cid);
		$smarty->assign("show","yes");		
			
	}
	elseif($sort)		
	{
		$sql=stripslashes($_COOKIE['CONTACT_QUERY']);
		$sql.=" ".$arr[$sort][$met];
		if($met=='DATE')
		{
			$user_sort="<a href='contact_limit_hit.php?sort=down&met=USERNAME&cid=$cid&user=$user' class='label'>USERNAME</a>";
			$date_sort=$sortby[$sort][$met];
		}
		elseif($met="USERNAME")
		{
			$user_sort=$sortby[$sort][$met];
			$date_sort="<a href='contact_limit_hit.php?sort=down&met=DATE&cid=$cid&user=$user' class='label'>DATE</a>";
		}
		$res=mysql_query_decide($sql,$db) or die($sql.mysql_error_js($db));
		$i=0;
		while($row=mysql_fetch_array($res))
		{$i++;
			$template.="<TR><TD  class=fieldsnew > $i</td><TD  class=fieldsnew ><a target='_blank' href='/jsadmin/showstat.php?cid=$cid&profileid=$row[PROFILEID]'>$row[USERNAME]</a></td><TD  class=fieldsnew >$row[DATE]</td></tr>";
		}
			if($i==0)
			$template.="<tr><TD colspan=3> No Results found</td></tR>";

			
			$smarty->assign("template",$template);
			$smarty->assign("user_sort",$user_sort);
			$smarty->assign("date_sort",$date_sort);
			$smarty->assign("cid",$cid);
			$smarty->assign("show","yes");
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
	if(!$_COOKIE['ORDER_BY'])
	{
		
		$order_by="ORDER BY DATE DESC";
	}
	
	$smarty->assign("action",$_SERVER['REQUEST_URI']);
	$smarty->assign("MONTH",$MONTH);
	$smarty->assign("DAY",$DAY);
	$smarty->assign("YEAR",$YEAR);	
	$smarty->assign("cid",$cid);
	$smarty->display("contact_limit_hit.htm");
}
else
	$smarty->display("jsconnectError.tpl");
