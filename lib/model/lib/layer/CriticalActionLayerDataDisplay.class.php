<?php
 /*
This is auto-generated class by running lib/task/profile/CAlayerDataCreatorTask.class.php
This class should not be updated manually.
Created on 2015-05-26
 */
class CriticalActionLayerDataDisplay{
  /*This will return data corresponding to asked info for a particular layer id

Keep the Layer id same as the key of $arr. Else there will be a mismatch.

  */
  public static function getDataValue($layerid='',$label='',$value=''){
    $arr=array( 
1=>array(LAYERID=>"1",
PRIORITY=>"5",
TIMES=>"2",
MINIMUM_INTERVAL=>"24",
TITLE=>"Upload your photo",
BUTTON1=>"Yes, sure",
BUTTON2=>"No, Thanks",
ACTION1=>"/social/addPhotos",
ACTION2=>"close",
JSMS_ACTION1=>"/profile/viewprofile.php?ownview=1#Album",
JSMS_ACTION2=>"/",
TEXT=>"People who upload their photos receive 8 times more relevant responses. Do you want to upload your photos now?",
BUTTON1_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"0",
BUTTON1_PAGEID_ANDROID=>"0",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",
UNLIMITED => "N",
),
2=>array(LAYERID=>"2",
PRIORITY=>"9",
TIMES=>"2",
MINIMUM_INTERVAL=>"24",
TITLE=>"Add details about your family",
BUTTON1=>"Yes, sure",
BUTTON2=>"No, Thanks",
ACTION1=>"/profile/viewprofile.php?ownview=1&section=family",
ACTION2=>"close",
JSMS_ACTION1=>"/profile/viewprofile.php?ownview=1&section=family",
JSMS_ACTION2=>"/",
TEXT=>"You will receive more interests and acceptances from others if they know a little about your family. Do you want to mention a few things?",
BUTTON1_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"5",
BUTTON1_PAGEID_ANDROID=>"4",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",
UNLIMITED =>"N",
),
4=>array(LAYERID=>"4",
PRIORITY=>"10",
TIMES=>"2",
MINIMUM_INTERVAL=>"24",
TITLE=>"Add details about your education.",
BUTTON1=>"Yes, sure",
BUTTON2=>"No, Thanks",
ACTION1=>"/profile/viewprofile.php?ownview=1&section=education",
ACTION2=>"close",
JSMS_ACTION1=>"/profile/viewprofile.php?ownview=1&section=education",
JSMS_ACTION2=>"/",
TEXT=>"People who mention their degrees and university/college/institution receive more interests and accepts. Would you like to enter your education highlights quickly?",
BUTTON1_URL_IOS=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"3",
BUTTON1_PAGEID_ANDROID=>"2",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",
UNLIMITED =>"N",
),
3=>array(LAYERID=>"3",
PRIORITY=>"11",
TIMES=>"255",
MINIMUM_INTERVAL=>"168",
TITLE=>"Respond to pending interests",
BUTTON1=>"Yes, sure",
BUTTON2=>"No, Thanks",
ACTION1=>"/inbox/1/1",
ACTION2=>"close",
JSMS_ACTION1=>"/profile/contacts_made_received.php?page=eoi&filter=R",
JSMS_ACTION2=>"/",
TEXT=>"It is always nice to respond as soon as you can. Do you want to Accept or Decline the interests you have received?",
BUTTON1_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"100",
BUTTON1_PAGEID_ANDROID=>"1",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",
UNLIMITED =>"Y",
),
5=>array(LAYERID=>"5",
PRIORITY=>"12",
TIMES=>"255",
MINIMUM_INTERVAL=>"720",
TITLE=>"Get relevant Matches and Interests",
BUTTON1=>"Yes, sure",
BUTTON2=>"No, Thanks",
ACTION1=>"/profile/dpp",
ACTION2=>"close",
JSMS_ACTION1=>"/profile/viewprofile.php?ownview=1#Dpp",
JSMS_ACTION2=>"/",
TEXT=>"The matches and interests you receive are based on your Desired Partner Profile and the Filters set by you. Would you like to review/edit them?",
BUTTON1_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"8",
BUTTON1_PAGEID_ANDROID=>"8",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",
UNLIMITED => "Y"
),
7=>array(LAYERID=>"7",
PRIORITY=>"14",
TIMES=>"255",
MINIMUM_INTERVAL=>"360",
TITLE=>"Don’t miss out on connecting with people you like",
BUTTON1=>"Yes, sure",
BUTTON2=>"No, Thanks",
ACTION1=>"/search/partnermatches",
ACTION2=>"close",
JSMS_ACTION1=>"/search/partnermatches",
JSMS_ACTION2=>"/",
TEXT=>"Lakhs of people log in everyday to send or accept interests. Your matches may be waiting for an interest from you too. Would you like to see your matches and start sending interests ?",
BUTTON1_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"101",
BUTTON1_PAGEID_ANDROID=>"103",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",
UNLIMITED => "Y",
),
6=>array(LAYERID=>"6",
PRIORITY=>"13",
TIMES=>"255",
MINIMUM_INTERVAL=>"360",
TITLE=>"Request a Callback",
BUTTON1=>"Yes, sure",
BUTTON2=>"No, Thanks",
ACTION1=>"RCB",
ACTION2=>"close",
JSMS_ACTION1=>"/profile/mem_comparison.php?showRCBForCAL=1",
JSMS_ACTION2=>"/",
TEXT=>"To get in touch with members you like and get more interests and accepts, you should upgrade your plan. Would you like us to call you and explain the plan benefits?",
BUTTON1_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"",
BUTTON1_PAGEID_ANDROID=>"105",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",
UNLIMITED => "Y",
),
8=>array(LAYERID=>"8",
PRIORITY=>"1",
TIMES=>"255",
MINIMUM_INTERVAL=>"168",
TITLE=>"Duplicate profile",
BUTTON1=>"",
BUTTON2=>"Okay",
ACTION1=>"",
ACTION2=>"close",
JSMS_ACTION1=>"/",
JSMS_ACTION2=>"/",
TEXT=>"Your profile has been marked as duplicate and is not being shown in search as we have found some other active profile(s) with similar details. Please delete the other profile(s) to start appearing in search. If you think this is in error, please contact customer care.",
BUTTON1_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"",
BUTTON1_PAGEID_ANDROID=>"",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",
UNLIMITED => "Y",
),

9=>array(LAYERID=>"9",
PRIORITY=>"3",
TIMES=>"255",
MINIMUM_INTERVAL=>"48",
TITLE=>"Provide Your Name",
BUTTON1=>"Submit",
BUTTON2=>"Skip",
ACTION1=>"close",
ACTION2=>"close",
JSMS_ACTION1=>"/",
JSMS_ACTION2=>"/",
TEXT=>"Now you can choose to show your name and see the names of other members",
BUTTON1_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"",
BUTTON1_PAGEID_ANDROID=>"",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",
UNLIMITED =>"Y"
),

10=>array(LAYERID=>"10",
PRIORITY=>"4",
TIMES=>"255",
MINIMUM_INTERVAL=>"720",
TITLE=>"Make Photo Visible?",
BUTTON1=>"Yes, sure",
BUTTON2=>"No, Thanks",
ACTION1=>"/social/addPhotos?fromCALphoto=1",
ACTION2=>"close",
JSMS_ACTION1=>"/profile/viewprofile.php?ownview=1&fromCALphoto=1",
JSMS_ACTION2=>"/",
TEXT=>"You may be receiving lesser interests as your photo is only visible on accept. To get more interests, would you like to make your photo visible to all?",
BUTTON1_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"",
BUTTON1_PAGEID_ANDROID=>"106",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",
UNLIMITED => "Y",
),
11=>array(LAYERID=>"11",
PRIORITY=>"7",
TIMES=>"4",
MINIMUM_INTERVAL=>"72",
TITLE=>"Get more matches",
BUTTON1=>"Yes, sure",
BUTTON2=>"No, Thanks",
ACTION1=>"/profile/dpp",
ACTION2=>"close",
JSMS_ACTION1=>"/profile/viewprofile.php?ownview=1#Dpp",
JSMS_ACTION2=>"/",
TEXT=>"You may not be receiving relevant matches and interests as your Desired Partner criteria is too strict. Would you like to consider relaxing your criteria to receive more matches?",
BUTTON1_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"8",
BUTTON1_PAGEID_ANDROID=>"8",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",
UNLIMITED => "N"
),     
12=>array(LAYERID=>"12",
PRIORITY=>"8",
TIMES=>"3",
MINIMUM_INTERVAL=>"24",
TITLE=>"Add your Horoscope",
BUTTON1=>"Yes, sure",
BUTTON2=>"No, Thanks",
ACTION1=>"/profile/viewprofile.php?ownview=1&fromCALHoro=1",
ACTION2=>"close",
JSMS_ACTION1=>"/profile/viewprofile.php?ownview=1&fromCALHoro=1#Kundli",
JSMS_ACTION2=>"/",
TEXT=>"You have indicated that horoscope matching is important for you. Would you like to create your horoscope by entering your birth details?",
BUTTON1_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"",
BUTTON1_PAGEID_ANDROID=>"107",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",
UNLIMITED => "N",
),
13=>array(LAYERID=>"13",
PRIORITY=>"15",
TIMES=>"3",
MINIMUM_INTERVAL=>"168",
TITLE=>"Add an Alternate Email",
BUTTON1=>"Yes, sure",
BUTTON2=>"No, Thanks",
ACTION1=>"/profile/viewprofile.php?ownview=1&section=contact&fieldName=ALT_EMAIL",
ACTION2=>"close",
JSMS_ACTION1=>"/profile/viewprofile.php?ownview=1&section=contact&fieldName=ALT_EMAIL",
JSMS_ACTION2=>"/",
TEXT=>"You can now also add Email address of someone in your Family for receiving match and contact alerts. Would you like to add and verify an alternate Email address?",
TEXTNEW=>"Provide your alternate Email Id to receive copy of emails",
SUBTITLE=>"A link will be sent to your above email id, click on the link to verify your email",
BUTTON1NEW=>"Submit",
BUTTON2NEW=>"Skip",
BUTTON1_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"",
BUTTON1_PAGEID_ANDROID=>"",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",
UNLIMITED => "N",
),
14=>array(LAYERID=>"14",
PRIORITY=>"16",
TIMES=>"10",
MINIMUM_INTERVAL=>"168",
TITLE=>"Verify your Alternate Email",
BUTTON1=>"Yes, sure",
BUTTON2=>"No, Thanks",
ACTION1=>"/profile/viewprofile.php?ownview=1&fromCALAlternate=1",
ACTION2=>"close",
JSMS_ACTION1=>"/profile/viewprofile.php?ownview=1#Contact",
JSMS_ACTION2=>"/",
TEXT=>"To receive alerts on your Alternate Email, you need to verify it. Would you like to verify your alternate Email address?",
BUTTON1_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"",
BUTTON1_PAGEID_ANDROID=>"",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",
UNLIMITED => "N",
),
        
15=>array(LAYERID=>"15",
PRIORITY=>"2",
TIMES=>"255",
MINIMUM_INTERVAL=>"48",
TITLE=>"See names of other members",
BUTTON1=>"Yes, make my name visible",
BUTTON2=>"No, hide my name",
ACTION1=>"close",
ACTION2=>"close",
JSMS_ACTION1=>"/",
JSMS_ACTION2=>"/",
TEXT=>"To see names of other members, you need to make your name visible to others. Would you like to make your name visible?",
BUTTON1_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"",
BUTTON1_PAGEID_ANDROID=>"",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",
UNLIMITED =>"Y"
),
16=>array(LAYERID=>"16",
PRIORITY=>"6",
TIMES=>"4",
MINIMUM_INTERVAL=>"72",
TITLE=>"Get more matches",
BUTTON1=>"Yes, sure",
BUTTON2=>"No, Thanks",
ACTION1=>"/",
ACTION2=>"close",
JSMS_ACTION1=>"/",
JSMS_ACTION2=>"/",
TEXT=>"You may not be receiving relevant matches and interests as your Desired Partner criteria is too strict. Would you like to consider relaxing your criteria to receive more matches?",
BUTTON1_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"",
BUTTON1_PAGEID_ANDROID=>"",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",
UNLIMITED => "N"
),     

        
);
if ($label) {
   if ($value) {
     foreach ($arr as $k=>$v) {
       
      if ($v[$label]==$value) return $v['LAYERID'];
       
     }
     return null;     
   }
   else {
     return $arr[$layerid][$label];
   }
}
else {
 return $arr[$layerid];
}
}
}
