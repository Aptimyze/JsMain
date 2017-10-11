~if $curlReq eq ''`
	~include_partial('global/header')`
~/if`
<link rel="stylesheet" href="~sfConfig::get('app_site_url')`/profile/images/styles.css" type="text/css">

<!-- Main-Profile-Stats section Start -->
~include_partial('global/profileStats/_MainProfileStat',[data=>$mainProfileStatsData,cid=>$cid,checksum=>$checksum])`
<!-- Main-Profile-Stats section End -->


<!-- Detailed-Profile-Stats details section  Start -->
~include_partial('global/profileStats/_DetailedProfileStat',[details=>$detailedProfileStatsData,CHECKSUM=>$checksum])`
<!-- Detailed-Profile-Stats details section End -->


<!-- Show-Profile Completion Score details section  Start -->
<div>
~include_Partial("showStatsProfileCompletionScore",["profileCompletionScoreArr"=>$profileDetailArr['profileCompletion'],"yourEducation"=>$profileDetailArr['Education']['EDUCATION']['label_val'],"jobInfo"=>$profileDetailArr['Career']['JOB_INFO']['label_val'],"familyInfo"=>$profileDetailArr['Family']['FAMILYINFO']['label_val'],"profCompScoreArr"=>$profCompScoreArr,"contactDetailsArr"=>$detailedProfileStatsData])`
</div>
<!-- Show-Profile Completion Score details section End -->


<!-- Show-Profile details section  Start -->
<div>
~include_Partial("showStatsViewProfile",["profileDetailArr"=>$profileDetailArr,"otherDetailsArr"=>$otherDetailsArr,"profilePicUrl"=>$profilePicUrl])`
</div>
<!-- Show-Profile details section  End -->

~if $curlReq eq ''`
	<!-- Botom Link section  Start -->
	~include_partial('global/profileStats/_crmFooterLink',[linkArr=>$linkArr,profileid=>$profileid,username=>$username,cid=>$cid,checksum=>$checksum,isAlloted=>$isAlloted,online_payment=>$online_payment,set_filter=>$set_filter])`
	<!-- Botom Link section section  End -->

	~include_partial('global/footer')`
~/if`

