<?php
function populate_call_source()
{
        $call_source = array(
                                array('name' => 'Chat', 'value' => 'CHAT'),
                                array('name' => 'FAQ', 'value' => 'FAQ'),
                                array('name' => 'Inbound', 'value' => 'INB'),
                                array('name' => 'Mailer', 'value' => 'MAIL'),
                                array('name' => 'Walk-In', 'value' => 'WALKIN'),
				array('name' => 'Offline order', 'value' => 'OFFORDER'),
				array('name' => 'Confirm client', 'value' => 'CONCL'),
				array('name' => 'FP', 'value' => 'FP'),
				array('name' => 'Old client', 'value' => 'OCL'),
				array('name' => 'Reference call', 'value' => 'REF'),
				array('name' => 'Tele calling data', 'value' => 'TELE'),
				array('name' => 'Online search link', 'value' => 'ONSEARCH'),
				array('name' => 'Renewal Campaign', 'value' => 'RC'),
				array('name' => 'VD data', 'value' => 'VD'),
				array('name' => 'High Score VD', 'value' => 'HSVD'),
				array('name' => 'Field Sales', 'value' => 'FS'),
				array('name' => 'Upsell', 'value' => 'U'),
                array('name' => 'Request Callback', 'value' => 'RCB')
                                );
                                                                                                                             
        return $call_source;
}
                                                                                                                             
function populate_query_type()
{
        $query_type = array(
                                array('name' => 'Branch Details', 'value' => 'BDET'),
                                array('name' => 'Cheque Pick Up', 'value' => 'CHPK'),
                                array('name' => 'DOB Change', 'value' => 'DOBC'),
                                array('name' => 'Feedback on Website', 'value' => 'FDBK'),
                                array('name' => 'Gender Change', 'value' => 'GENC'),
                                array('name' => 'Jeevansathi Messenger Related', 'value' => 'JMSN'),
                                array('name' => 'Match Alert', 'value' => 'MA'),
                                array('name' => 'Membership Features and Benefits', 'value' => 'MEMB'),
                                array('name' => 'Membership Fee', 'value' => 'MEMF'),
                                array('name' => 'Offers and Scheme Query', 'value' => 'OFFRS'),
                                array('name' => 'Payment Details', 'value' => 'PD'),
                                array('name' => 'Payment Mode', 'value' => 'PM'),
                                array('name' => 'Photo Upload', 'value' => 'PU'),
                                array('name' => 'Screening of Profile', 'value' => 'SCP'),
                                array('name' => 'Registration', 'value' => 'REG'),
                                array('name' => 'Website Complaint', 'value' => 'WEBCOMP'),
                                array('name' => 'Website Query', 'value' => 'WEBQ'),
                                );
                                                                                                                             
        return $query_type;
}
?>
