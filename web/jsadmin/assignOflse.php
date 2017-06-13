<?php
include_once("connect.inc");
$data=authenticated($cid);
if($data)
{
        if($Submit)
        {
		$sql_main="SELECT PROFILEID,SOURCE FROM newjs.JPROFILE WHERE USERNAME = '$assign_user'";
		$res_main=mysql_query_decide($sql_main) or die("$sql_main".mysql_error_js());
		if($row_main=mysql_fetch_array($res_main))
		{
			$pid=$row_main['PROFILEID'];
			$source=$row_main['SOURCE'];
		}
		if($source=='onoffreg')
		{
			$assign=0;
			$sql="SELECT EXECUTIVE FROM newjs.OFFLINE_REGISTRATION WHERE PROFILEID=$pid";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_array($res))
				$exe=$row['EXECUTIVE'];
			if($exe=='')
			{
				$sql="UPDATE newjs.OFFLINE_REGISTRATION SET EXECUTIVE='$ose' WHERE PROFILEID=$pid";
				$res2=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if($res2)
					$assign++;
			}
			if($assign)
				$smarty->assign("msg","Profile assigned.<br><a href=\"$SITE_URL/jsadmin/assignOflse.php?name=$user&cid=$cid\">Next</a>");
			else
				$smarty->assign("msg","Profile is already assigned to $exe.<br><a href=\"$SITE_URL/jsadmin/assignOflse.php?name=$user&cid=$cid\">Back</a>");
		}
		else
			$smarty->assign("msg","Either you have entered invalid username or the profile source is not onoffreg.<br><a href=\"$SITE_URL/jsadmin/assignOflse.php?name=$user&cid=$cid\">Back</a>");
	}		
	$name=getname($cid);
	$all_sql= "SELECT DISTINCT(OPERATOR) AS EXE FROM jsadmin.OFFLINE_ASSIGNED";
        $all_res= mysql_query_decide($all_sql) or die(mysql_error_js());
        while($all_row= mysql_fetch_array($all_res))
                $ofl_arr[]= $all_row['EXE'];
        $ofl_arr1=array('hojefa','priti','nisha.singh','neha.agarwal','shivangi.sinha','anjana','sulochana.gaikwad','tripti.daga','kavita.khambal','komal.tejpal','radhika.kulkarni','sapna.phule','moudipa','kavita.dhumal','hemali','sudipta','neha.gupta','rimpy.suri');
        for($i=0;$i<count($ofl_arr1);$i++)
        {
                if(!in_array($ofl_arr1[$i],$ofl_arr))
                        $ofl_arr[]=$ofl_arr1[$i];
        }
	$smarty->assign("name",$name);
        $smarty->assign("cid",$cid);
	$smarty->assign("ofl_arr",$ofl_arr);
        unset($Submit);
        $smarty->display("assignOflse.htm");
}
?>
