~include_partial('global/header')`
<form action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/welcomeDiscount" method="POST">
    <input type=hidden name="cid" value="~$cid`">
    <input type=hidden name="name" value="~$name`">
    <table width=760 align="CENTER" >
        <tr class="formhead" align="CENTER">
            <td colspan=3>Select Welcome Discount Values</td>
        </tr>
        ~if $message`
            <tr class="formhead" align = "CENTER">
                <td colspan="2" ~if $error` style="color:red" ~/if`>
                    ~$message`
                </td>
            </tr>
        ~/if`
        ~foreach from=$data key=catId item=v name=categoryLoop`
            <tr class="fieldsnew" align="CENTER" style="font-size: 14px">
                <td width=50%>
                    ~foreach from=$v.NAME key=index item=val name=nameLoop`
                        ~$val`<br>
                    ~/foreach`
                </td>
                <td>
                    <input type="text" name="~$catId`" value="~$v.DISCOUNT`">
                </td>
            </tr>
        ~/foreach`
            <tr align="CENTER">
                <td colspan="2">
                    <input type="submit" name="submit" value="Submit">
                </td>
            </tr>
       </table>
</form>
~include_partial('global/footer')`