~if $membership eq 0 && ($renew eq 0 || ($renew neq 0 && $renew["RENEW"] neq 1))`
<table border="0" align="left" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:12px; color:#000000; max-width:330px;">
		<tr>
        	<td height="24" colspan="2" width="320"><font face="Arial" color="#000000" style="line-height:20px;"><strong>Contact Who you want, When you want - Upgrade Now.</strong></font></td>
    		</tr>
    		<tr>
        		<td width="20" valign="top"><img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/bull1.gif" align="left" width="16" height="17" hspace="0" vspace="4" border="0" alt="1" /></td>
        		<td height="24">Instantly see Phone/Email of people you like</td>
    		</tr>
    		<tr>
        		<td valign="top"><img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/bull2.gif" align="left" width="16" height="17" hspace="0" vspace="3" border="0"  alt="2" /></td>
        		<td height="24">Initiate Email, Message, Chat with them</td>
    		</tr>
    		<tr>
        		<td valign="top"><img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/bull3.gif" align="left" width="16" height="17" hspace="0" vspace="3" border="0"  alt="3" /></td>
        		<td height="24">Get more Interests and faster Responses</td>
    		</tr>
	~elseif $renew neq 0 && $renew["RENEW"] eq 1`
<table border="0" align="left" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:11px; color:#000000; max-width:386px;">
		<tr>
			<td height="24" colspan="2"><font face="Arial" color="#000000" style="line-height:16px;"><strong>Your membership ~if $renew["EXPIRED"]` has expired~else` will expire~/if` on ~$renew["EXPIRY_DT"]["DAY"]` ~$renew["EXPIRY_DT"]["MONTH"]`! Renew it before ~if $renew["EXPIRED"]`~$renew["RENEW_DT"]["DAY"]` ~$renew["RENEW_DT"]["MONTH"]`~else`~$renew["EXPIRY_DT"]["DAY"]` ~$renew["EXPIRY_DT"]["MONTH"]`~/if` to:</strong></font></td>

                </tr>
                <tr>
                        <td width="23" valign="top"><img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/bull1.gif" align="left" width="16" height="17" hspace="0" vspace="4" border="0" alt="1" /></td>
                        <td height="24" style="font-size:12px">Get ~$renew["RENEW_DISCOUNT"]`% off on Plans and Additional Services</td>
                </tr>
                <tr>
                 	<td valign="top"><img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/bull2.gif" align="left" width="16" height="17" hspace="0" vspace="3" border="0"  alt="2" /></td>
                        <td height="24" style="font-size:12px">Continue to view contact details of all Acceptances</td>
                </tr>
                <tr>
                  	<td valign="top"><img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/bull3.gif" align="left" width="16" height="17" hspace="0" vspace="3" border="0"  alt="3" /></td>

                        <td height="24" style="font-size:12px">Continue to view Phone/Email IDs already viewed</td>
                </tr>
	~/if`
</table>
<table width="198" border="0" cellspacing="0" cellpadding="0" align="left" style=" margin:0px">
    <tr>
        <td height="25"></td>
    </tr>
    <tr>
	~if $membership eq 0 && ($renew neq 0 && $renew["RENEW"] eq 1  && $renew["EXPIRED"] eq 1)`
		<td height="27" align="center" bgcolor="#003a7e" style="border:1px solid #003a7e; width:250px; display:inline-block;"><a href="~$mailerLinks['MEMBERSHIP_COMPARISON']`~$commonParameters`?from_source=~$discount['renew']`&stype=~$stypeMatch`&profilechecksum=~$receiverProfilechecksum`" target="_blank" style="font-family:Arial; font-size:13px; color:#ffffff; text-decoration:none; line-height:25px;"><strong>Renew at ~$renew["RENEW_DISCOUNT"]`% OFF till ~$renew.EXPIRY_DT.DAY` ~$renew.EXPIRY_DT.MONTH`</strong></a></td>
	~elseif $membership eq 1 && ($renew neq 0 && $renew["EXPIRED"] eq 0)`
            <td height="27" align="center" bgcolor="#003a7e" style="border:1px solid #003a7e; width:250px; display:inline-block;"><a href="~$mailerLinks['MEMBERSHIP_COMPARISON']`~$commonParameters`?from_source=~$discount['renewPercent']`&stype=~$stypeMatch`&profilechecksum=~$receiverProfilechecksum`" target="_blank" style="font-family:Arial; font-size:13px; color:#ffffff; text-decoration:none; line-height:25px;"><strong>Renew at ~$renew["RENEW_DISCOUNT"]`% OFF till ~$renew.EXPIRY_DT.DAY` ~$renew.EXPIRY_DT.MONTH`</strong></a></td>
        ~elseif $vd neq 0`
            <td height="27" align="center" bgcolor="#003a7e" style="border:1px solid #003a7e; width:250px; display:inline-block;"><a href="~$mailerLinks['MEMBERSHIP_COMPARISON']`~$commonParameters`?from_source=~$discount['vdPercent']`~$vd['DISCOUNT']`&stype=~$stypeMatch`&profilechecksum=~$receiverProfilechecksum`" target="_blank" style="font-family:Arial; font-size:13px; color:#ffffff; text-decoration:none; line-height:25px;"><strong>Get ~$vd["VD_DISCOUNT_TEXT"]` ~$vd["DISCOUNT"]`% Off till ~$vd.DATE.DAY` ~$vd.DATE.MONTH`</strong></a></td>
    ~elseif $membership eq 0`
                <td height="27" align="center" bgcolor="#003a7e" style="border:1px solid #003a7e;"><a href="~$mailerLinks['MEMBERSHIP_COMPARISON']`~$commonParameters`?from_source=~$discount['upgrade']`&stype=~$stypeMatch`&profilechecksum=~$receiverProfilechecksum`" target="_blank" style="font-family:Arial; font-size:13px; color:#ffffff; text-decoration:none; line-height:25px;"><strong>Upgrade Membership</strong></a></td>
	~/if`
    </tr>
    <tr>
        <td>
            <table width="198" border="0" cellspacing="0" cellpadding="0" align="left">
                <tr>
                    <td height="29" width="24"><img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/lockIC.gif"" width="20" height="24" vspace="0" hspace="0" align="right" /></td>
                    <td><font face="Arial" color="#575656" style="font-size:10px;"><em>Easy and Secure Payment Options</em></font></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
