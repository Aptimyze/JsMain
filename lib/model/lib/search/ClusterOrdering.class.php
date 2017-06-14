<?php
/**
This class gives the ordering of clusters for bride and groom, for logged in and non logged in scenarios.
**/
class ClusterOrdering
{
	private $SearchParamtersObj;

	/*
        * @param  this->SearchParamtersObj->get i.e. the array having values of parameters searched
	*/
	public function __construct($SearchParamtersObj)
	{
		$this->SearchParamtersObj = $SearchParamtersObj;
	}

	/**
        This function sets the ordering array on different scenarios
	* @param 1) profileObj(optional) 3)$orderingFor = 1 for "see full criteria" 2) returnType = 1 for output having cluster names or returnType = 2 for output having solr names 4) check if user is viewing from mobile/tablet, value = 1
        * @return array where index denotes the cluster value and value denotes the cluster position.
        **/
	public function getClusterOrdering($profileObj="",$returnType,$orderingFor="",$mobileOrTablet="")
	{
		if($this->SearchParamtersObj->getGENDER()=="M")
			$groom = 1;
		else
			$groom = 0;
		
		if($profileObj && $profileObj->getPROFILEID() && $profileObj->getRELATION()==1)
			$selfSearch = 1;
		else
			$selfSearch = 0;

		if($profileObj && $profileObj->getPROFILEID() && !$selfSearch)		//LOGGEDIN AND NON_SELF SEARCHING
		{
			if($groom)		//GROOM SEARCH
			{
				$orderingArr[1] = 18;
				$orderingArr[2] = 7;
				$orderingArr[3] = 20;
				if($mobileOrTablet)
					$orderingArr[4] = 0;
				else
					$orderingArr[4] = 5;
				$orderingArr[5] = 22;
				$orderingArr[6] = 23;
				if($this->SearchParamtersObj->getRELIGION()=='1' || $this->SearchParamtersObj->getRELIGION()=='1,4' || $this->SearchParamtersObj->getRELIGION()=='4,1' || $this->SearchParamtersObj->getRELIGION()=='1,9' || $this->SearchParamtersObj->getRELIGION()=='9,1' || $this->SearchParamtersObj->getCASTE_GROUP() || strstr($this->SearchParamtersObj->getCASTE(),","))
					$orderingArr[7] = 24;
				else
					$orderingArr[7] = 0;
				if($this->SearchParamtersObj->getRELIGION()=='1' || $this->SearchParamtersObj->getRELIGION()=='4' || $this->SearchParamtersObj->getRELIGION()=='9' || $this->SearchParamtersObj->getRELIGION()=='2' || $this->SearchParamtersObj->getRELIGION()=='3' || $this->SearchParamtersObj->getRELIGION()=='1,4' || $this->SearchParamtersObj->getRELIGION()=='4,1' || $this->SearchParamtersObj->getRELIGION()=='1,9' || $this->SearchParamtersObj->getRELIGION()=='9,1' || $this->SearchParamtersObj->getCASTE())
					$orderingArr[8] = 25;
				else
					$orderingArr[8] = 0;
				/*if($this->SearchParamtersObj->getCASTE() || $this->SearchParamtersObj->getCASTE_GROUP())	//SUBCASTE IS NOT BEING TAKEN UP
					$orderingArr[9] = 26;
				else
					$orderingArr[9] = 0;*/
				$orderingArr[10] = 8;
				if($mobileOrTablet)
					$orderingArr[11] = 0;
				else
					$orderingArr[11] = 6;
				//$orderingArr[12] = 9;
				$orderingArr[13] = 7;
				if(strstr($this->SearchParamtersObj->getRELIGION(),"2") || strstr($this->SearchParamtersObj->getRELIGION(),"3") || strstr($this->SearchParamtersObj->getRELIGION(),"6"))
					$orderingArr[14] = 0;
				else
					$orderingArr[14] = 13;
				$orderingArr[15] = 15;
				$orderingArr[16] = 3;
				if(strstr($this->SearchParamtersObj->getRELIGION(),"2") || strstr($this->SearchParamtersObj->getRELIGION(),"3") || strstr($this->SearchParamtersObj->getRELIGION(),"6"))
					$orderingArr[17] = 0;
				else
					$orderingArr[17] = 14;
				/*if($this->SearchParamtersObj->getRELIGION()==2 || $this->SearchParamtersObj->getRELIGION()==4)	//NOT BEING TAKEN UP
					$orderingArr[18] = 27;
				else
					$orderingArr[18] = 0;*/
				if(!$profileObj->getHANDICAPPED() || $profileObj->getHANDICAPPED()=="N")
					$orderingArr[19] = 0;
				else
					$orderingArr[19] = 1;
				if($profileObj->getHIV()=="Y")
					$orderingArr[20] = 2;
				else
					$orderingArr[20] = 0;
				if($mobileOrTablet)
					$orderingArr[21] = 0;
				else
					$orderingArr[21] = 19;
				$orderingArr[22] = 0;
				$orderingArr[23] = 0;
				$orderingArr[24] = 4;
				$orderingArr[31] = 12.5;
				if(strstr($this->SearchParamtersObj->getMSTATUS(),"M") || strstr($this->SearchParamtersObj->getMSTATUS(),"S") || strstr($this->SearchParamtersObj->getMSTATUS(),"D") || strstr($this->SearchParamtersObj->getMSTATUS(),"W") || strstr($this->SearchParamtersObj->getMSTATUS(),"A"))
					$orderingArr[25] = 21;
				else
					$orderingArr[25] = 0;
				if($this->SearchParamtersObj->getINDIA_NRI()==1 || $this->SearchParamtersObj->getCOUNTRY_RES()==51)
				{
					$orderingArr[26] = 10;
					$orderingArr[27] = 11;
				}
				else
				{
					$orderingArr[26] = 0;
					$orderingArr[27] = 0;
				}
                                $orderingArr[28] = 9;
                                if(!$this->SearchParamtersObj->getCOUNTRY_RES() || in_array('51',explode(',',$this->SearchParamtersObj->getCOUNTRY_RES()))){
					$orderingArr[28] = 9;
                                        $orderingArr[26] = 10;
					$orderingArr[27] = 11;
                                }else{
                                        $orderingArr[26] = 0;
					$orderingArr[27] = 0;
                                }
				$orderingArr[29] = 17;	
			}
			else		//BRIDE SEARCH
			{
				$orderingArr[1] = 20;
				$orderingArr[2] = 6;
				$orderingArr[3] = 22;
				if($mobileOrTablet)
					$orderingArr[4] = 0;
				else
					$orderingArr[4] = 5.3;
				$orderingArr[5] = 24;
				$orderingArr[6] = 25;
				if($this->SearchParamtersObj->getRELIGION()=='1' || $this->SearchParamtersObj->getRELIGION()=='1,4' || $this->SearchParamtersObj->getRELIGION()=='4,1' || $this->SearchParamtersObj->getRELIGION()=='1,9' || $this->SearchParamtersObj->getRELIGION()=='9,1' || $this->SearchParamtersObj->getCASTE_GROUP() || strstr($this->SearchParamtersObj->getCASTE(),","))
                                        $orderingArr[7] = 26;
                                else
                                        $orderingArr[7] = 0;
				if($this->SearchParamtersObj->getRELIGION()=='1' || $this->SearchParamtersObj->getRELIGION()=='4' || $this->SearchParamtersObj->getRELIGION()=='9' || $this->SearchParamtersObj->getRELIGION()=='2' || $this->SearchParamtersObj->getRELIGION()=='3' || $this->SearchParamtersObj->getRELIGION()=='1,4' || $this->SearchParamtersObj->getRELIGION()=='4,1' || $this->SearchParamtersObj->getRELIGION()=='1,9' || $this->SearchParamtersObj->getRELIGION()=='9,1' || $this->SearchParamtersObj->getCASTE())
                                        $orderingArr[8] = 27;
                                else
                                        $orderingArr[8] = 0;
				/*if($this->SearchParamtersObj->getCASTE() || $this->SearchParamtersObj->getCASTE_GROUP())	//SUBCASTE IS NOT BEING TAKEN UP
                                        $orderingArr[9] = 28;
                                else
                                        $orderingArr[9] = 0;*/
				$orderingArr[10] = 11;
				if($mobileOrTablet)
					$orderingArr[11] = 0;
				else
					$orderingArr[11] = 5.5;
				//$orderingArr[12] = 13;
				$orderingArr[13] = 12;
				if(strstr($this->SearchParamtersObj->getRELIGION(),"2") || strstr($this->SearchParamtersObj->getRELIGION(),"3") || strstr($this->SearchParamtersObj->getRELIGION(),"6"))
					$orderingArr[14] = 0;
				else
					$orderingArr[14] = 7;
				$orderingArr[15] = 9;
				$orderingArr[16] = 4;
				if(strstr($this->SearchParamtersObj->getRELIGION(),"2") || strstr($this->SearchParamtersObj->getRELIGION(),"3") || strstr($this->SearchParamtersObj->getRELIGION(),"6"))
					$orderingArr[17] = 0;
				else
					$orderingArr[17] = 8;
				/*if($this->SearchParamtersObj->getRELIGION()==2 || $this->SearchParamtersObj->getRELIGION()==4)	//NOT BEING TAKEN UP
					$orderingArr[18] = 29;
				else
					$orderingArr[18] = 0;*/
				if(!$profileObj->getHANDICAPPED() || $profileObj->getHANDICAPPED()=="N")
                                        $orderingArr[19] = 0;
                                else
                                        $orderingArr[19] = 1;
                                if($profileObj->getHIV()=="Y")
                                        $orderingArr[20] = 2;
                                else
                                        $orderingArr[20] = 0;
				if($mobileOrTablet)
					$orderingArr[21] = 0;
				else
					$orderingArr[21] = 21;
				if($profileObj->getCOUNTRY_RES()!=51)
					$orderingArr[22] = 3;
				else
					$orderingArr[22] = 0;
				$orderingArr[23] = 18;
				$orderingArr[24] = 5;
				$orderingArr[31] = 16.5;
				if(strstr($this->SearchParamtersObj->getMSTATUS(),"M") || strstr($this->SearchParamtersObj->getMSTATUS(),"S") || strstr($this->SearchParamtersObj->getMSTATUS(),"D") || strstr($this->SearchParamtersObj->getMSTATUS(),"W") || strstr($this->SearchParamtersObj->getMSTATUS(),"A"))
					$orderingArr[25] = 23;
				else
					$orderingArr[25] = 0;
				/*if($this->SearchParamtersObj->getINDIA_NRI()==1 || in_array('51',explode(',',$this->SearchParamtersObj->getCOUNTRY_RES())))
				{
					$orderingArr[26] = 14;
					$orderingArr[27] = 15;
				}
				else
				{
					$orderingArr[26] = 0;
					$orderingArr[27] = 0;
				}*/
                                $orderingArr[28] = 13;
				if(!$this->SearchParamtersObj->getCOUNTRY_RES() || in_array('51',explode(',',$this->SearchParamtersObj->getCOUNTRY_RES()))){
					$orderingArr[28] = 13;
                                        $orderingArr[26] = 14;
					$orderingArr[27] = 15;
                                }else{
                                        $orderingArr[26] = 0;
					$orderingArr[27] = 0;
                                }
				$orderingArr[29] = 19;	
			}
		}
		else		//LOGGEDOUT OR LOGGEDIN SELF SEARCHING
		{
			if($groom)		//GROOM SEARCH
			{
				$orderingArr[3] = 20;
				if($mobileOrTablet)
					$orderingArr[4] = 0;
				else
					$orderingArr[4] = 5;
				$orderingArr[5] = 22;
				$orderingArr[6] = 23;
				if($this->SearchParamtersObj->getRELIGION()=='1' || $this->SearchParamtersObj->getRELIGION()=='1,4' || $this->SearchParamtersObj->getRELIGION()=='1,9' || $this->SearchParamtersObj->getRELIGION()=='4,1' || $this->SearchParamtersObj->getRELIGION()=='9,1' || $this->SearchParamtersObj->getCASTE_GROUP() || strstr($this->SearchParamtersObj->getCASTE(),","))
                                        $orderingArr[7] = 24;
                                else
                                        $orderingArr[7] = 0;
				if($this->SearchParamtersObj->getRELIGION()=='1' || $this->SearchParamtersObj->getRELIGION()=='4' || $this->SearchParamtersObj->getRELIGION()=='9' || $this->SearchParamtersObj->getRELIGION()=='2' || $this->SearchParamtersObj->getRELIGION()=='3' || $this->SearchParamtersObj->getRELIGION()=='1,4' || $this->SearchParamtersObj->getRELIGION()=='1,9' || $this->SearchParamtersObj->getRELIGION()=='4,1' || $this->SearchParamtersObj->getRELIGION()=='9,1' || $this->SearchParamtersObj->getCASTE())
                                        $orderingArr[8] = 25;
                                else
                                        $orderingArr[8] = 0;
				/*if($this->SearchParamtersObj->getCASTE() || $this->SearchParamtersObj->getCASTE_GROUP())	//SUBCASTE IS NOT BEING TAKEN UP
                                        $orderingArr[9] = 26;
                                else
                                        $orderingArr[9] = 0;*/
				$orderingArr[10] = 8;
				if($mobileOrTablet)
					$orderingArr[11] = 0;
				else
					$orderingArr[11] = 6;
				//$orderingArr[12] = 9;
				$orderingArr[13] = 7;
				if(strstr($this->SearchParamtersObj->getRELIGION(),"2") || strstr($this->SearchParamtersObj->getRELIGION(),"3") || strstr($this->SearchParamtersObj->getRELIGION(),"6"))
					$orderingArr[14] = 0;
				else
					$orderingArr[14] = 13;
				$orderingArr[15] = 15;
				if($profileObj && $profileObj->getPROFILEID())
					$orderingArr[16] = 3;
				else
					$orderingArr[16] = 0;
				if(strstr($this->SearchParamtersObj->getRELIGION(),"2") || strstr($this->SearchParamtersObj->getRELIGION(),"3") || strstr($this->SearchParamtersObj->getRELIGION(),"6"))
					$orderingArr[17] = 0;
				else
					$orderingArr[17] = 14;
				/*if($this->SearchParamtersObj->getRELIGION()==2 || $this->SearchParamtersObj->getRELIGION()==4)	//NOT BEING TAKEN UP
					$orderingArr[18] = 27;
				else
					$orderingArr[18] = 0;*/
				if($mobileOrTablet)
					$orderingArr[21] = 0;
				else
					$orderingArr[21] = 19;
				$orderingArr[22] = 0;
				$orderingArr[23] = 0;
				$orderingArr[31] = 12.5;
				if(strstr($this->SearchParamtersObj->getMSTATUS(),"M") || strstr($this->SearchParamtersObj->getMSTATUS(),"S") || strstr($this->SearchParamtersObj->getMSTATUS(),"D") || strstr($this->SearchParamtersObj->getMSTATUS(),"W") || strstr($this->SearchParamtersObj->getMSTATUS(),"A"))
					$orderingArr[25] = 21;
				else
					$orderingArr[25] = 0;
				if($this->SearchParamtersObj->getINDIA_NRI()==1 || $this->SearchParamtersObj->getCOUNTRY_RES()==51)
				{
					$orderingArr[26] = 10;
					$orderingArr[27] = 11;
				}
				else
				{
					$orderingArr[26] = 0;
					$orderingArr[27] = 0;
				}
                                $orderingArr[28] = 9;
                                if($this->SearchParamtersObj->getINDIA_NRI()==2 || (!$this->SearchParamtersObj->getCOUNTRY_RES() || in_array('51',explode(',',$this->SearchParamtersObj->getCOUNTRY_RES())))){
					$orderingArr[28] = 9;
                                        $orderingArr[26] = 10;
					$orderingArr[27] = 11;
                                }else{
                                        $orderingArr[26] = 0;
					$orderingArr[27] = 0;
                                }
                                
				if($profileObj  && $profileObj->getPROFILEID())
				{
					$orderingArr[1] = 18;
					$orderingArr[2] = 16;
					if(!$profileObj->getHANDICAPPED() || $profileObj->getHANDICAPPED()=="N")
                                        	$orderingArr[19] = 0;
                                	else
                                        	$orderingArr[19] = 1;
                                	if($profileObj->getHIV()=="Y")
                                        	$orderingArr[20] = 2;
                                	else
                                        	$orderingArr[20] = 0;
					$orderingArr[24] = 4;
					$orderingArr[29] = 17;	
				}
				else
				{
					$orderingArr[1] = 0;
					$orderingArr[2] = 0;
					$orderingArr[19] = 0;
					$orderingArr[20] = 0;
					$orderingArr[24] = 0;
					$orderingArr[29] = 0;	
				}
			}
			else		//BRIDE SEARCH
			{
				$orderingArr[3] = 22;
				if($mobileOrTablet)
					$orderingArr[4] = 0;
				else
					$orderingArr[4] = 5.5;
				$orderingArr[5] = 24;
				$orderingArr[6] = 25;
				if($this->SearchParamtersObj->getRELIGION()=='1' || $this->SearchParamtersObj->getRELIGION()=='1,4' || $this->SearchParamtersObj->getRELIGION()=='1,9' || $this->SearchParamtersObj->getRELIGION()=='4,1' || $this->SearchParamtersObj->getRELIGION()=='9,1' || $this->SearchParamtersObj->getCASTE_GROUP() || strstr($this->SearchParamtersObj->getCASTE(),","))
                                        $orderingArr[7] = 26;
                                else
                                        $orderingArr[7] = 0;
				if($this->SearchParamtersObj->getRELIGION()=='1' || $this->SearchParamtersObj->getRELIGION()=='4' || $this->SearchParamtersObj->getRELIGION()=='9' || $this->SearchParamtersObj->getRELIGION()=='2' || $this->SearchParamtersObj->getRELIGION()=='3' || $this->SearchParamtersObj->getRELIGION()=='1,4' || $this->SearchParamtersObj->getRELIGION()=='1,9' || $this->SearchParamtersObj->getRELIGION()=='4,1' || $this->SearchParamtersObj->getRELIGION()=='9,1' || $this->SearchParamtersObj->getCASTE())
                                        $orderingArr[8] = 27;
                                else
                                        $orderingArr[8] = 0;
				/*if($this->SearchParamtersObj->getCASTE() || $this->SearchParamtersObj->getCASTE_GROUP())	//SUBCASTE IS NOT BEING TAKEN UP
                                        $orderingArr[9] = 28;
                                else
                                        $orderingArr[9] = 0;*/
				$orderingArr[10] = 7;
				if($mobileOrTablet)
					$orderingArr[11] = 0;
				else
					$orderingArr[11] = 5.8;
				//$orderingArr[12] = 9;
				$orderingArr[13] = 8;
				if(strstr($this->SearchParamtersObj->getRELIGION(),"2") || strstr($this->SearchParamtersObj->getRELIGION(),"3") || strstr($this->SearchParamtersObj->getRELIGION(),"6"))
					$orderingArr[14] = 0;
				else
					$orderingArr[14] = 15;
				$orderingArr[15] = 17;
				if($profileObj && $profileObj->getPROFILEID())
					$orderingArr[16] = 4;
				else
					$orderingArr[16] = 0;
				if(strstr($this->SearchParamtersObj->getRELIGION(),"2") || strstr($this->SearchParamtersObj->getRELIGION(),"3") || strstr($this->SearchParamtersObj->getRELIGION(),"6"))
					$orderingArr[17] = 0;
				else
					$orderingArr[17] = 16;
				/*if($this->SearchParamtersObj->getRELIGION()==2 || $this->SearchParamtersObj->getRELIGION()==4)	//NOT BEING TAKEN UP
					$orderingArr[18] = 29;
				else
					$orderingArr[18] = 0;*/
				if($profileObj && $profileObj->getPROFILEID())
				{
					$orderingArr[1] = 20;
					$orderingArr[2] = 18;
					if(!$profileObj->getHANDICAPPED() || $profileObj->getHANDICAPPED()=="N")
                                                $orderingArr[19] = 0;
                                        else
                                                $orderingArr[19] = 1;
                                        if($profileObj->getHIV()=="Y")
                                                $orderingArr[20] = 2;
                                        else
                                                $orderingArr[20] = 0;
					if($profileObj->getCOUNTRY_RES()!=51)
						$orderingArr[22] = 3;
					else
						$orderingArr[22] = 0;
					$orderingArr[24] = 5;
					$orderingArr[29] = 19;	
				}
				else
				{
					$orderingArr[1] = 0;
					$orderingArr[2] = 0;
					$orderingArr[19] = 0;
					$orderingArr[20] = 0;
					$orderingArr[22] = 0;
					$orderingArr[24] = 0;
					$orderingArr[29] = 0;	
				}
				if($mobileOrTablet)
					$orderingArr[21] = 0;
				else
					$orderingArr[21] = 21;
				$orderingArr[23] = 14;
				$orderingArr[31] = 12.5;
				if(strstr($this->SearchParamtersObj->getMSTATUS(),"M") || strstr($this->SearchParamtersObj->getMSTATUS(),"S") || strstr($this->SearchParamtersObj->getMSTATUS(),"D") || strstr($this->SearchParamtersObj->getMSTATUS(),"W") || strstr($this->SearchParamtersObj->getMSTATUS(),"A"))
					$orderingArr[25] = 23;
				else
					$orderingArr[25] = 0;
				if($this->SearchParamtersObj->getINDIA_NRI()==1 || $this->SearchParamtersObj->getCOUNTRY_RES()==51)
				{
					$orderingArr[26] = 10;
					$orderingArr[27] = 11;
				}
				else
				{
					$orderingArr[26] = 0;
					$orderingArr[27] = 0;
				}
                                $orderingArr[28] = 9;
				if(!$this->SearchParamtersObj->getCOUNTRY_RES() || in_array('51',explode(',',$this->SearchParamtersObj->getCOUNTRY_RES()))){
					$orderingArr[28] = 9;
                                        $orderingArr[26] = 10;
					$orderingArr[27] = 11;
                                }else{
                                        $orderingArr[26] = 0;
					$orderingArr[27] = 0;
                                }
			}
		}
                //print_r($orderingArr);die;
		$orderingArr = $this->formatOutput($orderingArr,$returnType,$orderingFor);
		$SearchUtility = new SearchUtility;
		if($SearchUtility->isMatchAlertsPage($this->SearchParamtersObj))
		{
			unset($arr);
			$arr[] = "MATCHALERTS_DATE_CLUSTER";
			foreach($orderingArr as $v)
				$arr[] = $v;
			return $arr;
		}
		return $orderingArr;
	}

	/**
        This function formats the return array by removing unset values and sorting it based on sorting values and replacing values by labels
        * @param 1) orderingArray generated by getClusterOrdering() function 2) returnType as mentioned above. 
        * @return array having cluster or solr labels in the required sort order.
        **/
	private function formatOutput($orderingArr,$returnType,$orderingFor="")
	{
		foreach($orderingArr as $k=>$v)			//REMOVE UNSET VALUES
		{
			if(!$v)
			{
				if($orderingFor==1)
					$orderingArr[$k] = max($orderingArr)+1;
				else
					unset($orderingArr[$k]);
			}
		}
		
		asort($orderingArr);		//SORT THE ARRAY
		//print_r($orderingArr);die;
		if($returnType == 1)	//Cluster labels
			$clusters_labels = FieldMap::getFieldLabel("search_clusters",1,1);
		elseif($returnType == 2)	//Solr labels
			$clusters_labels = FieldMap::getFieldLabel("solr_clusters",1,1);
		if($orderingFor == 1)
			$output[] = "Looking for";

		foreach($orderingArr as $k=>$v)
		{
			$output[] = $clusters_labels[$k];
		}
		return $output;
	}

	/**
        This function handles the quick search band sorting logics i.e. when a particular cluster needs to be shifted at the bottom
        * @param 1) orderingArray generated by getClusterOrdering()
        * @return array where index has the cluster value and value has the cluster ordering
        **/
	private function quickSearchBandOrderingLogic($orderingArr)
	{
		if($this->SearchParamtersObj->getMSTATUS() == "N")
		{
			if($orderingArr[3])
			{
				$max = max($orderingArr);
				$orderingArr[3] = $max+1;
			}
			if($orderingArr[25])
			{
				$max = max($orderingArr);
				$orderingArr[25] = $max+1;
			}
		}
		if($this->SearchParamtersObj->getMTONGUE())
                {
			if($orderingArr[5])
			{
                        	$max = max($orderingArr);
                        	$orderingArr[5] = $max+1;
			}
                }
		if($this->SearchParamtersObj->getRELIGION())
                {
			if($orderingArr[6])
			{
                        	$max = max($orderingArr);
                        	$orderingArr[6] = $max+1;
			}
                }
		if($this->SearchParamtersObj->getCASTE() && strpos($this->SearchParamtersObj->getCASTE(),","))
                {
			if($orderingArr[7])
			{
                        	$max = max($orderingArr);
                        	$orderingArr[7] = $max+1;
			}
                }
		if($this->SearchParamtersObj->getCASTE() && !strpos($this->SearchParamtersObj->getCASTE(),","))
                {
			if($orderingArr[8])
			{
                        	$max = max($orderingArr);
                        	$orderingArr[8] = $max+1;
			}
                }
		if(!$this->SearchParamtersObj->getCITY_RES() && !$this->SearchParamtersObj->getCOUNTRY_RES())
                {
			if($orderingArr[12])
			{
                        	$max = max($orderingArr);
                        	$orderingArr[12] = $max+1;
			}
			if($orderingArr[26])
			{
                        	$max = max($orderingArr);
                        	$orderingArr[26] = $max+1;
			}
			if($orderingArr[27])
			{
                        	$max = max($orderingArr);
                        	$orderingArr[27] = $max+1;
			}
			if($orderingArr[28])
			{
                        	$max = max($orderingArr);
                        	$orderingArr[28] = $max+1;
			}
			
                }
		return $orderingArr;
	}
}
?>
