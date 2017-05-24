<?php
class mmmjs_MAILERYN extends TABLE
{
        public function __construct($dbname="")
        {
		$dbname=$dbname?$dbname:"211_connect";
		parent::__construct($dbname);
        }

	/**
	  * 
	**/
	public function InsertMailerYN($pid,$usercode)
	        {
	                try
	                {//print_r($pid);die;
	                	$resid = count($usercode);
							$sql = "INSERT INTO  mmmjs.MAILERYN (RECEIVER,USER1,USER2,USER3,USER4,USER5,USER6,USER7,USER8,USER9,USER10,COUNTS,DATE) VALUES(:PROFILEID,'$usercode[0]','$usercode[1]','$usercode[2]','$usercode[3]','$usercode[4]','$usercode[5]','$usercode[6]','$usercode[7]','$usercode[8]','$usercode[9]','$resid',now())";
							$res = $this->db->prepare($sql);
				            $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
	                		$res->execute();    
	                }
	                catch(PDOException $e)
	                {
	                        throw new jsException($e);
	                }
	        }

	        public function UpdateMailerYN($pid)
	        {
	                try
	                {//print_r($pid);die;
	                	
						$sql = "UPDATE mmmjs.MAILERYN SET SENT='Y' WHERE RECEIVER=:PROFILEID";
						$res = $this->db->prepare($sql);
			            $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
                		$res->execute();       
	                }
	                catch(PDOException $e)
	                {
	                        throw new jsException($e);
	                }
	        }
	public function SelectMailerYN()
	        {
	                try
	                {
							$sql = "SELECT ID,RECEIVER,USER1,USER2,USER3,USER4,USER5,USER6,USER7,USER8,USER9,USER10,COUNTS,DATE FROM mmmjs.MAILERYN WHERE SENT=''";
							$res = $this->db->prepare($sql);
				           // $res->bindValue(":PROFILEID", $pid, PDO::PARAM_INT);
	                		$res->execute();    
	                		while($row = $res->fetch(PDO::FETCH_ASSOC))
	                		{
								$profileMailData[$row['RECEIVER']][] =$row['USER1'];
								$profileMailData[$row['RECEIVER']][] =$row['USER2'];
								$profileMailData[$row['RECEIVER']][] =$row['USER3'];
								$profileMailData[$row['RECEIVER']][] =$row['USER4'];
								$profileMailData[$row['RECEIVER']][] =$row['USER5'];
								$profileMailData[$row['RECEIVER']][] =$row['USER6'];
								$profileMailData[$row['RECEIVER']][] =$row['USER7'];
								$profileMailData[$row['RECEIVER']][] =$row['USER8'];
								$profileMailData[$row['RECEIVER']][] =$row['USER9'];
								$profileMailData[$row['RECEIVER']][] =$row['USER10'];
								$profileMailData['COUNT'][]=$row['COUNTS'];
							}
							return $profileMailData;
	                }
	                catch(PDOException $e)
	                {
	                        throw new jsException($e);
	                }
	        }

	        public function EmptyMailerYN()
	        {
	                try
	                {//print_r($pid);die;
	                	
						$sql = "TRUNCATE TABLE mmmjs.MAILERYN";
						$res = $this->db->prepare($sql);
                		$res->execute();       
	                }
	                catch(PDOException $e)
	                {
	                        throw new jsException($e);
	                }
	        }

	}