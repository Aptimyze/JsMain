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
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                ~if $emailType eq 1`
                <table style="border-spacing:0px 10px; max-width:600px; min-width:240px; font-family:Arial, Helvetica, sans-serif; font-size:12px" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="20"></td>
                        <td valign="middle" height="35"> Dear <var>{{NAME_PROFILE:profileid=~$profileid`}}</var>, </td>
                        <td width="20" height="25"></td>
                    </tr>
                    <tr>
                        <td width="20"></td>
                        <td>
                It looks like we have received a request from you to delete your profile on Jeevansathi. As the request was made over call, we want to be double sure. Hence, we request you to delete your profile on your own:
                        </td>

                        <td width="20"></td>
                    </tr>
                
                <tr>
       <td></td>
    <td colspan="2">
        <table border="0" width="130" align="left" cellspacing="0" cellpadding="0" style="font-family:Arial, Verdana; font-size:12px;">
            <tr>
                <td bgcolor="#ad160d" height="27" align="center" width="167"><a href="(LINK)REQUEST_USER_TO_DELETE:profileid=~$profileid`(/LINK)" target="_blank" style="font-size:12px; color:#fff; font-family:Arial, Helvetica, sans-serif;word-break: keep-all;text-decoration: none;"><strong>Delete Profile</strong></a></td>
            </tr>
        </table>
    </td>
            
                </tr>
                <tr>
                        <td width="20"></td>
                        <td>
                Deletion of profiles who have already found a match or have postponed their partner search helps create a better experience for our members. Hence, please try to delete your profile as early as possible.
                        </td>
                        <td width="20" height="25"></td>
                </tr>
                <tr>
                        <td width="20"></td>
                        <td>
                In case you have found a match, congratulations! If not, we are willing to listen to you and try to change your mind to be a member again. Please get in touch with us if you think we can be of any help.
                        </td>
                        <td width="20"></td>
                </tr>

                </table>
                ~/if`
                ~if $emailType eq 2`
                <table style="border-spacing:0px 10px; max-width:600px; min-width:240px; font-family:Arial, Helvetica, sans-serif; font-size:12px" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="20"></td>
                        <td valign="middle" height="35"> Dear <var>{{NAME_PROFILE:profileid=~$profileid`}}</var>, </td>
                        <td width="20" height="25"></td>
                    </tr>
                    <tr>
                        <td width="20"></td>
                        <td>
                Someone on Jeevansathi has indicated that you have already found a match.
                        </td>

                        <td width="20"></td>
                    </tr>
                        <tr>
                        <td width="20"></td>
                        <td>
                    But we understand that this could be in error, so this Email is triggered to check if you have indeed found a match.
                        </td>

                        <td width="20"></td>
                    </tr>
                        <tr>
                        <td width="20"></td>
                        <td>
                    If you have found a match, congratulations! Please delete your profile and post a success story if you found a match through Jeevansath
                        </td>

                        <td width="20"></td>
                    </tr>
                <tr>
      <td></td>
    <td colspan="2">
        <table border="0" width="130" align="left" cellspacing="0" cellpadding="0" style="font-family:Arial, Verdana; font-size:12px;">
            <tr>
                <td bgcolor="#ad160d" height="27" align="center" width="167"><a href="(LINK)REQUEST_USER_TO_DELETE:profileid=~$profileid`(/LINK)" target="_blank" style="font-size:12px; color:#fff; font-family:Arial, Helvetica, sans-serif;word-break: keep-all;text-decoration: none;"><strong>Delete Profile</strong></a></td>
            </tr>
        </table>
    </td>
                </tr>
                <tr>
                        <td width="20"></td>
                        <td>
Deletion of profiles who have already found a match helps create a better experience for our members. Hence, please try to delete your profile as early as possible.
                        </td>
                        <td width="20"></td>
                </tr>
                <tr>
                        <td width="20"></td>
                        <td>
If haven't yet found a match as indicated by the member, please excuse us for the inconvenience. Someone could have incorrectly reported that you have already found a match.
                        </td>
                        <td width="20"></td>
                </tr>

                </table>   
                    
             ~/if`
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
                    
                    
                    <tr>
                       
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
