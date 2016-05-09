<?php
include("../jsadmin/connect.inc");
if($_SERVER["SERVER_ADDR"]=="192.168.2.220")
{
        $smarty->template_dir="/usr/local/apache/sites/jeevansathi.com/htdocs/jsadmin/templates";
        $smarty->compile_dir="/usr/local/apache/sites/jeevansathi.com/htdocs/jsadmin/templates_c";
}
if(authenticated($cid))
{
        if($SUBMIT)
        {
               if($val=="refund")
	                $ref_amount=$paidamount-$pricenew+$discount_new;
		$center=strtoupper(getcenter_for_walkin($user));
		$sql="SELECT NAME FROM incentive.BRANCHES order by NAME";
                $res=mysql_query_decide($sql) or die(mysql_error_js());
                $i=0;
                while($row=mysql_fetch_array($res))
                {
                        $dep_branch_arr[$i]=$row['NAME'];
                        $i++;
                }
                $dd_arr=explode("-",Date('Y-m-d'));
                $smarty->assign("DEP_DAY",$dd_arr[2]);
                $smarty->assign("DEP_MONTH",$dd_arr[1]);
                $smarty->assign("DEP_YEAR",$dd_arr[0]);
                $smarty->assign("dep_branch",$center);
                $smarty->assign("dep_branch_arr",$dep_branch_arr);

		$smarty->assign("REF_AMOUNT",$ref_amount);
		$smarty->assign("CID",$cid);
		$smarty->assign("pid",$pid);
		$smarty->assign("val",$val);
		$smarty->assign("user",$user);
		$smarty->assign("username",$username);
		$smarty->assign("mtype",$mtype);
		$smarty->assign("duration",$duration);
		$smarty->assign("serviceid",$serviceid);
		$smarty->assign("billid",$billid);
		$smarty->assign("link_msg",$link_msg);
		$smarty->assign("PAIDAMOUNT",$paidamount);
		$smarty->assign("DUEAMOUNT_NEW",$dueamount_new);
		$smarty->assign("PRICENEW",$pricenew);
		$smarty->assign("ENTRYDT",$entrydt);
		$smarty->assign("bank",create_dd($Bank,"Bank"));
		$smarty->display("makepaid_refund_paypart.htm");
	}
}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
                                                                                                 
}
?>
