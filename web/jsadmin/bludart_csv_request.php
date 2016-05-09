<?php

include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");

$db_master = connect_db();

if(authenticated($cid))
{
	if($delete=='delete')
	{
		for ($i=0; $i<count($_POST['checkbox']); $i++)
		{
			$ref=$_POST['checkbox'][$i];
			$refArr[]=$ref;

			$sql="UPDATE billing.BLUEDART_COD_REQUEST SET ACTIVE='N' WHERE REF_ID IN ('$ref')";
			mysql_query($sql,$db_master) or die(mysql_error($db_master));
		}
	}
	else if($delete=='send')
	{
		for ($i=0; $i<count($_POST['checkbox']); $i++)
		{
			$ref=$_POST['checkbox'][$i];
			$refArr[]=$ref;
		}	
		
		$ref_array=implode("','",$refArr);

		$sql="SELECT * FROM billing.BLUEDART_COD_REQUEST WHERE REF_ID IN ('$ref_array')";
		$res=mysql_query($sql,$db_master) or die(mysql_error($db_master));
		while($row=mysql_fetch_array($res))
		{
			$values[] = array("airway"=>$row['AIRWAY_NUMBER'],
					  "ref_id"=>$row['REF_ID'],
					  "amount"=>$row['TOTAL_AMOUNT'],
					  "discount"=>$row['DISCOUNT_AMNT'],
					  "address"=>$row['ADDRESS'],
					  "commnent"=>$row['COMMENTS'],
					  "entry_date"=>$row['ENTRY_DT'],
					  "phone_res"=>$row['PHONE_RES'],
					  "phone_mob"=>$row['PHONE_MOB'],
					  "email"=>$row['EMAIL'],
					  "username"=>$row['USERNAME'],
					  "name"=>$row['NAME'],
					  "profileid"=>$row['PROFILEID'],
					  "pincode"=>$row['PINCODE'],
					  "city"=>$row['CITY'],
					  "area"=>$row['AREA'],
					  "operator"=>$row['OPERATOR']
					  );
		}

		$sub="Jeevansathi.com BlueDart COD Request";
		$from='info@jeevansathi.com';

		if($whichMachine=='test')
		{
			$to='anurag.gautam@jeevansathi.com,vidushi@naukri.com';
			$cc='anurag.gautam@jeevansathi.com';
		}
		else
		{
			$to='shambhum@bluedart.com,vijayn@bluedart.com,dayald@bluedart.com,ramv@bluedart.com';
			$cc='ahujaj@bluedart.com,anurag.gautam@jeevansathi.com,aman.sharma@jeevansathi.com,rizwan@naukri.com,rajeev.kailkhura@naukri.com';
		}
		
		$tdy=date('d-m-Y');
		$smarty->assign('TODAY',$tdy);
		$smarty->assign("ROW",$values);
//		$htmmsg=$smarty->fetch("bluedart.htm");
		

		$header = "Airway Number".","."Request date".","."City".",".""."Pincode".","."Bluedart Center".","."Unique Jeevansathi Request ID".","."Name of Subscriber".","."User Name".","."Complete Address ".","."Landline Number".","."Mobile Number".","."Amount to be collected".","."Sales Executive Name";

                for($i=0;$i<count($values);$i++)
                {
		       $values[$i]['address']=str_replace("\r\n",' ',$values[$i]['address']);
                       $line=$values[$i]['airway'].",".$values[$i]['entry_date'].",".$values[$i]['city'].",".$values[$i]['pincode'].",".$values[$i]['area'].","."JSBD".$values[$i]['ref_id'].",".$values[$i]['name'].",".$values[$i]['username'].",".preg_replace('/[\$,]/',' ', $values[$i]['address']).",".$values[$i]['phone_res'].",".$values[$i]['phone_mob'].",".$values[$i]['amount'].",".$values[$i]['operator'];
                       $line = str_replace("\n",' ',$line);
                       $data .= trim($line)." \n";
	               $msg="$header\n$data";
                }
		
		$content="Hi,\n\nPlease find final Bluedart COD Request with attached CSV.\n\nRegards,\nTeam Jeevansathi";

		$headers ="Content-type: application/octet-stream";
		$headers.="Content-Disposition: attachment; filename=bluedart.csv";
		$headers.="Pragma: no-cache";
		$headers.="Expires: 0";
		
		send_email($to,$content,$sub,$from,$cc,'',$msg,$headers,'bluedart.csv','','1','');

		$sql_up="UPDATE billing.BLUEDART_COD_REQUEST SET SENT_MAIL='Y' WHERE REF_ID IN ('$ref_array')";
		mysql_query($sql_up,$db_master) or die(mysql_error($db_master));
	}
	
	$name= getname($cid);
        $smarty->assign("name",$name);
        $smarty->assign("cid",$cid);
        $smarty->assign("PID",$pid);
        $smarty->display("delete_bluedart_request.htm");
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
