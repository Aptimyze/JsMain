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
                RM wise RB Interests
            </font>
        </td>
    </tr>
</table>
<br>
<table width=100% align=center border=0 cellspacing=2 cellpadding=5>
    ~if $noData`
    <tr>
        <td width=100% align=center style="background-color:LightSalmon">~$noData`</td>
    </tr>
    ~else`
        <tr class=formhead style="background-color:LightSteelBlue">
            <ul>
                <li style="color:#0fff00;">Sent</li>
                <li style="color:#10a7ff;">Skipped</li>
                <li style="color:#ff171c;">Discarded</li>
                <li style="color:#bd27ff;">Not Sent</li>
            </ul>
        </tr>
        <tr></tr>
        <tr class=formhead style="background-color:LightSteelBlue">
            <td align=center style="background-color:LightSalmon">RM ID</td>
            ~foreach from=$daysArray key=k item=val`
                <td align=center>~$val`</td>
            ~/foreach`
            <td align=center>TOTAL</td>
        </tr>
        ~foreach from=$agentWiseDetails key=k item=v`
            <tr class=formhead>
                <td align=center>
                    ~if $k neq 'TOTAL'`
                    <a href="~sfConfig::get('app_site_url')`/operations.php/crmMis/screenRBInterestMISClientWiseData?cid=~$cid`&name=~$name`&agentID=~$k`&startDT=~$startDT`&endDT=~$endDT`&clients=~$agentWiseData[$k]`">
                        ~$k`
                    </a>
                    ~else`
                        ~$k`
                    ~/if`
                </td>
                ~foreach from=$daysArray key=kk item=val`
                    <td align=center style="font-weight: normal">
                        <table>
                            <tr>
                                <td style="color:#0fff00;">
                                    ~if $v[$val]`
                                        ~$v[$val]["Y"] + $v[$val]["P"]`
                                    ~else`
                                        0
                                    ~/if`
                                </td>
                            </tr>
                            <tr>
                                <td style="color:#10a7ff;">
                                    ~if $v[$val]`
                                        ~$v[$val]["S"]`
                                    ~else`
                                        0
                                    ~/if`
                                </td>
                            </tr>
                            <tr>
                                <td style="color:#ff171c;">
                                    ~if $v[$val]`
                                        ~$v[$val]["D"]`
                                    ~else`
                                        0
                                    ~/if`
                                </td>
                            </tr>
                            <tr>
                                <td style="color:#bd27ff;">
                                    ~if $v[$val]`
                                        ~$v[$val]["N"] + $v[$val]["E"]`
                                    ~else`
                                        0
                                    ~/if`
                                </td>
                            </tr>
                        </table>
                    </td>
                ~/foreach`
                <td align=center style="font-weight: normal">
                    <table>
                        <tr>
                            <td style="color:#0fff00;">
                                ~if $v["TOTAL"]`
                                    ~$v["TOTAL"]["Y"] + $v["TOTAL"]["P"]`
                                ~else`
                                    0
                                ~/if`
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#10a7ff;">
                                ~if $v["TOTAL"]`
                                    ~$v["TOTAL"]["S"]`
                                ~else`
                                    0
                                ~/if`
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#ff171c;">
                                ~if $v["TOTAL"]`
                                    ~$v["TOTAL"]["D"]`
                                ~else`
                                    0
                                ~/if`
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#bd27ff;">
                                ~if $v["TOTAL"]`
                                    ~$v["TOTAL"]["N"] + $v["TOTAL"]["E"]`
                                ~else`
                                    0
                                ~/if`
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        ~/foreach`
    ~/if`
</table>
</body>
</html>