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
