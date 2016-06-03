<?php
include("connect.inc");
$smarty->assign("cid",$cid);
$smarty->display("faq_footer.htm");
/*
if($var=='edit')
{
echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/jsadmin/faq_edit.php?cid=$cid\"></body></html>";
}
elseif($var=='logout')
{
echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/jsadmin/logout.php?cid=$cid\"></body></html>";
}
else
{
echo "<html><body><META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=$SITE_URL/jsadmin/faq_admin_main.php?cid=$cid\"></body></html>";
}
*/
?>
