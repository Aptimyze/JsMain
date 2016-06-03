<?php
include("connect.inc");

$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	//mysql_close($db2);
	if($CMDGo)
	{
		$smarty->assign("flag","1");

		$st_date=$yy."-".$mm."-01 00:00:00";
		$end_date=$yy."-".$mm."-31 23:59:59";

		$cgrparr=array('0-2','3-5','6-8','9-11','12-14','15-20','21-25','26-30','31-35','36-40','41-45','46-50','51-70','71-90','90+');

		$sql="CREATE TEMPORARY TABLE MISOLD.CONTACTS_TEMP1 (`ID` int(11) NOT NULL auto_increment,
								 `PROFILEID` int(11) NOT NULL default '0',
								 `NUM` int(11) NOT NULL default '0',
								 `GENDER` char(1) NOT NULL default '',
								 `HAVEPHOTO` char(1) NOT NULL default '',
								 PRIMARY KEY  (`ID`),
								 KEY `PROFILEID` (`PROFILEID`)
							  ) TYPE=MyISAM ";
		mysql_query_decide($sql,$db) or die("$sql.shiv".mysql_error_js($db));

		$sql="CREATE TEMPORARY TABLE MISOLD.CONTACTS_TEMP2 (`ID` int(11) NOT NULL auto_increment,
								 `PROFILEID` int(11) NOT NULL default '0',
								 `NUM` int(11) NOT NULL default '0',
								 `GENDER` char(1) NOT NULL default '',
								 `HAVEPHOTO` char(1) NOT NULL default '',
								 PRIMARY KEY  (`ID`),
								 KEY `PROFILEID` (`PROFILEID`)
							  ) TYPE=MyISAM ";
		mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));

		$sql="INSERT INTO MISOLD.CONTACTS_TEMP1 (NUM,PROFILEID,GENDER,HAVEPHOTO) SELECT COUNT(*) as cnt,c.SENDER,j.GENDER,j.HAVEPHOTO FROM newjs.CONTACTS c,newjs.JPROFILE j WHERE j.PROFILEID=c.SENDER AND j.ENTRY_DT BETWEEN '$st_date' AND '$end_date' GROUP BY c.SENDER";
		mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));

		$sql="INSERT INTO MISOLD.CONTACTS_TEMP2 (NUM,PROFILEID,GENDER,HAVEPHOTO) SELECT COUNT(*) as cnt,c.RECEIVER,j.GENDER,j.HAVEPHOTO FROM newjs.CONTACTS c,newjs.JPROFILE j WHERE j.PROFILEID=c.RECEIVER AND j.ENTRY_DT BETWEEN '$st_date' AND '$end_date' GROUP BY c.RECEIVER";
		mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));

		$sql="SELECT COUNT(*) as cnt , if(NUM<=2,'0-2',if(NUM<=5,'3-5',if(NUM<=8,'6-8',if(NUM<=11,'9-11',if(NUM<=14,'12-14',if(NUM<=20,'15-20',if(NUM<=25,'21-25',if(NUM<=30,'26-30',if(NUM<=35,'31-35',if(NUM<=40,'36-40',if(NUM<=45,'41-45',if(NUM<=50,'46-50',if(NUM<=70,'51-70',if(NUM<=90,'71-90','90+')))))))))))))) as contact_grp , GENDER, HAVEPHOTO FROM MISOLD.CONTACTS_TEMP1 WHERE 1 GROUP BY contact_grp, GENDER, HAVEPHOTO";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_array($res))
		{
			$contact_grp=$row['contact_grp'];
			$gender=$row['GENDER'];
			$havephoto=$row['HAVEPHOTO'];

			$i=array_search($contact_grp,$cgrparr);

			if($gender=='M')
				$j=0;
			else
				$j=1;

			if($havephoto=='N')
				$k=0;
			else
				$k=1;

			$scnt1[$i][$j][$k]+=$row['cnt'];
			$scnt2[$i][$j]+=$row['cnt'];
			$scnt3[$i][$k]+=$row['cnt'];
			$scnt4[$i]+=$row['cnt'];
		}

		$sql="SELECT COUNT(DISTINCT c.PROFILEID) as cnt , if(NUM<=2,'0-2',if(NUM<=5,'3-5',if(NUM<=8,'6-8',if(NUM<=11,'9-11',if(NUM<=14,'12-14',if(NUM<=20,'15-20',if(NUM<=25,'21-25',if(NUM<=30,'26-30',if(NUM<=35,'31-35',if(NUM<=40,'36-40',if(NUM<=45,'41-45',if(NUM<=50,'46-50',if(NUM<=70,'51-70',if(NUM<=90,'71-90','90+')))))))))))))) as contact_grp , c.GENDER, c.HAVEPHOTO FROM billing.PURCHASES b, MISOLD.CONTACTS_TEMP1 c WHERE b.PROFILEID=c.PROFILEID GROUP BY contact_grp, c.GENDER, c.HAVEPHOTO";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_array($res))
		{
			$contact_grp=$row['contact_grp'];
			$gender=$row['GENDER'];
			$havephoto=$row['HAVEPHOTO'];

			$i=array_search($contact_grp,$cgrparr);

			if($gender=='M')
				$j=0;
			else
				$j=1;

			if($havephoto=='N')
				$k=0;
			else
				$k=1;

			$spaidcnt1[$i][$j][$k]+=$row['cnt'];
			$spaidcnt2[$i][$j]+=$row['cnt'];
			$spaidcnt3[$i][$k]+=$row['cnt'];
			$spaidcnt4[$i]+=$row['cnt'];
		}

		$sql="SELECT COUNT(*) as cnt , if(NUM<=2,'0-2',if(NUM<=5,'3-5',if(NUM<=8,'6-8',if(NUM<=11,'9-11',if(NUM<=14,'12-14',if(NUM<=20,'15-20',if(NUM<=25,'21-25',if(NUM<=30,'26-30',if(NUM<=35,'31-35',if(NUM<=40,'36-40',if(NUM<=45,'41-45',if(NUM<=50,'46-50',if(NUM<=70,'51-70',if(NUM<=90,'71-90','90+')))))))))))))) as contact_grp , GENDER, HAVEPHOTO FROM MISOLD.CONTACTS_TEMP2 WHERE 1 GROUP BY contact_grp, GENDER, HAVEPHOTO";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_array($res))
		{
			$contact_grp=$row['contact_grp'];
			$gender=$row['GENDER'];
			$havephoto=$row['HAVEPHOTO'];

			$i=array_search($contact_grp,$cgrparr);

			if($gender=='M')
				$j=0;
			else
				$j=1;

			if($havephoto=='N')
				$k=0;
			else
				$k=1;

			$rcnt1[$i][$j][$k]+=$row['cnt'];
			$rcnt2[$i][$j]+=$row['cnt'];
			$rcnt3[$i][$k]+=$row['cnt'];
			$rcnt4[$i]+=$row['cnt'];
		}

		$sql="SELECT COUNT(DISTINCT c.PROFILEID) as cnt , if(NUM<=2,'0-2',if(NUM<=5,'3-5',if(NUM<=8,'6-8',if(NUM<=11,'9-11',if(NUM<=14,'12-14',if(NUM<=20,'15-20',if(NUM<=25,'21-25',if(NUM<=30,'26-30',if(NUM<=35,'31-35',if(NUM<=40,'36-40',if(NUM<=45,'41-45',if(NUM<=50,'46-50',if(NUM<=70,'51-70',if(NUM<=90,'71-90','90+')))))))))))))) as contact_grp , c.GENDER, c.HAVEPHOTO FROM billing.PURCHASES b, MISOLD.CONTACTS_TEMP2 c WHERE b.PROFILEID=c.PROFILEID GROUP BY contact_grp, c.GENDER, c.HAVEPHOTO";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_array($res))
		{
			$contact_grp=$row['contact_grp'];
			$gender=$row['GENDER'];
			$havephoto=$row['HAVEPHOTO'];

			$i=array_search($contact_grp,$cgrparr);

			if($gender=='M')
				$j=0;
			else
				$j=1;

			if($havephoto=='N')
				$k=0;
			else
				$k=1;

			$rpaidcnt1[$i][$j][$k]+=$row['cnt'];
			$rpaidcnt2[$i][$j]+=$row['cnt'];
			$rpaidcnt3[$i][$k]+=$row['cnt'];
			$rpaidcnt4[$i]+=$row['cnt'];
		}

		$smarty->assign("yy",$yy);
		$smarty->assign("mm",$mm);
		$smarty->assign("cgrparr",$cgrparr);
		$smarty->assign("scnt1",$scnt1);
		$smarty->assign("scnt2",$scnt2);
		$smarty->assign("scnt3",$scnt3);
		$smarty->assign("scnt4",$scnt4);
		$smarty->assign("spaidcnt1",$spaidcnt1);
		$smarty->assign("spaidcnt2",$spaidcnt2);
		$smarty->assign("spaidcnt3",$spaidcnt3);
		$smarty->assign("spaidcnt4",$spaidcnt4);
		$smarty->assign("rcnt1",$rcnt1);
		$smarty->assign("rcnt2",$rcnt2);
		$smarty->assign("rcnt3",$rcnt3);
		$smarty->assign("rcnt4",$rcnt4);
		$smarty->assign("rpaidcnt1",$rpaidcnt1);
		$smarty->assign("rpaidcnt2",$rpaidcnt2);
		$smarty->assign("rpaidcnt3",$rpaidcnt3);
		$smarty->assign("rpaidcnt4",$rpaidcnt4);
		$smarty->display("complex_contact_grp.htm");
	}
	else
	{
		for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}

		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("cid",$cid);
		$smarty->display("complex_contact_grp.htm");
	}
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
