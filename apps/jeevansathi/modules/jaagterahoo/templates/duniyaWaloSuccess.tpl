<html>
<head>
<title>Search Matrimonial Profile - Find Matrimony Profile - Jeevansathi.com</title>
<script type="text/javascript" language="Javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>

<body>
<form>
<input type="button" value="Print this page" onClick="window.print()">
</form>
<div>=== Server Info === &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="button" value="recheck load" />
	<div id="sinfo"> </div>
</div>
<br>

<div>=== Mysql Status === &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="buttonMysqlCount" value="recheck mysql counts" />
	<div id ='mysqlStatusCount'></div>
</div>
<br>

<div>=== Server Status === &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="buttonServerStatus" value="recheck idle workers" />
        <div id="serverStatus"> </div>
</div>

<br>
<div>=== Solr Health === &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="buttonSolr" value="recheck solr" />
	<div id ='solrStatus'></div>
</div>
<br>

<div>===Api response time ===&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="buttonApi" value="recheck api response" />
        <div id ='apiStatus'></div>
</div>

<br>

<div>=== Ha Proxy Health === &nbsp;&nbsp;<input type="button" id="buttonHaProxy" value="recheck Ha proxy"/>
	<div id='haproxy'></div>
</div>
<br>

<div>=== Mysql Status Detail=== 
	&nbsp;&nbsp;<input type="button" id="buttonMysqlStatus" value="recheck Mysql Status"/>
	<div id='mysqlStatus'></div>
</div>

</body>

<script>
var selfUrl = 'http://t.j.com/jaagterahoo/duniyaWalo';
var serverHealthConfig = ~$serverHealthConfig|decodevar`;
var marGayeServers = ~$marGayeServers|decodevar`;
var mysqlStatus = ~$mysqlStatus|decodevar`;
var thirdPartyCheckSolr = ~$thirdPartyCheckSolr|decodevar`;
var serverstatus = ~$serverstatus|decodevar`;
var checkServices = ~$checkServices|decodevar`;
var onlyIssues = "~$onlyIssues`";
var lavesh, temp , bad=0;
var listServers;
var test;
var healthArr = ["1 Minute","5 Minutes","15 Minute","Total Physical","Used Physical","Free Physical","Total Swap","Used Swap","Free Swap","Cache Used","Cached Free"]; 

$(document).ready(function() {
	$('#button').trigger('click');
	haProxySet();
	mysqlStatusCountSet();
	mysqlStatusSet();
	serverStatusSet();
	solrSet();
	apiStatusSet();
});
function apiStatusSet()
{
	$("#apiStatus").html('');
	var html;
	html = "<table border='1'>        <tr>";
	$.each(checkServices, function(x,y)
        {
		html = html + "<td>"+x+"</td>";
	});
	html = html +"        </tr>  <tr>";
	$.each(checkServices,function(x,y)
        {
                html = html + "<td ";
		if(y['status']=="Fail")
			html = html +"style='color:red'";
		html = html + ">"+y['responseTime']+"Sec </td>"
        });
	html = html +" </tr></table>";
        $("#apiStatus").html(html);
}
function serverStatusSet()
{
	$("#serverStatus").html('');
	var html;
	html = "<table border='1'>       <tr>           <td> servers</td>";
	$.each(serverstatus, function(k,v)
	{
		html = html + "<td ";
		if(v["flag"]!=2)
			html = html + "style='color:red'";
		html = html + ">"+ k+"</td>";
	});
	html = html + "        </tr>        <tr>                <td> idle workers</td>";
        $.each(serverstatus, function(k,v)
        {
                html = html + "<td ";
                if(v["flag"]!=2)
                        html = html + "style='color:red'";
                html = html + ">"+ v['idle']+"</td>";
        });
		html = html + "</tr></table>";
        $("#serverStatus").html(html);
}

function solrSet()
{
	$("#solrStatus").html('');
	var html;
	html = "<table border='1'><tr>";
	$.each(thirdPartyCheckSolr,function(k,v)
	{
		html = html + "<td ";
		if(v['status'] =="Fail")
			html = html + " style='color:red' ";
		html = html +">"+ k + "</td>";
	});
	html = html +"</tr><tr>";
	$.each(thirdPartyCheckSolr,function(k,v)
	{
		html = html + "<td ";
		if(v['status'] =="Fail")
			html = html + " style='color:red' ";
		html = html +">"+ v['responseTime'] + " ms.</td>";
	});
	html = html + "</tr></table>";
	$("#solrStatus").html(html);
}

function haProxySet()
{
        $("#haproxy").html('');
        var html;
        if($.isArray(marGayeServers))
        {
                $.each(marGayeServers,function(k,v)
                {
                        html = html+ "<div style='color:red;font-size:20px;'>"+v+" mar gaya</div>";
                });
        }
        else
                html = html + "<div>All servers up</div>";
        $("#haproxy").html(html);
}

function mysqlStatusCountSet()
{
	$("#mysqlStatusCount").html('');

	var html;

	html = "<table border='1'><tr>        <td>DB</td>";

	$.each(mysqlStatus,function(k,v)
	{
		html = html+"<td";
		if(v['FLAG']==0)
			html = html + "style='color:red;'";
		html = html +">"+ k;
	});

	html = html + "</tr><tr><td> connections taken</td>";

	$.each(mysqlStatus,function(k,v)
	{
		html  = html + "<td";
		if(v['FLAG']==0)
			html = html + "style='color:red;'";
		html = html +">"+v['TOTAL_COUNT'] +"</td>";
	});

	html = html + "</tr></table>";

	$("#mysqlStatusCount").html(html);
}

function mysqlStatusSet()
{
	$("#mysqlStatus").html('');
	var html ='';
	$.each(mysqlStatus,function(k,v)
	{
		html = html + "<div ";
		if(v['FLAG']==0)
			html = html + "style='color:red;'";
		html  = html + "><div><b>"+k+"</b></div><br>";
		html = html + "<span>Total "+v['TOTAL_COUNT']+"</span>";
                html = html + "<span> Sleep  "+v['SLEEP_COUNT']+"</span>";
                html = html + "<div> Queries</div>";
		$.each(v['QUERIES'],function(x,y)
		{
			html = html + "<span> "+y+"</span><br>";
		});
		html = html + "</div><br><br>";
	});
	$("#mysqlStatus").html(html);
}
function display(){
	$('#sinfo').html("");
	var html;
	html = "<div id ='sinfoTable'><table border='1'>";
	html = html + "<tr id='serverInfoId'><td>Server</td>";
	$.each(healthArr, function(x,y){
		html = html+"<td>"+y+"</td>";
	})
	html = html + "</tr>";
	var hideHeadInfo = true;
	$.each(listServers, function (k,v) {
		if(v.isloadThres==true)
		{
			html = html + "<tr style=color:red;>";
			hideHeadInfo = false;
		}
		else
		{
			html = html+"<tr ~if $onlyIssues` style=display:none; ~/if`>";
		}
		html = html + "<td><b>"+v.whoami+"</b></td>";
		$.each(healthArr, function (k1,v1) {
			if(v.hasOwnProperty(v1))
				html = html+"<td>"+v[v1]+"</td>";
                        else
                                html = html+"<td></td>";
		});
		html = html+"<tr>";
	});
	html = html+"</table></div>";
		$('#sinfo').append(html);
	if(onlyIssues && hideHeadInfo)
	{
		//$("#serverInfoId").hide();
		$('#sinfo').append("<div>All load normal</div>");
		$('#sinfoTable').hide();
	}
}

$('#buttonHaProxy').click(function(){
                var url =selfUrl+"?getParticularData=HAPROXY";
                $.ajax
                ({
                        url: url,
                        type: "GET",
                        datatype: 'json',
                        async: true,
                        success: function (res) {
				marGayeServers = $.parseJSON(res);
				haProxySet();
                        }
                });
});
$("#buttonApi").click(function(){
                var url =selfUrl+"?getParticularData=THIRD_SERVICES";
                $.ajax
                ({
                        url: url,
                        type: "GET",
                        datatype: 'json',
                        async: true,
                        success: function (res) {
                                checkServices = $.parseJSON(res);
                                apiStatusSet();
                        }
                });
});

$("#buttonServerStatus").click(function(){
                var url =selfUrl+"?getParticularData=SERVER_STATUS";
                $.ajax
                ({
                        url: url,
                        type: "GET",
                        datatype: 'json',
                        async: true,
                        success: function (res) {
                                serverstatus = $.parseJSON(res);
                                serverStatusSet();
                        }
                });
});

$('#buttonSolr').click(function(){
		$("#solrStatus").html('');
                var url =selfUrl+"?getParticularData=SOLR";
                $.ajax
                ({
                        url: url,
                        type: "GET",
                        datatype: 'json',
                        async: true,
                        success: function (res) {
                                thirdPartyCheckSolr = $.parseJSON(res);
                                solrSet();
                        }
                });
});

$('#buttonMysqlCount,#buttonMysqlStatus').click(function()
{
	$("#mysqlStatusCount,#mysqlStatus").html('');
	var url =selfUrl+"?getParticularData=MYSQL_STATUS";
	$.ajax
	({
		url: url,
		type: "GET",
		datatype: 'json',
		async: true,
		success: function (res) {
			mysqlStatus = $.parseJSON(res);
			mysqlStatusCountSet();
			mysqlStatusSet();
		}
	});
});

$('#button').click(function() {
	$('#sinfo').html('');
    listServers = [];
    var requestCallback = new MyRequestsCompleted({
        numRequest: 3,
        singleCallback: function(){
        }
    });

 $.each(serverHealthConfig,function(index,serverInfo)
    {
	url = serverInfo.host;
    $.ajax({
        url: url,
        success: function(data) {
            requestCallback.requestComplete(true);
		var parsed = $.parseJSON(data);
		var whoami,temp={};
	        var data = {};
		$.each(parsed, function (i, jsondata) {
				temp={};
			if(i=='whoami'){
				whoami = jsondata;
				if(whoami=='127.0.0.1')
					whoami = "172.10.18.64";
				data.whoami = whoami
				data.isloadThres = false;
			}
			else if(i=='load' || i=='Memory_Physical' || i=="Memory_Swap" || i=="Memory_cached"){
				$.each(jsondata, function (k,v) {
					data[k]=v;
					if(i=='load' && serverInfo.loadThreshold < v){
						data.isloadThres = true;	
					}
				});
			}
		});
		listServers.push(data);
		lavesh = listServers;
        }
    });
});
	$(document).ajaxStop(function() {
		display();
	});

});


var MyRequestsCompleted = (function() {
    var numRequestToComplete, 
        requestsCompleted, 
        callBacks, 
        singleCallBack; 

    return function(options) {
        if (!options) options = {};

        numRequestToComplete = options.numRequest || 0;
        requestsCompleted = options.requestsCompleted || 0;
        callBacks = [];
        var fireCallbacks = function () {
            //alert("we're all complete");
            for (var i = 0; i < callBacks.length; i++) callBacks[i]();
        };
        if (options.singleCallback) callBacks.push(options.singleCallback);

        

        this.addCallbackToQueue = function(isComplete, callback) {
            if (isComplete) requestsCompleted++;
            if (callback) callBacks.push(callback);
            if (requestsCompleted == numRequestToComplete) fireCallbacks();
        };
        this.requestComplete = function(isComplete) {
            if (isComplete) requestsCompleted++;
            if (requestsCompleted == numRequestToComplete) fireCallbacks();
        };
        this.setCallback = function(callback) {
            callBacks.push(callBack);
        };
    };
    })();
</script>
</html>
