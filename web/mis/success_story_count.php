<?
include("connect.inc");
$db=connect_misdb();
mysql_select_db("newjs");
//$mon="3";
//$year="2007";
$data=authenticated($cid);
$start=1;

if(isset($data))
{
	
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

			$sql="select count(*) as cnt,DAY(`DATETIME`) as dd from `SUCCESS_STORIES` where MONTH(`DATETIME`)='$mon' and  YEAR(`DATETIME`)='$year' group by dd order by dd ASC";
			
		}
		elseif($year!="")
		{
			$TYPE="MONTHS";

                        if($year!=date("Y"))
                                $total=12;
                        else
                                $total=date("m");

	                $sql="select count(*) as cnt,MONTH(`DATETIME`)as mm from `SUCCESS_STORIES` where YEAR(`DATETIME`)='$year' group by mm order by mm ASC";
			
		}	
		else
		{	
			$TYPE="YEAR";
			$total=date("Y")-2002;
			 $sql="select count(*) as cnt ,YEAR(`DATETIME`) as yy from `SUCCESS_STORIES` group by yy order by yy ASC";			
			$start=2003;
			
		}
		$res=mysql_query_decide($sql);	
		$actual_total=0;
		while($row=mysql_fetch_row($res))
		{
			$arr[$row[1]]=$row[0];	
			$actual_total+=intval($row[0]);
		}
		$temp=$start;
		for($i=0;$i<$total;$i++)
		{
		   if(!isset($arr[$temp]))
			$arr[$temp]=0;
			if($i!=0)
			{
				$back=$temp-1;
				if($arr[$back]!=0)
				{
					$percentage[$temp]=round((($arr[$temp]-$arr[$back])*100)/$arr[$back],2);
					if($percentage[$temp]<0)
						$percentage[$temp]="<img src=images/down.gif><BR><font color=red>".$percentage[$temp]."%</font>";
					elseif($percentage[$temp]>0)
						$percentage[$temp]="<img src=images/up.gif><BR><font color=green>".$percentage[$temp]."%</font>";
						
				}
				 if(strstr($percentage[$temp],"images")=="")
					$percentage[$temp]="<img src=images/none.gif><BR><font color=grey>0%</font>";	
			}
			else
				$percentage[$temp]="<img src=images/none.gif><BR><font color=grey>0%</font>";			
	           $temp++;
		}

                $temp=$start;
                $template="<table width=100% border=0><TR><TD>";
                $template.="<table width=100% border=0><TR class=label><TD><br>$TYPE</td>";
                for($i=0;$i<$total;$i++)
                {
                        $template.="<TD align=center valign=Middle><B>".$temp."</b></td>";
                        $temp++;
                }
		$template.="<TD class=label valign=middle align=center><b>Total</b></td></tr>";
		$temp=$start;
		$template.="<TR><TD class='label'> <b>Total</b> <BR> % Difference</td>";
		for($i=0;$i<$total;$i++)
		{
			$template.="<td><table cellspacing=0 cellpadding=4 border=0 width=100%><TR><TD class=fieldsnew valign=top align=center>".$arr[$temp]."</td></tr><tr><TD class=fieldsnew valign=top align=center>".$percentage[$temp]."</td></tr></table></td>";
			$temp++;
		}
		$template.="<TD class='fieldsnew' valign=top>$actual_total</td></tr></table>";
	
                
                                

		/*$temp=$start;
		$template="<table cellspacing=1 cellpadding=10 border=1 align=center style='border-bottom-style : groove; border-left-style : groove; border-right-style : groove; border-top-style : groove;'><TR>";
		$template.="<TD valign=top><table cellspacing=0 cellpadding=10 border=0 align=center  style='border-bottom-style : groove; border-left-style : groove; border-right-style : groove; border-top-style : groove;'><TR><td valign=top>$TYPE</td></tr><tr><td valign=top>Total</td></tr><tr><Td valign=top>% gain</td></tr></table></td>";
		for($i=0;$i<$total;$i++)
		{
			$template.="<td valign=top><table cellspacing=0 cellpadding=10 border=0 align=center style='border-bottom-style : groove; border-left-style : groove; border-right-style : groove; border-top-style : groove;'><TR><td valign=top>$temp</td></tr><tr><TD valign=top>$arr[$temp]</td></tr><tr><td valign=top>$percentage[$temp]</td></tr></table></td>";
			$temp++;
		}
			$template.="</tr></table>";*/
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
