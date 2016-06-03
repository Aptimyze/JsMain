<?

include "connect.inc";

//********************* THIS ROUTINE WILL CHECK YOUR AUTHENTICATION AND IF YOUR "cid" HAS EXPIRED THEN IT WILL REDIRECT TO LOGIN PAGE*****************************************************//
                                                                                                 
$ip = getenv('REMOTE_ADDR');
if(authenticated($cid,$ip))
{
        $auth=1;
        $un = getuser($cid,$ip);
        $tm=getIST();
        //setcookie ("cid", $cid,$tm+3600);
}
if(!$auth)
{
        $smarty->display("mmm_relogin.htm");
        die;
}
//***************AUTHENTICATION ROUTINE ENDS HERE*******************//

if($activate)
{
print_r($_POST);
echo "cid : ";echo $cid;
echo "MAILER ID : ";echo $mailer_id;
echo "TEMPLATE NAME : ";echo $tempname1;
        $sql="UPDATE MAIL_DATA SET ACTIVE='N' WHERE  MAILER_ID='$mailer_id'";
        mysql_query($sql) or die("could not select values from mail data table".mysql_error());
                                                                                                 
        $sql="UPDATE MAIL_DATA SET ACTIVE='Y' WHERE TEMPLATE_NAME='$tempname1' AND MAILER_ID='$mailer_id'";
        mysql_query($sql) or die("could not select values from mail data table".mysql_error());
        echo "this mailer has been activated";
                                                                                                 
}

?>
