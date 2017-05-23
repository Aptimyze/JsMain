<?php

/** 
* All the Memcache related operations are peformed through this class.
* It will decrease database load as data will be fetched from memory.
* @author Lavesh Rawat / Sriram Vishwanathan
* @copyright Copyright 2008, Infoedge India Ltd.
*/

include_once("Mysql.class.php");

class Banner
{
	private $BannerId;
	private $ZoneId;
	private $BannerClass;
	private $BannerStatic;
	private $BannerBookDate;
	private $BannerStartDate;
	private $BannerEndDate;
	private $BannerReactivationDate;
	private $BannerDeactivationDate;
	private $BannerStatus;
	private $BannerPriority;
	private $BannerWeightage;
	private $BannerServed;
	private $BannerClick;
	private $BannerGif;
	private $BannerUrl;
	private $CampaignId;
	private $SubscriptionId;
	private $BannerDefault;
	private $BannerString;
	private $BannerFeatures;
	private $BannerFreeOrPaid;
	private $BannerInternalOrExternal;
	private $MailerId;
	private $BannerLocation;
	private $BannerCountry;
	private $BannerInCity;
	private $BannerUsCity;
	private $BannerCategories;
	private $BannerIP;
	private $BannerCity;
	private $BannerCTCMin;
	private $BannerCTCMax;
	private $BannerAgeMin;
	private $BannerAgeMax;
	private $BannerGender;
	private $BannerCTC;
	private $BannerMEM;
	private $BannerMARITALSTATUS;
	private $BannerEDU;
	private $BannerOCC;
	private $BannerCOM;
	private $BannerREL;
	private $BannerPROPCITY;
	private $BannerPROPINR;
	private $BannerPROPTYPE;
	private $BannerPROPCAT;
	private $BannerFixed;
	private $CATEGORY_SUB;

	private $liveBanners;

	public function __construct($BannerId="")
	{
               if($BannerId!='')
                        $this->BannerId = $BannerId;

		$this->table="bms2.BANNER";
	}

	public function getLiveBanners()
	{
		return $this->liveBanners;
	}

	public function getBannerId()
	{
		return $this->BannerId;
	}

	public function getZoneId()
	{
		return $this->ZoneId;
	}

	public function getBannerClass()
	{
		return $this->BannerClass;
	}

	public function getBannerStatic()
	{
		return $this->BannerStatic;
	}

	public function getBannerBookDate()
	{
		return $this->BannerBookDate;
	}

	public function getBannerStartDate()
	{
		return $this->BannerStartDate;
	}

	public function getBannerEndDate()
	{
		return $this->BannerEndDate;
	}

	public function getBannerReactivationDate()
	{
		return $this->BannerReactivationDate;
	}

	public function getBannerDeactivationDate()
	{
		return $this->BannerDeactivationDate;
	}

	public function getBannerStatus()
	{
		return $this->BannerStatus;
	}

	public function getBannerPriority()
	{
		return $this->BannerPriority;
	}

	public function getBannerWeightage()
	{
		return $this->BannerWeightage;
	}

	public function getBannerServed()
	{
		return $this->BannerServed;
	}

	public function getBannerClick()
	{
		return $this->BannerClick;
	}

	public function getBannerGif()
	{
		return $this->BannerGif;
	}

	public function getBannerUrl()
	{
		return $this->BannerUrl;
	}

	public function getCampaignId()
	{
		return $this->CampaignId;
	}

	public function getSubscriptionId()
	{
		return $this->SubscriptionId;
	}

	public function getBannerDefault()
	{
		return $this->BannerDefault;
	}

	public function getBannerString()
	{
		return $this->BannerString;
	}

	public function getBannerFeatures()
	{
		return $this->BannerFeatures;
	}

	public function getBannerFreeOrPaid()
	{
		return $this->BannerFreeOrPaid;
	}

	public function getBannerInternalOrExternal()
	{
		return $this->BannerInternalOrExternal;
	}

	public function getMailerId()
	{
		return $this->MailerId;
	}

	public function getBannerLocation()
	{
		return $this->BannerLocation;
	}

	public function getBannerCountry()
	{
		return $this->BannerCountry;
	}

	public function getBannerInCity()
	{
		return $this->BannerInCity;
	}

	public function getBannerUsCity()
	{
		return $this->BannerUsCity;
	}

	public function getBannerCategories()
	{
		return $this->BannerCategories;
	}

	public function getBannerIP()
	{
		return $this->BannerIP;
	}

	public function getBannerCity()
	{
		return $this->BannerCity;
	}

	public function getBannerCTCMin()
	{
		return $this->BannerCTCMin;
	}

	public function getBannerCTCMax()
	{
		return $this->BannerCTCMax;
	}

	public function getBannerAgeMin()
	{
		return $this->BannerAgeMin;
	}

	public function getBannerAgeMax()
	{
		return $this->BannerAgeMax;
	}

	public function getBannerGender()
	{
		return $this->BannerGender;
	}

	public function getBannerCTC()
	{
		return $this->BannerCTC;
	}

	public function getBannerMEM()
	{
		return $this->BannerMEM;
	}

	public function getBannerMARITALSTATUS()
	{
		return $this->BannerMARITALSTATUS;
	}

	public function getBannerEDU()
	{
		return $this->BannerEDU;
	}

	public function getBannerOCC()
	{
		return $this->BannerOCC;
	}

	public function getBannerCOM()
	{
		return $this->BannerCOM;
	}

	public function getBannerREL()
	{
		return $this->BannerREL;
	}

	public function getBannerPROPCITY()
	{
		return $this->BannerPROPCITY;
	}

	public function getBannerPROPINR()
	{
		return $this->BannerPROPINR;
	}

	public function getBannerPROPTYPE()
	{
		return $this->BannerPROPTYPE;
	}

	public function getBannerPROPCAT()
	{
		return $this->BannerPROPCAT;
	}

	public function getBannerFixed()
	{
		return $this->BannerFixed;
	}

	public function getCATEGORY_SUB()
	{
		return $this->CATEGORY_SUB;
	}
	public function getbanzonepriority()
	{
		return $this->banzonepriority;
	}
	public function getbannarr()
	{
                return $this->bannarr;
	}

	/**
	* set live/active banners of zone.
	* @param int $zone zoneid where banner need to be displayed.
	* @param int $subzone subzone/priority of a banner
	* @param char $showall ='Y' implies all subzone. 
	*/
        public function setActiveBannersStr($zone,$subzone="",$showall="",$extraCondition="")
	{
		$this->liveBanners=$this->bannerIdonStatus($zone,'live',$subzone,$showall,$extraCondition);
	}

	/**
	* @param zone zoneid
	* @param string $status status of banner (active/served/deactive....)
	* @param int $subzone subzone/priority of a banner
	* @param char $showall ='Y' implies all subzone.
	* @param string $extraCondition used if an extra condition need to be added (eg. not equal condistions)
	* return string comma seperated strings.
	*/
	public function bannerIdonStatus($zone,$status,$subzone="",$showall="",$extraCondition="")
        {
                $mysqlObj=new Mysql;
                $mysqlObj->connect();

                if($showall)
                {
                        $sql_where=" WHERE ZoneId = '$zone' AND BannerStatus='$status'";
                }
                else
                {
                        $sql_where=" WHERE ZoneId = '$zone'";
                       	$sql_where.=" AND BannerStatus='$status'";
                        if($subzone)
                                $sql_where.=" AND BannerPriority='$subzone'";
                }

		if($extraCondition)
		{
			$sql_where.=" AND $extraCondition ";	
		}
	
               $sql="SELECT SQL_CACHE BannerId FROM $this->table".$sql_where;
                $result = $mysqlObj->query($sql);
                while($myrow=$mysqlObj->fetchArray($result))
                        $statusBanners[]=$myrow["BannerId"];

		if(is_array($statusBanners))
			$statusBannerStr=implode(",",$statusBanners);
		return($statusBannerStr);
        }

	/**
	* @param table tablename
	* @param column column of table
	* return array possible values of column type.
	*/

        function GetEnumColumnTypeValues( $table, $column)
        {
                $mysqlObj=new Mysql;
                $mysqlObj->connect();

                // Create a SQL Query to get the Columns Type information,
                // Open a database connection, execute the query, and retrieve
                // the result.

                echo "sql : ".$sql = "show columns from $table like '$column'";
                $result = $mysqlObj->query($sql);
                $myrow=$mysqlObj->fetchArray($result);

                echo "enum : ".$enum = $myrow['Type'];
                $off  = strpos($enum,"(");
                $enum = substr($enum, $off+1, strlen($enum)-$off-2);
                echo "value : ".$values = explode(",",$enum);

                // For each value in the array, remove the leading and trailing
                // single quotes, convert two single quotes to one. Put the result
                // back in the array in the same form as CodeCharge needs.

                for( $n = 0; $n < Count($values); $n++) {
                $val = substr( $values[$n], 1,strlen($values[$n])-2);
                $val = str_replace("''","'",$val);
                $values[$n] = array( $val, $val );
                }

                // return the values array to the caller
                return $values;
        }

        /**
        * Setting method for Banner information.
        * parameters will be set with column names.
        * @param string $parameter column(s) need to be fetched.
	* @where string $where filtering condition.This condition will be a where part of sql
        */
        public function setBannerDetails($parameter="*",$where="")
        {
                $mysqlObj=new Mysql;
                $mysqlObj->connect();

                $sql="SELECT SQL_CACHE $parameter FROM $this->table";
		if($where)
			$sql.=" WHERE ".$where;			

                $result = $mysqlObj->query($sql);
		$myrow=$mysqlObj->fetchAssoc($result);
                foreach ($myrow as $key => $value)
                        $this->$key=$value;
        }

	/**
	* Filter out the banner based on booking criteria & information of logged-in-user , searchcriteria..  .
	* @param string $filterCriteriaStr filtering condition.This condition will be a where part of sql.
	* @param int $zone zoneid.
	*/
	public function setBannersOnBookingCriteria($filterCriteriaStr,$zone)
	{
		$mysqlObj=new Mysql;
		$mysqlObj->connect();

		$allSubZoneArray[]=array('');

		
		//Select query modified by Varun on 5 th Feb 2009  to join with new table
		$sql="SELECT B.*,B99.REF_ID,B99.PROFILEID,B99.PG_ID FROM bms2.BANNER B LEFT OUTER JOIN bms2.BANNER99INFO B99 ON B.BannerId = B99.BannerId WHERE $filterCriteriaStr   ";
		
	//echo $sql;	
		$result=$mysqlObj->query($sql);
		while($myrow=$mysqlObj->fetchArray($result))
		{
			$bannerid=$myrow["BannerId"];

			$subzone=$myrow["BannerPriority"];

			unset($default_flag);
			unset($fixed_flag);

			if($myrow["BannerDefault"]=='Y')
				$default_flag=1;
			elseif($myrow["BannerFixed"]=='Y')
				$fixed_flag=1;

			if(in_array($subzone,$allSubZoneArray))
			{
				$allSubZoneArray[]=$subzone;
				if($default_flag)
					$banzonepriority[$zone][$subzone]["defaultcount"]+=1;
				elseif($fixed_flag)
					$banzonepriority[$zone][$subzone]["fixedcount"]+=1;
				else
					$banzonepriority[$zone][$subzone]["notdefaultcount"]+=1;
				$banzonepriority[$zone][$subzone]["banners"].=",".$bannerid;
			}
			else
			{
				$allSubZoneArray[]=$subzone;
				if($default_flag)
				{
					$banzonepriority[$zone][$subzone]["defaultcount"]=1;
					$banzonepriority[$zone][$subzone]["notdefaultcount"]=0;
					$banzonepriority[$zone][$subzone]["fixedcount"]=0;
				}
				elseif($fixed_flag)
				{
					$banzonepriority[$zone][$subzone]["defaultcount"]=0;
					$banzonepriority[$zone][$subzone]["notdefaultcount"]=0;
					$banzonepriority[$zone][$subzone]["fixedcount"]=1;
				}
				else
				{
					$banzonepriority[$zone][$subzone]["defaultcount"]=0;
					$banzonepriority[$zone][$subzone]["notdefaultcount"]=1;
					$banzonepriority[$zone][$subzone]["fixedcount"]=0;
				}
				$banzonepriority[$zone][$subzone]["banners"]=$bannerid;
			}

			$j=1;

			foreach($myrow as $key => $value)
			{
				if($j%2==0)
					$bannarr[$bannerid][$key]=$value;
				$j=$j+1;
			}
		}
		$this->banzonepriority=$banzonepriority;
		$this->bannarr=$bannarr;	
	}
}
?>
