<?
include "connect.inc";                                                                                                 
                /**
                *       Function        :       get_valid_mailers
                *       Input           :
                *       Output          :       array of mailers_id
                *       Description     :       This function will find all the mailers who are in 'vd' state
                **/
                                                                                                 
                                                                                                 
function get_valid_mailers($mailer_id)
{
        global $smarty;
        $sql="SELECT MAILER_ID,MAILER_NAME FROM MAIN_MAILER WHERE STATE='tc' ";
        $result=mysql_query($sql) or die("Could not connect MAIN_MAILER in mmm_create_table.php");
                                                                                                 
        $no=mysql_num_rows($result);
        if($no==0)
        {
                $message="There is no active mailer for which table has been created please create mailer first ";
                $smarty->assign("message",$message);
                $smarty->display("mmm_message.htm");
                die;
        }
        else
        {
                while($row=mysql_fetch_array($result))
                {
                        $mailer_id_arr[]=array("mailer_id"=>$row[MAILER_ID], "mailer_name"=>$row[MAILER_NAME]);
                }
        }
                                                                                                 
        return $mailer_id_arr;
}


if($create_dump)
{
	$sql="UPDATE MAIN_MAILER SET STATE='dc' WHERE MAILER_ID=$mailer_id";
        mysql_query($sql) or die("Coud not save query in mmm_save_search.php ".mysql_error());
}
else
{
        $mailer_id_arr=get_valid_mailers($mailer_id);
        $smarty->assign("msg",$msg);
        $smarty->assign("cid",$cid);
        $smarty->assign("mailer_id_arr",$mailer_id_arr);
        $smarty->display("mmm_dump_to_server3.htm");

}
?>
