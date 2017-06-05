<?php
/**
*       Filename        :       voucher_success_print.php
*       Description     :       Script to issue the printed Vouchers.
*       Created by      :       Tanu Gupta
*       Created on      :       19-03-2007
**/
include("connect.inc");
include ("../crm/display_result.inc");
if(authenticated($cid))
{
        $privilage=getprivilage($cid);
        $priv=explode("+",$privilage);
	if($id_str)
		$v_id=explode("^",$id_str);
	else
	{
		echo "Please select atleast one user";
		die;
	}
        if(in_array('VA',$priv))
	{	
		for($i=0;$i<count($v_id);$i++)
		{
			$sql="SELECT PROFILEID,OPTIONS_AVAILABLE,CITY_RES,PHONE_RES,PHONE_MOB,CONTACT,NAME_H,NAME_W FROM billing.VOUCHER_SUCCESSSTORY WHERE ID='$v_id[$i]' AND SELECTED NOT IN ('D','N')";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$profileid=$row["PROFILEID"];
				if($row['NAME_H'])
				$smarty->assign("name",$row['NAME_H']);
				elseif($row['NAME_W'])
				$smarty->assign("name",$row['NAME_W']);
				else
				$smarty->assign("name","");
				$sql="select USERNAME,GENDER from newjs.JPROFILE where PROFILEID=$profileid";
                                $res_jp=mysql_query_decide($sql);
                                if($row_jp=mysql_fetch_array($res_jp))
                                {
                                        if($row_jp["GENDER"]=='F')
                                        	$smarty->assign("name",$row['NAME_W']);
					else
						$smarty->assign("name",$row['NAME_H']);
                                }
				if($row['CITY_RES'])
				{
					$sqlcity="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$row[CITY_RES]'";
					$rescity=mysql_query_decide($sqlcity) or die("$sqlcity".mysql_error_js());
					if(mysql_num_rows($rescity))
					{
						$rowcity=mysql_fetch_assoc($rescity);
						$smarty->assign("city_res",$rowcity["LABEL"]);
					}
					else
					$smarty->assign("city_res","");
				}
				else
				$smarty->assign("city_res","");
				if($row['CONTACT'])
				$smarty->assign("contact",$row["CONTACT"]);
				else
				$smarty->assign("contact","");
				if($row["PHONE_RES"])
				$smarty->assign("phone_res",$row["PHONE_RES"]);
				else
				$smarty->assign("phone_res",$row["PHONE_MOB"]);
				$smarty->assign("pvoucher",$pvoucher);
				$smarty->assign("cid",$cid);
				$a.=$smarty->fetch("voucher_success_template.htm");
			}
		}
		echo $a;
	}
        else
        {
                echo "You don't have permission to Print the Vouchers.";
                die();
        }
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
