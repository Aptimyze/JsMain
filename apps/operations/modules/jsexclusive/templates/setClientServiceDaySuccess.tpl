
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
                    <option value="MON">Monday</option>
                    <option value="TUE">Tuesday</option>
                    <option value="WED">Wednesday</option>
                    <option value="THU">Thursday</option>
                    <option value="FRI">Friday</option>
                    <option value="SAT">Saturday</option>
                    <option value="SUN">Sunday</option>
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
        <tr>
            <td><br><br>
                **Note: You will not be able to change this after leaving this page.
            </td>
        </tr>
    </table>
   
</form>
</tr>
</table>


~include_partial('global/footer')`

