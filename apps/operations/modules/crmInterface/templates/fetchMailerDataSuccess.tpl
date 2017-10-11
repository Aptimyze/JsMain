<html>
    <head>
        <!--Load the AJAX API-->
        <link rel="stylesheet" async=true type="text/css" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700">
        <link rel="stylesheet" type="text/css" href="/fonts/fonts.css">
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        ~if $showMailer eq '1'`
            <script type="text/javascript">
                google.charts.load('current', {'packages':['corechart','bar','line']});
                
                google.charts.setOnLoadCallback(drawBarChart);
                google.charts.setOnLoadCallback(drawLineChart);
                google.charts.setOnLoadCallback(drawSteppedChart);
                
                function drawBarChart() {
                    var data = new Array();
                    var options = new Array();
                    var chart = new Array();
                    ~foreach from=$mailStats key=k item=v name=mailLoop`
                        data['~$k`'] = google.visualization.arrayToDataTable([
                            ['Fields', 'Counts', { role: "style"}, {type: 'string', role: 'tooltip'}],
                            ['Mailer Pool', ~$v.TOTAL_COUNT`, '#1E88E5', '~number_format($v.TOTAL_COUNT, 0, '.', ',')` (100%)'],
                            ['Successfully Sent', ~$v.SENT`, '#4CAF50', '~number_format($v.SENT, 0, '.', ',')` (~round(($v.SENT/$v.TOTAL_COUNT)*100,2)`)%'],
                            ['Hard Bounces', ~$v.HARD_BOUNCES`, '#f44336', '~number_format($v.HARD_BOUNCES, 0, '.', ',')` (~round(($v.HARD_BOUNCES/$v.TOTAL_COUNT)*100,2)`)%'],
                            ['Invalid Emails', ~$v.INVALID_EMAIL`, '#FFFF00', '~number_format($v.INVALID_EMAIL, 0, '.', ',')` (~round(($v.INVALID_EMAIL/$v.TOTAL_COUNT)*100,2)`)%'],
                            ['Unsubscribe', ~$v.UNSUBSCRIBE`, '#FF5722', '~number_format($v.UNSUBSCRIBE, 0, '.', ',')` (~round(($v.UNSUBSCRIBE/$v.TOTAL_COUNT)*100,2)`)%'],
                            ['Open Rate', ~$v.OPEN_RATE`, '#9C27B0', '~number_format($v.OPEN_RATE, 0, '.', ',')` (~round(($v.OPEN_RATE/$v.TOTAL_COUNT)*100,2)`)%'],
                        ]);
                        options['~$k`'] = {
                            'title': 'Overall Mailer Statistics : ~$startDt` to ~$endDt`',
                            'is3D': true,
                            'height': 450,
                            'legend':'none',
                            'tooltip': { 'isHtml': true },
                        };
                        chart['~$k`'] = new google.visualization.BarChart(document.getElementById('bar_chart_~$k`'));
                        chart['~$k`'].draw(data['~$k`'], options['~$k`']);
                    ~/foreach`
                }
                function drawLineChart() {
                    var data = new Array();
                    var options = new Array();
                    var chart = new Array();
                    ~foreach from=$mailPerInsStat key=k item=v name=mailLoop`
                        data['~$k`'] = google.visualization.arrayToDataTable([
                            ['Date' ~foreach from=$mailerParams key=m item=p name=mailParamAll`
                                        ~foreach from=$mailerParamReq key=mm item=pp name=mailParamReq`
                                            ~if $p eq $pp` ,'~$m`' ~/if`
                                        ~/foreach`
                                    ~/foreach`],
                            ~foreach from=$v key=kk item=vv name=mailOverallStatLoop`
                                ['~$kk` ~$dateArr[$kk]`' ~foreach from=$mailerParams key=m item=p name=mailParamAll`
                                            ~foreach from=$mailerParamReq key=mm item=pp name=mailParamReq`
                                                ~if $p eq $pp` ,~$vv.$pp` ~/if`
                                            ~/foreach`
                                        ~/foreach`],
                            ~/foreach`
                        ]);

                        options['~$k`'] = {
                            'title': 'Overall Mailer Trends : ~$startDt` to ~$endDt`',
                            'curveType': 'none',
                            'legend': { position: 'bottom' },
                            'height': 400,
                            'pointsVisible':true,
                            'pointSize':3,
                        };

                        chart['~$k`'] = new google.visualization.LineChart(document.getElementById('line_chart_~$k`'));
                        chart['~$k`'].draw(data['~$k`'], options['~$k`']);
                    ~/foreach`
                }
                function drawSteppedChart() {
                    var data = new Array();
                    var options = new Array();
                    var chart = new Array();
                    ~foreach from=$mailPerInsStatId key=k item=v name=mailLoop`
                        ~foreach from=$v key=kk item=vv name=mailInstLoop`
                            ~if (~$smarty.foreach.mailInstLoop.total` eq '1') OR (~$smarty.foreach.mailInstLoop.iteration` eq ~$smarty.foreach.mailInstLoop.total-1`)`
                                ~assign var=latsInstDate value=$kk`
                                ~foreach from=$vv key=kkk item=vvv name=mailInstLastLoop`
                                    ~if $smarty.foreach.mailInstLastLoop.last`
                                        data['~$k`'] = google.visualization.arrayToDataTable([
                                            ['Fields', 'Counts', { role: "style"}, {type: 'string', role: 'tooltip'}],
                                            ['Mailer Pool', ~$vvv.TOTAL_COUNT`, '#1E88E5', '~number_format($vvv.TOTAL_COUNT, 0, '.', ',')` (100%)'],
                                            ['Successfully Sent', ~$vvv.SENT`, '#4CAF50', '~number_format($vvv.SENT, 0, '.', ',')` (~round(($vvv.SENT/$vvv.TOTAL_COUNT)*100,2)`)%'],
                                            ['Hard Bounces', ~$vvv.HARD_BOUNCES`, '#f44336', '~number_format($vvv.HARD_BOUNCES, 0, '.', ',')` (~round(($vvv.HARD_BOUNCES/$vvv.TOTAL_COUNT)*100,2)`)%'],
                                            ['Invalid Emails', ~$vvv.INVALID_EMAIL`, '#FFFF00', '~number_format($vvv.INVALID_EMAIL, 0, '.', ',')` (~round(($vvv.INVALID_EMAIL/$vvv.TOTAL_COUNT)*100,2)`)%'],
                                            ['Unsubscribe', ~$vvv.UNSUBSCRIBE`, '#FF5722', '~number_format($vvv.UNSUBSCRIBE, 0, '.', ',')` (~round(($vvv.UNSUBSCRIBE/$vvv.TOTAL_COUNT)*100,2)`)%'],
                                            ['Open Rate', ~$vvv.OPEN_RATE`, '#9C27B0', '~number_format($vvv.OPEN_RATE, 0, '.', ',')` (~round(($vvv.OPEN_RATE/$vvv.TOTAL_COUNT)*100,2)`)%'],
                                        ]);
                                    ~/if`
                                ~/foreach`
                            ~/if`
                        ~/foreach`
                        options['~$k`'] = {
                            'title': 'Last Instance Statistic for Mailer : ~$latsInstDate`~$dateArr[$latsInstDate]`',
                            'height': 450,
                            'isStacked': true,
                            'legend': { position: 'bottom' },
                            'connectSteps': false,
                            hAxis: {
                                'textStyle': {
                                    'fontSize': '12',
                                    'bold': true,
                                    'italic': true,
                                    'tooltip': { 'isHtml': true },
                                }
                            }
                        };
                        chart['~$k`'] = new google.visualization.SteppedAreaChart(document.getElementById('stepped_chart_~$k`'));
                        chart['~$k`'].draw(data['~$k`'], options['~$k`']);
                    ~/foreach`
                }
            </script>
        ~/if`
    </head>
    <body class='fontreg'>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
            ~if $image neq '1'`
            <tr>
                <td valign="top" width="40%" align="center"><img src="/profile/images/logo_1.gif" width="209" height="80" usemap="#Map" border="0"></td>
            </tr>
            ~/if`
            <tr class="formhead" align="center" width="100%">
                <td colspan="3" style="background-color:lightblue" height="30">
                    <font size=3>Mailer Stats Interface</font>
                </td>
            </tr>
            ~if $image neq '1'`
            <tr>
                <td colspan="3" style="background-color:lightblue" height="30" align="center">
                    <a href="~sfConfig::get('app_site_url')`/jsadmin/mainpage.php?cid=~$cid`">Click here to go to main page</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="~sfConfig::get('app_site_url')`/jsadmin/logout.php?cid=~$cid`">Logout</a>
                </td>
            </tr>
            ~/if`
        </table>
        <br>
        ~if $showMailer eq '1'`
            <div style="margin: 0 auto;">
                ~foreach from=$mailStats key=k item=v name=mailLoop`
                <div style="height:880px;border:30px inset; border-radius: 15px;border-color: #263238; margin-top:10px;">
                    <div class="fontreg" style="text-align: center; font-weight: 900; font-size: 30px; margin-top: 30px;">~str_replace('_',' ',$k)`</div>
                    <div id="bar_chart_~$k`" style="float:left;width:50%;"></div>
                    <div id="stepped_chart_~$k`" style="float:left;width:50%;"></div>
                    <div id="line_chart_~$k`" style="float:left;width:100%;margin-top:-50px;"></div>
                </div>
                ~/foreach`
            </div>
        ~else`
        <form name="submitMailerDetails" action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/fetchMailerData" id="submitMailerDetails" method="POST">
        <div style="background-color:lightblue;padding:20px;font-size:12px;">
            <div style="display: inline-block;padding-left: 11%;">
            <span style="font-weight:bold;padding-right: 10px;">
                Select Mailer Key : &nbsp;
                <select id="mailerKey" name="mailer_key[]" multiple>
                    ~foreach from=$mailerKeys key=k item=v name=mailLoop`
                        <option value="~$v`">~str_replace('_',' ',$v)`</option>
                    ~/foreach`
                </select>
            </span>
            <br><br>
            <span id="mailerKeyErr" style="color:red;display: none;padding-left: 130px;">Please select atleast one Mailer Key</span>
            </div>
            <div style="display: inline-block;">
            <span style="font-weight:bold;">
                Select Trend Parameter(s) : &nbsp;
                <select id="mailerParam" name="mailer_params[]" multiple style="width:300px;">
                    ~foreach from=$mailerParams key=k item=v name=mailLoop`
                        ~if $v neq 'TOTAL_COUNT'`
                            <option value="~$v`">~$k`</option>
                        ~/if`
                    ~/foreach`
                </select>
            </span>
            <br><br>
            <span id="mailerParamErr" style="color:red;display: none;padding-left: 180px;">Please select atleast one Mailer Trend Parameter</span>
            </div>
            <br>
            <div style="font-weight:bold;padding-left: 23%;">
                Select Reporting Date Range
                <input id="date1" type="text" value="">
                &nbsp;&nbsp;&nbsp;
                <b>To</b>
                &nbsp;&nbsp;&nbsp;
                <input id="date2" type="text" value="">
                <br>
                <div style="color: red; margin: 0px auto; padding-left: 265px; padding-top: 15px;">~if $errorMsg`~$errorMsg`~/if`</div>
            </div>
            <div style="background-color:lightblue;text-align:center;padding-top:30px;font-size:12px;">
                *Multiple Options can be selected for Mailer Key and Trend Parameters
            </div>
            <div style="margin:0 auto;background-color:lightblue;text-align:center;padding:20px;font-size:12px;">
                <input style="font-size:16px;" id="submitBtn" type="submit" name="submit" value="Submit">
                <input style="font-size:16px;" id="pdfSubmitBtn" type="submit" name="pdfSubmit" value="Download">
                <input type="hidden" name="name" value="~$name`">
                <input type="hidden" name="cid" value="~$cid`">
            </div>
        </div>
        </form>
        ~/if`
    </body>
    <script type="text/javascript">
        var count = 0;
        $('#date1').dateDropDowns({ dateFormat: 'DD-mm-yy',yearStart: "2016", yearEnd: "~$rangeYear`"});
        $('#date2').dateDropDowns({ dateFormat: 'DD-mm-yy',yearStart: "2016", yearEnd: "~$rangeYear`"});
        $('#date1_dateLists_day_list option:selected').prop('selected', false);
        $('#date1_dateLists_day_list').on('click', function(){
            count = 1;
        });
        $('#date1_dateLists_month_list').on('click', function(){
            if(count != 1){
                $('#date1_dateLists_day_list option:selected').prop('selected', false);
            }
        });
        $(document).ready(function(){
            $('#submitBtn').click(function(e){
                var mailerKeyCount = 0;
                var mailerParamCount = 0;
                $('#mailerKey option').each(function() {
                    if($(this).is(':selected')){
                        mailerKeyCount++;
                    }
                });
                $('#mailerParam option').each(function() {
                    if($(this).is(':selected')){
                        mailerParamCount++;
                    }
                });
                if(mailerKeyCount == 0){
                    $('#mailerKeyErr').show();
                    e.preventDefault();
                } else {
                    $('#mailerKeyErr').hide();
                }
                if(mailerParamCount == 0){
                    $('#mailerParamErr').show();
                    e.preventDefault();
                } else {
                    $('#mailerParamErr').hide();
                }
            });
        });
    </script>
</html>