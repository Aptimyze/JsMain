<?php
/*
This class will contain the functions to retrieve MIS for mass mailer system.
*/
class CreateMIS
{
	/**
	 * This function is used to retrieve MIS for the mailers between a specific date range.
	 * @param: $data- array containing keys from the submit form of MIS.
	 * return: $res-> associative array containing keys as sent, open and uns.
	*/
	public function mis_data($data)
	{
		$mis=new mmmjs_MIS;
		$mailers=new MmmMailerBasicInfo;
		$for=$data['site'];
		$mailer_ids=$mailers->retrieveAllMailers($for,'','N');
		$data['ids']=array_keys($mailer_ids);
		$res=$mis->getMIS($data);
		return $res;
	}

	/**
	* This function will retrieve the MIS for a particluar client or a particular Mailer.
	* @param mailer_id- the id of the mailer which is a unique key.
	* @return an array with arr[0]-> total sent mails. arr[1] an associative array containing date as the key and open rate as the value.
	*/
	public function getClientMIS($mailer_id,$showCount = '')
	{
		$clientMIS= new mmmjs_MIS;
		return $clientMIS->ClientMIS($mailer_id,$showCount);
	}
	public function unsubscribe($data)
	{
		$un= new mmmjs_MIS;
		$un->unsubscribe($data);
	}
	public function open($data)
	{
		$op= new mmmjs_MIS;
		$op->openCount($data);
	}
	public function openIndividual($data)
	{
		$opI= new mmmjs_MIS;
		$opI->openIndividualCount($data);
	}


	/**
	* This function will generate the csv report corresponding to a mailer.
	* @return file where csv path is stored.
	*/
	public function getCsv($data)
	{
		$arr=array();

		$where['MAILER_ID'] = $data['mailer_id'];
		$file = $data['filename'];

		if($data['filename']=="overall")
		{
			$mmmjs_MAIL_OPEN_AND_UNSUBSCRIBE = new mmmjs_MAIL_OPEN_AND_UNSUBSCRIBE;
			$fields = "DATE,GMAIL_OP,YAHOO_OP,HOTMAIL_OP,REDIFF_OP,OTHERS_OP,GMAIL_US,YAHOO_US,HOTMAIL_US,REDIFF_US,OTHERS_US,TOTAL_OPEN";
			$limit = $data['limit'];
			$arr = $mmmjs_MAIL_OPEN_AND_UNSUBSCRIBE->get($fields, $where, $limit);

			/////Code for Sent Mail Domain/////

            $sent_fields = "DATE,GMAIL_SENT,YAHOO_SENT,HOTMAIL_SENT,REDIFF_SENT,OTHERS_SENT";
            $sent_arr = $mmmjs_MAIL_OPEN_AND_UNSUBSCRIBE->get($sent_fields, $where, $limit,'','mmmjs.DOMAIN_SENT_DATA');

		/*if(!empty($arr))
		$fields = $fields.",GMAIL_SENT,YAHOO_SENT,HOTMAIL_SENT,REDIFF_SENT,OTHERS_SENT";
		else
		$fields = "DATE,GMAIL_SENT,YAHOO_SENT,HOTMAIL_SENT,REDIFF_SENT,OTHERS_SENT";*/


		foreach($sent_arr as $key=>$value){
            $flag = 0;
                    foreach($arr as $k=>&$v){
                        if($value['DATE'] == $v['DATE']){
                                $v['GMAIL_SENT'] = $value['GMAIL_SENT'];
                                $v['YAHOO_SENT'] = $value['YAHOO_SENT'];
                                $v['HOTMAIL_SENT'] = $value['HOTMAIL_SENT'];
                                $v['REDIFF_SENT'] = $value['REDIFF_SENT'];
                                $v['OTHERS_SENT'] = $value['OTHERS_SENT'];
                            $flag = 1;
                            break;
                        }
                    }

					if(!$flag){

                            $temp = array();
                            $temp['DATE'] = $value['DATE'];
                            $temp['GMAIL_OP'] = 0;
                            $temp['YAHOO_OP'] = 0;
                            $temp['HOTMAIL_OP'] = 0;
                            $temp['REDIFF_OP'] = 0;
                            $temp['OTHERS_OP'] = 0;
                            $temp['GMAIL_US'] = 0;
                            $temp['YAHOO_US'] = 0;
                            $temp['HOTMAIL_US'] = 0;
                            $temp['REDIFF_US'] = 0;
                            $temp['OTHERS_US'] = 0;
							$temp['TOTAL_OPEN'] = 0;
                            $temp['GMAIL_SENT'] = $value['GMAIL_SENT'];
                            $temp['YAHOO_SENT'] = $value['YAHOO_SENT'];
                            $temp['HOTMAIL_SENT'] = $value['HOTMAIL_SENT'];
                            $temp['REDIFF_SENT'] = $value['REDIFF_SENT'];
                            $temp['OTHERS_SENT'] = $value['OTHERS_SENT'];

                            $arr[] = $temp;

                    }

            }

			$fields = $fields.",GMAIL_SENT,YAHOO_SENT,HOTMAIL_SENT,REDIFF_SENT,OTHERS_SENT";
        
            ///////////////////////////////////

		}
		else
		{
			$mmmjs_MAIL_OPEN_INDIVIDUAL = new mmmjs_MAIL_OPEN_INDIVIDUAL;
			$fields = "DATE,OPEN_COUNT,EMAIL,TIME";
			$limit = $data['limit'];
			$arr = $mmmjs_MAIL_OPEN_INDIVIDUAL->get($fields, $where, $limit);			
		}
		$uploadsPath = $this->getCsvMisPath($file,$data["mailer_id"]);
		$df = fopen(JsConstants::$docRoot.$uploadsPath, 'w');
		$f=explode(",",$fields);
		fputcsv($df, $f);
		foreach($arr as $row)
		{
			$res=array();
			foreach($row as $r)
				$res[]=$r;
			fputcsv($df, $res);
		}
		fclose($df);
		return $uploadsPath;
	}

	/**
	* This function will the csv path where mis data will be stored temporary.
	* @return path of csv.
	*/
	private function getCsvMisPath($file,$mailerId)
	{
		$path = "/uploads/mmm/".$file."-".$mailerId.".csv";
		return $path;
	}

	public function getMis99Data($startDate,$endDate)
    {
        $obj = new mmmjs_MIS;
        $mailerDataArr = $obj -> fetchMis99Data($startDate,$endDate);
        return $mailerDataArr;
    }

}
?>
