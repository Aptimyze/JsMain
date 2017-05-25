<?php
  $curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
$flag_using_php5=1;

include("config.php");
include("connect.inc");

$dbM=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$dbS=connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

for($i=0;$i<2;$i++)
{
        if($i==0)
                $table="SEARCH_FEMALE";
        else
                $table="SEARCH_MALE";
        $sql_main="SELECT A.PROFILEID FROM newjs.$table A LEFT JOIN newjs.$table"._REV." B ON A.PROFILEID = B.PROFILEID WHERE B.PROFILEID IS NULL ";
        //echo $sql_main;
        //echo "\n";
        $res_main = mysql_query($sql_main,$dbS) or die($sql_main.mysql_error($dbS));
        while($row = mysql_fetch_array($res_main))
        {
                $pid = $row['PROFILEID'];
                $sql="INSERT IGNORE INTO newjs.SWAP_JPARTNER(PROFILEID) VALUES ($pid)";
                mysql_query($sql,$dbM) or die($sql.mysql_error($dbM));
       	 	//if($xyz++%1000==0)
                //echo $xyz."-";
        }
}
?>
