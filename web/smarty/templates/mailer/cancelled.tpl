<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>

<body>
<table border="0" cellspacing="0" cellpadding="0" style="max-width:600px; min-width:318px; border:1px solid #efefef; -webkit-text-size-adjust: none; font-family:Arial; font-size:13px; color:#000000;" align="center">
	<tr>
<td colspan="4" style="font-size: 11px !important; font-family: Arial; color: black; padding-top: 10px;">
~$PREHEADER`
</td>
</tr>
	<tr>
          <td colspan="3" height="10" valign="top"  style="font-family:Arial; font-size:10px; color:#000000; padding-left:13px;"><p>~if $FTO eq 1`Take FREE TRIAL OFFER - Verify Phone &amp; Add Photo ~elseif $FTO eq 2`Jeevansathi matches Thousands of people every month. ~/if`
</p></td>
        </tr>
  <tr>
    <td width="10"></td>
    <td width="562"><img src="~$IMG_URL`/images/jspc/commonimg/logo1.png" width="213" height="56" vspace="0" hspace="0" align="left" border="0" alt="Jeevansathi.com" />
      <table border="0" cellpadding="0" cellspacing="0" align="right">
        <tr>
          <td width="184" height="56"><font style="font-size:10px;" color="#474646">Call us at <var>{{TOLLNO:profileid=~$profileid`}}</var> (Toll free), or<br />
            Visit any of our <a href="(LINK)ALLCENTRESLOCATIONS:profileid=~$profileid`(/LINK)" target="_blank" style="color:#0f529d;">60+ offices</a> across India</font></td>
        </tr>
      </table></td>
    <td width="10"></td>
  </tr>
  <tr>
    <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:22px; color:#ffffff; font-weight:bold;" align="left">
        <tr>
          <td height="44"></td>
          <td></td>
          <td rowspan="3" width="214"><img src="~$IMG_URL`/images/mailer/declineMailer/envelop.jpg" width="214" height="144" vspace="0" hspace="0" align="right" /></td>
        </tr>
        <tr>
          <td height="62" width="15"><img src="~$IMG_URL`/images/mailer/declineMailer/BandLft.gif" width="5" height="62" vspace="0" hspace="0" align="right" /></td>
          <td bgcolor="#920606" style="padding-left:10px;">Life has more to offer...
            <div style="padding-left:25px; font-size:28px; line-height:25px;">AND SO DO WE!</div></td>
        </tr>
        <tr>
          <td height="38"></td>
          <td></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td></td>
    <td height="45" valign="top">Dear <var>{{NAME_PROFILE:profileid=~$profileid`}}</var>,<br /><br />
      This is to inform you that Jeevansathi member <var>{{USERNAME:profileid=~$otherProfile`}}</var> has cancelled ~if $GENDER eq 'M'`her~elseif $GENDER eq 'F'`his~/if` expression of interest in you.</td>
    <td></td>
  </tr>
  <tr>
    <td colspan="3" height="5"></td>
  </tr>
  <tr>
    <td></td>
    <td bgcolor="#f7f7f7" style="padding:5px;"><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border:1px solid #ececec; padding:5px; font-family:Arial; font-size:11px; color:#000000;">
        <tr>
          <td width="67"><a href="(LINK)PHOTO_ALBUM:profileid=~$profileid`,receiver_id=~$otherProfile`(/LINK)"target=_"blank"><img src="(PHOTO)PROFILE_PIC:receiver_id=~$profileid`,profileid=~$otherProfile`,photo_type=thumbnail(/PHOTO)" width="61" height="61" vspace="0" hspace="0" align="left" /></a></td>
          <td valign="bottom"> <var>{{AGE:profileid=~$otherProfile`}}</var>, <var>{{HEIGHT:profileid=~$otherProfile`}}</var>,<var>{{MTONGUE:profileid=~$otherProfile`}}</var>,<br />
            <var>{{CITY:profileid=~$otherProfile`}}</var>,<var>{{OCCUPATION:profileid=~$otherProfile`}}</var>, <var>{{INCOME:profileid=~$otherProfile`}}</var>
            <div align="right"><a href="(LINK)DETAILED_PROFILE_HOME:receiver_id=~$otherProfile`,profileid=~$profileid`(/LINK)" target="_blank" style="text-decoration:underline; color:#0f529d; font-family:Arial; font-size:11px; line-height:17px;">View Full Profile</a></div></td>
        </tr>
      </table></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="3" height="15"></td>
  </tr>
  <tr>
    <td></td>
    <td style="padding:0 5px;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:13px; color:#000000;">
        <tr>
          <td valign="top" height="21"><strong>The interest could have been cancelled due to the following reasons:</strong></td>
        </tr>
        <tr>
          <td><table width="94%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:13px; color:#000000;">
              <tr>
                <td valign="top"><img src="~$IMG_URL`/images/mailer/declineMailer/arrowBullt.gif" width="16" height="9" vspace="6" hspace="0" align="left" /></td>
                <td style="line-height:22px;">Your profile doesn't meet this member's set criteria</td>
              </tr>
              <tr>
                <td width="16" valign="top"><img src="~$IMG_URL`/images/mailer/declineMailer/arrowBullt.gif" width="16" height="9" vspace="6" hspace="0" align="left" /></td>
                ~if $photo eq 1`
                <td width="494">Your profile is incomplete. You should <a href="(LINK)UPLOAD_PHOTO:profileid=~$profileid`(/LINK)" target="_blank" style="text-decoration:underline; color:#0f529d;">Upload more photos</a> and <a href="(LINK)DETAILED_PROFILE_HOME:receiver_id=~$profileid`,profileid=~$profileid`(/LINK)" target="_blank" style="text-decoration:underline; color:#0f529d;">add more information</a> such as education, lifestyle, hobbies etc. to receive more positive responses</td>
                ~else if $photo eq 0`
                <td width="494">Your profile is incomplete. Upload your photo to receive more positive responses. 
				<table><tr>
					~if $GENDER eq 'M'`
					<td width="110" height="32" background="~$IMG_URL`/images/mailer/declineMailer/btn-update-female-trans.png" style="font:12px 'Trebuchet MS', Arial, Helvetica, sans-serif; padding-left:50px">
					~elseif $GENDER eq 'F'`	
					<td width="110" height="32" background="~$IMG_URL`/images/mailer/declineMailer/btn-update-male-trans.png" style="font:12px 'Trebuchet MS', Arial, Helvetica, sans-serif; padding-left:50px">
					~/if`
						<a style="COLOR:#000; text-decoration:NONE" href="(LINK)UPLOAD_PHOTO:profileid=~$profileid`(/LINK)">UPLOAD PHOTO</a></td></tr></table></td>
						~/if`
				</tr>
            </table></td>
        </tr>
        <tr>
          <td height="20"></td>
        </tr>
        <tr>
          <td style="line-height:22px;"><em><strong style="color:#4c4b4b;">And don't lose hope!</strong></em></td>
        </tr>
        <tr>
          <td style="font-size:12px; color:#5f5f5f;" width="538">There are a lot more profiles that match your criteria. So, continue your search and browse through our database of over 6 Lakh profiles and find your dream match now.</td>
        </tr>
        <tr>
          ~$suggested_profiles`
        
        <tr>
			<tr>
          <td align="right" style="font-size:11px; color:#474646;" height="40"><a href="(LINK)SUGGESTED_MATCHES:profileid=~$profileid`(/LINK)#" target="_blank" style="text-decoration:underline; color:#0f529d;">Click here</a> to see more profiles</td>
        </tr>
          <td height="11"></td>
        </tr>
        <tr>
          <td style="font-size:12px;" valign="top" height="30">Wish you luck in your search!</td>
        </tr>
        <tr>
          <td><table style="font-family:Arial,Times New Roman,Times,serif;font-size:11px;line-height:17px; color:#000000; margin-left:10px;-webkit-text-size-adjust: none;" align="left" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td style="font-size:12px" valign="top">Warm Regards,<br>
                    <b style="color:#c4161c;">Jeevansathi<span style="font-size:1px;"> </span><font color="#00000">.com Team</font></b><br />
                    <a href="(LINK)JS_FB_PAGE:profileid=~$profileid`(/LINK)" target="_blank"><img src="~$IMG_URL`/images/mailer/declineMailer/fbBTN.gif" width="111" height="29" border="0" alt="Join Us on Facebook" vspace="4"></a></td>
                  <td style="font-size:12px" valign="top" width="10"></td>
                </tr>
              </tbody>
            </table></td>
        </tr>
      </table></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td height="20" style="border-bottom:1px solid #f5f3f3;"><img src="~$IMG_URL`/images/mailer/declineMailer/spacer.gif" width="1" height="20" /></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td width="562">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; color:#000000; font-size:10px;">
      <tr>
        <td valign="bottom" height="22"> <table width="300" border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial; color:#000000; font-size:11px;">
                  <tr>
                    <td>You can also visit any of our 60+ offices across India</td>
                  </tr>
                </table>
                <table width="133" border="0" cellspacing="0" cellpadding="0" align="right" style="font-family:Arial; color:#000000; font-size:11px;">
                  <tr>
                    <td><a href="(LINK)ALLCENTRESLOCATIONS:profileid=~$profileid`(/LINK)" target="_blank" title="View all Centre Addesses" style="color:#0f529d">View all Centre Addesses</a></td>
                  </tr>
                </table></td>
      </tr>
      <tr>
        <td height="12"></td>
      </tr>
      <tr>
        <td width="542"><span style="color:#711e1e">North India:</span> Noida, Gurgaon, Delhi - <font color="#7a7676">Laxmi Nagar, Connaught Place, Nehru Place, Kamla Nagar, Pitampura, Malviya Nagar, Rajori Garden,</font> Agra, Allahabad, Chandigarh, Jaipur, Kanpur, Dehradun, Varanasi, Lucknow, Ludhiana, Jallandhar, Gorakhpur, Pathankot, Amritsar, Jammu, Bareilly</td>
      </tr>
      <tr>
        <td height="12"></td>
      </tr>
      <tr>
        <td><span style="color:#711e1e">West India:</span> Goa, Kolahpur, Ahmedabad, Aurangabad, Baroda, Nagpur, Nashik, Rajkot, Surat, Pune - <font color="#7a7676">Chinchwad, Deccan, Koregaon, Modelina Road, Mumbai - Andheri(West), Andheri East, Borivalli, Chembur, Ghatkopar, Mulund, Thane, Vashi, Worli</font></td>
      </tr>
      <tr>
        <td height="12"></td>
      </tr>
      <tr>
        <td><span style="color:#711e1e">South India:</span> Chennai, Kochi, Coimboture, Hosur, Mangalore, Mysore, Trichy, Trivandrum, Vijaywada, Vyzag
Bangalore - <font color="#7a7676">Dickenson Road, Koramangla,</font> Hyderabad - <font color="#7a7676">Begumpet, Himmayat Nagar</font></td>
      </tr>
      <tr>
        <td height="12"></td>
      </tr>
      <tr>
        <td><span style="color:#711e1e">East &amp; Central India:</span> Ranchi, Bhopal, Bhubneshwar, Indore, Patna, Jamshedpur,  Gwalior, Jabalpur, Raipur,
Kolkata - <font color="#7a7676">Ghariat Road, Salt Lake, AJC Bose Road</font></td>
      </tr>
       <tr>
        <td height="12"></td>
      </tr>
    </table>
</td>
    <td></td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#f7f6f6"  height="23"><table width="75%" border="0" cellspacing="0" cellpadding="0" align="center" style="font-family:Verdana, Arial, Times New Roman, Times, serif; color:#000000; font-size:11px;">
                  <tr>
                    <td><table width="97%" border="0" cellspacing="0" cellpadding="0" align="center" style="font-family:Verdana, Arial, Times New Roman, Times, serif; color:#000000; font-size:11px;">
                      <tr>
                        <td width="150">100% Profile Screening</td>
                        <td width="20" align="center"><font color="#b7b6b6">|</font></td>
                        <td width="160">Lakhs of Success Stories</td>
                        <td width="20" align="center"><font color="#b7b6b6">|</font></td>
                        <td width="120">Privacy Options</td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
  </tr>
  <tr>
    <td align="center" height="40" colspan="3" style="font-family:Verdana, Arial, Times New Roman, Times, serif; color:#c9c9c9; font-size:11px; line-height:16px;">You have received this mail because your e-mail ID is registered with Jeevansathi.com. This is a system-generated e-mail, please don't reply to this message. The profiles sent in this mail have been posted by registered members of Jeevansathi.com. Jeevansathi.com has taken all reasonable steps to ensure that the information in this mailer is authentic. Users are advised to research bonafides of these profiles independently. To stop receiving these mails <a href="(LINK)UNSUBSCRIBE:profileid=~$profileid`(/LINK)" target="_blank" title="Click here" style="color:#c9c9c9;">UNSUBSCRIBE</a>.</td>
  </tr>
</table>
</body>
</html>
