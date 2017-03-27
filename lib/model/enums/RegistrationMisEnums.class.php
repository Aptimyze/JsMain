<?php

class RegistrationMisEnums {
  public static $columnDates = array('04', '05', '06', '07', '08', '09', '10', '11', '12','13','14','15');
  public static $monthNames = array('4' => 'April','5' => 'May','6' => 'June','7' => 'July','8' => 'August','9' => 'September','10' => 'October', '11' => 'November', '12' => 'December', '13' =>'January','14' => 'Feburary','15' => 'March');
  public static $quarterNames = array('Q1' => 'April-June','Q2' => 'July-September','Q3' => 'October-December','Q4' => 'January-March');
  public static $quaterIterate  = array("Q1","Q2","Q3","Q4","totalCount","percent");
  public static $monthIterate = array('4', '5', '6', '7', '8', '9', '10', '11', '12','13','14','15','totalCount',"percent");
  public static $dayIterate = array('1','2','3','4', '5', '6', '7', '8', '9', '10', '11', '12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','totalCount',"percent");
  public static $ageBucket = array(
  	"F_18_20" => array("LOW"=>18,"HIGH"=>20,"GENDER"=>"F"),
  	"F_21_24" => array("LOW"=>21,"HIGH"=>24,"GENDER"=>"F"),
  	"F_25_28" => array("LOW"=>25,"HIGH"=>28,"GENDER"=>"F"),
  	"F_29_32" => array("LOW"=>29,"HIGH"=>32,"GENDER"=>"F"),
  	"F_33_36" => array("LOW"=>33,"HIGH"=>36,"GENDER"=>"F"),
  	"F_37_40" => array("LOW"=>37,"HIGH"=>40,"GENDER"=>"F"),
  	"F_41_above" => array("LOW"=>41,"HIGH"=>90,"GENDER"=>"F"),
  	"M_21_24" =>array("LOW"=>21,"HIGH"=>24,"GENDER"=>"M"),
  	"M_25_28" => array("LOW"=>25,"HIGH"=>28,"GENDER"=>"M"),
  	"M_29_32" => array("LOW"=>29,"HIGH"=>32,"GENDER"=>"M"),
  	"M_33_36" => array("LOW"=>33,"HIGH"=>36,"GENDER"=>"M"),
  	"M_37_40" => array("LOW"=>37,"HIGH"=>40,"GENDER"=>"M"),
  	"M_41_above" => array("LOW"=>41,"HIGH"=>90,"GENDER"=>"M")
  	);
  public static $ageGenderBucket = array('F_18_20'=>'F_18_20','F_21_24'=>'F_21_24','F_25_28'=>'F_25_28','F_29_32'=>'F_29_32','F_33_36'=>'F_33_36','F_37_40'=>'F_37_40','F_41_above'=>'F_41_above','M_21_24'=>'M_21_24','M_25_28'=>'M_25_28','M_29_32'=>'M_29_32','M_33_36'=>'M_33_36','M_37_40'=>'M_37_40','M_41_above'=>'M_41_above');
}
?>