<?php
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
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
$ignoreObj=new newjs_IGNORE_PROFILE();
while($row1=mysql_fetch_array($res_main))
		{
                        $count=$row1['CNT']-4000;
                    	$sql2="SELECT PROFILEID,IGNORED_PROFILEID from newjs.IGNORE_PROFILE WHERE PROFILEID=".$row1['PROFILEID']. " ORDER BY `DATE` ASC LIMIT ".$count;
                        mysql_query($sql2,$dbSlave);
                        $res_main2=mysql_query($sql2,$dbSlave);
                        while($row2=mysql_fetch_array($res_main))
                        {
                            $ignoreObj->undoIgnoreProfile($row2['PROFILEID'], $row2['IGNORED_PROFILEID']);
                            JsMemcache::getInstance()->remove($row2['IGNORED_PROFILEID']);
                        }
                        		
                        
                        JsMemcache::getInstance()->remove($row1['PROFILEID']);

                }                
                                   
                                
                                
                                