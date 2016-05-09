<?php
	include("connect.inc");
	
	$db=connect_db();
	
/*	$sql="select PROFILEID from JPARTNER where LAGE=0 and HAGE=0";
	$result=mysql_query_decide($sql) or die(mysql_error_js());
	
	while($row=mysql_fetch_array($result))
	{
		$sql="select AGE,GENDER from JPROFILE where PROFILEID=" . $row["PROFILEID"];
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		
		$myrow=mysql_fetch_array($res);
		$age=$myrow["AGE"];
		$gender=$myrow["GENDER"];
		
		if($age==0)
			echo "age is 0 for " . $row["PROFILEID"] . "\n";
			
		if($gender=='M')
        {
            if($age<25)
				$lage=18;
            else
				$lage=$age-7;
            $hage=$age;
        }
        else
        {
            $hage=$age+7;
            if($age<21)
				$lage=21;
            else
				$lage=$age;
        }
        
        $sql="update JPARTNER set LAGE='$lage', HAGE='$hage' where PROFILEID=" . $row["PROFILEID"];
        mysql_query_decide($sql) or die(mysql_error_js());
	}
*/	
	$sql="select PROFILEID from JPARTNER where LHEIGHT=0 and HHEIGHT=0";
	$result=mysql_query_decide($sql) or die(mysql_error_js());
	
	while($row=mysql_fetch_array($result))
	{
		$sql="select HEIGHT,GENDER from JPROFILE where PROFILEID=" . $row["PROFILEID"];
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		
		$myrow=mysql_fetch_array($res);
		$height=$myrow["HEIGHT"];
		$gender=$myrow["GENDER"];
		
		if($height==0)
			echo "height is 0 for " . $row["PROFILEID"] . "\n";
			
		if($gender=='M')
        {
            $hheight=$height;
            if($height>10)
                    $lheight=$height-10;
            else
                    $lheight=1;
        }
        else
        {
            $lheight=$height;
            if($height<=20)
                    $hheight=$height+10;
            else
                    $hheight=30;
        }

        $sql="update JPARTNER set LHEIGHT='$lheight', HHEIGHT='$hheight' where PROFILEID=" . $row["PROFILEID"];
        mysql_query_decide($sql) or die(mysql_error_js());
	}
?>
