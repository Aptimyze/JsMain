~include_partial('global/header')`

<SCRIPT language="JavaScript">
$(document).ready(function(){
	setInterval(function(){getData();}, 5000);
	function getData(){
		var data = $.ajax({
			type:"GET",
			url : '/operations.php/commoninterface/WebServiceMonitoring?name=&cid=~$cid`&ajax=1',
			async: false,
			})
		.success(function(){
        	setTimeout(function(){getData();}, 10000);
    	}).responseText
    	data = $.parseJSON(data);
    	var mail = data.mail;
    	$.each(mail, function(index, value){
    		$("#mail_"+index+"_value").text(value);
		console.log("INDEX: " + index + " VALUE: " + value);
	});
    	var sms = data.sms;
    	var gcm = data.gcm;
    	console.log(mail);
	}
});
</script>
<div class="mail" id="create_cache_process">
<span id="create_cache_process_label">create_cache_process:</span><span id="mail_~$v`_value">~$create_cache_process`</span>
</div>
<div class="mail" id="cache_not_set">
	<span id="cache_not_set_label">cache_not_set:</span><span id="mail_~$v`_value">~$cache_not_set`</span>
</div>
<div class="mail" id="cache_inprocess">
	<span id="cache_inprocess_label">cache_inprocess:</span><span id="mail_~$v`_value">~$cache_inprocess`</span>
</div>
<div class="mail" id="cache_problem_sql">
	<span id="cache_problem_sql_label">cache_problem_sql:</span><span id="mail_~$v`_value">~$cache_problem_sql`</span>
</div>
<div class="mail" id="cache_connection">
	<span id="cache_connection_label">cache_connection:</span><span id="mail_~$v`_value">~$cache_connection`</span>
</div>
~include_partial('global/footer')`
