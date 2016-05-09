<?php
include("connect.inc");

	//$db=@mysql_connect("localhost","root","Km7Iv80l") or die("error".mysql_error_js());
        //mysql_select_db_js("jsadmin");

	$sql_res="select * from jsadmin.DELETED_PROFILES where PROFILEID='$profileid' order by ID";
        $result= mysql_query_decide($sql_res) or die("error1".mysql_error_js());
        echo"<table rows=2 cols =4 border=1 bordercolor=red >";
	while($row=mysql_fetch_array($result))
        {
		$del_by=$row["USER"];
		$ret_by=$row["RETRIEVED_BY"];
		$reason=$row["REASON"];
		$comments=nl2br($row["COMMENTS"]);
		$time=$row["TIME"];
		$profid=$row["PROFILEID"];
		
		if($del_by!='')
		{
			echo"<tr bgcolor='#CFEAC6'><td>deleted by=$del_by</td><td> Reason= $reason</td><td>Comments=$comments</td><td>Deleted on $time</td></tr>";
		}
		else
		{
			echo"<tr bgcolor='#EBF8E0'><td>Retrieved by=$ret_by</td><td>Reason= $reason</td><td>Comments=$comments</td><td>Retrieved on $time</td></tr>";	
		}
	}

echo "</table>";
/*echo'<p align="left">';

echo' <b>ProfileId = ';echo $_GET["profileid"];
echo"</b></br>
<b>Deleted BY=";echo  $_GET['delby'];echo'</b></br>
<b>Deleted on=';echo $_GET["timeofdel"];echo'<b></br>
<b>Reason=';echo $_GET["reason"];echo'</b></br>
<b>Comments=';echo $_GET["comments"];echo '</b></br>';
echo'</p>';*/
?>
