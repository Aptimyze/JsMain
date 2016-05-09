~if $AGE`
<div class="row2 no_b">
<label>Age</label><div class="rf wgy9f ~$CODEOWN.AGE`" >: ~$AGE` Years 
</div></div>
~/if`
~if $HEIGHT`
<div class="row2 no_b ">
<label>Height</label><div class="rf wgy9f ~$CODEOWN.HEIGHT`" >: ~$HEIGHT|decodevar`
</div></div>
~/if`

~if $PROFILEGENDER`
<div class="row2 no_b ">
<label>Gender</label><div class="rf wgy9f">: ~$PROFILEGENDER`
</div></div>
~/if`

~if $religionSelf`
<div class="row2 no_b ">
<label>Religion</label><div class="rf wgy9f ~$CODEOWN.RELIGION`" >: ~$religionSelf`
</div></div>
~/if`

~if $MTONGUE`
<div class="row2 no_b ">
<label>Mother Tongue</label><div class="rf wgy9f ~$CODEOWN.MTONGUE`" >: ~$MTONGUE`
</div></div>
~/if`

~if $CASTE neq ''`
<div class="row2 no_b ">
<label>~$casteLabel`</label><div class="rf wgy9f ~$CODEOWN.$casteLabel`" >: ~$CASTE` 
</div></div>
~/if`
~if $CASTE neq ''`
~if $SUBCASTE`
<div class="row2 no_b ">
<label>Sub Caste</label><div class="rf wgy9f" >: ~$SUBCASTE`
</div></div>
~/if`
~/if`



~if $MSTATUS`
<div class="row2 no_b ">
<label>Marital Status</label><div class="rf wgy5f ~$CODEOWN.MSTATUS`"  style="position:relative">: ~if $Annulled_Reason`<span onmouseover="annulled_show()" ~if $MSTATUS eq 'Awaiting Divorce'`style="border-bottom: 3px double rgb(255, 102, 0); color: rgb(255, 102, 0); position: relative;width:107px;padding:0px;"~else`style="border-bottom: 3px double rgb(255, 102, 0); color: rgb(255, 102, 0); position: relative;width:57px;padding:0px;"~/if`>~$MSTATUS`</span>
<div style="border: 1px none rgb(0, 0, 0); position: absolute; font:normal 12px arial; left: 20px; top: -140px; width: 281px; height: 142px; z-index: 1501; background-image: url(~sfConfig::get('app_img_url')`/profile/images/annulled_mess.gif); display: none;" id="annulled_reason"><br/>
<div align="right" style="text-decoration: none; padding-right: 16px;"><a onclick="javascript:annulled_hide()" style="cursor: pointer;"><img title="close" src="~sfConfig::get('app_img_url')`/img_revamp/close.png"/></a></div>
<div style="overflow: auto; position: absolute; left: 16px; top: 30px; width: 251px; height: 80px; z-index: 1000; visibility: visible;" class="bele" id="Layer2">~$Annulled_Reason`</div>
</div>
~else`
~$MSTATUS`
~/if`
</div></div>
~/if`
~if $MSTATUS neq 'Never Married' &&  $CHILDREN neq ''`
<div class="row2 no_b ">
<label>Have Children  </label><div class="rf wgy5f ~$CODEOWN.HAVECHILD`" >: ~$CHILDREN`
</div></div>
~/if`
~if $EDU_LEVEL_NEW`
<div class="row2 no_b ">
<label>Education  </label><div class="rf wgy5f ~$CODEOWN.ELEVEL_NEW`" >: ~$EDU_LEVEL_NEW`
</div></div>
~/if`
~if $OCCUPATION`
<div class="row2 no_b ">
<label>Occupation </label><div class="rf wgy5f ~$CODEOWN.OCCUPATION`" >: ~$OCCUPATION`
</div></div>
~/if`
~if $CITY_RES ||  $COUNTRY_RES`
<div class="row2 no_b ">
<label>Location</label><div class="rf wgy5f">: <span class="~$CODEOWN.CITYRES`" style="padding:0px;margin:0px;width:auto">~$CITY_RES`</span>~if $COUNTRY_RES neq ''`<span class="~$CODEOWN.COUNTRYRES`" style="padding:0px;margin:0px;width:auto">~if $CITY_RES neq ''`,~/if` ~$COUNTRY_RES`</span>~/if`
</div></div>
~/if`
~if $INCOME`
<div class="row2 no_b ">
<label>Annual Income</label><div class="rf wgy5f ~$CODEOWN.INCOME`" >: ~if $INCOME eq ''` - ~else` ~$INCOME` ~/if` 
</div></div>
~/if`

~if $religionSelf eq 'Hindu'`
~if $GOTHRA`
<div class="row2 no_b ">
<label>Gothra (Paternal)</label><div class="rf wgy5f" >: ~$GOTHRA`
</div></div>
~/if`
~/if`
~if $religionSelf eq 'Hindu'`
~if $GOTHRA_MATERNAL`
<div class="row2 no_b ">
<label>Gothra (Maternal)</label><div class="rf wgy5f" >: ~$GOTHRA_MATERNAL`
</div></div>
~/if`
~/if`
~if $RELATION`
<div class="row2 no_b ">
<label>Posted By</label><div class="rf wgy5f" >: ~$RELATION`
</div></div>
~/if`
