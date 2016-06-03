<?php
class billing_OCB_BANNER_MESSAGE extends TABLE {

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function insertBanner($top,$bottom,$start_dt,$end_dt)
	{
		try{
			$sql = "INSERT INTO billing.OCB_BANNER_MESSAGE(TOP_MSG,BOTTOM_MSG,START_DT,END_DT) VALUES (:TOP_MSG,:BOTTOM_MSG,:START_DT,:END_DT)";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":TOP_MSG", $top, PDO::PARAM_STR);
			$prep->bindParam(":BOTTOM_MSG", $bottom, PDO::PARAM_STR);
			$prep->bindParam(":START_DT", $start_dt, PDO::PARAM_STR);
			$prep->bindParam(":END_DT", $end_dt, PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e){
			throw new jsException($e);
		}
	}

	public function getBannerMessage($dt)
	{
		try{
			$sql = "SELECT TOP_MSG,BOTTOM_MSG,PAGEID FROM billing.OCB_BANNER_MESSAGE WHERE :TODAY_DATE BETWEEN START_DT AND END_DT";
			$prep = $this->db->prepare($sql);
			$prep->bindParam(":TODAY_DATE", $dt, PDO::PARAM_STR);
			$prep->execute();
			if($result=$prep->fetch(PDO::FETCH_ASSOC)){
				$output['top'] = $result['TOP_MSG'];
				$output['bottom'] = $result['BOTTOM_MSG'];
				$output['pageId'] = $result['PAGEID'];
			}
		}
		catch(Exception $e){
			throw new jsException($e);
		}
		return $output;
	}
}
?>
