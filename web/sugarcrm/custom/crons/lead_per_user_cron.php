<?php
define('sugarEntry',true);
chdir(realpath(dirname(__FILE__))."/../..");
require_once('include/entryPoint.php');
$from_date="$day-$month-$year";
$to_date="$end_day-$end_month-$end_year";
$unixfrom=JSstrToTime($from_date);
$unixTo=JSstrToTime($to_date);
//Calculat End time of that day
$unixTo+=86399;
if($unixTo<$unixfrom){
	echo "<br>Please select valid dates<br>"."<a href='leads_count.php'>Back</a>";
	exit(0);
}	
$result=$db->query("select count(l.id) as count, u.user_name, u.first_name, u.last_name from leads as l, users as u where l.created_by=u.id and unix_timestamp(l.date_entered)>=$unixfrom and unix_timestamp(l.date_entered)<$unixTo group by u.id");
echo "<html>
	<head> Number of Leads Entered By all Users </head>
	<title> Number of leads entered by users </title>
	<body>
	<table border='1'>
	<tr>
	<td>USER NAME</td>
	<td>FIRST NAME</td>
	<td>LAST NAME</td>
	<td>Number of Leads</td>
	</tr>";
while($row=$db->fetchByAssoc($result)){
//	print_r($row);
	echo "<tr>
	<td>".$row['user_name']."</td>
	<td>".$row['first_name']."</td>
	<td>".$row['last_name']."</td>
	<td>".$row['count']."</td>
	</tr>";
}
echo "</table>";
echo "
	</body>
	</html>
	";
?>
