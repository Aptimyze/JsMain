<?php
//Live Connection
/*
$dbHostMaster         	="localhost:3306";
$dbUserMaster         	="user_sel";
$dbPasswdMaster       	="CLDLRTa9";
$dbName                 ="test";
*/

// Test 
$dbHostMaster           ="172.16.3.185:3306";
$dbUserMaster           ="localuser";
$dbPasswdMaster         ="Km7Iv80l";
$dbName                 ="test";

$db             	=@mysql_connect("$dbHostMaster","$dbUserMaster","$dbPasswdMaster") or die("master connection failed");
$dateSelected 		="$year1-$month1-$day1";
?>
<html>
<form action="ipAlertMis.php" name="form">
Please select the date:<br><br>
From:
<select name='day1'>
	<option value="">Day</option>";
		<?php for($i=1;$i<=31;$i++) if($day1==$i){echo"<option value=$i selected>$i</option>";}else{echo"<option value=$i>$i</option>";}?>
</select>
<select name='month1'>
        <option value="">Month</option>";
                <?php for($i=1;$i<=12;$i++) if($month1==$i){echo"<option value=$i selected>$i</option>";}else{echo"<option value=$i>$i</option>";}?>
</select>
<select name='year1'>
        <option value="">Year</option>";
                <?php for($i=2013;$i<=2015;$i++) if($year1==$i){echo"<option value=$i selected>$i</option>";}else{echo"<option value=$i>$i</option>";}?>
</select>
<input type="submit" name="submit" value="submit">
<p>
</form>

<?php	
	$count=1;
        $sql ="SELECT * from test.IP_ALERT WHERE DATE>='$dateSelected 00:00:00' AND DATE<='$dateSelected 23:59:59'";
        $res =mysql_query($sql,$db) or die($sql.mysql_error());
        while($row = mysql_fetch_array($res)){
                $username 	=$row['USERNAME'];
		$agent 		=$row['AGENT'];
		$allotedTo 	=$row['ALLOTED_TO'];

		$msg3.="<tr>";
		$msg3.="<td width=5>$count</td><td width='10'>$username</td><td width='10'>$agent</td><td width='10'>$allotedTo</td>";
		$msg3.="</tr>";
		$count++;	
	}

	$msg1.="<table width=80% callpadding=5>";
	$msg2.="<tr><td width=5>S.No</td><td width='10'>Username</td><td width='10'>LTF Executive</td><td width='10'>FTA FTO Executive</td></tr>";
	$msg4.="<table>";
	echo $msg1.$msg2.$msg3.$msg4;
	
?>
