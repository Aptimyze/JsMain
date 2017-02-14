<?php
class jeevansathi_mailer_DAILY_MAILER_COUNT_LOG extends TABLE{
        public function __construct($dbname="")
        {
        	parent::__construct($dbname);
        }
	public function insertData($mailerKey,$totalCount,$sent,$bounced,$incomplete,$unsubscribe,$openRate,$entryDate)
	{
		try{
			$sqlInsert ="INSERT IGNORE INTO jeevansathi_mailer.DAILY_MAILER_COUNT_LOG (`ID`,`MAILER_KEY`,`TOTAL_COUNT`,`SENT`,`HARD_BOUNCES`,`INVALID_EMAIL`,`UNSUBSCRIBE`,`OPEN_RATE`,`ENTRY_DT`) VALUES ('',:MAILER_KEY,:TOTAL_COUNT,:SENT,:HARD_BOUNCES,:INCOMPLETE,:UNSUBSCRIBE,0,:ENTRY_DT)";
			$resInsert = $this->db->prepare($sqlInsert);
			$resInsert->bindValue(":MAILER_KEY",$mailerKey, PDO::PARAM_STR);
			$resInsert->bindValue(":TOTAL_COUNT",$totalCount, PDO::PARAM_INT);
			$resInsert->bindValue(":SENT",$sent, PDO::PARAM_INT);
			$resInsert->bindValue(":HARD_BOUNCES",$bounced, PDO::PARAM_INT);
			$resInsert->bindValue(":INCOMPLETE",$incomplete, PDO::PARAM_INT);
			$resInsert->bindValue(":UNSUBSCRIBE",$unsubscribe, PDO::PARAM_INT);
			$resInsert->bindValue(":ENTRY_DT",$entryDate, PDO::PARAM_STR);
			$resInsert->execute();
		}
                catch(PDOException $e){
                        throw new jsException($e);
                }
	}

	public function getID($mailerKey)
        {
                try{
			$sqlSelect ="SELECT ID FROM jeevansathi_mailer.DAILY_MAILER_COUNT_LOG WHERE MAILER_KEY=:MAILER_KEY ORDER BY ID DESC LIMIT 1";
                        $resSelect = $this->db->prepare($sqlSelect);
                        $resSelect->bindValue(":MAILER_KEY",$mailerKey, PDO::PARAM_STR);
                        $resSelect->execute();
			if($result = $resSelect->fetch(PDO::FETCH_ASSOC))
				return $result['ID'];
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }
	public function updateData($id,$totalCount,$sent,$bounced,$invalid_email,$unsubscribe)
        {
                try{
                        $sqlUpdate ="UPDATE jeevansathi_mailer.DAILY_MAILER_COUNT_LOG SET TOTAL_COUNT=:TOTAL_COUNT,SENT=:SENT,HARD_BOUNCES=:HARD_BOUNCES,INVALID_EMAIL=:INVALID_EMAIL,UNSUBSCRIBE=:UNSUBSCRIBE WHERE ID=:ID";
                        $resUpdate = $this->db->prepare($sqlUpdate);
                        $resUpdate->bindValue(":TOTAL_COUNT",$totalCount, PDO::PARAM_INT);
                        $resUpdate->bindValue(":SENT",$sent, PDO::PARAM_INT);
                        $resUpdate->bindValue(":HARD_BOUNCES",$bounced, PDO::PARAM_INT);
                        $resUpdate->bindValue(":INVALID_EMAIL",$invalid_email, PDO::PARAM_INT);
                        $resUpdate->bindValue(":UNSUBSCRIBE",$unsubscribe, PDO::PARAM_INT);
                        $resUpdate->bindValue(":ID",$id, PDO::PARAM_INT);
                        $resUpdate->execute();
                }
                catch(PDOException $e){
                        throw new jsException($e);
                }
        }
	public function updateMailerOpenRateCount($id, $count){
		try{
			$sql = "SELECT OPEN_RATE FROM jeevansathi_mailer.DAILY_MAILER_COUNT_LOG WHERE ID = :ID";
			$res = $this->db->prepare($sql);
			$res->bindValue(":ID",$id, PDO::PARAM_INT);
			$res->execute();
			if($result=$res->fetch(PDO::FETCH_ASSOC)){
				$sql2 = "UPDATE jeevansathi_mailer.DAILY_MAILER_COUNT_LOG SET OPEN_RATE = (OPEN_RATE+:COUNT) WHERE ID = :ID";
				$res2 = $this->db->prepare($sql2);
				$res2->bindValue(":ID",$id, PDO::PARAM_INT);
				$res2->bindValue(":COUNT",$count, PDO::PARAM_INT);
				$res2->execute();
			}

		} catch (PDOException $e){
			throw new jsException($e);
		}
	}

	public function fetchUniqueKeys(){
		try{
			$sql = "SELECT DISTINCT(MAILER_KEY) AS MAILER_KEY_NAME FROM jeevansathi_mailer.DAILY_MAILER_COUNT_LOG";
			$res = $this->db->prepare($sql);
			$res->execute();
			while($result=$res->fetch(PDO::FETCH_ASSOC)){
				$output[] = $result['MAILER_KEY_NAME'];
			}
		} catch (PDOException $e){
			throw new jsException($e);
		}	
		return $output;
	}

	public function fetchReqData($mailerKeysArr, $startDt, $endDt){
		try{
			$mailerKeysStr = implode("','",$mailerKeysArr);
			$sql = "SELECT * FROM jeevansathi_mailer.DAILY_MAILER_COUNT_LOG WHERE MAILER_KEY IN ('$mailerKeysStr') AND ENTRY_DT>=:START_DT AND ENTRY_DT<=:END_DT";
			$res = $this->db->prepare($sql);
			$res->bindValue(":START_DT",$startDt, PDO::PARAM_STR);
			$res->bindValue(":END_DT",$endDt, PDO::PARAM_STR);
			$res->execute();
			while($result=$res->fetch(PDO::FETCH_ASSOC)){
				$output[] = $result;
			}
		} catch (PDOException $e){
			throw new jsException($e);
		}	
		return $output;
	}
}
?>
