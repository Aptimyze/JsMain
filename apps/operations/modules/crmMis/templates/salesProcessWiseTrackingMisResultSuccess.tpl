<html>
    <head>
        <title>Jeevansathi.com - MIS</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
        <style>
        DIV {position: relative; top: 45px; right:25px; color:yellow; visibility:hidden}
        </style>
    </head>
    <body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <table width="100%" align="center">
            <tr>
                <td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="63" usemap="#Map" border="0">
                </td>
            </tr>
            <tr class="formhead" align="center">
                <td colspan="2">
                    <font size=2>
                        <a href="~sfConfig::get('app_site_url')`/operations.php/crmMis/salesProcessWiseTrackingMis?cid=~$cid`&name=~$name`">
                            Click To Select Different Range
                        </a>
                    </font>
                </td>
            </tr>
            <tr class="formhead" align="center">
                <td colspan="2">
                    <font size=2>
                        <a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?cid=~$cid`">
                            MainPage
                        </a>
                    </font>
                </td>
            </tr>
            <tr class="formhead" align="center">
                <td colspan="2" style="background-color:lightblue">
                    <font size=3>
                        Sales Process-wise tracking MIS
                    </font>
                </td>
            </tr>
            <tr class="formhead" align="center">
                <td colspan="2" style="background-color:PeachPuff">
                    <font size=2>
                        Selected date range : ~$range`
                    </font>
                </td>
            </tr>
        </table>
        <br>
        <table width=100% align=center border=0 cellspacing=2 cellpadding=5>
            ~if $error`
            <tr>
                <td width=100% align=center style="background-color:LightSalmon">~$error`</td>
            </tr>
            ~else if $noData`
            <tr>
                <td width=100% align=center style="background-color:LightSalmon">~$noData`</td>
            </tr>
            ~else`
            <tr class=formhead style="background-color:LightSteelBlue">
                <td align=center style="background-color:LightSalmon">Process</td>
                ~if $headCountArr`
                    <td align=center >HC</td>
                ~/if`
                ~foreach from=$labelArr key=k item=val`
                    <td align=center>~$val`</td>
                ~/foreach`
                <td align=center>Total</td>
            </tr>
            ~foreach from=$data key=k item=v`
            <tr class=formhead>
                <td align=center>~$processArray[$k]`</td>
                ~if $headCountArr`
                    <td align=center>~$headCountArr[$k]`</td>
                ~/if`
                    ~foreach from=$v key=kk item=val`
                        <td align=center style="font-weight: normal">~if $val`~$val`~else`0~/if`</td>
                    ~/foreach`
                    <td align="center">~if $total[$k]`~$total[$k]`~else`0~/if`</td>
            <tr>
            ~/foreach`
            ~/if`
        </table>
    </body>
</html>