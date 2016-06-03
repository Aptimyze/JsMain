<?php
/**
*       Filename        :       paymentcontact.php
*       Created By      :       Abhinav
**/
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
include("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/billing/comfunc_sums.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
if(authenticated($cid))
{
    $name= getname($cid);
    $center=get_centre($cid);
    $smarty->assign("name",$name);
    $mainServiceArr =array("P","C","D","ESP","X","NCP");
    $serviceObj = new Services;
    $membershipObj = new Membership;
    if($submit)
    {
        $is_error=0;
        if($city == "") 
        {
            $smarty->assign("check_city","Y");
            $is_error++;
        }
        if(!$NAME1)
        {
            $smarty->assign("check_name","Y");
            $is_error++;
        }
        if((!$PHONE_RES || !is_numeric($PHONE_RES)) && (!$PHONE_MOB || !is_numeric($PHONE_MOB)))
        {
            $smarty->assign("check_res","Y");
            $smarty->assign("check_mob","Y");
            $is_error++;
        }
        if(!$pincode || !is_numeric($pincode) || strlen($pincode)!=6){
            $smarty->assign("check_pincode","Y");
            $is_error++;
        }
        if(!$pref_time || $pref_time=='0000-00-00 00:00:00'){
            $smarty->assign("check_date","Y");
            $is_error++;
        }   
        if($SERVICE[0]=='')
        {
            $is_error++;
            $smarty->assign("check_service","Y");   
        }
        else
        {
            $ser_str= implode(",",$SERVICE);
            
            if(strstr($ser_str,'B') && (!strstr($ser_str,'P') && !strstr($ser_str,'C')))
            {
                $smarty->assign("check_service","Y");
                $msg="Boldlisting comes with E-value or E-rishta pack";
                $is_error++;
            }
            elseif(strstr($ser_str,'P') || strstr($ser_str,'C') || strstr($ser_str,'ESP') || strstr($ser_str,'D')||strstr($ser_str,'X')) {
                foreach($SERVICE as $key=>$val){
                    foreach($mainServiceArr as $key1=>$val1){
                        if(strstr($val,"NCP")){
                            if($val1!="P" && $val1!="C" && strstr($val,$val1)){
                                $maintSerCnt++;
                                $main_service=$val; 
                            }
                        } elseif(strstr($val,$val1)){
                            $maintSerCnt++;
                            $main_service=$val;
                        }       
                    }   
                }
                if($maintSerCnt>1)
                {
                    if(!strstr($ser_str,'ESP') || (strstr($ser_str,'ESP') && $maintSerCnt>2)){
                        $smarty->assign("check_service","Y");
                        $msg="Select only one main service";
                        $is_error++;
                    }           
                }
            }
            elseif(substr_count($ser_str,'B')>1 || substr_count($ser_str,'P')>1 || substr_count($ser_str,'C')>1 || substr_count($ser_str,'A')>1 || substr_count($ser_str,'O')>1 || substr_count($ser_str,'S')>1 || substr_count($ser_str,'D')>1 || substr_count($ser_str,'I')>1 || substr_count($ser_str,'T')>1 || substr_count($ser_str,'L')>1)
            {
                $smarty->assign("check_service","Y");
                $msg="Select one option for one service";
                $is_error++;
            }
            else
            {
                $smarty->assign("check_service","Y");
            //  $msg=check_service($SERVICE,$profileid);
                $msg=$membershipObj->checkRange($profileid,$SERVICE);
                if($msg!='')
                    $is_error++;
            }
        }
        /*if($SERVICE=="" ||($SERVICE=="P1" && count($addon_services)>0))
        {
            $smarty->assign("check_service","Y");
                        $is_error++;
                    }*/
                    if(!$ADDRESS)
                    {
                        $smarty->assign("check_address","Y");
                        $is_error++;
                    }
                    if($courier_type == '')
                    {
                        $smarty->assign("check_courier","Y");
                        $is_error++;
                    }   
                    if($is_error>=1)
                    {
                        $sql = "Select b.LABEL as LABEL,b.VALUE as VALUE from incentive.ARAMEX_BRANCHES a,incentive.BRANCH_CITY b where a.AR_BRANCH = b.VALUE";
                        $result_city = mysql_query_decide($sql) or die(mysql_error_js());

                        $result_city = mysql_query_decide($sql) or die(mysql_error_js());
                        while($myrow = mysql_fetch_array($result_city))
                        {
                            $city_values[] = array("LABEL"=>$myrow["LABEL"],
                                "VALUE"=>$myrow["VALUE"]);
                        }

            /* $sql_services = "SELECT SERVICEID, NAME FROM billing.SERVICES WHERE ACTIVE='Y' AND (SHOW_ONLINE = 'Y' ||  SERVICEID='O1' || SERVICEID='O3' || SERVICEID='O6' || SERVICEID='HDO6')";

                    $res_services = mysql_query_decide($sql_services) or logError_sums($sql_services);
    
                    $i=0;
                    while($row_services = mysql_fetch_assoc($res_services))
                    {
                            if($row_services["SERVICEID"]!='M1' && $row_services["SERVICEID"]!='M2' && $row_services["SERVICEID"]!='M3' && $row_services["SERVICEID"]!='M4' && $row_services["SERVICEID"]!='M5' && $row_services["SERVICEID"]!='M6' && $row_services["SERVICEID"]!='M12')
                            {
                                    $services[$i]['SERVICEID'] = $row_services["SERVICEID"];
                                    $services[$i]['NAME'] = $row_services["NAME"];
                                    $i++;
                            }
                        }*/
                        $services=$serviceObj->getAllServices_crm();
            //      $service_main=$serviceObj->getAllServices('SHOW_ONLINE');
                        $smarty->assign("CITY_VALUES",$city_values);
                        $smarty->assign("SERVICE_MAIN",$services);
                        $smarty->assign("msg",$msg);
                        $smarty->assign("DISCOUNT",$discount);
                        $smarty->assign("USERNAME",stripslashes($USERNAME));
                        $smarty->assign("PROFILEID",$profileid);
                        $smarty->assign("NAME1",stripslashes($NAME1));
                        $smarty->assign("EMAIL",$EMAIL);
                        $smarty->assign("PHONE_RES",$PHONE_RES);
                        $smarty->assign("PHONE_MOB",$PHONE_MOB);
                        $smarty->assign("SERVICE",$SERVICE);
                        $smarty->assign("ADDRESS",stripslashes($ADDRESS));
                        $smarty->assign("COMMENTS",$COMMENTS);  
                        $smarty->assign("COURIER",$courier_type);
                        $smarty->assign("pref_time",$pref_time);
                        $smarty->assign("prefix_name",$prefix_name);
                        $smarty->assign("LANDMARK",$LANDMARK);  
                        $smarty->assign("pincode",$pincode);
                        $smarty->assign("cid",$cid);
                        $smarty->assign("city",$city);
                        $smarty->display("paymentcontact_crm.htm");
                    }
                    else
                    {
                        $services=$serviceObj->get_matri_duration($SERVICE);
                        $SERVICE=implode(",",$services);
                        $sql="SELECT EMAIL from newjs.JPROFILE where PROFILEID='$profileid'";
                        $result=mysql_query_decide($sql);
                        $myrow=mysql_fetch_array($result);
                        
                        if(!$EMAIL)         
                        {
                            $email=$myrow['EMAIL'];
                        }
                        else
                            $email=$EMAIL;
                        
                        $service_arr=$serviceObj->getServicesAmount($SERVICE,'RS');
                        foreach($service_arr as $k=>$v)
                        {
                            if($ser_names=='')
                                $ser_names=$service_arr[$k]['NAME'];
                            else
                                $ser_names.=", ".$service_arr[$k]['NAME'];
                            $amount+=$service_arr[$k]['PRICE'];
                        }
                        $amount-=$discount;

                        $sql2 = "REPLACE INTO incentive.PAYMENT_COLLECT (PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,ENTRY_DT,ENTRYBY,COMMENTS,PREF_TIME,COURIER_TYPE,ADDON_SERVICEID,DISCOUNT,PICKUP_TYPE,REQ_DT,AMOUNT,PREFIX_NAME,LANDMARK) VALUES ('$profileid','".addslashes(stripslashes($USERNAME))."','".addslashes(stripslashes($NAME1))."','$email','$PHONE_RES','$PHONE_MOB','$SERVICE','".addslashes(stripslashes($ADDRESS))."','$city','$pincode','Y','Y',now(),'$name','$COMMENTS','$pref_time','$courier_type','$addon_services_str','$discount','CHEQ_REQ_EXEC',now(),'$amount','$prefix_name','$LANDMARK')";
                        
                        mysql_query_decide($sql2) or die("$sql2".mysql_error_js());
                        $req_id=mysql_insert_id_js();
                        $msg.= "Your request is successfully taken for pickup with $courier_type.<br>";
                        $msg.=  " And your request-id is $req_id.<br>";
                        $msg.= "Services: $ser_names<br> Amount: $amount";  
                        $msg .= "<a href=\"mainpage.php?name=$name&cid=$cid\">";
                        $msg .= "Continue &gt;&gt;</a>";
                        $smarty->assign("name",$name);
                        $smarty->assign("cid",$cid);
                        $smarty->assign("MSG",$msg);
                        $smarty->display("incentive_msg.tpl");
                    }   
                }
                else
                {
                    $sql = "Select b.LABEL as LABEL,b.VALUE as VALUE from incentive.ARAMEX_BRANCHES a,incentive.BRANCH_CITY b where a.AR_BRANCH = b.VALUE order by b.LABEL";
                    $result_city = mysql_query_decide($sql) or die(mysql_error_js());
                    while($myrow_city = mysql_fetch_array($result_city))
                    {
                        $city_values[] = array("LABEL"=>$myrow_city["LABEL"],
                            "VALUE"=>$myrow_city["VALUE"]); 
                    }

        /*$sql_services = "SELECT SERVICEID, NAME FROM billing.SERVICES WHERE ACTIVE='Y' AND (SHOW_ONLINE = 'Y' ||  SERVICEID='O1' || SERVICEID='O3' || SERVICEID='O6' || SERVICEID='HDO6')";

                $res_services = mysql_query_decide($sql_services) or logError_sums($sql_services);

                $i=0;
                while($row_services = mysql_fetch_assoc($res_services))
                {
            if($row_services["SERVICEID"]!='M1' && $row_services["SERVICEID"]!='M2' && $row_services["SERVICEID"]!='M3' && $row_services["SERVICEID"]!='M4' && $row_services["SERVICEID"]!='M5' && $row_services["SERVICEID"]!='M6' && $row_services["SERVICEID"]!='M12')
            {
                            $services[$i]['SERVICEID'] = $row_services["SERVICEID"];
                            $services[$i]['NAME'] = $row_services["NAME"];
                            $i++;
            }
        }*/
        
        $services=$serviceObj->getAllServices_crm();
        $smarty->assign("CITY_VALUES",$city_values);
        $smarty->assign("SERVICE_MAIN",$services);
        $smarty->assign("USERNAME",stripslashes($username));
        $smarty->assign("PROFILEID",$pid);
        $smarty->assign("pref_time","0000-00-00 00:00:00");
        $smarty->assign("cid",$cid);
        $smarty->display("paymentcontact_crm.htm");
    }
}
else//user timed out
{
    $msg="Your session has been timed out<br>  ";
    $msg .="<a href=\"index.php\">";
    $msg .="Login again </a>";
    $smarty->assign("MSG",$msg);
    $smarty->display("jsadmin_msg.tpl");
}

?>
