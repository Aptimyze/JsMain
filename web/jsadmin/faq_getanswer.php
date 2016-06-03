<?php
include("connect.inc");

$sql="SELECT ANSWER FROM feedback.QADATA WHERE ID = '$catid'";
$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
$row=mysql_fetch_array($res);

echo "<html>";
echo "<head>";
echo "<script>";
echo "function js()";
echo "{	
parent.main.document.form1.reply.value=document.form_empty.ANSWER.value;
}";
echo "</script>";
echo "</head>";
echo "<body onload=js();>";
echo "<form name=form_empty>";
echo "<input type=hidden name=ANSWER value=\"$row[ANSWER]>\"";
echo "</form>";
/*echo "<script>";
echo "var ANSWER=$row[ANSWER];";
echo "main.reply.value=empty.ANSWER.value;";
echo "</script>";*/
echo "</body>";
echo "</html>";
?>
