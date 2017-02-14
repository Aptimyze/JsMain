~include_partial("global/mailerheader")`
<body>
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
                                    <td valign="top">Dear ~if $data.RECEIVER.PROFILE->getNAME()`~$data.RECEIVER.PROFILE->getNAME()`~else`~$data.RECEIVER.PROFILE->getUSERNAME()`~/if`,</td>
                                </tr>
                                <tr>
                                    <td colspan="3" height="10"></td>
                                </tr>
                                <tr>
                                    <td valign="top" width="606">
                                        <table border="0" cellspacing="0" cellpadding="0" style="max-width:540px;font-family:Arial, Times New Roman, Times, serif; font-size:12px; color:#000000; text-align:left;" align="left">
                                            <tr>
                                                <td width="540">~$data.body`
                                                </td>
                                            </tr>
                                        </table>
                                        <table width="60" align="left" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Times New Roman, Times, serif; font-size:12px; color:#000000; text-align:left;">
                                            <tr>
						~if $data.COUNT gt 1`
                                                	<td><a href="~$mailerLinks['MATCH_ALERT']`~$data.commonParamaters`?From_Mail=Y&stype=~$data.stypeMatch`" target="_blank" style="color:#14428e; text-decoration:none;">View All</a></td>
						~/if`
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="22"><img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/spacer.gif" width="6" height="1" vspace="0" hspace="0" align="left" /></td>
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
</body>
