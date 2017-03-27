<?php
/**
 *  This task is for populating data in visitoralert.MAILER_VISITORS
 */
class VisitorAlertPopulateTask extends sfBaseTask
{

  protected function configure()
    {
    $this->addOptions(array(
    new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
       ));
        
        $this->namespace = 'VisitorAlert';
        $this->name = 'VisitorAlertPopulate';
        $this->briefDescription='';
        $this->detailedDescription = <<<EOF
          Call it with:
          [php symfony VisitorAlert:VisitorAlertPopulate]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        if(!sfContext::hasInstance())
            sfContext::createInstance ($this->configuration);
        ini_set('memory_limit','1024M');

        $lastLoginDate = date('Y-m-d', strtotime(VisitorAlertEnums::LAST_LOGIN_DATE));


        $visitoralertMailerVisitors = new visitorAlert_MAILER('shard1_masterDDL');

        $result = $visitoralertMailerVisitors->countTotalSent();

        $result['TOTAL'] == NULL ? $total = 0:$total = $result['TOTAL'];
        $result['COUNT'] == NULL ? $count = 0:$count = $result['COUNT'];

        $visitorAlertRecord = new visitorAlert_RECORD('shard1_masterDDL');


        $visitorAlertRecord->updateVisitorAlertRecord($total,$count);        

        $visitoralertMailerVisitors->truncateMailerVisitorsData();

        $date = date("Y-m-d",time()-VisitorAlertEnums::ONE_DAY_LIMIT);

        $viewLogTrigger=new VIEW_LOG_TRIGGER('shard2_slave');

        $receiverData = $viewLogTrigger->getViewedProfiles($date);

        if(is_array($receiverData))
        {
            $chunkReceiverData = array_chunk($receiverData, VisitorAlertEnums::ARRAY_CHUNK_VISITOR_ALERT);

            $jprofileObj = JPROFILE::getInstance('newjs_slave');

            foreach($chunkReceiverData as $i => $chunkReceiver)
            {
                $arrayReceivers = array();
                $visitoralert_VISITOR_ALERT_OPTION = new visitoralert_VISITOR_ALERT_OPTION('shard1_slave');
                // $arrayReceivers = ($visitoralert_VISITOR_ALERT_OPTION->fetchReceivers($chunkReceiver));

                $arrayReceiversNegate = ($visitoralert_VISITOR_ALERT_OPTION->fetchReceivers($chunkReceiver));
                foreach($chunkReceiver as $k=>$rec){
                        if(!is_array($arrayReceiversNegate) || !in_array($rec["VIEWED"], $arrayReceiversNegate))
                                $arrayReceivers[] = $rec["VIEWED"];
                }  

                if ( count($arrayReceivers) > 0) 
                {
                    $arrayReceiversCommaSeparated = implode(', ', $arrayReceivers);

                    $valueArray = array("PROFILEID"=>$arrayReceiversCommaSeparated,"activatedKey"=>1,'ACTIVATED'=>"Y");
                    $greaterThanArray = array("LAST_LOGIN_DT"=>$lastLoginDate);
                    $detailArr = array();
                    $excludeArray = array("PRIVACY"=>"'C'");
                    $detailArr = $jprofileObj->getArray($valueArray,$excludeArray,$greaterThanArray,'PROFILEID','','','','','','','','');
                    if ( is_array($detailArr))
                    {
                        $visitoralertMailerVisitors->insertReceiverData($detailArr);
                    }
                }

            }
        }
    }
}
