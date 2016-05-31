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
PRIORITY=>"1",
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
BUTTON2_PAGEID_ANDROID=>""
),
2=>array(LAYERID=>"2",
PRIORITY=>"2",
TIMES=>"2",
MINIMUM_INTERVAL=>"24",
TITLE=>"Add details about your family",
BUTTON1=>"Yes, sure",
BUTTON2=>"No, Thanks",
ACTION1=>"/profile/viewprofile.php?ownview=1&EditWhatNew=about",
ACTION2=>"close",
JSMS_ACTION1=>"/profile/viewprofile.php?ownview=1#Family",
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
),
4=>array(LAYERID=>"4",
PRIORITY=>"3",
TIMES=>"2",
MINIMUM_INTERVAL=>"24",
TITLE=>"Add details about your education.",
BUTTON1=>"Yes, sure",
BUTTON2=>"No, Thanks",
ACTION1=>"/profile/viewprofile.php?ownview=1&EditWhatNew=EduOcc",
ACTION2=>"close",
JSMS_ACTION1=>"/profile/viewprofile.php?ownview=1#Education",
JSMS_ACTION2=>"/",
TEXT=>"People who mention their degrees and university/college/institution receive more interests and accepts. Would you like to enter your education highlights quickly?",
BUTTON1_URL_IOS=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"3",
BUTTON1_PAGEID_ANDROID=>"2",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>""
),
3=>array(LAYERID=>"3",
PRIORITY=>"4",
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

),
5=>array(LAYERID=>"5",
PRIORITY=>"5",
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

),
7=>array(LAYERID=>"7",
PRIORITY=>"7",
TIMES=>"255",
MINIMUM_INTERVAL=>"360",
TITLE=>"Sending Interests is super important",
BUTTON1=>"Yes, sure",
BUTTON2=>"No, Thanks",
ACTION1=>"/search/partnermatches",
ACTION2=>"close",
JSMS_ACTION1=>"/search/partnermatches",
JSMS_ACTION2=>"/",
TEXT=>"It’s important to keep sending interests to relevant matches to increase your chances to receive acceptances and proposals from others. Would you like to see your matches and start sending interests ?",
BUTTON1_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"",
BUTTON1_PAGEID_ANDROID=>"",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",

),
6=>array(LAYERID=>"6",
PRIORITY=>"6",
TIMES=>"255",
MINIMUM_INTERVAL=>"360",
TITLE=>"Request a Callback",
BUTTON1=>"Yes, sure",
BUTTON2=>"No, Thanks",
ACTION1=>"RCB",
ACTION2=>"close",
JSMS_ACTION1=>"/profile/mem_comparison.php?showRCBForCAL=1",
JSMS_ACTION2=>"/",
TEXT=>"To get in touch with members you like and to get better responses, you may consider upgrading your membership. Would you want us to call you and explain the benefits?",
BUTTON1_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON1_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B1",
BUTTON2_URL_IOS=>"common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON2_URL_ANDROID=>"/common/criticalActionLayerTracking?layerR=$layerid&button=B2",
BUTTON1_PAGEID_IOS=>"",
BUTTON1_PAGEID_ANDROID=>"",
BUTTON2_PAGEID_IOS=>"",
BUTTON2_PAGEID_ANDROID=>"",

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