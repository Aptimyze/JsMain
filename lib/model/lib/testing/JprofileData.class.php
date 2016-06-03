<?php
class JprofileData 
{
        public function __construct($userDetails,$shard,$profiles='',$newlyRegistered='')
        {
		$this->gender  = $userDetails['GENDER'];
		$this->incomplete = $userDetails['INCOMPLETE'];
		$this->subscription = $userDetails['SUBSCRIPTION'];
		$this->activated = $userDetails['ACTIVATED'];
		$this->religion = $userDetails['RELIGION'];
		$this->shard = $shard;
		$this->newlyRegistered = $newlyRegistered;
		$this->profiles = $profiles;
        }
        public function profileRand()
        {
                $a['LOW'] = rand(1,1000000);
                $a['HIGH'] = $a['LOW']+7000;
                return $a;
        }

        public function getJprofileData()
        {
                if(!$this->gender||!$this->activated||!$this->incomplete)
                {
                        $this->error = true;
                        $this->errorMessage = "incomplete information gender, activation status. incomplete or subscription";
                        return;
                }
                if(!JprofileParamsAllowed::GENDER($this->gender)||!JprofileParamsAllowed::ACTIVATED($this->activated)||!JprofileParamsAllowed::INCOMPLETE($this->incomplete)||!JprofileParamsAllowed::SUBSCRIPTION($this->subscription))
                {
                        $this->error =true;
                        $this->errorMessage = "value of paramters is not as expected for gender, activated, incomplete or subscription";
                        return;
                }
                $return = array();
                do
                {
                        unset($set);
                        $valArray['GENDER']=$this->gender;
                        $valArray['ACTIVATED']=$this->activated;
                        $valArray['INCOMPLETE']=$this->incomplete;
                        $valArray['MOB_STATUS']="Y";
                        if($religion)
                                $valArray['RELIGION']=$religion;
                        if(is_array($subscription))
                                $valArray['SUBSCRIPTION']="'".implode("','",$subscription)."'";
                        else
                                $excludeArray['SUBSCRIPTION']="'F,D','D,F','D','F'";
                        if($this->profiles)
                                $valArray['PROFILEID']=$this->profiles;
                        else
                        {
                                if($this->shard)
                                        $valArray['PROFILEID%3']=$this->shard;
                                if($this->newlyRegistered=='Y')
                                        $greaterthanArr['ENTRY_DT']=date("Y-m-d H:i:s",mktime(date("H"), date("i"), date("s"), date("m"), date("d")-29, date("Y")));

                                elseif($this->newlyRegistered=='O')
                                        $lessthanArr['ENTRY_DT']=date("Y-m-d H:i:s",mktime(date("H"), date("i"), date("s"), date("m"), date("d")-31, date("Y")));
                                else
                                {       $profileRange = $this->profileRand();
                                        $greaterthanArr['PROFILEID']=$profileRange[LOW];
                                        $lessthanArr['PROFILEID']=$profileRange[HIGH];
                                        $limit = "100";
                                }
                        }
                        $jprofileObj = new JPROFILE;
                        $fields ='PROFILEID,USERNAME,EMAIL,PASSWORD';
                        $set = $jprofileObj->getArray($valArray,$excludeArray,$greaterthanArr,$fields,$lessthanArr,'',$limit,'','',$like);
                        if(is_array($set))
                        {
                                foreach($set as $k=>$v)
                                        $profileData[$v['PROFILEID']]=$v;
                                unset($set);
                        }
                        else
                        {
                                if($this->profiles)
                                        return false;
                        }
                }while(!$profileData || count($profileData)<=0);
                return $profileData;
        }

}
