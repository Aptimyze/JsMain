<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
chdir(dirname(__FILE__));
include_once("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
$dbSlave=connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbSlave);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbSlave);
$sql1='select count(*) as CNT,PROFILEID from newjs.IGNORE_PROFILE group by PROFILEID having CNT>4000';
$res_main=mysql_query($sql1,$dbSlave);

while($row1=mysql_fetch_array($res_main))
		{
                        $count=$row1['CNT']-4000;
                    	$sql2="DELETE from newjs.IGNORE_PROFILE WHERE PROFILEID=".$row1['PROFILEID']. " ORDER BY `DATE` ASC LIMIT ".$count;
                        mysql_query($sql2,$dbSlave);
                }                
                                   
                                
                                
                                