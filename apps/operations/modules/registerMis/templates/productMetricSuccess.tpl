<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Jeevansathi Product Metrics | </title>

    <!-- Bootstrap core CSS -->

    <link href="/css/productmetric/bootstrap.min.css" rel="stylesheet">

    <link href="/css/productmetric/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="/css/productmetric/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="/css/productmetric/custom.css" rel="stylesheet">
    <link href="/css/productmetric/icheck/green.css" rel="stylesheet">
    <link href="/css/productmetric/datetimePicker/datetimeCss.css" rel="stylesheet">
    <style type="text/css">
        
        /* Tooltip container */
.tooltipNew {
    position: relative;
    display: inline-block;
}

.tooltipNew .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: black;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    top: -5px;
    right: 102%;
}

.tooltipNew .tooltiptext::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 100%;
    margin-top: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: transparent transparent transparent black;
    opacity: 0.8;
}
.tooltipNew:hover .tooltiptext {
    visibility: visible;
}


.checkbox label:after, 
.radio label:after {
    content: '';
    display: table;
    clear: both;
}

.checkbox .cr,
.radio .cr {
    position: relative;
    display: inline-block;
    border: 1px solid #a9a9a9;
    border-radius: .25em;
    width: 1.3em;
    height: 1.3em;
    float: left;
    margin-right: .5em;
}

.radio .cr {
    border-radius: 50%;
}

.checkbox .cr .cr-icon,
.radio .cr .cr-icon {
    position: absolute;
    font-size: .8em;
    line-height: 0;
    top: 50%;
    left: 20%;
}

.radio .cr .cr-icon {
    margin-left: 0.04em;
}

.checkbox label input[type="checkbox"],
.radio label input[type="radio"] {
    display: none;
}

.checkbox label input[type="checkbox"] + .cr > .cr-icon,
.radio label input[type="radio"] + .cr > .cr-icon {
    transform: scale(3) rotateZ(-20deg);
    opacity: 0;
    transition: all .3s ease-in;
}

.checkbox label input[type="checkbox"]:checked + .cr > .cr-icon,
.radio label input[type="radio"]:checked + .cr > .cr-icon {
    transform: scale(1) rotateZ(0deg);
    opacity: 1;
}

.checkbox label input[type="checkbox"]:disabled + .cr,
.radio label input[type="radio"]:disabled + .cr {
    opacity: .5;
}
    </style>

    <script src="/js/productmetric/jquery.min.js"></script>

    <!--[if lt IE 9]>
        <script src="../assets/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

</head>


<body class="nav-md">
<canvas class="trendChart" id="chartDummy" height="231" width="462" style="width: 462px; height: 231px;display:none"></canvas>
    <div class="container body">


        <div class="main_container">

            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">

                    <div class="navbar nav_title" style="border: 0;">
                        <div class="profile_pic">
                            <img style='width:55px;' src="/images/JSLogo.png">
                        </div>
</i> <span class="site_title">Jeevansathi</span>                    </div>
                    <div class="clearfix"></div>



                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                        <div class="menu_section">
                            <ul class="nav side-menu">
                                <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                        <li><a href="javascript:void(0)" onclick='loadData("all")'>All Channels</a>
                                        </li>
                                        <li><a href="javascript:void(0)" onclick='loadData("P");'>Desktop</a>
                                        </li>
                                        <li><a href="javascript:void(0)" onclick='loadData("MS");'>Mobile</a>
                                        </li>
                                        <li><a href="javascript:void(0)" onclick='loadData("I");'>iOS APP</a>
                                        </li>
                                        <li><a href="javascript:void(0)" onclick='loadData("A");'>Android APP</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>


                    </div>
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="Settings">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Lock">
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Logout">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a>
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">

                <div class="nav_menu">
                    <nav class="" role="navigation">
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        
                    </nav>
                </div>

            </div>
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="page-title">
                        <div class="title_left">
                            <h3>
                    Jeevansathi Metrics
                </h3>
                        </div>


      <div class="col-sm-8">
        <div class="col-sm-6 start-date">
          <div class='input-group date' id="time-start">
            <input id="time-startVal" type='text' style="cursor: pointer;" class="form-control" placeholder="Start Time" readonly>
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
        </div>
  
        <div class="col-sm-6 end-date">
          <div class='input-group date' id="time-end">
            <input id="time-EndVal" type='text' style="cursor: pointer;" class="form-control" placeholder="End Time" readonly>
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
        </div>
                
                  <div id = 'time2-MainDiv' style='visibility: hidden;display: block;width: 100%;' >
                       <div class='tooltipNew'>
                            <span class="tooltiptext">Select start date for the Second Date Range</span>
                      <div class=' input-group date col-sm-6 end-date' id="time-start2">
            <input id="time2-startVal" type='text' style="cursor: pointer;" class="form-control" placeholder="End Time" readonly>
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
                       </div>
        </div>

         <div class="col-sm-6 end-date">
          <div>
            <input style="width: 100%;" type='button' value='GO' onclick='loadData(currentChannel)' >
            
          </div>
                 
             <div class="checkbox">
            <label>
                <input type="checkbox" value="">
                <span onClick="toggleDate2Visibility();" class="cr"><i class="cr-icon fa fa-check"></i></span>
                Compare two date-ranges
            </label>
        </div>
        </div>  
<br />
          

          
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">


                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Registrations</h2>

                                    <div class="clearfix">
                                        <span id ='REGCount' style="float:right;color: black;font-size: 26px;"></span>
                                    </div>
                                </div>
                                <div class="x_content" id="chart-REGParent">
                                    <canvas class = 'trendChart' id="chart-REG"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Logins</h2>
                                    <div class="clearfix">
                                        <span id ='LOGCount' style="float:right;color: black;font-size: 26px;"></span>

                                    </div>
                                </div>
                                <div class="x_content" id="chart-LOGParent">
                                    <canvas class = 'trendChart' id="chart-LOG"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Interests</h2>

                                    <div class="clearfix">
                                      <span id ='EOICount' style="float:right;color: black;font-size: 26px;"></span>

                                    </div>
                                </div>
                                <div class="x_content" id="chart-EOIParent">
                                    <canvas class = 'trendChart' id="chart-EOI"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Acceptances</h2>
                                    <div class="clearfix">
                                        <span id ='ACCCount' style="float:right;color: black;font-size: 26px;"></span>

                                    </div>
                                </div>
                                <div class="x_content" id="chart-ACCParent">
                                    <canvas class = 'trendChart' id="chart-ACC"></canvas>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="clearfix"></div>

                </div>

                <!-- footer content -->
                <footer>
                    <div class="clearfix"></div>
                </footer>
                <!-- /footer content -->

            </div>
            <!-- /page content -->
        </div>

    </div>

    <div id="custom_notifications" class="custom-notifications dsp_none">
        <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
        </ul>
        <div class="clearfix"></div>
        <div id="notif-group" class="tabbed_notifications"></div>
    </div>

    <script src="/js/productmetric/bootstrap.min.js"></script>

    <!-- chart js -->
    <script src="/js/productmetric/chartjs/chart.min.js"></script>
    <!-- bootstrap progress js -->
    <script src="/js/productmetric/progressbar/bootstrap-progressbar.min.js"></script>
    <script src="/js/productmetric/nicescroll/jquery.nicescroll.min.js"></script>
    <!-- icheck -->
    <script src="/js/productmetric/icheck/icheck.min.js"></script>

    <script src="/js/productmetric/custom.js"></script>

    <script src="/js/productmetric/datetimePicker/dateTimePicker.js"></script>

    <script>
        var doubleRange = 0;
        var chartOb={};
        var randomScalingFactor = function () {
            return Math.round(Math.random() * 100)
        };

        var currentChannel='all';
        var lineChartData = {
            labels: [],
            datasets: [
                {
                    label: "My First dataset",
                    fillColor: "rgba(38, 185, 154, 0.31)", //rgba(220,220,220,0.2)
                    strokeColor: "rgba(38, 185, 154, 0.7)", //rgba(220,220,220,1)
                    pointColor: "rgba(38, 185, 154, 0.7)", //rgba(220,220,220,1)
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                },
                {
                    label: "My Second dataset",
                    fillColor: "rgba(3, 88, 106, 0.3)", //rgba(151,187,205,0.2)
                    strokeColor: "rgba(3, 88, 106, 0.70)", //rgba(151,187,205,1)
                    pointColor: "rgba(3, 88, 106, 0.70)", //rgba(151,187,205,1)
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
            }

        ]

        }

        $(document).ready(function () {
            
            
            $('#time-start').datetimepicker({
    format: 'yyyy-mm-dd hh:ii:ss',
    autoclose: true,
    pickerPosition: "bottom-left",
    maxView: 3,
    minuteStep: 1,
    endDate: new Date()
});

$('#time-end').datetimepicker({
    format: 'yyyy-mm-dd hh:ii:ss',
    autoclose: true,
    pickerPosition: "bottom-left",
    maxView: 3,
    minuteStep: 1,
    endDate: new Date()
});
            $('#time-start2').datetimepicker({
    format: 'yyyy-mm-dd hh:ii:ss',
    autoclose: true,
    pickerPosition: "bottom-left",
    maxView: 3,
    minuteStep: 1,
    endDate: new Date()
});

            loadCurrentDateTime();
            
            sendAjaxForData('ACC');
            sendAjaxForData('REG');
            sendAjaxForData('EOI');
            sendAjaxForData('LOG');
    });

function loadData(channel){
    currentChannel = channel;
    sendAjaxForData('ACC');
    sendAjaxForData('REG');
    sendAjaxForData('EOI');
    sendAjaxForData('LOG');

}


function getCurrentTime(zeroTime){


var today = new Date();
var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
if((typeof zeroTime !='undefined') && zeroTime)
    var time = "00:00:00";
else 
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
var dateTime = date+' '+time;
return dateTime;
    
    
}
function loadCurrentDateTime()
{
    $("#time-startVal").val(getCurrentTime(1));
    $("#time-EndVal").val(getCurrentTime());
    $("#time2-startVal").val(getCurrentTime());
    
}
function drawCharts(data,type){
            if(typeof data[0].count=='undefined')return false;
            if(data[0].count.length<2)return false;
            var eleArr = $("#chart-"+type);
            eleArr.show();
            var newLineChartData = jQuery.extend(true, {}, lineChartData);
            var newData = jQuery.extend(true, {}, data[0]);
            var startString,endString;
            newLineChartData['labels'] = newData.timestamp;
            if(data['dayOrHour']=="day"){
                startString = 0;
                endString = 10;
            }
            else{
                startString = 10;
                endString = 16;
            }
            for(i=0;i<newLineChartData['labels'].length;i++){
                newLineChartData['labels'][i] = newLineChartData['labels'][i].replace('T',' ').substring(startString,endString);
            }
            newLineChartData['datasets'][0]['data'] = jQuery.extend(true, {}, newData.count);
            
            if(data[1]){
                var newData2 = jQuery.extend(true, {}, data[1]);
                newLineChartData['datasets'][1]['data'] = jQuery.extend(true, {}, newData2.count);

            }
           chartOb[type] = new Chart(eleArr[0].getContext("2d")).Line(newLineChartData, {
                responsive: true,
                tooltipFillColor: "rgba(51, 51, 51, 0.55)"
            });


        }

function sendAjaxForData(type){
    var postParams = {};
    postParams.type = type;
    postParams.channel = currentChannel;
    postParams.startDate = $("#time-startVal").val();
    postParams.endDate = $("#time-EndVal").val();
    if (doubleRange)
        postParams.startDate2 = $("#time2-startVal").val();
    $.ajax({
        
        'url':'/api/v1/api/metricMonitoring',
        'data' : postParams,
        'dataType' : 'json',
        success : function (response){
            $("#"+type+"Count").text('0');
            $('#chart-'+type).remove();
            var dummyHtml = document.getElementById("chartDummy").outerHTML;
            dummyHtml = $(dummyHtml).attr('id','chart-'+type);
            $("#chart-"+type + "Parent").append(dummyHtml);
            if(response!=null){
                if(doubleRange)
                    $("#"+type+"Count").text(response[0].totalCount+','+response[1].totalCount);
                else
                    $("#"+type+"Count").text(response[0].totalCount);
                drawCharts(response,type);
            }
        }
        
        
        
        
        
    });
}


function toggleDate2Visibility()
{
    var ob = $("#time2-MainDiv");
    if(ob.css('visibility')=='hidden')
    {
        ob.css('visibility','visible');
        doubleRange = 1;
    }
        else {
        ob.css('visibility','hidden');
        
        doubleRange = 0;
    }
}





    </script>
</body>

</html>