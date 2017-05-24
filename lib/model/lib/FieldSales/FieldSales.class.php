<?php

class FieldSales
{

       public function edit($paramArr=array(),$Id,$Id2='')
        {
                $jsadminObj = new jsadmin_PSWRDS;
                $status=$jsadminObj->edit($paramArr,$Id);
                return $status;
        }

        /*wrapper function to insert selected columns in row of table FIELD_SALES_WIDGET
        * @input : $paramsArr
        * @return : none
        */
        public function insertSelectedParams($paramsArr)
        {
        	$FSWidgetObj = new incentive_FIELD_SALES_WIDGET();
        	$FSWidgetObj->insertSelectedParams($paramsArr);
        	unset($FSWidgetObj);
        }

        /*wrapper function to fetch field visit details from table FIELD_SALES_WIDGET
        * @input : $paramsArr
        * @return : none
        */
        public function getFieldVisitDetails($value="",$criteria="PROFILEID",$fields="*",$orderby="",$limit="")
        {
            $FSWidgetObj = new incentive_FIELD_SALES_WIDGET("newjs_slave");
            $data = $FSWidgetObj->getArray($value,$criteria,$fields,$orderby,$limit);
            return $data;
        }

        /*wrapper function to update field visit details in table FIELD_SALES_WIDGET
        * @input :$criteria,$value,$updateArr,$extraWhereClause,$inWhereStr
        * @return : none
        */
        public function updateEntry($criteria="PROFILEID",$value="",$updateArr,$extraWhereClause="",$inWhereStr="")
        {
            $FSWidgetObj = new incentive_FIELD_SALES_WIDGET();
            $FSWidgetObj->updateEntry($criteria,$value,$updateArr,$extraWhereClause,$inWhereStr);
            unset($FSWidgetObj);
        }

        /*wrapper function to check for existing field visit details in table FIELD_SALES_WIDGET
        * @input :$profileid
        * @return : $output
        */
        public function checkProfileid($profileid)
        {
            $FSWidgetObj = new incentive_FIELD_SALES_WIDGET("newjs_slave");
            $output = $FSWidgetObj->checkProfileid($profileid);
            unset($FSWidgetObj);
            return $output;
        }

        /*function to trigger actions(send sms and mail to profile) after field visit request *submission
        * @input :$profileid,$sendMail(true/false),$sendSms(true/false)
        * @return : none
        */
        public function postFieldVisitRequestSubmit($profileid,$sendMail=true,$sendSms=true)
        {
            $memHandlerObj = new MembershipHandler();
            $profileDetails = $memHandlerObj->getUserData($profileid);
            unset($memHandlerObj);
            //send mail
            if($sendMail==true)
            {
                $mailerObj = new MembershipMailer();
                $profileDetails["PROFILEID"] = $profileid;
                $mailerObj->sendServiceActivationMail(1820, $profileDetails);
                unset($mailerObj);
            }
            //send sms
            if($sendSms==true)
            {
                if($profileDetails["PHONE_MOB"])
                {
                    CommonUtility::sendPlusTrackInstantSMS("FIELD_VISIT_SCHEDULE",$profileid);
                }
            }
            unset($profileDetails);
        }
}
