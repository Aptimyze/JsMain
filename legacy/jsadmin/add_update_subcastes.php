<?php
include_once('connect.inc');
$db = connect_db();

if (authenticated($cid)) {
  $user = getname($cid);
  $sql = "SELECT SQL_CACHE R1.SPELLING, R1.LABEL, R2.RELATED_CASTE_ID, R1.SORT_BY  FROM newjs.SUBCASTE_SPELLINGS_MAP R1, newjs.SUBCASTE_CASTE_ID_MAP R2 WHERE R1.SUBCASTE_ID = R2.SUBCASTE_ID ORDER BY R1.SORT_BY";
  $result = mysql_query($sql);

  while ($row = mysql_fetch_assoc($result)) {
    if (!@in_array($row['SPELLING'], $arr[$row['LABEL']]['SPELLINGS'])) {
      $arr[$row['LABEL']]['SPELLINGS'][/*/$i/*//**/] = $row['SPELLING'];
    }
    if (!@in_array($row['RELATED_CASTE_ID'], $arr[$row['LABEL']]['CASTE_IDs'])) {
      $arr[$row['LABEL']]['CASTE_IDs'][/*/$i/*//**/] = $row['RELATED_CASTE_ID'];
    }
    if (!@in_array($row['SORT_BY'], $arr[$row['LABEL']]['ORDER'])) {
      $arr[$row['LABEL']]['ORDER'] = $row['SORT_BY'];
    }
  }
  foreach ($arr as $k => $v) {
    $res[] = array('l'=> $k, 'v' => $v['SPELLINGS'], 'r' => $v['CASTE_IDs'], 'o' => $v['ORDER']);
  }
  $smarty->assign('subcaste_array', json_encode($res));
  $smarty->assign("operator_name", $user);
  //echo $user . " is Authenticated\n";
  $smarty->display("add_update_subcastes.htm");
}

