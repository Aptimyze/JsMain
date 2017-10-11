<?php
class REGISTRATION_QUALITY extends TABLE
{
  private $fields = '';
  public function __construct($dbname="")
  {
          parent::__construct($dbname);
          $this->fields = "`REG_DATE` , `SOURCEID`,`SOURCE_COUNTRY`, `SOURCECITY` , `TOTAL_REG` , `F22` , `F22MV` , `F22MVCC` , `M26` , `M26MV` , `M26MVCC` , `SCREENED_SIC` , `SCREENED_CC` , `OTHERS_COMMUNITY`";
  }

	//Three function for innodb transactions
	public function startTransaction()
	{
		$this->db->beginTransaction();
	}
	public function commitTransaction()
	{
		$this->db->commit();
	}

	public function rollbackTransaction()
	{
		$this->db->rollback();
	}
	//Three function for innodb transactions
  public function insertQualityRegistration($registrationData){
        foreach ($registrationData as $key => $regCityData){
                foreach ($regCityData as $sourceGroup => $regDateData) {
                    $sql = "REPLACE INTO REGISTER.REGISTRATION_QUALITY ($this->fields) VALUES ";
                    $param = array();
                    $countReg = count($regDateData);
                    for($i = 1;$i<=$countReg;$i++){
                      $param[] = "(:REG_DATE_".$i.", :SOURCEID_".$i.", :SOURCECOUNTRY_".$i.", :SOURCECITY_".$i.", :TOTAL_REG_".$i.", :F22_".$i.", :F22MV_".$i.", :F22MVCC_".$i.", :M26_".$i.", :M26MV_".$i.", :M26MVCC_".$i.", :SCREENED_SIC_".$i.", :SCREENED_CC_".$i.",:OTHERS_COMMUNITY_".$i.")";
                    }
                    $paramStr = implode(",",$param);
                    $sql = $sql.$paramStr;
                    $res=$this->db->prepare($sql);
                    $i = 1;
                    foreach($regDateData as $sourceCity=>$regData){
                            $cityCountry = explode("_",$sourceCity);
                      $res->bindValue(":REG_DATE_".$i, $regData['date'], PDO::PARAM_STR);
                      $res->bindValue(":SOURCEID_".$i, $sourceGroup, PDO::PARAM_STR);
                      $res->bindValue(":SOURCECOUNTRY_".$i, $cityCountry[1], PDO::PARAM_STR);
                      $res->bindValue(":SOURCECITY_".$i, $cityCountry[0], PDO::PARAM_STR);
                      $res->bindValue(":TOTAL_REG_".$i, $regData['total_reg'], PDO::PARAM_INT);
                      $res->bindValue(":F22_".$i, $regData['F'], PDO::PARAM_INT);
                      $res->bindValue(":F22MV_".$i, $regData['FMV'], PDO::PARAM_INT);
                      $res->bindValue(":F22MVCC_".$i, $regData['FMVCC'], PDO::PARAM_INT);
                      $res->bindValue(":M26_".$i, $regData['M'], PDO::PARAM_INT);
                      $res->bindValue(":M26MV_".$i, $regData['MMV'], PDO::PARAM_INT);
                      $res->bindValue(":M26MVCC_".$i, $regData['MMVCC'], PDO::PARAM_INT);
                      $res->bindValue(":SCREENED_SIC_".$i, $regData['screened_SIC'], PDO::PARAM_INT);
                      $res->bindValue(":SCREENED_CC_".$i, $regData['SCREENED_CC'], PDO::PARAM_INT);
                      $res->bindValue(":OTHERS_COMMUNITY_".$i, $regData['OTHERS_COMMUNITY'], PDO::PARAM_INT);
                      $i++;
                    }
                    $res->execute();
                }
        }
    return "success";
  }
  public function getRegisrationData($filters){
    $where = ' WHERE ';
    if(isset($filters['start_date']))
      $condition[] = 'REG_DATE >= :START_DATE';
    if(isset($filters['end_date']))
      $condition[] = 'REG_DATE <= :END_DATE';
    
    if(isset($filters['source_names'])){
      $filters['source_names'] = implode('","', array_map('addSlashes',$filters['source_names']));
      $filters['source_names'] = '"'.$filters['source_names'].'"';
      $condition[] = 's.GROUPNAME IN ('.$filters['source_names'].')';
    }
    if(isset($filters['source_cities'])){
      $filters['source_cities'] = implode('","', array_map('addSlashes',$filters['source_cities']));
      $filters['source_cities'] = '"'.$filters['source_cities'].'"';
      $condition[] = 'rq.SOURCECITY IN ('.$filters['source_cities'].')';
    }elseif(isset($filters['source_countries'])){
      $filters['source_countries'] = implode('","', array_map('addSlashes',$filters['source_countries']));
      $filters['source_countries'] = '"'.$filters['source_countries'].'"';
      $condition[] = 'rq.SOURCE_COUNTRY IN ('.$filters['source_countries'].')';
    }
    
    $where .= implode(' AND ',$condition);
    if($filters['range_format'] == 'Y'){
      $fields = "date_format(REG_DATE,'%m') as REG_DATE,rq.SOURCEID, SUM(TOTAL_REG) as TOTAL_REG,SUM(F22) as F22, SUM(F22MV) as F22MV,SUM(F22MVCC) as F22MVCC,SUM(M26) as M26, SUM(M26MV) as M26MV, SUM(M26MVCC) as M26MVCC,SUM(SCREENED_SIC) as SCREENED_SIC,s.GROUPNAME";
      $groupBy = " GROUP BY rq.SOURCEID,date_format(REG_DATE,'%m')";
    }else{ 
      $fields = 'rq.*,s.GROUPNAME';
      $groupBy = '';
    }
    $sql = "SELECT ".$fields." FROM REGISTER.REGISTRATION_QUALITY as rq LEFT JOIN MIS.SOURCE as s ON s.SourceID = rq.SOURCEID ".$where.$groupBy;
    
    $prep=$this->db->prepare($sql);	
    
    if(isset($filters['start_date']))
      $prep->bindValue(":START_DATE",$filters['start_date'],PDO::PARAM_STR);
    if(isset($filters['end_date']))
      $prep->bindValue(":END_DATE",$filters['end_date'],PDO::PARAM_STR);

    $prep->execute();
    $profilesArr = array();
    // for financial year change year will start from 04 to 15 where 13 will be january of next year, 14 will be feburary and so on.
    while($res=$prep->fetch(PDO::FETCH_ASSOC)){
      $profilesArr['source_data'][] = $res;
      if($res['REG_DATE']<=3){ //if month is jan,feb or march then replace there numeric value 1,2,3 by 13,14,15
        $res['REG_DATE'] = $res['REG_DATE'] + 12;
      }
      $profilesArr['source_dates'][$res['REG_DATE']] += $res['TOTAL_REG']; // date wise complete total

    }
    return $profilesArr;
  }
}
?>
