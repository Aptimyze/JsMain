<?php

/** 
* All the Zone related operations are peformed through this class.
* @author Lavesh Rawat
* @copyright Copyright 2008, Infoedge India Ltd.
*/

include_once("Mysql.class.php");

class Zone 
{
	private $ZoneId;	
	private $RegId;	
	private $ZoneName;	
	private $ZoneDesc;	
	private $ZoneMaxBans;	
	private $ZoneRotAllowed;	
	private $ZoneMaxBansInRot;	
	private $ZoneStatus;	
	private $ZoneAlignment;	
	private $ZoneSpacing;	
	private $ZoneBanWidth;	
	private $ZoneBanHeight;
	private $ZonePopup;	
	private $ZoneEntryDt;	
	private $ZoneModDt;	
	private $CriteriaId;	
	private $ZoneCriterias;	
	private $ZoneMeaning;
	private $ZoneSidePanel;
	private $Zoneheader;

        public function getZoneId()
        {
                return $this->ZoneId;
        }

	public function getRegId()
	{
		return $this->RegId;
	}
	public function getZoneName()
	{
		return $this->ZoneName;
	}
	public function getZoneDesc()
	{
		return $this->ZoneDesc;
	}
	public function getZoneMaxBans()
	{
		return $this->ZoneMaxBans;
	}
	public function getZoneRotAllowed()
	{
		return $this->ZoneRotAllowed;
	}
	public function getZoneMaxBansInRot()
	{
		return $this->ZoneMaxBansInRot;
	}

	public function getZoneStatus()
	{
		return $this->ZoneStatus;
	}

	public function getZoneAlignment()
	{
		return $this->ZoneAlignment;
	}
	public function getZoneSpacing()
	{
		 return $this->ZoneSpacing;
	}
	public function getZoneBanWidth()
	{
		 return $this->ZoneBanWidth;
	}

	public function getZoneBanHeight()
	{
		 return $this->ZoneBanHeight;
	}
	public function getZonePopup()
	{
		 return $this->ZonePopup;
	}
	public function getZoneEntryDt()
	{
		 return $this->ZoneEntryDt;
	}
	public function getZoneModDt()
	{
		 return $this->ZoneModDt;
	}
	public function getCriteriaId()
	{
		 return $this->CriteriaId;
	}
	public function getZoneCriterias()
	{
		 return $this->ZoneCriterias;
	}
	public function getZoneMeaning()
	{
		 return $this->ZoneMeaning;
	}
	public function getZoneSidePanel()
	{
		 return $this->ZoneSidePanel;
	}
	public function getZoneOnRotation()
	{
		 return $this->ZoneOnRotation;
	}
	
	public function getZoneheader() 
	{
		return $this->Zoneheader;
	}


	public function __construct($ZoneId="")
	{
		if($ZoneId!='')
			$this->ZoneId = $ZoneId;

		$this->table="bms2.ZONE";
	}

	/**
	* Setting method for zone information.
	* parameters will be set with column names.
	* @param string $parameter column(s) need to be fetched.
	*/
	public function setZoneDetails($parameter="*")
	{
		if($this->ZoneId)
		{
			$mysqlObj=new Mysql;
			$mysqlObj->connect();		
			$sql="SELECT SQL_CACHE $parameter FROM $this->table WHERE ZoneId=$this->ZoneId and ZoneStatus='active'";
			$result = $mysqlObj->query($sql) ; 
			$myrow =$mysqlObj->fetchAssoc($result);
			if($myrow)
			{	
				foreach ($myrow as $key => $value)
					$this->$key=$value;	
			}
		}
	}
}
?>
