<?php
require_once('data/SugarBean.php');
require_once('modules/Leads/Lead.php');
require_once('include/utils.php');

class After {
function After (&$bean, $event, $arguments)
{
#your code
	
//echo "hi".$bean->id,$event,$arguments;die;
if($bean->id && !$arguments['isUpdate'])
{
$id=$bean->id;
 //$path = $_SERVER['DOCUMENT_ROOT']."/sugarcrm/matchalert_for_leads.php '$id' > ".$_SERVER['DOCUMENT_ROOT']."/sugarcrm/a.htm ";
   //echo                                    $cmd = "/usr/bin/php ".$path;
//                                       $cmd = "/usr/bin/php -q ".JsConstants::$docRoot."/sugarcrm/matchalert_for_leads.php '$id'";
 //                                       passthru($cmd);
}
}
}
?>
