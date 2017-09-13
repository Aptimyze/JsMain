
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
<link rel="stylesheet" href="~sfConfig::get('app_img_url')`/profile/images/styles.css" type="text/css">




~include_partial('global/header')`
<script type="text/javascript">
function passvalue(){
    var username = document.getElementById('searchUserName').value;
    var url1 = '/operations.php/jsexclusive/welcomeCallsPage2?from=search&username=';
    var url2 = url1 + username;
    location.href=url2;
}
    
</script>
<br><br>
~if $notFound eq 1`
    <script type="text/javascript">
        alert("Username is either invalid or not eligible for new Exclusive handling");
    </script>
~/if`
<table width="30%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr class="formhead" align="center" width="100%">
        <td colspan="3"  height="30">
            <font size=3>RM Interface</font>
        </td>
    </tr>
    ~assign var=num value=1`
    <tr>
        <td>
            ~$num++`.&nbsp;&nbsp;<a href="/operations.php/jsexclusive/welcomeCalls/">WELCOME CALLS DUE </a>
        </td>
        <td>
            &nbsp;&nbsp;~$welcomeCallsCount`
        </td>
    </tr>
    <br>
    <tr>
        <td>
            ~$num++`.&nbsp;&nbsp;<a href="/operations.php/jsexclusive/todaysClients">Today's Clients ( Ask for Followups )</a>
        </td>
        <td>
            &nbsp;&nbsp;~$todaysClientCount`
        </td>
    </tr>
    <br>
    <tr>
        <td>
            ~$num++`.&nbsp;&nbsp;<a href="/operations.php/jsexclusive/screenRBInterests?name=~$name`&cid=~$cid`">Screen RB interests </a>
        </td>
         <td>
            &nbsp;&nbsp;~$unscreenedClientsCount`
        </td>
    </tr>
    <br>
    <tr>
        <td>
            ~$num++`.&nbsp;&nbsp;<a href="/operations.php/jsexclusive/pendingConcalls?name=~$name`&cid=~$cid`">Pending Concalls</a>
        </td>
        <td>
            &nbsp;&nbsp;~$pendingConcallsCount`
        </td>
    </tr>

    <tr>
        <td>
            ~$num++`.&nbsp;&nbsp;<a href="/operations.php/jsexclusive/activeClientList">Active Client List</a>
        </td>
        <td>
            &nbsp;&nbsp;~$activeClientsCount`
        </td>
    </tr>
    
    <tr>
        <td>
            ~$num++`.&nbsp;&nbsp;Search Client: &nbsp;&nbsp;<input style="width:90px;"type="text" id="searchUserName" name="searchUserName"/>
        </td>
        <td>
           &nbsp;&nbsp;<input  type="button" onclick="passvalue();" value="Search">
        </td>
    </tr>
    
</table>


~include_partial('global/footer')`

