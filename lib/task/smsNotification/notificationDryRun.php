<?php

class gcmCleanerTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
                    new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name',"api"),
                    new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
                    new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                    ));

        $this->addArguments(array(new sfCommandArgument('actionType', sfCommandArgument::REQUIRED, 'Specify Task Command - Cleanup/Optimize')));

        $this->namespace        = 'notification';
        $this->name             = 'gcmCleaner';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
            The [gcmCleaner] can be used to cleanup/optimize
            nimanager.GCM_ACTIVE_USERS and nimanager.GCM_REGISTERED_USER
            Call it with: [php symfony gcmCleaner <actionType>]
            <actionType> can be 'optimize|deleteInactive|cleanup'\n
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        try{
        // initialize the database connection

            if($arguments['actionType'] == 'cleanup'){
		
		// file to write response
                $responsesFileName = '/tmp/gcmResponse'.date('Y-m-d');

                $apnsPrimaryKey =0;
                $sleepCounter = 0;
		$limit =100;

		$this->registrationIdObj = new MOBILE_API_REGISTRATION_ID('newjs_slave');
		$this->registrationIdMasterObj = new MOBILE_API_REGISTRATION_ID();

		$maxIdData 		= $this->registrationIdObj->getArray("","","","max(ID) as maxId");
		$this->maxId 		= $maxIdData[0][maxId];
		$this->doneTillId 	= 1;

		while($this->doneTillId<=$this->maxId)
		{
                    $tokenArr = $getDetails();
                    if(count($tokenArr) == 0) 
			continue;

		    foreach($tokenArr as $key=>$registrationIdArr){	

                    	$gcmResponse 	= json_decode($this->sendDryGCM($registrationIdArr), true);
                    	$gcmResult 	= $gcmResponse['results'];

			// write content to file
                    	file_put_contents($responsesFileName, json_encode($gcmResult)."\n", FILE_APPEND);

                    	$userIdsToDelete = array();
                    	$deviceTokenHashesToDelete = array();

                    	for($i = 0; $i < count($gcmResult); $i++){
                            if(isset($gcmResult[$i]['registration_id'])){
                        	    $newRegId = $gcmResult[$i]['registration_id'];
                       	    }
                            if(isset($gcmResult[$i]['error'])){
                            	if($gcmResult[$i]['error'] == 'InvalidRegistration' || $gcmResult[$i]['error'] == 'NotRegistered'){
                            	    $deviceTokenHashesToDelete[] 	= $tokenArr[$i]['device_token'];
                            	}
                            }
                    	}
                    	if(count($deviceTokenHashesToDelete) > 0){
				foreach($deviceTokenHashesToDelete as $key1=>$regId)
	                    	    $this->registrationIdMasterObj->deleteRegId($regId);
                    	}
		    }
		    unset($tokenArr);	
		}
                // Let the DB breathe
                if((++$sleepCounter)%10 == 0) sleep(1);
                
            }
        }
        catch(Exception $exe){
            $message = "GCM Cleanup/Optimize Stopped";
        }
    }

    private function getDetails()
    {
        $limit = 100;
	$dateTime1 ='2017-01-01 00:00:00';	
	$dateTime2 ='2017-01-01 00:00:00';
	$limit1 =$this->doneTillId;
	$limit2 =$this->doneTillId+$limit;

        $details = $this->registrationIdObj->getAppRegisteredProfile('AND', $dateTime1, $dateTime2, $limit1, $limit2);
        $this->doneTillId = $this->doneTillId+$limit;
        if(is_array($details))
                return $details;
        return false;
    }

    private function sendDryGCM($registration_ids){
        $message = array("pushId"=>5,"message"=>"Product Update","type"=>"app","value"=>"PROD");
        $fields = array('registration_ids' => $registration_ids,'data' => $message,'dry_run' => true);
        $headers = NotificationEnums::$GcmAppHeaders;;
        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            if ($result === FALSE || (curl_getinfo($ch, CURLINFO_HTTP_CODE) != '200')) {
                throw new Exception('Curl failed: ' . curl_error($ch));
            }
            curl_close($ch);
        }
        catch(Exception $e){
            file_put_contents('/tmp/gcmError', json_encode($registration_ids)."\tException message: ".$e->getMessage()."\n", FILE_APPEND);
        }
        return $result;
    }

}

