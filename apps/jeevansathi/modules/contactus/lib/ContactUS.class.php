<?php

class ContactUS
{
	//MEMBER VARIABLE
	
	//Class object of newjs_CONTACT_US Store
	private $m_objDB_ContactUS;
	
	
	private $m_iProfileid;
	
	private $m_arrLocation;
	private $m_arrInfoSel;
	private $m_arrCityLabel;
	
	private $m_bShow_City;
	private $m_bDefaultFlag;
	
	private $m_szDefaultCity;
	private $m_szDefaultState;
	
	private $m_arrInfoCity;
	private $m_arrInfo;
	
	private $m_listCity;
	private $m_listState;
	private $m_szSelected_State;
	
	private $m_szGoogleApiKey;
	
	public function __construct($iProfileID = null)
	{
		$this->m_objDB_ContactUS = new newjs_CONTACT_US();	
		$this->m_iProfileid = $iProfileID;
		$this->m_szGoogleApiKey = "ABQIAAAAvW_F45jx_YHepDJ_Y3F1zxSfh2wRd7dhObWCYR02fS1iBDjeOBQUBf3Cc6Ssz5YevL7c8oFZ2DBFtQ";
	}
	
	public function SetSelection($szState)
	{
		$this->m_szSelected_State = $szState;
	}
	
	
	public function processData(sfWebRequest $request=null)
	{
		
		if($request!=null)
		{
			$this->m_iProfileid = $request->getAttribute("profileid");
			$this->m_szSelected_State = $request->getParameter('st_sel');
			$host=$request->getHost();
			$this->Calc_GoogleAPI_Key($host);
		}
		
		$arrResult = array();
		
		//Get Data From DB
		$this->m_objDB_ContactUS->fetch_All_Contact($arrResult);
		$arrCity = FieldMap::getFieldLabel("city_india",'',1);
					
		
		
		//Now Process Data		
		foreach($arrResult as $myrow)
		{		
			$address=$myrow['ADDRESS'];
				
			$name = ucfirst(strtolower($myrow['NAME']));
			
			$value=$myrow['VALUE'];
						
			$state = ucwords($myrow['STATE']);
			
			$contact_person=$myrow['CONTACT_PERSON'];

			$phone=$myrow['PHONE'];
			$mobile=$myrow['MOBILE'];
					
			$state_value = $myrow['STATE_VAL'];
			$state_label = $arrCity[$state_value];
			$city_id = $myrow['CITY_ID'];
            
			//Match point centers added
			$mps_Flag= false;
			$match_point_service=$myrow['Match_Point_Service'];
			
			$latitude = $myrow['LATITUDE'];
			$longitude = $myrow['LONGITUDE'];
			
			if($match_point_service == 'Y')
				$mps_Flag= true;
			//end
			
			$state_label_Arr[] = $state_label;
			$city_label_Arr[$state_label][] = $state;
			$location_label_Arr[$state][] = $name;		
			
			
			$info[$state][$name]=array("VALUE" => $value,
					"NAME" => $name,
					"CITY"=> $city,
					"CONTACT" => $contact_person,
					"ADDRESS" => $address,
					"PHONE" => $phone,
					"MOBILE" => $mobile,
					"MATCH_POINT_SERVICE" => $mps_Flag,
					"LATITUDE" => $latitude,
					"LONGITUDE" => $longitude,
					"STATE"=>$state_label,
                    "STATE_VAL"=>$state_value,
                    "CITY_ID"=>$city_id,
					);
			
			//if($name=='Head office' || $name=='Match point office')
			if($name=='Head office')
			{
				if($name=='Head office')
					$name="HO";
				else
					$name="MPO";					
				$infoSel[$name]=array("VALUE" => $value,
						"NAME" => $name,
						"CITY"=> $city,
						"CONTACT" => $contact_person,
						"ADDRESS" => $address,
						"PHONE" => $phone,
						"MOBILE" => $mobile,
						"MATCH_POINT_SERVICE" => $mps_Flag,
						"LATITUDE" => $latitude,
						"LONGITUDE" => $longitude,
						"STATE"=>$state_label,
                        "STATE_VAL"=>$state_value,
                        "CITY_ID"=>$city_id
						);
			}
		}
		
		$listState	= array();
		$listCity	= array();
		
		$listState  = array_unique($state_label_Arr);
		sort($listState); // Contains All States Which will be displayed in Tab under 'Select State' Label
		
		$szDefaultCity = "All";
		$szDefaultState = "Delhi";
		
		$defaultStateFlag = false;
		$show_sel_city    = false;
		
		if($this->m_iProfileid)
		{
			
			$this->loginProfile=LoggedInProfile::getInstance();
			$this->loginProfile->getDetail($this->m_iProfileid,"PROFILEID","CITY_RES");
			
			$szCity = $this->loginProfile->getCITY_RES();
			$len = strlen($szCity);
			
			if($len >0)
				$szDefaultState =  $arrCity[substr($szCity,0,2)];
			else
				$szDefaultState = "Delhi";
			
			$szDefaultCity = $arrCity[$szCity];
			
			$cityArrays = $city_label_Arr[$szDefaultState];
			$cityArraysUniq = array_unique($cityArrays); 
			
			
			if(!in_array($szDefaultCity,$cityArraysUniq) || !$szDefaultCity)
			{
				$szDefaultCity ="All";
			}
		}
		if($this->m_szSelected_State and in_array($this->m_szSelected_State,$listState))
		{
			$szDefaultState = $this->m_szSelected_State;
		}
		
		
		if($szDefaultState == "Delhi")
		{
			$defaultStateFlag = true;
			$locationArr = array_unique($location_label_Arr[$szDefaultState]);
		}
		
		if($szDefaultCity!='All')
		{
			$show_sel_city = true;
		}
		
		$listCity = array_unique($city_label_Arr[$szDefaultState]);
		sort($listCity);
				
		$iTotal_City = count($listCity);
		for($iCtr=0;$iCtr<$iTotal_City;$iCtr++)
		{
			$cityLabel = $listCity[$iCtr];
			
			$listSubCity = $location_label_Arr[$cityLabel];
			if(count($listSubCity))
			{
				$listSubCity = array_unique($listSubCity);	
				sort($listSubCity);
			}	
			else
			{
				continue;
			}
			
			foreach($listSubCity as $szLabel)
			{
				$infoCity[$cityLabel][$szLabel] = $info[$cityLabel][$szLabel];
			}
		}
				
		//Assign value

		$this->m_arrLocation  = $locationArr;
		
		$this->m_szDefaultState = $szDefaultState;
		$this->m_szDefaultCity  = $szDefaultCity;
		
		$this->m_bDefaultFlag = $defaultStateFlag;
		
		$this->m_listState = $listState;
		$this->m_listCity  = $listCity;
		$this->m_arrInfo   = $info;
	
		$this->m_arrCityLabel = $city_label_Arr;
		$this->m_arrInfoSel   = $infoSel;
		
		$this->m_arrInfoCity  = $infoCity;
		$this->m_bShow_City   = $show_sel_city;
	}
	
	
	public function setProfileID($iProfileID)
	{
		$this->m_iProfileid = $iProfileID;
	}
	
	public function getInfoSel()
	{
		return $this->m_arrInfoSel;
	}
	
	public function getCityLabel()
	{
		return $this->m_arrCityLabel;
	}
	
	public function getLocation()
	{
		return $this->m_arrLocation;
	}
	
	
	public function getShowCity()
	{
		return $this->m_bShow_City;
	}
	
	public function getDefaultFlag()
	{
		return $this->m_bDefaultFlag;
	}
	
	public function getDefaultCityLabel()
	{
		return $this->m_szDefaultCity;
	}
	
	public function getDefaultStateLabel()
	{
		return $this->m_szDefaultState;
	}

	public function getInfoCity()
	{
		return $this->m_arrInfoCity;
	}
	
	public function getInfo()
	{
		return $this->m_arrInfo;
	}
	
	public function getListCity()
	{
		return $this->m_listCity;
	}
	
	public function getListState()
	{
		return $this->m_listState;
	}
	
	public function Calc_GoogleAPI_Key($url)
	{ 
        $Key = JsConstants::$googleMapApiKey;
		$this->m_szGoogleApiKey = "http://www.google.com/jsapi?key=".$Key."&callback=loadMaps";	
	}
	
	public function getGoogleApiKey()
	{
		return $this->m_szGoogleApiKey;
	}
}

?>
