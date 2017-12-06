function sendClientNotes() {
    console.log("Method called successfully");
    var notes = document.getElementById("notes").value;
        console.log(notes);
    var client = document.getElementById("client").value;
    console.log(client);
    var relativeUrl = "clientNotesSubmission?client="+client;

        $.ajax({
            type: 'POST',
            url: relativeUrl,
            data:{
                notes: notes,
                client: client,
           },
           success: function(data) {
                 console.log("Success");
                 alert("Notes saved successfully");
           }
        });
}


$(document).ready(function() {
	//bind click action on status button
 $(".jsc-ExStatus").bind('click', function() {
 	var followUpData = $(this).attr("data").split(",");
 	var followUpId = followUpData[0],status=followUpData[1],client=followUpData[2],member=followUpData[3];
 	if(followUpId!=undefined && status!=undefined && client!=undefined && member!=undefined){
 		window.location = "/operations.php/jsexclusive/submitFollowupStatus?ifollowUpId="+followUpId+"&istatus="+status+"&iclient="+client+"&imember="+member;
 	}
 	else{
 		alert("Something went wrong !!");
 	}
 });
});