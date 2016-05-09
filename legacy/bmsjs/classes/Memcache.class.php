<?php
/** 
* All the Memcache related operations are peformed through this class.
* It will decrease database load as data will be fetched from memory.
* @author Lavesh Rawat
* @copyright Copyright 2008, Infoedge India Ltd.
*/

include_once("Mysql.class.php");
include_once("Semaphore.class.php");

class UserMemcache extends Memcache 
{
	private $userData;
	private $memConns = array();

        public function getuserData()
        {
                return $this->userData;
        }


	/**
	* default values for memcache connection.
	*/
	public function __construct()
	{
		$this->memdbHost=JsConstants::$bmsMemcache[HOST];
		$this->memport=JsConstants::$bmsMemcache[PORT];
		$this->memCompression=0;
		//$this->memCompression='MEMCACHE_COMPRESSED';
		$this->memExpiry=3600; //1 hr
	}

	/**
	* This function is used to establish connection to the memcache server.
	* A new connection is established and made active only when the requested connection is not in the $memConns array.
	* If the requested connection is present in $memConns array, then it is made active from the array.
	* @param string memdbHost  host address of memcache connection.
	* @param int portName port address of above connection. 
	* @return memcache-link
	*/
	public function memConnect($memdbHost="",$portName="")
	{
		if(!$memdbHost)
			$memdbHost=$this->memdbHost;
		if(!$portName)
			$portName=$this->memport;


		if(!isset($this->memConns[$memdbHost]))
		{
			$this->memConns[$memdbHost]=$this->connect($memdbHost,$portName);
		}
		$this->activeMemcache= $this->memConns[$memdbHost];
		return $this->activeMemcache;
	}


	/**	
	* Fetch impression served by banner in last 15 minutes.
	* memcache is used to get impression for low sql utilization.
	* @param int $bannerId bannerid whose impression served need to be calculated
	* @return int number of banner served
	*/
	public function getBannerServed($bannerId)
	{
		$bannerId="bannerImpression:$bannerId";
		$memcacheObj=$this->memConnect();
		if($memcacheObj)
			return $this->get($bannerId);
		else
			return 0;
	}


	/**
	* log Impression of banner in memcache.
	* @param int $bannerId bannerid whose impression need to be stored.
	* @return void
	*/
	public function logBannerImpression($bannerId)
	{
		if($bannerId)
		{
			$semaphoreObj= new Semaphore;
			$semaphoreKey=$semaphoreObj->getLock($bannerId);
			if($semaphoreKey)
			{
				$bannerId="bannerImpression:$bannerId";
				$memcacheObj=$this->memConnect();
				if($memcacheObj)
					$obtainMemcacheConnection=1;
				else
					$obtainMemcacheConnection=0;

				if($obtainMemcacheConnection)
				{
					$bannerIdImpression=$this->get($bannerId);

					if($bannerIdImpression)
					{
						$this->set($bannerId,$bannerIdImpression+1);//no need to set timeout
					}
					else
					{
						$this->set($bannerId,1);//no need to set timeout
					}
				}	
				//sleep(3);
				$semaphoreObj->releaseLock($semaphoreKey);
			}		
		}
	}

	public function resetAndGetBannerImpression($bannerId)
	{
		$semaphoreObj= new Semaphore;
		$semaphoreKey=$semaphoreObj->getLock($bannerId);

		if($semaphoreKey)
		{
			$memcacheObj=$this->memConnect();
			if($memcacheObj)
			{
				$impressionServed=$this->getBannerServed($bannerId);
				$bannerId="bannerImpression:$bannerId";
				$this->set($bannerId,0);
				$semaphoreObj->releaseLock($semaphoreKey);
			}
		}
		return($impressionServed);
	}

	/**
	* set information of logged in user (for jeevansathi user)
	* @param int profileid unique key for logged in user
	* @return void
	*/
	public function userDetails($profileid)
	{

		$memcacheObj=$this->memConnect();
		if($memcacheObj)
			$obtainMemcacheConnection=1;
		else
			$obtainMemcacheConnection=0;


		if($obtainMemcacheConnection)
		{
			$profileidBms="Bms".$profileid;
			$data=unserialize($this->get($profileidBms));
			$this->userData=$data;
		}

		if(!$data)
		{
			$mysqlObj=new Mysql;
			$mysqlObj->Connect();
			//$mysqlObj->Connect('temp');

			$sql="select GENDER,COUNTRY_RES,CITY_RES,INCOME,AGE,SUBSCRIPTION,MSTATUS,EDU_LEVEL_NEW EDU_LEVEL,OCCUPATION,MTONGUE,RELIGION,MESSENGER_ID,EMAIL,MESSENGER_CHANNEL,HAVEPHOTO,INCOMPLETE  from newjs.JPROFILE where PROFILEID='$profileid'";
			$result = $mysqlObj->Query($sql); 

			if($mysqlObj->numRows($result)<=0)
				return NULL;
			$myrow=$mysqlObj->fetchArray($result);

			if($myrow["INCOMPLETE"]=='Y')
				$data["PROFILE_COMPLETE_STATE"] = 'I';
			else
				$data["PROFILE_COMPLETE_STATE"] = 'C';
			//added by lavesh rawat
			//----1----
			$sql_1 = "SELECT SLAB  FROM newjs.ANALYTICS_VARIABLE_DISCOUNT WHERE PROFILEID = '$profileid'";
			$res_1 = $mysqlObj->Query($sql_1);
			$row_1 = $mysqlObj->fetchArray($res_1);
			$data["VARIABLE_DISCOUNT"]=$row_1["SLAB"];	
			//----1----

			//----2------
			if($myrow["HAVEPHOTO"]=='N' || $myrow["HAVEPHOTO"]=='')
				$photo=0;
			else
				$photo=1;
			$horo=0;
			//if($myrow["SHOW_HOROSCOPE"]=='Y')
			{
				$sql_ast_det = "SELECT COUNT(*) AS COUNT FROM newjs.ASTRO_DETAILS WHERE PROFILEID = '$profileid'";
				$res_ast_det = $mysqlObj->Query($sql_ast_det);
				$row_ast_det = $mysqlObj->fetchArray($res_ast_det);
				if($row_ast_det['COUNT'] > 0)
					$horo=1;
				else	
				{
					$sql_ast_det = "SELECT COUNT(*) AS COUNT FROM newjs.HOROSCOPE WHERE PROFILEID = '$profileid'";
					$res_ast_det = $mysqlObj->Query($sql_ast_det);
					$row_ast_det = $mysqlObj->fetchArray($res_ast_det);
					if($row_ast_det['COUNT'] > 0)
						$horo=1;
				}
			}
			if($photo)
			{
				if($horo)		
					$data["PROFILE_STATUS"]=4;
				else
					$data["PROFILE_STATUS"]=3;
			}
			elseif($horo)
				$data["PROFILE_STATUS"]=2;
			else
				$data["PROFILE_STATUS"]=1;
			//----2------

			//----3------
			if(strstr($myrow["EMAIL"],'@gmail'))
				$data["GMAIL_ID"]=1;
			elseif($myrow["MESSENGER_CHANNEL"]==6 && $myrow["MESSENGER_ID"]!='')
				$data["GMAIL_ID"]=2;
			else
				$data["GMAIL_ID"]=3;
			//----3------
		
			//---4----
			$sql_1 = "SELECT SLAB FROM newjs.ANALYTICS_EOI_STATUS WHERE PROFILEID = '$profileid'";
			$res_1 = $mysqlObj->Query($sql_1);
			$row_1 = $mysqlObj->fetchArray($res_1);
			$data["EOI_STATUS"]=$row_1["SLAB"];	
			//---4----	


			//---5----
			$sql_1 = "SELECT B.SUBSTATE,DATEDIFF(A.FTO_EXPIRY_DATE,CURDATE()) AS EXPIRY_DIFF FROM FTO.FTO_CURRENT_STATE A, FTO.FTO_STATES B WHERE A.STATE_ID = B.STATE_ID AND A.PROFILEID = '$profileid'";
			$res_1 = $mysqlObj->Query($sql_1);
			$row_1 = $mysqlObj->fetchArray($res_1);

			if($row_1["SUBSTATE"])
			{
				$data["FTO_STATE"] =  $row_1["SUBSTATE"];
				$expiry_diff = $row_1["EXPIRY_DIFF"];
				if($expiry_diff>0)
				{
					if($expiry_diff>5)
						$expiry_diff=5;
					$data["FTO_EXPIRY"] = $expiry_diff;
				}
			}
			else
				$data["FTO_STATE"] = 'F';
			//---5----
			//added by lavesh rawat


			$data["GENDER"]=$myrow["GENDER"];
			$data["COUNTRY_RES"]=$myrow["COUNTRY_RES"];
			if($myrow["COUNTRY_RES"]==51)
			{
				$data["CITY_INDIA"]=$myrow["CITY_RES"];
				$data["CITY_USA"]='';
			}
			else
			{
				$data["CITY_INDIA"]='';
				$data["CITY_USA"]=$myrow["CITY_RES"];
			}
			$data["INCOME"]=$myrow["INCOME"];
			$data["AGE"]=$myrow["AGE"];
			$data["SUBSCRIPTION"]=$myrow["SUBSCRIPTION"];
			if($myrow["SUBSCRIPTION"])
			{
				if(in_array($myrow["SUBSCRIPTION"],array('F',"'F','D'",'D',"'D','F'")))
				{
					$data["SUBSCRIPTION"].=",8";
				}
			}
			$data["MSTATUS"]=$myrow["MSTATUS"];
			$data["EDU_LEVEL"]=$myrow["EDU_LEVEL"];
			$data["OCCUPATION"]=$myrow["OCCUPATION"];
			$data["MTONGUE"]=$myrow["MTONGUE"];
			$data["RELIGION"]=$myrow["RELIGION"];

	                $sql="SELECT DATEDIFF(EXPIRY_DT,CURDATE()) AS DIFF FROM billing.SERVICE_STATUS WHERE PROFILEID='$profileid'  AND SERVEFOR LIKE '%F%' AND ACTIVE IN ('Y') ORDER BY EXPIRY_DT DESC LIMIT 1";
			$res = $mysqlObj->Query($sql);
			$row = $mysqlObj->fetchArray($res);
			if($row[0]>0 && $row[0]<31)
			{
				if($data["SUBSCRIPTION"]!='')
					$data["SUBSCRIPTION"].=",7";
				else
					$data["SUBSCRIPTION"].=",7";
			}
//print_r($data);
			if($obtainMemcacheConnection && $data)
			{
				$profileidBms="Bms".$profileid;
				$this->set($profileidBms,serialize($data),$this->memCompression,$this->memExpiry);
			}
			$this->userData=$data;
		}
	}
}
?>
