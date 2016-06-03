<?php
include('connect.inc');
$db = connect_db();
                                                                                                                             
$sql_un = "INSERT INTO UNSUBSCRIBED_USERS (EMAIL) VALUES ('$to')";
$res_un = mysql_query_decide($sql_un) or die("$sql_un".mysql_error_js());
$smarty->display('unsubscribed.htm');

?>
