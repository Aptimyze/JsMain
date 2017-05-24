<?php
        include("connect.inc");
        $db=connect_db();
	$clicks = 0;
	/*
	LH: Live Help, 
	TM: Top Membership options, 
	TS: Top Success Stories, 
	TR: Top Registration, 
	SSS: Slider Success Stories, 
	SMP: Slider Million of profiles, 
	SSAS: Slider Safe and Secure, 
	SPM: Slider Paid Membership, 
	DSS: Descriptor Success Stories, 
	DMP: Descriptor Million of profiles, 
	DSAS: Descriptor Safe and Secure, 
	DPM: Desciptor Paid Membership, 
	L: Login, 
	FP: Forgot Password, 
	RL: Registration below Login, 
	BRB: Bottom Registration Banner, 
	BSS: Bottom Success Stories, 
	BM: Browse Matrimonial, 
	QS: Quick Search, 
	PS: Profile Search, 
	AS: Advance Search, 
	F: Footer
	*/
	if($link)
	{
		$sql = "SELECT * FROM HOMEPAGE_CLICK_TRACK WHERE DATE = now() AND LINK='$link'";
		$res = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		while($row = mysql_fetch_array($res))
		{
			$clicks = $row["CLICKS"];
		}
		if($clicks)
		{
			$clicks = $clicks+1;
			$sql="UPDATE HOMEPAGE_CLICK_TRACK SET CLICKS = $clicks WHERE LINK = '$link' AND DATE = now()";
			mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}
		else
		{
			$clicks = $clicks+1;
                        $sql="INSERT IGNORE INTO HOMEPAGE_CLICK_TRACK (LINK, CLICKS, DATE) VALUES ('$link', $clicks, now())";
                        mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}
	}
?>
