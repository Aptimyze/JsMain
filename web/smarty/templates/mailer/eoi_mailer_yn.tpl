<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>jeevansathi.com</title>
</head>

<body>
    <table style="max-width:600px; min-width:240px; border:1px solid #dcdcdc;" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
<td colspan="4" style="font-size: 11px !important; font-family: Arial; color: black; padding-top: 10px;">
~$PREHEADER`
</td>
</tr>
        <tr>
            <td style="border-bottom:1px solid #ededed">
                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td width="373" height="52" style="padding-left:10px;">
                            <div><img border="0" align="left" vspace="0" hspace="0" style="max-width:204px; width:inherit;" alt="Jeevansathi.com" src="~$IMG_URL`/images/jspc/commonimg/logo1.png"> </div>
                        </td>
                        <td width="189" valign="middle" style="padding-right:10px;">
                            <table cellspacing="0" cellpadding="0" border="0" align="right" width="189">
                                <tr>
                                    <td align="right" valign="middle" height="50" style="vertical-align:middle;"><a style="font-size:12px; color:#14428e; font-family:Arial, Helvetica, sans-serif;text-decoration: none;" target="_blank" href="(LINK)OWN_PROFILE:profileid=~$profileid`(/LINK)">My Profile</a> | <a style="font-size:12px; color:#14428e; font-family:Arial, Helvetica, sans-serif;text-decoration: none;" target="_blank" href="(LINK)SUGGESTED_MATCHES:profileid=~$profileid`(/LINK)">My Matches</a> </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table style="max-width:600px; min-width:240px; font-family:Arial, Helvetica, sans-serif; font-size:12px" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="20"></td>
                        <td valign="middle" height="35"> Hi <var>{{NAME_PROFILE:profileid=~$profileid`}}</var>, </td>
                        <td width="20" height="25"></td>
                    </tr>
                    <tr>
                        <td width="20"></td>
                        <td>
                            ~if $count eq 1`
                                Here is the weekly summary of a member who is still awaiting your response.You may accept or decline the interest based on your preference.
                            ~else`
                                Here is the weekly summary of ~$countLimit` members who are still awaiting your response.You may accept or decline the interest based on your preference.
                            ~/if`
                        </td>
                        <td width="20"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="600" valign="top" align="left">
                ~$eoi_profile`
                </td>
        </tr>
        <tr>
            <td width="600">
                <table style=" font-family:Arial, Helvetica, sans-serif; font-size:12px" border="0" cellspacing="0" cellpadding="0" width="100%">
                    <tr>
                        <td width="20px" height="10"></td>
                        <td style="color:#4a4a4a;"> </td>
                    </tr>
                    <tr>
                        <td width="600"> </td>
                    </tr>
                    <tr>
                        <td colspan="2" width="600">
                            <table style="max-width:600px; min-width:240px" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td> </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    ~if $count gt 1`
                    <tr>
                        <td valign="top" height="20" colspan="2">
                            <table style="max-width:600px; min-width:240px" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="20"></td>
                                    <td style="font-size:12px; font-family: Arial, Helvetica, sans-serif;"><span color="#0f529d"><a href="(LINK)EOI_RECEIVIED:profileid=~$profileid`(/LINK)" style="color:#14428e; text-decoration:underline;">~$count` people</a></span> have sent you an interest, and would like to receive your response.</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <tr>
                        <td height="10"></td>
                    </tr>
                    <tr>
                        <td height="10" style="border-top:1px solid #eae9e9"><img align="left" width="6" vspace="0" hspace="0" height="10" src="~$IMG_URL`/images/mailer/ADRM/spacer.gif"> </td>
                    </tr>
                    ~/if`
                   
                     ~if $RECEIVER_IS_PAID neq "1"`
                    <tr>
                        <td>
                            <table align="left" style="max-width:340px; min-width:240px;" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                        <table cellspacing="0" cellpadding="0" border="0" align="left" style="font-family:Arial; font-size:12px; color:#000000; max-width:340px;">
                                            <tr>
                                                <td width="20"></td>
                                                <td width="340" height="24" colspan="2"><font color="#000000" face="Arial" style="line-height:20px;"><strong>Contact Who you want, When you want - Upgrade Now.</strong></font> </td>
                                            </tr>
                                            <tr>
                                                <td width="20"></td>
                                                <td width="20" valign="top"><img border="0" align="left" width="16" vspace="4" hspace="0" height="17" alt="1" src="~$IMG_URL`/images/mailer/ADRM/bull1.gif"> </td>
                                                <td height="24">Instantly see Phone/Email of people you like</td>
                                            </tr>
                                            <tr>
                                                <td width="20"></td>
                                                <td valign="top"><img border="0" align="left" width="16" vspace="3" hspace="0" height="17" alt="2" src="~$IMG_URL`/images/mailer/ADRM/bull2.gif"> </td>
                                                <td height="24">Initiate Email, Message, Chat with them</td>
                                            </tr>
                                            <tr>
                                                <td width="20"></td>
                                                <td valign="top"><img border="0" align="left" width="16" vspace="3" hspace="0" height="17" alt="3" src="~$IMG_URL`/images/mailer/ADRM/bull3.gif"> </td>
                                                <td height="24">Get more Interests and faster Responses</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <table cellspacing="0" cellpadding="0" border="0" align="left" width="" style=" margin:0px">
                                <tr>
                                    <td width="20"> </td>
                                    <td width="20"></td>
                                    <td height="28"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td bgcolor="#003a7e" align="center" height="27" style="border:1px solid #003a7e;"><a style="font-family:Arial; font-size:13px; color:#ffffff; text-decoration:none; line-height:25px; width:100%; float:left" target="_blank" href="(LINK)MEMBERSHIP_COMPARISON:profileid=~$profileid`,source=~$BottomSource`(/LINK)"><strong>~if $variableDiscount`Get upto ~$variableDiscount`% OFF ~else`Upgrade Membership~/if`</strong></a> </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <table cellspacing="0" cellpadding="0" border="0" align="left" width="198">
                                            <tr>
                                                <td width="24" height="29"><img align="right" width="20" vspace="0" hspace="0" height="24" src="~$IMG_URL`/images/mailer/ADRM/lockIC.gif"> </td>
                                                <td><font color="#575656" face="Arial" style="font-size:11px;"><em>Easy and Secure Payment Options</em></font> </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td height="10"></td>
                    </tr>
                    <tr>
                        <td height="10" style="border-bottom:1px solid #eae9e9"></td>
                    </tr>
                    ~/if`
                    
                    <tr>
                        <tr>
                            <td height="20"></td>
                        </tr>
                        <tr>
                            <td valign="top" height="30">
                                <table style="max-width:600px; min-width:240px" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="20"></td>
                                        <td style=" font-family: Arial, Helvetica, sans-serif; font-size:12px;">Wish you success in your search.</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">
                                <table style="max-width:600px; min-width:240px" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="20"></td>
                                        <td style=" font-family: Arial, Helvetica, sans-serif; font-size:12px;">Warm Regards,
                                            <br> <b style="color:#c4161c;">Jeevansathi</b><span style="font-size:1px;"> </span><b color="#00000">.com Team</b> </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td height="40"></td>
                        </tr>
                        <tr>
                            <td align="center" valign="top" height="20"><font face="Tahoma, Geneva, sans-serif" style="font-size:12px;">Got any Questions?</font> </td>
                        </tr>
                        <tr>
                            <td height="40">
                                <table cellspacing="0" cellpadding="0" border="0" align="left" width="241" style="font-family:Tahoma, Geneva, sans-serif; font-size:11px; color:#555555; text-align:left">
                                    <tr>
                                        <td width="15"></td>
                                        <td width="23"><img align="left" width="23" vspace="0" hspace="0" height="20" src="~$IMG_URL`/images/mailer/ADRM/icon1.gif"> </td>
                                        <td><var>{{TOLLNO:profileid=~$profileid`}}</var> (Toll Free) or <var>{{NOIDALANDL}}</var></td>
                                    </tr>
                                    <tr>
                                        <td height="8" colspan="2"></td>
                                    </tr>
                                </table>
                                <table cellspacing="0" cellpadding="0" border="0" align="left" width="158" style="font-family:Tahoma, Geneva, sans-serif; font-size:11px; color:#555555; text-align:left">
                                    <tr>
                                        <td width="15"></td>
                                        <td width="28" height="20"><img align="left" width="28" vspace="0" hspace="0" height="14" src="~$IMG_URL`/images/mailer/ADRM/msg_IC.gif"> </td>
                                        <td><a style="color:#0f529d; text-decoration:underline;" target="_blank" href="mailto:help@jeevansathi.com">help@jeevansathi.com </a> </td>
                                    </tr>
                                    <tr>
                                        <td height="8" colspan="2"></td>
                                    </tr>
                                </table>
                                <table cellspacing="0" cellpadding="0" border="0" align="left" width="165" style="font-family:Tahoma, Geneva, sans-serif; font-size:11px; color:#555555; text-align:left">
                                    <tr>
                                        <td width="15"></td>
                                        <td width="18" height="20"><img align="left" width="18" vspace="0" hspace="0" height="18" src="~$IMG_URL`/images/mailer/ADRM/visitIC.gif"> </td>
                                        <td>Visit any of our <a style="color:#0f529d;" target="_blank" href="(LINK)ALLCENTRESLOCATIONS:profileid=~$profileid`(/LINK)">60+centres</a> </td>
                                    </tr>
                                    <tr>
                                        <td height="8" colspan="2"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="top" height="20"><font face="Tahoma, Geneva, sans-serif" style="font-size:12px;">----- More Value, Less Money -----</font> </td>
                        </tr>
                </table>
            </td>
            </tr>
            <tr>
                <td align="center" width="600px">
                    <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
                        <tr>
                            <td>
                                <table cellspacing="0" cellpadding="0" border="0" bgcolor="#f0f0f0" align="left" style="font-family:Tahoma, Geneva, sans-serif; font-size:11px; color:#555555; text-align:center; max-width:600px; min-width:240px;">
                                    <tr>
                                        <td width="148" height="36" style="border:1px dashed #c4c3c3; border-top:0px;">LOWEST
                                            <br>Price per Contact </td>
                                        <td width="148" style="border:1px dashed #c4c3c3; border-left:0px; border-top:0px;">MAXIMUM
                                            <br>Benefits per Month</td>
                                        <td width="148" height="36" style="border:1px dashed #c4c3c3; border-top:0px;">BIGGEST
                                            <br>Savings per Plan </td>
                                        <td width="148" style="border:1px dashed #c4c3c3; border-left:0px; border-top:0px;">ON CALL
                                            <br>Customer Service</td>
                                    </tr>
                                </table>
                                <table cellspacing="0" cellpadding="0" border="0" bgcolor="#f0f0f0" align="left" style="font-family:Tahoma, Geneva, sans-serif; font-size:11px; color:#555555; text-align:center; max-width:300px; min-width:240px;">
                                    <tr> </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
               <td align="center" height="31" style="font-size:11px;">You have received this mail because your e-mail ID is registered with Jeevansathi.com. This is a system-generated e-mail, please don't reply to this message. The profiles sent in this mail have been posted by registered members of Jeevansathi.com. Jeevansathi.com has taken all reasonable steps to ensure that the information in this mailer is authentic. Users are advised to research bonafides of these profiles independently. To stop receiving these mails <a style="text-decoration:none;" target="_blank" href="(LINK)UNSUBSCRIBE:profileid=~$profileid`(/LINK)"><font color="#7d7b7b" face="Tahoma, Geneva, sans-serif" style="font-size:11px; text-align:center"><u>Unsubscribe</u></font></a> </td>
            </tr>
    </table>
    <img src="~$SITE_URL`/track.php?MAILER_ID=~$instanceID`" style="display:none !important;overflow:hidden;line-height:0"></img>
</body>

</html>
