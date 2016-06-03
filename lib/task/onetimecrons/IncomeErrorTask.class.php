<?php
/*
 * Author: Kumar Anand
*/
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/classes/shardingRelated.php");

class IncomeErrorTask extends sfBaseTask
{
	protected function configure()
        {
                $this->addOptions(array(
                new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
             ));

            $this->namespace        = 'cron';
            $this->name             = 'IncomeError';
            $this->briefDescription = 'onetimecron';
            $this->detailedDescription = <<<EOF
        Call it with:

          [php symfony cron:IncomeError]
EOF;
        }

        protected function execute($arguments = array(), $options = array())
        {
		global $noOfActiveServers;
		$mysqlObj = new Mysql;

		if(!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);

		for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
		{
			$myDbName=getActiveServerName($activeServerId);
			$myDbNameS=getActiveServerName($activeServerId,"slave");
			$myDbSlave=$mysqlObj->connect("$myDbNameS");
			$myDbMaster=$mysqlObj->connect("$myDbName");
			mysql_query("set session wait_timeout=10000",$myDbSlave);
			mysql_query("set session wait_timeout=10000",$myDbMaster);

			$sql = "SELECT PROFILEID,LINCOME,HINCOME FROM newjs.JPARTNER WHERE LINCOME!='' AND HINCOME!='' AND LINCOME_DOL='' AND HINCOME_DOL=''";
			$result = $mysqlObj->executeQuery($sql,$myDbSlave);
			while($row = $mysqlObj->fetchArray($result))
			{
				$rArr["minIR"] = $row["LINCOME"];
                                $rArr["maxIR"] = $row["HINCOME"];
                                $incomeType = "R";
                                $incomeMappingObj = new IncomeMapping($rArr,"");
                                $incomeMappingObj->getMappedValues();
                                $updateArr["LINCOME_DOL"] = $incomeMappingObj->getIncomeArr("minID");
                                $updateArr["HINCOME_DOL"] = $incomeMappingObj->getIncomeArr("maxID");
                                unset($incomeMappingObj);
				$sql1 = "UPDATE newjs.JPARTNER SET LINCOME_DOL = '".$updateArr["LINCOME_DOL"]."', HINCOME_DOL = '".$updateArr["HINCOME_DOL"]."' WHERE PROFILEID = ".$row["PROFILEID"]." AND LINCOME = '".$row["LINCOME"]."' AND HINCOME = '".$row["HINCOME"]."'";
				$mysqlObj->executeQuery($sql1,$myDbMaster);
				unset($updateArr);
				unset($rArr);
				//echo $sql1."\n";
			}
			unset($row);

			
			$sql = "SELECT PROFILEID,LINCOME_DOL,HINCOME_DOL FROM newjs.JPARTNER WHERE LINCOME_DOL!='' AND HINCOME_DOL!='' AND LINCOME='' AND HINCOME=''";
			$result = $mysqlObj->executeQuery($sql,$myDbSlave);
			while($row = $mysqlObj->fetchArray($result))
			{
				$dArr["minID"] = $row["LINCOME_DOL"];
                                $dArr["maxID"] = $row["HINCOME_DOL"];
                                $incomeType = "D";
                                $incomeMappingObj = new IncomeMapping("",$dArr);
                                $incomeMappingObj->getMappedValues();
                                $updateArr["LINCOME"] = $incomeMappingObj->getIncomeArr("minIR");
                                $updateArr["HINCOME"] = $incomeMappingObj->getIncomeArr("maxIR");
                                unset($incomeMappingObj);
				$sql1 = "UPDATE newjs.JPARTNER SET LINCOME = '".$updateArr["LINCOME"]."', HINCOME = '".$updateArr["HINCOME"]."' WHERE PROFILEID = ".$row["PROFILEID"]." AND LINCOME_DOL = '".$row["LINCOME_DOL"]."' AND HINCOME_DOL = '".$row["HINCOME_DOL"]."'";
				$mysqlObj->executeQuery($sql1,$myDbMaster);
				unset($updateArr);
				unset($dArr);
				//echo $sql1."\n";
			}
			unset($row);
			echo "SHARD".$activeServerId." DONE\n";
		}		
	}
}
?>
