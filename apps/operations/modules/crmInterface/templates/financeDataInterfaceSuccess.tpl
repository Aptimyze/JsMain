<html>
    <head>
        <title>
            Jeevansathi.com - MIS
        </title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
            <link href="~sfConfig::get('app_img_url')`/jsadmin/jeevansathi.css" rel="stylesheet" type="text/css">
                <script type="text/javascript">
                    $(function () {
				        var count = 0;
				        $('#date1').dateDropDowns({ dateFormat: 'DD-mm-yy',yearStart: "2014", yearEnd: "~$rangeYear`"});
				        $('#date2').dateDropDowns({ dateFormat: 'DD-mm-yy',yearStart: "2014", yearEnd: "~$rangeYear`"});
				        $('#date1_dateLists_day_list option:selected').prop('selected', false);
				        $('#date1_dateLists_day_list').on('click', function(){
				        	count = 1;
				        });
				        $('#date1_dateLists_month_list').on('click', function(){
				        	if(count != 1){
				        		$('#date1_dateLists_day_list option:selected').prop('selected', false);
				        	}
				        });
				    });
                </script>
            </link>
        </meta>
    </head>
    <body>
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="center" valign="top" width="40%">
                    <img border="0" height="63" src="~sfConfig::get('app_img_url')`/profile/images/logo_1.gif" usemap="#Map" width="209"/>
                </td>
            </tr>
        </table>
        ~if $showInitial eq 1`
        <form action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/financeDataInterface" method="post" name="form1">
            <input name="cid" type="hidden" value="~$cid`">
                <table align="center" border="0" cellpadding="4" cellspacing="4" width="60%">
                    <tr>
                        <td align="center" class="label">
                            <font size="2">
                                <a href="~sfConfig::get('app_site_url')`/mis/mainpage.php?name=~$agentName`&cid=~$cid`">
                                    Mainpage
                                </a>
                            </font>
                        </td>
                    </tr>
                    <tr>
                    </tr>
                    <tr align="center" class="formhead">
                        <td colspan="2" style="background-color:lightblue">
                            <font size="3">
                                Finance Data Interface
                            </font>
                        </td>
                    </tr>
                    <tr>
                    </tr>
                </table>
                ~if $errorMsg`
                <table align="center" border="0" cellpadding="4" cellspacing="4" width="60%">
                    <tr>
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                    </tr>
                    <tr align="center">
                        <td class="label">
                            <font size="2">
                                ~$errorMsg`
                            </font>
                        </td>
                    </tr>
                </table>
                ~else`
                <table align="center" border="0" cellpadding="4" cellspacing="4" width="60%">
                    <tr>
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                    </tr>
                    <tr align="center">
                        <td class="label">
                            <input checked="yes" name="range_format" type="radio" value="DMY"/>
                            <font size="2">
                                Select Reporting Date Range
                            </font>
                        </td>
                        <td class="fieldsnew">
                            <input id="date1" type="text" value="">
                                <b>
                                    To
                                </b>
                                <input id="date2" type="text" value="">
                                </input>
                            </input>
                        </td>
                    </tr>
                    <tr align="center">
                        <td class="label">
                            <font size="2">
                                Select Report Format
                            </font>
                        </td>
                        <td class="fieldsnew">
                            <input checked="" name="report_format" type="radio" value="HTML">
                                <font size="2">
                                    HTML Format
                                </font>
                            </input>
                            <br/>
                            <input name="report_format" type="radio" value="XLS">
                                <font size="2">
                                    Excel Format
                                </font>
                            </input>
                            <br>
                            </br>
                        </td>
                    </tr>
                    <tr align="center">
                        <td class="label">
                            <font size="2">
                                Select Report Device
                            </font>
                        </td>
                        <td class="fieldsnew">
                            <input checked="" name="device" type="radio" value="other">
                                <font size="2">
                                    All(Except Apple)
                                </font>
                            </input>
                            <br/>
                            <input name="device" type="radio" value="apple">
                                <font size="2">
                                    Apple
                                </font>
                            </input>
                            <br>
                            </br>
                        </td>
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                    </tr>
                    <tr align="center">
                        <td class="label" colspan="2" style="background-color:Moccasin">
                            <input name="submit" type="submit" value="SUBMIT">
                            </input>
                        </td>
                    </tr>
                </table>
                ~/if`
            </input>
        </form>
        ~else if $showData eq 1`
        <table width=100%>
            <tr class=formhead style="background-color:LightSteelBlue; line-height: 20px; font-size: 15px;">
                <td align=center>Entry Date</td>
                <td align=center>Billid</td>
                <td align=center>Receiptis</td>
                <td align=center>Profileid</td>
                <td align=center>Username</td>
                <td align=center>Serviceid</td>
                <td align=center>Service Name</td>
                <td align=center>Start Date</td>
                <td align=center>End Date</td>
                <td align=center>Currency</td>
                <td align=center>List Price</td>
                <td align=center>Amount</td>
                <td align=center>Deferrable Flag</td>
                <td align=center>ASSD(Actual Service Start Date)</td>
                <td align=center>ASED(Actual Service End Date)</td>
                <td align=center>Invoice No</td>
            </tr>
            ~foreach from=$rawData item=profileArr`
            <tr style="background-color:Moccasin">
                <td align=center><font color="#000">~$profileArr.ENTRY_DT`</font></td>
                <td align=center><font color="#000">~$profileArr.BILLID`</font></td>
                <td align=center><font color="#000">~$profileArr.RECEIPTID`</font></td>
                <td align=center><font color="#000">~$profileArr.PROFILEID`</font></td>
                <td align=center><font color="#000">~$profileArr.USERNAME`</font></td>
                <td align=center><font color="#000">~$profileArr.SERVICEID`</font></td>
                <td align=center><font color="#000">~$serviceData[$profileArr.SERVICEID]`</font></td>
                <td align=center><font color="#000">~$profileArr.START_DATE`</font></td>
                <td align=center><font color="#000">~$profileArr.END_DATE`</font></td>
                <td align=center><font color="#000">~$profileArr.CUR_TYPE`</font></td>
                <td align=center><font color="#000">~$profileArr.PRICE`</font></td>
                <td align=center><font color="#000">~$profileArr.AMOUNT`</font></td>
                <td align=center><font color="#000">~$profileArr.DEFERRABLE`</font></td>
                <td align=center><font color="#000">~$profileArr.ASSD`</font></td>
                <td align=center><font color="#000">~$profileArr.ASED`</font></td>
                <td align=center><font color="#000">~$profileArr.INVOICE_NO`</font></td>
            </tr>
            ~/foreach`
        </table>
        ~/if`
    </body>
</html>
