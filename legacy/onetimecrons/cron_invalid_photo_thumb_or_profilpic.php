<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit',-1);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

//$dbM=mysql_connect("10.208.67.196","user","CLDLRTa9");
$dbM=mysql_connect("172.16.3.185:3306","localuser","Km7Iv80l");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$statement = "SELECT PROFILEID,ProfilePicUrl,ThumbailUrl FROM newjs.PICTURE_NEW WHERE UPDATED_TIMESTAMP>'2012-02-16' AND ORDERING=0";
$result = mysql_query($statement,$dbM) or die(mysql_error($statement));
while($row = mysql_fetch_array($result))
{
        $p=$row["ProfilePicUrl"];
        $t=$row["ThumbailUrl"];
        $profileid=$row["PROFILEID"];
        $flag=0;

        $img = @ImageCreateFromJpeg($p);
        if($img)
        {
        $imagesX=imagesx($img);
        $imagesY=imagesy($img);

        if($imagesY!=200 || $imagesX!=150)
                $flag=1;
        }

        $img = @ImageCreateFromJpeg($t);
        if($img)
        {
        $imagesX=imagesx($img);
        $imagesY=imagesy($img);

        if($imagesY!=60 || $imagesX!=60)
                $flag=1;
        }

        if($flag==1)
        {
                echo $profileid.",";
                $xx[]=$profileid;
        }
}
echo "\n\n\n";
echo $xStr=implode(",",$xx);
?>
