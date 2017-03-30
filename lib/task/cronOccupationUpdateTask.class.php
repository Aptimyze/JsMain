<?
/*
This php script reads occupation from fieldlib class and updates them.
*/

class cronOccupationUpdateTask extends sfBaseTask
{
  /**
   * 
   * @access protected
   * @param none
  */
  protected function configure()
  {

    $this->namespace           = 'cron';
    $this->name                = 'cronOccupationUpdate';
    $this->briefDescription    = 'updates occupation table.';
    $this->detailedDescription = <<<EOF
     This php script reads occupation from fieldlib class and updates them.

     [php symfony cron:cronOccupationUpdate] 
EOF;
  }

  /**
   * 
   * Function for executing cron. 
   * 
   * @access protected
   * @param $arguments,$options
   */
  protected function execute($arguments = array(), $options = array())
  {
    try 
    {
        $occupation = FieldMap::getFieldLabel('occupation', '',1);
        $occupationGrouping = FieldMap::getFieldLabel('occupation_grouping_mapping_to_occupation', '',1);

        $insertOccupationArray = array();
        $insertOccupationGroupingArray = array();
        $count = 0;
        foreach ($occupation as $key => $value) {
            $insertOccupationArray[$count]['occupationValue'] = $value;
            $insertOccupationArray[$count]['occupationNumber'] = $key;
            foreach ($occupationGrouping as $groupingKey => $groupingValues) {
                $explodeGroupingValues = explode(',',$groupingValues);

                if ( in_array($key,$explodeGroupingValues))
                {
                  $insertOccupationArray[$count]['groupNumber'] = $groupingKey;
                  break;
              }
          }
          $count++;
      }
      unset($count);
      $newjs_occupation = new NEWJS_OCCUPATION();
      $newjs_occupation->createNewTable();
      $newjs_occupation->insert($insertOccupationArray);
      $newjs_occupation->RenameTable();
      unset($insertOccupationArray);

      //occupation grouping table update part
      $occGroupingObj = new NEWJS_OCCUPATION_GROUPING();
      $occGroupingObj->createNewTable();
      
      $occGroupingArr = FieldMap::getFieldLabel('occupation_grouping', '',1);
      foreach($occGroupingArr as $key=>$val)
      {
        $insertOccupationGroupingArray[$count]["occupationGroupingValue"] = $val;
        $insertOccupationGroupingArray[$count]["occupationGroupingNumber"] = $key;
        $count++;
      }
      $occGroupingObj->insertDataToTable($insertOccupationGroupingArray);            
      $occGroupingObj->RenameTable();
      unset($insertOccupationGroupingArray);
  } 
  catch (Exception $e) 
  {
    throw new jsException($e);
  }
}

   
}
?>