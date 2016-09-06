<?php
include("connect.inc");
//include("/usr/local/smarty/libs/Smarty.class.php");
//$smarty = New Smarty;
                                                                                 
$path = "/tmp";
                                                                                                                             
$acceptable_file_types = "text/csv";
$default_extension = ".csv";
if(authenticated($cid))
{
        if($CMDUpload)
        {
		$file_name = $_FILES["mainfile"]["name"];
		if(substr($file_name,-3,3)=="csv")
		{
			//$user=getname($cid);
			$filename=$path."/easybill.csv";
			$filename2=$path."/easybill-chd.csv";

			$fp=fopen($filename,"w+") or $flag_error=1;
			$fp1=fopen($filename2,"w+") or $flag_error=1;

			if($mainfile)
			{
				$file=$_FILES["mainfile"];
				$fp2 = fopen($file["tmp_name"],"rb") or $flag_error=1;
				$fcontent = fread($fp2,filesize($file["tmp_name"]));

				fputs($fp,$fcontent);

				fclose($fp);
				fclose($fp2);

				$fp=fopen($filename,"r") or  $flag_error=1;

				if($flag_error)//if the csv could not be uploaded to temp location
				{
				      $msg="The file could not be uploaded ";
					$msg .="&nbsp;&nbsp;";
					$msg .="<a href=\"uploadcsv.php?cid=$cid\">";
					$msg .="Upload again</a>";
					$smarty->assign("MSG",$msg);
					$smarty->display("jsadmin_msg.tpl");
					die;
				}
				else//successful upload of csv to a temporary location
				{
					$content=fgets($fp);
					$content=str_replace("\"","",$content);

					while(!feof($fp))
					{
						$content=fgets($fp);
						fputs($fp1,$content);
					}

					@@passthru("MysqlDbConstants::$mySqlPathRep \"\\r\" \"\" -- $filename2");

					$sql="LOAD DATA LOCAL INFILE '$filename2' REPLACE INTO TABLE billing.EASYBILL_TEMP FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\\n'";
					mysql_query_decide($sql) or die("$sql".mysql_error_js());

					@passthru("/bin/rm -f $filename2");
					$sql="SELECT * FROM billing.EASYBILL_TEMP";
					$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					while($row = mysql_fetch_array($res))
					{
						//change the transaction date separator and format to proper format.
						list($tx_dd,$tx_mm,$tx_yy) = explode("/",$row['TRANSACTION_DT']);
						$TRANSACTION_DT = $tx_yy."-".$tx_mm."-".$tx_dd;

						//change the cheque date separator and format to proper format.
						list($cd_dd,$cd_mm,$cd_yy) = explode(".",$row['CD_DT']);
						$CD_DT = $cd_yy."-".$cd_mm."-".$cd_dd;

						$CODE = addslashes(stripslashes($row['CODE']));
						$REF_ID = addslashes(stripslashes($row['REF_ID']));
						$RECT_ID = addslashes(stripslashes($row['RECT_ID']));
						$AMOUNT = addslashes(stripslashes($row['AMOUNT']));
						$CD_NUM = addslashes(stripslashes($row['CD_NUM']));
						$CD_CITY = addslashes(stripslashes($row['CD_CITY']));
						$BANK_NAME = addslashes(stripslashes($row['BANK_NAME']));
						$RETAILER_NAME = addslashes(stripslashes($row['RETAILER_NAME']));
						$CITY = addslashes(stripslashes($row['CITY']));

						//insert data into main table.
						$sql_ins = "INSERT INTO billing.EASY_BILL_RECEIPTS(
								CODE,
								REF_ID,
								RECT_ID,
								TRANSACTION_DT,
								AMOUNT,
								CD_NUM,
								CD_DT,
								CD_CITY,
								BANK_NAME,
								RETAILER_NAME,
								CITY
								)
								VALUES
								(
								'$CODE',
								'$REF_ID',
								'$RECT_ID',
								'$TRANSACTION_DT',
								'$AMOUNT',
								'$CD_NUM',
								'$CD_DT',
								'$CD_CITY ',
								'$BANK_NAME',
								'$RETAILER_NAME',
								'$CITY'
								)";
																				     
						mysql_query_decide($sql_ins) or die($sql.mysql_error_js());
					}
					$sql_trunc = "TRUNCATE TABLE billing.EASYBILL_TEMP";
					mysql_query_decide($sql_trunc) or die($sql_trunc.mysql_error_js());
					$MESSAGE =  mysql_num_rows($res)." Records Inserted.";
					$smarty->assign("MESSAGE",$MESSAGE);
					$smarty->assign("SUCCESS",1);
					$smarty->assign("cid",$cid);
					$smarty->display("easybill_uploadcsv.htm");
				}
			}
			else
			{
				$smarty->assign("MESSAGE","Pleas Select a file to Upload");
				$smarty->assign("ERROR",1);
				$smarty->assign("cid",$cid);
				$smarty->display("easybill_uploadcsv.htm");
			}
		}
		else
		{
			$smarty->assign("MESSAGE","Please Select a Valid file to Upload");
			$smarty->assign("ERROR",1);
			$smarty->assign("cid",$cid);
			$smarty->display("easybill_uploadcsv.htm");
		}
        }
        else
        {
                $smarty->assign("cid",$cid);
                $smarty->display("easybill_uploadcsv.htm");
        }
}
else
{
	$msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>

