<?php
/**
 * Created by PhpStorm.
 * User: Pankaj1
 * Date: 29/08/17
 * Time: 5:18 PM
 */

class test_PHOTO_BENCHMARK extends TABLE {

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}

	public function insert($faceDetected, $pid, $origPath,$imageT,$profileid){
		try{

			$sql = "INSERT IGNORE INTO test.PHOTO_BENCHMARK (`PROFILEID`,`PICTUREID`,`OrigPic`,`TIMESTAMP`,`FACEDETECTED`,`PICFORMAT`) 
														VALUES(:PROFILEID,:PICTIREID,:ORIGPIC,now(),:FACEDETECTED,:PICFORMAT)";
			$res = $this->db->prepare($sql);
			$res->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
			$res->bindValue(":PICTIREID", $pid, PDO::PARAM_INT);
			$res->bindValue(":ORIGPIC", $origPath, PDO::PARAM_STR);
			$res->bindValue(":FACEDETECTED", $faceDetected, PDO::PARAM_STR);
			$res->bindValue(":PICFORMAT", $imageT, PDO::PARAM_STR);
			$res->execute();
		} catch (Exception $ex) {
			throw new jsException($ex);
		}
	}

	public function get($name='')
	{
		$str = "";
		if($name)
		{
			$str = " AND owner = :AGENT";
		}
		$sql = "SELECT * FROM test.PHOTO_BENCHMARK WHERE facedetected = 1 and edit IS NULL $str LIMIT 1";
		$res = $this->db->prepare($sql);
		if($name)
		{
			$res->bindValue(":AGENT",$name,PDO::PARAM_STR);
		}
		$res->execute();
		$result = $res->fetch(PDO::FETCH_ASSOC);
		return $result;
	}
	public function markShowed($pid)
	{
		try{
			$sql = "UPDATE test.PHOTO_BENCHMARK set showed=true where PICTUREID = :PICTUREID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":PICTUREID",$pid,PDO::PARAM_INT);
			$res->execute();
		}
		catch (Exception $ex) {
			throw new jsException($ex);
		}
	}

	public function updateOpenCVEdit($pid)
	{
		try{
			$sql = "UPDATE test.PHOTO_BENCHMARK set opencvEdit = true where PICTUREID =:PICTUREID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":PICTUREID",$pid,PDO::PARAM_INT);
			$res->execute();
		}
		catch (Exception $ex) {
			throw new jsException($ex);
		}
	}
	public function updateBenchmark($pictureid,$edit)
	{
		try{
			$sql = "UPDATE test.PHOTO_BENCHMARK set edit = :EDIT where PICTUREID =:PICTUREID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":PICTUREID",$pictureid,PDO::PARAM_INT);
			$res->bindValue(":EDIT",$edit,PDO::PARAM_STR);
			$res->execute();
		}
		catch (Exception $ex) {
			throw new jsException($ex);
		}
	}

	public function initiate($name)
	{
		try{
			$sql = "UPDATE test.PHOTO_BENCHMARK set owner = :NAME where edit IS NULL and OWNER IS NULL AND facedetected = 1 LIMIT 1";
			$res = $this->db->prepare($sql);
			$res->bindValue(":NAME",$name,PDO::PARAM_STR);
			$res->execute();
		}
		catch (Exception $ex) {
			throw new jsException($ex);
		}

	}
}