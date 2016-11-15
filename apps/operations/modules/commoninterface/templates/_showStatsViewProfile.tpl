<div>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>JeevanSathi Matrimonilas - Profile</title>

<script language="javascript">

function MM_openBrWindow(theURL,winName,features)
{
        window.open(theURL,winName,features);
}

</script>

<br>
<table width="80%"  border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
  <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td ><font face="Arial" size="2" color="#666666"><b>Personal Profile of ~$otherDetailsArr['username']`</b></font></td>
   </tr>
  </table>
  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bggrey2">
   <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="bgwhite">
      <tr>
       <td  class="bgbrownL3"><img src="/P/imagesnew/zero.gif" width="8" height="8"></td>
       <td  class="bgbrownL3"><img src="/P/imagesnew/zero.gif" width="180" height="1"><br>
	<!-- ~if $UPLOADPHOTO`
		<a href="../social/addPhotos?checksum=~$CHECKSUM`"><img src="~$PHOTOFILE`" width="150" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;"></a> -->
		<!--<a href="uploadphoto.php?checksum=~$CHECKSUM`"><img src="~$PHOTOFILE`" width="150" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;"></a>-->
	<!-- ~else`
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
	~/if` -->
	<table width="80%"  border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
        <td><img src="/P/imagesnew/zero.gif" width="1" height="2"></td>
        </tr>
        <tr>
         <td colspan="3" class="mediumblack"><span class="blacklinku">
      ~if $profilePicUrl['profilePicUrl'] eq ""`
      <img src="~$SITE_URL`/profile/images/no_photo.gif" width="150" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;">
      ~else`
      <img src="~$profilePicUrl['profilePicUrl']`" width="150" height="200" GALLERYIMG="NO" border="0" oncontextmenu="return false;">
      <a href="" onclick="MM_openBrWindow('/P/photocheck.php?checksum=~$otherDetailsArr['checksum']`&profilechecksum=~$otherDetailsArr['profilechecksum']`&seq=1','','width=400,height=500,scrollbars=yes'); return false;">Click here for Album</a>
      ~/if`
	   <!--  <a href="" onclick="MM_openBrWindow('/P/photocheck.php?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&seq=1','','width=400,height=500,scrollbars=yes'); return false;">Click here for Full View</a> -->
	 
	</span></td>
        </tr>
~if $otherDetailsArr['photoDisplay'] eq "H"`<tr><td class="mediumred" align="center">Photo Hidden</td></tr>~/if`
~if $otherDetailsArr['photoDisplay'] eq "C"`<tr><td class="mediumred" align="center">Photo Visible when contact accepted</td></tr>~/if`
~if $otherDetailsArr['photoDisplay'] eq "F"`<tr><td class="mediumred" align="center">Photo Visible when not filtered</td></tr>~/if`
	</table>
	</td>
       <td width="100%" rowspan="2" valign="top" class="mediumblack">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td>
<!-- CONTACT GRID -->
         <table width="144" border="0" align="right" cellpadding="0" cellspacing="0">
         <!-- TO DO -->
<!-- ~if $NO_FORWARD_OPTION neq "1"`
        <tr>
        <td><img src="/P/imagesnew/zero.gif" width="144" height="1"></td>
        </tr>
        <tr>
        <td><img src="/P/imagesnew/p_forward.gif" width="144" height="16" border="0"></td>
        </tr>
~/if` -->
<!-- <tr>
<td><img src="/P/imagesnew/zero.gif" width="144" height="1"></td>
</tr>
<tr>
<td><img src="/P/imagesnew/p_seeprint_version.gif" width="144" height="16" border="0"></td>
</tr> -->

<!-- TO DO -->
<!-- ~if $HOROSCOPE eq "Y"`
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
~/if` -->
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
	~if $otherDetailsArr['username'] eq "test4js"`
		<!-- google_ad_region_start=otherinfo -->
		~$COMMENTS`
		~$SUBYOURINFO`
		~$YOURINFO`
		<!-- google_ad_region_end=otherinfo -->
	~else`
		<!-- google_ad_region_start=otherinfo -->

		<span><b>~$profileDetailArr['Details']['YOURINFO']['label']`: </b></span><span class="wordbreakwrap">~$profileDetailArr['Details']['YOURINFO']['label_val']|decodevar`</span><br>
        ~if $profileDetailArr['Details']['YOURINFO']['screenBit'] eq 1`
        <span class = "smallred">This field is currently being screened. Please re-check shortly.</span><br>
        ~/if`
        <br>
        ~if $profileDetailArr['Career']['JOB_INFO']['label_val'] neq null`
        <span><b>~$profileDetailArr['Career']['JOB_INFO']['label']`: </b></span><span class="wordbreakwrap">~$profileDetailArr['Career']['JOB_INFO']['label_val']|decodevar`</span><br>
        ~if $profileDetailArr['Career']['JOB_INFO']['screenBit'] eq 1`
        <span class = "smallred">This field is currently being screened. Please re-check shortly.</span><br>
        ~/if`
        <br>
        ~/if`
        ~if $profileDetailArr['Dpp']['SPOUSE']['label_val'] neq null`
        <span><b>About Desired Partner Profile: </b></span><span class="wordbreakwrap">~$profileDetailArr['Dpp']['SPOUSE']['label_val']|decodevar`</span><br>
        ~if $profileDetailArr['Dpp']['SPOUSE']['screenBit'] eq 1`
        <span class = "smallred">This field is currently being screened. Please re-check shortly.</span><br>
        ~/if`
        <br>
        ~/if`
    ~/if`
        ~if $profileDetailArr['Family']['PARENT_CITY_SAME']['label_val'] neq null and $profileDetailArr['Details']['GENDER']['label_val'] eq "Male"`
        <span><b>Living with parents: </b></span><span>~$profileDetailArr['Family']['PARENT_CITY_SAME']['label_val']`</span><br><br>
        ~/if`
        ~if $profileDetailArr['Details']['GENDER']['label_val'] eq 'Female' and $profileDetailArr['Career']['MARRIED_WORKING']['label_val'] neq ""`
        <br><br>
        <span class=bigblack><b>Plan to work after marriage:</b></span>
            <span class="bigblack" valign="top">
        ~$profileDetailArr['Career']['MARRIED_WORKING']['label_val']`
        </span></br></br>
        ~/if`
        ~if $profileDetailArr['Family']['FAMILYINFO']['label_val'] neq null`
        <span><b>~$profileDetailArr['Family']['FAMILYINFO']['label']`: </b></span><span class="wordbreakwrap">~$profileDetailArr['Family']['FAMILYINFO']['label_val']|decodevar`</span><br>
        ~if $profileDetailArr['Family']['FAMILYINFO']['screenBit'] eq 1`
        <span class = "smallred">This field is currently being screened. Please re-check shortly.</span><br>
        ~/if`
        <br>
        ~/if`


		<!-- google_ad_region_end=otherinfo -->
	</span>
	
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
	~if $profileDetailArr['Details']['RELATION']['label_val']`
        <tr>
         <td width="43%" class="mediumblack">Posted By </td>
         <td width="3%" class="mediumblack">:</td>
         <td width="54%" class="mediumgreybl">&nbsp;~$profileDetailArr['Details']['RELATION']['label_val']`</td><br>
        </tr>
	~/if`
	<!-- Social -->	
	<tr>
	<td width="43%" class="mediumblack">Person handling Profile </td>
	<td width="3%" class="mediumblack">:</td>
	<td width="54%" class="mediumgreybl wordbreakwrap">&nbsp;~$profileDetailArr['Contact']['PROFILE_HANDLER_NAME']['label_val']` ~if $profileDetailArr['Contact']['PROFILE_HANDLER_NAME']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
	</tr>

	~if $otherDetailsArr['entryDate'] neq ""`
	<tr>
	<td class="mediumblack">Registered On</td>
	<td class="mediumblack">:</td>
	<td class="mediumgreybl">&nbsp;~$otherDetailsArr['entryDate']`</td>
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
	~if $otherDetailsArr['modifiedDate'] neq ""`
	<tr>
	<td class="mediumblack">Last Updated</td>
	<td class="mediumbalck">:</td>
	<td class="mediumgreybl">&nbsp;~$otherDetailsArr['modifiedDate']`</td>
	</tr>
	~/if`
	~if $otherDetailsArr['lastLogin'] neq ""`
        <tr>
         <td class="mediumblack">Last Online </td>
         <td class="mediumblack">:</td>
         <td class="mediumgreybl">&nbsp;~$otherDetailsArr['lastLogin']`</td>
        </tr>
	~/if`
	~if $otherDetailsArr['photoModifiedDate'] neq ""`
	<tr>
	<td class="mediumblack">Photo Modified On</td>
	<td class="mediumblack">:</td>
	<td class="mediumgreybl">&nbsp;~$otherDetailsArr['photoModifiedDate']`</td>
	</tr>
	~/if`
        <tr>
         <td class="mediumblack">Gender </td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$profileDetailArr['Details']['GENDER']['label_val']`</td>
        </tr>
        <tr>
         <td class="mediumblack">Height</td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$profileDetailArr['Details']['HEIGHT']['label_val']`</td>
        </tr>
        <tr>
         <td class="mediumblack">Age</td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$otherDetailsArr['age']` years</td>
        </tr>
        <tr>
         <td class="mediumblack">Country living in</td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~if $profileDetailArr['Details']['CITY_RES']['label_val'] neq ""`~$profileDetailArr['Details']['CITY_RES']['label_val']`, ~/if`~$profileDetailArr['Details']['COUNTRY_RES']['label_val']`</td>
        </tr>
	<tr>
         <td class="mediumblack">Religion</td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$profileDetailArr['Details']['RELIGION']['label_val']`</td>
        </tr>
        <tr>
         <td class="mediumblack">Caste</td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$profileDetailArr['Details']['CASTE']['label_val']`</td>
        </tr>
        <tr>
         <td class="mediumblack">Occupation</td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$profileDetailArr['Career']['OCCUPATION']['label_val']`</td>
        </tr>
        <tr>
         <td class="mediumblack">Marital Status </td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$profileDetailArr['Details']['MSTATUS']['label_val']`</td>
        </tr>
        <tr>
         <td class="mediumblack">Have Children </td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$profileDetailArr['Details']['HAVECHILD']['label_val']`</td>
        </tr>
	~if $profileDetailArr['Career']['INCOME']['label_val'] neq ""`
       <tr>
        <td class="mediumblack">Annual Income </td>
        <td class="mediumblack">:</td>
        <td class="mediumblack">&nbsp;~$profileDetailArr['Career']['INCOME']['label_val']`</td>
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
  ~if $otherDetailsArr['subscription'] eq 'Y'`
   <table width="100%"  border="0" cellspacing="1" cellpadding="0" class="bggrey2">
    <tr>
     <td><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="bgwhite">
      <tr>
       <td width="19%"><img src="/P/imagesnew/p_eclassifides.gif" width="279" height="48"></td>
       <td width="81%"><table width="96%"  border="0" align="center" cellpadding="0" cellspacing="0">
	~if $profileDetailArr['Contact']['CONTACT']['label_val'] neq ""`
        <tr>
         <td width="35%" class="mediumblackb">Contact Address </td>
         <td width="1%" class="mediumblack">:</td>
         <td width="64%" class="mediumblackb">&nbsp;~$profileDetailArr['Contact']['CONTACT']['label_val']`</td>
        </tr>
	~/if`
	~if $profileDetailArr['Contact']['PARENTS_CONTACT']['label_val'] neq ""`
        <tr>
         <td width="35%" class="mediumblackb">Address of Parents</td>
         <td width="1%" class="mediumblack">:</td>
         <td width="64%" class="mediumblackb">&nbsp;~$profileDetailArr['Contact']['PARENTS_CONTACT']['label_val']`</td>
        </tr>
	~/if`
	~if $profileDetailArr['Contact']['PHONE_MOB']['label_val'] neq ""`
        <tr>
         <td class="mediumblackb">Phone No.</td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$profileDetailArr['Contact']['PHONE_MOB']['label_val']`</td>
        </tr>
	~/if`

	~if $profileDetailArr['Contact']['SHOWALT_MOBILE']['label_val'] eq 'Y' && $profileDetailArr['Contact']['ALT_MOBILE']['label_val'] neq ''`
	<tr>
	<td class="mediumblackb">Alternate Mobile</td>
	<td class="mediumblack">:</td>
	<td class="mediumblack">&nbsp;~$profileDetailArr['Contact']['ALT_MOBILE']['label_val']`</td>
	</tr>
	~/if`

        <tr>
         <td height="19" class="mediumblackb">E-mail</td>
         <td class="mediumblack">:</td>
         <td class="mediumblack">&nbsp;~$profileDetailArr['Contact']['EMAIL']['label_val']`</td>
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
      <td colspan="2" class="mediumgreyb">&nbsp; &nbsp;More About ~$otherDetailsArr['username']`</td>
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
        <td width="70%" valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['RELIGION']['label_val']`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Mother Tongue </td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['MTONGUE']['label_val']`</td>
       </tr>
	<!--HINDU-->
	~if $profileDetailArr['Details']['RELIGION']['label_val'] eq 'Hindu'`
	<tr>
        <td valign="top" class="mediumblack">Caste</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['CASTE']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Sub Caste/Surname</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Details']['SUBCASTE']['label_val']`~if $profileDetailArr['Details']['SUBCASTE']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Nakshatra</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Kundli']['NAKSHATRA']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Rashi/ Moon Sign</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Kundli']['RASHI']['label_val']`</td>
        </tr>

	<!-- Social -->
	<tr>
	<td valign="top" class="mediumblack">Sun Sign </td>
	<td valign="top" class="mediumblack">:</td>
	<td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Kundli']['SUNSIGN']['label_val']`</td>
	</tr>
	<tr>
        <td valign="top" class="mediumblack">Gothra(Paternal)</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Details']['GOTHRA']['label_val']`~if $profileDetailArr['Details']['GOTHRA']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
        </tr>

	 <!-- Social -->
        <tr>
        <td valign="top" class="mediumblack">Gothra (Maternal)</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Details']['GOTHRA_MATERNAL']['label_val']`~if $profileDetailArr['Details']['GOTHRA_MATERNAL']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
        </tr>
        <tr>
	
	<tr>
        <td valign="top" class="mediumblack">Native Place</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $profileDetailArr['Details']['NATIVE_COUNTRY']['label_val'] eq "India"`~if $profileDetailArr['Details']['NATIVE_CITY']['label_val'] neq ""`~$profileDetailArr['Details']['NATIVE_CITY']['label_val']`,~/if`~if $profileDetailArr['Details']['NATIVE_STATE']['label_val']`~$profileDetailArr['Details']['NATIVE_STATE']['label_val']`,~/if`~$profileDetailArr['Details']['NATIVE_COUNTRY']['label_val']` ~else`~$profileDetailArr['Details']['NATIVE_COUNTRY']['label_val']`~/if`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Horoscope Match needed</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $profileDetailArr['Kundli']['HOROSCOPE_MATCH']['value'] eq 'Y'`Yes~else`No~/if`</td>
        </tr>
	<!--HINDU-->
	~elseif $profileDetailArr['Details']['RELIGION']['label_val'] eq 'Jain'`
	<tr>
        <td valign="top" class="mediumblack">Caste</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['CASTE']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Sampraday</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['SAMPRADAY']['label_val']`</td>
        </tr>
	<!--JAIN-->
	~elseif $profileDetailArr['Details']['RELIGION']['label_val'] eq 'Christian'`
	<!--CHRISTIAN-->
	<tr>
        <td valign="top" class="mediumblack">Denomination</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['CASTE']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Diocese</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Details']['DIOCESE']['label_val']`~if $profileDetailArr['Details']['DIOCESE']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Baptised</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['BAPTISED']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Do You Read Bible Everyday?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['READ_BIBLE']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Do You Offer Tithe Regularly?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['OFFER_TITHE']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Interested in spreading : the Gospel?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['SPREADING_GOSPEL']['label_val']`</td>
        </tr>
	<!--CHRISTIAN-->
	~elseif $profileDetailArr['Details']['RELIGION']['label_val'] eq 'Muslim'`
	<tr>
        <td valign="top" class="mediumblack">Denomination</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['CASTE']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Ma'thab</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['MATHTHAB']['label_val']`</td>
        </tr>
	
	<tr>
        <td valign="top" class="mediumblack">Namaz</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['NAMAZ']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Zakat</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['ZAKAT']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Fasting</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['FASTING']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Umrah/Hajj</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['UMRAH_HAJJ']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Do You Read The Quran?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['QURAN']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Sunnah Beard</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['SUNNAH_BEARD']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Sunnah Cap</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['SUNNAH_CAP']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Do You Wear Hijab?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['HIJAB']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Willing To Wear Hijab After Marriage?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $profileDetailArr['Details']['HIJAB_MARRIAGE']['label_val'] neq ''`~$profileDetailArr['Details']['HIJAB_MARRIAGE']['label_val']`~else`-~/if`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Can the Girl Work After Marriage?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['WORKING_MARRIAGE']['label_val']`</td>
        </tr>
	<!--MUSLIM-->
	~elseif $profileDetailArr['Details']['RELIGION']['label_val'] eq 'Sikh'`
	<!--SIKH-->
	 <tr>
        <td valign="top" class="mediumblack">Caste</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['CASTE']['label_val']`</td>
        </tr>
	 <tr>
        <td valign="top" class="mediumblack">Are You Amritdhari?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['AMRITDHARI']['label_val']`</td>
        </tr>
	 <tr>
        <td valign="top" class="mediumblack">Do You Cut Your Hair?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['CUT_HAIR']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Do You Trim Your Beard?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['TRIM_BEARD']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Do You Wear Turban?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['WEAR_TURBAN']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Are You Clean Shaven?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['CLEAN_SHAVEN']['label_val']`</td>
        </tr>
	<!--SIKH-->
	~elseif $profileDetailArr['Details']['RELIGION']['label_val'] eq 'Parsi'`
	<!--PARSI-->
	<tr>
        <td valign="top" class="mediumblack">Are You a Zarathushtri?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['ZARATHUSHTRI']['label_val']`</td>
        </tr>
	<tr>
        <td valign="top" class="mediumblack">Are Both Parents Zarathushtri?</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['PARENTS_ZARATHUSHTRI']['label_val']`</td>
        </tr>
	<!--PARSI-->
	~/if`
	<!-- Social -->
	<tr>
	<td valign="top" class="mediumblack">Sect</td>
	<td valign="top" class="mediumblack">:</td>
	<td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['SECT']['label_val']`</td>
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
        <td width="66%" valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Lifestyle']['DIET']['label_val']`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Smoke</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Lifestyle']['SMOKE']['label_val']`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Drink</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Lifestyle']['DRINK']['label_val']`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Complexion</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['COMPLEXION']['label_val']`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Body Type </td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['BTYPE']['label_val']`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Challenged</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['HANDICAPPED']['label_val']`</td>
       </tr>
<!-- Social -->
       <tr>
        <td valign="top" class="mediumblack">Thalassemia</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['THALASSEMIA']['label_val']`</td>
       </tr>

	<tr>
        <td valign="top" class="mediumblack">Blood Group</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Lifestyle']['BLOOD_GROUP']['label_val']`</td>
       </tr>
	<tr>
        <td valign="top" class="mediumblack">Weight</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $profileDetailArr['Details']['WEIGHT']['label_val'] neq 0`~$profileDetailArr['Details']['WEIGHT']['label_val']`~/if`</td>
       </tr>
	~if $profileDetailArr['Details']['HIV']['value'] eq 'Y'`
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
        <td colspan="3" class="mediumgreyb">&nbsp;Astro/ Kundli Details </td>
       </tr>
       <tr>
        <td colspan="3" valign="top" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="3"></td>
        </tr>
	~if $profileDetailArr['Details']['DTOFBIRTH']['label_val']`
       <tr>
        <td valign="top" class="mediumblack">Date of Birth </td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Details']['DTOFBIRTH']['label_val']`</td>
       </tr>
       <tr>
	~/if`
        <td valign="top" class="mediumblack">Country of Birth</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Kundli']['ASTRO_COUNTRY_BIRTH']['label_val']`</td>
       </tr>
       <tr>
        <td width="28%" valign="top" class="mediumblack">City of Birth </td>
        <td width="2%" valign="top" class="mediumblack">:</td>
        <td width="70%" valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Kundli']['ASTRO_PLACE_BIRTH']['label_val']`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Time of Birth </td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~if $profileDetailArr['Kundli']['ASTRO_BTIME']['label_val'] neq ""`~$profileDetailArr['Kundli']['ASTRO_BTIME']['label_val']`~else`-~/if`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Nakshatra</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Kundli']['NAKSHATRA']['label_val']`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Manglik/Chevvai Dosham </td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Kundli']['MANGLIK']['label_val']`</td>
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
       
	<!-- Social-->

<tr>
<td valign="top" class="mediumblack">School Name </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Education']['SCHOOL']['label_val']`~if $profileDetailArr['Education']['SCHOOL']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">College Name</td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Education']['COLLEGE']['label_val']`~if $profileDetailArr['Education']['COLLEGE']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Graduation Degree </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Education']['DEGREE_UG']['label_val']`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Other Graduation Degree </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Education']['OTHER_UG_DEGREE']['label_val']`~if $profileDetailArr['Education']['OTHER_UG_DEGREE']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
</tr>

<tr>
<td valign="top" class="mediumblack">PG College </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Education']['PG_COLLEGE']['label_val']`~if $profileDetailArr['Education']['PG_COLLEGE']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">PG Degree </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Education']['DEGREE_PG']['label_val']`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Other PG Degree </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Education']['OTHER_PG_DEGREE']['label_val']`~if $profileDetailArr['Education']['OTHER_PG_DEGREE']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Highest Degree </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Education']['EDU_LEVEL_NEW']['label_val']`</td>
</tr>
<tr>
</tr>

       <tr>
        <td valign="top" class="mediumblack">Educational Qualification </td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Education']['EDUCATION']['label_val']`~if $profileDetailArr['Education']['EDUCATION']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Occupation</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Career']['OCCUPATION']['label_val']`</td>
       </tr>
	~if $profileDetailArr['Career']['INCOME']['label_val'] neq ""`
       <tr>
        <td valign="top" class="mediumblack">Annual Income </td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Career']['INCOME']['label_val']`</td>
       </tr>
	~/if`
	<!-- Social -->
<tr>
<td valign="top" class="mediumblack">Organization Name</td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Career']['COMPANY_NAME']['label_val']`~if $profileDetailArr['Career']['COMPANY_NAME']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
</tr>

	<tr>
        <td valign="top" class="mediumblack">Work Status</td>
        <td valign="top" class="mediumblack">:</td>
        <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Career']['WORK_STATUS']['label_val']`</td>
       </tr>
	<!-- Social -->
<tr>
<td valign="top" class="mediumblack">Interested in settling abroad?</td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Career']['GOING_ABROAD']['label_val']`</td>
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
        <td width="66%" class="mediumblack">&nbsp;~if $profileDetailArr['Details']['CITY_RES']['label_val'] neq ""`~$profileDetailArr['Details']['CITY_RES']['label_val']`, ~/if`~$profileDetailArr['Details']['COUNTRY_RES']['label_val']`</td>
       </tr>
       <tr>
        <td valign="top" class="mediumblack">Residential Status </td>
        <td valign="top" class="mediumblack">:</td>
        <td class="mediumblack">&nbsp;~$profileDetailArr['Lifestyle']['RES_STATUS']['label_val']`</td>
       </tr>

	<!-- Social -->
<tr>
<td valign="top" class="mediumblack">Own House </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Lifestyle']['OWN_HOUSE']['label_val']`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Have Car </td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Lifestyle']['HAVE_CAR']['label_val']`</td>
</tr>
<tr>
<td valign="top" class="mediumblack">Open To pets</td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Lifestyle']['OPEN_TO_PET']['label_val']`</td>
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
        <td colspan="3" class="mediumgreyb">&nbsp;Hobbies and Interests of ~$otherDetailsArr['username']` ~if $SELF`&nbsp;<span class="blacklinku"><a href="hobbies.php?checksum=~$CHECKSUM`">Edit</a></span>~/if`</td>
       </tr>
      ~if $NOHOBBY eq "1"`
       <tr>
	  <td height=18 colspan=15 class="mediumblack" align="center">No Information</td>
       </tr>
      ~else`
	<tr>
		<td width="25%" valign="top" class="mediumgreybl"> Hobbies</td>
		<td width="1%" valign="top" class="mediumblack">:</td>
		<td width="74%" valign="top" class="mediumblack">~$profileDetailArr['Lifestyle']['HOBBIES_HOBBY']['label_val']`</td>
	</tr>
        <tr>
        	<td valign="top" class="mediumgreybl">Interests</td>
        	<td valign="top" class="mediumblack">:</td>
        	<td class="mediumblack">~$profileDetailArr['Lifestyle']['HOBBIES_INTEREST']['label_val']`</td>
        </tr>
        <tr>
        	<td valign="top" class="mediumgreybl">Favourite Music </td>
        	<td valign="top" class="mediumblack">:</td>
        	<td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Lifestyle']['HOBBIES_MUSIC']['label_val']`</td>
        </tr>
        <tr>
        	<td valign="top" class="mediumgreybl">Favourite Read</td>
        	<td valign="top" class="mediumblack">:</td>
        	<td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Lifestyle']['HOBBIES_BOOK']['label_val']`</td>
        </tr>
	<tr>
		<td valign="top" class="mediumgreybl">Favourite Books</td>
		<td valign="top" class="mediumblack">:</td>
		<td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Lifestyle']['FAV_BOOK']['label_val']`~if $profileDetailArr['Lifestyle']['FAV_BOOK']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
	</tr>
        <tr>
        	<td valign="top" class="mediumgreybl">Dress Style</td>
        	<td valign="top" class="mediumblack">:</td>
        	<td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Lifestyle']['HOBBIES_DRESS']['label_val']`</td>
        </tr>
	<tr>
		<td valign="top" class="mediumgreybl">Favourite TV Shows</td>
		<td valign="top" class="mediumblack">:</td>
		<td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Lifestyle']['FAV_TVSHOW']['label_val']`~if $profileDetailArr['Lifestyle']['FAV_TVSHOW']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
	</tr>
       <tr>
        	<td valign="top" class="mediumgreybl">Preferred Movies </td>
        	<td valign="top" class="mediumblack">:</td>
        	<td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Lifestyle']['HOBBIES_MOVIE']['label_val']`</td>
       </tr>
       <tr>
		<td valign="top" class="mediumgreybl">Favourite Movies</td>
		<td valign="top" class="mediumblack">:</td>
		<td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Lifestyle']['FAV_MOVIE']['label_val']`~if $profileDetailArr['Lifestyle']['FAV_MOVIE']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
       </tr>
       <tr>
        	<td valign="top" class="mediumgreybl">Sports/ Fitness</td>
        	<td valign="top" class="mediumblack">:</td>
        	<td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Lifestyle']['HOBBIES_SPORTS']['label_val']`</td>
       </tr>
       <tr>
		<td valign="top" class="mediumgreybl">Favourite Food</td>
		<td valign="top" class="mediumblack">:</td>
		<td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Lifestyle']['FAV_FOOD']['label_val']`~if $profileDetailArr['Lifestyle']['FAV_FOOD']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
       </tr>
       <tr>
        	<td valign="top" class="mediumgreybl">Favourite Cuisine </td>
        	<td valign="top" class="mediumblack">:</td>
        	<td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Lifestyle']['HOBBIES_CUISINE']['label_val']`</td>
       </tr>
	<!-- Social -->
	<tr>
		<td valign="top" class="mediumgreybl">Favourite Vacation Destination</td>
		<td valign="top" class="mediumblack">:</td>
		<td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Lifestyle']['FAV_VAC_DEST']['label_val']`~if $profileDetailArr['Lifestyle']['FAV_VAC_DEST']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
	</tr>
       <tr>
                <td valign="top" class="mediumgreybl">Spoken Languages</td>
                <td valign="top" class="mediumblack">:</td>
                <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Lifestyle']['HOBBIES_LANGUAGE']['label_val']`</td>
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
       <td width="100%" class="mediumgreyb">&nbsp; &nbsp; About ~$otherDetailsArr['username']`'s Family </td>
      </tr>
      <tr>
       <td><img src="/P/imagesnew/zero.gif" width="1" height="5"></td>
      </tr>
      <tr>
       <td valign="top"><table width="99%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
          <td valign="top" class="mediumgreybl">Family Values</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Family']['FAMILY_VALUES']['label_val']`</td>
         </tr>
	 <tr>
          <td valign="top" class="mediumgreybl">Family Type</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Family']['FAMILY_TYPE']['label_val']`</td>
         </tr>
	<tr>
          <td valign="top" class="mediumgreybl">Family Status</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Family']['FAMILY_STATUS']['label_val']`</td>
         </tr>

<!-- Social -->
~if $profileDetailArr['Family']['FAMILY_INCOME']['label_val']`
<tr>
<td valign="top" class="mediumgreybl">Family Income</td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Family']['FAMILY_INCOME']['label_val']`</td>
</tr>
~/if`



	<tr>
          <td width="22%" valign="top" class="mediumgreybl">Father</td>
          <td width="1%" valign="top" class="mediumblack">:</td>
          <td width="77%" valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Family']['FAMILY_BACK']['label_val']`</td>
         </tr>
	<tr>
          <td width="22%" valign="top" class="mediumgreybl">Mother</td>
          <td width="1%" valign="top" class="mediumblack">:</td>
          <td width="77%" valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Family']['MOTHER_OCC']['label_val']`</td>
         </tr>
	 ~if $profileDetailArr['Family']['T_BROTHER']['label_val'] neq ''`
	<tr>
          <td width="22%" valign="top" class="mediumgreybl">Brother(s)</td>
          <td width="1%" valign="top" class="mediumblack">:</td>
          <td width="77%" valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Family']['T_BROTHER']['label_val']`</td>
         </tr>
	~/if`
	~if $profileDetailArr['Family']['T_SISTER']['label_val'] neq ''`
	<tr>
          <td width="22%" valign="top" class="mediumgreybl">Sister(s)</td>
          <td width="1%" valign="top" class="mediumblack">:</td>
          <td width="77%" valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Family']['T_SISTER']['label_val']`</td>
         </tr>
	~/if`
         <tr>
          <td valign="top" class="mediumgreybl">Gothra(Paternal)</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Details']['GOTHRA']['label_val']`~if $profileDetailArr['Details']['GOTHRA']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
         </tr>
<!-- Social -->
<tr>
<td valign="top" class="mediumgreybl">Gothra (Maternal)</td>
<td valign="top" class="mediumblack">:</td>
<td valign="top" class="mediumblack wordbreakwrap">&nbsp;~$profileDetailArr['Details']['GOTHRA_MATERNAL']['label_val']`~if $profileDetailArr['Details']['GOTHRA_MATERNAL']['screenBit'] eq 1`
        <br><span class = "smallred">This field is currently being screened. Please re-check shortly.</span>
        ~/if`</td>
</tr>
<tr>

         <tr>
          <td valign="top" class="mediumgreybl">Living with Parents</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Family']['PARENT_CITY_SAME']['label_val']`</td>
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
       <td colspan="2" class="mediumgreyb">&nbsp; &nbsp;~$otherDetailsArr['username']`'s partner profile ~if $SELF`&nbsp;<span class="blacklinku"><a href="advance_search.php?FLAG=partner&checksum=~$CHECKSUM`">Edit</a></span>~/if`</td>
      </tr>
      <tr>
       <td colspan="2"><img src="/P/imagesnew/zero.gif" width="1" height="5"></td>
      </tr>
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
          <td width="70%" valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_HEIGHT']['label_val']`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Age</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_AGE']['label_val']`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Marital Status</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_MSTATUS']['label_val']`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Have Children </td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_HAVECHILD']['label_val']`</td>
         </tr>
	<tr>
          <td valign="top" class="mediumblack">Country </td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_COUNTRY']['label_val']`</td>
         </tr>
	<tr>
          <td valign="top" class="mediumblack">City </td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_CITY']['label_val']`</td>
         </tr>
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
          <td width="70%" valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_RELIGION']['label_val']`</td>
         </tr>
         <tr>
          <td width="28%" valign="top" class="mediumblack">Caste</td>
          <td width="2%" valign="top" class="mediumblack">:</td>
          <td width="70%" valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_CASTE']['label_val']`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Community </td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_MTONGUE']['label_val']`</td>
         </tr>
	<tr>
          <td valign="top" class="mediumblack">Manglik </td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_MANGLIK']['label_val']`</td>
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
          <td width="70%" valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_DIET']['label_val']`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Smoke</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_SMOKE']['label_val']`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Drink</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_DRINK']['label_val']`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Complexion</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_COMPLEXION']['label_val']`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Body Type </td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_BTYPE']['label_val']`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Challenged</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_CHALLENGED']['label_val']`</td>
         </tr>
	 ~if $profileDetailArr['Dpp']['P_NCHALLENGED']['label_val'] neq ""`
	 <tr>
          <td valign="top" class="mediumblack">Nature of Handicap</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_NCHALLENGED']['label_val']`</td>
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
          <td width="70%" valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_EDUCATION']['label_val']`</td>
         </tr>
         <tr>
          <td valign="top" class="mediumblack">Occupation</td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_OCCUPATION']['label_val']`</td>
         </tr>
	~if $profileDetailArr['Details']['GENDER']['label_val'] eq 'Female'`
         <tr>
          <td valign="top" class="mediumblack">Income </td>
          <td valign="top" class="mediumblack">:</td>
          <td valign="top" class="mediumblack">&nbsp;~$profileDetailArr['Dpp']['P_INCOME']['label_val']`</td>
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
          <td width="64%" class="mediumblack">&nbsp;~if $profileDetailArr['Dpp']['P_CITY']['value'] eq "DM"`~$profileDetailArr['Dpp']['P_COUNTRY']['label_val']`~else`~$profileDetailArr['Dpp']['P_CITY']['label_val']`, ~$profileDetailArr['Dpp']['P_COUNTRY']['label_val']`~/if`</td>
         </tr>
         <tr>
          <td colspan="3" class="mediumblack"><img src="/P/imagesnew/zero.gif" width="1" height="5"></td>
         </tr>
       </table></td>
       <td valign="top">&nbsp;</td>
      </tr>
     </table>
   </tr>
  </table>
  <br>
 </td>
 </tr>
</table>
<br>
</div>

