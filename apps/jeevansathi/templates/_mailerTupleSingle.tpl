<table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="font-family:Arial, Verdana; font-size:12px; color:#000000;-webkit-text-size-adjust: none; text-align:left;">
    <tr>
        <td></td>
        <td height="28" style="padding-left:5px;" colspan="2"><a href="~$mailerLinks['DETAILED_PROFILE_HOME']`~$commonParameters`?profilechecksum=~$user->getPROFILECHECKSUM()`&logic_used=~$logic`&stype=~$stypeMatch`~if $fromMatchAlertMailer` &fromMatchAlertMailer=~$fromMatchAlertMailer`~/if`" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#14428e; text-decoration:none;">~if $user->getNAME_OF_USER()` ~$user->getNAME_OF_USER()` ~else` ~$user->getUSERNAME()` ~/if` </a>
            ~if $user->getSUBSCRIPTION_TEXT() neq ""` <span height="28" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;text-decoration:none;"> | </span>~/if`<span style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#c4161c"> ~if $user->getSUBSCRIPTION_TEXT() neq ""` ~$user->getSUBSCRIPTION_TEXT()` ~/if`</span>
            <span height="28" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;text-decoration:none;">~if $user->getGUNA() neq ""` | Guna Match ~$user->getGUNA()`/36 ~/if`</span></td>
    </tr>
    <tr>
        <td width="22"><img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/spacer.gif" width="20" height="1" vspace="0" hspace="0" align="left"></td>
        <td style="width:100px;" height="140" valign="top">
             <a href="~$mailerLinks['DETAILED_PROFILE_HOME']`~$commonParameters`?profilechecksum=~$user->getPROFILECHECKSUM()`&logic_used=~$logic`&stype=~$stypeMatch`~if $fromMatchAlertMailer` &fromMatchAlertMailer=~$fromMatchAlertMailer`~/if`" style="text-decoration:none; color:#0f529d;" target="_blank">
             <img src="~$user->getSearchPicUrl()`" align="left" width="100" height="133" hspace="0" vspace="0" border="0" style="border:1px solid #e6e6e6;" /></a>
        </td>
        <td valign="top" style="padding-left:10px;">
            <table border="0" cellspacing="0" align="left" cellpadding="0" style="font-family:Arial, Verdana; font-size:12px; color:#000000;-webkit-text-size-adjust: none; text-align:left;">
                <tr>
                    <td style="padding-bottom:2px;">~$user->getAGE()` yrs; ~$user->getHEIGHT()`</td>
                </tr>
                <tr>
                    <td style="padding-bottom:2px;">~$user->getCASTE()`</td>
                </tr>
                <tr>
                    <td style="padding-bottom:2px;">~$user->getMTONGUE()`</td>
                </tr>
                <tr>
                    <td style="padding-bottom:2px;"><a style="text-decoration:none; cursor:default; color:#000000;">~$user->getEDU_LEVEL_NEW()`</a></td>
                </tr>
                <tr>
                    <td style="padding-bottom:2px;">~$user->getOCCUPATION()`</td>
                </tr>
                <tr>
                    <td style="padding-bottom:2px;">~$user->getINCOME()`</td>
                </tr>
                <tr>
                    <td>~$user->getCITY()`</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="2" height="13"></td>
    </tr>
    ~if $user->getYOURINFO() neq ''`
    <tr>
        <td></td>
        <td colspan="2" style="padding-right: 5px;">
            About ~$user->getUSERNAME()`: ~$user->getYOURINFO()`... <a href="~$mailerLinks['DETAILED_PROFILE_HOME']`~$commonParameters`?profilechecksum=~$user->getPROFILECHECKSUM()`&logic_used=~$logic`&stype=~$stypeMatch`~if $fromMatchAlertMailer` &fromMatchAlertMailer=~$fromMatchAlertMailer`~/if`" target="_blank" style="font-size:12px; color:#14428e; font-family:Arial, Helvetica, sans-serif; text-decoration:none;">Read more</a> 
	</td>
    </tr>
    ~/if`
    <tr>
        <td></td>
        <td colspan="2" height="15"></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="2">
            <table border="0" width="130" align="left" cellspacing="0" cellpadding="0" style="font-family:Arial, Verdana; font-size:12px;">
                <tr>
                    <td bgcolor="#ad160d" height="27" align="center" width="167"><a href="~$mailerLinks['EXPRESS_INTEREST']`~$commonParameters`?profilechecksum=~$user->getPROFILECHECKSUM()`&logic_used=~$logic`&stype=~$stypeMatch`" target="_blank" style="text-decoration:none; color:#ffffff;"> <strong>View Profile</strong></a></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="2" height="20"></td>
    </tr>
</table>
