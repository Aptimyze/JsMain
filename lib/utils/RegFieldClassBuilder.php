<?php
$socialRoot=realpath(dirname(__FILE__)."/../..");
$fp=fopen($socialRoot."/lib/model/lib/RegEditFields.class.php","w");
$fr = fopen($socialRoot."/lib/model/lib/forms/RegFields.class.php","w");
$now=date("Y-m-d");
include_once($socialRoot."/web/profile/connect.inc");
    $db=connect_db();
	$sql ="SELECT DISTINCT PAGE FROM reg.REG_EDIT_PAGE_FIELDS";
	$result=mysql_query($sql);
fwrite($fp,"<?php\n /*
	This is auto-generated class by running lib/utils/RegFieldClassBuilder.php
	This class should not be updated manually.
	Created on $now
 */
	class RegEditFields
	{
		/*This function will return all the fields of this page*/
    	public static function getPageFields(\$page,\$returnArr='')
    	{
			switch(\$page){\n");
			while($row=mysql_fetch_assoc($result)){
				fwrite($fp,"  case \"$row[PAGE]\":
					\$page_obj=new PageFields(\"$row[PAGE]\");\n"
				);
				$sql="SELECT * from reg.REG_EDIT_PAGE_FIELDS where PAGE='$row[PAGE]'";
				$res=mysql_query($sql);	
				while($row_page=mysql_fetch_assoc($res)){
				$sql="SELECT * from reg.PROFILE_FIELDS where ID='$row_page[FIELD_ID]'";
				$res_field=mysql_query($sql);	
				$row_field=mysql_fetch_assoc($res_field);
				$all_page_fields[$row[PAGE]][]=$row_field[FIELD_NAME];
				fwrite($fp,"
					\$field=new Field($row_field[ID]);
					\$field->setName(\"$row_field[FIELD_NAME]\");
					\$field->setFieldType(\"$row_field[TYPE]\");
					\$field->setConstraintClass(\"$row_field[CONSTRAINT_CLASS]\");
					\$field->setJsValidation(\"$row_field[JAVASCRIPT_VALIDATION]\");
					\$field->setDependentField(\"$row_field[DEPENDENT_FIELD]\");
					\$field->setLabel(\"$row_page[LABEL]\");
					\$field->setBlankValue(\"$row_page[BLANK_VALUE]\");
					\$field->setBlankLabel(\"$row_page[BLANK_LABEL]\");
					\$field->setTableName(\"$row_page[TABLE_NAME]\");
					\$page_obj->setField($row_page[FIELD_ID],\"$row_page[GROUP]\",\"$row_page[POSITION]\",\$field);\n");
				}
					fwrite($fp,"break;\n");
			}
			fwrite($fp,"}\n
				return(\$page_obj);
			}
			public static function getFieldArray(\$page){
				switch(\$page){\n");
				foreach($all_page_fields as $page=>$field_arr){
					fwrite($fp,"case '$page':
						\$field_array=array('".implode("','",$all_page_fields[$page])."');
					break;\n");
				}
				fwrite($fp,'}
					return $field_array;
				}');
fwrite($fp,"}");
fwrite($fr,"<?php\n /*
        This is auto-generated class by running lib/utils/RegFieldClassBuilder.php
        This class should not be updated manually.
        Created on $now
 */
        class RegFields
        {
                /*This function will return all the fields of this page*/
        	public static function getPageFields(\$label,\$value,\$returnArr='')
        	{
        		switch(\$label){\n");
fwrite($fr,"
 				case \"stdcodes\":\n
  					\$arr=array(\n");
        $sql = "select SQL_CACHE STD_CODE,VALUE from CITY_NEW WHERE TYPE!='STATE' AND COUNTRY_VALUE IN (51,128) AND STD_CODE NOT IN (0,'') ORDER BY SORTBY";
        $result = mysql_query($sql);
        while($myrow=mysql_fetch_array($result))
        {
                fwrite($fr,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["STD_CODE"] . "\",\n");
        }
fwrite($fr,");\n
  				break;
 				case \"isdcode\":\n
  					\$arr=array(\n");
        $sql = "SELECT SQL_CACHE VALUE,ISD_CODE FROM newjs.COUNTRY_NEW ORDER BY ALPHA_ORDER";
        $result = mysql_query($sql);
        while($myrow=mysql_fetch_array($result))
        {
                fwrite($fr,"\"" . $myrow["VALUE"] . "\"=>\"+".$myrow["ISD_CODE"] . "\",\n");
        }
fwrite($fr,");\n
  				break;");
fwrite($fr,"\ndefault:\n
                                break;\n
                        }\n
                        if(\$returnArr)\n
                                return \$arr;\n
                        else\n
                                return \$arr[\$value];\n
                        }\n
                }\n
?>\n");
fclose($fr);
?>
