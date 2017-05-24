<?php
class billing_ADDON_RANK extends TABLE{

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function getVASRankSrvc($vas,$memStatus)
	{
		try
		{
			$sql="SELECT VAS_ID,RANK FROM billing.ADDON_RANK WHERE MSID=:MEM_STATUS";
			if(is_array($vas) && count($vas)){
				$sql .= " AND ";
				foreach($vas as $key=>$val){
					if($key != count($vas)-1){
						$sql.="VAS_ID=:VAS{$key} OR ";	
					} else {
						$sql.="VAS_ID=:VAS{$key} ";
					}
				}
				$sql .= "ORDER BY RANK DESC";
			} else {
				$sql .= "AND VAS_ID = NULL ORDER BY RANK DESC";
			}
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":MEM_STATUS",$memStatus,PDO::PARAM_STR);
			if(is_array($vas) && count($vas)){
				foreach($vas as $key=>$val){
					$prep->bindValue(":VAS{$key}",$val,PDO::PARAM_STR);
				}
			}
			$prep->execute();
			while($result = $prep->fetch(PDO::FETCH_ASSOC))
			{
				$res[]= array($result['VAS_ID'],$result['RANK']);;
			}
			return $res;
		}
		catch(PDOException $e)
		{
			throw new jsException($e);
		}
	}
}
?>
