
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/profile/images/styles.css" type="text/css">




~include_partial('global/header',["showExclusiveServicingBack"=>'Y'])`
<br><br>
<table width="70%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr class="formhead" align="center" width="100%">
        <td colspan="3"  height="30">
            <font size=3>Select Client</font>
        </td>
    </tr>
    ~assign var=num value=1`
    ~foreach from=$todaysClientProfiles key=k item=v name=todaysClientProfiles`
    <tr>
        <td>
            ~$num++`.&nbsp;&nbsp;&nbsp;&nbsp;<a href="/operations.php/jsexclusive/welcomeCallsPage2?client=~$v.CLIENT_ID`">~$v.CLIENT_ID`</a>
        </td>
    </tr>
    <br>
    ~/foreach`
</table>


~include_partial('global/footer')`

