<?php
/**
 * store class for MAIL_SENT, MAIL_OPEN and MAIL_UNSUBSCRIBE
*/
class mmmjs_MIS extends TABLE
{
	public function  __construct($dbname="matchalerts_slave_localhost")
	{
		parent::__construct($dbname);
	}

	/**
	* To retrieve the MIS for mailers within a date range
	* @PARAM $data: contains the field specified in the form for MIS.
	* return $arr as an associated array of the form $arr[mailer_id]=>(sent[],open[],uns[]);
	*/
	public function getMIS($data)
	{
		$sql1="";
		$sql2="";
		try
		{
			$arr=array();

			/**
			** TO CREATE QUERY FOR MONTHWISE MIS FOR A YEAR SPECIFIED IN $DATA
			*/ 
			if($data['dt_type']=="mnt")
			{
				if(array_key_exists('sent',$data))
				{
					$sql1.="SELECT MAILER_ID ,SUM(SENT) AS SENT, MONTH(DATE) AS MONTH FROM mmmjs.MAIL_SENT_NEW WHERE YEAR(DATE)=:date ";
				}
				if(array_key_exists('open',$data)&& array_key_exists('unsubscribe',$data))
				{
					$sql2.="SELECT MAILER_ID, SUM(OPEN_COUNT) AS OPEN, SUM(UN_COUNT) AS UNSUBSCRIBE, MONTH(DATE) AS MONTH FROM mmmjs.MAIL_UNSUBSCRIBE_NEW WHERE YEAR(DATE)=:date ";
				}
				else if(array_key_exists('open',$data))
				{
					$sql2.="SELECT MAILER_ID, SUM(OPEN_COUNT) AS OPEN, MONTH(DATE) AS MONTH FROM mmmjs.MAIL_UNSUBSCRIBE_NEW WHERE YEAR(DATE)=:date ";
				}
				else if(array_key_exists('unsubscribe',$data))
				{
					$sql2.="SELECT MAILER_ID, SUM(UN_COUNT) AS UNSUBSCRIBE, MONTH(DATE) AS MONTH FROM mmmjs.MAIL_UNSUBSCRIBE_NEW WHERE YEAR(DATE)=:date ";
				}
				/**
				 * IF SENT IS SET IN THE FORM FOR MIS
				 */
				if($sql1)
				{
					if($data['mailer_id'])
						$sql1.="AND MAILER_ID=:id";
					else
					{
						foreach($data['ids'] as $k =>$v)
                                                	$valueArr[]=":v".$k;
                                        	$str = implode(",",$valueArr);
						$sql1.="AND MAILER_ID IN(".($str).")";
					}
					$sql1.=" GROUP BY MONTH, MAILER_ID";
					$res1=$this->db->prepare($sql1);
					$res1->bindValue(":date",$data['years_m'], PDO::PARAM_INT);
					if($data['mailer_id'])
						$res1->bindValue(":id",$data['mailer_id'], PDO::PARAM_INT);
					else
					{
						 foreach($data['ids'] as $k =>$v)
						 	$res1->bindValue(":v".$k,$v, PDO::PARAM_INT);
					}
					$res1->execute();
					while($row=$res1->fetch(PDO::FETCH_ASSOC))
					{
						$arr[$row['MAILER_ID']]["sent"][$row['MONTH']]=$row['SENT'];
					}
				}
				/** 
				 * IF EITHER OPEN OR UNSUBSCRIBE IS SET IN THE FORM FOR MIS
				 */ 
				if($sql2)
				{
					if($data['mailer_id'])
						$sql2.="AND MAILER_ID=:id";
					else
					{
						foreach($data['ids'] as $k =>$v)
                                                        $valueArr[]=":v".$k;
                                                $str = implode(",",$valueArr);
						$sql2.="AND MAILER_ID IN(".($str).")";
					}
					$sql2.=" GROUP BY MONTH, MAILER_ID";
					$res2=$this->db->prepare($sql2);
					$res2->bindValue(":date",$data['years_m'], PDO::PARAM_INT);
					if($data['mailer_id'])
						$res2->bindValue(":id",$data['mailer_id'], PDO::PARAM_INT);
					else
                                        {
                                                 foreach($data['ids'] as $k =>$v)
                                                        $res2->bindValue(":v".$k,$v, PDO::PARAM_INT);
                                        }

					$res2->execute();
					while($row=$res2->fetch(PDO::FETCH_ASSOC))
					{
						if(array_key_exists('OPEN',$row))
							$arr[$row['MAILER_ID']]["open"][$row['MONTH']]=$row['OPEN'];
						if(array_key_exists('UNSUBSCRIBE',$row))
							$arr[$row['MAILER_ID']]["uns"][$row['MONTH']]=$row['UNSUBSCRIBE'];
					}					
				}				
			}
			/**
			 * TO CREATE QUERY FOR DAYWISE MIS FOR A YEAR AND A CORRESPONDING MONTH SPECIFIED IN $DATA
			 */ 
			else if($data['dt_type']=="day")
			{
				if(array_key_exists('sent',$data))
				{
					$sql1.="SELECT MAILER_ID ,SUM(SENT) AS SENT, DAY(DATE) AS DAY FROM mmmjs.MAIL_SENT_NEW WHERE YEAR(DATE)=:y AND MONTH(DATE)=:m ";
				}
				if(array_key_exists('open',$data)&& array_key_exists('unsubscribe',$data))
				{
					$sql2.="SELECT MAILER_ID, SUM(OPEN_COUNT) AS OPEN, SUM(UN_COUNT) AS UNSUBSCRIBE, DAY(DATE) AS DAY FROM mmmjs.MAIL_UNSUBSCRIBE_NEW WHERE YEAR(DATE)=:y AND MONTH(DATE)=:m ";
				}
				else if(array_key_exists('open',$data))
				{
					$sql2.="SELECT MAILER_ID, SUM(OPEN_COUNT) AS OPEN, DAY(DATE) AS DAY FROM mmmjs.MAIL_UNSUBSCRIBE_NEW WHERE YEAR(DATE)=:y AND MONTH(DATE)=:m ";
				}
				else if(array_key_exists('unsubscribe',$data))
				{
					$sql2.="SELECT MAILER_ID, SUM(UN_COUNT) AS UNSUBSCRIBE, DAY(DATE) AS DAY FROM mmmjs.MAIL_UNSUBSCRIBE_NEW WHERE YEAR(DATE)=:y AND MONTH(DATE)=:m ";
				}
				if($sql1)
				{
					if($data['mailer_id'])
						$sql1.="AND MAILER_ID=:id";
					else
					{
						foreach($data['ids'] as $k =>$v)
                                                        $valueArr[]=":v".$k;
                                                $str = implode(",",$valueArr);
						$sql1.="AND MAILER_ID IN(".($str).")";
					}						
					$sql1.=" GROUP BY DAY, MAILER_ID";
					$res1=$this->db->prepare($sql1);
					$res1->bindValue(":y",$data['years_d'], PDO::PARAM_INT);
					$res1->bindValue(":m",$data['months'], PDO::PARAM_INT);
					if($data['mailer_id'])
						$res1->bindValue(":id",$data['mailer_id'], PDO::PARAM_INT);	
					else
                                        {
                                                 foreach($data['ids'] as $k =>$v)
                                                        $res1->bindValue(":v".$k,$v, PDO::PARAM_INT);
                                        }
				
					$res1->execute();
					while($row=$res1->fetch(PDO::FETCH_ASSOC))
					{
						$arr[$row['MAILER_ID']]["sent"][$row['DAY']]=$row['SENT'];
					}
				}
				if($sql2)
				{
					if($data['mailer_id'])
						$sql2.="AND MAILER_ID=:id";
					else
					{
						foreach($data['ids'] as $k =>$v)
                                                        $valueArr[]=":v".$k;
                                                $str = implode(",",$valueArr);
						$sql2.="AND MAILER_ID IN(".($str).")";
					}						
					$sql2.=" GROUP BY DAY, MAILER_ID";
					$res2=$this->db->prepare($sql2);
					$res2->bindValue(":y",$data['years_d'], PDO::PARAM_INT);
					$res2->bindValue(":m",$data['months'], PDO::PARAM_INT);
					if($data['mailer_id'])
						$res2->bindValue(":id",$data['mailer_id'], PDO::PARAM_INT);
					else
                                        {
                                                 foreach($data['ids'] as $k =>$v)
                                                        $res2->bindValue(":v".$k,$v, PDO::PARAM_INT);
                                        }

					$res2->execute();
					while($row=$res2->fetch(PDO::FETCH_ASSOC))
					{
						if(array_key_exists('OPEN',$row))
							$arr[$row['MAILER_ID']]["open"][$row['DAY']]=$row['OPEN'];
						if(array_key_exists('UNSUBSCRIBE',$row))
							$arr[$row['MAILER_ID']]["uns"][$row['DAY']]=$row['UNSUBSCRIBE'];
					}					
				}				
			}
			return $arr;
		}
		catch(PDOException $e)
        	{	
			throw new jsException($e);
	        }	
	}
	/**
	 * To get the MIS for a particular client and hence a particular mailer_id.
	 * @param $mailer_id
	 * return $arr as an array containing two arrays $sent and $open.
	 * $sent contains the total mails sent for the mailer and $open contains datewise MIS for the same mailer.
	 */
	public function ClientMIS($mailer_id,$showCount = '')
	{
		$sql1="SELECT DATE, OPEN_COUNT AS OPEN FROM mmmjs.MAIL_UNSUBSCRIBE_NEW WHERE MAILER_ID=:id1";
		$res1 = $this->db->prepare($sql1);
		$res1->bindValue(":id1", $mailer_id);
		$res1->execute();
		$arr=array();
		while($row = $res1->fetch(PDO::FETCH_ASSOC))
		{
			$open[$row['DATE']]=$row['OPEN'];
		}
		$sql2="SELECT SUM(SENT) AS SENT FROM mmmjs.MAIL_SENT_NEW WHERE MAILER_ID=:id2";
		$res2 = $this->db->prepare($sql2);
		$res2->bindValue(":id2", $mailer_id);
		$res2->execute();		
		$sent=$res2->fetch(PDO::FETCH_ASSOC);

		if($showCount == '1'){
			$sql3 = "SELECT count(*) as cnt FROM mmmjs.".$mailer_id."mailer";
			$res3 = $this->db->prepare($sql3);
			$res3->execute();
			$row3 = $res3->fetch(PDO::FETCH_ASSOC); 
			$mailsToBeSent = $row3['cnt'];
		}

		$arr=array();
		$arr[0]=$sent;
		$arr[1]=$open;
		if($showCount == '1') $arr[2]=$mailsToBeSent;

		return $arr;
	}
	public function unsubscribe($data)
	{
		$sql="INSERT INTO mmmjs.MAIL_UNSUBSCRIBE_NEW (DATE, MAILER_ID, UN_COUNT) VALUES (:d, :m, :u) ON DUPLICATE KEY UPDATE UN_COUNT= UN_COUNT+1";
		$res = $this->db->prepare($sql);
		$res->bindValue(":d",$data[date]);
		$res->bindValue(":m",$data[mailer_id]);
		$res->bindValue(":u",1,PDO::PARAM_INT);
		$res->execute(); 
		$sql="INSERT INTO mmmjs.MAIL_OPEN_AND_UNSUBSCRIBE (DATE, MAILER_ID, TOTAL_UNSUBSCRIBE) VALUES (:d, :m, :u) ON DUPLICATE KEY UPDATE TOTAL_UNSUBSCRIBE= TOTAL_UNSUBSCRIBE+1";
		$res = $this->db->prepare($sql);
		$res->bindValue(":d",$data[date]);
		$res->bindValue(":m",$data[mailer_id]);
		$res->bindValue(":u",1,PDO::PARAM_INT);
		$res->execute(); 
	}
	public function openCount($data)
	{
		$sql="INSERT INTO mmmjs.MAIL_UNSUBSCRIBE_NEW (DATE, MAILER_ID, OPEN_COUNT) VALUES (:d, :m, :o) ON DUPLICATE KEY UPDATE OPEN_COUNT= OPEN_COUNT+1";
		$res = $this->db->prepare($sql);
		$res->bindValue(":d",$data[date]);
		$res->bindValue(":m",$data[mailer_id]);
		$res->bindValue(":o",1,PDO::PARAM_INT);
		$res->execute();
		$sql="INSERT INTO mmmjs.MAIL_OPEN_AND_UNSUBSCRIBE (DATE, MAILER_ID, TOTAL_OPEN, ";
		if($data['domain']=="G")
		{
				$sql.="GMAIL_OP)";
				$str="GMAIL_OP";
		}
	    else if($data['domain']=="Y")
		{
				$sql.="YAHOO_OP)";
				$str="YAHOO_OP";
		}
		else if($data['domain']=="H")
		{
				$sql.="HOTMAIL_OP)";
				$str="HOTMAIL_OP";
		}
	    else if($data['domain']=="R")
		{
				$sql.="REDIFF_OP)";
				$str="REDIFF_OP";
		}
	    else
		{
				$sql.="OTHERS_OP)";
				$str="OTHERS_OP";
		}
		$sql.="VALUES (:d, :m, :o, :dom) ON DUPLICATE KEY UPDATE TOTAL_OPEN= TOTAL_OPEN+1 , ". $str."= ".$str."+1";
		$res = $this->db->prepare($sql);
		$res->bindValue(":d",$data[date]);
		$res->bindValue(":m",$data[mailer_id]);
		$res->bindValue(":o",1,PDO::PARAM_INT);
		$res->bindValue(":dom",1,PDO::PARAM_INT);
		$res->execute();
	}
	public function openIndividualCount($data)
	{
		$sql="INSERT INTO mmmjs.MAIL_OPEN_INDIVIDUAL_NEW (DATE, MAILER_ID, OPEN_COUNT, EMAIL, TIME) VALUES (:d, :m, :o, :e, :t) ON DUPLICATE KEY UPDATE OPEN_COUNT= OPEN_COUNT+1,TIME=:t";
		$res = $this->db->prepare($sql);
		$res->bindValue(":d",$data[date]);
		$res->bindValue(":m",$data[mailer_id]);
		$res->bindValue(":o",1,PDO::PARAM_INT);
		$res->bindValue(":e",$data[email]);
		$res->bindValue(":t",$data[time]);
		$res->execute(); 
	}

	public function fetchMis99Data($startDate,$endDate)
    {

		/*** IST --> EST ***/
        $startDate = date('Y-m-d H:i:s', strtotime($startDate)-37800);
        $endDate = date('Y-m-d H:i:s', strtotime($endDate)-37800);

        $sql = "SELECT RTIME AS START_TIME,MAILER_ID,MAILER_NAME,RESPONSE_TYPE AS MAILER_TYPE FROM mmmjs.MAIN_MAILER_NEW where MAILER_FOR = '9' AND (RTIME BETWEEN '$startDate' AND '$endDate') order by RTIME asc";
        $res = $this->db->prepare($sql);
        $res->execute();


        $mailerIdList = '';
        $mailerDataArr = array();
        while($row = $res->fetch(PDO::FETCH_ASSOC))
        {
            $mailerIdList .= $row['MAILER_ID'].',';
			$mailerDataArr[$row['MAILER_ID']] = array('MAILER_NAME' => '-','MAILER_TYPE' => '-','F_EMAIL' => '-','START_TIME' => '-','RECEIVER' => '-','F_EMAIL' => '-','SUBJECT' => '-','TARGET_CITY' => '-','SENT' => '-','TOTAL_OPEN' => '-','OPEN_RATE' => '-','TOTAL_UNSUBSCRIBE' => '-','RESPONSE' => '-','BROWSERURL' => '-');
            $mailerDataArr[$row['MAILER_ID']]['MAILER_NAME'] = $row['MAILER_NAME'];
            $mailerDataArr[$row['MAILER_ID']]['START_TIME'] = $row['START_TIME'];
            $mailerDataArr[$row['MAILER_ID']]['MAILER_TYPE'] = $row['MAILER_TYPE'];
        }

		if($mailerIdList == '') return $mailerDataArr;

        $mailerIdList = trim($mailerIdList,',');
        $sql = "SELECT MAILER_ID,SUBJECT,BROWSERURL,F_EMAIL from mmmjs.MAIL_DATA_NEW where MAILER_ID IN ($mailerIdList)";
        $res = $this->db->prepare($sql);
        $res->execute();

        while($row = $res->fetch(PDO::FETCH_ASSOC))
        {

            $mailerDataArr[$row['MAILER_ID']]['SUBJECT'] = $row['SUBJECT'];
            $mailerDataArr[$row['MAILER_ID']]['BROWSERURL'] = $row['BROWSERURL'];
			$mailerDataArr[$row['MAILER_ID']]['F_EMAIL'] = $row['F_EMAIL'];

        }

		$sql = "SELECT MAILER_ID,RECIPIENT_TYPE,COALESCE(BUYER_PROP_CITY,SELLER_PROP_CITY) AS TARGET_CITY FROM mmmjs.MAILER_SPECS_99 where MAILER_ID IN ($mailerIdList)";

        $res = $this->db->prepare($sql);
        $res->execute();

		while($row = $res->fetch(PDO::FETCH_ASSOC))
        {
            $mailerDataArr[$row['MAILER_ID']]['RECEIVER'] = $row['RECIPIENT_TYPE'];
			$mailerDataArr[$row['MAILER_ID']]['TARGET_CITY'] = $row['TARGET_CITY'];
        }

        $sql = "SELECT MAILER_ID,SUM(SENT) AS SENT FROM mmmjs.MAIL_SENT_NEW where MAILER_ID IN ($mailerIdList) GROUP BY MAILER_ID";
        $res = $this->db->prepare($sql);
        $res->execute();

        while($row = $res->fetch(PDO::FETCH_ASSOC))
        {
            $mailerDataArr[$row['MAILER_ID']]['SENT'] = $row['SENT'];
        }

        $sql = "select MAILER_ID,SUM(TOTAL_OPEN) AS TOTAL_OPEN,SUM(TOTAL_UNSUBSCRIBE) AS TOTAL_UNSUBSCRIBE from mmmjs.MAIL_OPEN_AND_UNSUBSCRIBE where MAILER_ID IN ($mailerIdList) GROUP BY MAILER_ID";
        $res = $this->db->prepare($sql);
        $res->execute();

        while($row = $res->fetch(PDO::FETCH_ASSOC))
        {
            $mailerDataArr[$row['MAILER_ID']]['TOTAL_OPEN'] = $row['TOTAL_OPEN'];
            $mailerDataArr[$row['MAILER_ID']]['TOTAL_UNSUBSCRIBE'] = $row['TOTAL_UNSUBSCRIBE'];
        }

		$mmm99_RESPONSE = new mmm99_RESPONSE;
        $mailerDataArr = $mmm99_RESPONSE -> fetchResponse($mailerIdList,$mailerDataArr);
	
		$cityMapArr = $mmm99_RESPONSE -> mapCityIdToCity();

		foreach($mailerDataArr as $mailerId => $mailerData)
        {
            if($mailerData['SENT'] != '0' && $mailerData['SENT'] != '-' && $mailerData['TOTAL_OPEN'] != '-')
            $mailerDataArr[$mailerId]['OPEN_RATE'] = round(($mailerData['TOTAL_OPEN'] / $mailerData['SENT']) * 100,2);

            $cityIds = $mailerDataArr[$mailerId]['TARGET_CITY'];
            if($cityIds != NULL){

                $cityArr = explode(',',$cityIds);$mailerDataArr[$mailerId]['TARGET_CITY'] = '';
                foreach($cityArr as $cityId) $mailerDataArr[$mailerId]['TARGET_CITY'] .= $cityMapArr[$cityId].',';
                $mailerDataArr[$mailerId]['TARGET_CITY'] = trim($mailerDataArr[$mailerId]['TARGET_CITY'],',');
            }
            else $mailerDataArr[$mailerId]['TARGET_CITY'] = 'PAN-India';

            /*** EST --> IST  JS server time is behind IST ***/
            $mailerDataArr[$mailerId]['START_TIME'] = date('Y-m-d H:i:s', strtotime($mailerDataArr[$mailerId]['START_TIME'])+37800);
        }


        return $mailerDataArr;

	}
}
?>
