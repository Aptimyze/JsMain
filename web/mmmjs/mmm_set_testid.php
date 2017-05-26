<?
include"connect.inc";
                                                                                                 
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


                                                                                                 
                /**
                *       Function        :       get_test_emailid()
                *       Input           :
                *       Output          :       array of id corresponding to the emailid's for which test mails are to be sent.
                *       Description     :       This function fetches the id of test emailids andreturns it as array    **/

function get_test_emailid()
{
        global $smarty;
        $sql="select ID,EMAIL,DELETED from TEST WHERE 1 ";
        $result=mysql_query($sql) or die("could not get valid emailids ".mysql_error());
        while($row=mysql_fetch_array($result))
        {
                $arr[]=array("emailid"=>$row[EMAIL],
			"id"=>$row[ID],
                         "deleted"=>$row[DELETED]);
        }
        if(sizeof($arr)==0)
        {
                $message="There is no test mailid set in database";
		$smarty->assign("message","$message");
		$smarty->display("mmm_message.htm");
		die();
        }
        else
                return $arr;
}

if($submit)
{
        foreach( $_POST as $key => $value )
        {
                if( substr($key, 0, 10) == "testmailid" )
                {
                        $cnt=$cnt+1;
                        $mid = ltrim($key, "testmailid");
                        $id[] = $mid;
                }
        }
        $id_str=implode("','",$id);

	$sql="UPDATE TEST SET DELETED='1' WHERE 1";
        mysql_query($sql) or die("could not get valid mailers ".mysql_error());
                                                                                                 
        $sql="UPDATE TEST SET DELETED='0' WHERE ID IN ('$id_str') ";
        mysql_query($sql) or die("could not get valid mailers ".mysql_error());
	$message="Test emailid's have been updated";
	$smarty->assign("message","$message");
	$smarty->display("mmm_message.htm");
//        header("Location: http://".$_SERVER['HTTP_HOST']."/mmmjs/mmm_incomplete_mailer.php?cid=$cid");
}
elseif($deleteEmail)
{
        foreach( $_POST as $key => $value )
        {
                if( substr($key, 0, 10) == "testmailid" )
                {
                        $cnt=$cnt+1;
                        $mid = ltrim($key, "testmailid");
                        $id[] = $mid;
                }
        }
        $id_str=implode("','",$id);
        $sql="DELETE FROM TEST WHERE ID IN ('$id_str')";
        mysql_query($sql) or die("could not get valid ids ".mysql_error());
        $message="Deleted from test database. <a href='mmm_set_testid.php?cid=$cid'>Go back</a>";
        $smarty->assign("message","$message");
        $smarty->display("mmm_message.htm");
}
elseif($addEmail)
{
	if($emailId)
	{
		$sql="INSERT INTO TEST(EMAIL, SENT, DELETED) VALUES ('$emailId', 0, 1)";
		mysql_query($sql) or die("could not get valid ids ".mysql_error());
		$message="Added $emailId in the test database. <a href='mmm_set_testid.php?cid=$cid'>Activate email Id</a>";
	}
	else
		$message="Please enter email Id in the text box";	
        $smarty->assign("message","$message");
        $smarty->display("mmm_message.htm");
}
else
{
	$emailid=get_test_emailid();
	$smarty->assign("emailid",$emailid);
	$smarty->assign("cid",$cid);
        $smarty->display("mmm_set_testid.htm");	
}

?>
