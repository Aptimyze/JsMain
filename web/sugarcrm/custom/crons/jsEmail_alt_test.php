<?php
require_once('JsEmail.php');
$email=new JsEmail("alternate_days");
$email->sendMessage();
?>
