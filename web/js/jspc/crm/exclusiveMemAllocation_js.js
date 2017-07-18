/*sends assign/unassign request to api
*@param : params
*/
function sendExMemAllocationRequest(params)
{
	var url = "/operations.php/crmAllocation/handleExMemAllocationApi";
	var postParams ={'profileDetails':params,'sendAssignMailer':true,'sendAssignSMS':true},exAction=params["exAction"];
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
			$("#mainExContent").addClass("jsc-disabled"); 
			showLineLoader();            		
		},
		success: function(response) 
		{
			$("#exRow"+params["billid"]).remove();
			$("#mainExContent").removeClass("jsc-disabled");
			hideLineLoader();
		},
		error: function(xhr) 
		{
			$("#mainExContent").removeClass("jsc-disabled");
			hideLineLoader();
			alert("Something went wrong,please try again for "+params["username"]);
		}
	});
	return false;
}

$(document).ready(function() {
	//bind click action on assign/unassign actions
 $(".jsc-ExAllocate").bind('click', function() {
 	var dataArr = ($(this).attr("data")).split(",");
 	var params={},validRequest=true,assigned_to;
 	params["profile"] = dataArr[0];
 	params["username"] = dataArr[1];
 	params["phone"] = dataArr[2];
 	params["exAction"] = dataArr[3];
 	params["billid"] = dataArr[4];

 	if(params["exAction"]=="ASSIGN")
 	{
 		assigned_to = $("#ASSIGN"+params["billid"]).find('select').val();
 		params["executiveDetails"] = executivesdata[parseInt(assigned_to)];
 		if(assigned_to=="")
 		{
 			validRequest=false;
 			alert("Please select any executive before clicking on ASSIGN");
 		}
 	}
 	else
 	{
 		assigned_to = $("#UNASSIGN"+params["billid"]).html();
 		params["assignedToUsername"]=assigned_to;
	} 
 	if(validRequest==true)
 		sendExMemAllocationRequest(params);
 });
});