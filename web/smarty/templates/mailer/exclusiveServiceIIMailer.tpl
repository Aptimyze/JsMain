<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <title>jeevansathi.com
    </title>
  </head>
  <body>
    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" style="border:1px solid #dcdcdc; max-width:575px; text-align:left" align="center">
      <!--tr>
        <td>
          <table width="100%" border="0" cellspacing="0" cellpadding="0" height="52">
            <tr>
              <td width="440" style="padding-left:10px; padding-right:10px;" height="52">
                <div style="width:100%"><img src="images/logo.gif" alt="Jeevansathi.com" align="left" border="0" vspace="0" hspace="0" style="max-width:204px; width:inherit;max-height:52px;"></div>
              </td>
              <td width="120" height="52" style="padding-right:10px;">
                <table width="120" border="0" cellspacing="0" cellpadding="0" align="right">
                  <tr>
                    <td width="24"><img src="images/iconTop.gif" align="left" border="0" vspace="0" hspace="0" width="24" height="23">
                    </td>
                    <td align="left"><font face="Tahoma, Geneva, sans-serif" color="#555555" style="font-size:12px;">1800 419 6299</font></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr-->
      <tr>
        <td width="575">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="">
            <tr>
              <td colspan="3" height="16"></td>
            </tr>
            <tr>
              <td colspan="3" height="14"></td>
            </tr>
            <tr>
              <td width="22"><img src="images/spacer.gif" width="6" height="1" vspace="0" hspace="0" align="left" />
              </td>
              <td width="531">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Times New Roman, Times, serif; font-size:12px; color:#000000; text-align:left;">
                  <tr>
                    <td valign="top">Dear ~$SELF_NAME`,</td>
                  </tr>
                  <tr>
                    <td valign="top" height="12"></td>
                  </tr>
                  <tr>
                    <td style="color:#000000;">I have shortlisted the following profiles for you. Please go through them and suggest accordingly.
                    </td>
                  </tr>
                  <tr>
                    <td valign="top">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align:left; font-family:Tahoma; font-size:13px; color:#000000; text-size-adjust: none; -webkit-text-size-adjust: none; line-height:18px;">
                        <tr>
                          <td colspan="2" height="12"></td>
                        </tr>
                        ~foreach from=$USERNAMELIST key=k item=v name=viewProfileLinkLoop`
                        <tr> 
                          <td width="528" style="font-size:12px; color:#000000; line-height:normal;"><a style="font-size:12px; color:#14428e; font-family:Arial, Helvetica, sans-serif;text-decoration: none;" target="_blank" href="~$v`">~$k`</a>
                          </td>
                        </tr>
                        ~/foreach`
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
              <td width="22"><img src="images/spacer.gif" width="6" height="1" vspace="0" hspace="0" align="left" /></td>
            </tr>
            <tr>
              <td colspan="3" height="27"></td>
            </tr>
            <tr>
              <td></td>
              <td>
                <table style="font-family:Arial" border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tr>
                    <td>
                      <table style="font-family:Arial,Times New Roman,Times,serif;font-size:11px;line-height:17px; color:#000000; -webkit-text-size-adjust: none;" width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tbody>
                          <tr>
                            <td style="font-size:12px; padding-bottom:10px;" valign="top">Warm Regards,<br>
                              <b style="color:#c4161c;"></b>~$AGENT_NAME`<br>
                              <b style="color:#c4161c;"></b>~$AGENT_PHONE`
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
              <td></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td height="15"></td>
      </tr>
    </table>
  </body>
</html>
