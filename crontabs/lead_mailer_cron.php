<?php
/************************************************************************************************************************
  * FILENAME           : lead_mailer.php
  * DESCRIPTION        : Mail will be send to the User who are in the REG_LEAD Table for conversion of lead.
  * Mantis ID          : 4514
  * CREATED BY         : Anurag Gautam
  * Date               : 6th August 2009
  ***********************************************************************************************************************/

include(JsConstants::$docRoot."/profile/connect.inc");

$db_slave = connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_slave);
$db_master = connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_master);

$smarty->relative_dir="mailer/";
$smarty->setTemplateDir(JsConstants::$docRoot."/smarty/templates/mailer/");

$from='info@jeevansathi.com';
$sub='Complete your registration to find the right life partner';

$sql="SELECT R.LEADID, R.EMAIL
FROM MIS.REG_LEAD AS R
LEFT JOIN newjs.JPROFILE AS J ON R.EMAIL = J.EMAIL
WHERE R.LEAD_CONVERSION =  'N' AND R.UNSUB_LEADMAIL !=  'Y' AND R.TYPE =  '' AND (J.ACTIVATED != 'D' OR J.EMAIL IS NULL)";
$res= mysql_query($sql,$db_slave) or die(mysql_error1($db_slave));

while($row=mysql_fetch_array($res))
{
        $leadid=$row['LEADID'];
        $email=$row['EMAIL'];
//      preg_match("/^[a-z0-9._-]{2,}+\@[a-z0-9_-]{2,}+\.([a-z0-9-]{2,4}|[a-z0-9-]{2,}+\.[a-z0-9-]{2,4})$/i", $email);
        if(preg_match("/^[a-z0-9._-]{2,}+\@[a-z0-9_-]{2,}+\.([a-z0-9-]{2,4}|[a-z0-9-]{2,}+\.[a-z0-9-]{2,4})$/i", strtolower($email)))
        {
                $smarty->assign('LEADID',$leadid);
                $msg=$smarty->fetch('lead_mailer.htm');
                send_email($email,$msg,$sub,$from);
                $sql="UPDATE MIS.REG_LEAD SET SENT_MAIL='Y' WHERE EMAIL='$email'";
                mysql_query($sql,$db_master) or die(mysql_error1($db_master));
        }
        else
        {
                echo $email."\n";
        }
}

//mail("anurag.gautam@jeevansathi.com","Registration lead mailer ran successfully", date("Y-m-d"));

function mysql_error1($db)
{
        return mysql_error($db);
        //mail("anurag.gautam@jeevansathi.com","Error in Registration lead_mailer.php",mysql_error($db));
}

?>
