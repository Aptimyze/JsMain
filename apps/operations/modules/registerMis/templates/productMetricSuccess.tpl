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
                        <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Jeevansathi</span></a>
                    </div>
                    <div class="clearfix"></div>


                    <!-- menu prile quick info -->
                    <div class="profile">
                        <div class="profile_pic">
                            <img src="images/img.jpg" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>Welcome</span>
                        </div>
                    </div>
                    <!-- /menu prile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                        <div class="menu_section">
                            <h3>General</h3>
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
            <input id="time-startVal" type='text' class="form-control" placeholder="Start Time" readonly>
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
        </div>
  
        <div class="col-sm-6 end-date">
          <div class='input-group date' id="time-end">
            <input id="time-EndVal" type='text' class="form-control" placeholder="End Time" readonly>
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
        </div>
          
         <div class="col-sm-6 end-date">
          <div class='input-group date'>
            <input type='button' value='GO' onclick='loadData(currentChannel)' >
          </div>
        </div>  
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
        var chartOb={};
        var randomScalingFactor = function () {
            return Math.round(Math.random() * 100)
        };

        var currentChannel='a';
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
    
}
function drawCharts(data,type){
            if(typeof data.count=='undefined')return false;
            if(data.count.length<2)return false;
            var eleArr = $("#chart-"+type);
            eleArr.show();
            var newLineChartData = jQuery.extend(true, {}, lineChartData);
            newLineChartData['labels'] = data.timestamp;
            for(i=0;i<newLineChartData['labels'].length;i++){
                newLineChartData['labels'][i] = newLineChartData['labels'][i].replace('T',' ').substring(0,19);
            }
            newLineChartData['datasets'][0]['data'] = data.count;
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
            $("#"+type+"Count").text(response.totalCount);
            drawCharts(response,type);
            }
        }
        
        
        
        
        
    });
}





    </script>
</body>

</html>