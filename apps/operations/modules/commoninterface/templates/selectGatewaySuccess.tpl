~include_partial('global/header')`
<form action="~sfConfig::get('app_site_url')`/operations.php/commoninterface/selectGateway" method="POST">
    <input type=hidden name="cid" value="~$cid`">
    <input type=hidden name="name" value="~$name`">
    <table width=760 align="CENTER" >
        <tr class="formhead" align="CENTER">
            <td colspan=3>Select Gateway Redirection</td>
        </tr>
        ~if $message`
            <tr class="formhead" align = "CENTER">
                <td colspan="2">
                    ~$message`
                </td>
            </tr>
        ~/if`
            <tr class="fieldsnew" align="CENTER" style="font-size: 14px">
                <td width=50%>
                    Default
                </td>
                <td>
                    <input type="radio" name="payment" ~if $preSelectedGateway eq 'default'` checked ~/if` value="default">
                </td>
            </tr>
            <tr class="fieldsnew" align="CENTER" style="font-size: 14px">
                <td>
                    Payu
                </td>
                <td>
                    <input type="radio" name="payment" ~if $preSelectedGateway eq 'payu'` checked ~/if` value="payu">
                </td>
            </tr>
            <tr class="fieldsnew" align="CENTER" style="font-size: 14px">
                <td>
                    CCAvenue
                </td>
                <td>
                    <input type="radio" name="payment" ~if $preSelectedGateway eq 'ccavenue'` checked ~/if` value="ccavenue">
                </td>
            </tr>
            <tr align="CENTER">
                <td colspan="2">
                    <input type="submit" name="gatewaySubmit" value="Select Gateway">
                </td>
            </tr>
       </table>
</form>
~include_partial('global/footer')`