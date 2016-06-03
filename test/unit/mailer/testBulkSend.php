<?php
include(dirname(__FILE__).'/../../bootstrap/unit.php');
$t = new lime_test(1, new lime_output_color());
$profiles = null;
$db = mysql_connect("172.16.3.185", "localuser", "Km7Iv80l"); 
$sql = "SELECT PROFILEID FROM newjs.JPROFILE LIMIT 2100";

$res = mysql_query($sql, $db);

while ($row = mysql_fetch_array($res)) {
  $profiles[] = $row["PROFILEID"];
}

$emailSender = new EmailSender(8, 1739);
$emailSender->bulkSend($profiles, array(array("jeevansathi_contact_address", "jeevansathi_contact_address")));
