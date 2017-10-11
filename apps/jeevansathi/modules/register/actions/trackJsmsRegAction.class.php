<?php
/*
 * This Controller is used for tracking Jsms Registration 
 *  
 * @author Kunal Verma
 * @date 5th Feb 2015
 */
class trackJsmsRegAction extends sfAction
{
    const RECEIVER = "sanyam1204@gmail.com";
    const MAILBODY = "trackJsmsReg is being hit. Solve the issue";
    const SENDER = "info@jeevansathi.com";
    const SUBJECT = "removed trackJsmsReg tracking";
    public function execute($request)
    {
        $mailBody = self::MAILBODY.print_r($_SERVER,true);
        SendMail::send_email(self::RECEIVER,$mailBody,self::SUBJECT,self::SENDER);
        $info = $request->getParameter('info');
        $arrInfo = json_decode($info,true);
        $arrInfo['uniqueId'] = $_COOKIE['regUID'];
        // if(!is_array($arrInfo) && !strlen($arrInfo['view']) && !is_numeric($arrInfo['uniqueId']))
        // {
        //     return;
        // }
        $arrInfo['view'] = strtoupper($arrInfo['view']);
        $trackObj = new REG_TRACK_JSMS();

        $profileId = null;
        if($arrInfo['view'] == 'S6')
        {
            $loginData = $request->getAttribute("loginData");
            $profileId = $loginData['PROFILEID'];
        }
        $trackObj->updateRecord($arrInfo['uniqueId'],$arrInfo['view'],$profileId);
        die;
    }
}
?>
