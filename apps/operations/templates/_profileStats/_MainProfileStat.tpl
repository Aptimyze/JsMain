<table width="60%"  border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
  <td  valign="top"><br>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
     <td colspan="2" rowspan="3" valign="top"><img src="~sfConfig::get('app_img_url')`/P/imagesnew/ps_1.gif" width="9" height="24"></td>
     <td width="531" bgcolor="#000000"><img src="~sfConfig::get('app_img_url')`/P/imagesnew/zero.gif" width="1" height="1"></td>
     <td colspan="2" rowspan="3" valign="top"><img src="~sfConfig::get('app_img_url')`/P/imagesnew/ps_2.gif" width="39" height="24"></td>
    </tr>
    <tr>
     <td height="15" width="100%" class="bgbrownL3"><span class="mediumblackb"><b>Profile Statistics</b></span></td>
    </tr>
    <tr>
     <td bgcolor="#000000"><img src="~sfConfig::get('app_img_url')`/P/imagesnew/zero.gif" width="1" height="1"></td>
    </tr>
    <tr>
     <td bgcolor="#000000"><img src="~sfConfig::get('app_img_url')`/P/imagesnew/zero.gif" width="1" height="1"></td>
     <td width="8">&nbsp;</td>
     <td>
     <table width="100%"  border="0" cellspacing="0" cellpadding="0">

        ~if $data.PROFILE_NAME`
        <tr>
               <td width="27%" class="mediumblack">Name of person </td>
               <td width="1%">:</td>
               <td colspan="2" class="mediumblack" >&nbsp;~$data.PROFILE_NAME`</td>
        </tr>
        ~/if`
        <tr>
               <td width="27%" class="mediumblack">Current Status </td>
               <td width="1%">:</td>
               <td colspan="2" class="mediumblack" >&nbsp;~if $data.ONLINE_STATUS` Online ~else` Offline ~/if` </td>
        </tr>
        <tr>
               <td width="27%" class="mediumblack">Profile Views </td>
               <td width="1%">:</td>
               <td colspan="2" class="mediumblack" >&nbsp;~$data.PROFILE_VIEWS`</td>
        </tr>
        <tr>
                <td class="mediumblack">Profile last edited</td>
                <td>:</td>
                <td colspan="2" class="mediumblack" >&nbsp;~$data.LAST_MOD_DT`</td>
        </tr>
        ~if $data.PRIVACY`
        <tr>
                <td class="mediumblack">Privacy Settings</td>
                <td>:</td>
                <td colspan="2" class="mediumblack">&nbsp;
                ~if $data.PRIVACY eq 'A'`
                	Everyone can view
                ~elseif $data.PRIVACY eq 'F'`
                	Only people who pass filter can view
                ~elseif $data.PRIVACY eq 'C'`
                	Only contacted people can view
                ~/if`
                </td>
        </tr>
        ~/if`
         <tr>
                <td class="mediumblack">Action Required </td>
                <td>:</td>
                <td class="mediumblack" >&nbsp;~$data.ACTION_REQUIRED`</font></td>
                <td>&nbsp;
                </td>
        </tr>
        <tr>
                <td class="mediumblack">Membership Status </td>
                <td>:</td>
                <td class="mediumblack" >&nbsp; ~$data.MEMBERSHIP` ~if $data.MEMBERSHIP eq 'eValue'`&nbsp;Pack ~/if` </td>
                <td>&nbsp;
                ~if $data.MEMBERSHIP eq 'eRishta'`
                        <img src="~sfConfig::get('app_img_url')`/P/imagesnew/logo_erishta.gif" >
                ~elseif $data.MEMBERSHIP eq 'eValue'`
                        <img src="~sfConfig::get('app_img_url')`/P/imagesnew/logo_evaluepack.gif" >
                ~elseif $data.MEMBERSHIP eq 'eClassified'`
                        <img src="~sfConfig::get('app_img_url')`/P/imagesnew/logo_eclassifieds.gif" >
                ~/if`
                </td>
        </tr>
        ~if $data.MEMBERSHIP_VAS`
        <tr>
                <td class="mediumblack">Add on Services <br></td>
                <td>:</td>
                <td class="mediumblack" >&nbsp;~$data.MEMBERSHIP_VAS` </td>
        </tr>
        ~/if`
        ~if $data.MEMBERSHIP_EXPIRY`
        <tr>
                <td class="mediumblack">Membership Expires on</td>
                <td>:</td>
                <td class="mediumblack">&nbsp;~$data.MEMBERSHIP_EXPIRY`<br>
        </tr>
        ~/if`
        <tr>
                <td class="mediumblack" valign="top">Service Requirement</td>
                <td valign="top">:</td>
                <td valign="top" class="mediumblack"><font color="red" >&nbsp;~$data.SERVICE_REQUIREMENT`<br>
        </tr>
    </table>
    </td>
     <td width="38">&nbsp;</td>
     <td  bgcolor="#000000"><img src="~sfConfig::get('app_img_url')`/P/imagesnew/zero.gif" width="1" height="1"></td>
    </tr>
    <tr>
     <td colspan="5" bgcolor="#696969"><img src="~sfConfig::get('app_img_url')`/P/imagesnew/zero.gif" width="1" height="1"></td>
    </tr>
   </table><br>

<br>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
     <td height="18" align="right">
        <span class="mediumblackb">&nbsp;
	<font size='3'><b>
        <!--<a href="~sfConfig::get('app_site_url')`/crm/extraDetails_profile.php?cid=~$cid`&checksum=~$checksum`&table_name=~$data.CONTACT_TABLE_NAME`&paid_str=~$data.PAID_MEMBERSHIP_EXPIRY`" target=_blank>Profile Matches (Click here)</a>-->
	<a href="~$data.actualUrl`/crm/extraDetails_profile.php?cid=~$cid`&checksum=~$checksum`&table_name=~$data.CONTACT_TABLE_NAME`&paid_str=~$data.PAID_MEMBERSHIP_EXPIRY`" target=_blank>Profile Matches (Click here)</a>
	</b>
	</font>
        </span>
     </td>
    </tr>
   </table>
