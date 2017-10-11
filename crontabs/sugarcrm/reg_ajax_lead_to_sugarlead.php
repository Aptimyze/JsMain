<?php
$fromCron=1;
//INCLUDE FILES HERE
  $curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");
define('sugarEntry',true);
$path=$_SERVER[DOCUMENT_ROOT];
$fromCrontab=1;
require_once("$path/profile/connect.inc");
require_once("$path/sugarcrm/include/utils/Jscreate_lead.php");
$db_js=connect_db();
$date=date('Y-m-d',JSstrToTime("-1 days"));
$start=$date." 00:00:00";
$end=$date." 23:59:59";

$sql="select * from MIS.MINI_REG_AJAX_LEAD where DATE BETWEEN '$start' AND '$end' AND CONVERTED='N'";

//$sql="select * from MIS.MINI_REG_AJAX_LEAD where CONVERTED='N' AND DATE>='2012-01-01 00:00:00'";
$res=mysql_query_decide($sql,$db_js) or send_email("nikhil.dhiman@jeevansathi.com,nitesh.s@jeevansathi.com","Problem in running ajax_reg_lead_to_sugarlead at line 12","Problem in ajaxreglead to sugarlead cron");
while($row=mysql_fetch_assoc($res)){
mysql_select_db("newjs",$db_js);
        $createFlag=1;
        if(checkemail($row["EMAIL"]))
                $createFlag=0;
        if($createFlag && checkphone($row["MOBILE"]))
                $createFlag=0;
        if($createFlag)
        {
                $ldata=array();
                $ldata['last_name']=$row['EMAIL'];
                $ldata['email']=$row['EMAIL'];
                $ldata['mobile1']=$row['MOBILE'];
                $ldata['status']='13';
                $ldata['disposition_c']='24';
                $ldata['checkJprofile']='1';
                $ldata['js_source_c']=$row['SOURCE'];
                if($row['SOURCE'] && $row['SOURCE']!="unknown"){
                        $sql_source="select GROUPNAME from MIS.SOURCE where SourceID='".$row['SOURCE']."'";

                        $res_source=mysql_query_decide($sql_source,$db_js) or send_email("jaiswal.amit@jeevansathi.com","Problem related to fetching source group","Problem in reglead to sugarlead cron");
                        $row_source=mysql_fetch_assoc($res_source);
                                        if(strpos($row_source['GROUPNAME'],"SEO") !==false || strpos($row_source['GROUPNAME'],"jeevansathi") !==false || strpos($row_source['GROUPNAME'],"unknown") !==false )
                                                $ldata['source_c']=18;
                                        elseif(strpos($row_source['GROUPNAME'],"mobiledirect") !==false)
                                                $ldata['source_c']=20;
                                        else
                                                $ldata['source_c']=19;
                }
                else
                                $ldata['source_c']=18;
mysql_select_db("sugarcrm",$db_js);
                jscreate_lead($ldata);
        }
}
send_email("nikhil.dhiman@jeevansathi.com,nitesh.s@jeevansathi.com","Success reg ajax lead","Success reg ajax lead");
?>
