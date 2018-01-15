<?
include("connect.inc");
$db=connect_misdb();
mysql_select_db("MIS",$db);
//$mon="3";
//$year="2007";
$data=authenticated($cid);
$start=1;
	$category=array(0=>"Profile Deletion",1=>"Contact initiation",2=>"Login to jeevansathi.com",3=>"Retrieve username/password",4=>"Search for perfect match",5=>"Photo",6=>"Payment",7=>"Abuse",8=>"Suggestion",9=>"Other");
	$category_name=array(0=>"Profile Deletion",1=>"Contact initiation",2=>"Login to jeevansathi.com",3=>"Retrieve username/password",4=>"Search for perfect match",5=>"Photo Upload",6=>"Membership/Payment Related Queries",7=>"Report Abuse",8=>"Suggestions",9=>"Others");


if(isset($data))
{
	if($MONTH!="")
	{
		$catname=$category[$CAT];
		if($CAT>4)
		{
			$brown="<font color=brown>Matter for which no help template shown</font><BR>";
			$color='BROWN';
		}
		$template="<table border=0 width=100%  cellpadding=3><TR><td align=center class=label><a  href='feedback_mis.php?Submit=Go&cid=$cid&mon=$MONTH&year=$YEAR'>Get back to previous result</a><tr><TD	align=center class=label><b>$catname</b></td></tr>";
		$template.="<tr><TD align=center class=label><b>Duration: $MONTH - $YEAR</b></td></tr>";
		$template.="<tr><Td><table border=0 width=100% cellpadding=3><tr><Td align=center class=label>DAYS</td><td class=label align=center> Questions</td></tr>";
		if($year!=date("Y") || $mon!=date("m"))
			$total=date("t",mktime(0,0,0,$mon,1,$year));
		else
			$total=date("d");
		
		$j=1;
		$sql="select FM.TICKETID,RESOLVED,QUERY,DAY(`DATE`) as dd from MIS.FEEDBACK_RESULT as FM,feedback.TICKET_MESSAGES as TM where FM.TICKETID=TM.TICKETID and  MONTH(`DATE`)=$MONTH and YEAR(`DATE`)=$YEAR  and `CATEGORY`='$catname' order by dd ASC";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		while($row=mysql_fetch_assoc($res))
		{
			$arr[$row['dd']][]=$row['QUERY'];
			$resolved[$row['dd']][]=$row['RESOLVED'];
		}
		
		
		for($i=0;$i<=$total;$i++)
		{
			if($arr[$j][0]!="")
			{
				$template.="<tr><td align=center class=label>$j</td><td><table border=0 align=center width=100% cellspacing=5 cellpadding=0>";
				for($start=0;$start<count($arr[$j]);$start++)
				{
					if($color!="BROWN")
					{
						$color='green';
						if($resolved[$j][$start]=='N')
							$color='red';
						if($color=='green')
							$green="<font color='green'>Matter that are resolved by showing help template<BR>";
						else
							$red="<font color='red'>Matter for which help template failed to solve problem</font><BR>";	
					}
					
						$template.="<tr><TD class=fieldsnew style='color:$color'>".$arr[$j][$start]."</td></tr>";
				}
				$template.="</table></td></tr><tr><td style='background:black;height:2px' colspan=4></td></tr>";
			}
			$j++;
		}
		$smarty->assign("MSG",$green.$red.$brown);
		$smarty->assign("template",$template);
		$smarty->assign("SHOW_DATE","NO");
		
		
		
		
	}
	if($Submit)
	{
		if($mon!="")
		{
			if($year=="")
				$year=2007;
			
			if($year!=date("Y") || $mon!=date("m"))
				$total=date("t",mktime(0,0,0,$mon,1,$year));
			else
				$total=date("d");


			$TYPE="DAYS";

			$sql="select *,count(*) as cnt,DAY(`DATE`) as dd from `FEEDBACK_RESULT` where MONTH(`DATE`)='$mon' and  YEAR(`DATE`)='$year' group by dd,category,resolved order by dd ASC";
			
		}
		elseif($year!="")
		{
			$TYPE="MONTHS";
		
			if($year!=date("Y"))
				$total=12;
			else
				$total=date("m");
		
			$sql="select *,count(*) as cnt,MONTH(`DATE`)as dd from `FEEDBACK_RESULT` where YEAR(`DATE`)='$year' group by dd,category,resolved  order by dd ASC";
			
		}	
		else
		{	
			$TYPE="YEAR";
			$total=date("Y")-2006;
				$sql="select *,count(*) as cnt ,YEAR(`DATE`) as dd from `FEEDBACK_RESULT` group by dd,category,resolved  order by dd ASC";	
			$start=2007;
			
		}
		$res=mysql_query_decide($sql,$db) ;	
		$actual_total=0;
		while($row=mysql_fetch_assoc($res))
		{
			$arr[$row['dd']][$row['CATEGORY']][$row['RESOLVED']]=$row['cnt'];
			
			
		}
		$smarty->assign("MSG","<font color=green>User satisfied with the help templates.</font><BR><font color=red>Futher help was asked after showing help template to user.</font><BR> <font color=brown>No help template was shown , direct submission of query.</font>");
		$temp=$start;
		for($i=0;$i<$total;$i++)
		{
			for($j=0;$j<count($category);$j++)
			{
				if($arr[$temp][$category[$j]]['N']=="")
					$arr[$temp][$category[$j]]['N']=0;

				if($arr[$temp][$category[$j]]['Y']=="")
					$arr[$temp][$category[$j]]['Y']=0;
	
				$vertical_total[$j]['Y']+=intval($arr[$temp][$category[$j]]['Y']);
				$vertical_total[$j]['N']+=intval($arr[$temp][$category[$j]]['N']);
				$horizontal_total[$temp]['Y']+=intval($arr[$temp][$category[$j]]['Y']);
				$horizontal_total[$temp]['N']+=intval($arr[$temp][$category[$j]]['N']);
				
			}
			$temp++;
		}
		
		$template="<table border=0 width=100% cellspacing=4 cellpadding=3><TR class=label><TD align=center width=30%>$TYPE</td> ";

		$temp=$start;
		
		for($i=0;$i<$total;$i++)
		{
			
			$template.="<TD align=center><b>&nbsp;&nbsp;&nbsp;&nbsp;".$temp."&nbsp;&nbsp;&nbsp;</b></td>";
			$temp++;
			
		}
		$template.="<Td align=center valign=middle><b>TOTAL</td></tr>";
		for($j=0;$j<count($category);$j++)
		{
			$temp=$start;
			
			if($TYPE=="DAYS")
				$template.="<tr><td class=label valign=middle ><a href='feedback_mis.php?cid=$cid&MONTH=$mon&YEAR=$year&CAT=$j'>".$category_name[$j]."</a></td>";
			else
				$template.="<tr><td class=label valign=middle >".$category_name[$j]."</td>";
			for($i=0;$i<$total;$i++)
			{
				if($j<=4)
				{
					$template.="<td class=fieldsnew  ><font color='green'>".$arr[$temp][$category[$j]]['Y']."</font> , <font color='red' align=center>".$arr[$temp][$category[$j]]['N']."</font></td>";
				}
				else
				{
					$template.="<td class=fieldsnew><font color='brown' align=center>".$arr[$temp][$category[$j]]['Y']."</font></td>";
				}

				$temp++;
			}
		if($j<=4)
			$template.="<td class=fieldsnew align=center valign=middle><font color='green'>".$vertical_total[$j]['Y']."</font> , <font color='red'>".$vertical_total[$j]['N']."</font></td></tr>";
		else
			$template.="<td class=fieldsnew align=center valign=middle><font color='brown'>".$vertical_total[$j]['Y']."</font></td></tr>";

		 $eachtotal.="&$j=".$vertical_total[$j]['Y'];
		}
		$template.="<tr class=label><td align=center><b>TOTAL</b></td>";
		$smarty->assign("TOTAL",count($category));
		$smarty->assign("eachtotal",$eachtotal);
		$temp=$start;

		for($i=0;$i<$total;$i++)
		{
			$template.="<td class=fieldsnew align=center valign=middle><font color='green'>".$horizontal_total[$temp]['Y']."</font> , <font color='red'>".$horizontal_total[$temp]['N']."</font></td>";
			$actual_totalq['N']+=intval($horizontal_total[$temp]['N']);
			$actual_totalq['Y']+=($horizontal_total[$temp]['Y']);
			$temp++;
		}
		$template.="<td class=fieldsnew align=center><font color='green'>".$actual_totalq['Y']."</font> , <font color='red'>".$actual_totalq['N']."</font></td></tr></table>";

		
		$smarty->assign("template",$template);
	}
	if(!isset($mon))	
		$mon=date("m");
	if(!isset($year))
		$year=date("Y");
	for($i=1;$i<=12;$i++)
	{
		if($i==$mon)
			$MONTH.="<option value=$i selected='selected'>$i</option>";
		else
			$MONTH.="<option value=$i >$i</option>";
	}
	
	for($i=2007;$i<=date("Y");$i++)
	{
		if($i==$year)
			$YEAR.="<option value=$i selected='selected'>$i</option>";
		else
			$YEAR.="<option value=$i>$i</option>";
	}
	$smarty->assign("action",$_SERVER['REQUEST_URI']);
	$smarty->assign("MONTH",$MONTH);
	$smarty->assign("YEAR",$YEAR);	
	$smarty->assign("cid",$cid);
	$smarty->display("success_story_count.htm");
}
else
	$smarty->display("jsconnectError.tpl");
?>
