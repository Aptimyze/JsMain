<?php

/*************************************************************************************************************************
*       FUNCTION NAME   :doubtfull_ip
*       DESCRIPTION     :To check If the register ip address is suspected ip-address or not.
*       ADDED BY        :Lavesh Rawat
*       CREATED ON      :17 July 2006
*************************************************************************************************************************/

function doubtfull_ip($ip)
{
	return 0;//no longer required
        //List of suspected ip-address and suspected subnet mask.
        $list_ip=array ('61.246.45.67','196.201.*','81.199.125.*','213.136.*','193.220.188.189','67.184.68.175','216.147.159.*','65.91.140.140','82.193.38.18','196.207.*','82.153.50.*','41.207.*','41.219.*');
                                                                                                                             
        if(in_array($ip,$list_ip))
        {
                return(1);
        }
        else
        {
                $ip_add=explode(".",$ip);
                                                                                                                             
                $len=count($list_ip);
                                                                                                                             
                for($i=0;$i<$len;$i++)
                {
                        $ip1=$list_ip[$i];
                        $ip_add1=explode(".",$ip1);
                                                                                                                             
                        if($ip_add[0]==$ip_add1[0])
                        {
                                if($ip_add1[1]=='*')
                                {
                                        return(1);
                                }
                                elseif($ip_add[1]==$ip_add1[1])
                                {
                                        if($ip_add1[2]=='*')
                                        {
                                                return(1);
                                        }
                                        elseif($ip_add[2]==$ip_add1[2])
                                        {
						if($ip_add1[3]=='*' || $ip_add[3]==$ip_add1[3])
                                                {
                                                        return(1);
                                                }
                                        }
                                }
                        }
                }
        }
return(0);
}
?>
