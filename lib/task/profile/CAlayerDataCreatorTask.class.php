<?php

/* this class creates a file with data in it to be displayed on the critical 
 * action layers by fetching it from the table
 */
class CAlayerDataCreatorTask extends sfBaseTask
{
  protected function configure() {
    $this->namespace = 'profile';
    $this->name = 'CAlayerDataCreator';
    $this->briefDescription = 'create a file from table with data to be displayed on critical action layers';
    $this->detailedDescription = <<<EOF
Call it with:
[php symfony profile:CAlayerDataCreator]
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
    ));       
  }
  protected function execute($arguments = array(), $options = array()) {
    if(!sfContext::hasInstance()) {
      sfContext::createInstance($this->configuration);
    } 
    $fp=fopen(JsConstants::$docRoot."/../lib/model/lib/layer/CriticalActionLayerDataDisplay.class.php","w");
    $now=date("Y-m-d");
    fwrite($fp,"<?php\n /*
This is auto-generated class by running lib/task/profile/CAlayerDataCreatorTask.class.php
This class should not be updated manually.
Created on $now
 */
class CriticalActionLayerDataDisplay{
  /*This will return data corresponding to asked info for a particular layer id*/
  public static function getDataValue(\$layerid='',\$label='',\$value=''){
    \$arr=array( \n");
    $layerDataObj= new PROFILE_CA_LAYER_DISPLAY_DATA();
    $displayData= $layerDataObj->getLayersData();
    foreach ($displayData as $k=>$v) {
      $writeString .= $v[LAYERID]."=>array(";
      foreach ($v as $key=>$value) {
          $writeString .= $key."=>\"".$value."\",\n";
      }
      $writeString .= "),\n";
    }
    $writeString .= ");";
    fwrite($fp, $writeString."\nif (\$label) {
   if (\$value) {
     foreach (\$arr as \$k=>\$v) {
       foreach (\$v as \$key=>\$val) {
           if (\$key == \$label && \$value == \$val) {
             return \$v[LAYERID];
           }
       }
     }     
   }
   else {
     return \$arr[\$layerid][\$label];
   }
}
else {
 return \$arr[\$layerid];
}
}
}");
    fclose($fp);
  }          
}
