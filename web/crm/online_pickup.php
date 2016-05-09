<?php
ini_set('display_errors','On');
include($_SERVER['DOCUMENT_ROOT']."/crm/connect.inc");
include($_SERVER['DOCUMENT_ROOT']."/profile/pg/functions.php");
include_once($_SERVER['DOCUMENT_ROOT']."/crm/func_sky.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
//print_r($_POST);
if(authenticated($cid))
{
    $name= getname($cid);
    $center=get_centre($cid);
    $smarty->assign("name",$name);
    $serviceObj = new Services;
    $max_limit ='70';   
    $mainServiceArr =array("P","C","D","ESP","X","NCP");
    if($submit)
    {
        $is_error=0;
        if($discount==""){
            $smarty->assign("check_discount","Y");
            $is_error++;
        }
        if($SERVICE[0]==""){
            $smarty->assign("check_service","Y");
            $is_error++;
        }
        else{
            $ser_str= implode(",",$SERVICE);
            if(strstr($ser_str,'B') && (!strstr($ser_str,'P') && !strstr($ser_str,'C') && !strstr($ser_str,'ESP') && !strstr($ser_str,'D')))
            {
                $smarty->assign("check_service","Y");
                $msg="Boldlisting comes with E-value or E-rishta pack or E-sathi";
                $is_error++;
            }
            elseif(strstr($ser_str,'P') || strstr($ser_str,'C') || strstr($ser_str,'ESP') || strstr($ser_str,'D')||strstr($ser_str,'X')) 
            {
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
                elseif(substr_count($ser_str,'B')>1 || substr_count($ser_str,'P')>1 || substr_count($ser_str,'C')>1 || substr_count($ser_str,'ESP')>1 || substr_count($ser_str,'D')>1 || substr_count($ser_str,'A')>1 || substr_count($ser_str,'S')>1 || substr_count($ser_str,'L')>1 || substr_count($ser_str,'T')>1 || substr_count($ser_str,'M')>1 || substr_count($ser_str,'I')>1 || substr_count($ser_str,'R')>1 || substr_count($ser_str,'H')>1 || substr_count($ser_str,'K')>1 || substr_count($ser_str,'X')>1)
                {
                    $smarty->assign("check_service","Y");
                    $msg="Select one option for one service";
                    $is_error++;
                }
            }
            // Discount check added 
            if($discount>$max_limit){
                $is_error++;
                $msg="Discount limit exceeded";
                $smarty->assign("check_discount","Y");
            }
        }

        if($is_error>=1)
        {
            $service_main=$serviceObj->getAllServices('SHOW_ONLINE');
            $smarty->assign("SERVICE_MAIN",$service_main);
            $smarty->assign("USERNAME",stripslashes($USERNAME));
            $smarty->assign("PROFILEID",$profileid);
            $smarty->assign("SERVICE",$SERVICE);
            $smarty->assign("DISCOUNT",$discount);
            $smarty->assign("msg",$msg);
            $smarty->assign("cid",$cid);
            $smarty->display("online_pickup.htm");
        }
        else
        {
            // fetch details from PROFILE
            $sql="SELECT EMAIL,COUNTRY_RES from newjs.JPROFILE where PROFILEID='$profileid'";
            $result=mysql_query_decide($sql);
            $myrow=mysql_fetch_array($result);
            $email=$myrow['EMAIL'];
            $countryRes =$myrow['COUNTRY_RES'];
            if($countryRes=='51')
                $currencyType='RS';
            else
                $currencyType='DOL';

            // Selected Services distribution based on main-membership/addon-services
            $services=$serviceObj->get_matri_duration($SERVICE);
            $SERVICE= implode(",",$services);
            for($i = 0;$i<count($services);$i++)
                if($services[$i])
                {
                    $var=$services[$i];
                    if($var!=$main_service)
                        $addon_services_str=$addon_services_str.",".$var;
                }

            // get price for the selected services
                $service_arr=$serviceObj->getServicesAmount($SERVICE,$currencyType);
                foreach($service_arr as $k=>$v)
                    $price+=$service_arr[$k]['PRICE'];
                $discountVal =intval(($discount/100)*$price);

            // Adde values in the table
                $addon_services_str=trim($addon_services_str,',');
                $sql2 = "REPLACE INTO incentive.PAYMENT_COLLECT (PROFILEID,USERNAME,EMAIL,BYUSER,CONFIRM,ENTRY_DT,ENTRYBY,SERVICE,ADDON_SERVICEID,DISPLAY,PICKUP_TYPE,DISCOUNT,REQ_DT,DISCOUNT_PERCENT) VALUES ('$profileid','".addslashes(stripslashes($USERNAME))."','$email','Y','N',now(),'$name','$main_service','$addon_services_str','N','ONLINE','$discountVal',now(),'$discount')";
                mysql_query_decide($sql2) or die("$sql2".mysql_error_js());
                $req_id=mysql_insert_id_js();

            // link generation          
                $req_id1=md5($req_id).i.$req_id;
				if(JsConstants::$whichMachine == 'prod' && JsConstants::$siteUrl == 'http://crm.jeevansathi.com'){
					$SITE_URL = 'http://www.jeevansathi.com';
				} else {
					$SITE_URL = JsConstants::$siteUrl;
				}
                $URL="$SITE_URL/profile/membership_redirect.php?id=$req_id1";
                
            // link display message
                $msg.= "A mail has been sent to the user for payment using following URL :- $URL .<br>";
                $msg .= "<a href=\"mainpage.php?name=$name&cid=$cid\">";
                $msg .= "Continue &gt;&gt;</a>";
                
                $smarty->assign("URL","$URL");  
                $smarty->assign("USER",$name);
                $smarty->assign("USERNAME","$USERNAME");
                $msg1=$smarty->fetch("mail_for_payment.htm");
                $from="webmaster@jeevansathi.com";
                send_mail($email,'','manoj.rana@naukri.com',$msg1,"Link for online Payment",$from);
                $smarty->assign("cid",$cid);
            	$smarty->assign("MSG","A mail has been sent to the user for Payment using generated Discount link URL");
                //$smarty->assign("MSG",$msg);
                $smarty->display("incentive_msg.tpl");
            }   
        }
        else
        {
            $sql="SELECT INCOMPLETE from newjs.JPROFILE where PROFILEID='$pid'";
            $result = mysql_query_decide($sql) or die(mysql_error_js());
            $row=mysql_fetch_array($result);
            if($row["INCOMPLETE"]=="Y")
            {
                $msg="This user's profile is incomplete.So payment request can't be generated";
                $smarty->assign("MSG",$msg);
                $smarty->display("jsadmin_msg.tpl");
                die();
            }

        // get services                     
            $service_main=$serviceObj->getAllServices('SHOW_ONLINE');
            
            $smarty->assign("SERVICE_MAIN",$service_main);
            $smarty->assign("USERNAME",stripslashes($username));
            $smarty->assign("PROFILEID",$pid);
            $smarty->assign("cid",$cid);
            $smarty->assign("name",$name);
            $smarty->display("online_pickup.htm");
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
