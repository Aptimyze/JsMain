<?php
/**
* This will populate/truncate the data used for savedSearch. 
*/
class savedSearchPopulateTask extends sfBaseTask
{
	protected function configure()
    {
        $this->addArguments(array(
                        new sfCommandArgument('totalScripts', sfCommandArgument::REQUIRED, 'My argument'),
                        new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
        	));

		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
        
        $this->namespace = 'savedSearch';
        $this->name = 'savedSearchPopulate';
        $this->briefDescription='';
        $this->detailedDescription = <<<EOF
          Call it with:
          [php symfony savedSearch:savedSearchPopulate totalScripts currentScript]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        if(!sfContext::hasInstance())
            sfContext::createInstance ($this->configuration);
        ini_set('memory_limit','512M');

        $totalScripts = $arguments["totalScripts"]; // total no of scripts
        $currentScript = $arguments["currentScript"]; // current script number
        

        $curl = curl_init();
         //file_put_contents('the_file.txt', print_R($_POST,true), FILE_APPEND);
        $jsonArr = array("callbackUrl"=>"http://xmppdev.jeevansathi.com/api/v1/profile/dppSuggestions" ,"city"=> "Noida","gender"=>"M","name"=> " Ankit Shukla","phone"=> "9755158977","professionId"=> 69,"uid"=>"840198979729");
       $jsonEncodedArr = json_encode($jsonArr);
       //print_R($jsonEncodedArr);die;
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-staging.ongrid.in/app/v1/verify/aadhaar",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $jsonEncodedArr,
          CURLOPT_HTTPHEADER => array(
            "Authorization: Basic anZuc2F0aGk6ZXdoT1NuSHpmd1p1WExWNmhyQjZ1VG16REhCa291T25pTXEwU3FLTDFJMkgzUTlKUWsyNWRDblRCWkVScDJTYg==",
            "content-type: application/json"
            ),
          ));

        $response = curl_exec($curl);
       
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
      } else {
          echo $response;
}
       // $savedSearchObj = new send_saved_search_mail();
		
		/*//Truncate table Data       
		$savedSearchDDLObj = new send_saved_search_mail('newjs_masterDDL');
		$savedSearchDDLObj->truncateSavedSearchData();

        $lastLoginDate = date('Y-m-d', strtotime("-1 month"));

        //select from slave
		$selectSearchAgentObj = new SEARCH_AGENT("newjs_slave");
		$receiverData = $selectSearchAgentObj->selectSavedSearchMailerData($currentScript,$totalScripts,$lastLoginDate);
        unset($selectSearchAgentObj);
        
        if(is_array($receiverData))
        {
            //insert to master
            $insertDataObj = new SEARCH_AGENT("newjs_master");
            $insertDataObj->insertSavedSearchMailerData($receiverData);
            unset($insertDataObj);
        }*/
    }
}
