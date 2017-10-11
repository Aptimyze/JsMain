<?php
$fsearch=fopen($socialRoot."/lib/model/lib/search/SearchFieldMapLib.class.php","w");
fwrite($fsearch,"<?php\n /*
	This is auto-generated class by running lib/utils/JsLabelsCreater.php
	This class should not be updated manually.
	Created on $now
 */
	class SearchFieldMapLib{
		/*This will return label corresponding to value*/
        public static function getFieldLabel(\$label,\$value,\$returnArr='')
	{
		switch(\$label)
		{");
fwrite($fsearch,"
case \"caste_group_array\":\n
	\$arr=array(\n");
$sql="SELECT CG.GROUP_VALUE AS GROUP_VALUE,CG.CASTE_VALUE AS CASTE_VALUE, C.PARENT AS PARENT FROM newjs.CASTE_GROUP_MAPPING CG, newjs.CASTE C WHERE CG.CASTE_VALUE = C.VALUE ORDER BY CG.GROUP_VALUE,C.SORTBY";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
        {
                $casteKey = $row["GROUP_VALUE"];
                $casteVal = $row["CASTE_VALUE"];
                if(!(($casteKey=="20" && $casteVal=="20") || ($casteKey==485 && ($casteVal==20 || $row["PARENT"]==9))))// Jain castes having parent=9 are excluded and bania in bania also excluded 
                {
                        $casteGroupArraySearch[$row["GROUP_VALUE"]] = $casteGroupArraySearch[$row["GROUP_VALUE"]].$row["CASTE_VALUE"].",";
                }
        }

foreach ($casteGroupArraySearch as $k=>$v)
        {
                fwrite($fsearch,"\"".$k."\" => \"".rtrim($v,",")."\",\n");
        }
        
fwrite($fsearch,");\n
        break;\n");	
        
        	
fwrite($fsearch,"case \"religion\":\n
	\$arr=array(\n");
$sql="select VALUE,LABEL from RELIGION order by SortSearch";
$result=mysql_query($sql);
while($myrow=mysql_fetch_array($result))
{	
        if($myrow["LABEL"]=="Other")
                $myrow["LABEL"]="No Religion/Caste";
	fwrite($fsearch,"\"" . $myrow["VALUE"] . "\"=>\"" . $myrow["LABEL"] . "\",\n");
}
fwrite($fsearch,");\n
	break;\n");

fwrite($fsearch,"
case \"mtongue\":\n
	\$arr=array(\n");
$sql="select VALUE,LABEL,SEARCH_SORT from MTONGUE WHERE SEARCH_SORT IS NOT NULL order by SEARCH_SORT";
	$result=mysql_query($sql);

	while($myrow=mysql_fetch_array($result))
	{
                if($myrow["SEARCH_SORT"]>100) // All hindi mtongue with sorting greater than 100
                        $mtongueSearchArr["1"]=$mtongueSearchArr["1"].$myrow["VALUE"].",";
                else
                        $mtongueSearchArr["0"]=$mtongueSearchArr["0"].$myrow["VALUE"].",";
                        
        }
        krsort($mtongueSearchArr);
        foreach($mtongueSearchArr as $mainMtongue=>$resMtongue)
        {
		fwrite($fsearch,"\"".$mainMtongue."\" => \"".rtrim($resMtongue,",")."\",\n");
	}
fwrite($fsearch,");\n
	break;\n");
	

fwrite($fsearch,"\ndefault:\n
				break;\n
			}\n
			if(\$returnArr)\n
				return \$arr;\n
			else\n
				return \$arr[\$value];\n
			}\n
		}\n
?>\n");

fclose($fsearch);
		
?>
