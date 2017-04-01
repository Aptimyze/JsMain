<?php
//This will generate class AllCasteMap for all Links in the mailer architecture 
//that will be difined as consts.
//@author Kunal
$socialRoot=realpath(dirname(__FILE__)."/../..");

$fcasteMap=fopen($socialRoot."/lib/model/lib/AllCasteMap_Unused.class.php","w");
$now=date("Y-m-d");
include_once($socialRoot."/web/profile/connect.inc");
fwrite($fcasteMap,"<?php\n /*
	This is auto-generated class by running lib/utils/AllCasteCreater.php
	This class should not be updated manually.
	Created on $now
	@author : Kunal Verma
 */
	class AllCasteMap{
    /*
    * Array of all caste genertaed from newjs.NEWJS_CASTE Table
    */
		public static \$arrAllCaste=array(\n");
	
$db=connect_db();
$sql="SET SESSION group_concat_max_len = 1000000;";
$result=mysql_query_decide($sql);
// Entries for LinkArray having Id as key and values as Name,Url,ReqAutoLogin
$sql="SELECT SQL_CACHE VALUE, PARENT, ISALL, ISGROUP FROM newjs.CASTE ORDER BY VALUE";
	$result=mysql_query_decide($sql);

	while($myrow=mysql_fetch_array($result))
	{
		fwrite($fcasteMap,"'".$myrow["VALUE"]."'=>array('PARENT'=>'".$myrow["PARENT"]."','ISALL'=>'".$myrow["ISALL"]."','ISGROUP'=>'".$myrow["ISGROUP"]."'),\n");
	}
  fwrite($fcasteMap,");\n\n
  /*
    * Array of all caste genertaed from newjs.NEWJS_CASTE Table
    */
		public static \$arrAllCaste_GroupByParent=array(\n");
  
  // Entries for LinkArray having Id as key and values as Name,Url,ReqAutoLogin
$sql="SELECT PARENT, GROUP_CONCAT( VALUE ) AS `GROUP`  FROM  newjs.`CASTE` GROUP BY PARENT";
	$result=mysql_query_decide($sql);

	while($myrow=mysql_fetch_array($result))
	{
		fwrite($fcasteMap,"'".$myrow["PARENT"]."'=>'".$myrow["GROUP"]."',\n");
	}
  fwrite($fcasteMap,");\n\n
    /*
    * Array of all caste genertaed from newjs.CASTE_GROUP_MAPPING Table
    */
		public static \$arrAllCaste_GroupMapping=array(\n");
  
   // Entries for LinkArray having Id as key and values as Name,Url,ReqAutoLogin
$sql="SELECT GROUP_VALUE, GROUP_CONCAT(CASTE_VALUE) AS `GROUP` FROM newjs.CASTE_GROUP_MAPPING GROUP BY GROUP_VALUE ";
	$result=mysql_query_decide($sql);

	while($myrow=mysql_fetch_array($result))
	{
		fwrite($fcasteMap,"'".$myrow["GROUP_VALUE"]."'=>'".$myrow["GROUP"]."',\n");
	}
  fwrite($fcasteMap,");\n\n");
  
$funcDef = <<<FUNDEF
  /*
  * Function for reteriving all caste 
  * @param \$caste_values : Input caste values
  * @return Array of mapped caste or null
  */
public static function getAllCaste(\$caste_values){
  \$caste_values = str_replace("'","",\$caste_values);
  \$caste_values = str_replace("\"","",\$caste_values);
  \$caste_values_arr = explode(",",\$caste_values);
  \$outCaste = array(); 
  foreach(\$caste_values_arr as \$k=>\$v)
  {
    if(self::\$arrAllCaste[\$v])
      \$outCaste[\$v] = self::\$arrAllCaste[\$v];
  }
  
  if( 0 === count(\$outCaste) )
    \$outCaste = null;
 return \$outCaste;
}\n\n
FUNDEF;
fwrite($fcasteMap,$funcDef);
fwrite($fcasteMap,"}?>");
	mysql_free_result($result);
