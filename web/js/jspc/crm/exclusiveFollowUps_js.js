$(document).ready(function() {
	//bind click action on status button
 $(".jsc-ExStatus").bind('click', function() {
 	var followUpId = $(this).attr("data");
 	window.location = "/operations.php/jsexclusive/submitFollowupStatus?followUp="+followUpId;
 });
});