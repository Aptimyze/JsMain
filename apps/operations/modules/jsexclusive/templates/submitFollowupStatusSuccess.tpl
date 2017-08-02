<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
~include_partial('global/header')`
<br><br>
<table width="70%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr class="formhead" align="center" width="100%">
        <td colspan="3"  height="30">
            <font size=3>FOLLOWUP STATUS</font>
        </td>
    </tr>
    ~if $clientUsername`
    <tr class="formhead" align="center" width="100%">
        <td colspan="3"  height="30">
            <font size=3>Client:~$clientUsername`&nbsp;&nbsp; Member:~$memberUsername`</font>
        </td>
    </tr>
    ~/if`
    <tr></tr>
    <tr>
    <form name="followupStatusForm" method="post" action="~sfConfig::get('app_site_url')`/operations.php/jsexclusive/submitFollowupStatus" enctype="multipart/form-data">
        <table width="50%" border="0" align="center" cellpadding="4" cellspacing="4">
            <input type="hidden" name="ifollowUpId" value="~$ifollowUpId`">
            <input type="hidden" name="istatus" value="~$istatus`">
            <tr class="fieldsnew">
                <td>
                    Status:
                </td>
                <td>
                    <select name="followupStatus" id="followupStatus">
                        <option value="Y">Confirm</option>
                        <option value="N">Decline</option>
                        <option value="F">Followup</option>
                    </select>
                </td>
            </tr>
            <tr class="fieldsnew">
                <td>
                    Reason:
                </td>
                <td>
                    <select name="reason" id="reason" disabled class="crm-disabled">
                        <option value="">Select</option>
                        <option value="Will check profile">Will check profile</option>
                        <option value="Not decided yet">Not decided yet</option>
                        <option value="RNR/Switched off">RNR/Switched off</option>
                        <option value="Others">Others</option>
                    </select>
                </td>
            </tr>
            <tr class="fieldsnew">
                <td>
                    *Reason text:
                </td>
                <td>
                    <input type="text" maxlength="70" id="reasonText" name="reasonText" value="" class="crm-disabled" disabled>
                </td>
            </tr>
            <tr class="fieldsnew">
                <td class="label">
                    **Set date (Optional):
                </td>
                <td class="fieldsnew">
                    <select id="day" name="dayValue" class="crm-disabled" disabled>
                        ~foreach from=$dayArr item=dayVal`
                            <option value="~$dayVal.VALUE`" ~if $dayVal.VALUE eq $todayDay` selected="yes" ~/if`>~$dayVal.NAME`</option>
                        ~/foreach`
                    </select>
                    &nbsp;
                    <select id="month" name="monthValue" class="crm-disabled" disabled>
                        ~foreach from=$monthArr item=monthVal`
                            <option value="~$monthVal.VALUE`" ~if $monthVal.VALUE eq $todayMonth` selected="yes" ~/if`>~$monthVal.NAME`</option>
                        ~/foreach`
                    </select>
                    &nbsp;
                    <select id="year" name="yearValue" class="crm-disabled" disabled>
                        ~foreach from=$yearArr item=yearVal`
                            <option value="~$yearVal.VALUE`" ~if $yearVal.VALUE eq $todayYear` selected="yes" ~/if`>~$yearVal.NAME`</option>
                        ~/foreach`
                    </select>
                </td>
            </tr>
            <tr></tr>
            <tr align="center">
                <td class="label" style="background-color:PeachPuff">
                    <input type="submit" name="submit" value="Back to Followup page">
                </td>
                <td class="label" style="background-color:PeachPuff">
                    <input type="submit" name="submit" value="Submit" onclick="return validateFollowUp();">
                </td>
            </tr>
            <tr></tr><tr></tr>
        </table>
        <table width="50%" border="0" align="center" cellpadding="4" cellspacing="4">
            <tr>
                <td><br><br>
                    NOTE:
                    <br>
                    Click on submit button to set followup status.<br>
                    * Mention reason for followup-"Others" and decline cases<br>
                    ** Set date for future followup only<br>
                </td>
            </tr>
        </table>
    </form>
    </tr>
</table>
~include_partial('global/footer')`