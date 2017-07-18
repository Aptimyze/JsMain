<table align="left" border="0" cellspacing="0" cellpadding="0" ~if $index % 2 eq 1` style="max-width:320px; border-left:1px solid #eae9e9;" ~else` style="max-width:320px;" ~/if`>
<tr>
    <td></td>
    <td height="28" style="padding-left:5px;"><a href="~$mailerLinks['DETAILED_PROFILE_HOME']`~$commonParameters`?profilechecksum=~$user->getPROFILECHECKSUM()`&logic_used=~$logic`&stype=~$stypeMatch`~if $fromMatchAlertMailer` &fromMatchAlertMailer=~$fromMatchAlertMailer`~/if`" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#14428e; text-decoration:none;">~if $user->getNAME_OF_USER()` ~$user->getNAME_OF_USER()` ~else` ~$user->getUSERNAME()` ~/if`</a></td>
    <td height="28" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;text-decoration:none;">~if $user->getGUNA() neq ""`Guna Match ~$user->getGUNA()`/36 ~/if`</td>
</tr>
<tr>
    <td width="22">
        <img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/spacer.gif" width="20" height="1" vspace="0" hspace="0" align="left">
    </td>
    <td width="110" height="140" valign="top">
       
        <a href="~$mailerLinks['DETAILED_PROFILE_HOME']`~$commonParameters`?profilechecksum=~$user->getPROFILECHECKSUM()`&logic_used=~$logic`&stype=~$stypeMatch`~if $fromMatchAlertMailer` &fromMatchAlertMailer=~$fromMatchAlertMailer`~/if`" style="text-decoration:none; color:#0f529d;" target="_blank">
       
        <img src="~$user->getSearchPicUrl()`" align="left" width="100" height="133" hspace="0" vspace="0" border="0" style="border:1px solid #e6e6e6;" /></a>
    </td>
    <td width="188" valign="top">
        <table border="0" cellspacing="2" align="left" cellpadding="0" style="font-family:Arial, Verdana; font-size:12px; color:#000000;-webkit-text-size-adjust: none; text-align:left;">
            <tr>
                <td>~$user->getAGE()` yrs; ~$user->getHEIGHT()`</td>
            </tr>
            <tr>
                <td>~$user->getCASTE()`</td>
            </tr>
            <tr>
                <td>~$user->getMTONGUE()`</td>
            </tr>
            <tr>
                <td><a style="text-decoration:none; cursor:default; color:#000000;">~$user->getEDU_LEVEL_NEW()`</a></td>
            </tr>
            <tr>
                <td>~$user->getOCCUPATION()`</td>
            </tr>
            <tr>
                <td>~$user->getINCOME()`</td>
            </tr>
            <tr>
                <td>~$user->getCITY()`</td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td></td>
    <td colspan="2">
        <table border="0" width="130" align="left" cellspacing="0" cellpadding="0" style="font-family:Arial, Verdana; font-size:12px;">
            <tr>
                <td bgcolor="#ad160d" height="27" align="center" width="167"><a href="~$mailerLinks['DETAILED_PROFILE_HOME']`~$commonParameters`?profilechecksum=~$user->getPROFILECHECKSUM()`&logic_used=~$logic`&stype=~$stypeMatch`~if $fromMatchAlertMailer` &fromMatchAlertMailer=~$fromMatchAlertMailer`~/if`" target="_blank" style="text-decoration:none; color:#ffffff;"> <strong>View Profile</strong></a></td>
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
