
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/profile/images/styles.css" type="text/css">




~include_partial('global/header',["showExclusiveServicingBack"=>'Y'])`
<br><br>
<table width="70%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr class="formhead" align="center" width="100%">
        <td colspan="3"  height="30">
            <font size=3>CLIENT SERVICE PAGE</font>
        </td>
    </tr>
    ~if $notFound neq true`
    ~assign var=num value=1`
    <tr>
        <td>
            ~$num++`.&nbsp;&nbsp;&nbsp;&nbsp;<a href="/operations.php/commoninterface/ShowProfileStats?cid=~$cid`&profileid=~$client`" target="_blank">Client Profile Data</a>
        </td>
    </tr><br>
    <tr>
        <td>
            ~$num++`.&nbsp;&nbsp;&nbsp;&nbsp;<a href="/operations.php/jsexclusive/uploadBiodata?client=~$client`">Upload Client Bio data</a>
        </td>
    </tr><br>
    <tr>
        <td>
            ~$num++`.&nbsp;&nbsp;&nbsp;&nbsp;<a href="/operations.php/jsexclusive/setClientServiceDay?client=~$client`">Set Client Service Day</a>
        </td>
        
    </tr><br>
    <tr>
        <td>
            ~$num++`.&nbsp;&nbsp;&nbsp;&nbsp;<a href="/profile/dpp?allowLoginfromBackend=1&profileChecksum=~$profileChecksum`&checksum=~$profileChecksum`&cid=~$cid`" target="_blank">Set DPP</a>
            
        </td>
    </tr><br>
    <tr>
        <td>
            ~$num++`.&nbsp;&nbsp;&nbsp;&nbsp;<a href="/operations.php/jsexclusive/welcomeCallsPage2?client=~$client`">Fill Preferential DPP for RB ( Custome RB )</a>
        </td>
    </tr><br>
    ~else`
    <tr>
        <td>
            &nbsp;&nbsp;&nbsp;&nbsp;User is either invalid or not eligible for new exclusive handling
        </td>
    </tr><br>
    <tr>
        <td>
            &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onclick="location.href='/operations.php/jsexclusive/menu'" value="Back">
        </td>
    </tr><br>
    ~/if`
</table>


~include_partial('global/footer')`

