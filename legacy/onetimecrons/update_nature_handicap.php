<?php
include_once('../connect.inc');
$slave = connect_slave();
$slave_query = "SELECT PROFILEID FROM newjs.JPROFILE WHERE HANDICAPPED NOT IN ('1', '2') AND NATURE_HANDICAP != '' AND NATURE_HANDICAP IS NOT NULL";
$slave_result = mysql_query($slave_query, $slave) or die(mysql_error($slave));
$profile_id_arr = null;
$profile_ids = null;
while ($slave_row = mysql_fetch_assoc($slave_result)) {
  $profile_id_arr[] = $slave_row['PROFILEID'];
}

  $profile_ids = implode($profile_id_arr, ',');

  $db = connect_db();

  if ($profile_ids) {
    $query = "UPDATE newjs.JPROFILE SET NATURE_HANDICAP = NULL WHERE PROFILEID IN ($profile_ids)";
    $result = mysql_query($query, $db) or die(mysql_error($db));
  }
  else {
    echo "Somehow profile ids are not present\n";
	die;
  }
echo "successfully updated ->" . count($profile_id_arr);
?>
