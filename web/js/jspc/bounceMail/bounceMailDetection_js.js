var email;
var cid;

//This function is called when the submit button is hit and it sends an ajax call with the userData as entered in the form
function evaluateData()
{
	var userData = $("#user").val();
	cid = $("#cid").val();
	if(userData == "")
	{
		$("#nouserError").show();
	}
	else
	{	$("#nouserError").hide();
		$.ajax({
		url : "/operations.php/bounceMail/bounceMailDetection",
		data : {userData: userData,isSubmit: "1",cid: cid},
		async:true,
		timeout:30000,
		success:function(response){
			if(response == 'error')
			{
				$("#invalidUser").css("display", "block");
			}
			else if(response == '0'){
				$("#invalidUser").css("display", "none");
				alert("no result found for the Username/Email entered");
			}
			else
			{	email = response;
				alert("Entry exists. Click Delete to remove entry");
				$("#submitButton").hide();
				$("#deleteButton").show();
			}
		}
		
	});
	}

}

//This function is called when the Delete button is hit and it sends an ajax call to delete the email entry from bounce mails
function DeleteEntry()
{ $("#invalidUser").css("display", "none");
	$.ajax({
		url : "/operations.php/bounceMail/bounceMailDetection",
		data : {email: email,isDelete: "1",cid: cid},
		async:true,
		timeout:3000,
		success:function(response){
			if(response == "Deleted")
			{
				alert("Entry Deleted");
				$("#submitButton").show();
				$("#deleteButton").hide();
				location.reload();
			}
		},
		error:function(response)
		{
			location.reload();	
		}
		
	});
	
}