<?php
/**
* This classs list all the mysql connections 
* RO => read only connections
*/
class MysqlDbConstants
{
        public static $mySqlDumpPath       = "/usr/bin/mysqldump";
        public static $mySqlPath           = "/usr/local/mysql/bin/mysql";
        public static $mySqlPathRep        = "/usr/local/mysql/bin/replace";

        /* master-slave*/
        public static $master           =array('HOST'=>'master.js.jsb9.net', 'USER'=>'user', 'PASS'=>'CLDLRTa9', 'PORT'=>'3306');
        public static $misSlave         =array('HOST'=>'ser2.jeevansathi.jsb9.net', 'USER'=>'user_dialer', 'PASS'=>'DIALlerr', 'PORT'=>'3306');
        public static $slave111         =array('HOST'=>'localhost:/tmp/mysql_06.sock', 'USER'=>'user_sel', 'PASS'=>'CLDLRTa9', 'PORT'=>'3306');
        public static $shard1Slave112   =array('HOST'=>'productshard2slave.js.jsb9.net:3309', 'USER'=>'user_sel', 'PASS'=>'CLDLRTa9', 'PORT'=>'3309');
        public static $shard2Slave112   =array('HOST'=>'productshard2slave.js.jsb9.net:3306', 'USER'=>'user_sel', 'PASS'=>'CLDLRTa9', 'PORT'=>'3306');
        public static $shard3Slave112   =array('HOST'=>'productshard2slave.js.jsb9.net:3307', 'USER'=>'user_sel', 'PASS'=>'CLDLRTa9', 'PORT'=>'3307');
        public static $dialer           =array('HOST'=>'dialer.infoedge.com', 'USER'=>'online', 'PASS'=>'jeev@nsathi@123', 'PORT'=>'3306');
        public static $master1          =array('HOST'=>'master.js.jsb9.net', 'USER'=>'privUser', 'PASS'=>'Pr!vU3er!', 'PORT'=>'3306');
}

