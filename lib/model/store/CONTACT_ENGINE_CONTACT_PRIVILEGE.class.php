<?php
/**
 * NEWJS_CONTACT_PRIVILEGE
 * 
 * This class handles all database queries to calculate Privilege 
 * @package    FTO
 * @author     Nitesh Sethi And Pankaj Khandelwal
 * @created    2012-11-09
 */
class NEWJS_CONTACT_PRIVILEGE extends TABLE{
       
/**
* @fn __construct
* @brief Constructor function
* @param $dbName - Database name to which the connection would be made
*/
        public function __construct($dbname="")
        {
			parent::__construct($dbname);
        }
  
/**
 * @fn getPrivilege
 * @brief fetches results from ContactPrivilege
 * @param $contactHandlerObj ContactHandler 
 * @return Privilege Array according to the Profile State
 * @exception jsException for blank criteria
 * @exception PDOException for database level error handling
 */    
        public function getPrivilege(ContactHandler $contactHandlerObj)
        { 
			try{
				$loggedInProfileState=$contactHandlerObj->getViewer()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus();
				$otherProfileState=$contactHandlerObj->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus();
											
				$sql="select ACTION_TYPE,PRIVILEGE,ALLOWED from CONTACT_ENGINE.CONTACT_PRIVILEGE where LOGGEDINPROFILE=:loggedInProfileState and OTHERPROFILE=:otherProfileState AND SENDER_RECIEVER = :senderReciever AND CONTACT_STATUS = :contactStatus AND CONTACT_TYPE = :contactType";
				$prep=$this->db->prepare($sql);
				
				$prep->bindValue(":loggedInProfileState", $loggedInProfileState, PDO::PARAM_STR);
				$prep->bindValue(":otherProfileState", $otherProfileState, PDO::PARAM_STR);
				$prep->bindValue(":senderReciever", $contactHandlerObj->getContactInitiator(), PDO::PARAM_STR);
				$prep->bindValue(":contactStatus", $contactHandlerObj->getContactObj()->getTYPE(), PDO::PARAM_STR);
				$prep->bindValue(":contactType",$contactHandlerObj->getEngineType(), PDO::PARAM_STR);
				
				$prep->execute();
				while($result = $prep->fetch(PDO::FETCH_ASSOC))
				{
					$res[]= $result;
				}
				$count = count($res);
				$actionType="";
				$j=0;
				for($i =0; $i < $count ; $i++)
				{
					$arr[0][$res[$i]["ACTION_TYPE"]][$res[$i]["PRIVILEGE"]] = $res[$i]["ALLOWED"];
					if($res[$i]["ACTION_TYPE"]!=$actionType)
					{
						$actionType=$res[$i]["ACTION_TYPE"];
						$arr[1][$j]=$actionType;
						$j++;
					}
				}
				return $arr;
			}
			catch(PDOException $e)
			{
				/*** echo the sql statement and error message ***/
				throw new jsException($e);
			}
		}
		
}
?>
