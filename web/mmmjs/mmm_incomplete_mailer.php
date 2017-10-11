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
                *       Function        :       get_incomplete_mailers()
                *       Input           :
                *       Output          :       array of mailer_name which are not composed completely
                *       Description     :       This function fetches the mailer_name of incompleted mailers and returns it as array    **/

function get_incomplete_mailers()
{
        global $smarty;
        $sql="select MAILER_NAME,MAILER_ID,STATUS,STATE,MAIL_TYPE,RESPONSE_TYPE from MAIN_MAILER WHERE STATE!='mdi' AND DEL='N' ";
        $result=mysql_query($sql) or die("could not get valid mailers ".mysql_error());
        while($row=mysql_fetch_array($result))
        {
                $arr[]=array("mailer_id"=>$row[MAILER_ID],
                         "mailer_name"=>$row[MAILER_NAME],
                         "status"=>$row[STATUS],
			"state"=>$row[STATE],
			"mail_type"=>$row[MAIL_TYPE],
			"response_type"=>$row[RESPONSE_TYPE]);
        }
        if(sizeof($arr)==0)
        {
                $message="There is no incomplete mailer ";
		$smarty->assign("message","$message");
		$smarty->display("mmm_message.htm");
		die();
        }
        else
                return $arr;
}




if($delete)
{
        foreach( $_POST as $key => $value )
        {
                if( substr($key, 0, 10) == "incomplete" )
                {
                        $cnt=$cnt+1;
                        $mid = ltrim($key, "incomplete");
                        $mailerid[] = $mid;
                }
        }
        $incomplete=implode("','",$mailerid);
                                                                                                 
        $sql="UPDATE MAIN_MAILER SET DEL='Y' WHERE MAILER_ID IN ('$incomplete') ";
        mysql_query($sql) or die("could not get valid mailers ".mysql_error());
        header("Location: http://".$_SERVER['HTTP_HOST']."/mmmjs/mmm_incomplete_mailer.php?cid=$cid");
}
else
{
	$incomp=get_incomplete_mailers();
	$smarty->assign("incomp",$incomp);
	$smarty->assign("cid",$cid);
        $smarty->display("mmm_incomplete_mailer.htm");	
}



?>
