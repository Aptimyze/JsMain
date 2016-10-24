<?php

/**
 * CLASS VerificationSeal
 * This class is responsible to handle Verification data storing and retrieving operation.
 * @author Akash Kumar
 */
class VerificationSealLib {

        private $pID;
        private $timeToStoreSealForProfilePage="7200";

        public function __construct($pObjOrID='',$noCache='') {
                if (is_object($pObjOrID))
                        $this->pID = $pObjOrID->getPROFILEID();
                else
                        $this->pID = $pObjOrID;
                $this->noCache=$noCache;
                
        }

        /**
         * This function returns array of seal.
         * @return Array of seal details 
         */
        public function getVerificationSeal() {
                $memcacheObj = JsMemcache::getInstance();
                $key = "VerificationSeal_" . $this->pID;
                $memcacheSeal = $memcacheObj->get($key);
                if ($memcacheSeal)
                        return $memcacheSeal;

                if ($this->getFsoStatus() == 0) {
                        $memcacheObj->set($key, "0", $timeToStoreSealForProfilePage);
                        return 0;
                }

                $sealArr = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$ATTRIBUTES;
                $docArr = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$DOCUMENTS;
                $profieIdArr = array($this->pID);
                $sealObj = new PROFILE_VERIFICATION_DOCUMENTS();
                $seal = $sealObj->sealDetails($profieIdArr);
                if ($seal != 0)
                {
                    foreach ($seal as $attribute => $doc) {
                        if($sealArr[$attribute]=="Highest Qualification")
                                $sealArr[$attribute]="Qualification";
                        if($sealArr[$attribute]=="Self Address")
                                $sealArr[$attribute]="Self_Address";
                        if($sealArr[$attribute]=="Parent's Address")
                                $sealArr[$attribute]="Parents_Address";
                        if($sealArr[$attribute]=="Date of Birth")
                                $sealArr[$attribute]="Date_of_Birth";
                        $finalSeal['VERIFICATION_SEAL'][$sealArr[$attribute]] = $docArr[$doc];
                        }
                }
                else
                    $finalSeal = 1;
                $memcacheObj->set($key, $finalSeal, $timeToStoreSealForProfilePage);
                return $finalSeal;
        }
        /**
         * This function calculate and GENERATED coded verification seal.
         */
        public function codeVerificationSeal() { 
                $sealArr = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$VERIFICATION_SEAL_ARRAY;
                $docArr = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$ATTRIBUTE_DOCUMENT;

                $sealInitiate = array_fill(0, (ceil(count($sealArr) / 2)), "N");
                if ($this->getFsoStatus() == 0) {
                        $makeSeal[0] = "0";
                } else {
                        $makeSeal[0] = "F";
                        $sealObj = new PROFILE_VERIFICATION_DOCUMENTS();
                        $seal = $sealObj->sealDetails($this->pID);
                        if($seal != 0){
                          foreach ($seal as $sealKey => $sealValue) {
                                  $makeSeal[$sealArr[$sealKey]] = array_flip($docArr[$sealKey])[$sealValue];
                          }
                        }
                }
                $sealFinalArr = array_replace($sealInitiate, $makeSeal);
                $finalVerificationSeal = implode(",", $sealFinalArr);
                $sealUpdateObj = new newjs_SWAP();
		if(is_array($this->pID) && count($this->pID)==1)
                {
                        foreach($this->pID as $k=>$v)
                                $sealUpdateObj->sealUpdate($v, $finalVerificationSeal);
                }
                else
                        $sealUpdateObj->sealUpdate($this->pID, $finalVerificationSeal);
        }

        /**
         * This function calculate and resets verification seal.
         * @param $attributeArrOrSingle - Array of attributes or single attribute for which verification seal to be reset
         */
        public function resetVerificationSeal($attributeArrOrSingle) {
                if (!is_array($attributeArrOrSingle))
                        $attributeArr["0"] = $attributeArrOrSingle;
                $sealArr = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$ATTRIBUTE_FIELD_ENUM;
                foreach ($attributeArrOrSingle as $key => $field) {
                        foreach ($sealArr as $sealArrkey => $sealArrfield){
                                if (in_array($field, $sealArrfield))
                                        $attributeFinalArr[] = $sealArrkey;
                        }
                }
                if(is_array($attributeFinalArr)){
                        $attributeFinalArr=array_unique($attributeFinalArr);
                $this->unsetVerificationDoc($attributeFinalArr);
                $this->setForSolrSearch();
                return 1;
                }
                return 0;
               
        }

        /**
         * This function unset Verification Document verified flag.
         * @return Array of seal details 
         */
        public function unsetVerificationDoc($attributeArr) {
                $sealObj = new PROFILE_VERIFICATION_DOCUMENTS();
                $seal = $sealObj->unsetVerificationDoc($this->pID, $attributeArr);
        }

        /**
         * This function unset Verification Document verified flag.
         * @return Array of seal details 
         */
         public function getFsoStatus() {
                if(!$this->noCache){
                    $viewProfileOptimization = viewProfileOptimization::getInstance('',$this->pID);
                    $fsoStatus = $viewProfileOptimization->getFsoStatus();
                }
                if(isset($fsoStatus))
                    return $fsoStatus;
                else{
                    $fsoObj = ProfileFSO::getInstance();
                    if(is_array($this->pID))
                    {
                        foreach($this->pID as $k=>$v)
                        {
                                return $fsoObj->check($v);
                        }
                    }
                    else
                        return $fsoObj->check($this->pID);
                }
        }

        /**
         * This function sets profileID in swap_jprofile to be used for searching.
         */
        public function setForSolrSearch() {

                $solrObj = new newjs_SWAP_JPROFILE();
                $inserted = $solrObj->insert($this->pID);
                
                $memcacheObj = JsMemcache::getInstance();
                $key = "VerificationSeal_" . $this->pID;
                $memcacheObj->set($key, $finalSeal, 0);
               
        }

        /**
         * This function CHECKS FSO Visit Status.
         * @return $return - YES or NO and in case of NO it first index carries deletion log array
         */
        public function checkFsoVisitSeal() {

                $fsoObj = ProfileFSO::getInstance();
                $visit = $fsoObj->check($this->pID);
                if ($visit > 0)
                        $check[0] = "YES";
                else {
                        $check[0] = "NO";
                        $fsoDeleteObj = new PROFILE_VERIFICATION_FSO_DELETION();
                        $check[1] = $fsoDeleteObj->check($this->pID);
                        if (is_array($check[1]) && $check[1]['DELETE_REASON']!="0")
                                $check[1]['DELETE_REASON'] = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$FSO_REMOVAL_REASON[$check[1]['DELETE_REASON']];
                        elseif($check[1]['DELETE_REASON']=="0")
                                $check[1]['DELETE_REASON'] =$check[1]['DELETE_REASON_DETAIL'];  
                        elseif (!isset($check[1]['DELETE_REASON']))
                                $check[1] = 0;
                }

                return $check;
        }

        /**
         * This function sets profileID in FSO Visit table.
         */
        public function setFsoVisitSeal() {

                $fsoObj = ProfileFSO::getInstance();
                $fsoObj->insert($this->pID);
                $this->setForSolrSearch();
        }

        /**
         * This function deletes and log profileID in fso detetion table.
         * @param $reason - $reason of deletion
         * @param $deletedBy - one who deleted
         */
        public function unsetFsoVisitSeal($reasonEnum,$reasonDetail, $deletedBy) {
                $fsoObj = ProfileFSO::getInstance();
                $fsoObj->delete($this->pID);
                $record = array("PROFILEID" => $this->pID, "REASON" => $reasonEnum,"DETAIL" => $reasonDetail, "BY" => $deletedBy);
                $recordObj = new PROFILE_VERIFICATION_FSO_DELETION();
                $recordObj->deletionRecord($record);
                $this->setForSolrSearch();
        }
        /**
         * This function fetches the documents that have been verified for a
         * @param $profileID - profileID whose data is to be fetched
         * @return $dataArr - array of documents for the particular profile
         */
        public function getVerifiedDocumets($profileIDArr)
        {
            $key=array();
            $memcacheObj = JsMemcache::getInstance();
            foreach($profileIDArr as $k=>$v)
            {
                $key[$k] = "VerificationSeal_" . $v;
                $memcacheSeal = $memcacheObj->get($key[$k]);
                if ($memcacheSeal==1)
                    return;
                if ($memcacheSeal)
                    return $memcacheSeal;
            }
            $sealArr = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$ATTRIBUTES;
            $docArr = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$DOCUMENTS;
            $sealObj = new PROFILE_VERIFICATION_DOCUMENTS();
            $seal = $sealObj->sealDetails($profileIDArr);
            if ($seal != 0)
            {

                foreach ($seal as $attribute => $doc) {
                    
                    if($sealArr[$attribute]=="Highest Qualification"){
                                $sealArr[$attribute]="Qualification";}
                        if($sealArr[$attribute]=="Self Address")
                                $sealArr[$attribute]="Self_Address";
                        if($sealArr[$attribute]=="Parent's Address")
                                $sealArr[$attribute]="Parents_Address";
                        if($sealArr[$attribute]=="Date of Birth")
                                $sealArr[$attribute]="Date_of_Birth";
                    $finalSeal['VERIFICATION_SEAL'][$sealArr[$attribute]] = $docArr[$doc];
                }
            }
            else
                $finalSeal = 1;
                foreach($profileIDArr as $k=>$v)
                {
                    $memcacheObj->set($key[$k], $finalSeal, $timeToStoreSealForProfilePage);
                }
                return $finalSeal;
        }
}       

?>
