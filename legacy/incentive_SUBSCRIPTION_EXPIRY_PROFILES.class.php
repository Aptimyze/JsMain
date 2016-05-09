<?php
class SUBSCRIPTION_EXPIRY_PROFILES
{
	public function updateProfile($deAllMethodOob)
	{
		try
		{
			$sql = "UPDATE incentive.SUBSCRIPTION_EXPIRY_PROFILES SET HANDLED='Y' , HANDLE_DT=IF(HANDLE_DT<>0,HANDLE_DT,NOW()) WHERE PROFILEID=:PID";
			$prep = $this->db->prepare($sql);
			$prep->bindValue(":PID",$deAllMethodObj->getProfile(),PDO::PARAM_STR);
			$prep->execute();

		}
		catch(Exception $e)
		{
			throw new jsException();
		}
	}
	public function getUnHandledProfiles($name)
	{
		try
                {
                        $sql = "SELECT PROFILEID FROM incentive.SUBSCRIPTION_EXPIRY_PROFILES WHERE ALLOTED_TO=:ALLOTED_TO AND HANDLED = 'N'";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":ALLOTED_TO",$name,PDO::PARAM_STR);
                        $prep->execute();
			while($result=$prep->fetch(PDO::FETCH_ASSOC))
                        {
                                $profiles[]=$result['PROFILEID'];
                        }
                }
                catch(Exception $e)
                {
                        throw new jsException();
                }
		return $profiles;
	}
}
?>
