<?php

/* Messages for the callnow feature in IVR application
 * $IVR_errorCodeArr array contains the messages 
*/
$IVR_errorCodeArr = Array();

$IVR_errorCodeArr['ERROR'] 		          		='2';	 
$IVR_errorCodeArr['ERROR_DIALCODE']               		='3';
$IVR_errorCodeArr['CALLER_PHONE_NOT_REGISTERED']       		='4';
$IVR_errorCodeArr['CALLER_DIRECT_CALL_QUOTA_EXPIRED'] 		='5';
$IVR_errorCodeArr['CALLER_UNPAID_MEMBER']          		='6';
$IVR_errorCodeArr['CALLER_FILTERED']                 		='7';
$IVR_errorCodeArr['CALLER_DECLINED']                 		='8';
$IVR_errorCodeArr['CALLER_IGNORED']         			='9';
$IVR_errorCodeArr['CALLER_ALREADY_CALLED'] 			='10';
$IVR_errorCodeArr['RECEIVER_NRI']                    		='11';
$IVR_errorCodeArr['RECEIVER_HIDDEN']              		='12';
$IVR_errorCodeArr['RECEIVER_DELETED']             		='13';
$IVR_errorCodeArr['RECEIVER_SCREENING']           		='14';
$IVR_errorCodeArr['RECEIVER_INCOMPLETE']           		='15';
$IVR_errorCodeArr['RECEIVER_SAME_GENDER']               	='16';
$IVR_errorCodeArr['RECEIVER_NO_PHONE']                		='17';
$IVR_errorCodeArr['CALLER_NRI']                			='21';
$IVR_errorCodeArr['CALLER_CALLTIME']                		='22';
$IVR_errorCodeArr['CALLER_PHONE_NOT_REGISTERED_WITH_DIALER']    ='23';
$IVR_errorCodeArr['RECEIVER_PHONE_HIDDEN']    			='24';
$IVR_errorCodeArr['RECEIVER_DNC']    				='25';
$IVR_errorCodeArr['RECEIVER_INVALID']    			='26';
$IVR_errorCodeArr['CALLER_DELETED']    				='27';
$IVR_errorCodeArr['CALLER_NOT_AVAILABLE']			='28';

$IVR_errorDesc = Array();
$IVR_errorDesc[0] = 'JS_FAILURE';
$IVR_errorDesc[1] = 'JS_SUCCESS';
$IVR_errorDesc[2] = 'JS_ERROR';
$IVR_errorDesc[3] = 'JS_ERROR_DIALCODE';
$IVR_errorDesc[4] = 'JS_CALLER_PHONE_NOT_REGISTERED';
$IVR_errorDesc[5] = 'JS_CALLER_DIRECT_CALL_QUOTA_EXPIRED';
$IVR_errorDesc[6] = 'JS_CALLER_UNPAID_MEMBER';
$IVR_errorDesc[7] = 'JS_CALLER_FILTERED';
$IVR_errorDesc[8] = 'JS_CALLER_DECLINED';
$IVR_errorDesc[9] = 'JS_CALLER_IGNORED';
$IVR_errorDesc[10] = 'JS_CALLER_ALREADY_CALLED';
$IVR_errorDesc[11] = 'JS_RECEIVER_NRI';
$IVR_errorDesc[12] = 'JS_RECEIVER_HIDDEN';
$IVR_errorDesc[13] = 'JS_RECEIVER_DELETED';
$IVR_errorDesc[14] = 'JS_RECEIVER_SCREENING';
$IVR_errorDesc[15] = 'JS_RECEIVER_INCOMPLETE';
$IVR_errorDesc[16] = 'JS_RECEIVER_SAME_GENDER';
$IVR_errorDesc[17] = 'JS_RECEIVER_NO_PHONE';
$IVR_errorDesc[18] = 'NO_INPUT_PHONE';
$IVR_errorDesc[19] = 'NO_INPUT_DIALCODE';
$IVR_errorDesc[20] = 'NO_INPUT_DIALER_STATUS';
$IVR_errorDesc[21] = 'JS_CALLER_NRI';
$IVR_errorDesc[22] = 'JS_CALLER_CALLTIME';
$IVR_errorDesc[23] = 'JS_CALLER_PHONE_NOT_REGISTERED_WITH_DIALER';
$IVR_errorDesc[24] = 'JS_RECEIVER_PHONE_HIDDEN';
$IVR_errorDesc[25] = 'JS_RECEIVER_DNC';
$IVR_errorDesc[26] = 'JS_RECEIVER_INVALID';
$IVR_errorDesc[27] = 'JS_CALLER_DELETED';
$IVR_errorDesc[28] = 'JS_CALLER_NOT_AVAILABLE';

/*$JS_msgStrArr = Array();
$JS_msgStrArr['ERROR']                         ="Sorry. You cannot call the person. Please try later";
$JS_msgStrArr['CALLER_ALREADY_CALLED']                  ="You have already called the user twice using this service and cannot call this person anymore. In case you wish to contact this profile.";
$JS_msgStrArr['CALLER_NRI']                    ="You can try this only from a verified Indian number.";
$JS_msgStrArr['RECEIVER_NRI']        ="This profile does not have a number in India. You can only call Indian numbers through this feature. To contact this profile ";
$JS_msgStrArr['CALLER_FILTERED']                  ="You cannot call this user as you do not satisfy the user's filter criteria.";
$JS_msgStrArr['CALLER_IGNORED']          ="This user has declined further communication with you.";
$JS_msgStrArr['CALLER_DECLINED']               ="This user has declined your expression of interest.";
$JS_msgStrArr['CALLER_UNPAID_MEMBER']           ="You need to be a paid member to be able to talk to users using Call Now feature.";
$JS_msgStrArr['RECEIVER_SAME_GENDER']                   ="You cannot call a person of the same gender.";
*/
?>
