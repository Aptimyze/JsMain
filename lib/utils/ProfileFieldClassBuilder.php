<?php
$socialRoot=realpath(dirname(__FILE__)."/../..");
$fp=fopen($socialRoot."/lib/model/lib/ProfileEditFields.class.php","w");
//$fr = fopen($socialRoot."/lib/model/lib/forms/RegFields.class.php","w");
$now=date("Y-m-d");
include_once($socialRoot."/web/profile/connect.inc");
    $db=connect_db();
fwrite($fp,"<?php\n /*
	This is auto-generated class by running lib/utils/ProfileFieldClassBuilder.php
	This class should not be updated manually.
	Created on $now
 */
	class ProfileEditFields
	{
		/*This function will return all the fields of this page*/
    	public static function getPageField(\$fieldName,\$returnArr='')
    	{
			switch(\$fieldName){\n");
			$sql="SELECT * from reg.EDIT_FIELDS";
				$res_field=mysql_query($sql);
			while($row=mysql_fetch_assoc($res_field)){
				fwrite($fp,"case '$row[FIELD_NAME]':"
				);
				fwrite($fp,"
					\$field=new Field('','$row[FIELD_NAME]');
					\$field->setFieldType(\"$row[TYPE]\");
					\$field->setConstraintClass(\"$row[CONSTRAINT_CLASS]\");
					\$field->setTableName(\"$row[TABLE_NAME]\");
				");
					fwrite($fp,"break;\n\n");
			}
			fwrite($fp,"}\n
				return(\$field);
			}
			}");
			
fclose($fr);
?>
