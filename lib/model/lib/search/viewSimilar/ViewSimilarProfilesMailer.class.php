<?php

/*
 * This is a library for VSP mailer
 */

class ViewSimilarProfilesMailer {
    /*
     * this function returns profiles similar to a set of viewed profiles
     * @param $logged in profileid
     * @param $array of profileids
     * @return set of profileids
     */

    public function getSimilarProfilesForMailer($loggedInProfile, $profileArray) {
        $loginProfile = LoggedInProfile::getInstance('', $loggedInProfile);
        $loginProfile->getDetail('', '', '*');

        $viewerGenderChar = $loginProfile->getGENDER();

        if ($viewerGenderChar == 'M') {
            $viewedGender = 'FEMALE';
            $viewerGender = 'MALE';
        } elseif ($viewerGenderChar == 'F') {
            $viewedGender = 'MALE';
            $viewerGender = 'FEMALE';
        }
        else{
                echo $loggedInProfile."--".$viewerGenderChar."\n";
                return array();
        }

        $viewer = $loggedInProfile;
        $viewerAge = $loginProfile->getAGE();

        $suggAlgoMinimumNoOfContactsRequired = viewSimilarConfig::$suggAlgoMinimumNoOfContactsRequired;
        //$suggAlgoMinimumNoOfContactsRequired=0; // to check directly for Normal search operation
        $suggAlgoScoreConst = viewSimilarConfig::$suggAlgoScoreConst;

        $suggAlgoNoOfResultsNoFilter = viewSimilarConfig::$suggAlgoNoOfResultsForMailer;


        //Store Object
        $similarProfileObj = new viewSimilar_CONTACTS_CACHE_LEVEL();
        $ContactsRecordsObj = new ContactsRecords();
        $ignoredProfileObj = new IgnoredProfiles();
        // contacts viewed
        $contactsViewed = $similarProfileObj->getViewedProfilesForMultipleViewed($viewedGender, $profileArray);
        if (count($contactsViewed) > 0) {
            $suggProfAlgo = 'contacts';
            $viewedContactsStr = implode(",", $contactsViewed);
            // Get Receiver
            $WhereArr = array('SENDER' => $viewer);
            $contacts1 = $ContactsRecordsObj->getResultSet("RECEIVER", $WhereArr);
            if (is_array($contacts1)) {
                foreach ($contacts1 as $values) {
                    $contactsViewer[$values['RECEIVER']] = 1;
                }
            }
            // get Sender
            $WhereArr = array('RECEIVER' => $viewer);
            $contacts2 = $ContactsRecordsObj->getResultSet("SENDER,TYPE", $WhereArr);
            if (is_array($contacts2)) {
                foreach ($contacts2 as $values) {
                    $contactsViewer[$values['SENDER']] = 1;
                }
            }
            // contacts viewed

            $vspLibObj = new ViewSimilarProfile();
            $whereParams = $vspLibObj->getWhereParamsForReverseDpp($viewerGender, $loginProfile);

            $AgeViewed = $this->AgeInterval($viewerGender, $profileArray, $viewerAge);
            $whereParams['lage'] = $AgeViewed['lAge'];
            $whereParams['hage'] = $AgeViewed['hAge'];

            $profileListObj = new IgnoredContactedProfiles();
            $ignoredContactedProfiles = $profileListObj->getProfileList($viewer);
            $resultTemp = $similarProfileObj->getSuggestedProf($viewerGender, $viewedContactsStr, $whereParams, $ignoredContactedProfiles);
            
            $suggestedProf = $resultTemp['suggestedProf'];
            $constantVal = $resultTemp['constantVal'];
            $priority = $resultTemp['priority'];
            // contacts viewed 
            foreach ($contactsViewed as $key => $val) {
                unset($intersect1);
                if (is_array($suggestedProf[$val]) && is_array($contactsViewer)) {
                    foreach ($suggestedProf[$val] as $prof)
                        $intersect1[$prof] = 1;

                    $inter = sizeof(array_intersect_key($intersect1, $contactsViewer));
                } else {
                    $inter = 0;
                }
                $scoreNum = $suggAlgoScoreConst + $inter;
                $scoreDen = sizeof($contactsViewer) + sizeof($suggestedProf[$val]) - $scoreNum + $suggAlgoScoreConst;
                $scoreDen = sqrt($scoreDen);

                if ($scoreDen != 0)
                    $scoreViewed[$val] = $scoreNum / $scoreDen;
            }
            
            //GET IGNORED LIST
            $ignoredList = $ignoredProfileObj->ifProfilesIgnored('0', $viewer, 1);
            if (is_array($suggestedProf)) {
                foreach ($suggestedProf as $key => $value) {
                    foreach ($value as $k => $v) {
                        if ($contactsViewer[$v] != 1 && $ignoredList[$v] != 1)
                            $scores[$v] = 0;
                    }
                }

                foreach ($suggestedProf as $key => $value) {
                    foreach ($value as $k => $v) {
                        if ($contactsViewer[$v] != 1 && $ignoredList[$v] != 1) {
                            $score = $constantVal[$key][$k] * $scoreViewed[$key] + 10000 / $priority[$key][$k];
                            $scores[$v] += $score;
                        }
                    }
                }

                arsort($scores);

                $i = 0;
                foreach ($scores as $s => $x) {
                    if ($i++ < $suggAlgoNoOfResultsNoFilter)
                        $finalScores[] = $s;
                    else
                        break;
                }
            }
        }
        return $finalScores;
    }

    function AgeInterval($viewerGender, $viewedProfilesArray, $viewerAge) {
        $viewedAgeArr = $this->getAgeForMultipleProfiles($viewedProfilesArray);

        $maxViewedAge = $viewedAgeArr['max'];
        $minViewedAge = $viewedAgeArr['min'];
        
        if ($viewerGender == MALE) {
            $viewerAgeMin = $viewerAge - 5;
            $Age['lAge'] = (int) min($viewerAgeMin, $minViewedAge);
            $Age['hAge'] = (int) max($maxViewedAge, $viewerAge);
        } else {
            $viewedAgeMax = $viewerAge + 5;
            $Age['lAge'] = (int) min($minViewedAge, $viewerAge);
            $Age['hAge'] = (int) max($viewedAgeMax, $maxViewedAge);
        }
        return $Age;
    }

    function getAgeForMultipleProfiles($profilesArr) {
        $ageArrObj = new JPROFILE();
        foreach($profilesArr as $key=>$val){
            if($val)
                $whereString .= ",".$val; 
        }
        
        $whereString = trim($whereString,',');
        $whereArray = array("PROFILEID" => $whereString);
        $ageArr = $ageArrObj->getArray($whereArray, '', '', "AGE");
        
        $min =$ageArr[0][AGE];$max =$ageArr[0][AGE];
        foreach ($ageArr as $key => $val) {
            $age = $val[AGE];
            if ($age > $max)
                $max = $age;
            else if ($age < $min)
                $min = $age;
        }
        $finalAgeArr['min'] = $min;
        $finalAgeArr['max'] = $max;
        
        return $finalAgeArr;
    }

    function getProfilesForAUserToPopulateTable($profileFetched) {
        $typeArray = explode(',', $profileFetched['Types']);
        $userArray = explode(',', $profileFetched['Receivers']);
        if (in_array('A', $typeArray)) {
            $i = 0;
            foreach ($typeArray as $key => $type) {
                if ($i == 5)
                    break;
                if ($type == 'A'){
                    $finalArr['profiles'][] = $userArray[$key];
                    $i++;
                }
            }
            $finalArr['type'] = 'A';
        }
        else {
            $i = 0;
            foreach ($userArray as $key => $type) {
                if ($i++ == 5)
                    break;
                $finalArr['profiles'][] = $userArray[$key];
            }
            $finalArr['type'] = 'O';
        }

        return $finalArr;
    }

}
