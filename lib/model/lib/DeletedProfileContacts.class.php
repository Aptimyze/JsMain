<?php
/**
 * @brief This class is used to handle all contacts functionalities of users
 * @author Prinka Wadhwa
 * @created 2012-08-16
 */

class DeletedProfileContacts
{
	public function getContactsCount( $where, $group='',$time='',$skipProfile='')
	{
		if(!$where["RECEIVER"]&&!$where["SENDER"])
		{
			throw new jsException("","No Sender or reciever is specified in funcion getContactsCount OF Contacts.class.php");
		}
		else
		{
			if($where["RECEIVER"])
				$profileid = $where["RECEIVER"];
			else
				$profileid = $where["SENDER"];
		}

		$dbName = JsDbSharding::getShardNo($profileid);
		$contactsObj = new newjs_DELETED_PROFILE_CONTACTS($dbName);
		$contactsCount = $contactsObj->getContactsCount($where,$group,$time,$skipProfile);
		return $contactsCount;
		
	}
}
?>
