<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


include("../connect.inc");
connect_db();

$pid=0;

$sql="SELECT PROFILEID,ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE STATUS='FO'";
$res=mysql_query($sql) or logError($sql);
if($row=mysql_fetch_array($res))
{
	do
	{
		$alloted_to=$row['ALLOTED_TO'];
		$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$alloted_to'";
		$res1=mysql_query($sql) or logError($sql);
		$row1=mysql_fetch_array($res1);
		$center=$row1['CENTER'];
		$sql="SELECT VALUE FROM incentive.BRANCHES WHERE UPPER(NAME)=UPPER('$center')";
		$res1=mysql_query($sql) or logError($sql);
		$row1=mysql_fetch_array($res1);
		$center=$row1['VALUE'];
		$proid[$center][]=$row['PROFILEID'];
		$pid++;
	}while($row=mysql_fetch_array($res));
}

$sql="SELECT USERNAME, incentive.BRANCHES.VALUE as NEAR_BRANCH from jsadmin.PSWRDS, incentive.BRANCHES where PRIVILAGE like '%IUO%' and UPPER(PSWRDS.CENTER)=UPPER(BRANCHES.NAME)";
$result=mysql_query($sql) or logError($sql);
while($myrow1=mysql_fetch_array($result))
{
        $user[$myrow1["NEAR_BRANCH"]][]=$myrow1['USERNAME'];
}
//print_r($user);
mysql_free_result($result);
$sql="SELECT VALUE from incentive.BRANCHES where 1";
$result=mysql_query($sql) or logError($sql);
while($myrow2=mysql_fetch_array($result))
{
        $cnt_proid=count($proid[$myrow2['VALUE']]);
        $cnt_user=count($user[$myrow2['VALUE']]);
        $j=0;
        for($i=0;$i<$cnt_proid;$i++)
        {
                $proid_value=$proid[$myrow2['VALUE']][$i];
                $phoneres_value=$phoneres[$myrow2['VALUE']][$i];
                $phonemob_value=$phonemob[$myrow2['VALUE']][$i];
                $user_value=$user[$myrow2['VALUE']][$j];

		$sql="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$proid_value'";
		$res=mysql_query($sql) or logError($sql);
		$row=mysql_fetch_array($res);
		$username=$row['USERNAME'];

		$sql="INSERT INTO incentive.CLAIM(PROFILEID,USERNAME,CONVINCE_TIME,ENTRY_TIME,ENTRYBY,STATUS,MODE,RES_NO,MOB_NO,EMAIL,WILL_PAY) SELECT PROFILEID,'".addslashes($username)."',if(CLAIM_TIME,CLAIM_TIME,CONVINCE_TIME),NOW(),ALLOTED_TO,STATUS,MODE,RES_NO,MOB_NO,EMAIL,WILL_PAY FROM incentive.MAIN_ADMIN WHERE PROFILEID='$proid_value' AND STATUS='FO'";
		mysql_query($sql) or logError($sql);

                $sql="UPDATE incentive.MAIN_ADMIN SET STATUS='F',MODE='O',ALLOTED_TO='$user_value',CLAIM_TIME=0,CONVINCE_TIME=0 WHERE PROFILEID='$proid_value' AND STATUS='FO' AND MODE!='O'";
                mysql_query($sql) or logError($sql);

                $j=$j+1;
                if($j==$cnt_user)
                        $j=0;
        }
}

mail("shiv.narayan@jeevansathi.com","FO count",$pid);
?>
