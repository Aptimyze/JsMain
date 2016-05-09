<?php

function check_sms_service($mobile_num)
{
        $sql_series = "Select SQL_CACHE * from newjs.SMS_SERIES ";
        $result_series = mysql_query_decide($sql_series) or logError("Due to a temporary problem your re
quest could not be processed. Please try after a couple of minutes",$sql_series,"ShowErrTemplate"
);

        while($myrow = mysql_fetch_array($result_series))
                $sms_series[] = $myrow["SERIES"];
        $mobile_num_prefix = substr($mobile_num,0,4);
        if(in_array($mobile_num_prefix,$sms_series))
                return 1;
        else
                return 0;
}

?>
