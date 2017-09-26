~assign var="count" value=$eoi_profile_inputs|@count`
~foreach from=$eoi_profile_inputs item=eoi_draft key=eoiProfileId`
<table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td height="10"></td>
                    </tr>
                    <tr>
                        <td>
                            <table style="max-width:280px; min-width:240px" border="0" cellspacing="0" cellpadding="0" align="left">
                                <tr>
                                    <td></td>
                                    <td height="25"><a style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#14428e; text-decoration:none;" target="_blank" href="(LINK)DETAILED_PROFILE_HOME:profileid=~$profileid`,receiver_id=~$eoiProfileId`,source=~JSTrackingPageType::EOI_MAILER`(/LINK)"><var>{{NAME_PROFILE:profileid=~$eoiProfileId`}}</var></a> <var>{{PAIDSTATUS:profileid=~$eoiProfileId`}}</var> </td>
                                </tr>
                                <tr>
                                    <td width="20"></td>
                                    <td width="280">
                                        <table width="110" border="0" cellspacing="0" cellpadding="0" align="left">
                                            <tr>
                                                <td width="133"><a href="(LINK)DETAILED_PROFILE_HOME:profileid=~$profileid`,receiver_id=~$eoiProfileId`,source=~JSTrackingPageType::EOI_MAILER`(/LINK)" style="text-decoration:none; color:#0f529d;" target="_blank"><img border="0" align="left" width="133" vspace="0" hspace="0" height="133" src="(PHOTO)PROFILE_PIC:receiver_id=~$profileid`,profileid=~$eoiProfileId`,photo_type=contactMailer(/PHOTO)" style="border:1px solid #e6e6e6;"></a></td>
                                            </tr>
                                            <tr>
                                                <td height="15"></td>
                                            </tr>
                                            <tr>
                                                <td height=""></td>
                                            </tr>
                                        </table>
                                        <table width="150" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Verdana; font-size:12px; color:#000000;-webkit-text-size-adjust: none; text-align:left;">
                                            <tr>
                                                <td width="5"></td>
                                                <td><var>{{AGE:profileid=~$eoiProfileId`}}</var> yrs; <var>{{HEIGHT:profileid=~$eoiProfileId`}}</var></td>
                                            </tr>
                                            <tr>
                                                <td width="5"></td>
                                                <td><var>{{RELIGION_CASTE_VALUE_TEMPLATE:profileid=~$eoiProfileId`}}</var></td>
                                            </tr>
                                            <tr>
                                                <td width="5"></td>
                                                <td><var>{{MTONGUE:profileid=~$eoiProfileId`}}</var></td>
                                            </tr>
                                            <tr>
                                                <td width="5"></td>
                                                <td><a style="text-decoration:none; cursor:default; color:#000000;"><var>{{EDUCATION:profileid=~$eoiProfileId`}}</var></a></td>
                                            </tr>
                                            <tr>
                                                <td width="5"></td>
                                                <td><var>{{OCCUPATION:profileid=~$eoiProfileId`}}</var></td>
                                            </tr>
                                            <tr>
                                                <td width="5"></td>
                                                <td><var>{{INCOME:profileid=~$eoiProfileId`}}</var></td>
                                            </tr>
                                            <tr>
                                                <td width="5"></td>
                                                <td><var>{{CITY_WITH_COUNTRY:profileid=~$eoiProfileId`}}</var></td>
                                            </tr>
                                            <tr>
                                                <td height="10"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table style="width:260px; " border="0" cellspacing="0" cellpadding="0" align="left">
                                <tr>
                                    <td height="25"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width="20"></td>
                                    <td width="220" style="font-family:Arial, Verdana; font-size:12px; color:#4a4a4a;-webkit-text-size-adjust: none; text-align:left; word-break: break-word;"> <span><var>{{YOURINFO:profileid=~$eoiProfileId`}}</var></span> <a href="(LINK)DETAILED_PROFILE_HOME:profileid=~$profileid`,receiver_id=~$eoiProfileId`,source=~JSTrackingPageType::EOI_MAILER`(/LINK)" target="_blank" style="font-size:12px; color:#14428e; font-family:Arial, Helvetica, sans-serif;word-break: keep-all;text-decoration: none;"> Read more</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td height="10"></td>
                    </tr>
   
				   ~if $eoi_draft|replace:' ':'' neq ""`
				   <tr>
					    <td>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="20"></td>
									<td style="color:#4a4a4a; font-family: Arial, Helvetica, sans-serif; font-size:12px;"><strong>Message:</strong> ~$eoi_draft` </td>
								</tr>
				 
							</table>
						</td>
					</tr>
					<tr>
                        <td height="10"></td>
                    </tr>
				   ~/if`
                    <tr>
                        <td>
                            <table style="max-width:600px; min-width:240px" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="20"></td>
                                    <td style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">How would you like to respond?</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td height="10"></td>
                    </tr>
                    <tr>
                        <td>
                            <table cellspacing="0" cellpadding="0" border="0" width="235">
                                <tr>
                                    <td width="20"></td>
                                    <td width="80">
                                        <table cellspacing="0" cellpadding="0" border="0" align="left" width="100" style="font-family:Arial, Verdana; font-size:14px;">
                                            <tr>
                                                <td bgcolor="#ad160d" align="center" width="100" height="27">
                                                    <a style="text-decoration:none; width:100px; color:#ffffff; float:left" target="_blank" href="(LINK)ACCEPT:profileid=~$profileid`,receiver_id=~$eoiProfileId`,source=eoi(/LINK)"> <strong>Accept</strong> </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="10"></td>
                                    <td><a style="font-size:12px; color:#14428e; font-family:Arial, Helvetica, sans-serif; text-decoration:underline;" target="_blank" href="(LINK)DECLINE:profileid=~$profileid`,receiver_id=~$eoiProfileId`,source=eoi(/LINK)">Not interested</a> </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td height="25" style="border-bottom:1px solid #eae9e9"></td>
                    </tr>
                </table>
~/foreach` 
