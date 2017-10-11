<?php

include("connect.inc");
$db2=connect_master();
if(authenticated($checksum))
{
	$smarty->assign("checksum",$checksum);
        $user=getname($checksum);
        $privilage=getprivilage($checksum);
        $priv=explode("+",$privilage);
        $center=getcenter_for_operator($user);

	if(in_array('A',$priv))
	{

		if(!$submit)
		{
			$sql="select USERNAME,PROFILEID,STATUS,UPLOAD_DATE,UPLOADED_BY from billing.UPLOAD_MATRI_STATUS where VERIFIED <> 'Y'";

			$result = mysql_query_decide($sql,$db2) or die("error".mysql_error_js());

			//$row = mysql_fetch_array($result);
			$i=0;
			while($row = mysql_fetch_array($result))
			{
				$uploads[$i]['SNO'] = $i + 1;
				$uploads[$i]['USERNAME'] = $row['USERNAME'];
				$uploads[$i]['PROFILEID'] = $row['PROFILEID'];
				$uploads[$i]['STATUS'] = $row['STATUS'];
				$uploads[$i]['DATE'] = $row['UPLOAD_DATE'];
				$uploads[$i]['TARGET']= "/usr/local/matri_profiles/".$row['PROFILEID'].".doc";
				$uploads[$i]['UPLOADEDBY']= $row['UPLOADED_BY'];
				$i++;
		
			}
			//echo $uploads[4]['TARGET'];
			$smarty->assign("uploads",$uploads);
			$smarty->display("todownload.htm");

		}
		if($submit)
		{
			$date=date('Y-m-d');
			$date .= date('G-i-s');

			foreach($_POST as $var=>$value)
			{

				$temp = explode("-",$var);
				if($value=='A')
				{
					$tempV[] = $temp[0];
	
				}
			}
			if(is_array($tempV))
			{
				$verarr = "'".implode("','",$tempV)."'";
			}
			$verified_by = getname($checksum);
			if($verarr)
			{
				$sql1 = "update billing.UPLOAD_MATRI_STATUS set VERIFIED = 'Y', VERIFIED_DATE = '$date',VERIFIED_BY='$verified_by' where PROFILEID in ($verarr)";

				$result1 = mysql_query_decide($sql1,$db2) or die("update error".mysql_error_js());


			}

		$sql="select USERNAME,PROFILEID,STATUS,UPLOAD_DATE,UPLOADED_BY from billing.UPLOAD_MATRI_STATUS where VERIFIED <> 'Y'";
                                                                                                                             
		$result = mysql_query_decide($sql,$db2) or die("error".mysql_error_js());
                                                                                                                             
		$i=0;
		while($row = mysql_fetch_array($result))
		{
			$uploads[$i]['SNO'] = $i + 1;
			$uploads[$i]['USERNAME'] = $row['USERNAME'];
	        	$uploads[$i]['PROFILEID'] = $row['PROFILEID'];
		        $uploads[$i]['STATUS'] = $row['STATUS'];
		        $uploads[$i]['DATE'] = $row['UPLOAD_DATE'];
			$uploads[$i]['TARGET']= "/usr/local/matri_profiles/".$row['PROFILEID'].".doc";
			$uploads[$i]['UPLOADEDBY']= $row['UPLOADED_BY'];
		        $i++;
                                                                                                                             
		}
		//echo $uploads[4]['TARGET'];
		$smarty->assign("uploads",$uploads);
		$smarty->display("todownload.htm");

	}

}
else
{
        echo "You don't have permission to view this mis";
        die();
}
                                                                                                                             
}
else
{
        $smarty->display("jsconnectError.tpl");
}



?>
