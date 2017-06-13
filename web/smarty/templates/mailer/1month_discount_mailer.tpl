<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, user-scalable=no">
<title>jeevansathi.com</title>
</head>

<body>
<table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border:1px solid #dcdcdc; max-width:575px; text-align:left" align="center">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" height="52">
        <tr>
          <td width="440" style="padding-left:10px; padding-right:10px;" height="52">
          	<div style="width:100%"><a href='~$SITE_URL`' target="_blank"><img src="~$IMG_URL`/images/mailer/revampMailer/logo.gif" alt="Jeevansathi.com" align="left" border="0" vspace="0" hspace="0" style="max-width:204px; width:inherit;max-height:52px;"></a></div>
          </td>
           <td width="120" height="52" style="padding-right:10px;">
           		<table width="120" border="0" cellspacing="0" cellpadding="0" align="right">
                  <tr>
                  	<td width="24"><img src="~$IMG_URL`/images/mailer/revampMailer/iconTop.gif" align="left" border="0" vspace="0" hspace="0" width="24" height="23"></td>
                    <td align="left"><font face="Tahoma, Geneva, sans-serif" color="#555555" style="font-size:12px;"><var>{{TOLLNO}}</var></font></td>
                  </tr>
                </table>
			</td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td width="575"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="">
        <tr>
          <td colspan="3" height="16"></td>
        </tr>
        <tr>
          <td colspan="3" height="14"></td>
        </tr>
        <tr>
          <td width="22"><img src="~$IMG_URL`/images/mailer/revampMailer/spacer.gif" width="6" height="1" vspace="0" hspace="0" align="left" /></td>
          <td width="531"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Times New Roman, Times, serif; font-size:12px; color:#000000; text-align:left;">
              <tr>
                <td valign="top">Dear User, </td>
              </tr>
	      <br\>	
              <tr>
              	<td style="color:#000000;">We believe that a paid membership on Jeevansathi can significantly boost your search for a prospective match. To make it easier for you to try out, we are offering a special 1 month plan eRishta plan for you to try out at a special price of </td>
                <br>
                <tr>
                <td><br>Price : ~if $CURRENCYTYPE eq 'RS'` ~$CURRENCYTYPE` ~else` $ ~/if` ~$PRICE`</td>
                </tr>
                <tr>
                <td><br>Link : <a href='~$URL`' target="_blank" style="color:#0f529d;">~$URL`</a></td>
              </tr>
              <tr>
                <td valign="top"><br /><font color="#000000">We hope that a Jeevansathi paid membership can help make your search faster and more relevant.</font></td>
              </tr>
              <tr>
                <td valign="top"><br /><font color="#000000">Please note that this link will expire in 24 hours.</font></td>
              </tr>
              <tr>
              	<td valign="top"><br />Regards,</td>
              </tr>
              <tr>
                <td>Team Jeevansathi</td>
              </tr>
              <tr>
                <td valign="top" height="15"></td>
              </tr>
              <tr>
                <td valign="top" height="22"></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td colspan="3" height="27"></td>
        </tr>
      </table></td>
  </tr>
  ~$FOOTER`
</table>
</body>
</html>
