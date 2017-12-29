<html>
<head>
    <title>Jeevansathi.com - MIS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" type="text/css">
    <head>
        <style>
            ul {
                list-style-type: none;
                margin: 0;
                padding: 0;
                overflow: hidden;
            }

            li{
                width: auto;
                padding-right: 10%;
                padding-left: 10%;
                float: left;
            }
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
                <a href="~sfConfig::get('app_site_url')`/operations.php/crmMis/screenRBInterestMISSelectDates?cid=~$cid`&name=~$name`">
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
                Client Info - ~$clientID`
            </font>
        </td>
    </tr>
</table>
<br>
<h1 align="center">Total RB generated</h1><br>
<table width=100% align=center border=0 cellspacing=2 cellpadding=5>
    <tr class="formhead" align="center">
        <td colspan="2">
            Sent
        </td>
        <td colspan="2">
            ~$sent`
        </td>
    </tr>

    <tr class="formhead" align="center">
        <td colspan="2">
            Skipped
        </td>
        <td colspan="2">
            ~$skipped`
        </td>
    </tr>

    <tr class="formhead" align="center">
        <td colspan="2">
            Discarded
        </td>
        <td colspan="2">
            ~$discarded`
        </td>
    </tr>

    <tr class="formhead" align="center">
        <td colspan="2">
            Not Sent
        </td>
        <td colspan="2">
            ~$notSent`
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <table width=100% align=center border=0 cellpadding=5 style="margin-top: 50px">
                <tr class="formhead" align="center">
                    <td colspan="2">
                        (Matching pool - filters)
                    </td>
                    <td colspan="2">
                        ~$dppCount`
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>