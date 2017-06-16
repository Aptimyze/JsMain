<?php
/**
 * @brief This class performs the auto relaxation to search
 * @author Lavesh Rawat
 * @created 2012-07-06
 */
class AutoRelaxation
{
	private $genderMale = 'M';
	private $genderFemale = 'F';
	/*
	* @param SearchParamtersObj object-array storing the deatils of search perfomed.
	*/
	public function __construct($SearchParamtersObj)
	{
		$this->SearchParamtersObj = $SearchParamtersObj;
			
	}

	/*
	* This function will perform the auto relaxation.
        * For Female Searches age: lage-2 to hage+1 , height: height-2 to height+1;
	* For Male   Searches age: lage-1 to lage+2 , height: height-1 to height+2;
	*/	
	public function autoRelax($ProfileObj='')
	{
		$lage = $this->SearchParamtersObj->getLAGE();	
		$hage = $this->SearchParamtersObj->getHAGE();	
		$lheight = $this->SearchParamtersObj->getLHEIGHT();	
		$hheight = $this->SearchParamtersObj->getHHEIGHT();	
		$gender = $this->SearchParamtersObj->getGENDER();	
		$caste = $this->SearchParamtersObj->getCASTE();	
		$mtongue = $this->SearchParamtersObj->getMTONGUE();
		$caste_display = $this->SearchParamtersObj->getCASTE_DISPLAY();	

		$noRelaxParams = $lage."#".$hage."#".$lheight."#".$hheight."#".$caste_display."#".$mtongue;
		$this->SearchParamtersObj->setNoRelaxParams($noRelaxParams);
		/*  Age/Height Relexation */
		if($ProfileObj)
		{
			$myAge = $ProfileObj->getAGE();
			$myHeight = $ProfileObj->getHEIGHT();
		}

                if($gender==$this->genderFemale)
                {
			if($lage && $hage)
			{
	                        $lage = $lage-2;
        	                if($lage<18)
                	                $lage=18;

                        	if($myAge>$hage || !$myAge)
                                	$hage = $hage+1;
	                        if($hage>70)
        	                        $hage = 70;
			}

			if($lheight && $hheight)
			{
	                        $lheight = $lheight-2;
        	                if($lheight<1)
                	                $lheight=1;
                        	if($myHeight>$hheight || !$myHeight)
                                	$hheight = $hheight+1;
				if($hheight>37)
					$hheight=37;
			}
                }
                elseif($gender==$this->genderMale)
                {
			if($lage && $hage)
			{
	                        if($lage>$myAge ||!$myAge)
        	                {
                	                $lage=$lage-1;
                        	        if($lage<21)
                                	        $lage=21;
	                        }
        	                $hage=$hage+2;
                	        if($hage>70)
                        	        $hage=70;
			}
			
			if($lheight && $myHeight)
			{
	                        if($lheight>$myHeight || !$myHeight)
        	                {
                	                $lheight=$lheight-1;
                        	        if($lheight<1)
                                 	       $lheight=1;
	                        }
        	                $hheight=$hheight+2;
                                if($hheight>37)
                                        $hheight=37;
			}
                }
		$this->SearchParamtersObj->setLAGE($lage);
		$this->SearchParamtersObj->setHAGE($hage);
		$this->SearchParamtersObj->setLHEIGHT($lheight);
		$this->SearchParamtersObj->setHHEIGHT($hheight);
                
                $relaxArr["Age"] = $lage." to ".$hage." years";
                $HeightMap = FieldMap::getFieldLabel('height_without_meters','',1);
                
                $relaxArr["Height"] = htmlspecialchars($HeightMap[$lheight]. ' to '.$HeightMap[$hheight]);
		
		/*  caste Relexation */
		if($caste && strpos($caste,",")===false)
		{ 
	                $CasteSuggest = new CasteSuggest;
                        $casteMap = FieldMap::getFieldLabel('caste','',1);
                        
        	        $mappedCaste = $CasteSuggest->getSuggestedCastes($caste,1);
                        if(is_array($mappedCaste))
			{
				$mappedCaste[] = $caste;
				$mappedCaste = array_unique($mappedCaste);
                                
                                foreach($mappedCaste AS $key=>$value){
                                        $casteRelax[] = $casteMap[$value];
                                }
                                
				$casteStr = implode(",",$mappedCaste);
                                $relaxArr["Castes"] = implode(", ",$casteRelax);
				$this->SearchParamtersObj->setCASTE($casteStr);
			}
		}

		/* mtongue relaxation */
		if($mtongue && strpos($mtongue,",")===false)
		{
			$allHindiMtongues = FieldMap::getFieldLabel('allHindiMtongues','',1);
                        $language = FieldMap::getFieldLabel('community','',1);
                       
                        foreach($allHindiMtongues AS $key=>$value){
                                $mtongueRelax[] = $language[$value];
                        }
                        
			if(in_array($mtongue,$allHindiMtongues))
				$mtongue = implode(",",$allHindiMtongues);
                        
                        
                        $relaxArr["Mother Tongue"] = implode(", ",$mtongueRelax);
                        $this->SearchParamtersObj->setMTONGUE($mtongue);
		}

                return $relaxArr;
	}

	/*
	* This function is used to revert back the auto relaxation.
	*/	
	public function revertAutoRelax()
	{
		$noRelaxParams = $this->SearchParamtersObj->getNoRelaxParams();	
		$tempArr = explode("#",$noRelaxParams);
		$lage = $tempArr[0];
		$hage = $tempArr[1];
		$lheight = $tempArr[2];
		$hheight = $tempArr[3];
		$caste = $tempArr[4];
		$mtongue = $tempArr[5];

		$this->SearchParamtersObj->setLAGE($lage);
		$this->SearchParamtersObj->setHAGE($hage);
		$this->SearchParamtersObj->setLHEIGHT($lheight);
		$this->SearchParamtersObj->setHHEIGHT($hheight);
		$this->SearchParamtersObj->setCASTE($caste);
		$this->SearchParamtersObj->setMTONGUE($mtongue);
	}
}
?>
