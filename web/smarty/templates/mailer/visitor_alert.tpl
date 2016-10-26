<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>jeevansathi.com</title>
</head>

<body>
<table border="0" cellspacing="0" cellpadding="0" align="center" style="max-width:600px; min-width:320px; -webkit-text-size-adjust: none; font-family:Arial;" bgcolor="#920606">
  <tr>
    <td colspan="3" height="12"></td>
  </tr>
  <tr>
    <td width="10"><img src="~$IMG_URL`/images/mailer/visitorAlert/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
    <td width="580" bgcolor="#ffffff"><table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td height="10" valign="top"  style="font-family:Arial; font-size:11px; color:#000000; padding-left:13px;"><p>
          ~if $profileState eq "C1" or $profileState eq "C2" or $profileState eq "C3"`
          	See ~if $GENDER eq "M"` her ~else` his ~/if` phone/email for FREE by taking Trial Offer
          ~elseif $profileState eq "D1"`
          	Express Interest to contact ~if $GENDER eq "M"` her ~else` him ~/if` for FREE 
          ~elseif $profileState eq "D2" or $profileState eq "D3" or $profileState eq "D4" or $profileState eq "E4"`
          	See ~if $GENDER eq "M"` her ~else` his ~/if` phone/email if ~if $GENDER eq "M"` she ~else` he ~/if` "Accepts". 
          ~elseif $profileState eq "E1" or $profileState eq "E2" or $profileState eq "E3" or $profileState eq "E5" or $profileState eq "F" or $profileState eq "G" or $profileState eq "P"`
          	Express Interest to contact ~if $GENDER eq "M"` her ~else` him ~/if`
          ~/if`
          </p></td>
        </tr>
        <tr>
          <td height="54" valign="top"><table border="0" cellpadding="0" cellspacing="0" align="left" style="margin-left:10px;">
              <tr>
                <td height="49" width="203"><img src="~$IMG_URL`/images/mailer/visitorAlert/logo.gif" alt="Jeevansathi.com" align="left" border="0" height="45" vspace="0" width="204" hspace="0"></td>
              </tr>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" align="right" style="font-family:Arial; font-size:12px; color:#474646; margin-top:7px;">
              <tr>
                <td width="225" height="49" align="right" style="padding-right:10px;"><img src="~$IMG_URL`/images/mailer/visitorAlert/iconTop.gif" width="21" height="22" align="absmiddle"> <span style="font-size:11px;"><strong>Call us at:</strong></span><var>{{TOLLNO}}</var> (Toll free),<br>
                  <var>{{NOIDALANDL}}</var></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="42"></td>
        </tr>
        <tr>
          <td align="center" valign="top"><font face="Arial" color="#000000" style="font-size:18px;"><strong>THESE PROFILES MAY BE INTERESTED IN YOU</strong></font></td>
        </tr>
         <tr>
          <td align="center" height="27" valign="top"><font face="Arial" color="#000000" style="font-size:15px; line-height:22px;">They recently visited your profile.</font></td>
        </tr>
        <tr>
          <td align="center" valign="top"><div style="width:100%"><img src="~$IMG_URL`/images/mailer/visitorAlert/img2.gif" style="device-width: 320px; display:block;orientation:portrait; width:inherit;" vspace="0" hspace="0" align="absmiddle" /></div></td>
        </tr>
         ~if $variableDiscount`
 <tr>
 
                <td ><table border="0" cellspacing="0" cellpadding="0" width="100%" bgcolor="#fbebbf">
                    <tbody><tr>
                      <td width="484" style="font-family:Arial; font-size:13px; color:#000000; padding:6px 0 6px 11px; line-height:20px;" height="42"> To call or email ~if $GENDER eq "M"` her ~else` him ~/if` directly, <strong style="color:#801212; font-size:14px;">upgrade</strong> your membership at <strong style="color:#801212; font-size:14px;">~$variableDiscount`% discount</strong>.<br>
                        Hurry, offer expires on <strong style="color:#801212;">~$VD_END_DAY`<sup>~$VD_END_DAY_SUFFIX`</sup> ~$VD_END_MONTH`, ~$VD_END_YEAR`</strong>.</td>
                        
                    </tr>
                    <tr>
                      <td style=" padding:0px 0 0px 11px;"><table width="100" border="0" cellspacing="0" cellpadding="0" align="left" style="border:1px solid #d40832; margin:0px 12px 10px 0px;">
                          <tbody><tr>
                            <td background="~$IMG_URL`/images/mailer/up_btnBG.gif" height="27" align="center" bgcolor="#C31011" style=" margin-bottom:10px;"><a href="(LINK)MEMBERSHIP_COMPARISON:profileid=~$profileid`,source=~$topSource`(/LINK)" target="_blank" style="font-family:Arial; font-size:13px; color:#ffffff; text-decoration:none;"><strong>Get Discount</strong></a></td>
                          </tr>
                        </tbody></table></td>
                        
                    </tr>
                  </tbody></table></td>
                  
              </tr>
              <tr><td height="10"></td></tr>
~/if`
        <tr>
          <td align="center">~$va_match_1`</td>
        </tr>
        <tr>
          <td height="13"></td>
        </tr>
          
          ~if $profileState eq "D3" or $profileState eq "D4" or $profileState eq "E4" or $profileState eq "D2"`
        <tr>
          <td bgcolor="#fff5df" style="padding-left:12px;">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:13px; color:#000000;">
                <tr>
                	<td colspan="3" height="30">To get the most from your FREE TRIAL OFFER, express interest in <font color="#a70805">ALL profiles</font> you like.</td>
                </tr>
                <tr>
                <td style="line-height:18px;" height="80">See phone/email of members who <font color="#a70805">'Accept'</font><br />your 'Interest' before <font color="#a70805"><var>{{FTO_END_DAY_SINGLE_DOUBLE_DIGIT:profileid=~$profileid`}}</var><sup><var>{{FTO_END_DAY_SUFFIX:profileid=~$profileid`}}</var></sup> <var>{{FTO_END_MONTH:profileid=~$profileid`}}</var> <var>{{FTO_END_YEAR:profileid=~$profileid`}}</var></font><br /><br /><em style="color:#474747;">Most people express interest in <strong>40 profiles</strong> to find a life partner.</em></td>
				<td width="47" align="center" valign="top"><table width="47" border="0" cellspacing="0" cellpadding="0" align="center" style="font-family:Tahoma; font-size:10px; margin:10px 10px 0 0px;">
              <tr>
                <td bgcolor="#C00402" height="16" align="center" background="~$IMG_URL`/images/mailer/visitorAlert/calTop.jpg" style="color:#ffffff"><strong><var>{{FTO_END_MONTH_UPPERCASE:profileid=~$profileid`}}</var></strong></td>
              </tr>
              <tr>
                <td height="24" align="center" background="~$IMG_URL`/images/mailer/visitorAlert/dateBG.jpg" bgcolor="#E6E4E5" style="font-size:19px;"><strong><var>{{FTO_END_DAY:profileid=~$profileid`}}</var></strong></td>
              </tr>
              <tr>
                <td background="~$IMG_URL`/images/mailer/visitorAlert/calBot.jpg" height="31" align="center" valign="top" style="color:#ffffff; line-height:15px;"><strong><var>{{FTO_END_YEAR:profileid=~$profileid`}}</var></strong></td>
              </tr>
            </table></td>
                </tr>
            </table>
            </td>
        </tr>
      <tr>
        <td><div style="width:100%"><img src="~$IMG_URL`/images/mailer/visitorAlert/FtoBOT.gif" style="device-width: 320px; display:block;orientation:portrait; width:inherit;" vspace="0" hspace="0" align="left" /></div></td>
      </tr>
            ~/if`
            
            ~if $profileState eq "C2"`
        <tr>
          <td bgcolor="#fff5df" style="padding-left:12px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:13px; color:#000000;">
                <tr>
                	<td colspan="3" height="13">&nbsp;</td>
                </tr>
                <tr>
                <td style="line-height:18px; font-size:14px;" height="80" width="365"><var>{{NAME_PROFILE:profileid=~$profileid`}}</var>, You can  <font color="#a70805" >see phone/email </font>of profiles for <strong><font color="#a70805">FREE!</font></strong><br />
<font style="font-size:12px; ">However, to get this Jeevansathi Free Trial Offer worth Rs. <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- before <strong><var>{{FTO_END_DAY_SINGLE_DOUBLE_DIGIT:profileid=~$profileid`}}</var><sup><var>{{FTO_END_DAY_SUFFIX:profileid=~$profileid`}}</var></sup> <var>{{FTO_END_MONTH:profileid=~$profileid`}}</var> <var>{{FTO_END_YEAR:profileid=~$profileid`}}</var>.</strong></font><br />
<font style="color:#474747; font-size:11px; "><A href="(LINK)OFFER_PAGE_URL:profileid=~$profileid`(/LINK)">Know more</A> about Free Trial Offer</font></td>
				<td><table height="41" width="auto" style="font-size:12px">
				  <tr>
				    <td  ~if $GENDER eq "M"` background="~$IMG_URL`/images/mailer/visitorAlert/btn-update-male-trans.png" ~else` background="~$IMG_URL`/images/mailer/visitorAlert/btn-update-female-trans.png" ~/if` width="110" style="font:12px 'Trebuchet MS', Arial, Helvetica, sans-serif; padding-left:50px"><a href="(LINK)UPLOAD_PHOTO:profileid=~$profileid`(/LINK)" style="COLOR:#000; text-decoration:NONE">UPLOAD PHOTO</a></td>
				    
				    </tr>
				  </table></td>
            
              </tr>
            </table>
            </td>
        </tr>
      <tr>
        <td><div style="width:100%"><img src="~$IMG_URL`/images/mailer/visitorAlert/FtoBOT.gif" style="device-width: 320px; display:block;orientation:portrait; width:inherit;" vspace="0" hspace="0" align="left" /></div></td>
      </tr>
            ~/if`
            
            ~if $profileState eq "C3"`
        <tr>
          <td bgcolor="#fff5df" style="padding-left:12px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:13px; color:#000000;">
                <tr>
                	<td colspan="3" height="13">&nbsp;</td>
                </tr>
                <tr>
                <td style="line-height:18px; font-size:14px;" height="80" width="365"><var>{{NAME_PROFILE:profileid=~$profileid`}}</var>, You can  <font color="#a70805" >see phone/email </font>of profiles for <strong><font color="#a70805">FREE!</font></strong><br />
<font style="font-size:12px; ">However, to get this Jeevansathi Free Trial Offer worth Rs. <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- before <strong><var>{{FTO_END_DAY_SINGLE_DOUBLE_DIGIT:profileid=~$profileid`}}</var><sup><var>{{FTO_END_DAY_SUFFIX:profileid=~$profileid`}}</var></sup> <var>{{FTO_END_MONTH:profileid=~$profileid`}}</var> <var>{{FTO_END_YEAR:profileid=~$profileid`}}</var>.</strong></font><br />
<font style="color:#474747; font-size:11px; "><A href="(LINK)OFFER_PAGE_URL:profileid=~$profileid`(/LINK)">Know more</A> about Free Trial Offer</font></td>
				<td><table height="41" width="auto" style="font-size:12px">
				  <tr>
				    <td background="~$IMG_URL`/images/mailer/visitorAlert/btn-verify.jpg" width="100" style="font:12px 'Trebuchet MS', Arial, Helvetica, sans-serif; padding-left:50px"><a href="(LINK)VERIFY_PHONE:profileid=~$profileid`(/LINK)" style="COLOR:#000; text-decoration:NONE">VERIFY PHONE</a></td>
				    
				    </tr>
				  </table></td>
            
              </tr>
            </table>
            </td>
        </tr>
      <tr>
        <td><div style="width:100%"><img src="~$IMG_URL`/images/mailer/visitorAlert/FtoBOT.gif" style="device-width: 320px; display:block;orientation:portrait; width:inherit;" vspace="0" hspace="0" align="left" /></div></td>
      </tr>
            ~/if`
            
            ~if $profileState eq "C1"`
        <tr>
          <td bgcolor="#fff5df" style="padding-left:12px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:13px; color:#000000;">
                <tr>
                	<td colspan="3" height="13">&nbsp;</td>
                </tr>
                <tr>
                <td style="line-height:18px; font-size:14px;" height="80" width="365"><var>{{NAME_PROFILE:profileid=~$profileid`}}</var>, You can  <font color="#a70805" >see phone/email </font>of profiles for <strong><font color="#a70805">FREE!</font></strong><br />
<font style="font-size:12px; ">However, to get this Jeevansathi Free Trial Offer worth Rs. <var>{{FTO_WORTH:profileid=~$profileid`}}</var>/- you must</font><br />
<table height="41" width="auto" style="font-size:12px">
<tr>
<td ~if $GENDER eq "M"` background="~$IMG_URL`/images/mailer/visitorAlert/btn-update-male-trans.png" ~else` background="~$IMG_URL`/images/mailer/visitorAlert/btn-update-female-trans.png" ~/if`width="110" style="font:12px 'Trebuchet MS', Arial, Helvetica, sans-serif; padding-left:50px"><a href="(LINK)UPLOAD_PHOTO:profileid=~$profileid`(/LINK)" style="COLOR:#000; text-decoration:NONE">UPLOAD PHOTO</a>
</td><td>and</td>
<td  background="~$IMG_URL`/images/mailer/visitorAlert/btn-verify.jpg" width="100" headers="41" style="font:12px 'Trebuchet MS', Arial, Helvetica, sans-serif; padding-left:50px"><a href="(LINK)VERIFY_PHONE:profileid=~$profileid`(/LINK)" style="COLOR:#000; text-decoration:NONE">VERIFY PHONE</a></td>
<td>before <strong><var>{{FTO_END_DAY_SINGLE_DOUBLE_DIGIT:profileid=~$profileid`}}</var><sup><var>{{FTO_END_DAY_SUFFIX:profileid=~$profileid`}}</var></sup> <var>{{FTO_END_MONTH:profileid=~$profileid`}}</var> <var>{{FTO_END_YEAR:profileid=~$profileid`}}</var>.</strong></td>
</tr>
</table>

<font style="color:#474747; font-size:11px; float:right"><A href="(LINK)OFFER_PAGE_URL:profileid=~$profileid`(/LINK)">Know more</A> about Free Trial Offer</font></td>
				
            
                </tr>
            </table>
            </td>
        </tr>
      <tr>
        <td><div style="width:100%"><img src="~$IMG_URL`/images/mailer/visitorAlert/FtoBOT.gif" style="device-width: 320px; display:block;orientation:portrait; width:inherit;" vspace="0" hspace="0" align="left" /></div></td>
      </tr>
            ~/if`
            
            ~if $profileState eq "D1"`
        <tr>
          <td bgcolor="#fff5df" style="padding-left:12px;">
           <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; font-size:13px; color:#000000;">
                <tr>
                	<td colspan="3" height="13">&nbsp;</td>
                </tr>
                <tr>
                <td style="line-height:18px; font-size:14px;" height="80" width="365"><var>{{NAME_PROFILE:profileid=~$profileid`}}</var>, You can  <font color="#a70805" >see phone/email </font>of profiles for <strong><font color="#a70805">FREE!</font></strong><br />
                <img src="~$IMG_URL`/images/mailer/visitorAlert/maroon-arrow.jpg" style="margin-right:8px" /><font style="font-size:12px; "> Express Interest in those you like.<br />
               <img src="~$IMG_URL`/images/mailer/visitorAlert/maroon-arrow.jpg" style="margin-right:8px"/>  
                See their phone/email when they 'Accept' your Interest.</font>
                  <br /><br /><font style="color:#474747; font-size:11px "><A href="(LINK)OFFER_PAGE_URL:profileid=~$profileid`(/LINK)">Know more</A> about Free Trial Offer</font></td>
				<td width="60" align="center" valign="top"><table width="47" border="0" cellspacing="0" cellpadding="0" align="center" style="font-family:Tahoma; font-size:10px; margin:10px 10px 0 0px;">
              <tr>
                <td bgcolor="#C00402" height="16" align="center" background="~$IMG_URL`/images/mailer/visitorAlert/calTop.jpg" style="color:#ffffff"><strong><var>{{FTO_END_MONTH_UPPERCASE:profileid=~$profileid`}}</var></strong></td>
              </tr>
              <tr>
                <td height="24" align="center" background="~$IMG_URL`/images/mailer/visitorAlert/dateBG.jpg" bgcolor="#E6E4E5" style="font-size:19px;"><strong><var>{{FTO_END_DAY:profileid=~$profileid`}}</var></strong></td>
              </tr>
              <tr>
                <td background="~$IMG_URL`/images/mailer/visitorAlert/calBot2.jpg" height="29" align="center" valign="top" style="color:#ffffff; line-height:15px;"><strong><var>{{FTO_END_YEAR:profileid=~$profileid`}}</var></strong></td>
              </tr>
            </table></td>
            <td width="143" ><strong ><font color="#A70805">Hurry!</font></strong><br />
Your Free Trial Offer<br />
ends on<strong> <var>{{FTO_END_DAY_SINGLE_DOUBLE_DIGIT:profileid=~$profileid`}}</var><var>{{FTO_END_DAY_SUFFIX:profileid=~$profileid`}}</var> <var>{{FTO_END_MONTH:profileid=~$profileid`}}</var> <var>{{FTO_END_YEAR:profileid=~$profileid`}}</var>.</strong></td>
                </tr>
            </table>
            </td>
        </tr>
      <tr>
        <td><div style="width:100%"><img src="~$IMG_URL`/images/mailer/visitorAlert/FtoBOT.gif" style="device-width: 320px; display:block;orientation:portrait; width:inherit;" vspace="0" hspace="0" align="left" /></div></td>
      </tr>
            ~/if`
        <tr>
          <td align="center">~$va_matches_2to20`</td>
        </tr>
        <tr>
          <td height="12"></td>
        </tr>
        <tr>
          <td style="font-size:12px; font-family:Arial; padding-left:10px;">To view all the members who have visited your profile so far, <a href="(LINK)PROFILE_VISITORS:profileid=~$profileid`(/LINK)" target="_blank" style="color:#0f529d;"><b>Click Here.</b></a></td>
        </tr>
        <tr>
          <td height="19" style="padding:0px 10px;"><div style="border-bottom:1px solid #f5f3f3; height:19px;"><img src="~$IMG_URL`/images/mailer/visitorAlert/spacer.gif" width="1" height="1" vspace="0" hspace="0" align="left"></div></td>
        </tr>        
         ~if $profileState eq "E1" or $profileState eq "E2" or $profileState eq "E3" or $profileState eq "E5" or $profileState eq "F" or $profileState eq "G" or $profileState eq "P"`
         ~if $variableDiscount`
         <tr>
          <td height="10"></td>
        </tr>
<tr>

          <td ><table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#fff6de">
              <tr>
                <td height="17"></td>
              </tr>
              <tr>
                <td style="font-family:Arial; font-size:12px; color:#000000; padding-left:10px;" valign="top" height="21">To directly contact members you like,</td>
              </tr>
              <tr>
                <td style="padding-left:10px;"><table width="154" border="0" cellspacing="0" cellpadding="0" align="left" style="border:1px solid #d40832; margin:0px 12px 6px 0px;">
                    <tr>
                      <td background="~$IMG_URL`/images/mailer/up_btnBG.gif" height="27" align="center" bgcolor="#C31011" style=" margin-bottom:10px;"><a href="(LINK)MEMBERSHIP_COMPARISON:profileid=~$profileid`,source=~$BottomSource`(/LINK)" target="_blank" style="font-family:Arial; font-size:13px; color:#ffffff; text-decoration:none;"><strong>Upgrade at ~$variableDiscount`% OFF</strong></a></td>
                    </tr>
                  </table>
                  <table width="252" border="0" cellspacing="0" cellpadding="0" align="left">
                    <tr>
                      <td height="31" width="35" style="font-family:Arial; font-size:13px; color:#707070; text-decoration:none;">OR</td>
                      <td><font face="Arial" color="#000000" style="font-size:13px;">Just call us (Toll Free) <br />
                        1800 419 6299 </font></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td style="padding-left:10px;"><table width="252" border="0" cellspacing="0" cellpadding="0" align="left">
                    <tr>
                      <td height="31" width="24"><img src="~$IMG_URL`/images/mailer/lockICnew.gif" width="24" height="29" vspace="0" hspace="0" align="right" /></td>
                      <td><font face="Arial" color="#575656" style="font-size:11px;"><em>Easy and secure payment options</em></font></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td height="9" style="padding:0 5px 0 5px;"><div style="width:100%" align="center"><img src="~$IMG_URL`/images/divider.gif"  vspace="0" hspace="0" align="absmiddle" style="max-width: 474px; display:block;orientation:portrait; width:inherit;" /></div></td>
              </tr>
              <tr>
                <td height="10"></td>
              </tr>
              <tr>
                <td style="padding-left:10px;"><font face="Arial" color="#000000" style="font-size:13px; line-height:20px;">Benefits of Paid Membership:</font></td>
              </tr>
              <tr>
                <td style="padding-left:10px;"><table width="262" border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial; font-size:11px; color:#000000;">
                    <tr>
                      <td><img src="~$IMG_URL`/images/mailer/call_icnew.gif" width="121" height="49" vspace="0" hspace="0" align="left" /></td>
                      <td rowspan="2" width="15"></td>
                      <td><img src="~$IMG_URL`/images/mailer/chat_boxnew.gif" width="121" height="49" vspace="0" hspace="0" align="left" /></td>
                      <td rowspan="2" width="15"></td>
                    </tr>
                    <tr>
                      <td align="center" height="44" valign="top" width="121" style="padding-top:5px;">See <strong><font color="#801212">phone numbers</font></strong><br>
                        and <strong><font color="#801212">email id's</font></strong></td>
                      <td valign="top" align="center" width="121" style="padding-top:5px;">Initiate <strong><font color="#801212">chats</font></strong> on<br>
                        website or GTalk</td>
                    </tr>
                  </table>
                  <table border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial; font-size:11px; color:#000000;">
                    <tr>
                      <td width="121"><img src="~$IMG_URL`/images/mailer/msg_boxnew.gif" width="121" height="49" vspace="0" hspace="0" align="left" /></td>
                    </tr>
                    <tr>
                      <td align="center" height="44" valign="top" width="121" style="padding-top:5px;">Send <strong><font color="#801212">personalized <br>
                        messages</font></strong></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
            
        </tr>
        ~else`
        <tr>
          <td height="19"></td>
        </tr>
        <tr>
          <td style="font-family:Arial; font-size:12px; color:#000000; padding-left:13px;" valign="top" height="21"> ~if $profileState eq "P"`See~else`To see~/if` contact details of profile(s) you like, and avail other benefits of paid membership:</td>
        </tr>
        <tr>
          <td valign="top" style="padding-left:13px;"><table width="292" border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial; font-size:12px; color:#000000;">
              <tr>
                <td><img src="~$IMG_URL`/images/mailer/visitorAlert/call_ic.gif" width="121" height="48" vspace="0" hspace="0" align="left" /></td>
                <td rowspan="2" width="25"></td>
                <td><img src="~$IMG_URL`/images/mailer/visitorAlert/chat_box.gif" width="121" height="48" vspace="0" hspace="0" align="left" /></td>
                <td rowspan="2" width="25"></td>
              </tr>
              <tr>
                <td align="center" height="44" valign="top" width="121"><strong>See <font color="#801212">phone numbers</font><br>
                  and <font color="#801212">email id's</font></strong></td>
                <td valign="top" align="center" width="121"><strong>Initiate <font color="#801212">chats</font> on<br>
                  website or gtalk</strong></td>
              </tr>
            </table>
            <table border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial; font-size:12px; color:#000000;">
              <tr>
                <td width="121"><img src="~$IMG_URL`/images/mailer/visitorAlert/msg_box.gif" width="121" height="48" vspace="0" hspace="0" align="left" /></td>
              </tr>
              <tr>
                <td align="center" height="44" valign="top" width="121"><strong>Send <font color="#801212">personalized <br>
                  messages</font></strong></td>
              </tr>
            </table></td>
        </tr>
         ~if $profileState neq "P"`
        <tr>
          <td style="padding-left:18px;">
          	<table width="184" border="0" cellspacing="0" cellpadding="0" align="left" style="border:1px solid #d40832; margin-right:12px;">
              <tr>
                <td height="27" align="center" background="~$IMG_URL`/images/mailer/visitorAlert/btnBG.gif" bgcolor="#C31011"><a href="(LINK)MEMBERSHIP_COMPARISON:profileid=~$profileid`,source=~$BottomSource`(/LINK)" target="_blank" style="font-family:Arial; font-size:13px; color:#ffffff; text-decoration:none;"><strong>Upgrade membership now</strong></a></td>
              </tr>
            </table>
            <table width="252" border="0" cellspacing="0" cellpadding="0" align="left">
              <tr>
                <td height="31" width="24"><img src="~$IMG_URL`/images/mailer/visitorAlert/lockIC.gif" width="24" height="29" vspace="0" hspace="0" align="right" /></td>
                <td><font face="Arial" color="#575656" style="font-size:11px;"><em>Easy and secure payment options available</em></font></td>
              </tr>
            </table>
		  </td>
        </tr>
        ~/if`
        ~/if`
        ~/if`
        <tr>
          <td height="25"></td>
        </tr>
        <tr>
            <td style="padding:0 10px;"><table style="font-family:Arial" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <td style="font-size:12px; padding-left:10px;" valign="top" height="30">Wish you success in your search.</td>
                </tr>
                <tr>
                  <td><table style="font-family:Arial,Times New Roman,Times,serif;font-size:11px;line-height:17px; color:#000000; margin-left:10px; -webkit-text-size-adjust: none;" align="left" border="0" cellpadding="0" cellspacing="0">
                      <tbody>
                        <tr>
                          <td style="font-size:12px" valign="top">Warm Regards,<br>
                            <b style="color:#c4161c;">Jeevansathi<span style="font-size:1px;"> </span><font color="#00000">.com Team</font></b><br />
                            <a href="(LINK)JS_FB_PAGE(/LINK)" target="_blank"><img src="~$IMG_URL`/images/mailer/visitorAlert/fbBTN.gif" width="111" height="29" border="0" alt="Join Us on Facebook" vspace="4"></a></td>
                          <td style="font-size:12px" valign="top" width="10">&nbsp;</td>
                        </tr>
                      </tbody>
                    </table>
                    <table style="font-family:Arial,Times New Roman,Times,serif;font-size:11px;line-height:17px; color:#000000;" border="0" cellpadding="0" cellspacing="0" align="right">
                      <tr>
                        <td valign="top"><img src="~$IMG_URL`/images/mailer/visitorAlert/icon1.gif" align="absmiddle" height="24" width="24"></td>
                        <td style="font-family:Arial;font-size:12px;" width="226" align="left"><span style="font-size:13px"><b>Call us at:</b></span>1800-419-6299 (Toll free), or</span></td>
                      </tr>
                      <tr>
                        <td valign="top"><img src="~$IMG_URL`/images/mailer/visitorAlert/visitIC.gif" align="absmiddle" height="33" width="32"></td>
                        <td style="font-family:Arial;font-size:12px;-webkit-text-size-adjust: none;" align="left">Visit your <strong>nearest Jeevansathi centre:</strong><br /><font style="font-size:11px;">~$jeevansathi_contact_address`</font></td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
        </tr>
        <tr>
          <td height="19"></td>
        </tr>
        <tr>
          <td>
          	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial; color:#000000; font-size:10px;">
              <tr>
                <td width="10"></td>
                <td height="20" style="border-bottom:1px solid #f5f3f3;"><img src="~$IMG_URL`/images/mailer/visitorAlert/spacer.gif" width="1" height="20" /></td>
                <td width="10"></td>
              </tr>
              <tr>
                <td height="22"></td>
                <td valign="bottom"> <table width="300" border="0" cellspacing="0" cellpadding="0" align="left" style="font-family:Arial; color:#000000; font-size:11px;">
                  <tr>
                    <td>You can also visit any of our 60+ offices across India</td>
                  </tr>
                </table>
                <table width="133" border="0" cellspacing="0" cellpadding="0" align="right" style="font-family:Arial; color:#000000; font-size:11px;">
                  <tr>
                    <td><a href="(LINK)ALLCENTRESLOCATIONS:profileid=~$profileid`(/LINK)" target="_blank" title="View all Centre Addesses" style="color:#0f529d">View all Centre Addesses</a></td>
                  </tr>
                </table>
</td>
                <td></td>
              </tr>
              <tr>
                <td height="12"colspan="3"></td>
              </tr>
              <tr>
                <td></td>
                <td><span style="color:#711e1e">North India:</span> Noida, Gurgaon, Delhi - <font color="#7a7676">Laxmi Nagar, Connaught Place, Nehru Place, Kamla Nagar, Pitampura, Malviya Nagar, Rajori Garden,</font> Agra, Allahabad, Chandigarh, Jaipur, Kanpur, Dehradun, Varanasi, Lucknow, Ludhiana, Jallandhar, Gorakhpur, Pathankot, Amritsar, Jammu, Bareilly</span></td>
                <td></td>
              </tr>
              <tr>
                <td height="12"colspan="3"></td>
              </tr>
              <tr>
                <td></td>
                <td><span style="color:#711e1e">West India:</span> Goa, Kolahpur, Ahmedabad, Aurangabad, Baroda, Nagpur, Nashik, Rajkot, Surat, Pune - <font color="#7a7676">Chinchwad, Deccan, Koregaon, Modelina Road, Mumbai - Andheri(West), Andheri East, Borivalli, Chembur, Ghatkopar, Mulund, Thane, Vashi, Worli</font></td>
                <td></td>
              </tr>
              <tr>
                <td height="12"colspan="3"></td>
              </tr>
              <tr>
                <td></td>
                <td><span style="color:#711e1e">South India:</span> Chennai, Kochi, Coimboture, Hosur, Mangalore, Mysore, Trichy, Trivandrum, Vijaywada, Vyzag
Bangalore - <font color="#7a7676">Dickenson Road, Koramangla,</font> Hyderabad - <font color="#7a7676">Begumpet, Himmayat Nagar</font></td>
                <td></td>
              </tr>
              <tr>
                <td height="12"colspan="3"></td>
              </tr>
              <tr>
                <td></td>
                <td><span style="color:#711e1e">East &amp; Central India:</span> Ranchi, Bhopal, Bhubneshwar, Indore, Patna, Jamshedpur,  Gwalior, Jabalpur, Raipur,
Kolkata - <font color="#7a7676">Ghariat Road, Salt Lake, AJC Bose Road</font></td>
                <td></td>
              </tr>
               <tr>
                <td height="20"colspan="3"></td>
              </tr>
              <tr>
                <td colspan="3" bgcolor="#f7f6f6"  height="23"><table width="75%" border="0" cellspacing="0" cellpadding="0" align="center" style="font-family:Verdana, Arial, Times New Roman, Times, serif; color:#000000; font-size:11px;">
                  <tr>
                    <td><table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" style="font-family:Verdana, Arial, Times New Roman, Times, serif; color:#000000; font-size:11px;">
                      <tr>
                        <td width="141">100% Profile Screening</td>
                        <td width="10" align="center"><font color="#b7b6b6">|</font></td>
                        <td width="150">Lakhs of Success Stories</td>
                        <td width="10" align="center"><font color="#b7b6b6">|</font></td>
                        <td width="120">Privacy Options</td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
               <tr>
                <td height="10"colspan="3"></td>
              </tr>
              <tr>
                <td align="center" height="48" colspan="3" style="font-family:Verdana, Arial, Times New Roman, Times, serif; color:#c9c9c9; font-size:11px; line-height:16px;">This email shows profile visitors who may be interested in you.<br>
      Click here to <a href="(LINK)UNSUBSCRIBE:profileid=~$profileid`(/LINK)" target="_blank" title="Click here" style="color:#c9c9c9;">UNSUBSCRIBE</a> from Visitor Alert mails.</td>
              </tr>
            </table>
		  </td>
        </tr>
      </table></td>
    <td width="10"><img src="~$IMG_URL`/images/mailer/visitorAlert/spacer.gif" width="10" height="1" vspace="0" hspace="0" align="left" /></td>
  </tr>
  <tr>
    <td colspan="3" height="12"></td>
  </tr>
</table>
</body>
</html>
