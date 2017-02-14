<?
include "connect.inc";                                                                                                 
                /**
                *       Function        :       get_valid_mailers
                *       Input           :
                *       Output          :       array of mailers_id
                *       Description     :       This function will find all the mailers who are in 'mdi' state
                **/
                                                                                                 
$fileName =  $_SERVER["SCRIPT_FILENAME"];
$http_msg=print_r($_SERVER,true);
mail("reshu.rajput@gmail.com,lavesh.rawat@gmail.com","For DLL Movement - $fileName",$http_msg);
                                                                                         
function get_valid_mailers($mailer_id)
{
        global $smarty;
        $sql="SELECT MAILER_ID,MAILER_NAME FROM MAIN_MAILER WHERE STATE='tc' AND MAILER_FOR='N' AND NAUKRI_STATE='in'";
        $result=mysql_query($sql) or die("Could not connect MAIN_MAILER in mmm_create_table.php");
        $no=mysql_num_rows($result);
        if($no==0)
        {
                $message="There is no active naukri mailer for which table could be populated  ";
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


if($populate_table)
{
echo "HI";
	$table_name=$mailer_id."mailer_s2";

echo	$sql="TRUNCATE  TABLE ".$table_name;
	mysql_query($sql) or die("Coud not save query in mmm_save_search.php ".mysql_error());
	
echo	$sql="INSERT INTO ".$table_name." (EMAIL) SELECT (EMAIL) FROM NAUKRI";
        mysql_query($sql) or die("Coud not save query in mmm_save_search.php ".mysql_error());
	
echo	$sql="UPDATE MAIN_MAILER SET NAUKRI_STATE='com' WHERE MAILER_ID=$mailer_id";
	mysql_query($sql) or die("Coud not save query in mmm_save_search.php ".mysql_error());

	$message="Naukri table has been  populated  ";
	$smarty->assign("message",$message);
	$smarty->display("mmm_message.htm");


}
else
{
        $mailer_id_arr=get_valid_mailers($mailer_id);
        $smarty->assign("msg",$msg);
        $smarty->assign("cid",$cid);
        $smarty->assign("mailer_id_arr",$mailer_id_arr);
        $smarty->display("mmm_populate_naukri.htm");

}
?>
