<?php
require_once JsConstants::$docRoot . "/commonFiles/SymfonyPictureFunctions.class.php";
include JsConstants::$docRoot . "/../apps/operations/lib/crmUtility.class.php";
include "connect.inc";
include "mainmenunew.php";
include "viewprofilenew.php";
include JsConstants::$docRoot . "/commonFiles/flag.php";
include "history.php";

if (authenticated($cid)) {
    $smarty->assign("MODE", $mode);
    $name = getname($cid);
    if ($history || $submit) {
        if ($history) {
            if (trim($USERNAME) == '') {
                $smarty->assign("check_username", "Y");
            } else {
                $profileid = '';
                $USERNAME  = addslashes(stripslashes($USERNAME));
                $sql       = "SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$USERNAME'";
                $result    = mysql_query_decide($sql) or die("$sql" . mysql_error_js());
                if ($myrow = mysql_fetch_array($result)) {
                    $profileid = $myrow['PROFILEID'];
                } else {
                    $smarty->assign("wrong_username", "Y");
                }

                if ($profileid) {
                    $privilage = explode("+", getprivilage($cid));
                    if (in_array("SLHD", $privilage) || in_array("SLSUP", $privilage) || in_array("P", $privilage) || in_array("MG", $privilage) || in_array("TRNG", $privilage)) {
                        $limit = 0;
                    } else {
                        $limitCount = getHistoryCount($profileid);
                        if ($limitCount >= 5) {
                            $limit = $limitCount;
                        } else {
                            $limit = 5;
                        }

                    }

                    $smarty->assign("PROFILEID", $myrow['PROFILEID']);
                    $smarty->assign("USERNAME", stripslashes($USERNAME));
                    $user_values = gethistory($USERNAME, $limit);
                    $smarty->assign("ROW", $user_values);
                    /*$pmsg=viewprofile($USERNAME,"internal");
                    $smarty->assign("pmsg",$pmsg);
                    $checksum=md5($profileid)."i".$profileid;
                    $msg=profileview($profileid,$checksum);
                    $smarty->assign("msg",$msg);*/

                    // New show stats page
                    $crmUtilityObj = new crmUtility();
                    $msg           = $crmUtilityObj->getCurlData($profileid, '', $cid);
                    $smarty->assign("pmsg", $msg);
                }
            }
            $willpay_val = populate_will_pay('');
            $smarty->assign("WILL_PAY", $willpay_val);
            $reasonopt = "";
            $reasonopt = willpay_populate_reason('', '');
            $smarty->assign("REASON", $reasonopt);
            $smarty->assign("cid", $cid);
            $smarty->display("only_service.htm");
        } elseif ($submit) {
            $is_error = 0;

            if (!$USERNAME) {
                $smarty->assign("check_username", "Y");
                $is_error++;
            } else {
                $sql    = "SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='" . addslashes(stripslashes($USERNAME)) . "'";
                $result = mysql_query_decide($sql) or die("$sql" . mysql_error_js());
                if ($myrow = mysql_fetch_array($result)) {
                    $profileid = $myrow['PROFILEID'];
                } else {
                    $is_error++;
                    $smarty->assign("wrong_username", "Y");
                }
            }
            if ($WILL_PAY == 'AA|X') {
                $will_pay = explode("|X", $WILL_PAY);
                $WILL_PAY = $will_pay[0];
            } else {
                $will_pay = explode("|X|", $WILL_PAY);
                $WILL_PAY = $will_pay[0];
            }
            if ($is_error >= 1) {
                $smarty->assign("USERNAME", stripslashes($USERNAME));
                $smarty->assign("WILL_PAY", $WILL_PAY);
                $smarty->assign("COMMENTS", $COMMENTS);
                $smarty->assign("MODE", $mode);
                $checksum = md5($profileid) . "i" . $profileid;
                $smarty->assign("cid", $cid);
                $smarty->display("only_service.htm");
            } else {
                if (!empty($discountNegVal) && is_numeric($discountNegVal) && $discountNegVal > 0 && $discountNegVal < 100) {
                    $discNegObj = new incentive_DISCOUNT_NEGOTIATION_LOG();
                    $entryDt    = date("Y-m-d H:i:s");
                    $expiryDt   = date("Y-m-d H:i:s", (time() + 15 * 24 * 60 * 60));
                    $discNegObj->insert($name, $profileid, $discountNegVal, $entryDt, $expiryDt);
                    if($discountNegVal <= 10){
                        $agentAllocDetailsObj   =new AgentAllocationDetails();
                        $agentAllocDetailsObj->mailForLowDiscount(stripslashes($USERNAME),$name,$discountNegVal);
                        unset($agentAllocDetailsObj);
                    }
                }
                $sql4 = "INSERT INTO incentive.HISTORY (PROFILEID,USERNAME,ENTRYBY,MODE,DISPOSITION,VALIDATION,COMMENT,ENTRY_DT) VALUES ('$profileid','" . addslashes($USERNAME) . "','$name','$mode','$WILL_PAY','$REASON','" . addslashes($COMMENTS) . "',now())";
                mysql_query_decide($sql4) or die("4 $sql4" . mysql_error_js());

                $msg .= "The details you have feeded got logged.<br><br>";
                $msg .= "<a href=\"only_service.php?name=$name&cid=$cid&mode=$mode\">";
                $msg .= "Continue &gt;&gt;</a>";
                $smarty->assign("name", $name);
                $smarty->assign("cid", $cid);
                $smarty->assign("MODE", $mode);
                $smarty->assign("MSG", $msg);
                $smarty->display("crm_msg.tpl");
            }
        }
    } else {
        $willpay_val = populate_will_pay('');
        $smarty->assign("WILL_PAY", $willpay_val);
        $reasonopt = "";
        $reasonopt = willpay_populate_reason('', '');
        $smarty->assign("REASON", $reasonopt);
        $smarty->assign("name", $name);
        $smarty->assign("cid", $cid);
        $smarty->display("only_service.htm");
    }
} else //user timed out
{
    $msg = "Your session has been timed out<br>  ";
    $msg .= "<a href=\"index.php\">";
    $msg .= "Login again </a>";
    $smarty->assign("MSG", $msg);
    $smarty->display("crm_msg.tpl");
}
