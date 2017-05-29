<?php
class HOLIDAY extends TABLE
{
        public function __construct($dbname="")
        {
                parent::__construct($dbname);
        }

	/**
	 * This function is used to find out the no of holidays falling between any 2 dates .
	**/
	public function calculateNoOfHolidaysBetweenDates($time1,$time2)
	{
		$sql="SELECT COUNT(DATE) NUM from jsadmin.HOLIDAY where DATE>:TIME1 and DATE<:TIME2";
                $res=$this->db->prepare($sql);
		$res->bindValue(":TIME1", $time1, PDO::PARAM_STR);
		$res->bindValue(":TIME2", $time2, PDO::PARAM_STR);
		$res->execute();
		if($row = $res->fetch(PDO::FETCH_ASSOC))
			return $row['NUM'];
		else
			return NULL;
        }

	/**
	 * This function is used to find out the no of entries of a date in table jsadmin.HOLIDAY.
	**/
	public function calculateNoOfEntriesOfDate($date)
	{
		$sql="SELECT count(DATE) NUM from jsadmin.HOLIDAY where DATE='".strftime("%Y-%m-%d",JSstrToTime("$date"))."'";
                $res=$this->db->prepare($sql);
		$res->execute();
		if($row = $res->fetch(PDO::FETCH_ASSOC))
			return $row['NUM'];
		else
			return NULL;
        }

}
?>
