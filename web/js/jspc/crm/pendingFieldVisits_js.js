/*sends manage(remove/add) visit request to api
*@param : params,action
*/
function sendManageFieldVisitRequest(params,action)
{
	var url = "/operations.php/profileVerification/manageFieldVisitsApi";
	var postParams ={'FSAction':action,'visitDetails':params};
	$.ajax({
		url: url,
		dataType: 'json',
		type: 'POST',
		data: postParams,
		timeout: 60000,
		cache: false,
		beforeSend: function( xhr ) 
		{  
			//console.log(postParams);
			$("#mainContent").addClass("jsc-disabled"); 
			showLineLoader();            		
		},
		success: function(response) 
		{
			$("#FSRow"+params["USERNAME"]).remove();
			$("#mainContent").removeClass("jsc-disabled");
			hideLineLoader();
		},
		error: function(xhr) 
		{
			$("#mainContent").removeClass("jsc-disabled");
			hideLineLoader();
			alert("Something went wrong,please try again for "+params["USERNAME"]);
		}
	});
	return false;
}

$(document).ready(function() {
	//bind click action on remove action
 $(".jsc-FSRemove").bind('click', function() 
 {
 	var params={};
 	params["USERNAME"] = $(this).attr("data");
 	sendManageFieldVisitRequest(params,'REMOVE');
 });
});