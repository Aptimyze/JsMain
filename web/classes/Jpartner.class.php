<?php

/** 
* @author Lavesh Rawat 
* @copyright Copyright 2008, Infoedge India Ltd.
*/
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
class Jpartner
{
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
        private $DPP_ID;
        private $CREATED_BY;
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
	private $LINCOME;
	private $HINCOME;
	private $LINCOME_DOL;
	private $HINCOME_DOL;
    private $STATE;
    private $CITY_INDIA;
    private $OCCUPATION_GROUPING;

        public function __construct($table='')
        {
		//temp
		if($table)
			$this->table=$table;
		else
	                $this->table="newjs.JPARTNER";
		//temp
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
        /* 
	public function getALERTS()
        {
        }
	*/
        public function getDPP()
        {
                return $this->DPP;
        }
        public function setDPP($dpp)
        {
                $this->DPP=$dpp;
        }
        public function getCREATED_BY()
        {
                return $this->CREATED_BY;
        }
        public function setCREATED_BY($dpp)
        {
                $this->CREATED_BY=$dpp;
        }
        public function getDPP_ID()
        {
                return $this->DPP_ID;
        }
        public function setDPP_ID($dpp)
        {
                $this->DPP_ID=$dpp;
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
        public function getCITY_INDIA()
        {
                return $this->CITY_INDIA;
        }
        public function setCITY_INDIA($city_india)
        {
                $this->CITY_INDIA=$city_india;
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
	//new
        public function getLINCOME()
        {
                return $this->LINCOME;
        }
        public function setLINCOME($lincome)
        {
                $this->LINCOME=$lincome;
        }

        public function getHINCOME()
        {
                return $this->HINCOME;
        }
        public function setHINCOME($hincome)
        {
                $this->HINCOME=$hincome;
        }

        public function getLINCOME_DOL()
        {
                return $this->LINCOME_DOL;
        }
        public function setLINCOME_DOL($lincome_dol)
        {
                $this->LINCOME_DOL=$lincome_dol;
        }

        public function getHINCOME_DOL()
        {
                return $this->HINCOME_DOL;
        }
        public function setHINCOME_DOL($hincome_dol)
        {
                $this->HINCOME_DOL=$hincome_dol;
        }
	//new
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
        public function getSTATE()
        {
                return $this->STATE;
        }
        public function setSTATE($state)
        {
                $this->STATE=$state;
        }
        public function getOCCUPATION_GROUPING()
        {
            return $this->OCCUPATION_GROUPING;
        }
        public function setOCCUPATION_GROUPING($occGrouping)
        {
            $this->OCCUPATION_GROUPING = $occGrouping;
        }
		public function getJpartnerArray()
		{
			$paramArray["GENDER"]=$this->GENDER;
			$paramArray["CASTE_MTONGUE"]=$this->CASTE_MTONGUE;
			$paramArray["HANDICAPPED"]=$this->HANDICAPPED;
			$paramArray["PARTNER_BTYPE"]=$this->PARTNER_BTYPE;
			$paramArray["PARTNER_CASTE"]=$this->PARTNER_CASTE;
			$paramArray["PARTNER_DIET"]=$this->PARTNER_DIET;
			$paramArray["PARTNER_DRINK"]=$this->PARTNER_DRINK;
			$paramArray["PARTNER_ELEVEL_NEW"]=$this->PARTNER_ELEVEL_NEW;
			$paramArray["PARTNER_INCOME"]=$this->PARTNER_INCOME;
			$paramArray["PARTNER_MANGLIK"]=$this->PARTNER_MANGLIK;
			$paramArray["PARTNER_MTONGUE"]=$this->PARTNER_MTONGUE;
			$paramArray["PARTNER_NRI_COSMO"]=$this->PARTNER_NRI_COSMO;
			$paramArray["PARTNER_OCC"]=$this->PARTNER_OCC;
			$paramArray["PARTNER_RELATION"]=$this->PARTNER_RELATION;
			$paramArray["PARTNER_RES_STATUS"]=$this->PARTNER_RES_STATUS;
			$paramArray["PARTNER_SMOKE"]=$this->PARTNER_SMOKE;
			$paramArray["PARTNER_COMP"]=$this->PARTNER_COMP;
			$paramArray["PARTNER_RELIGION"]=$this->PARTNER_RELIGION;
			$paramArray["PARTNER_NAKSHATRA"]=$this->PARTNER_NAKSHATRA;
			$paramArray["NHANDICAPPED"]=$this->NHANDICAPPED;
			$paramArray["PROFILEID"]=$this->PROFILEID;
			$paramArray["CREATED_BY"]="ONLINE";
			$paramArray["CHILDREN"]=$this->CHILDREN;
			$paramArray["LAGE"]=$this->LAGE;
			$paramArray["HAGE"]=$this->HAGE;
			$paramArray["LHEIGHT"]=$this->LHEIGHT;
			$paramArray["HHEIGHT"]=$this->HHEIGHT;
			$paramArray["PARTNER_CITYRES"]=$this->PARTNER_CITYRES;
                       // $paramArray["CITY_INDIA"]=$this->CITY_INDIA;
			$paramArray["PARTNER_COUNTRYRES"]=$this->PARTNER_COUNTRYRES;
			$paramArray["PARTNER_MSTATUS"]=$this->PARTNER_MSTATUS;
			$paramArray["LINCOME"]=$this->LINCOME;
			$paramArray["HINCOME"]=$this->HINCOME;
			$paramArray["LINCOME_DOL"]=$this->LINCOME_DOL;
			$paramArray["HINCOME_DOL"]=$this->HINCOME_DOL;
            $paramArray["STATE"]=$this->STATE;
			$paramArray["OCCUPATION_GROUPING"]=$this->OCCUPATION_GROUPING;
            return $paramArray;
		}
		public function setJpartnerUsingArray($paramArray)
		{
			if(is_array($paramArray))
				foreach($paramArray as $name=>$value)
					$this->$name=$value;
		}
			//handling not cases

	/**
	* This function is used to calculate no. of partner profile registered for some given criteria
	* @param mysql-connection $myDb connection associated with logged-in profile ( or profile of which are doing calculation)
	* @param mysql-object $mysqlObj object of Mysql class
	* @param string $date optional field , a criteria to search 
	* @param char $dpp optional field , a criteria to search
	* @param int $profileid is unique id of a user
	* @return int count of partner profile registered for some given criteria
	*/
        public function calculateCountInPartnerProfile($myDb,$mysqlObj="",$date="",$dpp="",$profileid="")
        {
                if($this->PartnerProfileExist=='N')
                        return 0;
                elseif($myDb)
                {
			if(!$mysqlObj)
				$mysqlObj=new Mysql;

			if($profileid)
				$where[]=" PROFILEID='$profileid' ";

                        if($date)
				$where[]=" DATE='$date' ";	
			if($dpp)
				$where[]=" DPP='$dpp' ";
			

			if(is_array($where))
				$wherestr=" WHERE".implode(" AND ",$where);

			$sql="SELECT COUNT(*) AS CNT FROM $this->table".$wherestr;
			$result = $mysqlObj->executeQuery($sql,$myDb);
			$myrow =$mysqlObj->fetchAssoc($result);
			if($myrow["CNT"]>0)
				return $myrow["CNT"];
			else
				return 0;
                }
        }
	/**
	* This function is used to find if partner profile of a user exists or not.
	* @param mysql-connection $myDb connection associated with logged-in profile ( or profile of which are doing calculation).
	* @param mysql-object $mysqlObj object of Mysql class.
	* @param int $profileid is unique id of a user.
	* @return int 1-partnerprofile exist , 0 not exists.
	*/
	public function isPartnerProfileExist($myDb,$mysqlObj,$profileid="")
	{
		if($this->PartnerProfileExist=='Y')
			return 1;
		elseif($this->PartnerProfileExist=='N')
			return 0;
		if(!$profileid)
			$profileid=$this->PROFILEID;
		if($myDb && $profileid)
		{
			$sql="SELECT COUNT(*) AS CNT FROM $this->table WHERE PROFILEID=$profileid";		
			$result = $mysqlObj->executeQuery($sql,$myDb) ;
			$myrow =$mysqlObj->fetchAssoc($result);
			if($myrow["CNT"]>0)
				return 1;
			else
				return 0;
		}
	}
        public function setCasteMapping($own_caste,$own_mtongue,$myDb,$mysqlObj,$own_religion="")
        {
                if($own_caste)
                {
			$default_casteArr[]=$own_caste."-".$own_mtongue;
                        $sql="SELECT REL_CASTE,COMMUNITY  FROM newjs.CASTE_COMMUNITY WHERE PARENT_CASTE=$own_caste";
                        $result = $mysqlObj->executeQuery($sql,$myDb) ;
                        while($myrow=$mysqlObj->fetchAssoc($result))
                        {
                                $default_casteArr[]=$myrow['REL_CASTE']."-".$myrow["COMMUNITY"];
                        }
                        $default_caste_str=implode("#",$default_casteArr);
                        $this->CASTE_MTONGUE=$default_caste_str;
                }
		if($own_religion)
			$this->PARTNER_RELIGION="'$own_religion'";
        }

	/**
	* This function is used to set specified partner profile info. of a profile.
	* @param int $profileid is unique id of a user.
	* @param mysql-connection $myDb connection associated with logged-in profile ( or profile of which are doing calculation).
	* @param mysql-object $mysqlObj object of Mysql class.
	* @param string parameter information we want to fetch
	*/
	public function setPartnerDetails($profileid,$myDb,$mysqlObj,$parameter="*",$WhereCondition="")
	{
		if($profileid)
		{
			$this->profileid=$profileid;
			$this->myDb=$myDb;
		}	
		$sql="SELECT SQL_CACHE $parameter FROM $this->table WHERE PROFILEID=$profileid $WhereCondition";
		$result = $mysqlObj->executeQuery($sql,$myDb) ;
		$myrow =$mysqlObj->fetchAssoc($result);

		if($myrow)
		{
			foreach ($myrow as $key => $value)
			{
				$this->$key=$value;
				$flag=1;
			}
		}
		if($flag)
			$this->PartnerProfileExist='Y';
		else
			$this->PartnerProfileExist='N';
	}

	/**
	* This function is update/insert the partner profile info of a user.
	* @param mysql-connection $myDb connection associated with logged-in profile ( or profile of which are doing calculation).
	* @param mysql-object $mysqlObj object of Mysql class.
	* @param string specialcase If we want to update limited info we pass limted info here (eg.$specialcase="LAGE=18,HAGE=25")
	*/
	public function updatePartnerDetails($myDb,$mysqlObj,$specialcase="")
	{
		global $data;
		//@use this only if neccesary.
		if($this->DPP=='')
			$this->DPP='O';
		//tell memcache to flush all records related to jpartner.
		$mem_key=$this->PROFILEID."topsearchband";

		//since this file can be called 4m anywhere.
		if(function_exists("memcache_call"))
			memcache_call($mem_key,"");
	
		if($specialcase)
		{
                        if($this->PARTNER_CITYRES!="" && strpos($this->PARTNER_CITYRES,"'") === FALSE) {
                            $http_msg=print_r($_SERVER,true);
                            mail("ankitshukla125@gmail.com,lavesh.rawat@gmail.com","City without quotes","$this->PROFILEID,DPP :$this->DPP:$http_msg");
                        }
			if($data["GENDER"]==$this->GENDER)
			{
				$http_msg=print_r($_SERVER,true);
				//mail("lavesh.rawat@jeevansathi.com,neha.verma@jeevansathi.com,nehaverma.dce@gmail.com,lavesh.rawat@gmail.com","Gender Same:(SC)","$this->PROFILEID,DPP :$this->DPP:$http_msg");
			}
			if(!$this->isPartnerProfileExist($myDb,$mysqlObj))
			{
				if($this->GENDER=='')
                                {
					$http_msg=print_r($_SERVER,true);
                                        //mail("lavesh.rawat@jeevansathi.com,neha.verma@jeevansathi.com,nehaverma.dce@gmail.com,lavesh.rawat@gmail.com","Gender blank in Jpartner from Insert(SC)","Insert into Jpartner(SC) for $this->PROFILEID,DPP :$this->DPP:$http_msg");
                                }

				$sql1="INSERT INTO $this->table (PROFILEID,GENDER) VALUES ('$this->PROFILEID','$this->GENDER')";
	                        $mysqlObj->executeQuery($sql1,$myDb);
			}
			$sql="UPDATE $this->table SET $specialcase WHERE PROFILEID=$this->PROFILEID";
			$mysqlObj->executeQuery($sql,$myDb);
		}
		else
		{
                        if($this->PARTNER_CITYRES!="" && strpos($this->PARTNER_CITYRES,"'") === FALSE) {
                           $http_msg=print_r($_SERVER,true);
                           mail("ankitshukla125@gmail.com,lavesh.rawat@gmail.com","City without quotes","$this->PROFILEID,DPP :$this->DPP:$http_msg");
                        }
			if(!$this->isPartnerProfileExist($myDb,$mysqlObj))
			{
				if($this->GENDER=='')
				{
					$http_msg=print_r($_SERVER,true);
					mail("lavesh.rawat@jeevansathi.com,neha.verma@jeevansathi.com,nehaverma.dce@gmail.com,lavesh.rawat@gmail.com","Gender blank in Jpartner from Insert","Insert into Jpartner for $this->PROFILEID,DPP :$this->DPP:$http_msg");
				}
				if($data["GENDER"]==$this->GENDER)
				{
					$http_msg=print_r($_SERVER,true);
					mail("lavesh.rawat@jeevansathi.com,neha.verma@jeevansathi.com,nehaverma.dce@gmail.com,lavesh.rawat@gmail.com","Gender Same in Jpartner from Insert","$this->PROFILEID,DPP :$this->DPP:$http_msg");
				}
				$sql="INSERT INTO $this->table (PROFILEID,GENDER,CHILDREN,LAGE,HAGE,LHEIGHT,HHEIGHT,HANDICAPPED,NHANDICAPPED,DPP,CASTE_MTONGUE,PARTNER_BTYPE,PARTNER_CASTE,PARTNER_CITYRES,PARTNER_COUNTRYRES,PARTNER_DIET,PARTNER_DRINK,PARTNER_ELEVEL_NEW,PARTNER_INCOME,PARTNER_MANGLIK,PARTNER_MSTATUS,PARTNER_MTONGUE,PARTNER_NRI_COSMO,PARTNER_OCC,PARTNER_RELATION,PARTNER_RES_STATUS,PARTNER_SMOKE,PARTNER_COMP,PARTNER_RELIGION,PARTNER_NAKSHATRA,DATE,LINCOME,HINCOME,LINCOME_DOL,HINCOME_DOL,STATE,OCCUPATION_GROUPING) VALUES('$this->PROFILEID','$this->GENDER','$this->CHILDREN','$this->LAGE' ,'$this->HAGE' , '$this->LHEIGHT' , '$this->HHEIGHT' , \"$this->HANDICAPPED\" , \"$this->NHANDICAPPED\", '$this->DPP' , \"$this->CASTE_MTONGUE\" ,\"$this->PARTNER_BTYPE\" ,\"$this->PARTNER_CASTE\" ,\"$this->PARTNER_CITYRES\" ,\"$this->PARTNER_COUNTRYRES\" , \"$this->PARTNER_DIET\",\"$this->PARTNER_DRINK\" ,\"$this->PARTNER_ELEVEL_NEW\" , \"$this->PARTNER_INCOME\" , \"$this->PARTNER_MANGLIK\",\"$this->PARTNER_MSTATUS\" ,\"$this->PARTNER_MTONGUE\" , \"$this->PARTNER_NRI_COSMO\",\"$this->PARTNER_OCC\" ,\"$this->PARTNER_RELATION\",\"$this->PARTNER_RES_STATUS\",\"$this->PARTNER_SMOKE\",\"$this->PARTNER_COMP\",\"$this->PARTNER_RELIGION\",\"$this->PARTNER_NAKSHATRA\",now(),\"$this->LINCOME\",\"$this->HINCOME\",\"$this->LINCOME_DOL\",\"$this->HINCOME_DOL\",\"$this->STATE\",\"$this->OCCUPATION_GROUPING\")";	
				$mysqlObj->executeQuery($sql,$myDb);
				$this->partnerProfileUpdated=1;
			}
			else	
			{
				if($this->GENDER=='')
				{
					$http_msg=print_r($_SERVER,true);
					mail("lavesh.rawat@jeevansathi.com,neha.verma@jeevansathi.com,nehaverma.dce@gmail.com,lavesh.rawat@gmail.com","Gender blank in Jpartner from Update","Update Jpartner for $this->PROFILEID,DPP :$this->DPP:$http_msg");
				}
				if($data["GENDER"]==$this->GENDER)
				{
					$http_msg=print_r($_SERVER,true);
					mail("lavesh.rawat@jeevansathi.com,neha.verma@jeevansathi.com,nehaverma.dce@gmail.com,lavesh.rawat@gmail.com","Gender Same in Jpartner from Update","$this->PROFILEID,DPP :$this->DPP:$http_msg");
				}                
				$sql="UPDATE $this->table SET GENDER='$this->GENDER' , CHILDREN=\"$this->CHILDREN\" , LAGE='$this->LAGE' , HAGE='$this->HAGE' , LHEIGHT='$this->LHEIGHT' , HHEIGHT='$this->HHEIGHT' , HANDICAPPED=\"$this->HANDICAPPED\" , NHANDICAPPED=\"$this->NHANDICAPPED\", DPP='$this->DPP' , CASTE_MTONGUE=\"$this->CASTE_MTONGUE\" , PARTNER_BTYPE=\"$this->PARTNER_BTYPE\" , PARTNER_CASTE=\"$this->PARTNER_CASTE\" , PARTNER_CITYRES=\"$this->PARTNER_CITYRES\" , PARTNER_COUNTRYRES=\"$this->PARTNER_COUNTRYRES\" , PARTNER_DIET=\"$this->PARTNER_DIET\" , PARTNER_DRINK=\"$this->PARTNER_DRINK\" ,PARTNER_ELEVEL_NEW=\"$this->PARTNER_ELEVEL_NEW\" , PARTNER_INCOME=\"$this->PARTNER_INCOME\" , PARTNER_MANGLIK=\"$this->PARTNER_MANGLIK\" , PARTNER_MSTATUS=\"$this->PARTNER_MSTATUS\" , PARTNER_MTONGUE=\"$this->PARTNER_MTONGUE\" , PARTNER_NRI_COSMO=\"$this->PARTNER_NRI_COSMO\", PARTNER_OCC=\"$this->PARTNER_OCC\" , PARTNER_RELATION=\"$this->PARTNER_RELATION\",PARTNER_RES_STATUS=\"$this->PARTNER_RES_STATUS\",PARTNER_SMOKE=\"$this->PARTNER_SMOKE\",PARTNER_COMP=\"$this->PARTNER_COMP\", PARTNER_RELIGION=\"$this->PARTNER_RELIGION\", PARTNER_NAKSHATRA=\"$this->PARTNER_NAKSHATRA\",LINCOME='$this->LINCOME',HINCOME='$this->HINCOME',LINCOME_DOL='$this->LINCOME_DOL',HINCOME_dOL='$this->HINCOME_DOL' , STATE=\"$this->STATE\", OCCUPATION_GROUPING=\"$this->OCCUPATION_GROUPING\" WHERE PROFILEID=$this->PROFILEID";
                $mysqlObj->executeQuery($sql,$myDb);
	
				if($mysqlObj->affectedRows())
				{
					$sql="UPDATE $this->table set DATE=now() WHERE PROFILEID=$this->PROFILEID";	
					$mysqlObj->executeQuery($sql,$myDb);
					$this->partnerProfileUpdated=1;
				}
				else
					$this->partnerProfileUpdated=0;
			}	
		}
		$db=connect_db();
	}

	/**
	* This function is delete the partner profile info of a user.
	* @param mysql-connection $myDb connection associated with logged-in profile ( or profile of which are doing calculation).
	* @param mysql-object $mysqlObj object of Mysql class.
	**/
	public function deletePartnerProfile($myDb,$mysqlObj)
	{
		$sql="DELETE FROM $this->table WHERE PROFILEID=$this->PROFILEID";
		$mysqlObj->executeQuery($sql,$myDb);
		$db=connect_db();
		$sql="REPLACE INTO newjs.SWAP_JPARTNER (PROFILEID) VALUES ('$this->PROFILEID')";
		$mysqlObj->executeQuery($sql,$db) ;
	}

	/**
	* This function is log what partner info has been changed if a crm user update partner info.
	* @param int $profileid is unique id of a user
	* @param object jpartnerObj  object of Jpartner class.
	* @param mysql-object $mysqlObj object of Mysql class.
	* @param mysql-connection $myDb connection associated with logged-in profile ( or profile of which are doing calculation).
	* @param $COMPANY always JS(jeevansathi)
	**/
	public function desired_partner_change_log($profileid,$jpartnerObj,$mysqlObj,$myDb,$COMPANY="")
	{
		$db=connect_db();
		$mod="";

	        if(!$COMPANY)
        	        $COMPANY='JS';

		$sql_p = "SELECT * FROM $this->table WHERE PROFILEID='$profileid'";
		$result = $mysqlObj->executeQuery($sql_p,$myDb) ;
		$myrow =$mysqlObj->fetchAssoc($result);
		{
			$gender=$myrow['GENDER'];
			$children=$myrow['CHILDREN'];
			$lage=$myrow['LAGE'];
			$hage=$myrow['HAGE'];
			$lheight=$myrow['LHEIGHT'];
			$hheight=$myrow['HHEIGHT'];
			$handicapped=$myrow['HANDICAPPED'];
			$nhandicapped=$myrow['NHANDICAPPED'];
			$caste_mtongue=$myrow['CASTE_MTONGUE'];
			$partner_btype=$myrow['PARTNER_BTYPE'];
			$partner_caste=$myrow['PARTNER_CASTE'];
			$partner_cityres=$myrow['PARTNER_CITYRES'];
			$partner_countryres=$myrow['PARTNER_COUNTRYRES'];
			$partner_diet=$myrow['PARTNER_DIET'];
			$partner_drink=$myrow['PARTNER_DRINK'];
			$partner_elevel_new=$myrow['PARTNER_ELEVEL_NEW'];  
			$partner_income=$myrow['PARTNER_INCOME'];
			$partner_manglik=$myrow['PARTNER_MANGLIK'];
			$partner_mstatus=$myrow['PARTNER_MSTATUS'];
			$partner_mtongue=$myrow['PARTNER_MTONGUE'];
			$partner_nri_cosmo=$myrow['PARTNER_NRI_COSMO'];  
			$partner_occ=$myrow['PARTNER_OCC'];
			$partner_relation=$myrow['PARTNER_RELATION'];  
			$partner_res_status=$myrow['PARTNER_RES_STATUS']; 
			$partner_smoke=$myrow['PARTNER_SMOKE'];
			$partner_comp=$myrow['PARTNER_COMP'];
			$partner_religion = $myrow['PARTNER_RELIGION'];
			$partner_nakshatra = $myrow['PARTNER_NAKSHATRA'];
			$partner_lincome = $myrow['LINCOME '];
			$partner_hincome = $myrow['HINCOME'];
			$partner_lincome_dol = $myrow['LINCOME_DOL'];
			$partner_hincome_dol = $myrow['HINCOME_DOL'];
            $partner_state = $myrow['STATE'];
           // $city_india = $myrow['CITY_INDIA'];
            $occupation_grouping = $myrow['OCCUPATION_GROUPING']; // CHECK

		}
		if($gender!=$this->GENDER)
		{
			$comments.="<br><b>"." GENDER : "."</b><br>"."Changed From "."<b>".$gender."</b><br>"." To "."<b>".$this->GENDER."</b>";
		}
		if($children!=$this->CHILDREN)
		{
			$comments.="<br><b>"." CHILDREN : "."</b><br>"."Changed From "."<b>".$children."</b><br>"." To "."<b>".$this->CHILDREN."</b>";
		}
		if($lage!=$this->LAGE)
		{
			$comments.="<br><b>"." LAGE : "."</b><br>"."Changed From "."<b>".$lage."</b><br>"." To "."<b>".$this->LAGE."</b>";
		}
		 if($hage!=$this->HAGE)
		{
			$comments.="<br><b>"." HAGE : "."</b><br>"."Changed From "."<b>".$hage."</b><br>"." To "."<b>".$this->HAGE."</b>";
		}
		if($lheight!=$this->LHEIGHT)
		{
			$comments.="<br><b>"." LHEIGHT : "."</b><br>"."Changed From "."<b>".$lheight."</b><br>"." To "."<b>".$this->LHEIGHT."</b>";
		}
		if($hheight!=$this->HHEIGHT)
		{
			$comments.="<br><b>"." HHEIGHT : "."</b><br>"."Changed From "."<b>".$hheight."</b><br>"." To "."<b>".$this->HHEIGHT."</b>";
		}
		 if($handicapped!=$this->HANDICAPPED)
		{
			   $comments.="<br><b>"." HANDICAPPED : "."</b><br>"."Changed From "."<b>".$handicapped."</b><br>"." To "."<b>".$this->HANDICAPPED."</b>";
		}
		if($nhandicapped!=$this->NHANDICAPPED)
		{
			   $comments.="<br><b>"." NATURE_HANDICAPPED : "."</b><br>"."Changed From "."<b>".$handicapped."</b><br>"." To "."<b>".$this->NHANDICAPPED."</b>";
		}
		if($caste_mtongue!=$this->CASTE_MTONGUE)
		{
			$comments.="<br><b>"." CASTE_MTONGUE : "."</b><br>"."Changed From "."<b>".$caste_mtongue."</b><br>"." To "."<b>".$this->CASTE_MTONGUE."</b>";
		}
		if($partner_btype!=$this->PARTNER_BTYPE)
		{
			$comments.="<br><b>"." PARTNER_BTYPE : "."</b><br>"."Changed From "."<b>".$partner_btype."</b><br>"." To "."<b>".$this->PARTNER_BTYPE."</b>";
		}
		 if($partner_caste!=$this->PARTNER_CASTE)
		{
			   $comments.="<br><b>"." PARTNER_CASTE : "."</b><br>"."Changed From "."<b>".$partner_caste."</b><br>"." To "."<b>".$this->PARTNER_CASTE."</b>";
		}
		if($partner_cityres!=$this->PARTNER_CITYRES)
		{
			$comments.="<br><b>"." PARTNER_CITYRES : "."</b><br>"."Changed From "."<b>".$partner_cityres."</b><br>"." To "."<b>".$this->PARTNER_CITYRES."</b>";
		}
		if($partner_countryres!=$this->PARTNER_COUNTRYRES)
		{
			$comments.="<br><b>"." PARTNER_COUNTRYRES : "."</b><br>"."Changed From "."<b>".$partner_countryres."</b><br>"." To "."<b>".$this->PARTNER_COUNTRYRES."</b>";
		}
		if($partner_diet!=$this->PARTNER_DIET)
		{
			$comments.="<br><b>"." PARTNER_DIET : "."</b><br>"."Changed From "."<b>".$partner_diet."</b><br>"." To "."<b>".$this->PARTNER_DIET."</b>";
		}	
		if($partner_drink!=$this->PARTNER_DRINK)
		{
			$comments.="<br><b>"." PARTNER_DRINK : "."</b><br>"."Changed From "."<b>".$partner_drink."</b><br>"." To "."<b>".$this->PARTNER_DRINK."</b>";
		}
		if($partner_elevel_new!=$this->PARTNER_ELEVEL_NEW)
		{
			$comments.="<br><b>"." PARTNER_ELEVEL_NEW : "."</b><br>"."Changed From "."<b>".$partner_elevel_new."</b><br>"." To "."<b>".$this->PARTNER_ELEVEL_NEW."</b>";
		}
		if($partner_income!=$this->PARTNER_INCOME)
		{
			$comments.="<br><b>"." PARTNER_INCOME : "."</b><br>"."Changed From "."<b>".$partner_income."</b><br>"." To "."<b>".$this->PARTNER_INCOME."</b>";
		}
		if($partner_manglik!=$this->PARTNER_MANGLIK)
		{
			$comments.="<br><b>"." PARTNER_MANGLIK : "."</b><br>"."Changed From "."<b>".$partner_manglik."</b><br>"." To "."<b>".$this->PARTNER_MANGLIK."</b>";
		}
		if($partner_mstatus!=$this->PARTNER_MSTATUS)
		{
			$comments.="<br><b>"." PARTNER_MSTATUS : "."</b><br>"."Changed From "."<b>".$partner_mstatus."</b><br>"." To "."<b>".$this->PARTNER_MSTATUS."</b>";
		}
		 if($partner_mtongue!=$this->PARTNER_MTONGUE)
		{
			$comments.="<br><b>"." PARTNER_MTONGUE : "."</b><br>"."Changed From "."<b>".$partner_mtongue."</b><br>"." To "."<b>".$this->PARTNER_MTONGUE."</b>";
		}
		if($partner_nri_cosmo!=$this->PARTNER_NRI_COSMO)
		{
			$comments.="<br><b>"." PARTNER_NRI_COSMO : "."</b><br>"."Changed From "."<b>".$partner_nri_cosmo."</b><br>"." To "."<b>".$this->PARTNER_NRI_COSMO."</b>";
		}
		if($partner_occ!=$this->PARTNER_OCC)
		{
			$comments.="<br><b>"." PARTNER_OCC : "."</b><br>"."Changed From "."<b>".$partner_occ."</b><br>"." To "."<b>".$this->PARTNER_OCC."</b>";
		}
		if($partner_relation!=$this->PARTNER_RELATION)
		{
			$comments.="<br><b>"." PARTNER_RELATION : "."</b><br>"."Changed From "."<b>".$partner_relation."</b><br>"." To "."<b>".$this->PARTNER_RELATION."</b>";
		}
		if($partner_res_status!=$this->PARTNER_RES_STATUS)
		{
			$comments.="<br><b>"." PARTNER_RES_STATUS : "."</b><br>"."Changed From "."<b>".$partner_res_status."</b><br>"." To "."<b>".$this->PARTNER_RES_STATUS."</b>";
		}
		if($partner_smoke!=$this->PARTNER_SMOKE)
		{
			$comments.="<br><b>"." PARTNER_SMOKE : "."</b><br>"."Changed From "."<b>".$partner_smoke."</b><br>"." To "."<b>".$this->PARTNER_SMOKE."</b>";
		}
		if($partner_comp!=$this->PARTNER_COMP)
		{
			$comments.="<br><b>"." PARTNER_COMP : "."</b><br>"."Changed From "."<b>".$partner_comp."</b><br>"." To "."<b>".$this->PARTNER_COMP."</b>";
		}
		if($partner_religion!=$this->PARTNER_RELIGION)
		{
			$comments.="<br><b>"." PARTNER_RELIGION : "."</b><br>"."Changed From "."<b>".$partner_religion."</b><br>"." To "."<b>".$this->PARTNER_RELIGION."</b>";
		}
		if($partner_nakshatra!=$this->PARTNER_NAKSHATRA)
		{
			$comments.="<br><b>"." PARTNER_NAKSHATRA : "."</b><br>"."Changed From "."<b>".$partner_nakshatra."</b><br>"." To "."<b>".$this->PARTNER_NAKSHATRA."</b>";
		}
        if($partner_state!=$this->STATE)
        {
            $comments.="<br><b>"." STATE : "."</b><br>"."Changed From "."<b>".$partner_state."</b><br>"." To "."<b>".$this->STATE."</b>";
        }
       /* if($city_india !=$this->CITY_INDIA)
        {
            $comments.="<br><b>"." STATE : "."</b><br>"."Changed From "."<b>".$partner_state."</b><br>"." To "."<b>".$this->STATE."</b>";
        }*/
        if($occupation_grouping !=$this->OCCUPATION_GROUPING)
        {
            $comments.="<br><b>"." OCCUPATION_GROUPING : "."</b><br>"."Changed From "."<b>".$occupation_grouping."</b><br>"." To "."<b>".$this->OCCUPATION_GROUPING."</b>";
        }
		$crmuser = getname($cid);

		if ($comments!="")
		{
			 $sql = "INSERT INTO jsadmin.PROFILECHANGE_LOG(ID,USER,DATE,PROFILEID,CHANGE_DETAILS,CHANGE_TYPE,COMPANY) VALUES ('','$crmuser',NOW(),'$profileid','".addslashes(stripslashes($comments))."','D','$COMPANY')";
			 $mysqlObj->executeQuery($sql,$db) ;
		}
	}
}
?>
