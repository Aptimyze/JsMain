<?
/*
ADD SUMMARY
*/

class cronAddNewCitiesTask extends sfBaseTask
{
  /**
   * 
   * @access protected
   * @param none
  */
  protected function configure()
  {

    $this->namespace           = 'cron';
    $this->name                = 'cronAddNewCities';
    $this->briefDescription    = 'add new cities to table.';
    $this->detailedDescription = <<<EOF
     This php script reads cities from table CITY_NEW and merges the new values to create a new city array

     [php symfony cron:cronAddNewCities] 
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
          $cityObj = new newjs_CITY_NEW("newjs_masterRep");
          $allCityDataArr = $cityObj->getAllCityLabel("1"); //get cities in table
          $newCitiesDataArr = newCitiesEnums::$newCitiesDataArr;
          $spellCorrectionArr = newCitiesEnums::$spellCorrectionArr; 
          $newCitiesKeysArr = array_keys($newCitiesDataArr);
          $mergedArr = $allCityDataArr+$newCitiesDataArr; //merge old and new cities          
          asort($mergedArr); //sort cities alphabetically

          $cityObj->addCitiesIntoTable($mergedArr); //add sorted cities to test table
          $cityObj->updateNewCityData($newCitiesKeysArr); //update data of new cities in test table          
          $cityObj->updateOldCityValuesIntoNewTable(); //update old cities data from old table to test table
          $cityObj->RenameTable(); //rename tables

          //correct spellings
          foreach($spellCorrectionArr as $key=>$value)
          {
            $cityObj->updateSpellings($key,$value);
          }
          
          die("EXECUTED");
    } 
    catch (Exception $e) 
    {
      throw new jsException($e);
    }
  }

   
}
?>