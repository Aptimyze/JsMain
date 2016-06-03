<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Jeevansathi.com</title>
</head>

<body>
<table border="0" cellspacing="0" cellpadding="0" align="center" style="max-width:600px; min-width:320px; border:1px solid #dcdcdc; -webkit-text-size-adjust: none;" ~if $GENDER eq 'M'`bgcolor="#0c4e78"~elseif $GENDER eq 'F'`bgcolor="#58011c"~/if`>
  <tr bgcolor="#ffffff">
    <td width="19"></td>
    <td><table border="0" cellpadding="0" cellspacing="0" align="left">
          <tr>
            <td height="49" width="191"><img src="~$IMG_URL`/images/mailer/header_footer/jsLogo2.gif" alt="Jeevansathi.com" align="left" border="0" height="49" vspace="0" width="191" hspace="0"></td>
            </tr>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" align="right">
          <tr>
            <td width="161" height="49"><img src="~$IMG_URL`/images/mailer/header_footer/ventureTxt.gif" width="161" height="24" vspace="0" hspace="0" align="right" /></td>
          </tr>
      </table>
    </td>
    <td width="18"></td>
  </tr>
  <tr>
    <td colspan="3" ~if $GENDER eq 'M'`background="~$IMG_URL`/images/mailer/photoUpload/img1.jpg"~elseif $GENDER eq 'F'`background="~$IMG_URL`/images/mailer/photoUpload/img1_f.jpg"~/if` height="130" valign="top" align="center">
    	<table width="90%" border="0" cellspacing="0" cellpadding="0" style="-webkit-text-size-adjust: none; font-family:Arial; font-size:18px; color:#ffffff;">
          <tr>
            <td colspan="3" height="36"></td>
          </tr>
          <tr>
            <td width="85"><img ~if $GENDER eq 'M'`src="~$IMG_URL`/images/mailer/photoUpload/img2.jpg"~elseif $GENDER eq 'F'`src="~$IMG_URL`/images/mailer/photoUpload/img2_f.jpg"~/if` width="85" height="67" vspace="0" hspace="0" align="right" /></td>
            <td>Congratulations!&nbsp;
		~if $PHOTOS_SCREENED eq $PHOTOS_UPLOADED && $PHOTOS_SCREENED eq 1`
			Your photo has been
		~else`
			~$PHOTOS_SCREENED` of ~$PHOTOS_UPLOADED` photos have been
		~/if`
		<br /><font face="Tahoma" style="font-size:22px;" color="#fad672"><strong>SUCCESSFULLY UPLOADED</strong></font></td>
          </tr>
          <tr>
            <td colspan="2"><font face="Georgia" style="font-size:13px;" color="#ffe9f0"><em>With this photo on your profile you are all set to make your impression!</em></font></td>
          </tr>
        </table>
	</td>
  </tr>
  <tr>
    <td></td>
    <td>
	~$self_tuple`
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
                      <td height="13"></td>
                    </tr>
            <tr>
              <td height="93" ~if $GENDER eq 'M'`style="padding-left:20px; border-bottom:1px solid #1d5e8e;-webkit-text-size-adjust: none;"~elseif $GENDER eq 'F'`style="padding-left:20px; border-bottom:1px solid #8e1138;-webkit-text-size-adjust: none;"~/if` valign="top"><font face="Arial" style="font-size:13px;" color="#FFFFFF">Dear <var>{{NAME_PROFILE:profileid=~$profileid`}}</var>,<br /><br />
A lot of members are looking for their perfect life partner on Jeevansathi<span style="font-size:1px;"> </span>.com!<br />
Now with a photo on your profile you can take things forward by <font color="#fad672">EXPRESSING INTEREST</font> in <br />members you like.</font>
</td>
            </tr>
		~if $SEARCH_COUNT`
            <tr>
              <td height="43" ~if $GENDER eq 'M'`style="border-bottom:1px solid #1d5e8e;"~elseif $GENDER eq 'F'`style="border-bottom:1px solid #8e1138;"~/if`>
              		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:13px; color:#ffffff;-webkit-text-size-adjust: none;">
                      <tr>
                        <td width="5.5%"><img ~if $GENDER eq 'M'`src="~$IMG_URL`/images/mailer/photoUpload/img3.gif"~elseif $GENDER eq 'F'`src="~$IMG_URL`/images/mailer/photoUpload/img3_f.gif"~/if` width="33" height="42" vspace="0" hspace="0" align="left" /></td>
                        <td ~if $GENDER eq 'M'`background="~$IMG_URL`/images/mailer/photoUpload/img5.gif"~elseif $GENDER eq 'F'`background="~$IMG_URL`/images/mailer/photoUpload/img5_f.gif"~/if` bgcolor="#1769A1"><strong>~$SEARCH_COUNT` people are looking for profile just like yours! Here are a few of them:</strong></td>
                        <td width="26"><img ~if $GENDER eq 'M'`src="~$IMG_URL`/images/mailer/photoUpload/img4.gif"~elseif $GENDER eq 'F'`src="~$IMG_URL`/images/mailer/photoUpload/img4_f.gif"~/if` width="26" height="42" vspace="0" hspace="0" align="right" /></td>
                      </tr>
                    </table>

              </td>
            </tr>
		~/if`
            <tr>
              <td height="14"></td>
            </tr>
	</table>
		~$dpp_matches`
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
              <td height="10"></td>
            </tr>
            <tr>
              <td><table style="font-family:Arial" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                      <td colspan="2"><table style="font-family:Arial,Times New Roman,Times,serif;font-size:11px;line-height:17px; color:#ffffff; margin-left:10px;-webkit-text-size-adjust: none;" align="left" border="0" cellpadding="0" cellspacing="0">
                          <tbody>
                            <tr>
                              <td style="font-size:12px" valign="top">Warm Regards,<br>
                                <b>Jeevansathi<span style="font-size:1px;"> </span>.com Team</b></td>
                              <td style="font-size:12px" valign="top" width="10">&nbsp;</td>
                            </tr>
                          </tbody>
                        </table>
                        <table style="font-family:Arial,Times New Roman,Times,serif;font-size:11px;line-height:17px; color:#ffffff;" border="0" cellpadding="0" cellspacing="0" align="right">
                            <tr>
                              <td valign="top"><img ~if $GENDER eq 'M'`src="~$IMG_URL`/images/mailer/header_footer/icon1.gif"~elseif $GENDER eq 'F'`src="~$IMG_URL`/images/mailer/header_footer/icon3.gif"~/if` align="absmiddle" height="24" width="24"> </td>
                              <td style="font-family:Arial;font-size:12px;-webkit-text-size-adjust: none;" valign="top" width="206" align="right"><span style="font-size:13px"><b>Call us at:</b></span><var>{{TOLLNO}}</var> (Toll free),<br /><var>{{NOIDALANDL}}</var> </span></td>
                            </tr>
                        </table></td>
                    </tr>
                </table></td>
            </tr>
        </table></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="3" height="35"></td>
  </tr>
  <tr>
    <td colspan="3" height="22" valign="top" align="center"><font face="Arial" style="font-size:11px;" ~if $GENDER eq 'M'`color="#2795df"~elseif $GENDER eq 'F'`color="#cd1556"~/if`>If you do not wish to receive such service mailers further, click here to <a href="(LINK)UNSUBSCRIBE:profileid=~$profileid`(/LINK)" target="_blank" ~if $GENDER eq 'M'`style="text-decoration:underline; color:#2795df;"~elseif $GENDER eq 'F'`style="text-decoration:underline; color:#cd1556;"~/if`>UNSUBSCRIBE</a>.</font></td>
  </tr>
</table>
</body>
</html>
