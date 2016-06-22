<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Jeevansathi.com</title>
</head>

<body>
<table style="max-width:600px; min-width:240px; border:1px solid #dcdcdc;"  border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
<td colspan="4" style="font-size: 11px !important; font-family: Arial; color: black; padding-top: 10px;">
~$PREHEADER`
</td>
</tr>
  <tr>
    <td style="border-bottom:1px solid #ededed"><table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
          <td width="373" height="52" style="padding-left:10px;"><div><img border="0" align="left" vspace="0" hspace="0" style="max-width:204px;" alt="Jeevansathi.com" src="~$IMG_URL`/images/jspc/commonimg/logo1.png"></div></td>
          <td width="189" valign="middle" style="padding-right:10px;"><table cellspacing="0" cellpadding="0" border="0" align="right" width="189">
              <tr>
                <td align="right" valign="middle" height="50" style="vertical-align:middle;"><a style="font-size:12px; color:#14428e; font-family:Arial, Helvetica, sans-serif;text-decoration: none;" target="_blank" href="(LINK)COMPLETE_PROFILE:profileid=~$profileid`(/LINK)">My Profile</a> | <a style="font-size:12px; color:#14428e; font-family:Arial, Helvetica, sans-serif;text-decoration: none;" target="_blank" href="(LINK)SUGGESTED_MATCHES:profileid=~$profileid`(/LINK)">My Matches</a></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
  <td height="10"></td>
  </tr>
  <tr>
  <td align="left">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="10%"><img src="~$IMG_URL`/images/mailer/photoRequest/~$photoGender`s-photo1.jpg" width="83%" style="border:3px #c4161c solid; margin:0px; text-align:left" />
</td>
   <td width="10%"><img src="~$IMG_URL`/images/mailer/photoRequest/~$photoGender`s-photo2.jpg" width="83%" style="border:3px #c4161c solid; margin:0px; text-align:left" /></td>
   <td width="10%"><img src="~$IMG_URL`/images/mailer/photoRequest/~$photoGender`s-photo3.jpg" width="83%" style="border:3px #c4161c solid; margin:0px; text-align:left" /></td>
   <td width="10%"><img src="~$IMG_URL`/images/mailer/photoRequest/~$photoGender`s-photo4.jpg" width="83%" style="border:3px #c4161c solid; margin:0px; text-align:left" /></td>
    <td width="10%"><img src="~$IMG_URL`/images/mailer/photoRequest/~$photoGender`s-photo5.jpg" width="83%" style="border:3px #c4161c solid; margin:0px; text-align:left" /></td>
    <td width="10%"><img src="~$IMG_URL`/images/mailer/photoRequest/~$photoGender`s-photo2.jpg" width="83%" style="border:3px #c4161c solid; margin:0px; text-align:left" /></td>
    <td width="10%"><img src="~$IMG_URL`/images/mailer/photoRequest/~$photoGender`s-photo3.jpg" width="83%" style="border:3px #c4161c solid; margin:0px; text-align:left" /></td>
    <td width="10%"><img src="~$IMG_URL`/images/mailer/photoRequest/~$photoGender`s-photo4.jpg" width="83%" style="border:3px #c4161c solid; margin:0px; text-align:left" /></td>
    <td width="10%"><img src="~$IMG_URL`/images/mailer/photoRequest/~$photoGender`s-photo5.jpg" width="83%" style="border:3px #c4161c solid; margin:0px; text-align:left" /></td>
    <td width="10%"><img src="~$IMG_URL`/images/mailer/photoRequest/~$photoGender`s-photo3.jpg" width="83%" style="border:3px #c4161c solid; margin:0px; text-align:left" /></td>
    
 

 
  </tr>
</table>

  
  </td>
  </tr>
  <tr>
  <td height="10"></td>
  </tr>
  <tr>
  <td>
  <table style="max-width:600px; min-width:240px; font-family:Arial, Helvetica, sans-serif; font-size:12px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20"></td>
    <td valign="middle" height="35">
   Hi <var>{{NAME_PROFILE:profileid=~$profileid`}}</var>,
</td>
    <td width="20" height="25"></td>
  </tr>
  <tr>
    <td width="20"></td>
   <td>
<var>{{USERNAME:profileid=~$PHOTO_REQUESTED_BY_PROFILEID`}}</var> has viewed your profile details and would also like to see your photo to take things forward.</td>
    <td width="20"></td>
  </tr>
</table>

  </td>	
  </tr>
 
  <tr>
  <td width="600" valign="top" align="left">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><td height="10"></td></tr>
    <tr>
    <td></td>
    <td height="25"><a style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#14428e; text-decoration:none;" target="_blank" href="(LINK)DETAILED_PROFILE_HOME:profileid=~$profileid`,receiver_id=~$otherProfile`(/LINK)"><var>{{USERNAME:profileid=~$otherProfile`}}</var></a> <var>{{PAIDSTATUS:profileid=~$otherProfile`}}</var></td>
    </tr>
  ~if $requested_tuple`
  
  ~$requested_tuple`
  ~else`
  ~$profile_data`
  ~/if`
  
  
  
  
  <tr>
  ~if $TOTAL_REQUEST`
   <tr>
  
    	<td >
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-bottom:1px solid #eae9e9">
  <tr>
    <td height="40" width="20"></td>
    <td style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><a href="(LINK)PHOTO_REQUEST_PAGE:profileid=~$profileid`(/LINK)" style="color:#0f529d; text-decoration:underline">~$TOTAL_REQUEST` more</a> member~if $TOTAL_REQUEST neq 1`s~/if` ~if $TOTAL_REQUEST eq 1`has~else`have~/if` requested for your photo. <a href="(LINK)PHOTO_REQUEST_PAGE:profileid=~$profileid`(/LINK)"  style="color:#0f529d; text-decoration:underline">View All</a> Photo requests. </td>
  </tr>
</table>

        	</td>
    
  </tr>
 ~/if` 
  <tr>
  <td height="10"></td>
  </tr>
  <tr>
    <td><table cellspacing="0" cellpadding="0" border="0" style="max-width:330px;">
                      <tr>
                       <td width="22"></td>
                        <td width="166">
                        	<table cellspacing="0"  cellpadding="0" border="0" align="left" style="font-family:Arial, Verdana; max-width:150px; font-size:14px;">
                  <tr>
                 
                    <td bgcolor="#ad160d" align="center" width="150"  height="27"><a style="text-decoration:none; font-size:12px; font-family:Arial, Helvetica, sans-serif;   color:#ffffff; width:100%; " target="_blank" href="(LINK)UPLOAD_PHOTO:profileid=~$profileid`(/LINK)"> <strong>Add your Photo Now</strong></a></td>
                  </tr>
                </table>
                        </td>
                        <td width="1"></td>
                         
                      </tr>
                    </table></td>
  </tr>
 
  </td>
  </tr>

  <tr><td width="600">
  <table style=" font-family:Arial, Helvetica, sans-serif; font-size:12px" border="0" cellspacing="0" cellpadding="0" width="100%">
  <td style="color:#4a4a4a;" >
</td>
    
  </tr>
  
  <tr>
   
    	<td  width="600"  >
        

</td>
    
  </tr>
   <tr>
   
    	<td colspan="2" width="600"><table style="max-width:600px; min-width:240px" border="0" cellspacing="0" cellpadding="0">
 
</table>
              </td>
    
  </tr>
    <tr>
  
    	<td height="10"></td>
    
  </tr>
   
  
  
  <tr>
 
    	<td valign="top">
        
        <table style="max-width:600px;"border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20" height="20"></td>
    <td width="545" colspan="2">Control visibility of your photograph through our <a href="(LINK)UPLOAD_PHOTO:profileid=~$profileid`(/LINK)" style="color:#0f529d; text-decoration:underline;">Privacy Settings</a><br /><br />Benefits of Adding a Photo to your profile<br /><br /></td>
  </tr>
  <tr>
    <td width="20" height="20"></td>
    <td width="25"><img src="~$IMG_URL`/images/mailer/photoRequest/arrow-point.jpg" /></td>
    <td width="545">Increases response by 8 times</td>
  </tr>
  <tr>
    <td width="20" height="20"></td>
    <td width="25"><img src="~$IMG_URL`/images/mailer/photoRequest/arrow-point.jpg" /></td>
    <td width="545">Adds credibility to your profile</td>
  </tr>
  
</table>

        

        
        </td>
    
  </tr>
  <tr>
   
    	<td height="20"></td>
   
  </tr>
  
  <tr>
   
    	<td>
        <table style="max-width:600px;" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td></td>
        <td height="30" style="font-size:14px; text-transform:uppercase"><strong>How to Upload ?</strong></td>
        <td></td>
        </tr>
  <tr>
    <td width="20"></td>
    <td width="560" >
    
    <table width="270" border="0" cellspacing="0" cellpadding="0" align="left">
  <tr>
    <td align="center" width="140"><img src="~$IMG_URL`/images/mailer/photoRequest/pc-icon.jpg" /></td>
    <td align="center" width="140"><img src="~$IMG_URL`/images/mailer/photoRequest/fb-download-icon.jpg"/></td>
    
  </tr>
  <tr>
    <td height="10"></td>
    <td> </td>
    
  </tr>
  <tr>
    <td align="center">Upload<br />
from<br />
Computer </td>
    <td align="center">Import<br />
from<br />
Facebook </td>
   
  </tr>
  <tr>
    <td height="10"></td>
    <td> </td>
    
  </tr>
</table>
<table width="270" border="0" cellspacing="0" cellpadding="0" align="left">
  <tr>
    <td align="center" width="140"><img src="~$IMG_URL`/images/mailer/photoRequest/mail-icon.jpg"/></td>
    <td align="center" width="140"><img src="~$IMG_URL`/images/mailer/photoRequest/couriar-icon.jpg"/></td>
    
  </tr>
  <tr>
    <td height="10"></td>
    <td> </td>
    
  </tr>
  <tr>
    <td align="center">Mail us<br />
to<br />
<a href="mailto:photos@jeevansathi.com" style="color:#0f529d; text-decoration:underline">photos@jeevansathi.com</a></td>
    <td align="center">Courier your photos<br />
to any of<br />	
our 60+ offices</td>
   
  </tr>
  <tr>
    <td height="10"></td>
    <td> </td>
    
  </tr>
</table></td>
    <td width="20"></td>
  </tr>
</table>


</td>
   
  </tr>
  
  
  <tr>
   
    	<td height="25"></td>
   
  </tr>
  
  
  
  <tr>
    
    	<td valign="top" height="30" >
        <table style="max-width:600px; min-width:240px"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20"></td>
    <td style=" font-family: Arial, Helvetica, sans-serif; font-size:12px;">Wish you success in your search.</td>
  </tr>
</table>

        </td>
   
  </tr>
  
  <tr>
   
    	<td valign="top"><table style="max-width:600px; min-width:240px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20"></td>
    <td style=" font-family: Arial, Helvetica, sans-serif; font-size:12px;">Warm Regards,<br>
                          <b style="color:#c4161c;">Jeevansathi</b><span style="font-size:1px;"> </span><b color="#00000">.com Team</b></td>
  </tr>
</table>
</td>
    
  </tr>
  
  <tr>
    
    	<td height="40"></td>
   
  </tr>
  
  <tr>
 
    	<td align="center" valign="top" height="20"><font face="Tahoma, Geneva, sans-serif" style="font-size:12px;">Got any Questions?</font></td>
  </tr>
    <tr>
    
    	<td height="40">
        <table cellspacing="0" cellpadding="0" border="0" align="left" width="241" style="font-family:Tahoma, Geneva, sans-serif; font-size:11px; color:#555555; text-align:left">
              <tr>
              <td width="15"></td>
                <td width="23"><img align="left" width="23" vspace="0" hspace="0" height="20" src="~$IMG_URL`/images/mailer/photoRequest/icon1.gif"></td>
                <td>1800 419 6299 (Toll Free) or 0120-4393500</td>
              </tr>
              <tr>
                <td height="8" colspan="2"></td>
              </tr>
            </table>
            <table cellspacing="0" cellpadding="0" border="0" align="left" width="158" style="font-family:Tahoma, Geneva, sans-serif; font-size:11px; color:#555555; text-align:left">
              <tr>
              <td width="15"></td>
                <td width="28" height="20"><img align="left" width="28" vspace="0" hspace="0" height="14" src="~$IMG_URL`/images/mailer/photoRequest/msg_IC.gif"></td>
                <td><a style="color:#0f529d; text-decoration:underline;" target="_blank" href="mailto:help@jeevansathi.com">help@jeevansathi.com </a></td>
              </tr>
              <tr>
                <td height="8" colspan="2"></td>
              </tr>
            </table>
            <table cellspacing="0" cellpadding="0" border="0" align="left" width="165" style="font-family:Tahoma, Geneva, sans-serif; font-size:11px; color:#555555; text-align:left">
              <tr>
              <td width="15"></td>
                <td width="18" height="20"><img align="left" width="18" vspace="0" hspace="0" height="18" src="~$IMG_URL`/images/mailer/photoRequest/visitIC.gif"></td>
                <td>Visit any of our <a style="color:#0f529d; text-decoration:underline;" target="_blank" href="(LINK)ALLCENTRESLOCATIONS:profileid=~$profileid`(/LINK)">60+centres</a></td>
              </tr>
              <tr>
                <td height="8" colspan="2"></td>
              </tr>
            </table>
        </td>
    
  </tr>
  
   <tr>
    
    	<td align="center" valign="top" height="20"><font face="Tahoma, Geneva, sans-serif" style="font-size:12px;">----- More Value, Less Money -----</font>
        
        </td>
    
  </tr>
  <tr>
  <td><table cellspacing="0" cellpadding="0" border="0" bgcolor="#f0f0f0" align="center"  style="font-family:Tahoma, Geneva, sans-serif; font-size:11px; color:#555555; text-align:center; max-width:570px; min-width:240px;">
                  <tr>
                    <td width="148" height="36" style="border:1px dashed #c4c3c3; border-top:0px;">LOWEST<br>Price per Contact </td>
                    <td width="148" style="border:1px dashed #c4c3c3; border-left:0px; border-top:0px;">MAXIMUM<br>Benefits per Month</td>
                     <td width="148" height="36" style="border:1px dashed #c4c3c3; border-top:0px;">BIGGEST<br>Savings per Plan </td>
                    <td width="148" style="border:1px dashed #c4c3c3; border-left:0px; border-top:0px;">ON CALL<br>Customer Service</td>
                  </tr>
                </table></td>
  </tr>
   <tr>
  <td align="center" height="31" style="font-size:11px;">You have received this mail because your e-mail ID is registered with Jeevansathi.com. This is a system-generated e-mail, please don't reply to this message. The profiles sent in this mail have been posted by registered members of Jeevansathi.com. Jeevansathi.com has taken all reasonable steps to ensure that the information in this mailer is authentic. Users are advised to research bonafides of these profiles independently. To stop receiving these mails <a style="text-decoration:none;" target="_blank" href="(LINK)UNSUBSCRIBE:profileid=~$profileid`(/LINK)"><font color="#7d7b7b" face="Tahoma, Geneva, sans-serif" style="font-size:11px;"><u>Unsubscribe</u>.</font></a></td>
  </tr>
  
</table>

  </td></tr>
  <tr>
  <td align="center" width="600px">
  <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td> 
</td>
  </tr>
</table>

 
  </td>
  </tr>
  
</table>

</body>
</html>
