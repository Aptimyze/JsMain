<?php
include ("../connect.inc");
connect_db();

if ($actiontype == 'confirm')

//if actiontype is "confirm"
{
    list($part1, $part2) = explode("-", $orderid);
    $sql = "SELECT AMOUNT,STATUS from billing.ORDERS where ID = '$part2' and ORDERID = '$part1'";
    
    $res = mysql_query_decide($sql);
    if (mysql_num_rows($res))
    
    //if ORDERID exists in our database
    {
        $myrow = mysql_fetch_array($res);
        if ($myrow['STATUS'] == 'Y')
        
        //if MERCHANT_ALREADY_CONFIRMED
        {
            $ResponceCode = 3100;
        } 
        elseif ($myrow['STATUS'] == 'N')
        
        //if MERCHANT_ALREADY_CANCELLED
        {
            $ResponceCode = 3101;
        } 
        else {
            if (($myrow['AMOUNT'] * 100) == $productcost) {
                $ResponceCode = 0;
            } 
            else
            
            //else MERCHANT_INVALID_REQUEST_DATA
            {
                $ResponceCode = - 3107;
            }
        }
    } 
    else
    
    //else MERCHANT_INVALID_REQUEST_DATA
    {
        $ResponceCode = - 3107;
    }
} 
else

//else MERCHANT_INVALID_ACTION_TYPE
{
    $ResponceCode = - 3106;
}

//return the ResponseCode,Description
//mail("vikas@jeevansathi.com","ITZ",$ResponceCode.",".$description . $productcost);
echo $ResponceCode . "," . $description;
?>
