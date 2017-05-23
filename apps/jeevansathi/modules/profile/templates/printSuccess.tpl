<style>
.wgy9f{width:150px;}
.wgy5f{width:130px;}
.subhd{font-weight:bold;}
.p_bl{font-weight:bold;}
.mb{margin-bottom:3px;}
</style>
<div style="border: 1px solid #cccccc; border-right: 1px solid #cccccc; width:600px; margin:auto;padding-left:6px">
   
    <div class="hdr">
    <div class="fl" style="width:~if $pdf` 308px~else`220px~/if`">
      
~if !$pdf`<img vspace="4" border="0" alt="Matrimonials" src="~sfConfig::get("app_img_url")`/profile/ser4_images/Matrimonial.gif"> ~else` <img vspace="4" border="0" alt="Matrimonials" src="~sfConfig::get("app_img_url")`/profile/ser4_images/jsex.gif">~/if`<br>
 <div class="fr">We Match Better</div>

     </div>
</div>

<div class="sp12"></div>


   <div style="width:575px;">
    <div class="protop1 b fl" style="padding-left:3px;">Profile of ~$PROFILENAME`</div>
    <div class="fr" style="padding-right:10px;">~if !$pdf`<a onclick="print()" style="cursor:pointer"><img src="~sfConfig::get('app_img_url')`/images/print.gif" border="0"  align="right"/></a>~/if`</div>
    </div>
    <p class="clr"></p>
    
<div class="sp6"></div>

<p style="border-top: 1px solid #cccccc;"></p>
<p class="fl" style="margin-right:12px;margin-top:8px;margin-bottom:8px"><img src="~if $PHOTO` ~$PHOTO`~else`~sfConfig::get("app_img_url")`/profile/images/no_photo.gif~/if`" galleryimg="NO" border="0" oncontextmenu="return false;"/>


</p>
<div style="padding-left:161px">
<div class="protop1 b">Contact Details</div>
~if $CONTACT_LOCKED eq 1`
~if $SHOW_MOBILE`
<div  >Mobile no.~if $MOB_PROFILENAME` of  ~$MOB_PROFILENAME` (~$MOB_RELATION_NAME`)~/if`</div>
<div class="b mb">~$SHOW_MOBILE` ~if $VERIFIED_MOB`(Verified)~/if`</div>
~/if`
 ~if $ALT_MOBILE`
<div  >Alternative Mobile no. ~if $ALT_MOBILE_LABEL`~$ALT_MOBILE_LABEL`~/if`</div>
<div class="b mb">~$ALT_MOBILE`</div>
~/if`
~if $PHONE_NO`
<div >Landline no.~if $PHONE_PROFILENAME` of  ~$PHONE_PROFILENAME` (~$PHONE_RELATION_NAME`)~/if`</div>
<div class="b mb">~$PHONE_NO` ~if $VERIFIED_LANDLINE`(Verified)~/if`</div>
~/if`
~if $TIME_TO_CALL_START`
<div >Suitable time to call</div>
<div class="b mb">~$TIME_TO_CALL_START` to ~$TIME_TO_CALL_END`</div>
~/if`
 ~if $SHOW_ADDRESS`
<div >Address</div>
<div class="b mb">~$SHOW_ADDRESS|decodevar`</div>
~/if`
~if $SHOW_PARENTS_ADDRESS`
<div >Parent's Address</div>
<div class="b mb">~$SHOW_PARENTS_ADDRESS|decodevar`</div>
~/if` 
~if $EMAIL_ID`
<div >email ID</div>
<div class="b mb">~$EMAIL_ID`</div>
~/if` 
~if $SHOW_MESSENGER`
<div >Messenger ID</div>
<div class="b mb">~$SHOW_MESSENGER`</div>
~/if`  
~else`
      <div>Contact Details are locked<br>
      
<br>
<br>
<img src="~sfConfig::get('app_img_url')`/images/locked1.gif"></div>
~/if`
</div>
<p class="clr"></p>         
<div style="border-bottom: 1px solid #cccccc;"></div>
   
<div class="protop1 b" >About ~$HIMHER`</div>    
<div>~if $YOURINFO`
<span class="no_b">~$YOURINFO|decodevar`</span>
~/if`</div>    
   
   
<br />

<div class="protop1 b" >Basic Information of ~$PROFILENAME`</div>    


<!--left basic information-->
~include_partial("profile/leftbasicinfr",['AGE'=>$AGE,'HEIGHT'=>$HEIGHT,'PROFILEGENDER'=>$PROFILEGENDER,'religionSelf'=>$religionSelf,'MTONGUE'=>$MTONGUE,'CASTE'=>$CASTE,'SUBCASTE'=>$SUBCASTE,casteLabel=>$casteLabel,sectLabel=>$sectLabel,CODEOWN=>$CODEOWN])`
<!--end left basic information-->

<!--right basic information-->
~include_partial("profile/rightbasicinfr",['MSTATUS'=>$MSTATUS,'Annulled_Reason'=>$Annulled_Reason,'CHILDREN'=>$CHILDREN,'EDU_LEVEL_NEW'=>$EDU_LEVEL_NEW,'OCCUPATION'=>$OCCUPATION,'CITY_RES'=>$CITY_RES,'COUNTRY_RES'=>$COUNTRY_RES,'INCOME'=>$INCOME,'religionSelf'=>$religionSelf,'GOTHRA'=>$GOTHRA,'GOTHRA_MATERNAL'=>$GOTHRA_MATERNAL,'RELATION'=>$RELATION,casteLabel=>$casteLabel,sectLabel=>$sectLabel,CODEOWN=>$CODEOWN,'PRINT'=>1])`
<!--end right basic information-->

<p class="clr"></p>

<div class="sp12"></div>
~include_partial("profile/printmoreabout",['NameValueArr'=>$moreAboutArr,'PROFILENAME'=>$PROFILENAME])`
<div class="sp12"></div>
 


<div class="sp12"></div>
<!-- Religion and Ethnicity start here -->
~include_partial("profile/genericProfileSection",['NameValueArr'=>$ReligionAndEth,'isEdit'=>$isEdit,'LabelHeading'=>"Religion and Ethnicity",'viewPage'=>"1","CODEOWN"=>$CODEOWN])`
<!-- Religion and Ethnicity end here -->
~if $profile->getSHOW_HOROSCOPE() neq 'D'`
~include_partial("profile/genericProfileSection",['NameValueArr'=>$AstroKundaliArr,'isEdit'=>$isEdit,'LabelHeading'=>"Astro/ Kundali Details",'rightSect'=>1,'viewPage'=>"1","CODEOWN"=>$CODEOWN,'HOROSCOPE'=>$HOROSCOPE,HIDE_HORO=>$HIDE_HORO,PROFILECHECKSUM=>$PROFILECHECKSUM])`
~else`
<div class="lf" style="width:48%;margin-left:20px;_margin-left:10px;">
<div class="lf pd5 subhd">Astro/ Kundali Details&nbsp;~if $isEdit`<a href="/profile/editProfile?flag=~$editFlag`&width=700" style="font-size:14px; color:#0f71ae;" class="thickbox">[Edit]</a>~/if`</div>

<div class="row2 no_b">User has hidden all Astro/Kundli Details.Please email/call the person to get horoscope/kundli.
~if $paid eq 'N' and !$SAMEGENDER and $LOGIN`<div style="padding-top:5px">To view contact details <br><br><input type="button" class="b green_btn" value="Buy Membership" style="width:146px;"  onclick="javascript:{document.location= '~sfConfig::get('app_site_url')`/profile/mem_comparison.php?from_source=Horoscope_Request_From_Detailed';}">
</div>
~/if`
</div>
</div>
~/if`
<div class="sp12"></div>
<!-- Family Details start here -->
~include_partial("profile/genericProfileSection",['NameValueArr'=>$familyArr,'isEdit'=>$isEdit,'LabelHeading'=>"Family Details",'viewPage'=>"1","CODEOWN"=>$CODEOWN])`
<!-- Family details end here -->
<!-- Education Section starts -->
~include_partial("profile/genericProfileSection",['NameValueArr'=>$educationAndOccArr,'isEdit'=>$isEdit,'LabelHeading'=>"Education and Occupation",'rightSect'=>1,'viewPage'=>"1","CODEOWN"=>$CODEOWN])`
<!-- Education Section ends -->

<div class="sp12"></div>
<!-- LifeStyle Section starts -->
~include_partial("profile/genericProfileSection",['NameValueArr'=>$lifeAttrArray,'isEdit'=>$isEdit,'LabelHeading'=>"Lifestyle and Attributes","viewPage"=>"1","CODEOWN"=>$CODEOWN])`
<!-- Lifestyle Section ends -->
<!-- Hobbies Section starts -->
~include_partial("profile/genericProfileSection",['NameValueArr'=>$Hobbies,'isEdit'=>$isEdit,'LabelHeading'=>"Hobbies and Interest",'rightSect'=>1,"viewPage"=>"1","CODEOWN"=>$CODEOWN])`
<!-- Hobbies Section end -->
<p class=" clr_18"></p>
~include_partial("profile/dppPart",['loginProfile'=>$profile,'dpartner'=>$profile->getJpartner(),'casteLabel'=>$casteLabel,'religionSelf'=>$religionSelf,"viewPage"=>"1","CODEDPP"=>$CODEDPP])`
<p class="clr_18"></p>
<div class="lbdm1" ~if $pdf`style="width:583px"~/if`>
<ul>
<li style="font-size:16px;"><strong style="color:#3c3c3c;">Profile Page :</strong> <a href="#" onclick="return false" style="cursor:default">~sfConfig::get("app_site_url")`/profiles/~$PROFILENAME`</a></li>


</ul>

</div>
 <p class=" clr_18" ~if $pdf` style="height:7px"~/if`></p>


</div>

</div>
