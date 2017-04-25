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
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'operations'),
        ));
    }

    protected function execute($arguments = array(), $options = array()) {
        if (!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
        $memObject = JsMemcache::getInstance();
        $memcacheValue = $memObject->get("MIS_FDI_PARAMS_KEY");
        $memObject->delete("MIS_FDI_PARAMS_KEY");

        $start_date = $memcacheValue['STARTDATE'];
        $end_date = $memcacheValue['ENDDATE'];
        $device = $memcacheValue['DEVICE'];
        $mainKey = $memcacheValue['MAINKEYNAME'];
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
        $string1 = $start_date . "_" . $end_date . "_". $device;
        //header("Content-Type: application/vnd.ms-excel");
        //header("Content-Disposition: attachment; filename=FinanceData_" . $string . ".xls");
        //header("Pragma: no-cache");
        //header("Expires: 0");
        //echo $xlData;
        $memObject->set($mainKey,'Finished');
        $fileName ="FDI_".$string1.".xls";
        passthru("echo '$xlData' >>/usr/local/scripts/config/branch3/'$fileName'");
        $memObject->set($mainKey,'Finished');
        //echo $xlData;
        //die;
    }

}
