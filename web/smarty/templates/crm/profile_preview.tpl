<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>JeevanSathi Matrimonilas - Profile</title>
<link href="/P/images/styles.css" rel="stylesheet" type="text/css">
<link href="/P/imagesnew/styles.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
}
-->
</style>
<script language="javascript">

function MM_openBrWindow(theURL,winName,features)
{
        window.open(theURL,winName,features);
}

</script>

<br>
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
  <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td ><font face="Arial" size="2" color="#666666"><b>Personal Profile of ~$PROFILENAME`</b></font></td>
   </tr>
  </table>
  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bggrey2">
   <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="bgwhite">
      <tr>
       <td  class="bgbrownL3"><img src="/P/imagesnew/zero.gif" width="8" height="8"></td>
       <td  class="bgbrownL3"><img src="/P/imagesnew/zero.gif" width="180" height="1"><br>
	~if $UPLOADPHOTO`
		<a href="../social/addPhotos?checksum=~$CHECKSUM`"><img src="~$PHOTOFILE`" width="150" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;"></a>
		<!--<a href="uploadphoto.php?checksum=~$CHECKSUM`"><img src="~$PHOTOFILE`" width="150" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;"></a>-->
	~else`
	 ~if $PHOTOFILE eq "../profile/images/no_photo.gif"`
	    ~if $GENDER eq "M"`
	      <a href="" onclick="MM_openBrWindow('~$SITE_URL`/profile/photorequest.php?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&filtered=~$FILTERED`&samegender=~$SAMEGENDER`','','width=370,height=330'); return false;"><img src="/profile/images/Request-a-photo-male.jpg" width="150" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;"></a>
	    ~else`
	      <a href="" onclick="MM_openBrWindow('~$SITE_URL`/profile/photorequest.php?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&filtered=~$FILTERED`&samegender=~$SAMEGENDER`','','width=370,height=330'); return false;"><img src="/profile/images/Request-a-photo-Female.jpg" width="150" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;"></a>
	    ~/if`
	 ~else`
	  <table background="~$PHOTOFILE`" width="150" height="200" border="0" cellpadding="0" cellspacing="0">
	    <tr>
	      <td>
		  <img src="~$PHOTOFILE`" width="150" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;">
	      </td>
	    </tr>
	  </table>
	 ~/if`
	~/if`
	<table width="80%"  border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
        <td><img src="/P/imagesnew/zero.gif" width="1" height="2"></td>
        </tr>
        <tr>
         <td colspan="3" class="mediumblack"><span class="blacklinku">
  	  ~if $ISALBUM`
	    <a href="" onclick="MM_openBrWindow('/P/photocheck.php?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&seq=1','','width=400,height=500,scrollbars=yes'); return false;">Click here for Album</a>
	  ~elseif $FULLVIEW`
	    <a href="" onclick="MM_openBrWindow('/P/photocheck.php?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&seq=1','','width=400,height=500,scrollbars=yes'); return false;">Click here for Full View</a>
	  ~else`&nbsp;
	  ~/if`
	</span></td>
        </tr>
~if $PHOTOSTATUS eq "H"`<tr><td class="mediumred" align="center">Photo Hidden</td></tr>~/if`
~if $PHOTOSTATUS eq "C"`<tr><td class="mediumred" align="center">Photo Visible when contact accepted</td></tr>~/if`
~if $PHOTOSTATUS eq "F"`<tr><td class="mediumred" align="center">Photo Visible when not filtered</td></tr>~/if`
	</table>
	</td>
       <td width="100%" rowspan="2" valign="top" class="mediumblack">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td>
<!-- CONTACT GRID -->
         <table width="144" border="0" align="right" cellpadding="0" cellspacing="0">
~if $NO_FORWARD_OPTION neq "1"`
        <tr>
        <td><img src="/P/imagesnew/zero.gif" width="144" height="1"></td>
        </tr>
        <tr>
        <td><img src="/P/imagesnew/p_forward.gif" width="144" height="16" border="0"></td>
        </tr>
~/if`
<tr>
<td><img src="/P/imagesnew/zero.gif" width="144" height="1"></td>
</tr>
<tr>
<td><img src="/P/imagesnew/p_seeprint_version.gif" width="144" height="16" border="0"></td>
</tr>
~if $HOROSCOPE eq "Y"`
        <tr>
        <td><img src="/P/imagesnew/zero.gif" width="144" height="1"></td>
        </tr>
        <tr>
        <td><img src="/P/imagesnew/p_match_horo.gif" width="144" height="16" border="0"></td>
        </tr>
~/if`
~if $KUNDALI eq "Y"`
        <tr>
        <td><img src="/P/imagesnew/zero.gif" width="144" height="1"></td>
        </tr>
        <tr>
        <td><img src="/P/imagesnew/p_match_kundali.gif" width="144" height="16" border="0"></td>
        </tr>
~/if`
~if $REQUESTKUNDALI eq "Y"`
        <tr>
        <td><img src="/P/imagesnew/zero.gif" width="144" height="1"></td>
        </tr>
        <tr>
        <td><img src="/P/imagesnew/p_match_kundali.gif" width="144" height="16" border="0"></td>
        </tr>
~/if`
~if $ECLASSIFIED_MEM eq 'Y'`
        <tr>
        <td><img src="/P/imagesnew/zero.gif" width="144" height="1"></td>
        </tr>
        <tr>
        <td><img src="/P/imagesnew/p_mem_eclass.gif" width="144" height="30" border="0"></td>
        </tr>
~/if`
         </table>
	</td>
<!-- CONTACT GRID ENDS -->
	</tr>
        <tr>
	<td colspan=2><br>
	<table width="320" align="center" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td>
	<div align="justify">
        <span class="bigblack" valign="top"><br>
	~if $PROFILENAME eq "test4js"`
		<!-- google_ad_region_start=otherinfo -->
		~$COMMENTS`
		~$SUBYOURINFO`
		~$YOURINFO`
		<!-- google_ad_region_end=otherinfo -->
	~else`
		<!-- google_ad_region_start=otherinfo -->
		~$SUBYOURINFO`
		~$YOURINFO`
		<!-- google_ad_region_end=otherinfo -->
	~/if`
	</span>
	~if $PROFILEGENDER eq 'Male' and $LIVE_WITH_PARENTS neq "-"`
		<br><br>
		<span class=bigblack><b>Living with parents:</b></span>
		<span class="bigblack" valign="top">
		~$LIVE_WITH_PARENTS`
		</span>
	~/if`
	~if $PROFILEGENDER eq 'Female' and $CAREER_AFTER_MARRIAGE neq ""`
		<br><br>
		<span class=bigblack><b>Plan to work after marriage:</b></span>
        	<span class="bigblack" valign="top">
		~$CAREER_AFTER_MARRIAGE`
		</span>
	~/if`
	~if $FAMILYINFO neq ""`
		<br><br>
		<span class=bigblack><b>~$about2` family:</b></span>
        	<span class="bigblack" valign="top">
		~$FAMILYINFO`
		</span>
	~/if`
	~if $YOURINFO_SCREEN eq 'Y'`
		<br><br>
		<span class="mediumred">*This field is under screening.</span>
	~/if`
	</div>
	</td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</td>
      </tr>
      <tr>
       <td class="bgbrownL3">&nbsp;</td>
       <td class="bgbrownL3" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
         <td colspan="3" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="1"></td>
        </tr>
	~if $RELATION`
        <tr>
         <td width="43%" class="mediumblack">Posted By </td>
         <td width="3%" class="mediumblack">:</td>
         <td width="54%" class="mediumgreybl">&nbsp;~$RELATION`</td>
        </tr>
	~/if`
	<!-- Social -->	
	<tr>
	<td width="43%" class="mediumblack">Person handling Profile </td>
	<td width="3%" class="mediumblack">:</td>
	<td width="54%" class="mediumgreybl">&nbsp;~$PROFILE_HANDLER_NAME`</td>
	</tr>

	~if $ENTRY_DATE neq ""`
	<tr>
	<td class="mediumblack">Registered On</td>
	<td class="mediumblack">:</td>
	<td class="mediumgreybl">&nbsp;~$ENTRY_DATE`</td>
	</tr>
	~/if`
	<!--	
	~if $SOURCE neq ""`
        <tr>
        <td class="mediumblack">Source</td>
        <td class="mediumblack">:</td>
        <td class="mediumgreybl">&nbsp;~$SOURCE`</td>
        </tr>
        ~/if`
	-->
	~if $MOD_DATE neq ""`
	<tr>
	<td class="mediumblack">Last Updated</td>
	<td class="mediumbalck">:</td>
	<td class="mediumgreybl">&nbsp;~$MOD_DATE`</td>
	</tr>
	~/if`
	~if $LAST_LOGIN_DT neq ""`
        <tr>
         <td class="mediumblack">Last Online </td>
         <td class="mediumblack">:</td>
         <td class="mediumgreybl">&nbsp;~$LAST_LOGIN_DT`</td>
        </tr>
	~/if`
	~if $PHOTODATE neq ""`
	<tr>
	<td class="mediumblack">Photo Modified On</td>
	<td class="mediumblack">:</td>
	<td class="mediumgreybl">&nbsp;~$PHOTODATE`</td>
	</tr>
	~/if`
        <tr>
         <td class="mediumblack">Gender </td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$PROFILEGENDER`</td>
        </tr>
        <tr>
         <td class="mediumblack">Height</td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$HEIGHT`</td>
        </tr>
        <tr>
         <td class="mediumblack">Age</td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$AGE`</td>
        </tr>
        <tr>
         <td class="mediumblack">Country living in</td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~if $CITY_RES neq ""`~$CITY_RES`, ~/if`~$COUNTRY_RES`</td>
        </tr>
	<tr>
         <td class="mediumblack">Religion</td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$RELIGION_SELF`</td>
        </tr>
        <tr>
         <td class="mediumblack">Caste</td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$CASTE`</td>
        </tr>
        <tr>
         <td class="mediumblack">Occupation</td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$OCCUPATION`</td>
        </tr>
        <tr>
         <td class="mediumblack">Marital Status </td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$MSTATUS`</td>
        </tr>
        <tr>
         <td class="mediumblack">Have Children </td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$CHILDREN`</td>
        </tr>
	~if $INCOME`
       <tr>
        <td class="mediumblack">Annual Income </td>
        <td class="mediumblack">:</td>
        <td class="mediumblack">&nbsp;~$INCOME`</td>
       </tr>
	~/if`
        <tr>
         <td colspan="3"><img src="/P/imagesnew/zero.gif" width="1" height="1"></td>
         </tr>
       </table></td>
      </tr>
    </table></td>
   </tr>
  </table>
  ~if $ECLASSIFIED_MEM eq 'Y' and $CONTACTDETAILS eq '1'`
   <table width="100%"  border="0" cellspacing="1" cellpadding="0" class="bggrey2">
    <tr>
     <td><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="bgwhite">
      <tr>
       <td width="19%"><img src="/P/imagesnew/p_eclassifides.gif" width="279" height="48"></td>
       <td width="81%"><table width="96%"  border="0" align="center" cellpadding="0" cellspacing="0">
	~if $ADDRESS neq ""`
        <tr>
         <td width="35%" class="mediumblackb">Contact Address </td>
         <td width="1%" class="mediumblack">:</td>
         <td width="64%" class="mediumblackb">&nbsp;~$ADDRESS`</td>
        </tr>
	~/if`
	~if $PARENT_ADDRESS neq ""`
        <tr>
         <td width="35%" class="mediumblackb">Address of Parents</td>
         <td width="1%" class="mediumblack">:</td>
         <td width="64%" class="mediumblackb">&nbsp;~$PARENTS_ADDRESS`</td>
        </tr>
	~/if`
	~if $PHONE neq ""`
        <tr>
         <td class="mediumblackb">Phone No.</td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$PHONE`</td>
        </tr>
	~/if`

	~if $SHOWALT_MOBILE eq 'Y' && $ALT_MOBILE neq ''`
	<tr>
	<td class="mediumblackb">Alternate Mobile</td>
	<td class="mediumblack">:</td>
	<td class="mediumblack">&nbsp;~$ALT_MOBILE`</td>
	</tr>
	~/if`

	~if $MESSENGER_CHANNEL neq "" and $MESSENGER_ID neq ""`
        <tr>
         <td width="35%" class="mediumblackb">~$MESSENGER_CHANNEL` Messenger Id. </td>
         <td width="1%" class="mediumblack">:</td>
         <td width="64%" class="mediumblackb">&nbsp;~$MESSENGER_ID`</td>
        </tr>
	~/if`

        ~if SHOW_ALT_MESSENGER eq 'Y' && $ALT_MESSENGER_CHANNEL neq '' && $ALT_MESSENGER_ID neq ''`
        <tr>
         <td width="35%" class="mediumblackb">~$ALT_MESSENGER_CHANNEL` Alternate Messenger Id. </td>
         <td width="1%" class="mediumblack">:</td>
         <td width="64%" class="mediumblackb">&nbsp;~$ALT_MESSENGER_ID`</td>
        </tr>
        ~/if`

        <tr>
         <td height="19" class="mediumblackb">E-mail</td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$HISEMAIL`</td>
        </tr>
       </table></td>
      </tr>
     </table></td>
    </tr>
   </table>
  ~/if`
  <br>
  <br>
  <table width="100%"  border="0" cellspacing="1" cellpadding="0" class="bggrey2">
   <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="bgwhite">
     <tr class="bggrey">
      <td colspan="2" class="mediumgreyb">&nbsp; &nbsp;More About ~$PROFILENAME`</td>
     </tr>
     <tr>
      <td colspan="2"><img src="/P/imagesnew/zero.gif" width="1" height="5"></td>
     </tr>
     <tr>
      <td width="50%" valign="top"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
       <tr class="bgbrownL3">
        <td colspan="3" class="mediumgreyb">&nbsp;Religion And Ethnicity </td>
       </tr>
       <tr>
        <td colspan="3" valign="top" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="3"></td>
       </tr>
       <tr>
        <td width="28%" valign="top" class="mediumblack">Religion</td>
        <td width="2%" valign="top" class="mediumblack">:</td>
        <td width="70%" valign="top" class="mediumblack">&nbsp;~$RELIGION_SELF`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Mother Tongue </td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$MTONGUE`</td>
       </tr>
	<!--HINDU-->
	~if $RELIGION_SELF eq 'Hindu'`
	<tr>
        <td valign="top" class="mediumblack">Caste</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$CASTE`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Sub Caste/Surname</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$SUBCASTE`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Nakshatra</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$NAKSHATRA`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Rashi/ Moon Sign</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$RASHI`</td>
        </tr>

	<!-- Social -->
	<tr>
	<td valign="top" class="mediumblack">Sun Sign </td>
	<td valign="top" class="mediumblack">:</td>
	<td valign="top" class="mediumblack">&nbsp;~$SUNSIGN`</td>
	</tr>
	<tr>
        <td valign="top" class="mediumblack">Gothra(Paternal)</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$GOTHRA`</td>
        </tr>

	 <!-- Social -->
        <tr>
        <td valign="top" class="mediumblack">Gothra (Maternal)</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$GOTHRA_MATERNAL`</td>
        </tr>
        <tr>
	
	<tr>
        <td valign="top" class="mediumblack">Native Place</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$NATIVE_PLACE`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Horoscope Match needed</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $HOROSCOPE_MATCH eq 'Y'`Yes~else`No~/if`</td>
        </tr>
	<!--HINDU-->
	~elseif $RELIGION_SELF eq 'Jain'`
	<tr>
        <td valign="top" class="mediumblack">Caste</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$CASTE`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Sampraday</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$SAMPRADAY`</td>
        </tr>
	<!--JAIN-->
	~elseif $RELIGION_SELF eq 'Christian'`
	<!--CHRISTIAN-->
	<tr>
        <td valign="top" class="mediumblack">Denomination</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$CASTE`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Diocese</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$DIOCESE`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Baptised</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $BAPTISED eq 'Y'`Yes~else`No~/if`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Do You Read Bible Everyday?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $READ_BIBLE eq 'Y'`Yes~else`No~/if`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Do You Offer Tithe Regularly?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $OFFER_TITHE eq 'Y'`Yes~else`No~/if`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Interested in spreading : the Gospel?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $SPREADING_GOSPEL eq 'Y'`Yes~else`No~/if`</td>
        </tr>
	<!--CHRISTIAN-->
	~elseif $RELIGION_SELF eq 'Muslim'`
	<tr>
        <td valign="top" class="mediumblack">Denomination</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$CASTE`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Ma'thab</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$MATHTHAB`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Speak Urdu</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $SPEAK_URDU eq 'Y'`Yes~else`No~/if`</td>
        </tr>	
	<tr>
        <td valign="top" class="mediumblack">Namaz</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$NAMAZ`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Zakat</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $ZAKAT eq 'Y'`Yes~else`No~/if`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Fasting</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$FASTING`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Umrah/Hajj</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$UMRAH_HAJJ`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Do You Read The Quran?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$QURAN`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Sunnah Beard</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$SUNNAH_BEARD`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Sunnah Cap</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$SUNNAH_CAP`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Do You Wear Hijab?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $HIJAB eq 'Y'`Yes~else`No~/if`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Willing To Wear Hijab After Marriage?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $HIJAB_MARRIAGE neq ''`~if $HIJAB_MARRIAGE eq 'Y'`Yes~elseif $HIJAB_MARRIAGE eq 'N'`No~else`Not Decided~/if`~else`-~/if`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Can the Girl Work After Marriage?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $WORKING_MARRIAGE eq 'Y'`Yes~else`No~/if`</td>
        </tr>
	<!--MUSLIM-->
	~elseif $RELIGION_SELF eq 'Sikh'`
	<!--SIKH-->
	 <tr>
        <td valign="top" class="mediumblack">Caste</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$CASTE`</td>
        </tr>
	 <tr>
        <td valign="top" class="mediumblack">Are You Amritdhari?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $AMRITDHARI eq 'Y'`Yes~else`No~/if`</td>
        </tr>
	 <tr>
        <td valign="top" class="mediumblack">Do You Cut Your Hair?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $CUT_HAIR eq 'Y'`Yes~else`No~/if`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Do You Trim Your Beard?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $TRIM_BEARD eq 'Y'`Yes~else`No~/if`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Do You Wear Turban?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $WEAR_TURBAN eq 'Y'`Yes~else`No~/if`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Are You Clean Shaven?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $CLEAN_SHAVEN eq 'Y'`Yes~else`No~/if`</td>
        </tr>
	<!--SIKH-->
	~elseif $RELIGION_SELF eq 'Parsi'`
	<!--PARSI-->
	<tr>
        <td valign="top" class="mediumblack">Are You a Zarathushtri?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $ZARATHUSHTRI eq 'Y'`Yes~else`No~/if`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Are Both Parents Zarathushtri?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $PARENTS_ZARATHUSHTRI eq 'Y'`Yes~else`No~/if`</td>
        </tr>
	<!--PARSI-->
	~/if`
	<!-- Social -->
	<tr>
	<td valign="top" class="mediumblack">Sect</td>
	<td valign="top" class="mediumblack">:</td>
	<td valign="top" class="mediumblack">&nbsp;~$SECT`</td>
	</tr>

      </table></td>
      <td width="50%" valign="top"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
       <tr class="bgbrownL3">
        <td colspan="3" class="mediumgreyb">&nbsp;Lifestyle And Attributes </td>
       </tr>
       <tr>
        <td colspan="3" valign="top" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="3"></td>
        </tr>
       <tr>
        <td width="32%" valign="top" class="mediumblack">Diet</td>
        <td width="2%" valign="top" class="mediumblack">:</td>
        <td width="66%" valign="top" class="mediumblack">&nbsp;~$DIET`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Smoke</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$SMOKE`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Drink</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$DRINK`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Complexion</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$COMPLEXION`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Body Type </td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$BODYTYPE`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Challenged</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$HANDICAPPED`</td>
       </tr>
<!-- Social -->
       <tr>
        <td valign="top" class="mediumblack">Thalassemia</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$THALASSEMIA`</td>
       </tr>

	<tr>
        <td valign="top" class="mediumblack">Blood Group</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$BLOOD_GROUP`</td>
       </tr>
	<tr>
        <td valign="top" class="mediumblack">Weight</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $WEIGHT neq 0`~$WEIGHT`Kg~/if`</td>
       </tr>
	~if $HIV eq 'Y'`
	<tr>
        <td valign="top" class="mediumblack">HIV +</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;Positive</td>
       </tr>
	~/if`
       <tr>
        <td colspan="3" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="5"></td>
        </tr>
      </table></td>
     </tr>
     <tr>
      <td valign="top"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
       <tr class="bgbrownL3">
        <td colspan="3" class="mediumgreyb">&nbsp;Astro/ Kundali Details </td>
       </tr>
       <tr>
        <td colspan="3" valign="top" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="3"></td>
        </tr>
	~if $DTOFBIRTH`
       <tr>
        <td valign="top" class="mediumblack">Date of Birth </td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$DTOFBIRTH`</td>
       </tr>
       <tr>
	~/if`
        <td valign="top" class="mediumblack">Country of Birth</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$COUNTRY_BIRTH`</td>
       </tr>
       <tr>
        <td width="28%" valign="top" class="mediumblack">City of Birth </td>
        <td width="2%" valign="top" class="mediumblack">:</td>
        <td width="70%" valign="top" class="mediumblack">&nbsp;~$CITYBIRTH`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Time of Birth </td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $BTIMEHOUR neq "" and $BTIMEMIN neq ""`~$BTIMEHOUR`hrs:~$BTIMEMIN`mins~else`-~/if`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Nakshatra</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$NAKSHATRA`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Manglik/Chevvai Dosham </td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$MANGLIK`</td>
       </tr>
	    <tr>
        <td colspan="3" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="5"></td>
        </tr>
      </table></td>
      <td valign="top"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
       <tr class="bgbrownL3">
        <td colspan="3" class="mediumgreyb">&nbsp;Education and Occupation </td>
       </tr>
       <tr>
        <td colspan="3" valign="top" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="3"></td>
        </tr>
       <tr>
        <td width="32%" valign="top" class="mediumblack">Education Level </td>
        <td width="2%" valign="top" class="mediumblack">:</td>
        <td width="66%" valign="top" class="mediumblack">&nbsp;~$EDUCATION_LEVEL`</td>
       </tr>
	<!-- Social-->

<tr>
<td valign="top" class="mediumblack">School Name </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$SCHOOL`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">College Name</td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$COLLEGE`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Graduation Degree </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$UG_DEGREE`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Other Graduation Degree </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$OTHER_UG_DEGREE`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Other Graduation College </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$OTHER_UG_COLLEGE`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">PG College </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$PG_COLLEGE`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">PG Degree </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$PG_DEGREE`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Other PG Degree </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$OTHER_PG_DEGREE`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Other PG College </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$OTHER_PG_COLLEGE`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Highest Degree </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$EDU_LEVEL_NEW`</td>
</tr>
<tr>
</tr>

       <tr>
        <td valign="top" class="mediumblack">Educational Qualification </td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$EDUCATION`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Occupation</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$OCCUPATION`</td>
       </tr>
	~if $INCOME`
       <tr>
        <td valign="top" class="mediumblack">Annual Income </td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$INCOME`</td>
       </tr>
	~/if`
	<!-- Social -->
<tr>
<td valign="top" class="mediumblack">Organization Name</td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$COMPANY_NAME`</td>
</tr>

	<tr>
        <td valign="top" class="mediumblack">Work Status</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$WORK_STATUS`</td>
       </tr>
	<!-- Social -->
<tr>
<td valign="top" class="mediumblack">Interested in settling abroad?</td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$GOING_ABROAD`</td>
</tr>

      </table></td>
     </tr>
     <tr>
      <td valign="top"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
       <tr class="bgbrownL3">
        <td colspan="3" class="mediumgreyb">&nbsp;Residence</td>
       </tr>
       <tr>
        <td colspan="3" valign="top" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="3"></td>
        </tr>
       <tr>
        <td width="32%" valign="top" class="mediumblack">Country living in</td>
        <td width="2%" valign="top" class="mediumblack">:</td>
        <td width="66%" class="mediumblack">&nbsp;~if $CITY_RES neq ""`~$CITY_RES`, ~/if`~$COUNTRY_RES`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Residential Status </td>
        <td valign="top" class="mediumblack">:</td>
        <td class="mediumblack">&nbsp;~$RSTATUS`</td>
       </tr>

	<!-- Social -->
<tr>
<td valign="top" class="mediumblack">Own House </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$OWN_HOUSE`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Have Car </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$HAVE_CAR`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Open To pets</td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$OPEN_TO_PET`</td>
</tr>

       <tr>
        <td colspan="3" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="5"></td>
        </tr>
      </table></td>


<!--
<td valign="top">
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
<tbody>
<tr class="bgbrownL3">
<td colspan="3" class="mediumgreyb">&nbsp;Contact Details</td>
</tr>
<tr>
<td colspan="3" valign="top" class="mediumblack"><img height="3" width="1"></td>
</tr>
<tr>
<td width="32%" valign="top" class="mediumblack">Blackberry Pin</td>
<td width="2%" valign="top" class="mediumblack">:</td>
<td width="66%" class="mediumblack">&nbsp;~$BLACKBERRY`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Linkedin profile ID/URL</td>
<td valign="top" class="mediumblack">:</td>
<td class="mediumblack">&nbsp;~$LINKEDIN_URL`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Facebook profile ID/URL</td>
<td valign="top" class="mediumblack">:</td>
<td class="mediumblack">&nbsp;~$FB_URL`</td>
</tr>

~if $SHOWALT_MOBILE eq 'Y'`
<tr>
<td valign="top" class="mediumblack">Alternate Mobile ISD</td>
<td valign="top" class="mediumblack">:</td>
<td class="mediumblack">&nbsp;~$ALT_MOBILE_ISD`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Alternate Mobile</td>
<td valign="top" class="mediumblack">:</td>
<td class="mediumblack">&nbsp;~$ALT_MOBILE`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Alternate Mobile Owner Name</td>
<td valign="top" class="mediumblack">:</td>
<td class="mediumblack">&nbsp;~$ALT_MOBILE_OWNER_NAME`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Alternate Mobile Number Owner</td>
<td valign="top" class="mediumblack">:</td>
<td class="mediumblack">&nbsp;~$ALT_MOBILE_NUMBER_OWNER`</td>
</tr>
~/if`
~if $SHOW_ALT_MESSENGER eq 'Y'`
<tr>
<td valign="top" class="mediumblack">Alternate Messenger ID</td>
<td valign="top" class="mediumblack">:</td>
<td class="mediumblack">&nbsp;~$ALT_MESSENGER_ID`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Alternate Messenger Channel</td>
<td valign="top" class="mediumblack">:</td>
<td class="mediumblack">&nbsp;~$ALT_MESSENGER_CHANNEL`</td>
</tr>
~/if`
<tr>
<td colspan="3" class="mediumblack"><img height="5" width="1"></td>
</tr>
</tbody>
</table>
</td>
-->
      <td valign="top">&nbsp;</td>
     </tr>
     <tr>
      <td colspan="2" valign="top"><table width="99%"  border="0" align="center" cellpadding="0" cellspacing="0">
       <tr class="bgbrownL3">
        <td colspan="3" class="mediumgreyb">&nbsp;Hobbies and Interests of ~$PROFILENAME` ~if $SELF`&nbsp;<span class="blacklinku"><a href="hobbies.php?checksum=~$CHECKSUM`">Edit</a></span>~/if`</td>
       </tr>
      ~if $NOHOBBY eq "1"`
       <tr>
	  <td height=18 colspan=15 class="mediumblack" align="center">No Information</td>
       </tr>
      ~else`
	<tr>
		<td width="25%" valign="top" class="mediumgreybl"> Hobbies</td>
		<td width="1%" valign="top" class="mediumblack">:</td>
		<td width="74%" valign="top" class="mediumblack">~if $HOBBY eq ""`No Information~else`~$HOBBY`~/if`</td>
	</tr>
        <tr>
        	<td valign="top" class="mediumgreybl">Interests</td>
        	<td valign="top" class="mediumblack">:</td>
        	<td class="mediumblack">~if $INTEREST eq ""`No Information~else`~$INTEREST`~/if`</td>
        </tr>
        <tr>
        	<td valign="top" class="mediumgreybl">Favourite Music </td>
        	<td valign="top" class="mediumblack">:</td>
        	<td valign="top" class="mediumblack">&nbsp;~if $MUSIC eq ""`No Information~else`~$MUSIC`~/if`</td>
        </tr>
        <tr>
        	<td valign="top" class="mediumgreybl">Favourite Read</td>
        	<td valign="top" class="mediumblack">:</td>
        	<td valign="top" class="mediumblack">&nbsp;~if $BOOK eq ""`No Information~else`~$BOOK`~/if`</td>
        </tr>
	<tr>
		<td valign="top" class="mediumgreybl">Favourite Books</td>
		<td valign="top" class="mediumblack">:</td>
		<td valign="top" class="mediumblack">&nbsp;~if $FAV_BOOK eq ""`No Information~else`~$FAV_BOOK`~/if`</td>
	</tr>
        <tr>
        	<td valign="top" class="mediumgreybl">Dress Style</td>
        	<td valign="top" class="mediumblack">:</td>
        	<td valign="top" class="mediumblack">&nbsp;~if $DRESS eq ""`No Information~else`~$DRESS`~/if`</td>
        </tr>
	<tr>
		<td valign="top" class="mediumgreybl">Favourite TV Shows</td>
		<td valign="top" class="mediumblack">:</td>
		<td valign="top" class="mediumblack">&nbsp;~if $FAV_TVSHOW eq ""`No Information~else`~$FAV_TVSHOW`~/if`</td>
	</tr>
       <tr>
        	<td valign="top" class="mediumgreybl">Preferred Movies </td>
        	<td valign="top" class="mediumblack">:</td>
        	<td valign="top" class="mediumblack">&nbsp;~if $MOVIE eq ""`No Information~else`~$MOVIE`~/if`</td>
       </tr>
       <tr>
		<td valign="top" class="mediumgreybl">Favourite Movies</td>
		<td valign="top" class="mediumblack">:</td>
		<td valign="top" class="mediumblack">&nbsp;~if $FAV_MOVIE eq ""`No Information~else`~$FAV_MOVIE`~/if`</td>
       </tr>
       <tr>
        	<td valign="top" class="mediumgreybl">Sports/ Fitness</td>
        	<td valign="top" class="mediumblack">:</td>
        	<td valign="top" class="mediumblack">&nbsp;~if $SPORTS eq ""`No Information~else`~$SPORTS`~/if`</td>
       </tr>
       <tr>
		<td valign="top" class="mediumgreybl">Favourite Food</td>
		<td valign="top" class="mediumblack">:</td>
		<td valign="top" class="mediumblack">&nbsp;~if $FAV_FOOD eq ""`No Information~else`~$FAV_FOOD`~/if`</td>
       </tr>
       <tr>
        	<td valign="top" class="mediumgreybl">Favourite Cuisine </td>
        	<td valign="top" class="mediumblack">:</td>
        	<td valign="top" class="mediumblack">&nbsp;~if $CUISINE eq ""`No Information~else`~$CUISINE`~/if`</td>
       </tr>
	<!-- Social -->
	<tr>
		<td valign="top" class="mediumgreybl">Favourite Vacation Destination</td>
		<td valign="top" class="mediumblack">:</td>
		<td valign="top" class="mediumblack">&nbsp;~if $FAV_VAC_DEST eq ""`No Information~else`~$FAV_VAC_DEST`~/if`</td>
	</tr>
       <tr>
                <td valign="top" class="mediumgreybl">Spoken Languages</td>
                <td valign="top" class="mediumblack">:</td>
                <td valign="top" class="mediumblack">&nbsp;~if $LANGUAGE eq ""`No Information~else`~$LANGUAGE`~/if`</td>
       </tr>

       <tr>
        <td colspan="3" valign="top" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="3"></td>
        </tr>
	~/if`
      </table></td>
      </tr>
    </table>
   </tr>
  </table><br>
  <table width="100%"  border="0" cellspacing="1" cellpadding="0" class="bggrey2">
   <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="bgwhite">
      <tr class="bggrey">
       <td width="100%" class="mediumgreyb">&nbsp; &nbsp; About ~$PROFILENAME`'s Family </td>
      </tr>
      <tr>
       <td><img src="/P/imagesnew/zero.gif" width="1" height="5"></td>
      </tr>
      <tr>
       <td valign="top"><table width="99%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
          <td valign="top" class="mediumgreybl">Family Values</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$FAMILY_VALUES`</td>
         </tr>
	 <tr>
          <td valign="top" class="mediumgreybl">Family Type</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$FAMILY_TYPE`</td>
         </tr>
	<tr>
          <td valign="top" class="mediumgreybl">Family Status</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$FAMILY_STATUS`</td>
         </tr>

<!-- Social -->
~if $FAMILY_INCOME`
<tr>
<td valign="top" class="mediumgreybl">Family Income</td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$FAMILY_INCOME`</td>
</tr>
~/if`
<tr>
<td valign="top" class="mediumgreybl">Parent Pincode</td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$PARENT_PINCODE`</td>
</tr>


	<tr>
          <td width="22%" valign="top" class="mediumgreybl">Father</td>
          <td width="1%" valign="top" class="mediumblack">:</td>
          <td width="77%" valign="top" class="mediumblack">&nbsp;~$FAMILY_BACK`</td>
         </tr>
	<tr>
          <td width="22%" valign="top" class="mediumgreybl">Mother</td>
          <td width="1%" valign="top" class="mediumblack">:</td>
          <td width="77%" valign="top" class="mediumblack">&nbsp;~$MOTHER_OCC`</td>
         </tr>
	 ~if $T_BROTHER neq '0'`
	<tr>
          <td width="22%" valign="top" class="mediumgreybl">Brother(s)</td>
          <td width="1%" valign="top" class="mediumblack">:</td>
          <td width="77%" valign="top" class="mediumblack">&nbsp;~$T_BROTHER` brother~if $T_BROTHER neq '1'`s~/if` ~if $M_BROTHER neq '0'`<i>of which married</i> ~$M_BROTHER`~/if`</td>
         </tr>
	~/if`
	~if $T_SISTER neq '0'`
	<tr>
          <td width="22%" valign="top" class="mediumgreybl">Sister(s)</td>
          <td width="1%" valign="top" class="mediumblack">:</td>
          <td width="77%" valign="top" class="mediumblack">&nbsp;~$T_SISTER` sister~if $T_SISTER neq '1'`s~/if` ~if $M_SISTER neq '0'`<i>of which married</i> ~$M_SISTER`~/if`</td>
         </tr>
	~/if`
         <tr>
          <td valign="top" class="mediumgreybl">Gothra(Paternal)</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$GOTHRA`</td>
         </tr>
<!-- Social -->
<tr>
<td valign="top" class="mediumgreybl">Gothra (Maternal)</td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$GOTHRA_MATERNAL`</td>
</tr>
<tr>

         <tr>
          <td valign="top" class="mediumgreybl">Living with Parents</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$LIVE_WITH_PARENTS`</td>
         </tr>
         <tr>
          <td colspan="3" valign="top" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="3"></td>
         </tr>
       </table></td>
      </tr>
     </table>
   </tr>
  </table>
  <br>
  <table width="100%"  border="0" cellspacing="1" cellpadding="0" class="bggrey2">
   <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="bgwhite">
      <tr class="bggrey">
       <td colspan="2" class="mediumgreyb">&nbsp; &nbsp;~$PROFILENAME`'s partner profile ~if $SELF`&nbsp;<span class="blacklinku"><a href="advance_search.php?FLAG=partner&checksum=~$CHECKSUM`">Edit</a></span>~/if`</td>
      </tr>
      <tr>
       <td colspan="2"><img src="/P/imagesnew/zero.gif" width="1" height="5"></td>
      </tr>
    ~if $NOPARTNER eq "1"`
      <tr>
       <td height=18 colspan=15 class="mediumblack" align="center">No Information</td>
      </tr>
    ~else`
      <tr>
       <td width="50%" valign="top"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr class="bgbrownL3">
          <td colspan="3" class="mediumgreyb">Basic Details</td>
         </tr>
         <tr>
          <td colspan="3" valign="top" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="3"></td>
         </tr>
         <tr>
          <td width="28%" valign="top" class="mediumblack">Height</td>
          <td width="2%" valign="top" class="mediumblack">:</td>
          <td width="70%" valign="top" class="mediumblack">&nbsp;~$PARTNER_HEIGHT`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Age</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$PARTNER_AGE`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Marital Status</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$PARTNER_MSTATUS`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Have Children </td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$PARTNER_CHILDREN`</td>
         </tr>
	<tr>
          <td valign="top" class="mediumblack">Country </td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$PARTNER_COUNTRYRES`</td>
         </tr>
	<tr>
          <td valign="top" class="mediumblack">City </td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$PARTNER_CITYRES`</td>
         </tr>
	~if $PROFILEGENDER eq 'Male'`
         <tr>
          <td valign="top" class="mediumblack">Career </td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$WORKINGSPOUSE`</td>
         </tr>
	~/if`
	 <tr>
          <td colspan="3" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="5"></td>
         </tr>
       </table></td>
       <td width="50%" valign="top"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr class="bgbrownL3">
          <td colspan="3" class="mediumgreyb">Religion and Ethnicity</td>
         </tr>
         <tr>
          <td colspan="3" valign="top" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="3"></td>
         </tr>
	<tr>
          <td width="28%" valign="top" class="mediumblack">Religion</td>
          <td width="2%" valign="top" class="mediumblack">:</td>
          <td width="70%" valign="top" class="mediumblack">&nbsp;~$PARTNER_RELIGION`</td>
         </tr>
         <tr>
          <td width="28%" valign="top" class="mediumblack">Caste</td>
          <td width="2%" valign="top" class="mediumblack">:</td>
          <td width="70%" valign="top" class="mediumblack">&nbsp;~$PARTNER_CASTE`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Community </td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$PARTNER_MTONGUE`</td>
         </tr>
	<tr>
          <td valign="top" class="mediumblack">Manglik </td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$PARTNER_MANGLIK`</td>
         </tr>
       </table></td>
      </tr>
      <tr>
       <td valign="top"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr class="bgbrownL3">
          <td colspan="3" class="mediumgreyb">Lifestyle &amp; Attributes </td>
         </tr>
         <tr>
          <td colspan="3" valign="top" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="3"></td>
         </tr>
         <tr>
          <td width="28%" valign="top" class="mediumblack">Diet</td>
          <td width="2%" valign="top" class="mediumblack">:</td>
          <td width="70%" valign="top" class="mediumblack">&nbsp;~$PARTNER_DIET`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Smoke</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$PARTNER_SMOKE`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Drink</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$PARTNER_DRINK`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Complexion</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$PARTNER_COMP`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Body Type </td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$PARTNER_BTYPE`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Challenged</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$PARTNER_HANDICAPPED`</td>
         </tr>
	 ~if $showit`
	 <tr>
          <td valign="top" class="mediumblack">Nature of Handicap</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$PARTNER_NHANDICAPPED`</td>
         </tr>
	 ~/if`
       </table></td>
       <td valign="top"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr class="bgbrownL3">
          <td colspan="3" class="mediumgreyb">&nbsp;Education and Occupation </td>
         </tr>
         <tr>
          <td colspan="3" valign="top" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="3"></td>
         </tr>
         <tr>
          <td width="28%" valign="top" class="mediumblack">Education Level </td>
          <td width="2%" valign="top" class="mediumblack">:</td>
          <td width="70%" valign="top" class="mediumblack">&nbsp;~$PARTNER_ELEVEL`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Occupation</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$PARTNER_OCC`</td>
         </tr>
	~if $PROFILEGENDER eq 'Female'`
         <tr>
          <td valign="top" class="mediumblack">Income </td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$PARTNER_INCOME`</td>
         </tr>
	~/if`
         <tr>
          <td colspan="3" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="5"></td>
         </tr>
       </table></td>
      </tr>
      <tr>
       <td valign="top"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr class="bgbrownL3">
          <td colspan="3" class="mediumgreyb">&nbsp;Residence</td>
         </tr>
         <tr>
          <td colspan="3" valign="top" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="3"></td>
         </tr>
         <tr>
          <td width="34%" valign="top" class="mediumblack">Country living in</td>
          <td width="2%" valign="top" class="mediumblack">:</td>
          <td width="64%" class="mediumblack">&nbsp;~if $PARTNER_CITYRES neq ""`~$PARTNER_CITYRES`, ~$PARTNER_COUNTRYRES`~else`~$PARTNER_COUNTRYRES`~/if`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Residential Status </td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$PARTNER_RES_STATUS`</td>
         </tr>
         <tr>
          <td colspan="3" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="5"></td>
         </tr>
       </table></td>
       <td valign="top">&nbsp;</td>
      </tr>
	~/if`
     </table>
   </tr>
  </table>
  <br>
 </td>
 </tr>
</table>
<br>
</body>
</html>
