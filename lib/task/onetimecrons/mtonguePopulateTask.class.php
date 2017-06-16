<?php

class mtonguePopulateTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'CRM';
		$this->name             = 'mtonguePopulateTask';
		$this->briefDescription = 'mtonguePopulateTask';
		$this->detailedDescription = <<<EOF
		The [mtonguePopulateTask|INFO] task populates the mtongue column in purchases table.
		Call it with:
		[php symfony CRM:mtonguePopulateTask|INFO]
EOF;
	}

    
    public function updatePurchases($profileid,$mtongue,$db){
        $sql5 = "UPDATE billing.PURCHASES SET MTONGUE ='$mtongue' where PROFILEID ='$profileid'";
        print_r("\n".$sql5."                 \n");
	$res5=mysql_query_decide($sql5,$db) or die("$sql5".mysql_error_js($db));
    }
    public function getMTongue($profileid,$db){
        $sql5 = "SELECT MTONGUE from newjs.JPROFILE WHERE PROFILEID = '$profileid'";
        print_r("\n".$sql5."                 \n");
	$res5=mysql_query_decide($sql5,$db) or die("$sql5".mysql_error_js($db));
        while($row1 = mysql_fetch_array($res5)){
            return $row1['MTONGUE'];
        }
    }

	protected function execute($arguments = array(), $options = array())
	{
            print_r("\nExecusion Started\n");
            $startDate = '2017-01-01 00:00:00';
            $endDate = '2017-05-31 23:59:59';
            include_once(JsConstants::$docRoot."/profile/connect_db.php");
            //get all transactions within date range 
            $db = connect_db();
            $sql = "select DISTINCT PROFILEID"
                        . " from billing.PURCHASES"
                        . " where ENTRY_DT between '$startDate' and '$endDate' and MTONGUE IS NULL";
            print_r($sql);
            
            $row = mysql_query_decide($sql, $db) or die("$sql Error" );
            while($row1 = mysql_fetch_array($row)){
                    $profileidArr[] = $row1['PROFILEID'];
            }
            $n = count($profileidArr);
            print_r("\nGot all Data, Number of records to update: $n");

            
            for($i=0;$i<$n;$i++){
                print_r("\n-------\n Going to update for $profileidArr[$i] \n");
                $mtongue = $this->getMTongue($profileidArr[$i],$db);
                $this->updatePurchases($profileidArr[$i],$mtongue,$db);
                die;
            }
            print_r("\nExecusion Finished\n");
	}
}
