		~assign var='kk' value=0`
        ~foreach from=$dpp_matches_inputs item=profile_id key=k`
                ~if $kk neq 0`
		<p></p>
                ~/if`
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
                   <!-- <tr>
                      <td height="13"></td>
                    </tr>-->
		<tr><td bgcolor="#f7f6f6" align="center">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
                      <td style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#575656" height="22" align="left">
                      &nbsp; &nbsp; <b><var>{{USERNAME:profileid=~$profile_id`}}</var></b>
                      </td>
                    </tr></table>
		<table width="96%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #f4e4fc;" bgcolor="#FFFFFF">
			<tr>
                      <td height="8" colspan="3"></td>
                    </tr>	
			    <tr>
	      <td width="9"></td>
                      <td width="532" align="left" valign="top" height="137">
                      <table width="110" border="0" cellspacing="0" cellpadding="0" align="left">
                          <tr>
                            <td><a href="(LINK)PHOTO_ALBUM:profileid=~$profileid`,receiver_id=~$profile_id`(/LINK)" style="text-decoration:none; color:#0f529d;" target="_blank"><img src="(PHOTO)PROFILE_PIC:receiver_id=~$profileid`,profileid=~$profile_id`,photo_type=search(/PHOTO)" width="100" height="133" hspace="0" vspace="0" border="0" style="border:1px solid #e6e6e6;" /></a></td>
                          </tr>
			~if $profileid neq $profile_id`
                          <tr>
                            <td height="30"><table border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Verdana; font-size:12px; margin-right:1.5%;" align="left">
                            <tr>
				<var>{{ALBUM_LINK:profileid=~$profileid`,photo_type=album_link,receiver_id=~$profile_id`}}</var>
                            </tr>
                          </table></td>
                          </tr>
			~/if`
                        </table>
                      <table width="76%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Verdana; font-size:12px; color:#000000;-webkit-text-size-adjust: none;">
                        <tr>
                          <td valign="top"><table width="100%" height="133" border="0" cellspacing="1" cellpadding="0" style="font-family:Arial, Verdana; font-size:12px; color:#000000;-webkit-text-size-adjust: none;">
                            <tr>
                              <td width="107"><b>Age &amp; Height</b></td>
                              <td width="5">:</td>
                              <td width="298"><var>{{AGE:profileid=~$profile_id`}}</var> yrs; <var>{{HEIGHT:profileid=~$profile_id`}}</var></td>
                            </tr>
                            <tr>
                              <td><b><var>{{RELIGION_CASTE_OR_SECT_LABEL:profileid=~$profile_id`}}</var></b></td>
                              <td>:</td>
                              <td><var>{{RELIGION_CASTE_VALUE_TEMPLATE:profileid=~$profile_id`}}</var></td>
                            </tr>
                            <tr>
                              <td><b>Mother Tongue</b></td>
                              <td>:</td>
                              <td><var>{{MTONGUE:profileid=~$profile_id`}}</var></td>
                            </tr>
                            <tr>
                              <td><b>Education</b></td>
                              <td>:</td>
                              <td><var>{{EDUCATION:profileid=~$profile_id`}}</var></td>
                            </tr>
                            <tr>
                              <td><b>Occupation</b></td>
                              <td>:</td>
                              <td><var>{{OCCUPATION:profileid=~$profile_id`}}</var></td>
                            </tr>
                            <tr>
                              <td><b>Income</b></td>
                              <td>:</td>
                              <td><var>{{INCOME:profileid=~$profile_id`}}</var></td>
                            </tr>
                            <tr>
                              <td><b>Location</b></td>
                              <td>:</td>
                              <td><var>{{CITY_WITH_COUNTRY:profileid=~$profile_id`}}</var></td>
                            </tr>
				<!--
				<tr>
				<td colspan="3" height="30"></td>
				</tr>-->
                          </table></td>
                        </tr>
			~if $profileid neq $profile_id`
                        <tr>
                          <td align="right" style="padding-top:7px;"><table border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Verdana; font-size:12px;" align="left">
                            <tr>
                              <td width="100" align="center" style="border:1px solid #c7c7c7;" height="25" background="~$IMG_URL`/images/mailer/partialTemplateImages//gry_btn_bg.gif" bgcolor="#d0cece"><a href="(LINK)DETAILED_PROFILE_HOME:profileid=~$profileid`,receiver_id=~$profile_id`(/LINK)" style="text-decoration:none; color:#0f529d;" target="_blank"><img src="~$IMG_URL`/images/mailer/partialTemplateImages//fullIC.gif" width="32" height="16" hspace="0" vspace="0" border="0" align="absmiddle" />Full Profile</a></td>
                            </tr>
                          </table>
~if $acceptance_mailer neq 1 && $PHOTO_REQUEST_MAILER neq 1`
                                                <table border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Verdana; font-size:12px;" align="right">
  <tr>
    <td background="~$IMG_URL`/images/mailer/partialTemplateImages/expressBTN.gif" height="27" align="center" width="167"><a href="(LINK)EXPRESS_INTEREST:profileid=~$profileid`,receiver_id=~$profile_id`(/LINK)" target="_blank" style="text-decoration:none; color:#ffffff;">&nbsp; &nbsp; &nbsp; <strong>EXPRESS INTEREST</strong></a></td>
  </tr>
</table>
~/if`
			</td>
                        </tr>
			~/if`
                      </table></td>
                      <td width="9"></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="8"></td>
                    </tr>
                  </table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                      <td height="10">
                      </td>
                    </tr></table>
</td></tr></table>
		~assign var='kk' value=$kk+1`
        ~/foreach`
