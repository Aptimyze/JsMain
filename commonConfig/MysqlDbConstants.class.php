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
        public static $alertsMySqlPath     = "/usr/local/jeevansathi/bin/mysql";
        public static $alertsMySqlDumpPath = "/usr/local/jeevansathi/bin/mysqldump";

	/* master-slave*/
	public static $master      = array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3306' , 'DEFAULT_DB'=>'newjs');
	public static $masterRO    = array('HOST'=>'172.16.3.185', 'USER'=>'localSlave', 'PASS'=>'Km7Iv80l', 'PORT'=>'3306' , 'DEFAULT_DB'=>'newjs');
	public static $bmsSlave    = array('HOST'=>'172.16.3.185', 'USER'=>'localSlave', 'PASS'=>'Km7Iv80l', 'PORT'=>'3306' , 'DEFAULT_DB'=>'newjs');
	public static $alertsSlave = array('HOST'=>'172.16.3.185', 'USER'=>'localSlave', 'PASS'=>'Km7Iv80l', 'PORT'=>'3306' , 'DEFAULT_DB'=>'newjs');
	public static $misSlave    = array('HOST'=>'172.16.3.185', 'USER'=>'localSlave', 'PASS'=>'Km7Iv80l', 'PORT'=>'3306' , 'DEFAULT_DB'=>'newjs'); // use 'user' as to hv some insert grants;
	public static $bounceLog   = array('HOST'=>'172.16.3.185', 'USER'=>'localSlave', 'PASS'=>'Km7Iv80l', 'PORT'=>'3306' , 'DEFAULT_DB'=>'bouncelog');
	public static $dialer   = array('HOST'=>'172.16.3.185', 'USER'=>'localSlave', 'PASS'=>'Km7Iv80l', 'PORT'=>'3306' , 'DEFAULT_DB'=>'newjs');

	/*view Similar */
	public static $viewSimilar = array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3309' , 'DEFAULT_DB'=>'newjs');

	/*dnc*/
	public static $dnc         = array('HOST'=>'172.16.3.185', 'USER'=>'localSlave', 'PASS'=>'Km7Iv80l', 'PORT'=>'3306' , 'DEFAULT_DB'=>'newjs');
        public static $crmSlave    = array('HOST'=>'172.16.3.185', 'USER'=>'localSlave', 'PASS'=>'Km7Iv80l', 'PORT'=>'3306' , 'DEFAULT_DB'=>'newjs');
	/* view log */
	public static $viewLog     = array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3308' , 'DEFAULT_DB'=>'newjs');
	public static $viewLogSlave= array('HOST'=>'172.16.3.185', 'USER'=>'localSlave', 'PASS'=>'Km7Iv80l', 'PORT'=>'3308' , 'DEFAULT_DB'=>'newjs');

	/* shards-master-slave*/
	public static $shard1      = array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3307' , 'DEFAULT_DB'=>'newjs');
	public static $shard2      = array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3308' , 'DEFAULT_DB'=>'newjs');
	public static $shard3      = array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3309' , 'DEFAULT_DB'=>'newjs');

	public static $shard1Slave = array('HOST'=>'172.16.3.185', 'USER'=>'localSlave', 'PASS'=>'Km7Iv80l', 'PORT'=>'3307' , 'DEFAULT_DB'=>'newjs');
	public static $shard2Slave = array('HOST'=>'172.16.3.185', 'USER'=>'localSlave', 'PASS'=>'Km7Iv80l', 'PORT'=>'3308' , 'DEFAULT_DB'=>'newjs');
	public static $shard3Slave = array('HOST'=>'172.16.3.185', 'USER'=>'localSlave', 'PASS'=>'Km7Iv80l', 'PORT'=>'3309' , 'DEFAULT_DB'=>'newjs');

	/* bms */
	public static $bms         = array('HOST'=>'172.16.3.185', 'USER'=>'bms', 'PASS'=>'Km7Iv80l', 'PORT'=>'3306' , 'DEFAULT_DB'=>'newjs');

	/* shard with dump grants inside match-alerts*/
	public static $shard1SlaveDump = array('HOST'=>'172.16.3.185', 'USER'=>'user_dump', 'PASS'=>'Km7Iv80l', 'PORT'=>'3307' , 'DEFAULT_DB'=>'newjs');
	public static $shard2SlaveDump = array('HOST'=>'172.16.3.185', 'USER'=>'user_dump', 'PASS'=>'Km7Iv80l', 'PORT'=>'3308' , 'DEFAULT_DB'=>'newjs');
	public static $shard3SlaveDump = array('HOST'=>'172.16.3.185', 'USER'=>'user_dump', 'PASS'=>'Km7Iv80l', 'PORT'=>'3309' , 'DEFAULT_DB'=>'newjs');

	/* alerts(match-alerts/mmm/kundli)*/
	public static $alerts         = array('HOST'=>'172.16.3.185', 'USER'=>'alerts', 'PASS'=>'Km7Iv80l', 'PORT'=>'3306' , 'DEFAULT_DB'=>'newjs','SOCKET'=>'');
	
	/* 99 Acres*/
	public static $BMS_99      = array('HOST'=>'10.208.65.98', 'USER'=>'user', 'PASS'=>'CLDLRTa9', 'PORT'=>'3306' , 'DEFAULT_DB'=>'');

	public static $MMM_99      = array('HOST'=>'172.16.3.43', 'USER'=>'user', 'PASS'=>'CLDLRTa9', 'PORT'=>'3306' , 'DEFAULT_DB'=>'');

	public static $productSlave  =array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3306' , 'DEFAULT_DB'=>'');
	public static $shard1Slave112=array('HOST'=>'172.16.3.185', 'USER'=>'localSlave', 'PASS'=>'Km7Iv80l', 'PORT'=>'3307' , 'DEFAULT_DB'=>'newjs');
	public static $shard2Slave112=array('HOST'=>'172.16.3.185', 'USER'=>'localSlave', 'PASS'=>'Km7Iv80l', 'PORT'=>'3308' , 'DEFAULT_DB'=>'newjs');
	public static $shard3Slave112=array('HOST'=>'172.16.3.185', 'USER'=>'localSlave', 'PASS'=>'Km7Iv80l', 'PORT'=>'3309' , 'DEFAULT_DB'=>'newjs');
	
	/* Master replication */
	public static $masterRep = array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3306' , 'DEFAULT_DB'=>'newjs');
	public static $shard1Rep = array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3307' , 'DEFAULT_DB'=>'newjs');
	public static $shard2Rep = array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3308' , 'DEFAULT_DB'=>'newjs');
	public static $shard3Rep = array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3309' , 'DEFAULT_DB'=>'newjs');
  	public static $viewLogRep = array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3308' , 'DEFAULT_DB'=>'newjs');
	
	/*Restricting db privileges*/
        public static $masterDDL=array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3306' , 'DEFAULT_DB'=>'newjs');
        public static $shard1DDL=array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3309' , 'DEFAULT_DB'=>'newjs');
        public static $shard2DDL=array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3306' , 'DEFAULT_DB'=>'newjs');
        public static $shard3DDL=array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3307' , 'DEFAULT_DB'=>'newjs');              public static $viewSimilarDDL=array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3307' , 'DEFAULT_DB'=>'newjs');
        public static $viewLogDDL=array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3307' , 'DEFAULT_DB'=>'newjs');
        public static $alertsDDL=array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3307' , 'DEFAULT_DB'=>'newjs');
	public static $shard1SlaveDDL=array('HOST'=>'172.16.3.185','USER'=>'localuser','PASS'=>'Km7Iv80l','PORT'=>'3309','DEFAULT_DB'=>'newjs');
        public static $shard2SlaveDDL=array('HOST'=>'172.16.3.185','USER'=>'localuser','PASS'=>'Km7Iv80l','PORT'=>'3306','DEFAULT_DB'=>'newjs');
        public static $shard3SlaveDDL=array('HOST'=>'172.16.3.185','USER'=>'localuser','PASS'=>'Km7Iv80l','PORT'=>'3307','DEFAULT_DB'=>'newjs');
	/*end*/
        public static $matchalertsSlave = array('HOST'=>'172.16.3.185', 'USER'=>'localuser', 'PASS'=>'Km7Iv80l', 'PORT'=>'3306' , 'DEFAULT_DB'=>'newjs');

}
