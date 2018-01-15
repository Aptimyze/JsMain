<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

        chdir(dirname(__FILE__));
        include("../connect.inc");
        $SITE_URL="http://www.jeevansathi.com";

        $db = connect_slave();

        //define header to write into csv file.
        $header="\"PROFILEID\"".","."\"DISCOUNT\"".","."\"START DATE\"".","."\"END DATE\"".","."\"USERNAME\"".","."\"REGISTERED DATE\"".","."\"PHOTO\"".","."\"PHONE VERIFIED\"".","."\"INCOMPLETE\"".","."\"MEMBERSHIP\"\n";

        $filename = "$_SERVER[DOCUMENT_ROOT]/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_bengali_banner.txt";

        $fp = fopen($filename,"w+");

        if(!$fp)
        {
                die("no file pointer");
        }

        fwrite($fp,$header);

        $sqlj="SELECT PROFILEID,USERNAME,ENTRY_DT,HAVEPHOTO,MOB_STATUS,LANDL_STATUS,INCOMPLETE,SUBSCRIPTION FROM newjs.JPROFILE WHERE MTONGUE='6' AND ENTRY_DT>='2012-04-12' AND ENTRY_DT<'2012-04-20'";
        $resj=mysql_query($sqlj,$db) or die(mysql_error());
        while($rowj = mysql_fetch_array($resj))
        {
		$profileid = $rowj["PROFILEID"];
                $username = $rowj["USERNAME"];
                $entry_dt = date('Y-m-d',strtotime($rowj["ENTRY_DT"]));
		$start_dt = date('Y-m-d',strtotime($rowj["ENTRY_DT"])+1*86400);
		$end_dt = date('d-m-Y',strtotime($rowj["ENTRY_DT"])+11*86400);
                if($rowj["HAVEPHOTO"] == 'Y')
                        $havephoto = 'Yes';
                else
                        $havephoto = 'No';
                if($rowj["MOB_STATUS"]=='Y' || $rowj["LANDL_STATUS"]=='Y')
                        $phone_status = 'Yes';
                else
                        $phone_status = 'No';
                if($rowj["INCOMPLETE"] == 'Y')
			$incomplete = 'Yes';
                else
                        $incomplete = 'No';
                if($rowj["SUBSCRIPTION"]!='')
                        $membership = 'Paid';
                else
                        $membership = 'Free';
                $line="\"$profileid\"".","."\"0\"".","."\"$start_dt\"".","."\"$end_dt\"".","."\"$username\"".","."\"$entry_dt\"".","."\"$havephoto\"".","."\"$phone_status\"".","."\"$incomplete\"".","."\"$membership\"";
                $data = trim($line)."\n";
                $output = $data;
                unset($data);
                unset($DPP);
                fwrite($fp,$output);
        }
        fclose($fp);

        $profileid_file = $SITE_URL."/crm/csv_files/bulk_csv_crm_data_".date('Y-m-d')."_bengali_banner.txt";

        $msg="Bengali Profiles: ".$profileid_file;

	$to="sriparna.bose@jeevansathi.com,kumarika.b@jeevansathi.com,manish.raj@jeevansathi.com,vijay.bhaskar@jeevansathi.com,rohit.manghnani@jeevansathi.com,devika.khanna@jeevansathi.com,anant.gupta@naukri.com";
        $bcc="vibhor.garg@jeevansathi.com";
        $sub="Pilot - Bengali Free Trial Offer - Banner CSV";
        $from="From:vibhor.garg@jeevansathi.com";
        $from .= "\r\nBcc:$bcc";
        mail($to,$sub,$msg,$from);
?>
