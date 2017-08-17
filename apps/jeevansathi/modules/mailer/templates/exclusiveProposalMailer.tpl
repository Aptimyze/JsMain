~include_partial("global/mailerheader")`
<body>
<table align="center" style="font-size: 11px;">
    <tr>
        <td>Please add ~$mailerName` to your address book to ensure delivery of this mail into your inbox</td>
    </tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border:1px solid #dcdcdc; max-width:650px; text-align:left" align="center">
    <tr>
        <td colspan="3"><img src="~JsConstants::$ser2Url`/mailer/openRate?checksum=~$data.RECEIVER.PROFILECHECKSUM`&sent_date=~$data.OpenTracking.sentDate`&freq=~$data.OpenTracking.frequency`&email=~$data.OpenTracking.emailType`&stype=~$data.stypeMatch`" width="0" height="0" vspace="0" hspace="0" align="left"></td>
    </tr>
    <tr>
        <td style="border-bottom:1px solid #ededed">
            ~include_partial("global/mailer_sub_header",[commonParamaters=>$data.commonParamaters,stype=>$data.stypeMatch,mailerLinks=>$mailerLinks])`
        </td>
    </tr>
    <tr>
        <td width="650" style="border-top:1px solid #dcdcdc">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="">
                <tr>
                    <td colspan="3" height="10"></td>
                </tr>
                <tr>
                    <td width="22"><img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/spacer.gif" width="6" height="1" vspace="0" hspace="0" align="left" /></td>
                    <td width="606">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Times New Roman, Times, serif; font-size:12px; color:#000000; text-align:left;">
                            <tr>
                                <td valign="top">Hi ~if $data.RECEIVER.PROFILE->getNAME()`~$data.RECEIVER.PROFILE->getNAME()`~else`~$data.RECEIVER.PROFILE->getUSERNAME()`~/if`,</td>
                            </tr>
                            <tr>
                                <td colspan="3" height="10"></td>
                            </tr>
                            <tr>
                                <td valign="top" width="606">
                                    <table border="0" cellspacing="0" cellpadding="0" style="max-width:540px;font-family:Arial, Times New Roman, Times, serif; font-size:12px; color:#000000; text-align:left;" align="left">
                                        <tr>
                                            <td width="540">~$data.body`
                                                ~if $data.showDpp eq 1`
                                                <div><div style="padding-top: 5px;"><a href="~$mailerLinks['MY_DPP']`~$data.commonParamaters`?From_Mail=Y&EditWhatNew=FocusDpp&stype=~$data.stypeMatch`" target="_blank" style="text-decoration:none; color:#0f529d; display:inline-block;" title="Edit Desired Partner Profile">Edit Desired Partner Profile</a></div></div>
                                                ~/if`
                                            </td>
                                        </tr>
                                    </table>
                                    <table width="60" align="left" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Times New Roman, Times, serif; font-size:12px; color:#000000; text-align:left;">
                                        <!-- <tr>
                    ~if $data.COUNT gt 1`
                                                <td><a href="~$mailerLinks['SAVED_SEARCH']`~$data.commonParamaters`?From_Mail=Y&stype=~$data.stypeMatch`&mySaveSearchId=~$data.SEARCHID`" target="_blank" style="color:#14428e; text-decoration:none;">View All</a></td>
                    ~/if`
                                        </tr> -->
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="22"><img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/spacer.gif" width="6" height="1" vspace="0" hspace="0" align="left" /></td>
                </tr>
                <tr>
                    <td colspan="3" height="10"></td>
                </tr>
                ~if $data.COUNT gt 1 || $data.MEMBERSHIP.vd`
                <tr>
                    <td colspan="3" align="center">
                        ~if $data.MEMBERSHIP.vd`
                        ~include_partial("global/mailerVD",[vd=>$data.MEMBERSHIP.vd,commonParamaters=>$data.commonParamaters,stype=>$data.stypeMatch,profilechecksum=>$data.RECEIVER.PROFILECHECKSUM,tracking=>$data.MEMBERSHIP.tracking,mailerLinks=>$mailerLinks])`
                        ~elseif $data.APP.ANDROID.ICON eq 1`
                        ~include_partial("global/mailerAndroidApp",[mailerLinks=>$mailerLinks,data=>$data.APP])`
                        ~/if`
                    </td>
                </tr>
                ~/if`
                <tr>
                    <td colspan="3" height="10"></td>
                </tr>
                <tr>
                    <td colspan="3" height="10"></td>
                </tr>
                <tr>
                    <td colspan="3">
                        ~include_partial("global/mailerTupleContent",[users=>$data.USERS,logic=>$data.logic,commonParameters=>$data.commonParamaters,stypeMatch=>$data.stypeMatch,count=>$data.COUNT,mailerLinks=>$mailerLinks])`
                    </td>
                </tr>
                <tr>
                    <td colspan="3" height="10"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="text-align:left;">
                            <tr>
                                <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                        <tr>
                                            <td height="10" style="border-top:1px solid #eae9e9"><img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/spacer.gif" width="6" height="10" vspace="0" hspace="0" align="left" /></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            ~if $data.MEMBERSHIP.membership eq 0 || ( $data.MEMBERSHIP.membership eq 1 && $data.MEMBERSHIP.renew.RENEW eq 1)`
                            <tr>
                                <td style="padding-left:10px;">
                                    ~include_partial("global/mailerUpgrade",[vd=>$data.MEMBERSHIP.vd,membership=>$data.MEMBERSHIP.membership,renew=>$data.MEMBERSHIP.renew,commonParameters=>$data.commonParamaters,receiverProfilechecksum=>$data.RECEIVER.PROFILECHECKSUM,stypeMatch=>$data.stypeMatch,discount=>$data.MEMBERSHIP.tracking,mailerLinks=>$mailerLinks])`
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                        <tr>
                                            <td height="10" style="border-bottom:1px solid #eae9e9"><img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/spacer.gif" width="6" height="10" vspace="0" hspace="0" align="left" /></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            ~/if`
                        </table>
                    </td>
                    <td></td>
                </tr>
                ~if $data.DPP neq NULL`
                <tr>
                    <td></td>
                    <td>
                        <table border="0" cellpadding="0" cellspacing="0" align="left" style="font-family:Arial; font-size:12px; color:#000000;" width="100%">
                            <tr>
                                <td height="39" valign="bottom">
                                    Looking for more relevant matches? <a href="~$mailerLinks['MY_DPP']`~$data.commonParamaters`?From_Mail=Y&EditWhatNew=FocusDpp&stype=~$data.stypeMatch`" target="_blank" style="text-decoration:none; color:#0f529d; display:inline-block;">See/Edit your desired partner profile</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3" height="6"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        ~include_partial("global/mailerDpp",[DPP=>$data.DPP])`
                    </td>
                    <td></td>
                </tr>
                ~/if`
                <tr>
                    <td colspan="3" height="27"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        ~include_partial("global/exclusiveProposalMailSignature",[name=>$data.AGENT_NAME])`
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3" height="24"></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="3">
                        ~include_partial("global/mailerfooter",[logic=>$data.logic,mailerParameter=>"matchalertTrack=1",mailerLinks=>$mailerLinks])`
                    </td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td height="15"></td>
    </tr>
    <tr>
        <td align="center" style="padding-left:1%">
        </td>
    </tr>
</table>
<!-- <img src="~sfConfig::get('app_site_url')`/track.php?MAILER_ID=~$instanceID`" style="display:none !important;overflow:hidden;line-height:0"></img> --> <!-- NO INSTANCE ID -->
</body>
