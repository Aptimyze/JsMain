~foreach from=$shortlistedMailerTuple_inputs item=userProfileId key=index`

<table align="left" border="0" cellspacing="0" cellpadding="0" ~if $index % 2 eq 1` style="margin-right:1px;max-width:298px; border-left:1px solid #eae9e9;" ~else` style="margin-right:1px; max-width:298px;" ~/if`>
<tr>
    <td></td>
    <td height="28" style="padding-left:5px;"><a style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#14428e; text-decoration:none;" target="_blank" href="(LINK)DETAILED_PROFILE_HOME:profileid=~$profileid`,receiver_id=~$userProfileId`,source=~JSTrackingPageType::EOI_MAILER`(/LINK)"><var>{{USERNAME:profileid=~$userProfileId`}}</var></a> <var>{{PAIDSTATUS:profileid=~$userProfileId`}}</var></td>
    <td></td>
</tr>
<tr>
    <td width="22">
        <img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/spacer.gif" width="20" height="1" vspace="0" hspace="0" align="left">
    </td>
    <td width="125" height="140" valign="top">
       
        <a href="(LINK)DETAILED_PROFILE_HOME:profileid=~$profileid`,receiver_id=~$userProfileId`,source=~JSTrackingPageType::EOI_MAILER`(/LINK)" style="text-decoration:none; color:#0f529d;" target="_blank"><img border="0" align="left" width="100" vspace="0" hspace="0" height="133" src="(PHOTO)PROFILE_PIC:receiver_id=~$profileid`,profileid=~$userProfileId`,photo_type=search(/PHOTO)" style="border:1px solid #e6e6e6;"></a>
    </td>
    <td width="188" valign="top">
        <table border="0" cellspacing="2" align="left" cellpadding="0" style="font-family:Arial, Verdana; font-size:12px; color:#000000;-webkit-text-size-adjust: none; text-align:left;">
            <tr>
                <td><var>{{AGE:profileid=~$userProfileId`}}</var> yrs; <var>{{HEIGHT:profileid=~$userProfileId`}}</var></td>
            </tr>
            <tr>
                <td><var>{{RELIGION_CASTE_VALUE_TEMPLATE_2:profileid=~$userProfileId`}}</var></td>
            </tr>
            <tr>
                <td><var>{{MTONGUE_SMALL:profileid=~$userProfileId`}}</var></td>
            </tr>
            <tr>
                <td><a style="text-decoration:none; cursor:default; color:#000000;"><var>{{EDUCATION:profileid=~$userProfileId`}}</var></a></td>
            </tr>
            <tr>
                <td><var>{{OCCUPATION:profileid=~$userProfileId`}}</var></td>
            </tr>
            <tr>
                <td><var>{{INCOME:profileid=~$userProfileId`}}</var></td>
            </tr>
            <tr>
                <td><var>{{CITY_WITH_COUNTRY:profileid=~$userProfileId`}}</var></td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td></td>
    <td colspan="2">
        <table border="0" width="130" align="left" cellspacing="0" cellpadding="0" style="font-family:Arial, Verdana; font-size:12px;">
            <tr>
                <td bgcolor="#ad160d" height="27" align="center" width="167"><a href="(LINK)DETAILED_PROFILE_HOME:profileid=~$profileid`,receiver_id=~$userProfileId`,source=~JSTrackingPageType::EOI_MAILER`(/LINK)" target="_blank" style="font-size:12px; color:#fff; font-family:Arial, Helvetica, sans-serif;word-break: keep-all;text-decoration: none;"><strong>View Profile</strong></a></td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    ~if $index % 2 neq 1`	
    <td></td>
    ~/if`
    <td ~if $index % 2 neq 1` colspan="2"~else` colspan="3"~/if` height="15" ~if !(($index eq $count -1 ) || ( $index % 2 eq 0 && $index eq $count -2))` style="border-bottom:1px solid #eae9e9;"~/if`><img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/spacer.gif" width="1" height="15" vspace="0" hspace="0" align="left"></td>
</tr>
</table>
~/foreach`