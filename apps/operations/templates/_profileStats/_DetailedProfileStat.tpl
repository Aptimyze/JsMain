
<br>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
     <td height="18" class="bgbrownL"><span class="mediumblackb">&nbsp;Detailed Profile Stats</span></td>
    </tr>
   </table>
 
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr class="bggrey">
     <td><img src="/P/imagesnew/zero.gif" width="8" height="20"></td>
     <td width="42%" class="mediumgreybl">Members who contacted you </td>
     <td width="8%" class="mediumgreybl">~$details["CONTACTS"]["TOTAL_RECEIVED"]` </td>
     <td rowspan="4" bgcolor="#666666" ><img src="/P/imagesnew/zero.gif" width="1" height="1"></td>
     <td><img src="/P/imagesnew/zero.gif" width="8" height="1"></td>
     <td width="42%" class="mediumgreybl">Members you contacted </td>
     <td width="8%" class="mediumgreybl">~$details["CONTACTS"]["TOTAL_SENT"]` </td>
    </tr>
    <tr class="bggreyl">
     <td ><img src="/P/imagesnew/zero.gif" width="8" height="20"></td>
     <td class="mediumblack"><span class= "blacklinku">~if $company`<a href="~$SITE_URL`/infovision/contacts_made_received.php?self_profileid=~$profileid`&flag=I&type=R&company=~$company`&cid=~$cid`" target="_blank">~else`<a href="~$SITE_URL`/crm/contacts_made_received.php?checksum=~$CHECKSUM`&flag=I&type=R&company=~$company`" target="_blank">~/if` Awaiting Response </a></span><BR>&nbsp;<img src="/P/imagesnew/open.gif" alt="Contact Member">~if $details["CONTACTS"]["AWAITING_RESPONSE_NEW"]`<span class="mediumred">&nbsp;(~$details["CONTACTS"]["AWAITING_RESPONSE_NEW"]`) new</span>~/if` <BR> Awaiting Response (Filtered)
     </td>
     <td class="mediumblackb">~$details["CONTACTS"]["AWAITING_RESPONSE"]` <BR>~$details["CONTACTS"]["AWAITING_RESPONSE_NEW"]` <BR> ~$details["CONTACTS"]["FILTERED"]`</td>
     <td>&nbsp;</td>
     <td class="mediumblack"><span class= "blacklinku">~if $company`<a href="~$SITE_URL`/infovision/contacts_made_received.php?self_profileid=~$profileid`&flag=I&type=M&company=~$company`&cid=~$cid`" target="_blank">~else`<a href="~$SITE_URL`/crm/contacts_made_received.php?checksum=~$CHECKSUM`&flag=I&type=M&company=~$company`" target="_blank">~/if`Of which no response </a></span>&nbsp;<img src="/P/imagesnew/open.gif" alt="Contact Member"><BR><BR> Viewed: ~$details["CONTACTS"]["VIEWED"]` &nbsp;&nbsp;&nbsp; Not viewed:~$details["CONTACTS"]["NOT_OPEN"]` </td>
     <td class="mediumblackb">~$details["CONTACTS"]["NOT_REP"]` </td>
    </tr>
    <tr class="bggrey">
     <td class="mediumblack"><img src="/P/imagesnew/zero.gif" width="8" height="20"></td>
     <td class="mediumblack"><span class= "blacklinku">~if $company`<a href="~$SITE_URL`/infovision/contacts_made_received.php?self_profileid=~$profileid`&flag=A&type=R&company=~$company`&cid=~$cid`" target="_blank">~else`<a href="~$SITE_URL`/crm/contacts_made_received.php?checksum=~$CHECKSUM`&flag=A&type=R&company=~$company`" target="_blank">~/if`Of which you accepted </a></span>&nbsp;<img src="/P/imagesnew/ac.gif" alt="Contact Accepted"></td>
     <td class="mediumblackb">~$details["CONTACTS"]["ACC_BY_ME"]` </td>
     <td >&nbsp;</td>
     <td class="mediumblack"><span class= "blacklinku">~if !$company`<a href="~$SITE_URL`/crm/contacts_made_received.php?checksum=~$CHECKSUM`&flag=A&type=M&company=~$company`" target="_blank">~else`<a href="~$SITE_URL`/infovision/contacts_made_received.php?self_profileid=~$profileid`&flag=A&type=M&company=~$company`&cid=~$cid`" target="_blank">~/if`Of which accepted </a></span>&nbsp;<img src="/P/imagesnew/ac.gif" alt="Contact Accepted"></td>
     <td class="mediumblackb">~$details["CONTACTS"]["ACC_ME"]` </td>
    </tr>
    <tr class="bggreyl">
     <td><img src="/P/imagesnew/zero.gif" width="8" height="20"></td>
     <td class="mediumblack"><span class= "blacklinku">~if !$company`<a href="~$SITE_URL`/crm/contacts_made_received.php?checksum=~$CHECKSUM`&flag=D&type=R&company=~$company`" target="_blank">~else` <a href="~$SITE_URL`/infovision/contacts_made_received.php?self_profileid=~$profileid`&flag=D&type=R&company=~$company`&cid=~$cid`" target="_blank">~/if`Of which you declined </a></span>&nbsp;<img src="/P/imagesnew/rj.gif" alt=""></td>
     <td class="mediumblackb">~$details["CONTACTS"]["DEC_BY_ME"]` </td>
     <td>&nbsp;</td>
     <td class="mediumblack"><span class= "blacklinku">~if !$company`<a href="~$SITE_URL`/crm/contacts_made_received.php?checksum=~$CHECKSUM`&flag=D&type=M&company=~$company`" target="_blank">~else`<a href="~$SITE_URL`/infovision/contacts_made_received.php?self_profileid=~$profileid`&flag=D&type=M&company=~$company`&cid=~$cid`" target="_blank"> ~/if`Of which declined </a></span>&nbsp;<img src="/P/imagesnew/rj.gif" alt=""></td>
     <td class="mediumblackb">~$details["CONTACTS"]["DEC_ME"]` </td>
    </tr>
    <tr class="bggrey">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Profile Length</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~$details["PROFILE_LENGTH"]`</td>
    </tr>
    <tr class="bggreyl">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Login Frequency</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~$details["LOGIN_FREQ"]`</td>
    </tr>
    <tr class="bggrey">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Contacts Viewed Frequency</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~$details["CONTACT_VIEWED_FREQ"]`</td>
    </tr>
<!-- New data records -->
<!--
    <tr class="bggreyl">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Free members contacted by you</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~$details["CONTACTS"]["FREE_CONTACTED_BY_ME"]`</td>
    </tr>

    <tr class="bggrey">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Free members accepted by you</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8"    height="20">~$details["CONTACTS"]["FREE_CONTACTED_ME"]`</td>
    </tr>
-->
    <tr class="bggreyl">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Total Acceptances</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~$details["CONTACTS"]["TOTAL_ACC"]`</td>
    </tr>

    <tr class="bggrey">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Mobile Usage in the last one month</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~$details["MOBILE_USAGE"]`</td>
    </tr>

    <tr class="bggreyl">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Total EOIs</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~$details["CONTACTS"]["TOTAL_EOI"]`</td>
    </tr>
    <tr class="bggrey">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">EOIs sent to you versus your profile viewed</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~if $details["EOI_RATIO"]` ~$details["EOI_RATIO"]`% ~else` 0 ~/if`</td>
    </tr>
<!--
    <tr class="bggreyl">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">EOIs sent through auto-apply </td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~$eoiSent_autoApply`</td>
    </tr>

    <tr class="bggrey">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">EOIs received through auto-apply</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~$eoiReceived_autoApply`</td>
    </tr>
-->
    <tr class="bggreyl">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Discount Applicable</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~if $details["VARIABLE_DISCOUNT"]` ~$details["VARIABLE_DISCOUNT"]` ~else` 0 ~/if`</td>
    </tr>

    <tr class="bggrey">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Discount valid till</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~if $details["VD_EXPIRY"]` ~$details["VD_EXPIRY"]`  ~/if` </td>
    </tr>

    <tr class="bggreyl">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Last Discount Applicable</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~if $details["LAST_VD_APPLICABLE"]` ~$details["LAST_VD_APPLICABLE"]`% ~else` 0 ~/if`</td>
    </tr>

    <tr class="bggrey">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Last Discount valid till</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~if $details["LAST_VD_EXPIRY"]` ~$details["LAST_VD_EXPIRY"]`  ~/if` </td>
    </tr>

    <tr class="bggreyl">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Renewal Discount Applicable</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~if $details["RENEWAL_DISCOUNT"]` ~$details["RENEWAL_DISCOUNT"]`% ~else` 0 ~/if`</td>
    </tr>

    <tr class="bggreyl">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Photo Request Received</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~$details["CONTACTS"]["PHOTO_REQUEST_COUNT"]`</td>
    </tr>

    <tr class="bggrey">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Horoscope Request Received</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~$details["CONTACTS"]["HOROSCOPE_REQUEST_COUNT"]`</td>
    </tr>

    <tr class="bggreyl">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Photo uploaded count</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~$details["ALBUM_COUNT"]`</td>
    </tr>

    <tr class="bggrey">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Phone verification status</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">
       ~$details["MOB_STATUS"]`
     </td>
    </tr>

    <tr class="bggreyl">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Email status</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">
    ~$details["EMAIL_STATUS"]`
     </td>
    </tr>

    <tr class="bggrey">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Address verification status</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">
    ~$details["ADDRESS_STATUS"]`
     </td>
    </tr>
    <tr class="bggrey">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Messenger ID</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">
    ~$details["MESSENGER_ID"]`
     </td>
    </tr>

<!--
~if $sugar_username`
    <tr class="bggreyl">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Profile handler name</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~$sugar_username`</td>
    </tr>
~/if`
-->

<!-- New data records Ends -->

~if $details["show_score"] eq 1`
    <tr class="bggreyl">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Profile source</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~$details["SOURCE"]`</td>
    </tr>
    <tr class="bggrey">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">User Score</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~$details["SCORE"]`</td>
    </tr>
~/if`
~if $details["an_show_score"] eq 1`
    <tr class="bggreyl">
     <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20">Analytics Score</td>
     <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20">~$details["ANALYTIC_SCORE"]`</td>
    </tr>
~/if`
     <tr class="bggrey" width="100%">
      <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20"/>Eligible for Free Response Booster</td>
      <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20"/><font color="red" >~$details["RB_ELIGIBILITY_FLAG"]`</font></td>
     </tr>
~if $details["REMAINING_CONTACT"]`
     <tr class="bggreyl" width="100%">
      <td class="mediumblack" colspan="4"><img src="/P/imagesnew/zero.gif" width="8" height="20"/>Number of Direct Contacts Remaining</td>
      <td class="mediumblackb" colspan="3"><img src="/P/imagesnew/zero.gif" width="8" height="20"/>~$details["REMAINING_CONTACT"]`</font></td>
     </tr>
~/if`


        <!--
        <a href=# onClick=\"window.open('~$SITE_URL`/crm/extraDetails_profile.php?cid=~$cid`&table_name=~$table_name`&paid_str=~$paid_str`&checksum=~$CHECKSUM`','','fullscreen=1,resizable=1,scrollbars=1');\">Exta Info of the profile Stats (Click here)</a>
        -->

   </table>

<br>
</td>
 </tr>
</table>
