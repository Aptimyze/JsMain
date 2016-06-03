<?php
class AlertManagerEnums
{
    
    public static $sortArr = array(
        "MA",
        "VA",
        "MM",
        "NMM",
        "CA",
        "PR",
        "KA",
        "SM",
        "PM",
        "PS",
        "SS",
        "MC",
        "OC",
        "SC1",
        "SC2",
        "PN"
    );
    
    public static $settingsArray = array(
        'KA' => array(
            0 => 'Kundli Alert Mails',
            1 => 'Receive mails where Jeevansathi recommends a profile to you based on astrological compatibility.',
            3 => 'kundli_alert',
        ) ,
        'NMM' => array(
            0 => 'New Matches Mails',
            1 => 'Receive mails where Jeevansathi recommends new profiles to you.',
            3 => 'new_matches_mail',
        ) ,
        'MC' => array(
            0 => 'Membership Calls',
            1 => 'Receive calls for Paid Membership options',
            3 => 'mem_call',
        ) ,
        'OC' => array(
            0 => 'Offer Calls',
            1 => 'Receive calls for Special offers / discounts on Membership',
            3 => 'offer_call',
        ) ,
        'SC1' => array(
            0 => 'Website Help',
            1 => 'Receive calls for Explanation of site features',
            3 => 'service1',
        ) ,
        'SC2' => array(
            0 => 'Profile Completion',
            1 => 'Receive calls which aid in "Completion of Profile"',
            3 => 'service2',
        ) ,
        'MM' => array(
            0 => 'Membership Mails',
            1 => 'Receive mails from Jeevansathi about membership options and offers',
            3 => 'mem_mail',
        ) ,
        'PM' => array(
            0 => 'Promotional Mails',
            1 => 'Receive mails from 3rd Party other than Jeevansathi.',
            3 => 'promo_mails',
        ) ,
        'VA' => array(
            0 => 'Visitor Alert Mails',
            1 => 'Receive mails from Jeevansathi when someone visits your profile',
            3 => 'vis_alert',
        ) ,
        'MA' => array(
            0 => 'Match Alert Mails',
            1 => 'Receive mails where Jeevansathi recommends a profile to you.',
            3 => 'match_alert',
        ) ,
        'CA' => array(
            0 => 'Contact Alert Mails',
            1 => 'Receive mails when someone on Jeevansathi "Expresses Interest" in your profile.',
            3 => 'contact_alert',
        ) ,
        'PR' => array(
            0 => 'Photo Request Mails',
            1 => 'Receive mails when someone in Jeevansathi "Requests you to upload photo" in your profile.',
            3 => 'photo_req',
        ) ,
        'SM' => array(
            0 => 'Service Mails',
            1 => 'Receive mails (other than Visitor Alert/ Match Alert / Photo request) from Jeevansathi.',
            3 => 'serv_mail',
        ) ,
        'SS' => array(
            0 => 'Transactional SMS',
            1 => 'Receive important notifications from Jeevansathi on your profile.',
            3 => 'serv_sms',
        ) ,
        'STM' => array(
            0 => 'Service / Transactional MMS',
            1 => 'Receive important notifications from Jeevansathi on your profile.',
            3 => 'serv_mms',
        ) ,
        'SU' => array(
            0 => 'Service / Transactional USSD',
            1 => 'Receive important notifications from Jeevansathi on your profile.',
            3 => 'serv_ussd',
        ) ,
        'PU' => array(
            0 => 'Promotional USSD',
            1 => 'Receive membership information and special offers from Jeevansathi for your profile.',
            3 => 'promo_ussd',
        ) ,
        'PS' => array(
            0 => 'Membership SMS',
            1 => 'Receive membership information and special offers from Jeevansathi for your profile.',
            3 => 'promo_sms',
        ) ,
        'PMM' => array(
            0 => 'Promotional MMS',
            1 => 'Receive membership information and special offers from Jeevansathi for your profile.',
            3 => 'promo_mms',
        ) ,
        'PN' => array(
            0 => 'Push Notifications',
            1 => 'Get notified about important updates related to your account through browser push notifications.',
            3 => 'push_notify',
        ) 
    );
}
?>