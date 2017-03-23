<?php
class MembershipActionFunctions
{
    
    public function getReqParamsForRevMobMem($request) {
        $displayPage = $request->getParameter('displayPage');
        if (!$displayPage) {
            $displayPage = 1;
        }
        if($displayPage == 4){
            $upgradeMem = $request->getParameter('upgradeMem');
            if(!in_array($upgradeMem, VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){
                $upgradeMem = 'NA';
            }
        }
        else{
            $upgradeMem = 'NA';
        }
        $pageURL = "displayPage=" . $displayPage . "&JSX=1";
        $mainMem = "&mainMem=" . $request->getParameter('mainMem');
        $mainMemDur = "&mainMemDur=" . $request->getParameter('mainMemDur');
        $orderID = $request->getParameter("orderID");
        $device = $request->getParameter("device");
        if (empty($device)) {
            $device = "&device=mobile_website";
        }
        
        $mainMembership = "&mainMembership=" . $request->getParameter('mainMembership');
        $vasImpression = "&vasImpression=" . $request->getParameter('vasImpression');
        $authchecksum = "&AUTHCKECHSUM=" . $request->getParameter('AUTHCHECKSUM');
        $userProfile = $request->getParameter('userProfile');

        $fromBackend = $request->getParameter('from_source');
        $backendRedirect = $request->getParameter('backendRedirect');
        if ($fromBackend == "discount_link" || $backendRedirect == 1) {
            $checksum = $request->getParameter("chksum");
            $profilechecksum = $request->getParameter("profilechecksum");
            $reqid = $request->getParameter("reqid");
            if ($backendRedirect == 1) {
                $displayPage = 5;
                $pageURL = "displayPage=" . $displayPage . "&checksum=" . $checksum . "&profilechecksum=" . $profilechecksum . "&reqid=" . $reqid . "&backendRedirect=1&userProfile=" . $userProfile;
            } 
            else {
                $displayPage = 3;
                $fromBackend = 1;
                $pageURL = "displayPage=" . $displayPage . "&checksum=" . $checksum . "&profilechecksum=" . $profilechecksum . "&reqid=" . $reqid . "&from_source=discount_link&userProfile=" . $userProfile;
            }
        }

        $fromGCM = $request->getParameter('FROM_GCM');
        if ($fromGCM) {
            $pageURL.= "&FROM_GCM=" . $fromGCM;
            $msgId = $request->getParameter("messageId");
            $notificationKey = $request->getParameter("notificationKey");
            $loginData =$request->getAttribute("loginData");
            $profileid = ($loginData['PROFILEID'] ? $loginData['PROFILEID'] : null);
            //error_log("in api request membership ankita-".$msgId."---".$notificationKey);
            //file_put_contents("/home/ankita/Desktop/1.txt", serialize($request));
            NotificationFunctions::handleNotificationClickEvent(array("profileid"=>$profileid,"messageId"=>$msgId,"notificationKey"=>$notificationKey));
        }
        return array(
            $displayPage,
            $pageURL,
            $mainMem,
            $mainMemDur,
            $orderID,
            $device,
            $fromBackend,
            $checksum,
            $profilechecksum,
            $reqid,
            $mainMembership,
            $vasImpression,
            $authchecksum,
            $upgradeMem
        );
    }
    
    public function getReqParamsForJspcRevamp($request) {
        $displayPage = $request->getParameter('displayPage');
        if (!$displayPage) {
            $displayPage = 1;
        }
        $pageURL = "displayPage=" . $displayPage . "&JSX=1";
        $mainMem = "&mainMem=" . $request->getParameter('mainMem');
        $mainMemDur = "&mainMemDur=" . $request->getParameter('mainMemDur');
        $orderID = $request->getParameter("orderID");
        $device = $request->getParameter("device");
        if (empty($device)) {
            $device = "&device=desktop";
        }
        
        $mainMembership = "&mainMembership=" . $request->getParameter('mainMembership');
        $vasImpression = "&vasImpression=" . $request->getParameter('vasImpression');
        $authchecksum = "&AUTHCKECHSUM=" . $request->getParameter('AUTHCHECKSUM');
        $userProfile = $request->getParameter('userProfile');
        
        $fromBackend = $request->getParameter('from_source');
        $backendRedirect = $request->getParameter('backendRedirect');
        if ($fromBackend == "discount_link" || $backendRedirect == 1) {
            $checksum = $request->getParameter("chksum");
            $profilechecksum = $request->getParameter("profilechecksum");
            $reqid = $request->getParameter("reqid");
            if ($backendRedirect == 1) {
                $displayPage = 5;
                $pageURL = "displayPage=" . $displayPage . "&checksum=" . $checksum . "&profilechecksum=" . $profilechecksum . "&reqid=" . $reqid . "&backendRedirect=1&userProfile=" . $userProfile;
            } 
            else {
                $displayPage = 3;
                $fromBackend = 1;
                $pageURL = "displayPage=" . $displayPage . "&checksum=" . $checksum . "&profilechecksum=" . $profilechecksum . "&reqid=" . $reqid . "&from_source=discount_link&userProfile=" . $userProfile;
            }
        }
        
        return array(
            $displayPage,
            $pageURL,
            $mainMem,
            $mainMemDur,
            $orderID,
            $device,
            $fromBackend,
            $checksum,
            $profilechecksum,
            $reqid,
            $mainMembership,
            $vasImpression,
            $authchecksum
        );
    }
    
    public function formatDataForRevMobMem($request, $displayPage, $data) {
        switch ($displayPage) {
            case '1':
                $data['message'] = strip_tags($data['message']);
                $data['benefits_message'] = explode("<br>", $data['benefits_message']);
                if ($data['currency'] == 'RS') {
                    $data['currency'] = '&#8377;';
                } 
                else {
                    $data['currency'] = '$';
                }
                if (!$loginData['PROFILEID']) {
                    $data['logout'] = 1;
                }
                if (isset($data['call_us'])) {
                    $data['call_us']['title'] = str_replace('<br>', ' ', $data['call_us']['title']);
                }
                break;

            case '2':
                
                $count = count($data['membership_plans']);
                foreach ($data['membership_plans'] as $kk => & $vv) {
                    if ($kk != ($count - 1)) {
                        if (strpos($data['membership_plans'][$kk + 1]['subscription_name'], "Exclusive")) {
                            $data['membership_plans'][$kk]['next_plan_name'] = "JS Exclus";
                        } 
                        else {
                            $data['membership_plans'][$kk]['next_plan_name'] = $data['membership_plans'][$kk + 1]['subscription_name'];
                        }
                    } 
                    else {
                        $data['membership_plans'][$kk]['next_plan_name'] = $data['membership_plans'][$kk - 1]['subscription_name'];
                    }
                    
                    if ($data['currency'] == "RS") {
                        if ($vv['subscription_id'] != "X") {
                            $data['membership_plans'][$kk]['starting_price'] = str_replace('RS', '&#8377;', $vv['starting_price']);
                        } 
                        else {
                            $data['membership_plans'][$kk]['starting_strikeout'] = str_replace('#', '', $data['membership_plans'][$kk]['starting_strikeout']);
                            $data['membership_plans'][$kk]['starting_strikeout'] = str_replace('RS', '&#8377;', $data['membership_plans'][$kk]['starting_strikeout']);
                            $data['membership_plans'][$kk]['starting_price_string'] = str_replace('RS', '&#8377;', $data['membership_plans'][$kk]['starting_price_string']);
                        }
                    } 
                    else {
                        if ($vv['subscription_id'] != "X") {
                            $data['membership_plans'][$kk]['starting_price'] = str_replace('DOL', '$', $vv['starting_price']);
                        } 
                        else {
                            $data['membership_plans'][$kk]['starting_strikeout'] = str_replace('#', '', $data['membership_plans'][$kk]['starting_strikeout']);
                            $data['membership_plans'][$kk]['starting_strikeout'] = str_replace('DOL', '$', $data['membership_plans'][$kk]['starting_strikeout']);
                            $data['membership_plans'][$kk]['starting_price_string'] = str_replace('DOL', '$', $data['membership_plans'][$kk]['starting_price_string']);
                        }
                    }
                    foreach ($vv['icon_visibility'] as $k => $v) {
                        $data['membership_plans'][$kk]['icon_visibility'][$k]['icon_name'] = str_replace("<br>", "", $v['icon_name']);
                    }
                    if ($vv['subscription_id'] == "X") {
                        $data['request_callback_params'] = $data['membership_plans'][$kk]['request_callback']['params'];
                    }
                }
                if ($data['currency'] == "RS") {
                    $data['currency'] = str_replace('RS', '&#8377;', $data['currency']);
                } 
                else {
                    $data['currency'] = '$';
                }
                break;

            case '3':
                
                $memHandlerObj = new MembershipHandler();
                list($ipAddress, $currency) = $memHandlerObj->getUserIPandCurrency();
                unset($memHandlerObj);
                $data['currency'] = $currency;
                foreach ($data['prices'] as $key => & $val) {
                    $data['prices'][$key]['price_top_value'] = filter_var($val['price_top'], FILTER_SANITIZE_NUMBER_FLOAT);
                    $data['prices'][$key]['price_bottom_value'] = filter_var($val['price_bottom'], FILTER_SANITIZE_NUMBER_FLOAT);
                    $data['prices'][$key]['price_top'] = str_replace('RS', '&#8377;', $val['price_top']);
                    $data['prices'][$key]['price_bottom'] = str_replace('RS', '&#8377;', $val['price_bottom']);
                    $data['prices'][$key]['price_top'] = str_replace('DOL', '$', $val['price_top']);
                    $data['prices'][$key]['price_bottom'] = str_replace('DOL', '$', $val['price_bottom']);
                }
                if ($data['currency'] == 'RS') {
                    $data['currency'] = str_replace('RS', '&#8377;', $data['currency']);
                } 
                else {
                    $data['currency'] = '$';
                }
                break;

            case '4':
                if (!empty($data['vas_services'])) {
                    $data['showVas'] = 1;
                    foreach ($data['vas_services'] as $key => & $val) {
                        $val['starting_price'] = str_replace('RS', '&#8377;', $val['starting_price']);
                        $val['starting_price'] = str_replace('DOL', '$', $val['starting_price']);
                        $val['vas_name'] = str_replace("<br>", "", $val['vas_name']);
                        foreach ($val['vas_options'] as $kk => & $vv) {
                            $vv['price_val'] = filter_var($vv['price'], FILTER_SANITIZE_NUMBER_FLOAT);
                            $vv['price'] = str_replace('RS', '&#8377;', $vv['price']);
                            $vv['price'] = str_replace('DOL', '$', $vv['price']);
                        }
                    }
                }
                if (!empty($data['cart_items']['vas_memberships'])) {
                    foreach ($data['cart_items']['vas_memberships'] as $key => & $val) {
                        $val['service_name'] = str_replace('<br>', '', $val['service_name']);
                    }
                }
                if ($data['currency'] == 'RS') {
                    $data['currency'] = str_replace('RS', '&#8377;', $data['currency']);
                } 
                else {
                    $data['currency'] = '$';
                }
                $data['cart_price_val'] = filter_var($data['cart_price'], FILTER_SANITIZE_NUMBER_FLOAT);
                $data['cart_price'] = str_replace('RS', '&#8377;', $data['cart_price']);
                $data['cart_price'] = str_replace('DOL', '$', $data['cart_price']);
                break;

            case '5':
                $data['order_content']['amount'] = str_replace('RS', '&#8377;', $data['order_content']['amount']);
                $data['order_content']['amount'] = str_replace('DOL', '$;', $data['order_content']['amount']);
                $data['order_content']['currency'] = str_replace('RS', '&#8377;', $data['order_content']['currency']);
                $data['order_content']['currency'] = str_replace('DOL', '$;', $data['order_content']['currency']);
                break;

            case '6':
                break;

            default:
                break;
        }
        array_walk_recursive($data, function (&$val, $key){
            $number = preg_replace('/[^\d.]/', '', $val);
            $fnumber = floatval(preg_replace('/[^\d.]/', '', $val));
            if(strpos($number, '.00' )){
                $val = number_format($fnumber, 0, '.', ',');
            }
        });
        return $data;
    }
    
    public function formatDataForNewRevMobMem($request, $displayPage, $data) {
        if ($data['currency'] == 'RS') {
            $data['currency'] = '&#8377;';
        } 
        elseif ($data['currency'] == 'DOL') {
            $data['currency'] = '$';
        }
        if(is_array($data)){
            $temp = $data["continueText"];
        }
        else{
            $temp = "";
        }
        
        array_walk_recursive($data, function (&$val, $key){
            $number = preg_replace('/[^\d.]/', '', $val);
            $fnumber = floatval(preg_replace('/[^\d.]/', '', $val));
            if(strpos($number, '.00' )){
                $val = number_format($fnumber, 0, '.', ',');
            }
        });
        if($data["upgradeMem"] == 'MAIN' && $displayPage=='3' && $data['currency'] == '$'){
            $data["continueText"] = $temp;
        }
        return $data;
    }

    public function getRemainingContacts($request) {
        $loginData = $request->getAttribute("loginData");
        if ($loginData['PROFILEID']) {
            $profileid = $loginData['PROFILEID'];
            $userObj = new memUser($profileid);
            $userObj->setMemStatus();
            $contactsRemaining = $userObj->getRemainingContacts($userObj->getProfileid());
            unset($userObj);
        }
        return $contactsRemaining;
    }
    
    public function setTickerData($date) {
        if ($date) {
            $days = floor((strtotime($date) - time()) / (60 * 60 * 24)) + 1;
        }
        if ($days <= 1 && $days >= 0) {
            $showCountdown = 1;
            $countdown = date("F j, Y 23:59:59", strtotime($date));
        }
        return array(
            $days,
            $showCountdown,
            $countdown
        );
    }
    
    public function getFestDurBanner($unlimitedId, $discountType, $profileid, $apiVersion = 3) {
        $festOffrLookObj = new billing_FESTIVE_OFFER_LOOKUP();
        $memHandlerObj = new MembershipHandler();
        $curLookupTable = $festOffrLookObj->retrieveCurrentLookupTable();
        $userObj = new memUser($profileid);
        if (!empty($profileid)) {
            $userObj->setMemStatus();
            $userType = $userObj->userType;
        }
        if (strpos(discountType::SPECIAL_DISCOUNT, $discountType) !== false) {
            $discount = $memHandlerObj->getSpecialDiscountForAllDurations($profileid);
            if (($userType == 4 || $userType == 6)) {
                $renewPerc = $memHandlerObj->getVariableRenewalDiscount($profileid);
            }
        } 
        else if (strpos(discountType::RENEWAL_DISCOUNT, $discountType) !== false || ($userType == 4 || $userType == 6)) {
            $renewPerc = $memHandlerObj->getVariableRenewalDiscount($profileid);;
        }
        $servArr[] = array();
        foreach ($curLookupTable as $key => $value) {
            $tempID = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $key);
            $servID = $tempID[0];
            $servDur = $tempID[1];
            if (strpos($tempID[0], "L")) {
                $servDur = $unlimitedId;
                $Lflag = 1;
                $servID = substr($tempID[0], 0, -1);
            }
            if (!in_array($key, $servArr)) {
                $servArr[] = $key;
                $offrDur = $value['DISCOUNT_DURATION'];
                $offrPerc = $value['DISCOUNT_PERCENT'];
                if ($offrDur > 0) {
                    if ($offrDur == 1) {
                        $output[$servID][$servDur] = " + " . $offrDur . " Month FREE";
                    } 
                    elseif ($offrDur > 1) {
                        $output[$servID][$servDur] = " + " . $offrDur . " Months FREE";
                    }
                } 
                elseif ($offrPerc > 0 || isset($discount) || isset($renewPerc)) {
                    if ($apiVersion == 3) {
                        $MonthsText = 'Months';
                    }
                    if (is_array($discount)) {
                        if ($Lflag == 1) {
                            if ($discount[$servID]["L"] > $renewPerc) {
                                $output[$servID][$servDur] = $MonthsText . " + " . $discount[$servID]["L"] . "% Discount";
                            } 
                            else {
                                $output[$servID][$servDur] = $MonthsText . " + " . $renewPerc . "% Discount";
                            }
                        } 
                        else {
                            if ($discount[$servID][$servDur] > $renewPerc) {
                                $output[$servID][$servDur] = $MonthsText . " + " . $discount[$servID][$servDur] . "% Discount";
                            } 
                            else {
                                $output[$servID][$servDur] = $MonthsText . " + " . $renewPerc . "% Discount";
                            }
                        }
                    } 
                    elseif ($renewPerc) {
                        $output[$servID][$servDur] = $MonthsText . " + " . $renewPerc . "% Discount";
                    } 
                    else {
                        $output[$servID][$servDur] = $MonthsText . " + " . $offrPerc . "% Discount";
                    }
                } 
                else {
                    $output[$servID][$servDur] = NULL;
                }
            }
            unset($Lflag);
        }
        return $output;
    }
}
