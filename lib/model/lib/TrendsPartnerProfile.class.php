<?php
class TrendsPartnerProfile
{
	private $table;
        private $PROFILEID;
        private $GENDER;
        private $CHILDREN;
        private $LAGE;
        private $HAGE;
        private $LHEIGHT;
        private $HHEIGHT;
        private $HANDICAPPED;
        private $NHANDICAPPED;
        private $DATE;
        private $ALERTS;
        private $PAGE;
        private $DPP;
        private $CASTE_MTONGUE;
        private $PARTNER_BTYPE;
        private $PARTNER_CASTE;
        private $PARTNER_CITYRES;
        private $PARTNER_COUNTRYRES;
        private $PARTNER_DIET;
        private $PARTNER_DRINK;
        private $PARTNER_ELEVEL_NEW;
        private $PARTNER_INCOME;
        private $PARTNER_MANGLIK;
        private $PARTNER_MSTATUS;
        private $PARTNER_MTONGUE;
        private $PARTNER_NRI_COSMO;
        private $PARTNER_OCC;
        private $PARTNER_RELATION;
        private $PARTNER_RES_STATUS;
        private $PARTNER_SMOKE;
        private $PARTNER_COMP;
	private $PARTNER_RELIGION;
	private $PARTNER_NAKSHATRA;

        public function __construct($table='')
        {
		if($table)
			$this->table=$table;
		else
	                $this->table="twowaymatch.TRENDS";
        }

        public function getPROFILEID()
        {
                return $this->PROFILEID;
        }
	public function setPROFILEID($profileid)
	{
		$this->PROFILEID=$profileid;
	}
        public function getGENDER()
        {
                return $this->GENDER;
        }

        public function setGENDER($gender)
        {
                $this->GENDER=$gender;
        }

        public function getCHILDREN()
        {
                return $this->CHILDREN;
        }
        public function setCHILDREN($child)
        {
                $this->CHILDREN=$child;
        }
        public function getLAGE()
        {
                return $this->LAGE;
        }
        public function setLAGE($lage)
        {
                $this->LAGE=$lage;
        }
        public function getHAGE()
        {
                return $this->HAGE;
        }
        public function setHAGE($hage)
        {
                $this->HAGE=$hage;
        }
        public function getLHEIGHT()
        {
                return $this->LHEIGHT;
        }
        public function setLHEIGHT($lheight)
        {
                $this->LHEIGHT=$lheight;
        }
        public function getHHEIGHT()
        {
                return $this->HHEIGHT;
        }
        public function setHHEIGHT($hheight)
        {
                $this->HHEIGHT=$hheight;
        }
        public function getDATE()
        {
                return $this->DATE;
        }
        public function setDATE($dt)
        {
                $this->DATE=$dt;
        }
        public function getDPP()
        {
                return $this->DPP;
        }
        public function setDPP($dpp)
        {
                $this->DPP=$dpp;
        }
        public function getCASTE_MTONGUE()
        {
                return $this->CASTE_MTONGUE;
        }
        public function setCASTE_MTONGUE($caste_mtongue)
        {
                $this->CASTE_MTONGUE=$caste_mtongue;
        }
        public function getPARTNER_CITYRES()
        {
                return $this->PARTNER_CITYRES;
        }
        public function setPARTNER_CITYRES($city)
        {
                $this->PARTNER_CITYRES=$city;
        }
        public function getPARTNER_COUNTRYRES()
        {
                return $this->PARTNER_COUNTRYRES;
        }
        public function setPARTNER_COUNTRYRES($country)
        {
                $this->PARTNER_COUNTRYRES=$country;
        }
        public function getPARTNER_ELEVEL_NEW()
        {
                return $this->PARTNER_ELEVEL_NEW;
        }
        public function setPARTNER_ELEVEL_NEW($elevelnew)
        {
                $this->PARTNER_ELEVEL_NEW=$elevelnew;
        }
        public function getPARTNER_INCOME()
        {
                return $this->PARTNER_INCOME;
        }
        public function setPARTNER_INCOME($income)
        {
                $this->PARTNER_INCOME=$income;
        }
        public function getPARTNER_CASTE()
        {
		$str_caste=$this->PARTNER_CASTE;
                if(substr($str_caste,-3)==",''")
                         $str_caste=substr($str_caste,0,strlen($str_caste)-3);
                return $str_caste;
        }
        public function setPARTNER_CASTE($caste)
        {
                $this->PARTNER_CASTE=$caste;
        }
        public function getPARTNER_MTONGUE()
        {
                return $this->PARTNER_MTONGUE;
        }
	public function setPARTNER_MTONGUE($mtongue)
	{
		$this->PARTNER_MTONGUE=$mtongue;
	}
        public function getPARTNER_MANGLIK()
        {
                return $this->PARTNER_MANGLIK;
        }
        public function setPARTNER_MANGLIK($manglik)
        {
                $this->PARTNER_MANGLIK=$manglik;
        }
        public function getPARTNER_MSTATUS()
        {
                return $this->PARTNER_MSTATUS;
        }
        public function setPARTNER_MSTATUS($mstatus)
        {
                $this->PARTNER_MSTATUS=$mstatus;
        }
        public function getPARTNER_DIET()
        {
                return $this->PARTNER_DIET;
        }
        public function setPARTNER_DIET($diet)
        {
                $this->PARTNER_DIET=$diet;
        }
        public function getPARTNER_COMP()
        {
                return $this->PARTNER_COMP;
        }
        public function setPARTNER_COMP($comp)
        {
                $this->PARTNER_COMP=$comp;
        }
        public function getPARTNER_BTYPE()
        {
                return $this->PARTNER_BTYPE;
        }
        public function setPARTNER_BTYPE($btype)
        {
                $this->PARTNER_BTYPE=$btype;
        }
        public function getPARTNER_SMOKE()
        {
                return $this->PARTNER_SMOKE;
        }
        public function setPARTNER_SMOKE($smoke)
        {
                $this->PARTNER_SMOKE=$smoke;
        }
        public function getPARTNER_DRINK()
        {
                return $this->PARTNER_DRINK;
        }
        public function setPARTNER_DRINK($drink)
        {
                $this->PARTNER_DRINK=$drink;
        }
        public function getHANDICAPPED()
        {
                return $this->HANDICAPPED;
        }
        public function setHANDICAPPED($handi)
        {
                $this->HANDICAPPED=$handi;
        }
        public function getNHANDICAPPED()
        {
                return $this->NHANDICAPPED;
        }
        public function setNHANDICAPPED($handi)
        {
                $this->NHANDICAPPED=$handi;
        }
	//----------------done---------------------
        public function getPARTNER_NRI_COSMO()
        {
                return $this->PARTNER_NRI_COSMO;
        }
        public function setPARTNER_NRI_COSMO($nri_cosmo)
        {
                $this->PARTNER_NRI_COSMO=$nri_cosmo;
        }
        public function getPARTNER_OCC()
        {
                return $this->PARTNER_OCC;
        }
        public function setPARTNER_OCC($occ)
        {
                $this->PARTNER_OCC=$occ;
        }
        public function getPARTNER_RELATION()
        {
                return $this->PARTNER_RELATION;
        }
        public function setPARTNER_RELATION($rel)
        {
                $this->PARTNER_RELATION=$rel;
        }

        public function getPARTNER_RES_STATUS()
        {
                return $this->PARTNER_RES_STATUS;
        }
        public function setPARTNER_RES_STATUS($rstatus)
        {
                $this->PARTNER_RES_STATUS=$rstatus;
        }
	public function getPARTNER_RELIGION()
	{
		return $this->PARTNER_RELIGION;
	}
	public function setPARTNER_RELIGION($partner_religion)
	{
		return $this->PARTNER_RELIGION = $partner_religion;
	}
	public function getPARTNER_NAKSHATRA()
	{
		return $this->PARTNER_NAKSHATRA;
	}
	public function setPARTNER_NAKSHATRA($partner_nakshatra)
	{
		return $this->PARTNER_NAKSHATRA = $partner_nakshatra;
	}
	public function getIsPartnerProfileUpdated()
	{
		return $this->partnerProfileUpdated;
	}

	//handling not cases
        public function getPARTNER_MSTATUS_IGNORE()
        {
                return $this->PARTNER_MSTATUS_IGNORE;
        }
        public function setPARTNER_MSTATUS_IGNORE($mstatus)
        {
                $this->PARTNER_MSTATUS_IGNORE=$mstatus;
        }
        public function getPARTNER_MANGLIK_IGNORE()
        {
                return $this->PARTNER_MANGLIK_IGNORE;
        }
        public function setPARTNER_MANGLIK_IGNORE($manglik)
        {
                $this->PARTNER_MANGLIK_IGNORE=$manglik;
        }
        public function getPARTNER_COUNTRY_RES_IGNORE()
        {
                return $this->PARTNER_COUNTRY_RES_IGNORE;
        }
        public function setPARTNER_COUNTRY_RES_IGNORE($country_res)
        {
                $this->PARTNER_COUNTRY_RES_IGNORE=$country_res;
        }
	//handling not cases

	public function setPartnerDetails($profileId)
	{
		if($this->table == "matchalerts.TRENDS")
		{
			$mtObj = new MATCHALERTS_TRENDS("newjs_slave");
		}
		else
		{
			$mtObj = new TWOWAYMATCH_TRENDS;
		}
	
		$myrow = $mtObj->getData($profileId);	

		if($myrow)
		{
                        $forward_filter["PARTNER_CASTE"]=$myrow["W_CASTE"];
			$forward_filter["PARTNER_MTONGUE"]=$myrow["W_MTONGUE"];
			$forward_filter["AGE"]=$myrow["W_AGE"];
			$forward_filter["HEIGHT"]=$myrow["W_HEIGHT"];
			$forward_filter["PARTNER_INCOME"]=$myrow["W_INCOME"];
			$forward_filter["PARTNER_MSTATUS"]=$myrow["W_MSTATUS"];
			$forward_filter["PARTNER_MANGLIK"]=$myrow["W_MANGLIK"];
			$forward_filter["PARTNER_COUNTRYRES"]=$myrow["W_NRI"];

			//--------------only if are in top 2-----------------------
			$forward_filter["PARTNER_ELEVEL_NEW"]=$myrow["W_EDUCATION"];
			$forward_filter["PARTNER_OCC"]=$myrow["W_OCCUPATION"];
			$forward_filter["PARTNER_CITYRES"]=$myrow["W_CITY"];
			//--------------only if are in top 2-----------------------

			//print_r($forward_filter);
			arsort($forward_filter);
			$l=0;
			foreach($forward_filter as $k=>$v)
			{
				//if($ll++>2)
					//break;
				$l=$l+1;
				if($k=='AGE' || $k=='HEIGHT')
				{
					$temp=$this->getValue($myrow,$k);
					if($k=='AGE')
					{
						$this->LAGE=$temp[1];
						$this->HAGE=$temp[0];
					}
					else
					{
						$this->LHEIGHT=$temp[1];
						$this->HHEIGHT=$temp[0];
					}
				}
				elseif($k=='PARTNER_MSTATUS')
				{
					if($myrow["MSTATUS_M_P"] && !$myrow["MSTATUS_N_P"])
						$this->PARTNER_MSTATUS_IGNORE="'N'";
					if(!$myrow["MSTATUS_M_P"] && $myrow["MSTATUS_N_P"])
						$this->$k="'N'";
				}
				elseif($k=='PARTNER_MANGLIK')
				{
					if($myrow["MANGLIK_M_P"] && !$myrow["MANGLIK_N_P"])
						$this->$k="'M'";
					if(!$myrow["MANGLIK_M_P"] && $myrow["MANGLIK_N_P"])
						$this->PARTNER_MANGLIK_IGNORE="'M'";
				}
				elseif($k=='PARTNER_COUNTRYRES')
				{
                                	if($myrow["NRI_N_P"]>=90)
					{
						$this->$k="'51'";
					}
					elseif($myrow["NRI_M_P"]>=90)
					{
						$this->PARTNER_COUNTRY_RES_IGNORE="51";
					}
					/*
                                	if($myrow["NRI_N_P"] && !$myrow["NRI_M_P"])
						$this->$k="'51'";
                                	if($myrow["NRI_M_P"] && !$myrow["NRI_N_P"])
						$this->PARTNER_COUNTRY_RES_IGNORE="51";
					*/
				}
				else
				{
					if($l<=2 || !in_array($k,array('PARTNER_ELEVEL_NEW','PARTNER_OCC','PARTNER_CITYRES')))
						$this->$k=$this->getValue($myrow,$k);
				}
			}	

			if($myrow["GENDER"]=='M')
				$this->GENDER='F';
			else
				$this->GENDER='M';
			$this->PROFILEID=$myrow["PROFILEID"];
		}
	}

	public function getValue($myrow,$k)
	{
		if($k=='PARTNER_CASTE')
			$value=$myrow["CASTE_VALUE_PERCENTILE"];
		elseif($k=='PARTNER_MTONGUE')
			$value=$myrow['MTONGUE_VALUE_PERCENTILE'];
		elseif($k=='AGE')
		{
			$value=$myrow['AGE_VALUE_PERCENTILE'];
	                $rangeCase=1;
		}
		elseif($k=='HEIGHT')
		{
			$value=$myrow['HEIGHT_VALUE_PERCENTILE'];
	                $rangeCase=1;
		}
		elseif($k=='PARTNER_INCOME')
			$value=$myrow['INCOME_VALUE_PERCENTILE'];
		elseif($k=='PARTNER_ELEVEL_NEW')
			$value=$myrow['EDUCATION_VALUE_PERCENTILE'];
		elseif($k=='PARTNER_OCC')
			$value=$myrow['OCCUPATION_VALUE_PERCENTILE'];
		elseif($k=='PARTNER_CITYRES')
			$value=$myrow['CITY_VALUE_PERCENTILE'];
		/*
		elseif($k=='PARTNER_MSTATUS')
		{
		}
		elseif($k=='PARTNER_MANGLIK')
		{
		}
		elseif($k=='PARTNER_COUNTRYRES')
		{
			$value=$myrow[''];
		}
		*/

		/*
		elseif($k=='')
			$value=$myrow[''];
		*/
		$forward_temp=explode("|",$value);
	        $n=count($forward_temp);

        	for($lav=0;$lav<$n;$lav++)
	        {
        	        $tempval=$forward_temp[$lav];
                	if($tempval)
	                {
        	                $temparr=explode("#",$tempval);
	                        if($temparr[1]>5)//2 is cut-off percentage of individual values.
        	                {
                	                $value=$temparr[0];
                        	        $forward_temp2[]=$value;
	                        }
        	        }
	        }
		if($forward_temp2)
		{
	                if($rangeCase)
        	        {
				//PHASE2
                	        //$str[0]=max($forward_temp2)+1;
                        	//$str[1]=min($forward_temp2)-1;
                	        $str[0]=max($forward_temp2);
                        	$str[1]=min($forward_temp2);
				//PHASE2
        	        }
			else
				$str="'".implode("','",$forward_temp2)."'";
		}
		return $str;
	}

}
?>
