<?
include("connect.inc");
$db=connect_misdb();
mysql_select_db("newjs",$db);
//$mon="3";
//$year="2007";
$data=authenticated($cid);
$start=1;

//Delete reason array
$del_reason=array("I found my match on Jeevansathi.com"=>1,"I found my match on another matrimonial site"=>2,"I found my match elsewhere"=>3,"I am unhappy with Jeevansathi.com services"=>4,"Other reasons"=>5);

//Creating a array by reversing key and val of above array	
foreach($del_reason as $key=>$val)
{
	$refer[$val]=$key;
}

if(isset($data))
{if($MONTH!="")
	{
		$catname=$refer[$CAT];

		$template="<table border=0 width=100%  cellpadding=3><TR><td align=center class=label><a  href='delete_profile_count.php?Submit=Go&cid=$cid&mon=$MONTH&year=$YEAR'>Get back to previous result</a><tr><TD	align=center class=label><b>$catname</b></td></tr>";
		$template.="<tr><TD align=center class=label><b>Duration: $MONTH - $YEAR</b></td></tr>";
		$template.="<tr><Td><table border=0 width=100% cellpadding=3><tr><Td align=center class=label>DAYS</td><td class=label align=center><table border=0 align=center width=100%><tr><TD class=label> Username</td><td class=label>SPECIFY REASON</td></tr></table></td></tr>";
		if($year!=date("Y") || $mon!=date("m"))
			$total=date("t",mktime(0,0,0,$mon,1,$year));
		else
			$total=date("d");
		
		$j=1;
		$sql="select USERNAME,DAY(`PROFILE_DEL_DATE`) as dd,SPECIFIED_REASON  from `PROFILE_DEL_REASON` where MONTH(`PROFILE_DEL_DATE`)='$MONTH' and  YEAR(`PROFILE_DEL_DATE`)='$YEAR' and DEL_REASON='".$catname."' order by dd ASC";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$i=1;
		while($row=mysql_fetch_assoc($res))
		{
			
			$reason[$row['dd']][]=$row['SPECIFIED_REASON'];
			$username[$row['dd']][]=$row['USERNAME'];
		}
		
		
		for($i=0;$i<=$total;$i++)
		{
			if($username[$j][0]!="")
			{
				

				$template.="<tr><td align=center class=label>$j</td><td><table border=0 align=center width=100%>";
				
				for($start=0;$start<count($reason[$j]);$start++)
				{
					$sql_profile="select PROFILEID from newjs.JPROFILE where USERNAME='".$username[$j][$start]."'";
					$res_profile=mysql_query_decide($sql_profile,$db) or die(mysql_error_js());
					$row=mysql_fetch_row($res_profile);
					$profileid=$row[0];
					$template.="<tr><TD class=fieldsnew  width=20%>&nbsp;&nbsp;&nbsp;&nbsp;</a>".$username[$j][$start]."</a>&nbsp;&nbsp;&nbsp;&nbsp;<BR><a href='#' onclick=\"MM_openBrWindow('../jsadmin/edit_details.php?cid=$cid&SHOW=Y&pid=$profileid&user=".$username[$j][$start]."','udetails','width=640,height=480,scrollbars=yes'); return false;\"            >Show  User Details <BR><a target='_blank' href='#' onclick=\"MM_openBrWindow('../jsadmin/showstat.php?cid=$cid&profileid=$profileid','UserStat','width=800,height=600,scrollbars=yes');return false;\">Show Statistics</a></td><td class=fieldsnew align=left valign=top width=80%>".$reason[$j][$start]."</td></tr>";
				}
				$template.="</table></td></tr><tr><td style='background:black;height:2px' colspan=4></td></tr>";
			}
			$j++;
		}
		//$smarty->assign("MSG",$green.$red.$brown);
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

			$sql="select count(*) as cnt,DAY(`PROFILE_DEL_DATE`) as dd,DEL_REASON  from `PROFILE_DEL_REASON` where MONTH(`PROFILE_DEL_DATE`)='$mon' and  YEAR(`PROFILE_DEL_DATE`)='$year' group by dd,DEL_REASON order by dd ASC";
			
		}
		elseif($year!="")
		{
			$TYPE="MONTHS";

                        if($year!=date("Y") )
                                $total=12;
                        else
                                $total=date("m");
			
	                $sql="select count(*) as cnt,MONTH(`PROFILE_DEL_DATE`)as mm,DEL_REASON  from `PROFILE_DEL_REASON` where YEAR(`PROFILE_DEL_DATE`)='$year' group by mm,DEL_REASON  order by mm ASC";
			
		}	
		else
		{	
			$TYPE="YEAR";
			$total=date("Y")-2002;
			 $sql="select count(*) as cnt ,YEAR(`PROFILE_DEL_DATE`) as yy,DEL_REASON  from `PROFILE_DEL_REASON` group by yy,DEL_REASON order by yy ASC";			
			$start=2003;
		//	$percentage[2003]=0;
		}
		

		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());	
		
		//Saving the total on the basis of delete profile reason
		$horizontal_total=array("1"=>0,"2"=>0,"3"=>0,"4"=>0,"5"=>0);
		
		$actual_total=0;	
		
		while($row=mysql_fetch_row($res))
		{
			$temp_res=$row[2];
			$temp_res=$del_reason[$temp_res];
			$arr[$row[1]][$temp_res]=$row[0];
			$horizontal_total[$temp_res]+=intval($row[0]);
			$vertical_total[$row[1]]+=intval($row[0]);
			$actual_total+=intval($row[0]);
			
		}
		
		$temp=$start;
		
		for($i=0;$i<$total;$i++)
		{
			$back=$temp-1;
			for($j=1;$j<=5;$j++)
			{
				
				if($arr[$temp][$j]=="")
					$arr[$temp][$j]=0;
				if($i!=0)
				{
					if($arr[$back][$j]!=0)
					{
						$percentage[$temp][$j]=round((($arr[$temp][$j]-$arr[$back][$j])*100)/$arr[$back][$j],2);
						if($percentage[$temp][$j]<0)
							$percentage[$temp][$j]="<img src=images/down.gif><BR><font color=red>".$percentage[$temp][$j]."%</font>";
						elseif($percentage[$temp][$j]>0)
							$percentage[$temp][$j]="<img src=images/up.gif><BR><font color=green>".$percentage[$temp][$j]."%</font>";
					}
					if(strstr($percentage[$temp][$j],"images")=="")
						 $percentage[$temp][$j]="<img src=images/none.gif><BR><font color=grey>0%</font>";
				}
				else
				{ 
					$percentage[$temp][$j]="<img src=images/none.gif><BR><font color=grey>0%</font>";
				}
			}
			$temp++;
		}
		
		//Storing percentage for vertical total , since horizontal total will not make that difference
		$temp=$start;
		for($i=2;$i<=$total;$i++)
		{
			$back=$i-1;
			
			if($vertical_total[$back]!=0)
			{
				$var_percent[$i]=round((($vertical_total[$i]-$vertical_total[$back])*100)/$vertical_total[$back],2);
				if($var_percent[$i]<0)
					$var_percent[$i]="<img src=images/down.gif><BR><font color=red>$var_percent[$i]%</font>";
				if($var_percent[$i]>0)
					$var_percent[$i]="<img src='images/up.gif'><BR><font color=green>$var_percent[$i]%</font>";
			}
			 if(strstr($var_percent[$back],"images")=="")
				$var_percent[$back]="<img src='images/none.gif'><BR><font color=grey>0%</font>";
		}
		$var_percent[1]="<img src='images/none.gif'><BR><font color=grey>0%</font>";
		$var_percent[$total]="<img src='images/none.gif'><BR><font color=grey>0%</font>";
		
				
				
		
	
		$temp=$start;
		$template="<table width=100% border=0><TR><TD>";
		$template.="<table width=100% border=0><TR class=label><TD><BR>$TYPE</td>";
		for($i=0;$i<$total;$i++)
		{
			$template.="<TD align=center><b>".$temp."</b></td>";
			$temp++;
		}
			$template.="<TD class=label align=center><b>Total</b></td></tr>";

		for($j=1;$j<=5;$j++)
		{
			$temp=$start;
			if($TYPE=='DAYS' && $j==4)
				$template.="<TR><td class=label valign=middle><a href='delete_profile_count.php?cid=$cid&MONTH=$mon&YEAR=$year&CAT=$j'>".$refer[$j]."</a></td>";
			else
				$template.="<TR><td class=label valign=middle>".$refer[$j]."</td>";
			for($i=0;$i<$total;$i++)
			{
				$template.="<td align=center><table cellspacing=0 cellpadding=4 border=0 width=100% align=center><TR><TD class=fieldsnew align=center>".$arr[$temp][$j]."</td></tr><tr><TD class=fieldsnew align=center>".$percentage[$temp][$j]."</td></tr></table></td>";
				$temp++;
			}
			$template.="<TD class=fieldsnew valign=top align=center>".$horizontal_total[$j]."</td></tr>";
		}
			$template.="<TR><TD colspan=30 style='background:#FFFFFF'>&nbsp;</td></tr><TR><TD class=label><B>Total</b></td>";
			
			
			for($i=1;$i<=$total;$i++)
			{	
				if(!isset($vertical_total[$i]))
					$vertical_total[$i]=0;
				$template.="<td class=fieldsnew align=center valign=middle>".$vertical_total[$i]."<BR>$var_percent[$i]</td>";
		
			}
				
	$template.="<TD class=fieldsnew align=center valign=top>$actual_total</td></tr></table>";	
				
		
		$smarty->assign("template",$template);
	}

	if(!isset($year))
		$year=date("Y");
	
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
	$smarty->assign("action",$_SERVER['REQUEST_URI']);
	$smarty->assign("MONTH",$MONTH);
	$smarty->assign("YEAR",$YEAR);	
	$smarty->assign("cid",$cid);
	$smarty->display("success_story_count.htm");
}
else
	$smarty->display("jsconnectError.tpl");
