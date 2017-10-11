<?php

/* this layer creates fetches data from table CALayerDisplayData
 */
class PROFILE_CA_LAYER_DISPLAY_DATA extends TABLE
{
  public function __construct($dbname='') {
    parent::__construct($dbname);
  }
  /*this function will fetch a record from table for the asked layer type
   * @param- layer id
   * @return- tuple corresponding to a layer
   */
  public function getLayersData() {
    try {
      $sql="SELECT * FROM PROFILE.CA_LAYER_DISPLAY_DATA";
      $prep=$this->db->prepare($sql);
      $prep->execute();
      while ($result = $prep->fetch(PDO::FETCH_ASSOC)) {
        $records[] = $result;
      }
      return $records;
    } 
    catch (PDOException $e) {
      throw new jsException($e);
    } 
  }
     
}
