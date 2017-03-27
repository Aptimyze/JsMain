<?php
class contactOnce
{
	
//this updates the personalized message for paid members after they perform an EOI
function updateMessageForPaidMembers($contactID,$msg){

 (new NEWJS_CONTACTS_ONCE())->updateMessage($contactID,$msg);

}



}

?>