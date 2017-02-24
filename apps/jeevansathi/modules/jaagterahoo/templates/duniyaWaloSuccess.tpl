<html>
<head>
<title>Search Matrimonial Profile - Find Matrimony Profile - Jeevansathi.com</title>
<script type="text/javascript" language="Javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>

<body>
<input type="button" id="button" value="send" />

<br><br>
<div>=== Server Info === </div><div id="sinfo"> </div><br>

</body>

<script>
var lavesh, temp , bad=0;
var listServers;
var test; 
function display(){
	$('#err').html("");
	$('#info').html("");
	var html;
	$.each(listServers, function (k,v) {
		html = "<b>"+v.whoami+"</b><br>";
		$.each(v, function (k1,v1) {
			if(k1!='whoami'){
				html = html+k1+" : "+v1+"<br>";
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

    for(ii=0;ii<2;ii++)
    {
	if(ii==0)
		url = "http://ser2.jeevansathi.com/load.php";
	else if(ii==1)
		url = "http://staging.jeevansathi.com/load.php";
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
			}
			else if(i=='load' || i=='Memory_Physical' || i=="Memory_Swap" || i=="Memory_cached"){
				temp='';
				$.each(jsondata, function (k,v) {
					temp = temp+k+"--"+v+" , ";	
				});
				data[i] = temp;
			}
		});
		listServers.push(data);
		lavesh = listServers;
        }
    });
}
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
