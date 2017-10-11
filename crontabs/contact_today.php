<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

ini_set(max_execution_time,0);
ini_set(memory_limit,-1);
ini_set(mysql.connect_timeout,-1);
ini_set(default_socket_timeout,259200); // 3 days
ini_set(log_errors_max_len,0);

$flag_using_php5=1;
include("connect.inc");

$db2=connect_slave81();
//$db2=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);

$ts=time();
$ts-=24*60*60;
$today=date("Y-m-d",$ts);

$sql="CREATE TEMPORARY TABLE  test.CONTACTS_TEMP_ONE_DAY (`SENDER` int(11) unsigned NOT NULL default '0',`RECEIVER` int(11) unsigned NOT NULL default '0',`TYPE` char(1) NOT NULL default '', KEY `IND2` (`RECEIVER`,`TYPE`),KEY `IND3` (`SENDER`,`TYPE`))";
mysql_query($sql,$db2) or logError($sql,$db2);


$mysqlObj=new Mysql;

for($i=0;$i<$noOfActiveServers;$i++)
{
	$tempDbName=$slave_activeServers[$i];
	$myDb=$mysqlObj->connect($tempDbName);
	$sql="SELECT SENDER,RECEIVER,TYPE FROM newjs.CONTACTS,newjs.PROFILEID_SERVER_MAPPING WHERE newjs.CONTACTS.TIME BETWEEN '$today 00:00:00' AND '$today 23:59:59' AND newjs.CONTACTS.SENDER=newjs.PROFILEID_SERVER_MAPPING.PROFILEID AND newjs.PROFILEID_SERVER_MAPPING.SERVERID='$i'";
	$res=$mysqlObj->executeQuery($sql,$myDb);
	while($row=$mysqlObj->fetchAssoc($res))
	{
		$sql_insert="INSERT INTO test.CONTACTS_TEMP_ONE_DAY(SENDER,RECEIVER,TYPE) VALUES('$row[SENDER]','$row[RECEIVER]','$row[TYPE]')";
		mysql_query($sql_insert,$db2) or logError(sql,$db2);
	}
	unset($myDb);
	unset($tempDbName);
}

//test
//$today="2008-02-19";
//test

$sql="SELECT  PROFILEID,SENDER,RECEIVER,GENDER FROM newjs.JPROFILE J , test.CONTACTS_TEMP_ONE_DAY C WHERE J.PROFILEID=C.RECEIVER AND TYPE='A'";
$res=mysql_query($sql,$db2) or logError($sql,$db2);
while($row=mysql_fetch_array($res))
{
	$acc[$row['GENDER']][$row['PROFILEID']][]=$row['SENDER'];

}

$sql="SELECT  PROFILEID,SENDER,RECEIVER,GENDER FROM newjs.JPROFILE J , test.CONTACTS_TEMP_ONE_DAY C WHERE J.PROFILEID=C.SENDER AND TYPE='A'";
$res=mysql_query($sql,$db2) or logError($sql,$db2);
while($row=mysql_fetch_array($res))
{
        $acc[$row['GENDER']][$row['PROFILEID']][]=$row['RECEIVER'];


}


$uniquef[0]=0;
$uniquem[0]=0;
$uniquemarr=0;
$i=0;

$UMA=0;
$UFA=0;
$UMP=0;

if(is_array($acc))
{
	//Getting Unique female in acceptance state
	foreach($acc['F'] as $key=>$val)
	{
	        if(in_array($key,$uniquef))
	        {}
	        else
	                $uniquef[]=$key;
	
	}
	unset($uniquef[0]);
	
	//Getting Unique male in acceptance state
	foreach($acc['M'] as $key=>$val)
	{
	        if(in_array($key,$uniquem))
	        {}
	        else
	                $uniquem[]=$key;
	
	}
	unset($uniquem[0]);
	
	$i=0;
	//Getting unique acceptance possible
	/* $acc -> Array containing unique male and female with acceptance 
		$ume -> Stores the location at which male profileid is saved(vertical way)
		$ufe --> Stores the location at which female position can be identified(horizontal way)
		$vc -->Storing the horizontal score, helps in knowing the total acceptance made by the male.
		$pos--> if the female is accepted by more than on male , then to whom male this female accepted before, is identified by this position array
		
	*/
	$ume['test']='test';
	foreach($acc['M'] as $key=>$val)
	{
		if(!in_array($key,$ume))
		{
			$ume[$i]=$key;
			if($vc[$i]=="")
			$vc[$i]=0;
			for($k=0;$k<count($val);$k++)
			{
				if(!is_array($ufe))
				{
					$ufe[0]=$val[$k];
					$vc[$i]=1;
					$pos[$val[$k]]=$i;
				}
				else
				{
					if(!in_array($val[$k],$ufe))
					{
		
						$ufe[]=$val[$k];
						$pos[$val[$k]]=$i;
						$vc[$i]=$vc[$i]+1;
					}
					else
					{
						$position=$pos[$val[$k]];
					
						if($vc[$position]>1)
						{
							$vc[$position]-=1;
							$pos[$val[$k]]=$i;
							if($vc[$i]=="")
								$vc[$i]=1;
							else
								$vc[$i]+=1;
						}
					}
				}
			}
			$i++;
		}
	}$i=0;
	$value=0;
	
	foreach($vc as $key=>$val)
	{
		if($val>=1)
			$value+=1;
	}
	
	$UMA=count($uniquem);
	$UFA=count($uniquef);
	$UMP=$value*2;
	

	
}


$sql="SELECT COUNT(*) as cnt,GENDER,TYPE FROM newjs.JPROFILE J , test.CONTACTS_TEMP_ONE_DAY C WHERE J.PROFILEID=C.RECEIVER AND TYPE IN ('A','D') GROUP BY GENDER,TYPE";
$res=mysql_query($sql,$db2) or logError($sql,$db2);

while($row=mysql_fetch_array($res))
{
	$gender=$row['GENDER'];
	$type=$row['TYPE'];
	if($gender=='M')
	{
		if($type=='A')
			$macnt=$row['cnt'];
		elseif($type=='D')
			$mdcnt=$row['cnt'];
	}
	elseif($gender=='F')
	{
		if($type=='A')
			$facnt=$row['cnt'];
		elseif($type=='D')
			$fdcnt=$row['cnt'];
	}
}

$sql="SELECT COUNT(*) as cnt,GENDER,TYPE FROM newjs.JPROFILE J , test.CONTACTS_TEMP_ONE_DAY C WHERE J.PROFILEID=C.SENDER AND TYPE IN ('I','C') GROUP BY GENDER,TYPE";
$res=mysql_query($sql,$db2) or logError($sql,$db2);
while($row=mysql_fetch_array($res))
{
	$gender=$row['GENDER'];
	$type=$row['TYPE'];
	if($gender=='M')
	{
		if($type=='I')
			$micnt=$row['cnt'];
		elseif($type=='C')
			$mccnt=$row['cnt'];
	}
	elseif($gender=='F')
	{
		if($type=='I')
			$ficnt=$row['cnt'];
		elseif($type=='C')
			$fccnt=$row['cnt'];
	}
}
//========== Changes Made By Shobha on 2005.12.27 ================\\
// added query to find unique initial count
//================================================================\\

$sql="SELECT COUNT( DISTINCT SENDER ) as cnt, GENDER FROM newjs.JPROFILE J , test.CONTACTS_TEMP_ONE_DAY C WHERE J.PROFILEID = C.SENDER AND TYPE = 'I' GROUP BY GENDER";
$res=mysql_query($sql,$db2) or logError($sql,$db2);

while($row=mysql_fetch_array($res))
{
	$gender=$row['GENDER'];
	if($gender=='M')
        {
		$muicnt = $row['cnt']; // unique initial count for male
	}
	elseif($gender=='F')
	{
		$fuicnt=$row['cnt']; // unique initial count for female
	}
}

//========== Changes Made By Puneet on 2006.1.23 ================\\
// added query to find unique receivers initial contact
//================================================================\\

$sql="SELECT COUNT( DISTINCT RECEIVER ) as cnt, GENDER FROM newjs.JPROFILE J , test.CONTACTS_TEMP_ONE_DAY C WHERE J.PROFILEID = C.RECEIVER AND TYPE = 'I' GROUP BY GENDER";
$res=mysql_query($sql,$db2) or logError($sql,$db2);
while($row=mysql_fetch_array($res))
{
        $gender=$row['GENDER'];
        if($gender=='M')
        {
                $muricnt = $row['cnt']; // unique receivers initial count for male
        }
        elseif($gender=='F')
        {
                $furicnt=$row['cnt']; // unique receivers initial count for female
        }
}

//=================Code added by Sriram to store same caste/community contacts(initiated)=======================//
//========================================8th April 2007=======================================================//

$sql_create = "CREATE TEMPORARY TABLE test.TEMP_CAST_COMM
		(
			GENDER CHAR(1) NOT NULL,
			SENDER INT UNSIGNED NOT NULL,
			RECEIVER INT UNSIGNED NOT NULL,
			SENDER_CASTE SMALLINT(3) NOT NULL,
			RECEIVER_CASTE SMALLINT(3) NOT NULL,
			SENDER_MTONGUE TINYINT(3) NOT NULL,
			RECEIVER_MTONGUE TINYINT(3) NOT NULL,
			INDEX(`SENDER`),
			INDEX(`RECEIVER`)
		)
		";
mysql_query($sql_create,$db2) or logError($sql_create, $db2);

$sql_ins_cc = "INSERT INTO test.TEMP_CAST_COMM(GENDER,SENDER,RECEIVER,SENDER_CASTE,SENDER_MTONGUE) SELECT j.GENDER, c.SENDER, c.RECEIVER, j.CASTE, j.MTONGUE FROM newjs.JPROFILE j , CONTACTS_TEMP_ONE_DAY c WHERE j.PROFILEID = c.SENDER AND c.TYPE = 'I'";
mysql_query($sql_ins_cc,$db2) or logError($sql_ins_cc,$db2);

$sql_upd_cc = "UPDATE test.TEMP_CAST_COMM tcc, JPROFILE j SET tcc.RECEIVER_CASTE = j.CASTE, tcc.RECEIVER_MTONGUE = j.MTONGUE WHERE tcc.RECEIVER = j.PROFILEID";
mysql_query($sql_upd_cc,$db2) or logError($sql_upd_cc,$db2);

$sql = "SELECT GENDER, COUNT(*) AS COUNT FROM test.TEMP_CAST_COMM WHERE SENDER_CASTE = RECEIVER_CASTE GROUP BY GENDER";
$res = mysql_query($sql,$db2) or logError($sql,$db2);

while($row = mysql_fetch_array($res))
{
        $gender=$row['GENDER'];
        if($gender=='M')
                $msccnt = $row['COUNT']; //count for same caste contacts intiated by males.
        elseif($gender=='F')
                $fsccnt=$row['COUNT']; //count for same caste contacts initiated by females.
	
}

$sql = "SELECT GENDER, COUNT(*) AS COUNT FROM test.TEMP_CAST_COMM WHERE SENDER_MTONGUE = RECEIVER_MTONGUE GROUP BY GENDER";
$res = mysql_query($sql,$db2) or logError($sql,$db2);

while($row = mysql_fetch_array($res))
{
        $gender=$row['GENDER'];
        if($gender=='M')
                $msmcnt = $row['COUNT']; //count for same mtongue contacts intiated by males.
        elseif($gender=='F')
                $fsmcnt=$row['COUNT']; //count for same mtongue contacts initiated by females.
}

//=================End of - Code added by Sriram to store same caste/community contacts(initiated)=======================//
//=================================================8th April 2007=======================================================//

/***********Stores temporary contacts made/delivered***************/
//Temporary contacts made
$sql = "SELECT COUNT(1) cntTemp FROM newjs.CONTACTS_TEMP WHERE newjs.CONTACTS_TEMP.TIME BETWEEN '$today 00:00:00' AND '$today 23:59:59'";
$res=mysql_query($sql,$db2) or logError($sql,$db2);
$tempContacts = 0;
$row = mysql_fetch_array($res);
$tempContacts = $row["cntTemp"];

//Temporary contacts delivered
$sql = "SELECT COUNT(1) cntDelivered FROM newjs.CONTACTS_TEMP WHERE newjs.CONTACTS_TEMP.DELIVER_TIME BETWEEN '$today 00:00:00' AND '$today 23:59:59' AND DELIVERED='Y'";
$res=mysql_query($sql,$db2) or logError($sql,$db2);
$delivered = 0;
$row = mysql_fetch_array($res);
$delivered = $row["cntDelivered"];

//Automated Contacts Tracking
$sql="SELECT COUNT(*) AS COUNT,GENDER FROM Assisted_Product.AUTOMATED_CONTACTS_TRACKING,newjs.JPROFILE  WHERE SENDER=PROFILEID AND DATE BETWEEN '$today 00:00:00' AND '$today 23:59:59' GROUP BY GENDER";
$res=mysql_query($sql,$db2) or logError($sql,$db2);
if(mysql_num_rows($res))
{
	while($row=mysql_fetch_assoc($res))
	{
		if($row["GENDER"]=="M")
			$mAutoContacts=$row["COUNT"];
		elseif($row["GENDER"]=="F")
			$fAutoContacts=$row["COUNT"];
	}
}

mysql_close($db2);

// query changed to add NEW FIELD 'UICOUNT' for unique initial contact
// query changed to add NEW FIELD 'URICOUNT' for unique receivers initial contact

$db=connect_db();
//mysql_select_db("newjs",$db);

$sql="INSERT INTO MIS.DAY_CONTACT_COUNT(CONTACT_DT,GENDER,ICOUNT,ACOUNT,DCOUNT,CCOUNT,UICOUNT,URICOUNT,SCCOUNT,SMCOUNT,UNIQUE_M_A,UNIQUE_F_A,UNIQUE_MARR,NDGCOUNT,NNACOUNT,NACOUNT,REJCOUNT,TEMP_CONTACTS,TEMP_CONTACTS_DELIVERED,AUTO_CONTACTS) VALUES('$today','M','$micnt','$macnt','$mdcnt','$mccnt','$muicnt','$muricnt','$msccnt','$msmcnt','$UMA','$UFA','$UMP','$mnud','$mnacc','$macc','$mrej','$tempContacts','$delivered','$mAutoContacts'),('$today','F','$ficnt','$facnt','$fdcnt','$fccnt','$fuicnt','$furicnt','$fsccnt','$fsmcnt','$UMA','$UFA','$UMP','$fnud','$fnacc','$facc','$frej','$tempContacts','$delivered','$fAutoContacts')";
mysql_query($sql,$db) or logError($sql,$db);

//====================Changes End Here==================================\\

mysql_close($db);

?>
