<?php
/**
 * @desc: Cron to handle legacy data from the table MOBILE_API.BROWSER_NOTIFICATION_REGISTRATION to MOBILE_API.BROWSER_NOTIFICATION_LAYER. This is a one time cron and it will transfer all the entries frmo the first table to the second table.
 *
 * @author nitish
 */
class browserNotificationLayerLegacyTask extends sfBaseTask {
    
    protected function configure()
    {
        $this->namespace           = 'browserNotification';
        $this->name                = 'browserNotificationLayerLegacyTask';
        $this->briefDescription    = 'One time cron to handle legacy users from registration table to layer table';
        $this->detailedDescription = <<<EOF
      Call it with:[php symfony browserNotification:browserNotificationLayerLegacyTask|INFO]
EOF;
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
        ));
    }
    
    protected function execute($arguments = array(), $options = array()){
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
        $browserNotificationRegObj = new MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION("newjs_slave"); 
        $data = $browserNotificationRegObj->getAllWebsiteUsers();
        $browserNotificationLayerObj = new MOBILE_API_BROWSER_NOTIFICATION_LAYER(); //Master Object to insert
        //$layerData = $browserNotificationLayerObj->getAll();
        foreach($data as $key=>$val){
            //if(!$layerData[$val['PROFILEID']])
            {
                unset($paramsArr);
                $paramsArr['PROFILEID'] = $val[0]['PROFILEID'];
                $paramsArr['MOBILE_COUNT'] = 0;
                $paramsArr['DESKTOP_COUNT'] = 0;
                if($val[0]['CHANNEL'] == 'M'){
                    $paramsArr['MOBILE_LAST_CLICK'] = $val[0]['ENTRY_DT'];
                    $paramsArr['MOBILE_LAYER'] = 'Y';
                    if($val[1]['CHANNEL'] == 'D'){
                        $paramsArr['DESKTOP_LAST_CLICK'] = $val[1]['ENTRY_DT'];
                        $paramsArr['DESKTOP_LAYER'] = 'Y';
                    }
                }
                else{
                    $paramsArr['DESKTOP_LAST_CLICK'] = $val[0]['ENTRY_DT'];
                    $paramsArr['DESKTOP_LAYER'] = 'Y';
                    if($val[1]['CHANNEL'] == 'M'){
                        $paramsArr['MOBILE_LAST_CLICK'] = $val[1]['ENTRY_DT'];
                        $paramsArr['MOBILE_LAYER'] = 'Y';
                    }
                }
                $browserNotificationLayerObj->insert($paramsArr);
            }
        }
    }
}
