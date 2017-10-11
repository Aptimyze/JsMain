
<?php

/****************************************************************************************************************************
*	FILENAME           : showsmsdetails.php
*       INCLUDED           : connect.inc , display_result.inc
*       DESCRIPTION        : displays the list of all mobilenumbers from which the sms has been received
*       CREATED BY         : shobha
****************************************************************************************************************************/

	include("connect.inc");
	include("display_result.inc");

	$PAGELEN=50;
	$LINKNO=10;

	if(!$j )
		$j = 0;

	$sno=$j+1;

	if(authenticated($cid))
	{
		$username=getname($cid);

		$sql="SELECT COUNT(*) FROM Shobha.SMSPROMOTION ";
		$res=mysql_query($sql);
		$myrow = mysql_fetch_row($res);
                $TOTALREC = $myrow[0];

		$i = 0;		

		$sql1="SELECT * FROM Shobha.SMSPROMOTION LIMIT $j,$PAGELEN ";
		$result=mysql_query($sql1) or die("$sql".mysql_error());

		while($row=mysql_fetch_array($result))
		{	
			$details[$i]["SNO"]=$sno;	
			$details[$i]["MOBILENO"]=$row['MOBILENO'];
			
			$i++;
			$sno++;

		}
		if( $j )
        	        $cPage = ($j/$PAGELEN) + 1;
	        else
                	$cPage = 1;
                                                                                                                             
	        pagelink($PAGELEN,$TOTALREC,$cPage,$LINKNO,$cid,"showsmsdetails.php",'','');
	
		$no_of_pages=ceil($TOTALREC/$PAGELEN);
        
		$smarty->assign("COUNT",$TOTALREC);
	        $smarty->assign("CURRENTPAGE",$cPage);
	        $smarty->assign("NO_OF_PAGES",$no_of_pages);
	
		$smarty->assign("details",$details);
		$smarty->assign("cid",$cid);
		$smarty->assign("name",$name);
		$smarty->assign("username",$username);
		$smarty->display("showsmsdetails.htm");
	}
	else
	{
		$msg="Your session has been timed out<br>  ";
		$msg .="<a href=\"index.htm\">";
		$msg .="Login again </a>";
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");
	}
?>
