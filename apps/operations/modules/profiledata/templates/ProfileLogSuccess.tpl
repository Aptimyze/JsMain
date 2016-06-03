<div id="main_cont">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/min/?f=/css/common_css_2.css,/css/hdr_css_new_2.css,/css/phonelayerStyle_css_1.css,/css/hdr_sulekha_1.css,/css/view_page_css_1.css" type="text/css">

<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/profile/images/styles.css" type="text/css">



<form action="~$moduleurl`/search" method="post">
<input type="hidden" name=cid value="~$cid`">
~include_partial('global/header')`
<br>

<!--
<table width="86%" border="0" cellspacing="1" cellpadding="2" align="center">
<tr class="fieldsnew">
	
	<td class="formhead" valign="middle" colspan="12" align="center" >Duplicate Profiles </td>
	</tr>
<br>~if $duplicateArr`
	<TR class="formhead" valign="middle" colspan="2" align="center"><TH>User Name</TH>
	<Tr>
	
	~foreach from=$duplicateArr  key=k item=v`
	<TR>
	
	
	<TD class="label" valign="middle" colspan="1" align="center" ><a href="~$moduleurl`/linkPage?cid=~$cid`&profileid1=~$k`"target="_blank"> ~$v`</a></TD>
</tr>
	
	~/foreach`
	~else`
	<tr class="fieldsnew">
	
	<td class="formhead" valign="middle" colspan="12" align="center" >No duplicate Profile found </td>
	</tr>
	~/if`
</table>
-->
<table width="86%" border="0" cellspacing="1" cellpadding="2" align="center">
<tr class="fieldsnew">
	
	<td class="formhead" valign="middle" colspan="12" align="center" > Profile Retrival Data</td>
	</tr>
<br>
	<TR class="formhead" valign="middle" colspan="2" align="center"><TH>Date</TH><TH>Reason</TH><TH>Retrived By</TH>

	~foreach $retrievedArr as $kk`
	<Tr>
	
	~foreach $kk as $i`
	<TD class="label" valign="middle" colspan="1" align="center" >~$i|decodevar`</TD>
	
	~/foreach`
	</tr>
	~/foreach`
	
</table>
<table width="86%" border="0" cellspacing="1" cellpadding="2" align="center">
<tr class="fieldsnew">
	
	<td class="formhead" valign="middle" colspan="12" align="center" >Deleted Profile Data (If profile was deleted) </td>
	</tr>
<br>
	<TR class="formhead" valign="middle" colspan="1" align="center"><TH>Date</TH><TH>Reason</TH><TH>Deleted By</TH>
	
	<Tr>
	~foreach from=$deletedArr key=kk item=vv`
		
	<Tr>
	
	<TD class="label" valign="middle" colspan="1" align="center" >~$vv.PROFILE_DEL_DATE`</TD>
	<TD class="label" valign="middle" colspan="1" align="center" >~$vv.REASON|decodevar`</TD>
	<TD class="label" valign="middle" colspan="1" align="center" >~$vv.DELETED_BY`</TD>
	</tr>
	
	~/foreach`
	
	<BR>
 <BR><BR>
 <table width="86%" border="0" cellspacing="1" cellpadding="2" align="center">
<tr class="fieldsnew">
	
	<td class="formhead" valign="middle" colspan="12" align="center" >Profile Data</td>
	</tr>
	<tr class="formhead" valign="middle" colspan="1" align="center"><th > Registraion Date</th><th>I.P.</th></tr>
        <Tr>

        <TD class="label" valign="middle" colspan="1" align="center" >~$profileDetailArr.ENTRY_DT`</TD>
        <TD class="label" valign="middle" colspan="1" align="center" >~$profileDetailArr.IPADD`</TD>
        </tr>
<br>
	
</table>
 <table width="100%" border="0" cellspacing="1" cellpadding="2" align="center">
 <Tr><td>
 <div class="fl" style="width:390px">
<div class="protop b" style="float:left;">
<div class="fl">About ~$HIMHER` &nbsp;</div>


<p style="height:5px;"></p>

</div> <div style="float:left;padding:2px 3px 5px 0px;clear:both;width:390px">
~include_partial("moreabout",['NameValueArr'=>$moreAboutArr,'InfoLimit'=>$InfoLimit,'PROFILENAME'=>$PROFILENAME])`
<br />

<div class="fl" style="margin-top:7px">
</div>
 </div>
</div>
    
<!-- contact details -->
~include_partial("contactdetails",['nameofuser'=>$nameofuser,'PHONE_MOB'=>$PHONE_MOB,'PHONE_RES'=>$PHONE_RES,'EMAIL'=>$EMAIL,'PADDRESS'=>$P_ADDRESS,'ADDRESS'=>$ADDRESS,'MESSENGER'=>$MESSENGER,'Alt_Mob'=>$AltMob])`
<div class="clr"></div>
<!-- contact details ends here -->    
 </BR></BR>
 
 <div class="lf t12 b" style="width:790px;padding:5px; border-right: 1px solid #aeaeae; margin-right:3px;" id="profileData">
<div class="protop1 b"><h3>Basic Information of ~$profile->getUSERNAME()`:</h3></div>

<!--left basic information-->
~include_partial("leftbasicinfr",['AGE'=>$AGE,'HEIGHT'=>$HEIGHT,'PROFILEGENDER'=>$PROFILEGENDER,'religionSelf'=>$religionSelf,'MTONGUE'=>$MTONGUE,'CASTE'=>$CASTE,'SUBCASTE'=>$SUBCASTE,casteLabel=>$casteLabel,sectLabel=>$sectLabel,CODEOWN=>$CODEOWN])`
<!--end left basic information-->

<!--right basic information-->
~include_partial("rightbasicinfr",['MSTATUS'=>$MSTATUS,'Annulled_Reason'=>$Annulled_Reason,'CHILDREN'=>$CHILDREN,'EDU_LEVEL_NEW'=>$EDU_LEVEL_NEW,'OCCUPATION'=>$OCCUPATION,'CITY_RES'=>$CITY_RES,'COUNTRY_RES'=>$COUNTRY_RES,'INCOME'=>$INCOME,'religionSelf'=>$religionSelf,'GOTHRA'=>$GOTHRA,'GOTHRA_MATERNAL'=>$GOTHRA_MATERNAL,'RELATION'=>$RELATION,casteLabel=>$casteLabel,sectLabel=>$sectLabel,CODEOWN=>$CODEOWN])`
<!--end right basic information-->


<p class="clr"></p>

<div class="sp12"></div>

<div class="sp12"></div>
<div class="rf" style="margin:5px 7px 0px;"><a href="#" class="b blink">Go to top <img src="~sfConfig::get("app_img_url")`/images/icon_blue_up.gif" border="0"></a></div>
<div class="sp12"></div>
<!-- Religion and Ethnicity start here -->
~include_partial("genericProfileSection",['NameValueArr'=>$ReligionAndEth,'isEdit'=>$isEdit,'LabelHeading'=>"Religion and Ethnicity",'viewPage'=>"1","CODEOWN"=>$CODEOWN])`
<!-- Religion and Ethnicity end here -->

~if $profile->getSHOW_HOROSCOPE() neq 'D'`
~include_partial("genericProfileSection",['NameValueArr'=>$AstroKundaliArr,'isEdit'=>$isEdit,'LabelHeading'=>"Astro/ Kundali Details",'rightSect'=>1,'viewPage'=>"1","CODEOWN"=>$CODEOWN])`
~/if`
<div class="sp12"></div>
<!-- Family Details start here -->
~include_partial("genericProfileSection",['NameValueArr'=>$familyArr,'isEdit'=>$isEdit,'LabelHeading'=>"Family Details",'viewPage'=>"1","CODEOWN"=>$CODEOWN])`
<!-- Family details end here -->
<!-- Education Section starts -->
~include_partial("genericProfileSection",['NameValueArr'=>$educationAndOccArr,'isEdit'=>$isEdit,'LabelHeading'=>"Education and Occupation",'rightSect'=>1,'viewPage'=>"1","CODEOWN"=>$CODEOWN])`
<!-- Education Section ends -->

<div class="sp12"></div>
<!-- LifeStyle Section starts -->
~include_partial("genericProfileSection",['NameValueArr'=>$lifeAttrArray,'isEdit'=>$isEdit,'LabelHeading'=>"Lifestyle and Attributes","viewPage"=>"1","CODEOWN"=>$CODEOWN])`
<!-- Lifestyle Section ends -->
<!-- Hobbies Section starts -->
~include_partial("genericProfileSection",['NameValueArr'=>$Hobbies,'isEdit'=>$isEdit,'LabelHeading'=>"Hobbies and Interest",'rightSect'=>1,"viewPage"=>"1","CODEOWN"=>$CODEOWN])`
<!-- Hobbies Section end -->
<p class=" clr_18"></p>
<div style="margin: 5px 7px 0px;" class="rf"><a class="b blink" href="#">Go to top <img border="0" src="~sfConfig::get("app_img_url")`/images/icon_blue_up.gif"></a></div> ~include_partial("dppPart",['loginProfile'=>$profile,'dpartner'=>$profile->getJpartner(),'casteLabel'=>$casteLabel,'religionSelf'=>$religionSelf,"viewPage"=>"1",'PROFILENAME'=>$PROFILENAME,'show_nhandicap'=>$show_nhandicap])`
<p class="clr_18"></p>

 <p class=" clr_18"></p>


</div>
</td></tr>
</table>

	
</table>
~if !$sf_request->getParameter("actiontocall")`
<table width="83%" border="0" cellspacing="1" cellpadding="2" align="center">
<tr class="fieldsnew">
	
	<td  valign="middle" colspan="11" align="right" ><a href="~$moduleurl`/linkPage?cid=~$cid`&profileid1=~$profileid`">go to back</a></td>
	</tr>
 </table>
 ~/if`

~include_partial('global/footer')`
</div>
