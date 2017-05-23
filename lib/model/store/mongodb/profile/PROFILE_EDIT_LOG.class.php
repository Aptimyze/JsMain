<?php

/**
 * Description of PROFILE_EDIT_LOG
 * Store Class for MongoDB CRUD Operation on profile.EDIT_LOG
 * 
 * @author Kunal Verma
 * @created 13th March 2016
 */

class PROFILE_EDIT_LOG extends MongoTable {

  public function __construct($dbname="") 
  {
    parent::__construct($dbname);
  }
  
  /**
   * insertOne
   * @param type $arrFields
   * @return type
   * @throws jsException
   */
  public function insertOne($arrFields)
  {
    try {
        if (false === isset($arrFields['PROFILEID'])) {
          throw new jsException('profileid is missing');
        }
        
        if(false === isset($arrFields['MOD_DT'])) {
          $arrFields['MOD_DT'] = date('Y-m-d H:i:s');
        }
        
        $result = $this->db->profile->EDIT_LOG->insertOne($arrFields);
        return $result->getInsertedCount();
    } catch (Exception $e) {
      jsMongoException::logThis($e);
    }
  }
  
  /**
  * getRecords
  * @param type $whereCond 
  * @param type $columnNotPresentArr 
  * @param type $columnsToFetch
  * @param type sortDesc
  * @param type $iProfileID
  */
  public function getRecords($whereCond="",$columnNotPresentArr="",$columnsToFetch="",$sortDesc="")
  {
    try {

	/**
	* setting filters using where condition and if column not present.
	*/
	if(is_array($whereCond))
		foreach($whereCond as $k=>$v)
			$filter[$k] = $v;
	if(is_array($columnNotPresentArr))
		foreach($columnNotPresentArr as $v)
			$filter[$v] = array('$exists' => true);

	/**
	* Sort By Desc
	*/
	if($sortDesc)
		$options['sort'][$sortDesc] = -1;

	/**
	* columns need to fetch from table
	*/
	$options["projection"]['_id'] = 0;
	if($columnsToFetch)
	{
		$temp = explode(",",$columnsToFetch);
		foreach($temp as $v)
			$options["projection"][$v] = '1';
	}

        $result = $this->db->profile->EDIT_LOG->find($filter , $options);
        return $this->cursorIntoArray($result);
    } catch (Exception $e) {
      jsMongoException::logThis($e);
    }
  } 
  
  /**
   * insertMany
   * @param type $arrFields
   * @return type
   * @throws jsException
   */
  public function insertMany($arrDocument)
  {
    try {
        $result = $this->db->profile->EDIT_LOG->insertMany($arrDocument);
        return $result->getInsertedCount();
    } catch (Exception $e) {
      jsMongoException::logThis($e);
      jsException::log("mongoError-->>".$http_msg);
      return false;
    }
  }
  
  /**
   * getLegalDetails
   * @param type $iProfileID
   * @return type
   */
  public function getLegalDetails($iProfileID)
  {
    try {
        $filter = ['PROFILEID'=>$iProfileID];
        $options = [
          'projection'=>['_id'=>0,'GENDER'=>1,'RELIGION'=>1,'CASTE'=>1,'MTONGUE'=>1,'MSTATUS'=>1,'DTOFBIRTH'=>1,'OCCUPATION'=>1,'COMPANY_NAME'=>1,'EDU_LEVEL'=>1,'INCOME'=>1,'AGE'=>1,'COUNTRY_RES'=>1,'CITY_RES'=>1,'COUNTRY_BIRTH'=>1,'CITY_BIRTH'=>1,'PHONE_RES'=>1,'PHONE_MOB'=>1,'ALT_MOBILE'=>1,'EMAIL'=>1,'HANDICAPPED'=>1,'FAMILY_BACK'=>1,'MOTHER_OCC'=>1,'PROFILE_HANDLER_NAME'=>1,'CONTACT'=>1,'PARENTS_CONTACT'=>1,'FAMILY_INCOME'=>1,'IPADD'=>1,'MOD_DT'=>1],
          'sort'=>['MOD_DT'=>-1]
        ];
        
        $result = $this->db->profile->EDIT_LOG->find($filter , $options);
        
        return $this->cursorIntoArray($result);
    } catch (Exception $e) {
      jsMongoException::logThis($e);
    }
  }
  
  /**
   * removeData : To remove data of profile from edit log store
   * @param type $iProfileID
   */
  public function removeData($iProfileID)
  {
    try {
        $filter = ['PROFILEID'=>$iProfileID];
        
        $result = $this->db->profile->EDIT_LOG->deleteMany($filter);
        return $result->getDeletedCount();
    } catch (Exception $e) {
      jsMongoException::logThis($e);
    }
  }
}
