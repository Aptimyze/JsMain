<?php
include("connect.inc");

$db=connect_misdb();
$db2=connect_master();
mysql_select_db("billing",$db);
if(authenticated($cid))
{
	if($CMDGo)
	{
		$smarty->assign("flag","1");
		$st_date=$year."-".$month."-".$day." 00:00:00";
		$end_date=$year2."-".$month2."-".$day2." 23:59:59";
		$sql="SELECT WALKIN, SUM( if( CUR_TYPE = 'RS', DISCOUNT, 0 ) ) AS RUPPESS, SUM( if( CUR_TYPE = 'DOL', DISCOUNT, 0 ) ) AS DOLLAR FROM `PURCHASES` WHERE DISCOUNT > 0 and ENTRY_DT between '$st_date' and '$end_date' ";
		if($with_renew_discount=='N')
                {
                        $sql.=" AND DISCOUNT_TYPE <>'1'";
                        $no_renew_discount=1;
                }
		$sql.=" GROUP BY WALKIN  order by RUPPESS DESC ";
		$res=mysql_query_decide($sql,$db) or die("$sql - ->".mysql_error_js($db));
		
		$j=1;
		while($row=mysql_fetch_assoc($res))
		{
			$template.="<tr class='fieldsnew'><TD align=center>$j</td><td align=center><a href='brief_discount.php?cid=$cid&st_date=".urlencode($st_date)."&end_date=".urlencode($end_date)."&agent=".$row["WALKIN"]."&no_renew_discount=".$no_renew_discount."'>".$row["WALKIN"]."</a></td><td align=center>".$row["RUPPESS"]."</td><td align=center>".$row["DOLLAR"]."</td></tr>";
			
			$j++;
		}
		
		
		$smarty->assign("template",$template);	
		$smarty->assign("arr",$arr);
		$smarty->assign("day",$day);
		$smarty->assign("month",$month);
		$smarty->assign("year",$year);
		$smarty->assign("day2",$day2);
		$smarty->assign("month2",$month2);
		$smarty->assign("year2",$year2);
		$smarty->assign("date1",my_format_date($day,$month,$year));
		$smarty->assign("date2",my_format_date($day2,$month2,$year2));
		$smarty->assign("cid",$cid);
		$smarty->display("discountmis.htm");
	}
	else
	{
		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
			
                }
                for($i=0;$i<10;$i++)
                {
                        $yyarr[$i]=$i+2006;
                }
		for($i=0;$i<31;$i++)
                {
                        $ddarr[$i]=$i+1;
                }



                $smarty->assign("ddarr",$ddarr);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		
		$smarty->assign("month",date("m"));
		$smarty->assign("year",date("Y"));
		$smarty->assign("day",date("d"));
		$smarty->assign("cid",$cid);
                $smarty->display("discountmis.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
