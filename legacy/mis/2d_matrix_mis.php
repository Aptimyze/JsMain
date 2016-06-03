<?php
/*      Filename        :	2d_matrix_mis MIS.
*       Description     :  	shows the count of total and paid members in different quadrant of 2D MATRIX.
*       Created by      :       Puneet on 10 may 2006
*/

include("connect.inc");
$db=connect_misdb();
$db2=connect_master();
if(authenticated($cid))
{
	if($CMDGo)
	{
		$date=$year."-".$month."-".$day;	
		if($day!='ALL')
		{
			$sql="SELECT * FROM MIS.DATA_MATRIX_2D WHERE DATE='$date' ";
			if($gender=='M')
				$sql.=" AND GENDER='M'";
			elseif($gender=='F')
				$sql.=" AND GENDER='F'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			while($row=mysql_fetch_array($res))
			{
				$total[0]+=$row['T1_F1'];
				$total[1]+=$row['T1_F2'];
				$total[2]+=$row['T1_F3'];
				$total[3]+=$row['T2_F1'];
				$total[4]+=$row['T2_F2'];
				$total[5]+=$row['T2_F3'];
				$total[6]+=$row['T3_F1'];
				$total[7]+=$row['T3_F2'];
				$total[8]+=$row['T3_F3'];
				$total[9]+=$row['49'];
				$total[10]+=$row['48'];
				$total[11]+=$row['47'];
				$total[12]+=$row['46'];
				$total[13]+=$row['45'];
				$total[14]+=$row['44'];
				$total[15]+=$row['43'];
			}
			
			$sql="SELECT * FROM MIS.DATA_MATRIX_2D_FREE WHERE DATE='$date' ";
			if($gender=='M')
				$sql.=" AND GENDER='M'";
			elseif($gender=='F')
				$sql.=" AND GENDER='F'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			while($row=mysql_fetch_array($res))
			{
				$total_free[0]+=$row['T1_F1'];
				$total_free[1]+=$row['T1_F2'];
				$total_free[2]+=$row['T1_F3'];
				$total_free[3]+=$row['T2_F1'];
				$total_free[4]+=$row['T2_F2'];
				$total_free[5]+=$row['T2_F3'];
				$total_free[6]+=$row['T3_F1'];
				$total_free[7]+=$row['T3_F2'];
				$total_free[8]+=$row['T3_F3'];
				$total_free[9]+=$row['49'];
				$total_free[10]+=$row['48'];
				$total_free[11]+=$row['47'];
				$total_free[12]+=$row['46'];
				$total_free[13]+=$row['45'];
				$total_free[14]+=$row['44'];
				$total_free[15]+=$row['43'];
			}
			
			$total_paid[0]=$total[0]-$total_free[0];
			$total_paid[1]=$total[1]-$total_free[1];
			$total_paid[2]=$total[2]-$total_free[2];
			$total_paid[3]=$total[3]-$total_free[3];
			$total_paid[4]=$total[4]-$total_free[4];
			$total_paid[5]=$total[5]-$total_free[5];
			$total_paid[6]=$total[6]-$total_free[6];
			$total_paid[7]=$total[7]-$total_free[7];
			$total_paid[8]=$total[8]-$total_free[8];
			$total_paid[9]=$total[9]-$total_free[9];
			$total_paid[10]=$total[10]-$total_free[10];
			$total_paid[11]=$total[11]-$total_free[11];
			$total_paid[12]=$total[12]-$total_free[12];
			$total_paid[13]=$total[13]-$total_free[13];
			$total_paid[14]=$total[14]-$total_free[14];
			$total_paid[15]=$total[15]-$total_free[15];
			/**********/
		}
		else
		{
			$st_date=$year."-".$month."-01";
			$end_date=$year."-".$month."-31";
			
			$sql="SELECT * , DAYOFMONTH(DATE) as day FROM MIS.DATA_MATRIX_2D WHERE DATE BETWEEN '$st_date' AND '$end_date' ";
                        if($gender=='M')
                                $sql.=" AND GENDER='M'";
                        elseif($gender=='F')
                                $sql.=" AND GENDER='F'";
			
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        while($row=mysql_fetch_array($res))
                        {	
				$dd=$row['day']-1;
                                $total[$dd][0]+=$row['T1_F1'];
                                $total[$dd][1]+=$row['T1_F2'];
                                $total[$dd][2]+=$row['T1_F3'];
                                $total[$dd][3]+=$row['T2_F1'];
                                $total[$dd][4]+=$row['T2_F2'];
                                $total[$dd][5]+=$row['T2_F3'];
                                $total[$dd][6]+=$row['T3_F1'];
                                $total[$dd][7]+=$row['T3_F2'];
                                $total[$dd][8]+=$row['T3_F3'];
                                $total[$dd][9]+=$row['49'];
                                $total[$dd][10]+=$row['48'];
                                $total[$dd][11]+=$row['47'];
                                $total[$dd][12]+=$row['46'];
                                $total[$dd][13]+=$row['45'];
                                $total[$dd][14]+=$row['44'];
                                $total[$dd][15]+=$row['43'];
                        }
	
			$sql="SELECT * , DAYOFMONTH(DATE) as day FROM MIS.DATA_MATRIX_2D_FREE WHERE DATE BETWEEN '$st_date' AND '$end_date' ";
                        if($gender=='M')
                                $sql.=" AND GENDER='M'";
                        elseif($gender=='F')
                                $sql.=" AND GENDER='F'";
                                                                                                                             
                        $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        while($row=mysql_fetch_array($res))
                        {
				$dd=$row['day']-1;
                                $total_free[$dd][0]+=$row['T1_F1'];
                                $total_free[$dd][1]+=$row['T1_F2'];
                                $total_free[$dd][2]+=$row['T1_F3'];
                                $total_free[$dd][3]+=$row['T2_F1'];
                                $total_free[$dd][4]+=$row['T2_F2'];
                                $total_free[$dd][5]+=$row['T2_F3'];
                                $total_free[$dd][6]+=$row['T3_F1'];
                                $total_free[$dd][7]+=$row['T3_F2'];
                                $total_free[$dd][8]+=$row['T3_F3'];
                                $total_free[$dd][9]+=$row['49'];
                                $total_free[$dd][10]+=$row['48'];
                                $total_free[$dd][11]+=$row['47'];
                                $total_free[$dd][12]+=$row['46'];
                                $total_free[$dd][13]+=$row['45'];
                                $total_free[$dd][14]+=$row['44'];
                                $total_free[$dd][15]+=$row['43'];
                        }
			for($i=0;$i<31;$i++)
			{
				$total_paid[$i][0]=$total[$i][0]-$total_free[$i][0];
				$total_paid[$i][1]=$total[$i][1]-$total_free[$i][1];
				$total_paid[$i][2]=$total[$i][2]-$total_free[$i][2];
				$total_paid[$i][3]=$total[$i][3]-$total_free[$i][3];
				$total_paid[$i][4]=$total[$i][4]-$total_free[$i][4];
				$total_paid[$i][5]=$total[$i][5]-$total_free[$i][5];
				$total_paid[$i][6]=$total[$i][6]-$total_free[$i][6];
				$total_paid[$i][7]=$total[$i][7]-$total_free[$i][7];
				$total_paid[$i][8]=$total[$i][8]-$total_free[$i][8];
				$total_paid[$i][9]=$total[$i][9]-$total_free[$i][9];
				$total_paid[$i][10]=$total[$i][10]-$total_free[$i][10];
				$total_paid[$i][11]=$total[$i][11]-$total_free[$i][11];
				$total_paid[$i][12]=$total[$i][12]-$total_free[$i][12];
				$total_paid[$i][13]=$total[$i][13]-$total_free[$i][13];
				$total_paid[$i][14]=$total[$i][14]-$total_free[$i][14];
				$total_paid[$i][15]=$total[$i][15]-$total_free[$i][15];
			}
		}
		//print_r($total);
		
		$smarty->assign("total",$total);
		$smarty->assign("total_paid",$total_paid);
		$smarty->assign("total_free",$total_free);
		$smarty->assign("gender",$gender);
		$smarty->assign("date",$date);
		$smarty->assign("day",$day);
		$smarty->assign("month",$month);
		$smarty->assign("year",$year);
		$smarty->assign("flag",'1');
		for($i=0;$i<31;$i++)
                {
                        $ddarr[$i]=$i+1;
                }
		$smarty->assign("ddarr",$ddarr);

	}
	else
	{
		for($i=0;$i<10;$i++)
		{
			$yyarr[$i]=$i+2006;
		}
		for($i=1;$i<=12;$i++)
		{
			$mmarr[]=$i;
		}
		for($i=1;$i<=31;$i++)
		{
			$dayarr[]=$i;
		}
		$smarty->assign("dayarr",$dayarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("mmarr",$mmarr);
	}
			
	$smarty->assign("cid",$cid);
	$smarty->display("2d_matrix_mis.htm");
}
else
{
	$msg="Your session has been timed out<br>  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsconnectError.tpl");

}
?>
