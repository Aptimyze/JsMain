<?php

class incentive_SUGARCRM_LTF_CSV_DATA extends TABLE
{
	public function __construct($dbname="")
	{
  		parent::__construct($dbname);
   	}

	public function insertProfile($leadid,$lead_name,$age,$gender,$height,$marital_status,$religion,$mother_tongue,$caste,$education,$occupation,$income,$manglik,$phone_no1,$phone_no2,$campaign_source,$lead_source,$enquirer_name,$email,$campaign_username,$campaign_description,$campaign_newspaper,$campaign_newspaper_date,$campaign_edition,$campaign_emailid,$campaign_mobile,$priority,$username,$password,$ent_date,$isDNC,$csv_type)
	{
		try
		{
			$csv_entry_date=date("Y-m-d",time());
			$sql= "INSERT ignore INTO incentive.SUGARCRM_LTF_CSV_DATA(LEAD_ID,LEAD_NAME,AGE,GENDER,HEIGHT,MARITAL_STATUS,RELIGION,MOTHER_TONGUE,CASTE,EDUCATION,OCCUPATION,INCOME,MANGLIK,PHONE_NO1,PHONE_NO2,CAMPAIGN_SOURCE,LEAD_SOURCE,ENQUIRER_NAME,EMAIL,CAMPAIGN_USERNAME,CAMPAIGN_DESCRIPTION,CAMPAIGN_NEWSPAPER,CAMPAIGN_NEWSPAPER_DATE,CAMPAIGN_EDITION,CAMPAIGN_EMAILID,CAMPAIGN_MOBILE,PRIORITY,USERNAME,PASSWORD,ENTRY_DATE,IS_DNC,CSV_TYPE,CSV_ENTRY_DATE) VALUES(:LEAD_ID,:LEAD_NAME,:AGE,:GENDER,:HEIGHT,:MARITAL_STATUS,:RELIGION,:MOTHER_TONGUE,:CASTE,:EDUCATION,:OCCUPATION,:INCOME,:MANGLIK,:PHONE_NO1,:PHONE_NO2,:CAMPAIGN_SOURCE,:LEAD_SOURCE,:ENQUIRER_NAME,:EMAIL,:CAMPAIGN_USERNAME,:CAMPAIGN_DESCRIPTION,:CAMPAIGN_NEWSPAPER,:CAMPAIGN_NEWSPAPER_DATE,:CAMPAIGN_EDITION,:CAMPAIGN_EMAILID,:CAMPAIGN_MOBILE,:PRIORITY,:USERNAME,:PASSWORD,:ENTRY_DATE,:IS_DNC,:CSV_TYPE,:CSV_ENTRY_DATE)";

			$prep = $this->db->prepare($sql);

			$prep->bindValue(":LEAD_ID",$leadid,PDO::PARAM_STR);
			$prep->bindValue(":LEAD_NAME",$lead_name,PDO::PARAM_STR);
			$prep->bindValue(":AGE",$age,PDO::PARAM_INT);
			$prep->bindValue(":GENDER",$gender,PDO::PARAM_STR);
			$prep->bindValue(":HEIGHT",$height,PDO::PARAM_INT);
			$prep->bindValue(":MARITAL_STATUS",$marital_status,PDO::PARAM_STR);
			$prep->bindValue(":RELIGION",$religion,PDO::PARAM_STR);
			$prep->bindValue(":MOTHER_TONGUE",$mother_tongue,PDO::PARAM_STR);
			$prep->bindValue(":CASTE",$caste,PDO::PARAM_STR);
			$prep->bindValue(":EDUCATION",$education,PDO::PARAM_STR);
			$prep->bindValue(":OCCUPATION",$occupation,PDO::PARAM_STR);
			$prep->bindValue(":INCOME",$income,PDO::PARAM_INT);
			$prep->bindValue(":MANGLIK",$manglik,PDO::PARAM_STR);
			$prep->bindValue(":PHONE_NO1",$phone_no1,PDO::PARAM_STR);
			$prep->bindValue(":PHONE_NO2",$phone_no2,PDO::PARAM_STR);
			$prep->bindValue(":CAMPAIGN_SOURCE",$campaign_source,PDO::PARAM_STR);
			$prep->bindValue(":LEAD_SOURCE",$lead_source,PDO::PARAM_STR);
			$prep->bindValue(":ENQUIRER_NAME",$enquirer_name,PDO::PARAM_STR);
			$prep->bindValue(":EMAIL",$email,PDO::PARAM_STR);
			$prep->bindValue(":CAMPAIGN_USERNAME",$campaign_username,PDO::PARAM_STR);
			$prep->bindValue(":CAMPAIGN_DESCRIPTION",$campaign_description,PDO::PARAM_STR);
			$prep->bindValue(":CAMPAIGN_NEWSPAPER",$campaign_newspaper,PDO::PARAM_STR);
			$prep->bindValue(":CAMPAIGN_NEWSPAPER_DATE",$campaign_newspaper_date,PDO::PARAM_STR);
			$prep->bindValue(":CAMPAIGN_EDITION",$campaign_edition,PDO::PARAM_STR);
			$prep->bindValue(":CAMPAIGN_EMAILID",$campaign_emailid,PDO::PARAM_STR);
			$prep->bindValue(":CAMPAIGN_MOBILE",$campaign_mobile,PDO::PARAM_STR);
			$prep->bindValue(":PRIORITY",$priority,PDO::PARAM_INT);
			$prep->bindValue(":USERNAME",$username,PDO::PARAM_STR);
			$prep->bindValue(":PASSWORD",$password,PDO::PARAM_STR);
			$prep->bindValue(":ENTRY_DATE",$ent_date,PDO::PARAM_STR);
			$prep->bindValue(":IS_DNC",$isDNC,PDO::PARAM_INT);
			$prep->bindValue(":CSV_TYPE",$csv_type,PDO::PARAM_STR);
			$prep->bindValue(":CSV_ENTRY_DATE",$csv_entry_date,PDO::PARAM_STR);

			$prep->execute();
		}
		catch(Exception $e)
		{
        		throw new jsException($e);
		}       
	}

        
	public function removeProfiles($csvEntryDate)
	{
		try
		{
			$sql="DELETE FROM incentive.SUGARCRM_LTF_CSV_DATA WHERE CSV_ENTRY_DATE<:CSV_ENTRY_DATE";
			$prep=$this->db->prepare($sql);
			$prep->bindValue(":CSV_ENTRY_DATE",$csvEntryDate,PDO::PARAM_STR);
			$prep->execute();
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}


	public function getData($date,$csv_type)
        {
       		try
        	{
			ini_set('memory_limit', '256M');
        		$MT = csvFields::$csvMotherTongueArr;
	        	$DNC = csvFields::$csvDNCvalues;

        		$typeArr = explode("_", $csv_type);
        		$dnc_val = $typeArr[0];
			$dnc = $DNC[$dnc_val];
			$mother_tongue = $typeArr[1];
        	    	$sql="SELECT * FROM incentive.SUGARCRM_LTF_CSV_DATA WHERE CSV_ENTRY_DATE = :ENTRY_DT AND CSV_TYPE=:MOTHER_TONGUE AND IS_DNC=:DNC ORDER BY ENTRY_DATE DESC";
            	    	$prep=$this->db->prepare($sql);
		        $prep->bindValue(":ENTRY_DT",$date,PDO::PARAM_STR);
		        $prep->bindValue(":MOTHER_TONGUE",$mother_tongue,PDO::PARAM_STR);
		        $prep->bindValue(":DNC",$dnc,PDO::PARAM_STR);
	                $prep->execute();

            		$i=0;
		        while($res=$prep->fetch(PDO::FETCH_ASSOC))
            		{
	                    $data[$i]=$res;
        	            $i++;
           		}	
        	}
        	catch(Exception $e)
        	{
        	    throw new jsException($e);
        	}
        	return $data;
    	}

	public function getMobileLeadData($date)
	{
		try{
			$dnc ='Y';
			$date1 =$date." 00:00:00";
			$date2 =$date." 23:59:59";
                        $sql="SELECT * FROM incentive.SUGARCRM_LTF_CSV_DATA WHERE CSV_ENTRY_DATE = :ENTRY_DT AND ENTRY_DATE>=:ENTRY_DATE1 AND ENTRY_DATE<=:ENTRY_DATE2 AND IS_DNC=:DNC ORDER BY ENTRY_DATE DESC";
                        $prep=$this->db->prepare($sql);
                        $prep->bindValue(":ENTRY_DT",$date,PDO::PARAM_STR);
			$prep->bindValue(":ENTRY_DATE1",$date1,PDO::PARAM_STR);
			$prep->bindValue(":ENTRY_DATE2",$date2,PDO::PARAM_STR);		
		
                        $prep->bindValue(":DNC",$dnc,PDO::PARAM_STR);
                        $prep->execute();
                        while($res=$prep->fetch(PDO::FETCH_ASSOC)){
                            $data[]=$res;
                        }
                }
                catch(Exception $e){
                    throw new jsException($e);
                }
                return $data;
	}

}
?>
