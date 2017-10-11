<?php

/**
 * This task archives Data for different tables
 */

class archiveDataTask extends sfBaseTask
{
    protected function configure()
    {

    	$this->addArguments(array(new sfCommandArgument('dbName', sfCommandArgument::REQUIRED, 'DB NAME')));
		$this->addArguments(array(new sfCommandArgument('tableName', sfCommandArgument::REQUIRED, 'TABLE NAME')));
		$this->addArguments(array(new sfCommandArgument('days', sfCommandArgument::REQUIRED, 'PRESERVE DAYS')));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
        ));

        $this->namespace        = 'archive';
        $this->name             = 'archiveData';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
        The [archive|INFO] ARCHIVES TABLE DATA WITH SPECIFIED TIME PERIOD IN ARGUMENTS.
        Call it with:
        [php symfony archive:archiveData|INFO]
EOF;

    }

    protected function execute($arguments = array(), $options = array())
    {   
        
        if (!sfContext::hasInstance()) {
            sfContext::createInstance($this->configuration);
        }
        
        $archiveStoreObj =  new archiveStore('newjs_slave');

        $argDbName = $arguments["dbName"];
        $argTableName = $arguments["tableName"];
        $argDays = $arguments["days"];

        $startDt = date('Y-m-d H:i:s', time() - $argDays*24*3600);

        if($argDbName == 'billing' && $argTableName == 'PAYMENT_HITS') {
	        $argsArr = array("ENTRY_DT" => $startDt);
	        $argsCondArr = array("ENTRY_DT" => "<=");
	        $argsCondOppArr = array("ENTRY_DT" => ">");
	        $maxId = "ENTRY_DT";
	        $archiveStoreObj->archiveData($argDbName, $argTableName, $argsArr, $argsCondArr, $argsCondOppArr, $startDt, $maxId);
	}

        if($argDbName == 'incentive' && $argTableName == 'HISTORY') {
	        $argsArr = array("ENTRY_DT" => $startDt);
	        $argsCondArr = array("ENTRY_DT" => "<=");
	        $argsCondOppArr = array("ENTRY_DT" => ">");
	        $maxId = "ENTRY_DT";
	        $archiveStoreObj->archiveData($argDbName, $argTableName, $argsArr, $argsCondArr, $argsCondOppArr, $startDt, $maxId);
	}

        if($argDbName == 'MIS' && $argTableName == 'LOGIN_TRACKING') {
	        $argsArr = array("DATE" => $startDt);
	        $argsCondArr = array("DATE" => "<=");
	        $argsCondOppArr = array("DATE" => ">");
	        $maxId = "DATE";
	        $archiveStoreObj->archiveData($argDbName, $argTableName, $argsArr, $argsCondArr, $argsCondOppArr, $startDt, $maxId);
    	}

        if($argDbName == 'MIS' && $argTableName == 'SEARCHQUERY') {
	        $argsArr = array("DATE" => $startDt);
	        $argsCondArr = array("DATE" => "<=");
	        $argsCondOppArr = array("DATE" => ">");
	        $maxId = "DATE";
	        $archiveStoreObj->archiveData($argDbName, $argTableName, $argsArr, $argsCondArr, $argsCondOppArr, $startDt, $maxId);
	}

        if($argDbName == 'MOBILE_API' && $argTableName == 'LOCAL_NOTIFICATION_LOG') {
	        $argsArr = array("ENTRY_DATE" => $startDt);
	        $argsCondArr = array("ENTRY_DATE" => "<=");
	        $argsCondOppArr = array("ENTRY_DATE" => ">");
	        $maxId = "ENTRY_DATE";
	        $archiveStoreObj->archiveData($argDbName, $argTableName, $argsArr, $argsCondArr, $argsCondOppArr, $startDt, $maxId);
	}

        if($argDbName == 'MOBILE_API' && $argTableName == 'GCM_RESPONSE_LOG') {
	        $argsArr = array("DATE" => $startDt);
	        $argsCondArr = array("DATE" => "<=");
	        $argsCondOppArr = array("DATE" => ">");
	        $maxId = "DATE";
	        $archiveStoreObj->archiveData($argDbName, $argTableName, $argsArr, $argsCondArr, $argsCondOppArr, $startDt, $maxId);
	}

        if($argDbName == 'MOBILE_API' && $argTableName == 'NOTIFICATION_LOG') {
	        $argsArr = array("SEND_DATE" => $startDt);
	        $argsCondArr = array("SEND_DATE" => "<=");
	        $argsCondOppArr = array("SEND_DATE" => ">");
	        $maxId = "SEND_DATE";
	        $archiveStoreObj->archiveData($argDbName, $argTableName, $argsArr, $argsCondArr, $argsCondOppArr, $startDt, $maxId);
	}

    }
}
