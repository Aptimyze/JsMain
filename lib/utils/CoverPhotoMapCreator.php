<?php
//This will generate class CoverPhotoMap for all the photo ids in the database
//that will be difined as consts.
//@author Nitish
$socialRoot=realpath(dirname(__FILE__)."/../..");

$urlMap=fopen($socialRoot."/lib/model/lib/CoverPhotoMap.class.php","w");
$now=date("Y-m-d");
$arrValidPhotoId = array();
$arrCategoryMap = array();
include_once($socialRoot."/web/profile/connect.inc");
connect_db();
fwrite($urlMap,"<?php\n /*
	This is auto-generated class by running lib/utils/CoverPhotoMapCreator.php
	This class should not be updated manually.
	Created on $now
	@author : Nitish
 */
	class CoverPhotoMap{
    /*
    * Array of all Cover Photos URLs genertaed from picture.COVER_PHOTO_URL Table
    * The abbreviations stands for the following:
    * SP => Sports
    * CK => Cooking
    * MD => Music/Dance
    * TR => Travel
    * PH => Photography
    * BO => Books
    * NA => Nature
    * PE => Pets
    * TE => Techie
    * PU => Puzzels
    * GA => Gardening
    * TV => TV/Movies
    */
    public static function getFieldLabel(\$label,\$value,\$returnArr='')
    {
        switch(\$label)
        {\n");
        // Entries for LinkArray having Id as key and values as Url
        $sql="SELECT PHOTOID, PHOTO_URL FROM PICTURE.COVER_PHOTO_URL";
        $result=mysql_query($sql) ;
        $prev = null;
        while($myrow=mysql_fetch_array($result))
        {
        $curr = substr($myrow["PHOTOID"], 0, 2);
        
        if($prev == null){
        fwrite($urlMap,"
        case \"".$curr."\":\n
        \$arr=array(\n");
        }
        if($prev != null && ($curr != $prev)){
            fwrite($urlMap,");\nbreak;\n");
            fwrite($urlMap,"
            case \"".$curr."\":\n
            \$arr=array(\n");
        }
            fwrite($urlMap,"\t\t\t\t'".$myrow["PHOTOID"]."'=>'".$myrow["PHOTO_URL"]."',\n");
        $prev = $curr;
        }
        
        $sql="SELECT CATEGORY_NAME, CATEGORY_ID FROM PICTURE.COVER_PHOTO_CATEGORIES";
        $result=mysql_query($sql) ;
        while($myrow=mysql_fetch_array($result))
        {
            $arrValidPhotoId[] = $myrow["CATEGORY_ID"];
            $arrCategoryMap[$myrow["CATEGORY_ID"]] = $myrow["CATEGORY_NAME"];
        }
        
        fwrite($urlMap,");\n
        break;\n\n case \"valid_photo_id\" : \n\n \$arr = array( ");
        foreach($arrValidPhotoId as $k=>$v){
            fwrite($urlMap," \"$k\" => \"$v\" , \n");
        }
        
        fwrite($urlMap,");\n
        break;\n\n case \"category_map\" : \n\n \$arr = array( ");
        foreach($arrCategoryMap as $k=>$v){
            fwrite($urlMap," \"$k\" => \"$v\" , \n");
        }
        
        fwrite($urlMap,"); break;\n\n");
        fwrite($urlMap,"\ndefault:\n
				break;\n
			}\n
			if(\$returnArr)\n
				return \$arr;\n
			else\n
				return \$arr[\$value];\n
			}\n
		}\n
    ?>\n");
mysql_free_result($result);
