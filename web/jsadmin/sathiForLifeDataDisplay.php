<?php
include("connect.inc");
$data           =authenticated($cid);
$db             =connect_db();
$user           =trim(getname($cid));

if($data)
{
    if(!$submitPhotos){
        $once = 1;
        $sql="SELECT * from MARKETING.PROFILE_DETAILS";
        $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
        
        echo "<form action='/jsadmin/sathiForLifeDataDisplay.php?cid=$cid' method='post'>";
        echo "<input type='submit' name='submitPhotos'>  Click to save selected photos</input>";
        echo "<table border='2'>";
        
        
        $columnHeading = array('SNO','NAME','AGE','PARTNER_NAME','USERNAME','PHONE','EMAIL','DESCRIPTION','PICTURE','CHECK_BOX','VIDEO_URL','SATHI_STORY','TWITTER_HANDLE','INSTA_USERNAME');
        echo "<tr>";
        foreach($columnHeading as $columnName){
            echo "<td>$columnName</td>";
        }
        echo "</tr>";
        
        
        while($row=mysql_fetch_row($result))
        {
            echo "<tr>";
            foreach($row as $key=>$column){
                if($key==8 && $column){
                    $replacedUrl = str_replace ('JS',  JsConstants::$siteUrl,$column);
                    $column = "<a target='_blank' href = ".$replacedUrl.">Image</a>";
                }
                echo "<td>$column</td>";
                if($key==8){
                    if($column){
                        $nameOfCheckbox = $replacedUrl."_*_".$row[7];
                        echo "<td><input name = 'checkbox_$row[0]' value='$nameOfCheckbox' type='checkbox'></input></td>";
                    }
                    else
                        echo "<td></td>";
                }       
            }
            echo "</tr>";
        }
        echo "</table></form>";
    }
    else{
        foreach($_POST as $key=>$val){
            if(explode('_',$key)[0] == 'checkbox'){
                include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
                $toStore[] = $val;
            }
        }
        JsMemcache::getInstance()->setHashObject('SFL_images',$toStore,3600*24*30,true);
        echo "Your selected photos have been saved, these will be displayed on 'SathiForLife page'";
    }
}
