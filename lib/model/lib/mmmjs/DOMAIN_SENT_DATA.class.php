<?php

class DOMAIN_SENT_DATA
{
	public static function domainCount($sent_data,$mailerId)
	{
		foreach($sent_data as $date => $domainArr){
                foreach($domainArr as $domain => $count){
				$obj = new mmmjs_MAIL_SENT;
				$MAIL_SENT = $obj->domainDataInsert('mmmjs.DOMAIN_SENT_DATA',$date,$mailerId,$domain,$count);					
                }
		}
	}

	public static function domainCountForMIS($mailerId)
    {       
        $obj = new mmmjs_DOMAIN_SENT_DATA;
        $insertData = $obj->domainData($mailerId);
    }
}

?>
