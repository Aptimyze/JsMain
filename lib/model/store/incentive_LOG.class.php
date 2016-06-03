<?php
class incentive_LOG extends TABLE
{
	public function __construct($dbname="")
	{
      		parent::__construct($dbname);
   	}

        public function addRecord($idArr)
        {
                try
                {
			$idStr =implode(",",$idArr);
			if($idStr){
				$sql ="INSERT INTO incentive.LOG (PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,ADDON_SERVICEID,REF_ID,DISCOUNT,PREFIX_NAME,LANDMARK) SELECT PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,ADDON_SERVICEID, ID,DISCOUNT,PREFIX_NAME,LANDMARK FROM incentive.PAYMENT_COLLECT WHERE ID IN($idStr)";
                        	$res = $this->db->prepare($sql);
				$res->execute();
			}
                }
                catch(Exception $e)
                {
                        throw new jsException($e);
                }
	}
}
?>
