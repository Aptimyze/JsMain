~assign var='kk' value=0`
~foreach from=$tuple_profiles_inputs item=profile_id key=k`
 <tr>
        <td>
        	<table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="font-family:Arial, Verdana; font-size:12px; color:#000000;-webkit-text-size-adjust: none; text-align:left;">
            <tr>
              <td></td>
              <td height="28" style="padding-left:5px;"><a href="#" target="_blank" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#14428e; text-decoration:none;"><var>{{USERNAME:profileid=~$otherProfile`}}</var>~if $paid eq 1` | Paid member(~$paidStatus`)~/if`</a></td>
              <td></td>
            </tr>
            <tr>
              <td width="22"><img src="http://ieplads.com/mailers/2014/jeevansathi/NEOI1may/images/spacer.gif" width="20" height="1" vspace="0" hspace="0" align="left"></td>
              <td width="110" height="140" valign="top" colspan="2"><table cellpadding="0" cellspacing="0" align="left"><tr><td valign="top"><a href="(LINK)PHOTO_ALBUM:profileid=~$profileid`,receiver_id=~$profile_id`(/LINK)" style="text-decoration:none; color:#0f529d;" target="_blank"><img src="(PHOTO)PROFILE_PIC:receiver_id=~$profileid`,profileid=~$profile_id`,photo_type=search(/PHOTO)" align="left" width="100" height="133" hspace="0" vspace="0" border="0" style="border:1px solid #e6e6e6;" /></a></td></tr></table><table border="0" cellspacing="2" align="left" cellpadding="0" style="font-family:Arial, Verdana; font-size:12px; color:#000000;-webkit-text-size-adjust: none; text-align:left;">
                  <tr>
                  	<td width="10" rowspan="7"></td>
                    <td><var>{{AGE:profileid=~$profile_id`}}</var> yrs; <var>{{HEIGHT:profileid=~$profile_id`}}</var></td>
                  </tr>
                  <tr>
                    <td><var>{{RELIGION_CASTE_VALUE_TEMPLATE:profileid=~$profile_id`}}<var></td>
                  </tr>
                  <tr>
                    <td><var>{{MTONGUE:profileid=~$profile_id`}}</var></td>
                  </tr>
                  <tr>
                    <td><var>{{EDUCATION:profileid=~$profile_id`}}</var></td>
                  </tr>
                  <tr>
                    <td><var>{{OCCUPATION:profileid=~$profile_id`}}</var></td>
                  </tr>
                  <tr>
                    <td><var>{{INCOME:profileid=~$profile_id`}}</var></td>
                  </tr>
                  <tr>
                    <td><var>{{CITY_WITH_COUNTRY:profileid=~$profile_id`}}</var></td>
                  </tr>
                </table>
                
                </td>
            </tr>
            <tr>
            	<td></td>
              <td colspan="2" height="10"></td>
            </tr>
            <tr>
            	<td></td>
              	<td colspan="2">
              		<table width="286" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="140">
                        	<table border="0" width="140" align="left" cellspacing="0" cellpadding="0" style="font-family:Arial, Verdana; font-size:14px;">
                  <tr>
                    <td bgcolor="#ad160d" height="27" align="center" width="140"><a href="(LINK)DETAILED_PROFILE_HOME:profileid=~$profileid`,receiver_id=~$profile_id`(/LINK)" target="_blank" style="text-decoration:none; color:#ffffff; font-size:12px;"> <strong>View Contact Details</strong></a></td>
                  </tr>
                </table>
                        </td>
                        <td width="4"></td>
                        <td width="127" align="left">
                        	<table border="0" width="120" align="left" cellspacing="0" cellpadding="0" style="font-family:Arial, Verdana; font-size:14px;">
                  <tr>
                    <td bgcolor="#ad160d" height="27" align="center" width="120"><a href="(LINK)DETAILED_PROFILE_HOME:profileid=~$profileid`,receiver_id=~$profile_id`(/LINK)" target="_blank" style="text-decoration:none; color:#ffffff; font-size:12px;"> <strong>Send Message</strong></a></td>
                  </tr>
                </table>
                        </td>
                      </tr>
                    </table>

              </td>
            </tr>
            
            
          </table>
          	
        </td>
      </tr>
    ~assign var='kk' value=$kk+1`
        ~/foreach`
