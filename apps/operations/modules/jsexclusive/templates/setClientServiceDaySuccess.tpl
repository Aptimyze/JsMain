
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/profile/images/styles.css" type="text/css">




~include_partial('global/header',["showExclusiveServicingBack"=>'Y'])`
<br><br>
<br><br>
<table width="70%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr class="formhead" align="center" width="100%">
        <td colspan="3"  height="30">
            <font size=3>CLIENT SERVICE DAY SELECTION</font>
        </td>
    </tr>
   
    <tr>
    <form name="setserviceday" method="post" action="~sfConfig::get('app_site_url')`/operations.php/jsexclusive/setClientServiceDay?client=~$client`" enctype="multipart/form-data">
    <table width="50%" border="0" align="center" cellpadding="4" cellspacing="4">
        ~if $serviceDay neq 'NA' AND $serviceDay neq ''`
        <tr><td>Service Day Currently Selected: ~$serviceDay`</td></tr>
        <tr><td>Service Day Was last updated on: ~$serviceDaySetDate`</td></tr>
        ~/if`
        <tr class="fieldsnew">
            <td>
                Select Service Day:
            </td>
            <td>
                <select name="serviceDay" id="serviceDay">
                    <option value="NA">NA</option>
                    <option value="MON">Monday (~$dayWiseCountArr['MON']`)</option>
                    <option value="TUE">Tuesday (~$dayWiseCountArr['TUE']`)</option>
                    <option value="WED">Wednesday (~$dayWiseCountArr['WED']`)</option>
                    <option value="THU">Thursday (~$dayWiseCountArr['THU']`)</option>
                    <option value="FRI">Friday (~$dayWiseCountArr['FRI']`)</option>
                    <option value="SAT">Saturday (~$dayWiseCountArr['SAT']`)</option>
                    <option value="SUN">Sunday (~$dayWiseCountArr['SUN']`)</option>
                </select>
            </td>
            <td>
                ~if $serviceDay neq 'NA' AND $serviceDay neq ''`
                    <input type="submit" name="submit" value="Change Service Day">
                ~else`
                    <input type="submit" name="submit" value="Submit">
                ~/if`
            </td>
        </tr>
        <tr></tr><tr></tr>
    </table>
    <table width="50%" border="0" align="center" cellpadding="4" cellspacing="4">
        <tr>
            <td><br><br>
                NOTE:
                <br>
                **You will not be able to change this after leaving this page.<br>
                **The number in the parenthesis indicates clients already having their service day set as that day itself.
            </td>
        </tr>
    </table>
   
</form>
</tr>
</table>


~include_partial('global/footer')`

