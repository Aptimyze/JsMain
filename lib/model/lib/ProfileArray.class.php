<?php
class ProfileArray{

  private $profileArr;

  function __construct($dbname="") {
    $this->profileArr = null;
    $this->JPROFILE = JPROFILE::getInstance($dbname);
  }

  public function getDetail($valueArray = "", $excludeArray = "", $greaterThanArray = "", $fields = "PROFILEID", $table = "JPROFILE", $lessThanArray = "", $greaterThanArray = "", $connection = ""){


    $this->$table = new JPROFILE($connection);
    $res = $this->$table->getArray($valueArray, $excludeArray, $greaterThanArray, $fields, $lessThanArray, '', '', $greaterThanArray);
    foreach($res as $key=>$val){

      $profileArr[$val["PROFILEID"]] = Profile::getInstance("newjs_master",$val["PROFILEID"]);
      $profileArr[$val["PROFILEID"]]->setDetail($val);
    }
    return $profileArr;
  }

  /**
   * This function gets results from jprofile/jprofile_education/profile_name based on an array of parameters.
   * @param - $valueArray -  array - keys represent field name and values have field values. The results specifying these parameters are to be included.
   * @param - $excludeArray - array - keys represent field name and values have field values. The results specifying these parameters are to be excluded.
   * @param - $fields - string - these details would be returned for each profile
   * @param - $table - name of the table that is to be queried
   * @param - $profileIdArray - array of profile objects
   **/

  public function getResultsBasedOnJprofileFields($valueArray="",$excludeArray="",$greaterThanArray = "",$fields="PROFILEID",$table="JPROFILE",$connection="",$lessThanArray="",$greaterThanEqualArray="",$orderBy="",$limit="")
  {
    if($table == "JPROFILE_EDUCATION") {
      $this->$table = new newjs_JPROFILE_EDUCATION($connection);
    }
    elseif($table == "NAME_OF_USER")
      $this->$table = new incentive_NAME_OF_USER($connection);
    elseif($table == "JPROFILE")
      $this->$table = new JPROFILE($connection);
    //				$this->$table = JPROFILE::getInstance($connection);
    elseif($table == "JPROFILE_FOR_DUPLICATION")
      $this->$table = new test_JPROFILE_FOR_DUPLICATION($connection);

    if($orderBy && $limit)
    {
    	$profileIdArray = $this->$table->getArray($valueArray,$excludeArray,$greaterThanArray,$fields,$lessThanArray,$orderBy,$limit,$greaterThanEqualArray);
    }
    else		
        $profileIdArray = $this->$table->getArray($valueArray,$excludeArray,$greaterThanArray,$fields,$lessThanArray,'','',$greaterThanEqualArray);

    if(!$connection)
      $connection = "newjs_master";

    unset($this->profileArr);
    if(is_array($profileIdArray))
    {
      foreach($profileIdArray as $key=>$pid)
      {
	$this->profileArr[$key] = Profile::getInstance($connection,$pid);
	$this->profileArr[$key]->setDetail($pid,$fields);
      }
    }
    return $this->profileArr;
  }
}
?>
