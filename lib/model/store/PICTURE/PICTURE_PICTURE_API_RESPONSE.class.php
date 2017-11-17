<?php

/**
 * Description of PICTURE_PICTURE_API_RESPONSE
 * Store Class for CRUD Operation on PICTURE.PICTURE_API_RESPONSE
 * 
 * @author Kunal Verma
 * @created 21st Sept 2017
 */
class PICTURE_PICTURE_API_RESPONSE extends TABLE {

  /**
   * @fn __construct
   * @brief Constructor function
   * @param $dbName - Database to which the connection would be made
   */
  public function __construct($dbname = "") {
    parent::__construct($dbname);
  }

  /**
   * 
   * @param type $arrRecordData
   * @return type
   * @throws jsException
   */
  public function insertRecord($arrRecordData) {
    if (!is_array($arrRecordData))
      throw new jsException("", "Array is not passed in InsertRecord OF PICTURE_PICTURE_API_RESPONSE.class.php");

    try {
      $szINs = implode(',', array_fill(0, count($arrRecordData), '?'));

      $arrFields = array();
      foreach ($arrRecordData as $key => $val) {
        $arrFields[] = strtoupper($key);
      }
      $szFields = implode(",", $arrFields);

      $sql = "INSERT IGNORE INTO PICTURE.PICTURE_API_RESPONSE ($szFields,MOD_DT) VALUES ($szINs,NOW())";
      $pdoStatement = $this->db->prepare($sql);

      //Bind Value
      $count = 0;
      foreach ($arrRecordData as $k => $value) {
        ++$count;
        $pdoStatement->bindValue(($count), $value);
      }
      $pdoStatement->execute();
      return $this->db->lastInsertId();
    } catch (Exception $e) {
	    jsException::nonCriticalError("PICTURE_PICTURE_API_RESPONSE (1)-->.$sql".$e);
    }
  }

  /**
   * 
   * @param type $iProfileID
   * @param type $arrRecordData
   * @return boolean
   * @throws jsException
   */
  public function updateRecord($iPictureId, $arrRecordData) {
    if (!is_numeric(intval($iPictureId)) || !$iPictureId) {
      throw new jsException("", "$iPictureId is not numeric in UpdateRecord OF PICTURE_PICTURE_API_RESPONSE.class.php");
    }

    if (!is_array($arrRecordData))
      throw new jsException("", "Array is not passed in UpdateRecord OF PICTURE_PICTURE_API_RESPONSE.class.php");

    if (isset($arrRecordData['PROFILEID']) && strlen($arrRecordData['PROFILEID']) > 0)
      throw new jsException("", "Trying to update PROFILEID in  in UpdateRecord OF PICTURE_PICTURE_API_RESPONSE.class.php");

    try {
      $arrFields = array();
      foreach ($arrRecordData as $key => $val) {
        $columnName = strtoupper($key);

        $arrFields[] = "$columnName = ?";
      }
      $szFields = implode(",", $arrFields);

      $sql = "UPDATE PICTURE.PICTURE_API_RESPONSE SET $szFields WHERE PROFILEID = ?";
      $pdoStatement = $this->db->prepare($sql);

      //Bind Value
      $count = 0;
      foreach ($arrRecordData as $k => $value) {
        ++$count;
        $pdoStatement->bindValue(($count), $value);
      }
      ++$count;
      $pdoStatement->bindValue($count, $iPictureId);

      $pdoStatement->execute();
      return true;
    } catch (Exception $e) {
      throw new jsException($e);
    }
  }

  /**
   * 
   * @param type $iProfileID
   * @return type
   * @throws jsException
   */
  public function findRecord($iProfileID) {
    if (!is_numeric(intval($iProfileID)) || !$iProfileID) {
      throw new jsException("", "iProfileID is not numeric in findRecord OF PICTURE_PICTURE_API_RESPONSE.class.php");
    }

    try {
      $sql = "SELECT * FROM PICTURE.PICTURE_API_RESPONSE WHERE PROFILEID=:PID AND COMPLETE_STATUS='N' ORDER BY ID DESC LIMIT 1";
      $pdoStatement = $this->db->prepare($sql);

      $pdoStatement->bindValue(":PID", $iProfileID, PDO::PARAM_INT);
      $pdoStatement->execute();

      $arrResult = false;
      if ($pdoStatement->rowCount()) {
        $arrResult = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
      }

      return $arrResult;
    } catch (Exception $ex) {
      throw new jsException($ex);
    }
  }

  public function get($name)
  {
	  $str = "";
	  if($name)
	  {
		  $str = " AND OWNER = :AGENT";
	  }
	  $sql = "select p.id,N.MainPicUrl, p.MAINPIC,p.PICTUREID,p.PROFILEID, p.ADULT,p.SPOOF,p.VIOLENCE,p.LABEL,p.FACE_COUNT from PICTURE.PICTURE_API_RESPONSE as p   JOIN newjs.PICTURE_FOR_SCREEN_NEW N ON N.PICTUREID = p.PICTUREID  WHERE  SCREEN IS NULL $str GROUP BY p.PICTUREID LIMIT 1";
	  $res = $this->db->prepare($sql);
	  if($name)
	  {
		  $res->bindValue(":AGENT",$name,PDO::PARAM_STR);
	  }
	  $res->execute();
	  $result = $res->fetch(PDO::FETCH_ASSOC);
	  return $result;
  }

	public function initiate($name)
	{
		try{
			$sql = "UPDATE PICTURE.PICTURE_API_RESPONSE set owner = :NAME where SCREEN IS NULL and OWNER IS NULL LIMIT 1";
			$res = $this->db->prepare($sql);
			$res->bindValue(":NAME",$name,PDO::PARAM_STR);
			$res->execute();
		}
		catch (Exception $ex) {
			throw new jsException($ex);
		}

	}

	public function getFace($pictureid){
  	    try{
  	    	$sql = "SELECT * FROM PICTURE.FACE_RESPONSE where PICTUREID = :PICTUREID";
  	    	$res = $this->db->prepare($sql);
  	    	$res->bindValue(":PICTUREID",$pictureid,PDO::PARAM_INT);
  	    	$res->execute();
	        $res->execute();
	        $arrResult = false;
	        if ($res->rowCount()) {
		        $arrResult = $res->fetchAll(PDO::FETCH_ASSOC);
	        }

	        return $arrResult;

        }
        catch (Exception $ex) {
	        throw new jsException($ex);
        }
	}

	public function updateBenchmark($pictureid, $edit,$deletedReason)
	{
		try{
			$sql = "UPDATE PICTURE.PICTURE_API_RESPONSE SET SCREEN = :SCREEN ,SCREEN_REASON = :SCREEN_REASON WHERE id = :ID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":SCREEN",$edit,PDO::PARAM_STR);
			$res->bindValue(":ID",$pictureid,PDO::PARAM_INT);
			if(is_array($deletedReason))
			{
				$reason = implode(",", $deletedReason);
			}
			else
			{
				$reason = "";
			}
			$res->bindValue(":SCREEN_REASON",$reason,PDO::PARAM_STR);
			$res->execute();
			}
			catch (Exception $ex) {
				throw new jsException($ex);
			}
	}

}
