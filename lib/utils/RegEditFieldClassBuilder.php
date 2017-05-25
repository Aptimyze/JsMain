<?php
$socialRoot=realpath(dirname(__FILE__)."/../..");
$fp=fopen($socialRoot."/lib/model/lib/RegisterEditFields.class.php","w");
$fr = fopen($socialRoot."/lib/model/lib/forms/RegFields.class.php","w");
/*
$fp=fopen($socialRoot."/lib/model/lib/RegEditFields.class.php","w");
*/
$now=date("Y-m-d");
include_once($socialRoot."/web/profile/connect.inc");
    $db=connect_db();
fwrite($fp,"<?php\n /*
	This is auto-generated class by running lib/utils/RegEditFieldClassBuilder.php
	This class should not be updated manually.
	Created on $now
 */
	class RegisterEditFields
	{
		/*This function will return all the fields of this page*/
    	public static function getPageFields(\$page,\$returnArr='')
    	{
		\$page_obj=new PageFields(\$page);\n
                foreach (RegistrationEnums::\$pageFieldMap[\$page] as \$id=>\$fieldName)
                {
			switch(\$fieldName)\n\t\t\t{\n");

				$sql="SELECT * from REGISTER.USER_PROFILE_FIELDS where 1";
				$res=mysql_query($sql);	
				while($row_page=mysql_fetch_assoc($res))
				{
					fwrite($fp,"\t\t\t\tcase \"$row_page[FIELD_NAME]\":"
					);
					$all_page_fields[$page][]=$row_page[FIELD_NAME];
					fwrite($fp,"
					\$field=new Field($row_page[ID]);
					\$field->setName(\"$row_page[FIELD_NAME]\");
					\$field->setFieldType(\"$row_page[TYPE]\");
					\$field->setConstraintClass(\"$row_page[CONSTRAINT_CLASS]\");
					\$field->setDependentField(\"$row_page[DEPENDENT_FIELD]\");
					\$field->setLabel(\"$row_page[LABEL]\");
					\$field->setBlankValue(\"$row_page[BLANK_VALUE]\");
					\$field->setBlankLabel(\"$row_page[BLANK_LABEL]\");
					\$field->setTableName(\"$row_page[TABLE_NAME]\");
					\$page_obj->setField($row_page[ID],\"\",\"\",\$field);\n");
					fwrite($fp,"\t\t\t\t\tbreak;\n");
				}
			fwrite($fp,"\t\t\t}\n\t\t}\n
		return(\$page_obj);
			}

			public static function getFieldArray(\$page)
				{
				return(RegistrationEnums::\$pageFieldMap[\$page]);
				}\n");
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
