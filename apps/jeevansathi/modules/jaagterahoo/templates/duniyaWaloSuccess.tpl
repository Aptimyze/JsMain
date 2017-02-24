<html>
<head>
<title>Search Matrimonial Profile - Find Matrimony Profile - Jeevansathi.com</title>
<script type="text/javascript" language="Javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>

<body>
<input type="button" id="button" value="send" />

<br><br>
<div>=== Error === </div><div id="err"> </div><br>
<div>=== Info === </div><div id="info"> </div><br>

</body>

<script>
var lavesh, temp , bad=0;
var loadGoodArray = {}, loadBadArray= {} , badServers = [] , loadServers = {} , memoryPhysical = {};

function display(){
	$('#err').html("");
	$('#info').html("");
	$.each(loadServers, function (k,v) {
		if($.inArray(k,badServers)){
			$('#err').append("<div style='color:red'> Load on Server : "+k+" is "+v);
		}
		else{
			$('#info').append("<div> Load on Server : "+k+" is "+v);
		}
	});
	$.each(memoryPhysical, function (k,v) {
		if($.inArray(k,badServers)){
			$('#err').append("<div style='color:red'> Memory on Server : "+k+" is "+v);
		}
		else{
			$('#info').append("<div> Memory on Server : "+k+" is "+v);
		}
	});
}

$('#button').click(function() {
    loadGoodArray = {}, loadBadArray= {}; badServers = [] , loadServers = {} ; memoryPhysical = {};
    var requestCallback = new MyRequestsCompleted({
        numRequest: 3,
        singleCallback: function(){
            //alert( "I'm the callback");
        }
    });

    for(i=0;i<2;i++)
{
	if(i==0)
		url = "http://ser2.jeevansathi.com/load.php";
	else if(i==1)
		url = "http://staging.jeevansathi.com/load.php";
    $.ajax({
        url: url,
        success: function(data) {
            requestCallback.requestComplete(true);
		var parsed = $.parseJSON(data);
		var whoami;
		$.each(parsed, function (i, jsondata) {
			//console.log(i+"-->>"+jsondata);
			if(i=='whoami'){
				whoami = jsondata;
				if(whoami=='127.0.0.1')
					whoami = "172.10.18.64";
			}
			else if(i=='load'){
				temp='';
				bad = 0;
				$.each(jsondata, function (k,v) {
					temp = temp+k+"--"+v+" , ";		
					if(v<11) //config	
						bad=1;
				});
				loadServers[whoami] = temp;
			 	if(bad==1)
					badServers.push(whoami);
			}
			else if(i=='Memory'){
				temp='';
				bad = 0;
				$.each(jsondata, function (k,v) {
					$.each(v, function (k1,v1) {
						temp = temp+k1+"--"+v1+" , ";	
						
					});
				});
				memoryPhysical[whoami] = temp;
			}
		});
        }
    });
}

	$(document).ajaxStop(function() {
		display();
	});

	/*
    $.ajax({
        url: 'http://staging.jeevansathi.com/1.php',
        success: function(data) {
            requestCallback.requestComplete(true);
        }
    });
    $.ajax({
        url: 'http://ser2.jeevansathi.com/1.php',
        success: function(data) {
            requestCallback.requestComplete(true);
        }
    });
*/
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
