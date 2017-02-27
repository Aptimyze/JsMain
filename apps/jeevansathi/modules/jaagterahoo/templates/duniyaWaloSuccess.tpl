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
~foreach from=$mysqlStatus item=v key=k`
        <div ~if $v['FLAG'] eq 0`style="color:red;"~/if`>~$k` connections: ~$v['TOTAL_COUNT']`</div>
~/foreach`
<br>

<div>=== Ha Proxy Health === </div>
~foreach from=$marGayeServers item=v key=k`
	<div style="color:red;font-size:20px;">~$v` mar gaya</div>
~/foreach`

<br><div>=== Solr Health === </div>
~foreach from=$thirdPartyCheckSolr item=v key=k`
		~if $v['status'] eq 'Fail'`
			<span style="color:red">
		~/if`
		~$k` Response Time : ~$v['responseTime']` ms.
		<br>
		~if $v['status'] eq 'Fail'`
			</span>
		~/if`
~/foreach`

<br><div ~if $checkGuna.status eq 'Fail'` style="color:red"~/if`>=== Guna Score Response Time === : ~$checkGuna.responseTime` Seconds</div>
<br><div ~if $checkRedis.status eq 'Fail'` style="color:red"~/if`>=== Redis Response Time === : ~$checkRedis.responseTime` Seconds</div>
<br><div ~if $checkRabbit.status eq 'Fail'` style="color:red"~/if`>=== Rabbit Response Time === : ~$checkRabbit.responseTime` Seconds</div>
<br>
~foreach from=$checkServices item=y key=x`
<div ~if $y.status eq 'Fail'` style="color:red"~/if`>=== ~$x` Time === : ~$y.responseTime` Seconds</div>
<br>
~/foreach`

<br><div>=== Server Status === </div>
~foreach from=$serverstatus item=v key=k`
		<div>idle connection on server ~$k` are ~$v["idle"]` 
			~if $v["flag"] neq 2`
			<span style="color:red">
			~/if`
			(~$v["message"]`)
			~if $v["flag"] neq 2`
			</span>
			~/if`
		</div>
~/foreach`

<br><br>

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
$(document).ready(function() {
	$('#button').trigger('click');
});

function display(){
	$('#sinfo').html("");
	var html;
	$.each(listServers, function (k,v) {
		html = "<b>"+v.whoami+"</b><br>";
		$.each(v, function (k1,v1) {
			if(k1!='whoami' && k1!='isloadThres'){
				var alert='>';
				if(k1=="load" && v.isloadThres==true)
					alert = " style=color:red;>";	
				html = html+"<span"+alert+k1+" : "+v1+"</span><br>";
			}
		});
		html = html+"<br>";
		console.log(html);
		$('#sinfo').append(html);
	});
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
    $.ajax({
        url: url,
        success: function(data) {
            requestCallback.requestComplete(true);
		var parsed = $.parseJSON(data);
		var whoami;
	        var data = {};
		$.each(parsed, function (i, jsondata) {
			if(i=='whoami'){
				whoami = jsondata;
				if(whoami=='127.0.0.1')
					whoami = "172.10.18.64";
				data.whoami = whoami
				data.isloadThres = false;
			}
			else if(i=='load' || i=='Memory_Physical' || i=="Memory_Swap" || i=="Memory_cached"){
				temp='';
				$.each(jsondata, function (k,v) {
					temp = temp+k+"--"+v+" , ";	
					if(i=='load' && serverInfo.loadThreshold < v){
						console.log(serverInfo.loadThreshold+"---"+v);
						data.isloadThres = true;	
					}
				});
				data[i] = temp;
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
