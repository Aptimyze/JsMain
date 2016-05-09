<?php
class SEOBreadCrumb
{	
	
	private $TYPE;
	
	private $PAGE_SOURCE;
	
	private $PARENT_VALUE;
	
	private $PARENT_TYPE;
	
	private $MAPPED_TYPE;
	
	private $MAPPED_VALUE;
	
	private $LEVEL1_COUNT;
	
	private $levelonebreadcrumb;
	
	private $levelonedropdown;

	private $leveltwobreadcrumb;

	private $LevelOneCntArr;
	
	function __construct()
	{
		$this->LevelOneCntArr = array("STATE"=>9,"MTONGUE"=>12,"CASTE"=>13,"RELIGION"=>8,"COUNTRY"=>9,"OCCUPATION"=>9);
		$this->TypeArr = array("RELIGION"=>0,"MTONGUE"=>1,"OCCUPATION"=>2,"CASTE"=>3,"CITY"=>4,"STATE"=>5,"COUNTRY"=>6,"OTHERS"=>7);
	}
	
	function setPageSource($PAGE_SOURCE) {$this -> PAGE_SOURCE = $PAGE_SOURCE;}
	function getPageSource() { return $this->PAGE_SOURCE;}
	
	function setParentValue($PARENT_VALUE) {$this->PARENT_VALUE = $PARENT_VALUE;}
	function getParentValue() { return $this->PARENT_VALUE;}
	
	function setParentType($PARENT_TYPE) { $this->PARENT_TYPE = $PARENT_TYPE;}
	function getParentType() { return $this->PARENT_TYPE;}
	
	function setMappedValue($MAPPED_VALUE) {$this->MAPPED_VALUE = $MAPPED_VALUE;}
	function getMappedValue() { return $this->MAPPED_VALUE;}
	
    function setMappedType($MAPPED_TYPE) {$this->MAPPED_TYPE = $MAPPED_TYPE;}
	function getMappedType() { return $this->MAPPED_TYPE;}
	
	function setLevel1Count()
	{
		if(array_key_exists($this->PARENT_TYPE, $this->LevelOneCntArr))
			$this->LEVEL1_COUNT = $this->LevelOneCntArr[$this->PARENT_TYPE];
		else 
			$this->LEVEL1_COUNT = 11;
	}
	
	function getLevel1Count()
	{
		return $this->LEVEL1_COUNT;
	}
		
	function setLevelOneBreadCrumb()
	{
		
		$storeObj = new NEWJS_COMMUNITY_PAGES();		
		$whereArr = array("TYPE"=>"'$this->PARENT_TYPE'","PAGE_SOURCE"=>$this->PAGE_SOURCE);
		if($this->PARENT_TYPE==FetchProfiles::MSTATUS_TYPE || $this->PARENT_TYPE == FetchProfiles::SPL_TYPE)
			$whereArr = array("TYPE"=>"'SPECIAL_CASES','MSTATUS'","PAGE_SOURCE"=>$this->PAGE_SOURCE);
		$result = $storeObj->fetchL1BreadCrumb($whereArr);
		$i=0;
		foreach($result as $key=>$row)
		{
			$finalurl=sfConfig::get('app_site_url').$row['URL'];

			if($row['SMALL_LABEL']=='')
				$label=$row['LABEL_NAME'];
			else
				$label=$row['SMALL_LABEL'];
			if($this->PAGE_SOURCE=='N')	
	        	$title = $label." Matrimony";
        	else if($this->PAGE_SOURCE=='G')
                $title = $label." Groom";
        	else if($this->PAGE_SOURCE=='B')
				$title = $label." Bride";
			$title = str_replace('-',' ',$title);
			$title = str_replace('/',' ',$title);	
			
			if($row['VALUE']==$this->getParentValue())
				$style="color:#b6001b;font-weight:bold";
			else
				$style="";				
	    
			
			if($i<$this->LEVEL1_COUNT)
			{	
				    $this->levelonebreadcrumb[$i][0]= $finalurl;
					$this->levelonebreadcrumb[$i][1]=$title;
					$this->levelonebreadcrumb[$i][2]=$label;
					$this->levelonebreadcrumb[$i][3]=$style;
			}	
		    
			if($i>=$this->LEVEL1_COUNT)	
			{
				$this->levelonedropdown[$i-$this->LEVEL1_COUNT][0] = $finalurl;
				$this->levelonedropdown[$i-$this->LEVEL1_COUNT][1]=$title;
				$this->levelonedropdown[$i-$this->LEVEL1_COUNT][2] = $label;
				$this->levelonedropdown[$i-$this->LEVEL1_COUNT][3] = $style;
			}	
			$i++;
		}
		
	}
	function setLevelTwoBreadCrumb()
	{
		$whereArr = array("PARENT_VALUE"=>$this->PARENT_VALUE,"PARENT_TYPE"=>$this->PARENT_TYPE,"PAGE_SOURCE"=>$this->PAGE_SOURCE);
		$storeobj = new NEWJS_COMMUNITY_PAGES_MAPPING();
		$storeobj1= new NEWJS_COMMUNITY_PAGES();
		$result = $storeobj->fetchL2BreadCrumb($whereArr);
		$i = 0;
		if(is_array($result))
		foreach($result as $key=>$row)
		{
			$mapped_value=$row['MAPPED_VALUE'];
			$mapped_val[]=$row['MAPPED_VALUE'];
			$mapped_type=$row['MAPPED_TYPE'];
			$url=$row['URL'];
			$follow_l2=$row['FOLLOW'];
			$final_l2_url=sfConfig::get('app_site_url').$url;
			if($mapped_value == $this->getMappedValue() && $mapped_type == $this->getMappedType())
				$style="color:#b6001b;font-weight:bold";
			else
				$style='';
	
			$Arr = array("VALUE"=>$mapped_value,"TYPE"=>$mapped_type);
			
			$res_1 = $storeobj1->getResult($Arr);
			if(is_array($res_1))			
			foreach ($res_1 as $row_1)			
			{
				$long_label=$row_1['LABEL_NAME'];
				$small_label=$row_1['SMALL_LABEL'];
				$type[]=$row_1['TYPE'];
				if($small_label=='')
					$label=$long_label;
				else	
					$label=$small_label;
			}
           
            //$ur="<a href=\"$final_l2_url\" class=\"daddy\">$label</a>";
            foreach($this->TypeArr as $k=>$v)
            {
				if($k==$mapped_type)
				{
					
					if($this->PAGE_SOURCE=='N')	
						$title = $label." Matrimony";
					else if($this->PAGE_SOURCE=='G')
						$title = $label." Groom";
					else if($this->PAGE_SOURCE=='B')
						$title = $label." Bride";
					$title = str_replace('-',' ',$title);
					$title = str_replace('/',' ',$title);
            		$this->leveltwobreadcrumb[$v][0][]=$mapped_value;
					$this->leveltwobreadcrumb[$v][1][]=$label;
					$this->leveltwobreadcrumb[$v][2][]=$final_l2_url;
					$this->leveltwobreadcrumb[$v][3][]=ucwords(strtolower($mapped_type));
					$this->leveltwobreadcrumb[$v][6][]=$title;
					
					if($mapped_type=="RELIGION"||$mapped_type=="MTONGUE"||$mapped_type=="CITY"||$mapped_type=="STATE"||$mapped_type=="COUNTRY"||$mapped_type=="OTHERS")
						$tabCount = 4;
					else if($mapped_type=="CASTE")
						$tabCount = 3;
					else if($mapped_type=="OCCUPATION")
						$tabCount = 2;	
					$this->leveltwobreadcrumb[$v][4][]=$tabCount;
					
					if($mapped_type=="RELIGION"||$mapped_type=="MTONGUE"||$mapped_type=="STATE"||$mapped_type=="COUNTRY"||$mapped_type=="OTHERS")
						$tabStyle = "width:270px; margin-left:-245px;";
					else if($mapped_type=="CASTE")
						$tabStyle = "width:280px; margin-left:-255px;";
					else if($mapped_type=="OCCUPATION")
						$tabStyle = "width:300px; margin-left:-275px;";
					else if($mapped_type=="CITY")
						$tabStyle="width:290px; margin-left:-265px;";
					$this->leveltwobreadcrumb[$v][5][]=$tabStyle;
					$this->leveltwobreadcrumb[$v][7][]=$style;	
				}
            }
			//$this->leveltwobreadcrumb[$mapped_type][4][]=$ur;
			$i++;
		}		
	}
	function getLevelOneBreadCrumb()
	{
		 return $this->levelonebreadcrumb;
	}
	function getLevelOneDropDown()
	{
		return $this->levelonedropdown;
	}
	function getLevelTwoBreadCrumb()
	{
		 if($this->leveltwobreadcrumb) 
			ksort($this->leveltwobreadcrumb); 
		 return $this->leveltwobreadcrumb;
	}
	
}
