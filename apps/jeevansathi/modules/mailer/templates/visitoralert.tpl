~include_partial("global/mailerheader")`
<body>
		<table align="center" style="font-size: 11px;">
        <tr align="center" style="font-size: 11px;">
            <td>Please add ~$mailerName` to your address book to ensure delivery of this mail into you inbox</td>
        </tr>
        <tr align="center" style="font-size: 11px;">
            <td>This Email contains links which let you to directly login to your account. So forward this Email only to people you can completely trust.</td>
        </tr>
     </table>
    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border:1px solid #dcdcdc; max-width:650px; text-align:left" align="center">
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
                                                <td width="540">You may wish to connect with ~if $data.COUNT eq 1` this member~else` these people~/if` who recently visited your profile.</td>
                                            </tr>
                                        </table>
                                        <table width="60" align="left" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Times New Roman, Times, serif; font-size:12px; color:#000000; text-align:left;">
                                            <tr>
                                                <td><a href="~$mailerLinks['PROFILE_VISITORS']`~$data.commonParamaters`?From_Mail=Y&stype=~$data.stypeMatch`" target="_blank" style="color:#14428e; text-decoration:none;">View All</a></td>
                                            </tr>
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
                        <td colspan="3">
                        	~include_partial("global/mailerTupleContent",[users=>$data.USERS,count=>$data.COUNT,logic=>$data.logic,stypeMatch=>$data.stypeMatch,commonParameters=>$data.commonParamaters,mailerLinks=>$mailerLinks])`
                       
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
                                        ~include_partial("global/mailerUpgrade",[vd=>$data.MEMBERSHIP.vd,membership=>$data.MEMBERSHIP.membership,discount=>$data.MEMBERSHIP.tracking,renew=>$data.MEMBERSHIP.renew,commonParameters=>$data.commonParamaters,receiverProfilechecksum=>$data.RECEIVER.PROFILECHECKSUM,stypeMatch=>$data.stypeMatch,mailerLinks=>$mailerLinks])`
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
                    <tr>
                        <td colspan="3" height="6"></td>
                    </tr>
                    <tr>
                        <td colspan="3" height="27"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            ~include_partial("global/mailerJsSignature")`
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3" height="24"></td>
                    </tr>
                    <tr>
			<td></td>
                        <td colspan="3"> 
                            ~include_partial("global/mailerfooter",[logic=>"",mailerParameter=>"",mailerLinks=>$mailerLinks])`
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
    <img src="~sfConfig::get('app_site_url')`/track.php?MAILER_ID=~$instanceID`" style="display:none !important;overflow:hidden;line-height:0"></img>
</body>
