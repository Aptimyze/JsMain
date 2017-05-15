<?php

class cronFinanceDataInterfaceExcelTask extends sfBaseTask {

    protected function configure() {
        $this->namespace = 'cron';
        $this->name = 'cronFinanceDataInterfaceExcelTask';
        $this->briefDescription = 'This cron fetches data to be displayed for cronFinanceDataInterfaceExcelTask Mis';
        $this->detailedDescription = <<<EOF
The [cronFinanceDataInterfaceExcelTask|INFO] ADD DESCRIPTION.
Call it with:

  [php symfony cron:cronFinanceDataInterfaceExcelTask] 
EOF;
        $this->addArguments(array(
            new sfCommandArgument('AGENT', sfCommandArgument::OPTIONAL, 'My argument'),
            ));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'operations'),
        ));
    }

    protected function execute($arguments = array(), $options = array()) {
        ini_set('max_execution_time',0);
        ini_set('memory_limit',-1);
        if (!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
        $agent = $arguments["AGENT"];
        $memObject = JsMemcache::getInstance();
        $memcacheValue = $memObject->get("MIS_FDI_PARAMS_KEY_".$agent);
        //$memObject->delete("MIS_FDI_PARAMS_KEY_".$agent);

        $start_date = $memcacheValue['STARTDATE'];
        $end_date = $memcacheValue['ENDDATE'];
        $device = $memcacheValue['DEVICE'];
        $mainKey = $memcacheValue['MAINKEYNAME'];
        $fileName = $memcacheValue['FILENAME'];
        // Data fetch logic
        $billServObj = new billing_SERVICES('newjs_slave');
        $purchaseObj = new BILLING_PURCHASES('newjs_slave');
        $this->serviceData = $billServObj->getFinanceDataServiceNames();
        $this->rawData = $purchaseObj->fetchFinanceData($start_date, $end_date, $device);
        $headerString = "Entry Date\tBillid\tReceiptid\tProfileid\tUsername\tServiceid\tService Name\tStart Date\tEnd Date\tCurrency\tList Price\tAmount\tDeferrable Flag\tASSD(Actual Service Start Date)\tASED(Actual Service End Date)\tInvoice No\r\n";
        if ($this->rawData && is_array($this->rawData)) {
            foreach ($this->rawData as $k => $v) {
                $dataString = $dataString . $v["ENTRY_DT"] . "\t";
                $dataString = $dataString . $v["BILLID"] . "\t";
                $dataString = $dataString . $v["RECEIPTID"] . "\t";
                $dataString = $dataString . $v["PROFILEID"] . "\t";
                $dataString = $dataString . $v["USERNAME"] . "\t";
                $dataString = $dataString . $v["SERVICEID"] . "\t";
                $dataString = $dataString . $this->serviceData[$v["SERVICEID"]] . "\t";
                $dataString = $dataString . $v["START_DATE"] . "\t";
                $dataString = $dataString . $v["END_DATE"] . "\t";
                $dataString = $dataString . $v["CUR_TYPE"] . "\t";
                $dataString = $dataString . $v["PRICE"] . "\t";
                $dataString = $dataString . $v["AMOUNT"] . "\t";
                $dataString = $dataString . $v["DEFERRABLE"] . "\t";
                $dataString = $dataString . $v["ASSD"] . "\t";
                $dataString = $dataString . $v["ASED"] . "\t";
                $dataString = $dataString . $v["INVOICE_NO"] . "\r\n";
            }
        }
        $xlData = $headerString . $dataString;
        //$string1 = $start_date . "_" . $end_date . "_". $device."_".$agent;
        //header("Content-Type: application/vnd.ms-excel");
        //header("Content-Disposition: attachment; filename=FinanceData_" . $string . ".xls");
        //header("Pragma: no-cache");
        //header("Expires: 0");
        //echo $xlData;
        //$fileName ="FDI_".$string1.".xls";
        //If you need to store in file instead of Redis, uncomment below  line
        //passthru("echo '$xlData' >>/usr/local/scripts/config/branch3/'$fileName'");
        
        //Storing computed data into a rediskey
        $memObject->set("MIS_FDI_PARAMS_KEY"."_".$agent, $xlData,3600);     //Setting time of 30 mins
        $memObject->set($mainKey,'Finished',3600);
        //echo $xlData;
        //die;
    }

}
