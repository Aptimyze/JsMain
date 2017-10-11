<?php

function benefits($ser_id)
{
 
 if($ser_id=="P")
	 $details_benefits="  Write Instant Messages + View Contact Details of Accepted Members + Initiate Chat";
 elseif($ser_id=="D")
	$details_benefits="  Let other members view your contact details + Receive Personalized Messages from Members";
 elseif($ser_id=="C")
	$details_benefits="  Write Instant Messages + Initiate Chat + View Contact Details of Accepted Members + Let other Members View your Contact Details + Receive Personalized Messages from Members";
 
  return $details_benefits;
}
?>

