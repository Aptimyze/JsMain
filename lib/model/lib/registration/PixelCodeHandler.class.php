<?php
/**
 * PixelCodeHandler class
 *
 * @author Esha Jain <esha.jain@jeevansathi.com>
 * @package jeevansathi
 * @subpackage registration
 */

class PixelCodeHandler
{
	
  /*
   * Declaring Memeber Varibales
   */
   private $southMtongue = array("16",//Kannada
				"17",//Malyalam
				"3",//Telugu
				"31",//Tamil
				);
   private $new_cityarr = array(	"UP01",//agra
				"GU01",//Ahmedabad
				"MH30",//Ahmednagar
				"RA01",//Ajmer
				"MH01",//Akola
				"UP02",//Aligarh
				"UP03",//Allahabad
				"RA02",//Alwar
				"HA01",//Ambala
				"UP04",//Amethi
				"PU01",//Amritsar
				"GU02",//Anand
				"KA01",//Ankola
				"MH02",//Aurangabad
				"UP05",//Ayodhya
				"KA02",//Bangalore
				"UP06",//Bareilly
				"GU04",//Baroda/Vadodara
				"PU02",//Bathinda
				"CH04",//Bhilai
				"RA04",//Bhilwara
				"MP02",//Bhopal
				"OR01",//Bhubaneshwar
				"RA05",//Bikaner
				"CH03",//Bilaspur
				"JH04",//Bokaro
				"KE06",//Kozhikhode/ Calicut
				"PH00",//Chandigarh
				"OR02",//Cuttack
				"HP01",//Dalhousie
				"UK05",//Dehradun
				"JH03",//Dhanbad
				"CH02",//Durg
				"WB03",//Durgapur
				"UP08",//Etawah
				"UP09",//Faizabad 
				"HA02",//Faridabad
				"PU03",//Faridkot
				"UP10",//Fatehpur
				"UP11",//Firozabad
				"GU05",//Gandhinagar
				"RA06",//Ganganagar
				"BI03",//Gaya
				"UP12",//Ghaziabad
				"GO",//Goa
				"UP13",//Gorakhpur
				"JK01",//Gulmarg
				"PU05",//Gurdaspur
				"HA03",//Gurgaon
				"MP07",//Gwalior
				"UP14",//Hapur
				"PU06",//Hoshiarpur
				"AP03",//Hyderabad/Secunderabad
				"MP08",//Indore
				"MP09",//Jabalpur
				"RA07",//Jaipur
				"RA11",//Jaisalmer
				"PU10",//Jalandhar
				"JK04",//Jammu
				"JH02",//Jamshedpur
				"UP16",//Jhansi
				"RA08",//Jodhpur
				"AP04",//Kakinada
				"GU07",//Kandla
				"UP17",//Kannauj
				"UP18",//Kanpur
				"HP02",//Kasauli
				"WB04",//Kharagpur
				"MH03",//Kolhapur
				"WB05",//Kolkata
				"RA09",//Kota
				"KE06",//Kozhikhode/ Calicut
				"UP19",//Lucknow
				"PU07",//Ludhiana
				"UP20",//Mathura
				"UP21",//Meerut
				"UP22",//Moradabad
				"MH04",//Mumbai
				"UK01",//Mussoorie
				"UP24",//Muzaffarnagar
				"BI05",//Muzaffarpur
				"KA09",//Mysore
				"MH05",//Nagpur
				"MH06",//Nanded
				"UP25",//Noida
				"GU08",//Palanpur
				"HA06",//Panipat
				"PU08",//Pathankot
				"PU09",//Patiala
				"BI06",//Patna
				"MH08",//Pune/ Chinchwad
				"UP26",//Rae Bareli
				"GU09",//Rajkot
				"JH01",//Ranchi
				"UK04",//Rishikesh
				"HA04",//Rohtak
				"UK03",//Roorkee
				"OR04",//Rourkela
				"UP29",//Saharanpur
				"MH09",//Sangli
				"HP03",//Shimla
				"MH10",//Shirdi
				"HA05",//Sirsa
				"MH11",//Solapur
				"JK03",//Srinagar
				"GU10",//Surat
				"MH12",//Thane
				"RA10",//Udaipur
				"MP11",//Ujjain
				"MH13",//Ulhasnagar
				"GU04",//Baroda/Vadodara
				"GU12",//Vapi
				"UP30",//Varanasi
				"AP09",//Warangal
				"DE00",//New Delhi
				"KA05",//Dharwad
				"UK02",//Haridwar
				"MH24",//Nashik/ Nasik
				"CH01",//Raipur
				"PU11",//Abohar
				"AP13",//Amravati
				"UP32",//Amroha
				"AP14",//Anantapur
				"BI08",//Arrah
				"WB07",//Bally
				"WB08",//Balurghat
				"WB10",//Baranagar
				"WB11",//Barrackpore
				"WB12",//Basirhat
				"PU12",//Batala
				"RA12",//Beawar
				"KA10",//Bellary
				"BI09",//Bhagalpur
				"GU13",//Bharuch 
				"GU14",//Bhavnagar
				"MH14",//Bhiwandi
				"HA07",//Bhiwani 
				"GU15",//Bhuj
				"MH15",//Bhusawal
				"BI10",//Bihar Sharif
				"KA11",//Bijapur
				"UP34",//Budaun
				"UP35",//Bulandshahar
				"MP14",//Burhanpur
				"WB16",//Burnpur
				"WB17",//Chandan Nagar
				"MH16",//Chandrapur
				"BI11",//Chapra
				"AP16",//Chittor
				"WB18",//Coochbehar
				"BI12",//Darbanga
				"WB20",//Darjeeling
				"MP15",//Dewas
				"MH17",//Dhule
				"GU16",//Dwarka
				"UP36",//Etah
				"PU14",//Ferozepur
				"GU17",//Gandhidham
				"GU18",//Godhra
				"MH18",//Gondiya
				"MP16",//Guna
				"WB22",//Habra
				"WB23",//Haldia
				"WB24",//Haora
				"UP37",//Hathras
				"HA08",//Hissar
				"MH20",//Jalgaon
				"MH21",//Jalna
				"RA13",//Jalore
				"GU19",//Jam Nagar
				"GU06",//Junagarh
				"GU20",//Kalol
				"BI13",//Katihar
				"WB27",//Krishnanagar
				"MH22",//Latur
				"WB09",//Bankura
				"MP15",//Dewas
				"MP13",//Bhind
				"MH23",//Malegaon
				"UP38",//Maunath Bhanjan
				"WB29",//Medinipur
				"UP39",//Mirzapur
				"UP40",//Modi Nagar
				"PU15",//Moga
				"MP18",//Morena
				"GU21",//Morvi
				"BI14",//Munger
				"MP19",//Murwara
				"WB30",//Nabadwip
				"GU22",//Nadiad
				"WB31",//Naihati
				"MH24",//Nashik/ Nasik
				"GU23",//Navsari
				"RA14",//Pali
				"WB32",//Panihati
				"MH25",//Parbhani
				"UP41",//Pilibhit
				"GU24",//Porbandar
				"OR07",//Puri
				"BI15",//Purnia
				"WB33",//Raiganj
				"UP42",//Rampur
				"WB34",//Raniganj
				"MP21",//Ratlam
				"MP22",//Rewa
				"HA10",//Rewari
				"MP23",//Sagar
				"BI16",//Samastipur
				"OR09",//Sambalpur
				"UP43",//Sambhal
				"PU16",//Sangrur
				"MP24",//Satna
				"WB36",//Serampore
				"UP44",//Shahjahanpur
				"MP25",//Shivapuri
				"RA15",//Sikar
				"UP45",//Sitapur
				"HA11",//Sonepat
				"WB38",//South Dum Dum
				"WB39",//Titagarh
				"RA16",//Tonk
				"KA26",//Udipi
				"UP46",//Unnao
				"GU25",//Valsad
				"MH26",//Wardha
				"HA12",//Yamunanagar
				"MH27");//Yavatmal

  /* 
   * @access private groupName
   */

  private $groupName;

  /* 
   * source from which the action is initiated
   * @access private source
   */

  private $source;
  /*
   * Declaring Memeber Varibales
   */
  /*
   * @access private profile object
   */

  private $profileObj;

  /*
   * @access private page id depicting page and channel both
   */

  private $pageId;
  /*
   * @access private adnetwork
   */

  private $adnetwork;

  /*
   * Member functions
   */

  /**
   * Constructor for 
   * @access Public
   * @return Void
   * <p>
   * </p>
   */
  public function __construct($groupName='',$source='',$pageId='',$profileObj='',$adnetwork='')
  {
    $this->groupName = $groupName;

    $this->source = $source;

    $this->pageId = $pageId;

    $this->profileObj = $profileObj;

    $this->adnetwork = $adnetwork;
  }

  /*
   * Method to get all fetch pixel Codes for the current page and group, validate and compute
   * @return complete pixel script code to be embedded in js
   */

	public function getPixelCode()
	{
		if(!$this->groupName)
			return;

		$pixelCodeArr = $this->fetchPixelCodes();
		if(is_array($pixelCodeArr))
		{
		foreach($pixelCodeArr as $k=>$v)
		{
			$valid = $this->validatePixel($v);
			if($valid)
			{
				$pixelCode.=$this->replacePixelVars($v['PIXELCODE'],$v['REPLACEMENT']);
			}
		}
		}
		return $pixelCode;
	}
  /*
   * private Method to get all fetch pixel Codes from db
   * @return array of pixel codes and their conditions
   */

 	private function fetchPixelCodes()
	{
		$pixelCodeStoreObj = new MIS_PIXELCODE;
		return $pixelCodeStoreObj->getPixelCodes($this->groupName,$this->pageId);
	}
  /*
   * private Method to validate a single pixel code basis condtions
   * @return true if pixel can be fired and false if not
   */

	private function validatePixel($pixelRow)
	{

		if($pixelRow['CONDITION']=='')
			return true;

		$validCondition = true;

                $conditionArr = explode("|",$pixelRow['CONDITION']);

		foreach($conditionArr as $k=>$v)
		{
			if($validCondition==false)
				break;
			$validCondition = $this->checkCondition($v);
		}

		return $validCondition;
	}
  /*
   * Method to get all replace variables in a pixel code with actual values
   * @return corrected pixel code
   */

	private function replacePixelVars($pixelcode,$replaceLogic='')
	{
		switch($replaceLogic)
		{
		case "1":
			$pixelcode = str_replace('~$CITY`', $this->profileObj->getDecoratedCity(), $pixelcode);
			$pixelcode = str_replace('~$USERNAME`', $this->profileObj->getUSERNAME(), $pixelcode);
			$pixelcode = str_replace('~$AGE`', $this->profileObj->getAge(), $pixelcode);
			$pixelcode = str_replace('~$GENDER`', $this->profileObj->getDecoratedGender(), $pixelcode);
			$pixelcode = str_replace('~$PROFILEID`', $this->profileObj->getPROFILEID(), $pixelcode);
			$pixelcode = str_replace('~$ADNETWORK1`', $this->adnetwork1, $pixelcode);
			break;
		case "2":
			$rid = sfContext::getInstance()->getRequest()->getParameter("RID");
			$pixelcode = str_replace('~$RID`', $rid, $pixelcode);
			break;
		default:
			break;
		}
		return $pixelcode;
	}

  /*
   * Method to get all check conditions whether the pixel should be fired or not
   * @return true if can be fired else false
   */

	private function checkCondition($condition)
	{
		switch($condition)
		{
		case "AGE_F22_M25":								//VCommission_May10
			$age=$this->profileObj->getAGE();
			$gender=$this->profileObj->getGENDER();
			if(($gender== 'F' && $age>=22)||($gender== 'M' && $age>=25))
				return true;
			break;
		case "CITY":								//VCommission_May10
			$city=$this->profileObj->getCITY_RES();
			if(in_array($city, $this->new_cityarr))
				return true;
			break;
		case "MTONGUE":
                        $mtongueArr=FieldMap::getFieldLabel("allHindiMtongues","",1);
                        //for mtnogues punjabi and marathi
                        $mtongueArr[]="27";
                        $mtongueArr[]="20";

			$age=$this->profileObj->getAGE();
			$gender=$this->profileObj->getGENDER();
                        //if belong to religion hindu and required motherTongues and satisfy conditions of male female age group
                        if($this->profileObj->getRELIGION()=="1" && in_array($this->profileObj->MTONGUE(),$mtongueArr))
                        {
                                if($gender=="M" && $age>=26 && $age<=34)
                                        return true;
                                elseif($gender=="F" && $age>=24 && $age<=32)
                                        return true;
                                else
                                        return false;
                        }
                        else
                                return false;
			break;
		case "NON_SOUTH_MTONGUE":
			$mtongue = $this->profileObj->getMTONGUE();
			if(in_array($mtongue,$this->southMtongue))
				return false;
			return true;
			break;
		}

	}

}
?>
