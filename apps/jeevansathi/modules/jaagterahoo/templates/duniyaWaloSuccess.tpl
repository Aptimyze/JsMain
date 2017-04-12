<html>
<head>
<title>Search Matrimonial Profile - Find Matrimony Profile - Jeevansathi.com</title>
<script type="text/javascript" language="Javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>

<body>
<div>=== Server Info <input type="button" id="button" value="recheck" /> ====
	<div id="sinfo"> </div>
</div>

<br><div>=== Mysql Status === </div>
<table border='1'>
<tr>
	<td>DB</td>
	~foreach from=$mysqlStatus item=v key=k`
		<td ~if $v['FLAG'] eq 0`style="color:red;"~/if`> ~$k`</td>
	~/foreach`
</tr>
<tr>
	<td> connections taken</td>
	~foreach from=$mysqlStatus item=v key=k`
		<td ~if $v['FLAG'] eq 0`style="color:red;"~/if`> ~$v['TOTAL_COUNT']`</td>
	~/foreach`
</tr>
</table>
<br>

<div>=== Server Status === </div>
<table border='1'>
        <tr>
                <td> servers</td>
                ~foreach from=$serverstatus item=v key=k`
                        <td ~if $v["flag"] neq 2` style="color:red" ~/if`> ~$k`</td>
                ~/foreach`
        </tr>
        <tr>
                <td> idle workers</td>
                ~foreach from=$serverstatus item=v key=k`
                        <td ~if $v["flag"] neq 2` style="color:red" ~/if`> ~$v['idle']`</td>
                ~/foreach`
        </tr>
</table>

<br><div>=== Solr Health === </div>
<table border='1'>
<tr>
~foreach from=$thirdPartyCheckSolr item=v key=k`
	<td ~if $v['status'] eq 'Fail'` style="color:red" ~/if`>~$k`</td>
~/foreach`
</tr>
<tr>
~foreach from=$thirdPartyCheckSolr item=v key=k`
	<td ~if $v['status'] eq 'Fail'` style="color:red" ~/if`>~$v['responseTime']` ms.</td>
~/foreach`
</tr>
</table>
<br>

<div>===Api respoonse time ===</div>
<table border='1'>
	<tr>
		~foreach from=$checkServices item=y key=x`
			<td>~$x`</td>
		~/foreach`
	</tr>
	<tr>
		~foreach from=$checkServices item=y key=x`
			<td ~if $y.status eq 'Fail'` style="color:red"~/if`>~$y.responseTime` Sec</td>
		~/foreach`
	</tr>
</table>


<br>
<div>=== Ha Proxy Health === </div>
~if $marGayServers eq ''`
~foreach from=$marGayeServers item=v key=k`
	<div style="color:red;font-size:20px;">~$v` mar gaya</div>
~/foreach`
~else`
	<div>All servers up</div>
~/if`
<br>
<div>=== Mysql Status Detail=== </div>
<br>
~foreach from=$mysqlStatus item=v key=k`
        <div ~if $v['FLAG'] eq 0`style="color:red;"~/if`>
		<div><b>~$k`</b></div>
<br>
		<span>Total ~$v['TOTAL_COUNT']`</span>
		<span> Sleep  ~$v['SLEEP_COUNT']`</span>
		<div> Queries</div>
		~foreach from=$v['QUERIES'] item=y key=x`
			<span> ~$y`</span><br> 
		~/foreach`	
	 </div>
<br>
<br>
~/foreach`
<br>
</body>

<script>
var serverHealthConfig = ~$serverHealthConfig|decodevar`;
var lavesh, temp , bad=0;
var listServers;
var test;
var healthArr = ["1 Minute","5 Minutes","15 Minute","Total Physical","Used Physical","Free Physical","Total Swap","Used Swap","Free Swap","Cache Used","Cached Free"]; 
$(document).ready(function() {
	$('#button').trigger('click');
});

function display(){
	$('#sinfo').html("");
	var html;
	html = "<table border='1'>";
	html = html + "<tr><td>Server</td>";
	$.each(healthArr, function(x,y){
		html = html+"<td>"+y+"</td>";
	})
	html = html + "</tr>";
	$.each(listServers, function (k,v) {
		if(v.isloadThres==true)
			html = html + "<tr style=color:red;>";
		else
			html = html+"<tr>";
		html = html + "<td><b>"+v.whoami+"</b></td>";
		$.each(healthArr, function (k1,v1) {
			if(v.hasOwnProperty(v1))
				html = html+"<td>"+v[v1]+"</td>";
                        else
                                html = html+"<td></td>";
		});
		html = html+"<tr>";
	});
	html = html+"</table>";
		$('#sinfo').append(html);
}

$('#button').click(function() {
    listServers = [];
    var requestCallback = new MyRequestsCompleted({
        numRequest: 3,
        singleCallback: function(){
        }
    });

 $.each(serverHealthConfig,function(index,serverInfo)
    {
	url = serverInfo.host;
console.log(url);
    $.ajax({
        url: url,
        success: function(data) {
            requestCallback.requestComplete(true);
		var parsed = $.parseJSON(data);
console.log(parsed);
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
