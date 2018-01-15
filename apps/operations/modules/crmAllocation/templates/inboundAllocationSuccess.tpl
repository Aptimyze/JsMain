<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>JeevanSathi</title>
    </meta>
  </head>
  ~include_partial('global/header')`
  <br>
  <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <table width=100% cellspacing="1" cellpadding='0' ALIGN="CENTER" >
      <tr width=100% border=1>
        <td width="25%" class="formhead" align="center">Inbound Module</td>
      </tr>
    </table>
    ~if $allocatedSuccessfully`
    <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
      <table width=760 align="CENTER" >
        <tr>
          <td height="23" class="formhead" align="center">
            ~if $exceededAllocationCount neq ''`
            <font color="red">
            You have exceeded your allocation limit of ~$allocationLimit`. You have allocated ~$exceededAllocationCount` extra profiles.<br>
            </font>
            ~else`
            Entry for <font color="blue">~$username`</font> is done<br><br>
            ~/if`
            <a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/inboundAllocation?name=~$agentName`&cid=~$cid`&mode=~$mode`">Continue >></a>
            <br>
            <a href="~sfConfig::get('app_site_url')`/billing/entryfrm.php?cid=~$cid`&profileid=~$profileid`&username=~$username`&source='I'">Enter Billing Details >></a>
          </td>
          <tr>
          </table>
          ~else`
          <form name=insertForm method="post" action="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/inboundAllocation">
            <input type=hidden name=cid value="~$cid`">
            <input type=hidden name=name value="~$agentName`">
            <input type=hidden name=showDetail value="~$showDetail`">
            ~if $details.WAS_PAID`
            <table width=760 align="CENTER">
              <tr>
                <td>
                  <center class=red>This member has some payment history</center>
                  <center class=label><a href="~sfConfig::get('app_site_url')`/billing/search_user.php?cid=~$cid`&phrase=~$username`&criteria=uname" target="_blank">Click here to see the billing details of this member</a></center>
                  <br>
                </td>
              </tr>
            </table>
            ~/if`
            ~if $errorCriteria || $errorPaid`
            <table width=80% align="center" cellspacing=2 cellpadding=1 border=0>
              <tr>
                <td class=label align="center" width=30%>
                  <font color="red">
                  ~if $errorCriteria`
                  Profile Out Of Region
                  ~elseif $errorPaid`
                  Inbound allocation for this profile is not permitted. Please email your supervisor to get the allocation
                  ~/if`
                  </font>
                </td>
              </tr>
            </table>
            ~/if`
            <table width=80% align="center" cellspacing=2 cellpadding=1 border=0>
              <tr>
                <td class=label align="left" width=30%>~if $errorUsername eq 'NO_USERNAME' || ($error eq 'Y' && $username eq '')`<font color="red"> Username* </font> ~else` Username* ~/if`
                  ~if $errorUsername eq 'WRONG_USERNAME'`
                  <font color="red">
                  Username does not exist
                  </font>
                  ~/if`
                </td>
                <td class=fieldsnew width=70%><input type="text" name="username" value="~$username`" class="textbox"></td>
                <td class=label align="left" width=30%>
                  <input type="submit" name="submit" value="Get History">
                </td>
              </tr>
              <tr>
                <td class=label align="left" width=30%>~if $errorEmail eq 'WRONG_EMAIL' || ($error eq 'Y' && $email eq '')`<font color="red"> Email*</font> ~else` Email* ~/if`</td>
                <td class=fieldsnew width=70%><input type="text" name="email" value="~if $email`~$email`~else`~$details.EMAIL`~/if`" class="textbox"></td>
              </tr>
              <tr>
                <td width=30% class=label>Phone No. (Residence) </td>
                <td class=fieldsnew><input type="text" name="resPhone" value="~if $resPhone`~$resPhone`~else`~$details.PHONE_WITH_STD`~/if`" class="textbox"></td>
              </tr>
              <tr>
                <td class=label width=30%>Phone No. (Mobile)</td>
                <td class=fieldsnew><input type="text" name="mobPhone" value="~if $mobPhone`~$mobPhone`~else`~$details.PHONE_MOB`~/if`" class="textbox"></td>
              </tr>
              <tr>
                <td class=label width=30%>Phone No. (Mobile 2)</td>
                <td class=fieldsnew><input type="text" name="mobAltPhone" value="~if $mobAltPhone`~$mobAltPhone`~else`~$details.ALT_MOB`~/if`" class="textbox"></td>
              </tr>
              <tr>
                <td class=label width="30%">CRM FollowUp No.</td>
                <td class=fieldsnew><input type="text" name="alternatePhone" value="~if $alternatePhone`~$alternatePhone`~else`~$details.ALTERNATE_NO`~/if`" class="textbox"></td>
              </tr>
              <tr>
                <td class=label width="30%">Discount Negotiation Percentage(%)</td>
                <td class=fieldsnew><input type="text" name="discountNegVal" value="" class="textbox discountNegVal"></td>
              </tr>
              <tr>
                <td class=label>
                  ~if $error eq 'Y' && !$follow_date`<font color="red">Follow Up Time*</font>~else`Follow Up Time*~/if`
                </td>
                <td class=fieldsnew>
                  <select name="follow_date" class="textbox">
                    ~$followupDate.follow_time|decodevar`
                  </select> at Hrs.
                  <select name="follow_hour" class="textbox">
                    ~$followupDate.hour|decodevar`
                  </select> Min.
                  <select name="follow_min" class="textbox">
                    ~$followupDate.min|decodevar`
                  </select>
                </td>
              </tr>
              <tr>
                <td class=label align="left" width=30%>
                  ~if $error eq 'Y' && $call_source eq ''`<font color="red">Call Source*</font>~else`Call Source*~/if`
                </td>
                <td class=fieldsnew>
                  <select name="call_source">
                    <option value="">Select</option>
                    ~foreach from=$callSource item=callSourceVal key=callSourceKey`
                    <option value="~$callSourceVal.value`" ~if $callSourceVal.value eq $call_source` selected ~/if`>~$callSourceVal.name`</option>
                    ~/foreach`
                  </select>
                </td>
              </tr>
              <tr>
                <td class=label align="left" width=30%>
                  ~if $error eq 'Y' && $query_type eq ''`<font color="red">Query Type*</font>~else`Query Type*~/if`
                </td>
                <td class=fieldsnew>
                  <select name="query_type">
                    <option value="">Select</option>
                    ~foreach from=$queryType item=queryTypeVal key=queryTypeKey`
                    <option value="~$queryTypeVal.value`" ~if $queryTypeVal.value eq $query_type` selected ~/if`>~$queryTypeVal.name`</option>
                    ~/foreach`
                  </select>
                </td>
              </tr>
              <tr>
                <td class=label width="30%">
                  ~if $error eq 'Y' && $willPayVal eq ''`<font color="red"> Disposition*</font>~else` Disposition*~/if`
                </td>
                <td class=fieldsnew>
                  <select name="willPay" class="textbox" onChange="PopSPEC(this,'willPay','reason');">
                    <option value="" selected>Select any one option</option>
                    ~$willPay|decodevar`
                  </select>
                  &nbsp;&nbsp;
                  ~if $error eq 'Y' && $reason eq ''`<font color="red">Validation*</font>~else`Validation*~/if`
                  <select name="reason" class="textbox">
                    <option  value="" selected>Select any One Option</option>
                    ~$reason`
                  </select>
                </td>
              </tr>
              <tr>
                <td class=label align="left" width=30%>~if $error eq 'Y' && $comments eq ''`<font color="red"> Comments*</font> ~else` Comments* ~/if`</td>
                <td class=fieldsnew height="26">
                  <textarea name="comments" value="~$comments`"  class="textbox" cols="55" rows="3" >~$comments`</textarea>
                </td>
              </tr>
              <tr>
                <td class=label width="30%" height="2">&nbsp;</td>
                <td colspan="2" height="2" class=fieldsnew> &nbsp;
                  <input type="submit" name="submit" value="submit">
                </td>
              </tr>
              <tr>
                <td colspan=2 align=center><br><hr></td>
              </tr>
              <tr>
                ~if $profileid`
                <td class=label width="30%" height="2">&nbsp;</td>
                <td class=label align="right"><a href="#" onclick="openBrWindow('~sfConfig::get('app_site_url')`/crm/do_not_call.php?username=~$username`&profileid=~$profileid`&cid=~$cid`','','width=200,height=200,scrollbars=yes'); return false;">Remove this profile from calling FOREVER</a></td>
              </tr>
              ~/if`
              <tr>
                <table width=100% align="CENTER" class="fieldsnew">
                  ~if $showDetail`
                  <tr align="CENTER">
                    <td class="label" colspan="4" height="23"><font size="2" color="black">This profile is currently allocated to:<b> ~$details.ALLOTED_TO`</b></font></td>
                  </tr>
                  ~/if`
                  ~if $history.show_IM eq 'Y'`
                  <tr align="CENTER" bgcolor="#fgfgfg">
                    <td class="formhead" colspan="4" height="23"><b><font size="5" color="green">Incentive Multiplier : ~$history.IM`</font></b></td>
                  </tr>
                  ~/if`
                  <tr align="CENTER">
                    <td class="formhead" colspan="4" height="23"><b><font size="3" color="black">History</font></b></td>
                  </tr>
                  <tr align="CENTER">
                    <td class="label" width=5% height="20"><b>S.No.</b></td>
                    <td class="label" width="15" height="20"><b>Handled By</b></td>
                    <td class="label" width=20% height="21"><b>Date</b></td>
                    <td class="label" width=15% height="21"><b>Mode</b></td>
                  </tr>
                  ~foreach from=$history item=historyRow key=k`
                  <tr align="CENTER" bgcolor="#fbfbfb" >
                    <td height="20" align="CENTER" width="5%">~$historyRow.SNO`</td>
                    <td height="20" width="15%">~$historyRow.NAME`</td>
                    <td height="21" width="20%">~$historyRow.DATE`</td>
                    <td height="21" width="15%" align="LEFT">~$historyRow.MODE`</td>
                  </tr>
                  ~if $historyRow.COMMENTS neq ''`
                  <tr bgcolor="#fgfgfg" >
                    <td height="21" align="CENTER" width="5%">&nbsp;</td>
                    <td width=20% height="21"><b>Comments</b></td>
                    <td height="21" colspan="4">~$historyRow.COMMENTS`</td>
                  </tr>
                  ~/if`
                  <tr class="label">
                    <td height="21" align="CENTER" width="5%" colspan=7>&nbsp;</td>
                  </tr>
                  ~/foreach`
                </table>
              </tr>
              <tr>
                <td colspan=2 align=center><br><hr></td>
              </tr>
              <tr>
                <td colspan=2 align=center>~$pmsg|decodevar`<td>
                </tr>
              </table>
            </form>
            ~if $error eq 'Y' && $willPayVal neq ''`
            <script>
            PopSPEC(this,"willPay","reason");
            </script>
            ~/if`
            ~/if`
            <br><br>
            ~if $profileid && $isAlloted`
            <table width=100% align="center">
              <tr class=fieldsnew>
                <td align=center><a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/crmSmsFunctionalityInterface?cid=~$cid`&profileid=~$profileid`&username=~$username`" target="_blank">Send SMS to this profile user</a><br><br></td>
              </tr>
            </table>
            ~/if`
            ~include_partial('global/footer')`
          </body>
          <script type="text/javascript">
              $(document).ready(function(){
                $(".discountNegVal").bind('focusout', function(){
                  var floatVal = /^\s*(\+|-)?((\d+(\d+)?)|(\d+))\s*$/;
                  var value  = $(this).val();
                  if(floatVal.test(value)){
                    if(value > 100 || value <= 0){
                      alert("Discount cannot be greater than 100%! or less than/equal to 0 & should contain decimal number");
                      $(this).val('');
                    }
                  } else if (value == '') {
                    $(this).val('');
                  } else {
                    alert('Please enter a numeric value!');
                    $(this).val('');
                  }
                });
                $(".discountNegVal").bind('focusin', function() {
                  $(window).keydown(function(event){
                    if(event.keyCode == 13) {
                      event.preventDefault();
                      return false;
                    }
                  });
                });
              });
            </script>
        </html>