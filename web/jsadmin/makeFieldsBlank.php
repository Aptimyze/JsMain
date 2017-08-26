<?php

include("connect.inc");
$data           =authenticated($cid);
$db             =connect_db();
$user           =trim(getname($cid));

if($data)
{
    if($CMDSubmit){
        if(!is_numeric($username)){
            $sql="select PROFILEID from newjs.JPROFILE where USERNAME='$username'";
        
            $result=mysql_query_decide($sql) or die("$sql"."0".mysql_error_js());
            if(mysql_num_rows($result)>0)
            {
                    $myrow=mysql_fetch_array($result);
                    $profileid=$myrow['PROFILEID'];
            }
        }
        else
            $profileid=$username;
        
        if($jamaat || $hijaab){
            
            if($jamaat)
                $jp_muslimArr['JAMAAT'] = '';
        
            if($hijab)
                $jp_muslimArr['HIJAB'] = '';
            $jpMuslimObj = new NEWJS_JP_MUSLIM();
            $jpMuslimObj->update($profileid,$jp_muslimArr);
        }
        
        if($sect){
            $updateArr['SECT'] = '';
            $objUpdate = JProfileUpdateLib::getInstance();
            $result = $objUpdate->editJPROFILE($updateArr,$profileid,"PROFILEID");
            if(false === $result) {
              die('Mysql error while updating JPROFILE');
            }
            unset($objUpdate);
        }
      
        $message="This user's profile details have been updated.<br>";
        $message.="<a href=\"makeFieldsBlank.php?cid=$cid\">Edit another profile?</a>";
        $smarty->assign("MSG",$message);
        $smarty->display("jsadmin_msg.tpl");
    }
    else{
        $smarty->display("makeFieldsBlank.htm");
    }
}
else //user timed out
{
	$msg="Your session has been timed out<br>  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";	
	$smarty->assign("MSG",$msg);	
	$smarty->display("jsadmin_msg.tpl");
}	

