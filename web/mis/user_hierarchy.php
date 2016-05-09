<?php
	function user_hierarchy($logged_in_username,$same_branch_required="",$checkStatus="",$all_agent="")
	{
		if($all_agent){
			if($all_agent=='ALL')
				$sql_unames ="SELECT USERNAME FROM jsadmin.PSWRDS WHERE (PRIVILAGE LIKE '%IUO%' OR PRIVILAGE LIKE '%IUI%') AND ACTIVE='Y' AND COMPANY='JS'";			
			else
				$sql_unames ="SELECT USERNAME FROM jsadmin.PSWRDS WHERE ACTIVE='Y' AND COMPANY='JS'";
		}
		else{	
		$sql = "SELECT CENTER,EMP_ID FROM jsadmin.PSWRDS WHERE USERNAME='$logged_in_username' AND COMPANY='JS'";
		if($checkStatus)
			$sql .=" AND ACTIVE='Y'";
		$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$row = mysql_fetch_array($res);
		$head_id = $row['EMP_ID'];
		$head_center = strtoupper($row['CENTER']);
		
		if($checkStatus)
			$sql_emp = "SELECT distinct(EMP_ID) FROM jsadmin.PSWRDS WHERE HEAD_ID='$head_id' AND COMPANY='JS' AND ACTIVE='Y'";
		else
			$sql_emp = "SELECT distinct(EMP_ID) FROM jsadmin.PSWRDS WHERE HEAD_ID='$head_id' AND COMPANY='JS'";
		$res_emp = mysql_query_decide($sql_emp) or die("$sql_emp".mysql_error_js());
		while($row_emp = mysql_fetch_array($res_emp))
		{
			if(0 == strstr($emp_id_str1, "'$row_emp[EMP_ID]'"))
				$emp_id_str1 .= "'$row_emp[EMP_ID]',";
		}

		$emp_id_str = $emp_id_str1."'$head_id'";

		$force_break_loop = 0;

		if($emp_id_str1)
		{
			while(1)
			{
				$emp_id_str1 = substr($emp_id_str1,0,strlen($emp_id_str1)-1);
				unset($emp_id_str2);

				if($checkStatus)
					$sql_emp_more = "SELECT distinct(EMP_ID) FROM jsadmin.PSWRDS WHERE HEAD_ID IN ($emp_id_str1) AND COMPANY='JS' AND ACTIVE='Y'";
				else
					$sql_emp_more = "SELECT distinct(EMP_ID) FROM jsadmin.PSWRDS WHERE HEAD_ID IN ($emp_id_str1) AND COMPANY='JS'";
				
				$res_emp_more = mysql_query_decide($sql_emp_more) or die($sql_emp_more.mysql_error_js());

				if(0 == mysql_num_rows($res))
					break;
				else
				{
					while($row_emp_more = mysql_fetch_array($res_emp_more))
					{
						if(0 == strstr($emp_id_str2,"'$row_emp_more[EMP_ID]'"))
							$emp_id_str2 .= "'$row_emp_more[EMP_ID]',";
					}

					if(!$emp_id_str2)
						break;

					$emp_id_str1 = $emp_id_str2;
					$emp_id_str = $emp_id_str2.$emp_id_str;
				}

				$force_break_loop++;
				if($force_break_loop > 15)
				{
					echo $force_break_loop;
					die;
				}
			}
		}
		$emp_id_array	=@explode(",",$emp_id_str);
		$emp_id_array	=array_unique($emp_id_array);
		$emp_id_str	=@implode(",",$emp_id_array); 

		$sql_unames = "SELECT USERNAME FROM jsadmin.PSWRDS WHERE EMP_ID IN ($emp_id_str)";
                if($checkStatus)
                        $sql_unames .=" AND ACTIVE='Y'";
		if($same_branch_required)
		{
			$sql_unames .= " UNION SELECT USERNAME FROM jsadmin.PSWRDS WHERE UPPER(CENTER)='$head_center' AND COMPANY='JS'";
                	if($checkStatus)
                        	$sql_unames .=" AND ACTIVE='Y'";
		}
		}

		$res_unames = mysql_query_decide($sql_unames) or die($sql_unames.mysql_error_js());
		while($row_unames = mysql_fetch_array($res_unames))
			$uname_arr[] = $row_unames['USERNAME'];

		$uname_str = "'".@implode("','",$uname_arr)."'";
		return $uname_str;
	}
?>
