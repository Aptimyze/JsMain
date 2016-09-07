<?php
include_once(JsConstants::$alertDocRoot."/kundli/commonIncludeFileForLogic.php");

$populateTablesObj = new PopulateTables($localdb,$mysqlObj);
$populateTablesObj->truncate_table("KUNDLI_RECEIVER_PAID");
$populateTablesObj->truncate_table("PROFILE_LOGS_PAID");
$populateTablesObj->populate_receiver_table(1);
$file = $populateTablesObj->getLock();
$populateTablesObj->truncate_table("SEARCH_MALE_PAID");
$populateTablesObj->truncate_table("SEARCH_FEMALE_PAID");
$populateTablesObj->populate_search_table(1);
$populateTablesObj->releaseLock($file);

        $sql_loop="SELECT A.PROFILEID,A.GENDER,A.START_DT,A.END_DT FROM kundli_alert.KUNDLI_RECEIVER_PAID A LEFT JOIN kundli_alert.PROFILE_LOGS_PAID B ON A.PROFILEID=B.PROFILEID WHERE B.PROFILEID IS NULL";
        $result_loop=$mysqlObj->executeQuery($sql_loop,$localdb) or die($sql_loop);
        while($row_loop=$mysqlObj->fetchArray($result_loop))
        {
		if($row_loop["START_DT"]=="0000-00-00 00:00:00" || !$row_loop["START_DT"])
			$start_dt = "";
		else
			$start_dt = $row_loop["START_DT"];

		if($row_loop["END_DT"]=="0000-00-00 00:00:00" || !$row_loop["END_DT"])
			$end_dt = "";
		else
			$end_dt = $row_loop["END_DT"];

                $profileId=$row_loop["PROFILEID"];

                $sql="INSERT INTO kundli_alert.PROFILE_LOGS_PAID VALUES(".$profileId.")";
		$mysqlObj->executeQuery($sql,$localdb);
		
		$receiverObj=new Receiver($profileId,$localdb,"",1,$myDbArr,$mysqlObj);//get receiver profile
		if($receiverObj->getPartnerProfile()->getPROFILEID())
		{
			$StrategyKundliObj = new StrategyKundli($receiverObj,$localdb,$myDbArr,$mysqlObj,1,$start_dt,$end_dt);
			$StrategyKundliObj->doProcessing();
		}
        }
?>
