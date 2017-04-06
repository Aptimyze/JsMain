<?php

/*
 * This class handles internal functions called by the MembershipAPIResponseHandler.class.php
 * Operations regarding decorating/setting key/value pairs for responses is done here
 * Please note: modifying properties here will result in change across all channels
 */

class MembershipApiFunctions
{
    public function getDateDropDown($startDate, $daysNo) {
        for ($i = 0; $i <= $daysNo; $i++) {
            $dateFormat = date("d M Y", strtotime($startDate . " +$i day"));
            $formatter = strtotime($dateFormat);
            $display_date[$formatter] = $dateFormat;
        }
        return $display_date;
    }
    
    public function getOrderContent($apiObj) {
        list($orderid, $id) = explode("-", $apiObj->orderID);
        $billOrdObj = new BILLING_ORDERS();
        $memHandlerObj = new MembershipHandler();
        $orderArr = $billOrdObj->getOrderDetailsForOrderID($id, $orderid);
        unset($billOrdObj);
        $ser_name = $memHandlerObj->getServiceName($orderArr[0]["SERVICEMAIN"]);
        list($vas, $main) = $memHandlerObj->getMobileDisplayServiceArray($ser_name, $id, $orderid, $apiObj->profileid, $orderArr[0]["ENTRY_DT"], $orderArr[0]["EXPIRY_DT"]);
        
        $amount = $orderArr[0]['AMOUNT'];
        
        if (is_array($main)) {
            foreach ($main as $key => $val) {
                $main_serv_name = $val['NAME'];
                $main_serv_dur = $val['DURATION'];
                if ($val['EXTRA']) {
                    $main_serv_dur.= ' +' . $val['EXTRA'];
                }
                if ($main_serv_dur == 1) {
                    $main_serv_dur.= ' Month';
                } 
                else {
                    $main_serv_dur.= ' Months';
                }
            }
        }
        $vas_arr = array();
        if ($apiObj->device == 'desktop') {
            if (is_array($vas) && !empty($vas)) {
                foreach ($vas as $key => $val) {
                    if ($val['KEY'] == 'I') {
                        $vas_arr[$val['NAME']][] = '' . $val['DURATION'] . ' Profiles';
                    } 
                    else {
                        if ($val['DURATION'] == 1) {
                            $vas_arr[$val['NAME']][] = '' . $val['DURATION'] . ' Month';
                        } 
                        else {
                            $vas_arr[$val['NAME']][] = '' . $val['DURATION'] . ' Months';
                        }
                    }
                }
            } 
            else {
                $vas_arr = NULL;
            }
        } 
        else {
            if (is_array($vas) && !empty($vas)) {
                foreach ($vas as $key => $val) {
                    if ($val['KEY'] == 'I') {
                        $vas_arr[] = $val['NAME'] . ' - ' . $val['DURATION'] . ' Profiles';
                    } 
                    else {
                        if ($val['DURATION'] == 1) {
                            $vas_arr[] = $val['NAME'] . ' - ' . $val['DURATION'] . ' Month';
                        } 
                        else {
                            $vas_arr[] = $val['NAME'] . ' - ' . $val['DURATION'] . ' Months';
                        }
                    }
                }
            } 
            else {
                $vas_arr = NULL;
            }
        }
        
        $order_content = array(
            'amount' => " " . number_format($amount, 2, '.', ',') ,
            'membership_plan' => $main_serv_name,
            'currency' => $orderArr[0]['CURTYPE'],
            'duration' => $main_serv_dur,
            'vas_services' => $vas_arr,
            'orderid' => $orderid . '-' . $id,
            'transaction_date' => date("M d, Y", strtotime($orderArr[0]['ENTRY_DT']))
        );
        
        return $order_content;
    }
    
    public function calculateCartPrice($request, $apiObj) {
        $servObj = new billing_SERVICES();
        $memHandlerObj = new MembershipHandler();
        if (isset($apiObj->mainMembership) && !empty($apiObj->mainMembership)) {
            $allMemberships = $apiObj->mainMembership;
            $tempMem = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $apiObj->mainMembership);
            $mainMem = $tempMem[0];
            if (strpos($mainMem, "L")) {
                $mainMemDur = "L";
                $mainMem = substr($mainMem, 0, -1);
            } 
            else {
                $mainMemDur = $tempMem[1];
            }
            //error_log("ankita upgradeMem in calculateCartPrice-".$apiObj->upgradeMem);
            
            list($discountType, $discountActive, $discount_expiry, $discountPercent, $specialActive, $variable_discount_expiry, $discountSpecial, $fest, $festEndDt, $festDurBanner, $renewalPercent, $renewalActive, $expiry_date, $discPerc, $code,$upgradePercentArr,$upgardeActive) = $memHandlerObj->getUserDiscountDetailsArray($apiObj->userObj, "L",3,$apiObj,$apiObj->upgradeMem);

            if ($specialActive == 1 || $discountActive == 1 || $renewalActive == 1 || $fest == 1) {
                if ($apiObj->userObj->userType == 4 || $apiObj->userObj->userType == 6) {
                    $discPerc = $renewalPercent;
                } 
                else if ($specialActive == 1) {
                    $vdDiscArr = $memHandlerObj->getSpecialDiscountForAllDurations($apiObj->profileid);
                    $vdDisc = $vdDiscArr[$mainMem];	
                    if (in_array($mainMemDur, array_keys($vdDisc))) {
                        $discPerc = $vdDisc[$mainMemDur];
                    } 
                    else if ($fest == 1) {
                        $discPerc = $vdDisc[$mainMemDur];
                    } 
                    else {
                        $discPerc = 0;
                    }
                } 
                else if ($discountActive == 1) {
                    $discPerc = $memHandlerObj->getDiscountOffer($apiObj->mainMembership);
                } 
                else if ($fest == 1 && $mainMem != "X") {
                    $festOffrLookup = new billing_FESTIVE_OFFER_LOOKUP();
                    $perc = $festOffrLookup->getPercDiscountOnService($apiObj->mainMembership);
                    unset($festOffrLookup);
                    if ($renewalActive == 1) {
                        $discPerc = $renewalPercent;
                    } 
                    else {
                        $discPerc = $perc;
                    }
                }
                if ($fest == 1 && $mainMem == "X" && $specialActive != 1 && $renewalActive != 1) {
                    $discPerc = 0;
                }
            }
            if (isset($apiObj->vasImpression) && !empty($apiObj->vasImpression)) {
                $allMemberships.= "," . $apiObj->vasImpression;
            }
            $mems = explode(",", $allMemberships);
        } 
        else if (isset($apiObj->vasImpression) && !empty($apiObj->vasImpression)) {
            $allMemberships = $apiObj->vasImpression;
            $mems = explode(",", $allMemberships);
        }
        
        if (!empty($apiObj->backendRedirect) && $apiObj->backendRedirect == 1) {
            $profileCheckSum = $apiObj->profilechecksum;
            $profileCheckSumArray = explode("i", $profileCheckSum);
            $profileid = $profileCheckSumArray[1];
            $idCheckSum = $apiObj->reqid;
            $idCheckSumArray = explode("i", $idCheckSum);
            $idBackend = $idCheckSumArray[1];
            if (md5($idBackend) == $idCheckSumArray[0]) {
                list($allMemberships, $discountBackend, $profileid) = $memHandlerObj->handleBackendCase($idBackend, $apiObj->profileid);
            }
            $apiObj->discountBackend = $discountBackend;
            $discPerc = $discountBackend;
            $apiObj->fromBackend = 1;
            $apiObj->backendId = $idBackend;
            $apiObj->backendCheckSum = $apiObj->reqid;
            $apiObj->discSel = $apiObj->reqid;
            $apiObj->profileid = $profileCheckSumArray[1];
            $apiObj->specialActive = 1;
            if ($allMemberships) {
                $memArray = explode(",", $allMemberships);
                for ($p = 0; $p < count($memArray); $p++) {
                    if (strpos($memArray[$p], "main") !== false) {
                        $subMem = substr($memArray[$p], 4);
                        $tempMem = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $subMem);
                        $mainMem = $tempMem[0];
                        if (strpos($mainMem, "L")) {
                            $mainMemDur = "L";
                            $mainMem = substr($mainMem, 0, -1);
                        } 
                        else {
                            $mainMemDur = $tempMem[1];
                        }
                    }
                }
            }
            if ($mainMem == "E") {
                $mainMem = "ESP";
            }
            $remainingServices = array_splice($memArray, 1);
            $apiObj->mainMembership = $mainMem . $mainMemDur;
            $apiObj->vasImpression = implode(",", $remainingServices);
            foreach ($remainingServices as $key => $val) {
                if (empty($val) || $val == NULL) {
                    $emptyRemainingServicesFlag = 1;
                }
            }
            if (!$emptyRemainingServicesFlag) {
                $allMemberships = $mainMem . $mainMemDur . "," . implode(",", $remainingServices);
            } 
            else {
                $allMemberships = $mainMem . $mainMemDur;
            }
            $mems = explode(',', $allMemberships);
        }
        $apiObj->allMemberships = $allMemberships;
        if ($apiObj->currency == 'RS') {
            $price = 0;
            $totalCartPrice = 0;
            $discountCartPrice = 0;
            if (strpos($apiObj->mainMembership, 'ESP') === FALSE) {
                if (is_array($mems)) {
                	$servDetails = $servObj->fetchServiceDetailForRupeesTrxn($mems, $apiObj->device);
                    foreach ($mems as $key => $val) {
                        $price = $servDetails[$val]['PRICE'];
                        
                        if ($discountActive == 1 && $apiObj->backendRedirect != 1) {
                            if ($memHandlerObj->getDiscountOffer($apiObj->mainMembership)) {
                                $discPerc = $memHandlerObj->getDiscountOffer($val);
                            }
                        }
                        if (!empty($discPerc) && $discPerc != 0) {
                            $discountCartPrice+= round($price * ($discPerc / 100) , 2);
                        } 
                        else {
                            $discountCartPrice+= 0;
                        }
                        $totalCartPrice+= $price - round($price * ($discPerc / 100), 2);
                    }
                }
            } 
            else {
                $servDetails = $servObj->fetchServiceDetailForRupeesTrxn($apiObj->mainMembership, $apiObj->device);
                $price = $servDetails['PRICE'];
                if ($discountActive == 1 && $apiObj->backendRedirect != 1) {
                    if ($memHandlerObj->getDiscountOffer($apiObj->mainMembership)) {
                        $discPerc = $memHandlerObj->getDiscountOffer($apiObj->mainMembership);
                    }
                }
                if (!empty($discPerc) && $discPerc != 0) {
                    $discountCartPrice+= round($price * ($discPerc / 100) , 2);
                } 
                else {
                    $discountCartPrice+= 0;
                }
                $totalCartPrice+= $price - round($price * ($discPerc / 100), 2);
            }
        } 
        else {
            $price = 0;
            $totalCartPrice = 0;
            $discountCartPrice = 0;
            if (strpos($apiObj->mainMembership, 'ESP') === FALSE) {
                $servDetails = $servObj->fetchServiceDetailForDollarTrxn($mems, $apiObj->device);
                foreach ($mems as $key => $val) {
                    $price = $servDetails[$val]['PRICE'];
                    if ($discountActive == 1 && $apiObj->backendRedirect != 1) {
                        if ($memHandlerObj->getDiscountOffer($apiObj->mainMembership)) {
                            $discPerc = $memHandlerObj->getDiscountOffer($val);
                        }
                    }
                    if (!empty($discPerc) && $discPerc != 0) {
                        $discountCartPrice+= round($price * ($discPerc / 100) , 2);
                    } 
                    else {
                        $discountCartPrice+= 0;
                    }
                    $totalCartPrice+= $price - round($price * ($discPerc / 100), 2);
                }
            } 
            else {
                $servDetails = $servObj->fetchServiceDetailForDollarTrxn($apiObj->mainMembership, $apiObj->device);
                $price = $servDetails['PRICE'];
                if ($discountActive == 1 && $apiObj->backendRedirect != 1) {
                    if ($memHandlerObj->getDiscountOffer($apiObj->mainMembership)) {
                        $discPerc = $memHandlerObj->getDiscountOffer($apiObj->mainMembership);
                    }
                }
                if (!empty($discPerc) && $discPerc != 0) {
                    $discountCartPrice+= round($price * ($discPerc / 100) , 2);
                } 
                else {
                    $discountCartPrice+= 0;
                }
                $totalCartPrice+= $price - round($price * ($discPerc / 100), 2);
            }
        }

        //add additional discount for upgrade membership if applicable
        if((empty($apiObj->backendRedirect) || $apiObj->backendRedirect != 1) && $upgardeActive == '1' && count($upgradePercentArr) > 0 && $upgradePercentArr[$apiObj->mainMembership]){
            
            //$additionalUpgradeDiscount = round($totalCartPrice * ($upgradePercentArr[$apiObj->mainMembership] / 100) , 2);
            //$temp = $totalCartPrice;
            $totalCartPrice = $upgradePercentArr[$apiObj->mainMembership]["discountedUpsellMRP"];
            $discountCartPrice+= $upgradePercentArr[$mainMembership]["actualUpsellMRP"] - $upgradePercentArr[$apiObj->mainMembership]["discountedUpsellMRP"];
            /*if(is_array($apiObj->purchaseDetArr) && $apiObj->purchaseDetArr['NET_AMOUNT']){
                error_log("purchaseDetArr in calculateCartPrice-".$apiObj->purchaseDetArr['NET_AMOUNT']);
                $discountCartPrice = $discountCartPrice - $apiObj->purchaseDetArr['NET_AMOUNT'];
            }*/
        }
        
        if (!empty($apiObj->couponCode)) {
            $couponResponse = $apiObj->validateCouponResponse($apiObj->mainMembership, $apiObj->couponCode);
            if (is_array($couponResponse)) {
                $validation = $couponResponse['validationCode'];
                if (is_numeric($validation) && !empty($validation) && $validation > 0) {
                    $additionalDiscount = round($totalCartPrice * ($validation / 100) , 2);
                    $totalCartPrice-= $additionalDiscount;
                    $discountCartPrice+= $additionalDiscount;
                }
            }
        }
     
        return array(
            $totalCartPrice,
            $discountCartPrice
        );
    }
    
    public function retrieveCorrectMemID($memID, $apiObj) {
        if ($memID != "FREE") {
            $memID = @explode(",", $memID);
            $memID = $memID[0];
            $memID = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $memID);
            $memID = $memID[0];
            if (strpos($memID, "L")) {
                $memID = substr($memID, 0, -1);
            }
            if (!in_array($memID, VariableParams::$mainMembershipsArr)) {
                $memID = $apiObj->userObj->memStatus;
                $memID = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $memID);
                $memID = $memID[0];
                if (strpos($memID, "L")) {
                    $memID = substr($memID, 0, -1);
                }
                if (!in_array($memID, VariableParams::$mainMembershipsArr)) {
                    $memID = "FREE";
                }
            }
            return $memID;
        } 
        else {
            return $memID;
        }
    }
    
    public function customizeVASDataForAPI($validation = 0, $backendDiscount = 0, $apiObj) {
        $newData = array();
        $vasDesc = VariableParams::$newApiVasNamesAndDescription;
        $memHandlerObj = new MembershipHandler();
        $vdDisc = $memHandlerObj->getSpecialDiscountForAllDurations($apiObj->profileid);
        $mainDisc = $memHandlerObj->getDiscountOffer($apiObj->mainMem . $apiObj->mainMemDur);
        foreach ($apiObj->vas_data as $key => & $value) {
            $tempArr = array();
            if (!in_array($key, array_keys($vasDesc))) {
                unset($apiObj->vas_data[$key]);
                continue;
            } 
            else {
                $tempArr['vas_name'] = $vasDesc[$key]['name'];
                $tempArr['vas_description'] = $vasDesc[$key]['description'];
                $tempArr['vas_id'] = "" . $vasDesc[$key]['vas_id'];
                $tempArr['vas_key'] = $key;
                
                $lowest = 9999999;
                $standardLowest = 9999999;
                
                if ($tempArr['vas_key'] == 'I') {
                    $tempArr['selectDurationText'] = "Select Number of Profiles";
                } 
                else {
                    $tempArr['selectDurationText'] = "Select Duration";
                }
                
                foreach ($value as $kk => $vv) {
                    if ($key == "M") {
                        if (isset($apiObj->mainMemDur) && !empty($apiObj->mainMemDur)) {
                            if ($apiObj->mainMemDur == "L") {
                                if ($kk != "M12") {
                                    continue;
                                }
                            } 
                            else {
                                if ($kk != $key . $apiObj->mainMemDur) {
                                    continue;
                                }
                            }
                        } 
                        else {
                            if ($kk != "M3") {
                                continue;
                            }
                        }
                    }
                    
                    $priceArr = array();
                    $priceArr['standard_price'] = "" . round($vv['PRICE'], 2);
                    $priceArr['orig_price'] = $priceArr['standard_price'];
                    $priceArr['orig_price_formatted'] = number_format($priceArr['standard_price'], 2, '.', ',');
                    if (($apiObj->specialActive == 1 || $apiObj->discountActive == 1 || $apiObj->renewalActive == 1 || $apiObj->fest == 1) && ($backendDiscount == 0) && $apiObj->device != "iOS_app") {
                        if ($apiObj->specialActive == 1) {
                            $temp = ($vv['PRICE'] - ($vv['PRICE'] * ($vdDisc[$apiObj->mainMem][$apiObj->mainMemDur] / 100)));
                            $priceArr['price'] = "" . round($temp, 2);
                            $temp = ($priceArr['standard_price'] - $priceArr['price']);
                            $priceArr['discount_given'] = "" . round($temp, 2);
                        } 
                        else if ($apiObj->discountActive == 1) {
                            if ($mainDisc) {
                                $offerDisc = $memHandlerObj->getDiscountOffer($kk);
                                if ($offerDisc) {
                                    $temp = ($vv['PRICE'] - ($vv['PRICE'] * ($offerDisc / 100)));
                                    $priceArr['price'] = "" . round($temp, 2);
                                    $temp = ($priceArr['standard_price'] - $priceArr['price']);
                                    $priceArr['discount_given'] = "" . round($temp, 2);
                                } 
                                else {
                                    $priceArr['standard_price'] = NULL;
                                    $priceArr['price'] = $vv['PRICE'];
                                    $priceArr['discount_given'] = NULL;
                                }
                            } 
                            else {
                                $priceArr['standard_price'] = NULL;
                                $priceArr['price'] = $vv['PRICE'];
                                $priceArr['discount_given'] = NULL;
                            }
                        } 
                        else if ($apiObj->fest == 1) {
                            $festOffrLookup = new billing_FESTIVE_OFFER_LOOKUP();
                            $perc = $festOffrLookup->getPercDiscountOnService($apiObj->mainMem . $apiObj->mainMemDur);
                            unset($festOffrLookup);
                            if ($apiObj->specialActive == 1 || $apiObj->renewalActive == 1) {
                                $perc = $apiObj->discPerc;
                            }
                            if (!empty($perc) && $perc > 0) {
                                $temp = ($vv['PRICE'] - ($vv['PRICE'] * ($perc / 100)));
                                $priceArr['price'] = "" . round($temp, 2);
                                $temp = ($priceArr['standard_price'] - $priceArr['price']);
                                $priceArr['discount_given'] = "" . round($temp, 2);
                            } 
                            else {
                                $priceArr['standard_price'] = NULL;
                                $priceArr['price'] = $vv['PRICE'];
                                $priceArr['discount_given'] = NULL;
                            }
                        } 
                        else {
                            $temp = ($vv['PRICE'] - ($vv['PRICE'] * ($apiObj->discPerc / 100)));
                            $priceArr['price'] = "" . round($temp, 2);
                            $temp = ($priceArr['standard_price'] - $priceArr['price']);
                            $priceArr['discount_given'] = "" . round($temp, 2);
                        }
                    } 
                    else {
                        $priceArr['standard_price'] = NULL;
                        $priceArr['price'] = $vv['PRICE'];
                        $priceArr['discount_given'] = NULL;
                    }
                    
                    if (is_numeric($validation) && $validation > 0 && $apiObj->mainMem != "ESP") {
                        $priceArr['standard_price'] = "" . round($vv['PRICE'], 2);
                        $tempPrice = $priceArr['price'];
                        $priceArr['orig_price'] = $priceArr['price'];
                        $priceArr['price'] = $priceArr['price'] - round($tempPrice * ($validation / 100) , 2);
                        $priceArr['discount_given'] = round($tempPrice * ($validation / 100) , 2);
                    }
                    if (is_numeric($backendDiscount) && $backendDiscount > 0) {
                        $priceArr['standard_price'] = "" . round($vv['PRICE'], 2);
                        $tempPrice = $priceArr['price'];
                        $priceArr['orig_price'] = $priceArr['price'];
                        $priceArr['price'] = $priceArr['price'] - round($tempPrice * ($backendDiscount / 100) , 2);
                        $priceArr['discount_given'] = round($tempPrice * ($backendDiscount / 100) , 2);
                    }
                    if ($priceArr['price'] <= $lowest) {
                        $lowest = $priceArr['price'];
                    }
                    if ($priceArr['standard_price'] <= $standardLowest) {
                        $standardLowest = $priceArr['standard_price'];
                    }
                    
                    $priceArr['vas_price'] = number_format($priceArr['price'], 2, '.', ',');
                    $priceArr['price'] = "" . $priceArr['price'];
                    if ($priceArr['standard_price'] != NULL && ($priceArr['standard_price'] != $priceArr['price'])) {
                        $priceArr['vas_price_strike'] = number_format($priceArr['standard_price'], 2, '.', ',');
                    } 
                    else {
                        $priceArr['vas_price_strike'] = NULL;
                    }
                    unset($priceArr['standard_price']);
                    if (!empty($priceArr['discount_given']) || !is_null($priceArr['discount_given']) || $priceArr['discount_given'] != '') {
                        $priceArr['discount_given'].= "";
                    } 
                    else {
                        $priceArr['discount_given'] = NULL;
                    }
                    
                    $priceArr['duration'] = $vv['DURATION'];
                    if ($key == "I") {
                        $priceArr['text'] = 'Profiles';
                    } 
                    else {
                        $priceArr['text'] = 'Months';
                    }
                    $priceArr['id'] = $kk;
                    $tempArr['vas_options'][] = $priceArr;
                }
                $tempArr['starting_price_text'] = "From ";
                $tempArr['starting_price'] = "" . number_format($lowest, 2, '.', ',');
                if ($standardLowest > 0) {
                    $tempArr['starting_strikeout'] = "" . number_format($standardLowest, 2, '.', ',');
                } 
                else {
                    $tempArr['starting_strikeout'] = NULL;
                }
            }
            usort($tempArr['vas_options'], function ($a, $b) {
                return $a['duration'] - $b['duration'];
            });
            $newData[] = $tempArr;
        }
        $apiObj->custVAS = $newData;
    }
    
    public function removeExtraParamsFromVAS($vasArr, $apiObj) {
        if (is_array($vasArr)) {
            foreach ($vasArr as $key => $val) {
                foreach ($val['vas_options'] as $kk => $vv) {
                    unset($apiObj->custVAS[$key]['vas_options'][$kk]['price']);
                    unset($apiObj->custVAS[$key]['vas_options'][$kk]['discount_given']);
                }
            }
        }
    }
    
    public function resortVasData($vasData, $apiObj) {
        
        $order = VariableParams::$vasOrder;
        $tempArr = array();
        $tempArr2 = array();
        if (is_array($order)) {
            foreach ($order as $key => $val) {
                $tempArr[$val] = $vasData[$val];
                if (empty($tempArr[$val])) {
                    unset($tempArr[$val]);
                }
            }
        }
        
        if ($this->getCurrentlyActiveVasNames($apiObj)) $vasIds = array_keys($this->getCurrentlyActiveVasNames($apiObj));
        if (!empty($vasIds) && is_array($vasIds)) {
            foreach ($vasIds as $key => $val) {
                if (in_array(substr($val, 0, 1) , array_keys($tempArr))) {
                    $tempArr2[substr($val, 0, 1) ] = $tempArr[substr($val, 0, 1) ];
                    unset($tempArr[substr($val, 0, 1) ]);
                }
            }
            if (!empty($tempArr2)) {
                foreach ($tempArr2 as $key => $val) {
                    $tempArr[$key] = $tempArr2[$key];
                }
            }
        }
        return $tempArr;
    }
    
    public function getMembershipData($apiObj) {
        
        $memHandlerObj = new MembershipHandler();
        $allMemberships = $apiObj->curActServices;
        $serviceName = $memHandlerObj->getServiceNames($allMemberships);
        $mostPopularArr = $memHandlerObj->getMostPopular();
        
        foreach ($allMemberships as $key => $value) {
            $starting_price = number_format($apiObj->minPriceArr[$value]['OFFER_PRICE'], 2, '.', ',');
            
            if (in_array($value, array_keys($mostPopularArr))) {
                $mostPopular = $mostPopularArr[$value];
            }
            if ($apiObj->currency == "RS" && $apiObj->device != "iOS_app") {
                if ($apiObj->minPriceArr[$value]["PRICE_INR"] != $apiObj->minPriceArr[$value]["OFFER_PRICE"]) {
                    $standardJSPrice = $apiObj->minPriceArr[$value]["PRICE_INR"];
                } 
                else {
                    $standardJSPrice = NULL;
                }
            } 
            elseif ($apiObj->currency == "DOL" && $apiObj->device != "iOS_app") {
                if ($apiObj->minPriceArr[$value]["PRICE_USD"] != $apiObj->minPriceArr[$value]["OFFER_PRICE"]) {
                    $standardJSPrice = $apiObj->minPriceArr[$value]["PRICE_USD"];
                } 
                else {
                    $standardJSPrice = NULL;
                }
            }
            if ($standardJSPrice != NULL && $apiObj->device != "iOS_app") {
                $starting_strikeout = "" . number_format($standardJSPrice, 2, '.', ',');
            } 
            else {
                $starting_strikeout = NULL;
            }
            if ($value == "X") {
            	 if($apiObj->currency != "DOL"){
            	 	$requestCallback = array(
	                     'label' => 'Call us now for more details',
	                     'labelLink' => 'tel:180030106299',
	                     'linkText' => 'Request Callback',
	                     'params' => 'processCallback=1&INTERNAL=1&execCallbackType=JS_EXC&tabVal=1&profileid=' . $apiObj->profileid . "&device=" . $apiObj->device . "&channel=" . $apiObj->channel . "&callbackSource=" . $apiObj->callbackSource
	                 );
            	 } else {
            		$requestCallback = array(
	                    'label' => 'Need more details?',
	                    'linkText' => 'Request Callback',
	                    'params' => 'processCallback=1&INTERNAL=1&execCallbackType=JS_EXC&tabVal=1&profileid=' . $apiObj->profileid . "&device=" . $apiObj->device . "&channel=" . $apiObj->channel . "&callbackSource=" . $apiObj->callbackSource
	                );
            	}
            } 
            else {
                $requestCallback = NULL;
            }
            list($benefits, $servMessage, $benefitsExcluded) = $this->getServiceWiseBenefits($value, 1, $apiObj);
            
            if ($apiObj->device == "iOS_app") {
                $starting_strikeout = NULL;
            }
            
            $service_data[] = array(
                'subscription_id' => $value,
                'subscription_name' => $serviceName[$key],
                'starting_price' => 'From ',
                'starting_price_string' => "" . $starting_price,
                'starting_strikeout' => $starting_strikeout,
                'benefits' => $benefits,
                'servMessage' => $servMessage,
                'benefitsExcluded' => $benefitsExcluded,
                'selectDurationText' => "Select Duration",
                'durations' => $this->getDurationAndPrices($value, $mostPopular, $apiObj) ,
                'request_callback' => $requestCallback,
                'viewDurationText' => "View Duration"
            );
            unset($mostPopular);
        }
        return $service_data;
    }
    
    public function setDiscountDetails($apiObj,$fromApi=false) {
        if($fromApi == true && $apiObj->memHandlerObj){
            $memHandlerObj = $apiObj->memHandlerObj;
        }
        else{
            $memHandlerObj = new MembershipHandler();
        }
        list($discountType, $discountActive, $discount_expiry, $discountPercent, $specialActive, $variable_discount_expiry, $discountSpecial, $fest, $festEndDt, $festDurBanner, $renewalPercent, $renewalActive, $expiry_date, $discPerc, $code,$upgradePercentArr,$upgradeActive) = $memHandlerObj->getUserDiscountDetailsArray($apiObj->userObj, "L",3,$apiObj,$apiObj->upgradeMem);
    
        $apiObj->discountType = $discountType;
        $apiObj->discountActive = $discountActive;
        $apiObj->discount_expiry = $discount_expiry;
        $apiObj->discountPercent = $discountPercent;
        $apiObj->specialActive = $specialActive;
        $apiObj->variable_discount_expiry = $variable_discount_expiry;
        $apiObj->discountSpecial = $discountSpecial;
        $apiObj->fest = $fest;
        $apiObj->festEndDt = $festEndDt;
        $apiObj->festDurBanner = $festDurBanner;
        $apiObj->renewalPercent = $renewalPercent;
        $apiObj->renewalActive = $renewalActive;
        $apiObj->expiry_date = $expiry_date;
        $apiObj->discPerc = $discPerc;
        $apiObj->code = $code;
        $apiObj->upgradePercentArr = $upgradePercentArr;
        $apiObj->upgradeActive = $upgradeActive;
    }
    
    public function getDurationAndPrices($mainMem, $mostPopular, $apiObj) {
        foreach ($apiObj->allMainMem[$mainMem] as $key => $value) {
            
            if ($key == $mostPopular) {
                $service['mostPopular'] = 'Y';
            } 
            else {
                $service['mostPopular'] = 'N';
            }
            
            if ($value['DURATION'] == 1188 || $key == 'ESPL') {
                $service['duration'] = 'Unlimited';
                $service['duration_id'] = 'L';
            } 
            else {
                $service['duration'] = $value['DURATION'];
                $service['duration_id'] = $value['DURATION'];
            }
            if ($apiObj->fest == 1 && $mainMem != "X") {
                $service['duration_text'] = $apiObj->festDurBanner[$mainMem][$service['duration_id']];
                if (empty($service['duration_text'])) {
                    $service['duration_text'] = 'Months';
                }
            } 
            else {
                $service['duration_text'] = 'Months';
            }
            
            if ($value['PRICE'] != $value['OFFER_PRICE'] && $apiObj->device != "iOS_app") {
                $service['price'] = "" . number_format($value['OFFER_PRICE'], 2, '.', ',');
                $service['price_strike'] = number_format($value['PRICE'], 2, '.', ',');
            } 
            else {
                $service['price'] = "" . number_format($value['PRICE'], 2, '.', ',');
                $service['price_strike'] = NULL;
            }
            
            if ($service['duration'] != 'L') {
                $service['price_per_month'] = round(str_replace(",", '', $service['price']) / $service['duration'], 2);
            }
            
            $service['contacts'] = $value['CALL'] . " Contacts To View";
            $tempArr[] = $service;
        }
        
        return $tempArr;
    }
    
    public function getServiceWiseBenefits($memID, $getSupport = 0, $apiObj) {
        $benefitMsg = VariableParams::$newApiPageOneBenefits;
        $benefitArr = VariableParams::$newApiPageOneBenefitsVisibility;
        if ($memID == "X") {
            $benefits = VariableParams::$newApiPageOneBenefitsJSX;
        } 
        else {
            foreach ($benefitArr as $key => $value) {
                if ($key == $memID) {
                    foreach ($value as $kk => $vv) {
                        if ($vv == 1) {
                            $benefits[$kk] = $benefitMsg[$kk];
                        } 
                        else if ($apiObj->device == 'desktop' && $vv == 0) {
                            $benefitsExcluded[] = $benefitMsg[$kk];
                        }
                        if ($getSupport) {
                            foreach (VariableParams::$newApiVasNamesAndDescription as $id => $desc) {
                                if ($benefits[$kk] == $desc['name'] || in_array($desc['name'], $benefitsExcluded)) {
                                    if ($apiObj->device == "iOS_app") {
                                        unset($benefits[$kk]);
                                        $supportingText[] = array(
                                            'name' => $desc['name'],
                                            'desc' => $desc['description']
                                        );
                                    } 
                                    else {
                                        $supportingText[$desc['name']] = $desc['description'];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($getSupport) {
            return array(
                $benefits,
                $supportingText,
                $benefitsExcluded
            );
        } 
        else {
            return array(
                $benefits
            );
        }
    }
    
    /*getAdditionalUpgradeBenefits
    * get additional upgrade benefits list
    * @inputs:$currentMem,$upgradeMem
    * @return : $additionalBenefits
    */
    public function getAdditionalUpgradeBenefits($currentMem,$upgradeMem) {
        if ($upgradeMem == "X") {
            $additionalBenefits = VariableParams::$newApiPageOneBenefitsJSX;
            $lastIndex = count($additionalBenefits)-1;
            if($additionalBenefits[$lastIndex] == "Priority Customer service"){
                unset($additionalBenefits[$lastIndex]);
            }
        } else {
            $benefitMsg = VariableParams::$newApiPageOneBenefits;
            $upgradebenefitsArr = VariableParams::$newApiPageOneBenefitsVisibility[$upgradeMem];
            $currentbenefitsArr = VariableParams::$newApiPageOneBenefitsVisibility[$currentMem];
            $counter = 0;
            foreach ($upgradebenefitsArr as $key => $value) {
                if ($value == 1 && $currentbenefitsArr[$key] != 1) {
                    $additionalBenefits[$counter++] = $benefitMsg[$key];
                }
            }
            if($upgradeMem == "NCP"){
                $defaultVas = VariableParams::$mainMemBasedVasFiltering;
                if($defaultVas[$upgradeMem] && in_array("J", $defaultVas[$upgradeMem])){
                    $boostBenefits = VariableParams::$newApiPageOneBenefitsBoost;
                    if(is_array($boostBenefits)){
                        if(is_array($additionalBenefits)){
                            $additionalBenefits = array_merge($additionalBenefits,$boostBenefits);
                        }
                        else{
                            $additionalBenefits = $boostBenefits;
                        }
                    }
                }
            }
        }
        return $additionalBenefits;
    }
    public function getCurrentlyActiveVasNames($apiObj) {
        $memHandlerObj = new MembershipHandler();
        $serviceArr = $apiObj->rawSubStatusArray;
        if (is_array($serviceArr)) {
            foreach ($serviceArr as $key => $val) {
                $servStart = substr($val['SERVICEID'], 0, 1);
                if (!in_array($servStart, VariableParams::$mainMembershipsArr)) {
                    $names[$val['SERVICEID']] = substr($val['SERVICE'], 0, strpos($val['SERVICE'], "-", 0));
                    if ($names[$val['SERVICEID']] == 'Matri') {
                        $names[$val['SERVICEID']] = 'Matri Profile';
                    }
                }
            }
        }
        return $names;
    }
    
    public function getTopBlockContent($apiObj) {
        
        $topBlockMessage = array();
        if ($apiObj->subStatus[0]['EXPIRY_DT'] && $apiObj->memID != "ESJA") {
            $topBlockMessage["titleMessage"] = "Your {$apiObj->activeServiceName} plan expires in";
            $subscriptionExp = date("Y-m-d", strtotime($apiObj->subStatus[0]['EXPIRY_DT']));
            $datetime1 = new DateTime($subscriptionExp);
            $datetime2 = new DateTime(date("Y-m-d"));
            $difference = $datetime1->diff($datetime2);
            if($apiObj->userObj->userType == memUserType::UPGRADE_ELIGIBLE && in_array($apiObj->device, VariableParams::$memUpgradeConfig["channelsAllowed"]) && $apiObj->displayPage == '1'){
                $upgradeMemCase = true;
            }
            else{
                $upgradeMemCase = false;
            }
            if ($difference->y < 1) {
                /*if($upgradeMemCase == true){
                    $topBlockMessage["uMonthsText"] = "Month";
                    $topBlockMessage["uMonthsValue"] = "".$difference->m;
                    $topBlockMessage["uDaysText"] = "Days";
                    $topBlockMessage["uDaysValue"] = "".$difference->d;
                }*/
                $topBlockMessage["monthsText"] = "MONTHS";
                $topBlockMessage["monthsValue"] = "" . str_pad($difference->m, 2, 0, STR_PAD_LEFT);
                $topBlockMessage["daysText"] = "DAYS";
                $topBlockMessage["daysValue"] = "" . str_pad($difference->d, 2, 0, STR_PAD_LEFT);
                
                if($difference->y == 0 && $difference->m == 0 && $difference->d <=30) {
                    //$topBlockMessage["JSPCHeaderRenewMessage"] = "Your {$apiObj->activeServiceName} plan is due for renewal by {$datetime1->format('d M Y')}";
                	$topBlockMessage["JSPCHeaderRenewMessage"] = "Benefits of your membership";
                }
            } 
            else {
                /*if($upgradeMemCase == true){
                    $topBlockMessage["uMonthsText"] = "Month";
                }*/
                $topBlockMessage["monthsText"] = "MONTHS";
                $topBlockMessage["monthsValue"] = "Unlimited";
                $topBlockMessage["daysText"] = NULL;
                $topBlockMessage["daysValue"] = NULL;
                $topBlockMessage["JSPCHeaderRenewMessage"] = NULL;
            }
            $topBlockMessage["contactsLeftText"] = "Contacts Left To View";
            $topBlockMessage["contactsLeftNumber"] = $apiObj->contactsRemaining;
            if ($apiObj->userObj->userType == 5 || $apiObj->userObj->userType == 6 || $apiObj->userObj->userType == memUserType::UPGRADE_ELIGIBLE) {
                $benefits = $this->getServiceWiseBenefits($apiObj->memID,0,$apiObj);
                $benefits = $benefits[0];
            }
            $topBlockMessage["currentBenefitsTitle"] = "Your Current Benefits";
            
            $vasNames = $this->getCurrentlyActiveVasNames($apiObj);
            //print_r($benefits);
            //print_r($vasNames);die;
            if ($apiObj->memID == "ESP" || $apiObj->memID == "NCP") {
               if ($vasNames != NULL) 
                {
                    $topBlockMessage["currentBenefitsMessages"] = array_values(array_merge($benefits , $vasNames));
                }
                else 
                {
                    $topBlockMessage["currentBenefitsMessages"] = array_values($benefits);
                }
            } 
            else {
                if ($vasNames != NULL) {
                    $topBlockMessage["currentBenefitsMessages"] = array_values(array_merge($benefits, $vasNames));
                } 
                else {
                    $topBlockMessage["currentBenefitsMessages"] = array_values($benefits);
                }
            }
            if (!empty($apiObj->subStatus[1]) && isset($apiObj->subStatus[1])) {
                $nextMembershipService = str_replace(array(" - "," -"),array(" "," "), $apiObj->subStatus[1]['SERVICE']);
                $topBlockMessage["nextMembershipMessage"] = "Your next {$apiObj->subStatus[1]['SERVICE_NAME']} membership will start in";
                $topBlockMessage["JSPCnextMembershipMessage"] = "Benefits of your membership; your next '{$nextMembershipService}' membership starts from {$datetime1->format('d M Y')}";
                if ($difference->y > 0) {
                    $topBlockMessage["nextMembershipMessage"].= " {$difference->y} years ";
                }
                if ($difference->m > 0) {
                    $topBlockMessage["nextMembershipMessage"].= " {$difference->m} months ";
                }
                if ($difference->d > 0) {
                    $topBlockMessage["nextMembershipMessage"].= " {$difference->d} days ";
                }
            } 
            else {
                $topBlockMessage["nextMembershipMessage"] = NULL;
                $topBlockMessage["JSPCnextMembershipMessage"] = NULL;
            }
            //set additional required keys in api response for mem upgrade
            if($upgradeMemCase == true){
                $topBlockMessage["currentMemName"] = $apiObj->activeServiceName;
                $topBlockMessage["currentActualDuration"] = $apiObj->subStatus[0]['SERVICE_DURATION'];
                
                $topBlockMessage["totalContactsAllotted"] = $apiObj->allMainMem[$apiObj->subStatus[0]['SERVICEID_WITHOUT_DURATION']][$apiObj->subStatus[0]['ORIG_SERVICEID']]['CALL'];
            }
        } 
        elseif ($apiObj->userObj->userType == 4 && $apiObj->memID != "ESJA") {
            $serviceStatusObj = new BILLING_SERVICE_STATUS();
            $memHandlerObj = new MembershipHandler();
            $lastActiveDetails = $serviceStatusObj->getLastActiveServiceDetails($apiObj->profileid);
            if(strpos($lastActiveDetails['SERVEFOR'], 'N') !== false)
                $membershipName = $memHandlerObj->getUserServiceName("NCP");
            else
                $membershipName = $memHandlerObj->getUserServiceName($lastActiveDetails['SERVICEID']);
            $topBlockMessage["titleMessage"] = "Your {$membershipName} membership has expired";
            $topBlockMessage["JSPCHeaderRenewMessage"] = $topBlockMessage["titleMessage"].", Renew your membership to see contact details of other members";
            if ($apiObj->userObj->userType == 4) {
                $topBlockMessage["monthsText"] = "MONTHS";
                $topBlockMessage["monthsValue"] = "00";
                $topBlockMessage["daysText"] = "DAYS";
                $topBlockMessage["daysValue"] = "00";
            } 
            else {
                $topBlockMessage["monthsText"] = NULL;
                $topBlockMessage["monthsValue"] = NULL;
                $topBlockMessage["daysText"] = NULL;
                $topBlockMessage["daysValue"] = NULL;
            }
            $topBlockMessage["contactsLeftText"] = NULL;
            $topBlockMessage["contactsLeftNumber"] = NULL;
            $topBlockMessage["currentBenefitsTitle"] = NULL;
            $topBlockMessage["currentBenefitsMessages"] = NULL;
            $topBlockMessage["nextMembershipMessage"] = NULL;
            $topBlockMessage["JSPCnextMembershipMessage"] = NULL;
        } 
        else {
            $topBlockMessage = NULL;
        }
        $apiObj->topBlockMessage = $topBlockMessage;
    }
    
    public function getMainMembershipDetails($apiObj, $id){
        $apiObj->mainServices['sideTitle'] = "Main Membership";
        $apiObj->mainServices['service_name'] = $apiObj->memHandlerObj->getUserServiceName($id);
        if ($apiObj->mainMemDur == "L") {
            $subId = $apiObj->mainMem . 'L';
            $apiObj->mainServices['service_duration'] = 'Unlimited Months';
        }
        if ($apiObj->fest == 1 && $apiObj->mainMem != "X" && $apiObj->device != "iOS_app") {
            $festOffrLookup = new billing_FESTIVE_OFFER_LOOKUP();
            $addedDur = $festOffrLookup->getDurationDiscountOnService($apiObj->mainMem . $apiObj->mainMemDur);
            $addedPerc = $festOffrLookup->getPercDiscountOnService($apiObj->mainMem . $apiObj->mainMemDur);
            unset($festOffrLookup);
            if ((!empty($addedDur) && $addedDur > 0) || (!empty($addedPerc) && $addedPerc > 0)) {
                $addonMonths = $apiObj->festDurBanner[$apiObj->mainMem][$apiObj->mainMemDur];
            }
        }
        
        if ($apiObj->mainMemDur == "L") {
            $monthsPrependVal = "Unlimited";
        } 
        else {
            $monthsPrependVal = $apiObj->mainMemDur;
        }
        if (!isset($subId)) {
            $subId = $apiObj->mainMem . $apiObj->mainMemDur;
        }
        if ($addonMonths || $addedPerc) {
            $apiObj->mainServices['service_duration'] = $monthsPrependVal . " " . $addonMonths;
        } 
        else {
            if (!isset($apiObj->mainServices['service_duration'])) {
                $apiObj->mainServices['service_duration'] = $monthsPrependVal . ' Months';
            }
        }
       
        if($apiObj->upgradeMem && $apiObj->upgradeMem == 'MAIN'){
            $apiObj->mainServices['actual_upgrade_price'] = $apiObj->allMainMem[$id][$subId]['OFFER_PRICE']; 
        }
        $apiObj->mainServices['service_contacts'] = $apiObj->allMainMem[$id][$subId]['CALL'] . ' Contacts To View';
        $apiObj->mainServices['standard_price'] = $apiObj->allMainMem[$id][$subId]['PRICE'];
        $apiObj->mainServices['orig_price_formatted'] = number_format($apiObj->mainServices['standard_price'], 2, '.', ',');
        
        if (!empty($apiObj->couponCode) && $apiObj->device != "iOS_app") {
            $apiObj->couponResponse = $apiObj->validateCouponResponse($apiObj->mainMem . $apiObj->mainMemDur, $apiObj->couponCode);
            if (is_array($apiObj->couponResponse)) {
                $apiObj->validation = $apiObj->couponResponse['validationCode'];
            }
        }
        if ($apiObj->allMainMem[$id][$subId]['OFFER_PRICE'] != $apiObj->allMainMem[$id][$subId]['PRICE'] && $apiObj->device != "iOS_app") {
            $apiObj->mainServices['price'] = "" . $apiObj->allMainMem[$id][$subId]['OFFER_PRICE'];
            $apiObj->mainServices['orig_price'] = $apiObj->mainServices['standard_price'];
            $apiObj->mainServices['discount_given'] = $apiObj->mainServices['standard_price'] - $apiObj->mainServices['price'];

            if (is_numeric($apiObj->validation) && $apiObj->validation > 0) {
                $tempPrice = $apiObj->mainServices['price'];
                $apiObj->mainServices['price'] = "" . ($apiObj->mainServices['price'] - round($tempPrice * ($apiObj->validation / 100) , 2));
                $apiObj->mainServices['discount_given']+= round($tempPrice * ($apiObj->validation / 100) , 2);
            }
        } 
        elseif (is_numeric($apiObj->validation) && $apiObj->validation > 0) {
            $tempPrice = $apiObj->mainServices['standard_price'];
            $apiObj->mainServices['price'] = "" . ($tempPrice - round($tempPrice * ($apiObj->validation / 100) , 2));
            $apiObj->mainServices['discount_given']+= round($tempPrice * ($apiObj->validation / 100) , 2);
        } 
        else {
            $apiObj->mainServices['standard_price'] = NULL;
            $apiObj->mainServices['price'] = "" . $apiObj->allMainMem[$id][$subId]['PRICE'];
            $apiObj->mainServices['discount_given'] = NULL;
        }

        if ($apiObj->mainServices['standard_price'] != NULL) {
            $apiObj->mainServices['price_strike'] = number_format($apiObj->mainServices['standard_price'], 2, '.', ',');
        } 
        else {
            $apiObj->mainServices['price_strike'] = NULL;
        }
        $apiObj->mainServices['display_price'] = number_format($apiObj->mainServices['price'], 2, '.', ',');
        if (!isset($apiObj->selectedVas) || empty($apiObj->selectedVas)) {
            $apiObj->mainServices['remove_text'] = NULL;
            $apiObj->mainServices['change_text'] = "Change Plan";
        } 
        else {
            $apiObj->mainServices['remove_text'] = NULL;
            $apiObj->mainServices['change_text'] = "Change Plan";
        }

        if (($apiObj->mainMem == 'ESP' || $apiObj->mainMem == 'NCP') && !empty($apiObj->mainMemDur)) 
        {
            if ($apiObj->mainMem == 'ESP') {
                $arr = VariableParams::$eSathiAddOns;
            }
            elseif($apiObj->mainMem == 'NCP'){
                $arr = VariableParams::$jsExclusiveComboAddon;
            }
            else {
                $arr = VariableParams::$eValuePlusAddOns;
            }
            foreach ($arr as $key => $val) {
                if ($apiObj->mainMemDur == '1188' || $apiObj->mainMemDur == 'L') {
                    $dur = '12';
                } 
                else {
                    $dur = $apiObj->mainMemDur;
                }
                if ($val == "I") {
                    $dur.= "0";
                }
                $arr[$key] = $val . $dur;
            }
            $preSelectedVas = implode(",", $arr);
            if ($apiObj->mainMem == 'ESP') {
                $apiObj->preSelectedESathiVas = $preSelectedVas;
            } 
            else {
                $apiObj->preSelectedEValuePlusVas = $preSelectedVas;
            }
            if($apiObj->mainMem=="NCP" && $preSelectedVas)
            {
                if($apiObj->selectedVas)
                    $apiObj->selectedVas = $apiObj->selectedVas.",".$preSelectedVas;
                else
                    $apiObj->selectedVas = $preSelectedVas;
            }
            else
                $apiObj->selectedVas = $preSelectedVas;
        }
    }
    
    
    public function getVASDetails($apiObj){
        $apiObj->totalVASPrice = 0;
        $apiObj->totalVASOrigPrice = 0;
        $apiObj->totalVASCount = 0;
        $vasArr = explode(",", $apiObj->selectedVas);
        foreach ($vasArr as $key => $val){
            $vasID = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $val);
            $v['service_name'] = VariableParams::$newApiVasNamesAndDescription[$vasID[0]]['name'];
            if ($vasID[0] == "I") {
                $v['service_duration'] = $vasID[1] . ' Profiles';
            } 
            else {
                $v['service_duration'] = $vasID[1] . ' Months';
            }
            if (is_array($apiObj->custVAS)) {
                foreach ($apiObj->custVAS as $kk => $vv) {
                    if ($vv['vas_key'] == $vasID[0]) {
                        foreach ($vv['vas_options'] as $x => $z) {
                            if ($z['id'] == $val) {
                                $v['vas_id'] = $z['id'];
                                $v['orig_price'] = $z['orig_price'];
                                $v['orig_price_formatted'] = $z['orig_price_formatted'];
                                if (in_array($apiObj->mainMem, VariableParams::$skipVasPageMembershipBased) || ($apiObj->mainMem=='NCP' && in_array($vv['vas_key'], VariableParams::$mainMemBasedVasFiltering[$apiObj->mainMem]))) {
                                    $v['discount_given'] = NULL;
                                    $price = "0";
                                    $v['vas_price'] = "0";
                                    $v['orig_price'] = "0";
                                    $v['orig_price_formatted'] = "0.00";
                                    $v['vas_price_strike'] = "0";
                                } 
                                else {
                                    $v['price'] = $z['price'];
                                    $v['orig_price'] = $v['orig_price'];
                                    $v['orig_price_formatted'] = number_format($z['orig_price'], 2, '.', ',');
                                    $v['discount_given'] = number_format($z['discount_given'], 2, '.', ',');
                                    $v['vas_price'] = "" . number_format($v['price'], 2, '.', ',');
                                    $v['vas_price_strike'] = $z['vas_price_strike'];
                                }
                                if (in_array($apiObj->mainMem, VariableParams::$skipVasPageMembershipBased) || ($apiObj->mainMem=='NCP' && in_array($vv['vas_key'], VariableParams::$mainMemBasedVasFiltering[$apiObj->mainMem]))) {
                                    $v['remove_text'] = NULL;
                                    $v['change_text'] = NULL;
                                } 
                                else {
                                    $v['remove_text'] = "Remove";
                                    $v['change_text'] = "Change Duration";
                                }
                            }
                            else if($apiObj->mainMem=="NCP" && in_array($vv['vas_key'], VariableParams::$mainMemBasedVasFiltering[$apiObj->mainMem]))
                            {
                                $v['discount_given'] = NULL;
                                $price = "0";
                                $v['vas_price'] = "0";
                                $v['orig_price'] = "0";
                                $v['orig_price_formatted'] = "0.00";
                                $v['vas_price_strike'] = "0";
                                $v['remove_text'] = NULL;
                                $v['change_text'] = NULL;
                            }
                        }
                    }
                }
            }
            if($apiObj->mainMem=="NCP" && in_array($vasID[0], VariableParams::$mainMemBasedVasFiltering[$apiObj->mainMem]))
            {
                $v['discount_given'] = NULL;
                $price = "0";
                $v['vas_price'] = "0";
                $v['orig_price'] = "0";
                $v['orig_price_formatted'] = "0.00";
                $v['vas_price_strike'] = "0";
                $v['remove_text'] = NULL;
                $v['change_text'] = NULL;
            }
            if(empty($v['vas_price_strike'])){
            	$v['vas_price_strike'] = 0;

            }
            $apiObj->totalVASOrigPrice+= $v['orig_price'];
            $apiObj->totalVASPrice+= $v['price'];
            $apiObj->totalVASCount+= 1;
            $apiObj->totalVASDiscount+= $v['discount_given'];
            unset($v['discount_given']);
            unset($v['price']);
            $apiObj->vasServices[] = $v;
        }
    }
    
    public function getCouponCodeDetails($apiObj){
        if (isset($apiObj->mainMem) && !empty($apiObj->mainMem))
            $apiObj->apply_coupon_text = "Have coupon code? Apply here";
        else
            $apiObj->apply_coupon_text = NULL;
        if (empty($apiObj->couponCode)) {
            $apiObj->coupon_message = null;
            $apiObj->coupon_success = 0;
        } 
        else {
            if (is_array($apiObj->couponResponse)) {
                $apiObj->coupon_message = $apiObj->couponResponse['message']['message'];
                if ($apiObj->validation == 0 || $apiObj->validation == "INVDUR" || $apiObj->validation == "LIMEXP")
                    $apiObj->coupon_success = 0;
                else
                    $apiObj->coupon_success = $apiObj->couponResponse['message']['success_code'];
            }
        }
        if (is_numeric($apiObj->validation) && $apiObj->validation > 0) {
            $apiObj->discount_text = "Coupon Code Discount ";
            $apiObj->apply_coupon_text = NULL;
        } 
        else {
            $apiObj->discount_text = NULL;
        }
        return $apiObj;
    }
    
    /*filter out vas services based on main membership
    * @inputs: $vasData,$apiObj,$mainMem
    * @output: none
    */
    public function filterMainMemBasedVASData($vasData,$apiObj,$mainMem)
    {  
        if(is_array($vasData) && $vasData && VariableParams::$mainMemBasedVasFiltering[$mainMem])
        {
            foreach ($vasData as $key => $details)
            {
                //remove vas service if present in filter list of mainMemID
                if(in_array($details["vas_key"], VariableParams::$mainMemBasedVasFiltering[$mainMem]))
                    unset($vasData[$key]);
            } 
            $apiObj->custVAS = array_values($vasData);
        }
        else
        {
            $apiObj->custVAS = $vasData;
        }
    }
    
}
