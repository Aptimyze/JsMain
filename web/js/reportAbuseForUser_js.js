

function reportAbuseForUserFun(obj){
//	alert("Profile is being reported Abuse");
		  obj.disabled = true;

		 $( "buttonForReportAbuse").off( "click", "**" );
		 $('#reporterNp').css('display','none');
		 $('#reporteeNp').css('display','none');
		 var reporterName = $("#reporterProfileId").val().trim();
		 var reporteeName = $("#reporteeProfileId").val().trim();
		 var reason = $("#reasonId").val().trim();
		 var crmUserName = $("#crmUserId").val().trim();
		 $("#reporterProfileId").removeAttr("style");$("#reporteeProfileId").removeAttr("style");$("#crmUserId").removeAttr("style");$("#reasonId").removeAttr("style");
		if(reporterName== '' || reporteeName == '' || reason == ''){
		if(reporterName == '')
		{
				$("#reporterProfileId").css('borderColor','red');
		}

		if(reporteeName == '')
		{
			$("#reporteeProfileId").css('borderColor','red');
		}

		if(reason == '')
		{
			$("#reasonId").css('borderColor','red');
		}
//		$('#formForReportAbuse').attr('onsubmit',"reportAbuseForUserFun();return false;");
		obj.disabled = false;
		return;
	  }

	  if(reporteeName == reporterName)
	  {
	  	alert('Both Reporter and Reportee are same');
	  	obj.disabled = false;
	  	return;
	  }


	var feed={}; 
	//feed.message:as sdf sd f
	feed.category='Abuse';
	feed.reporter = reporterName;
	feed.reportee = reporteeName;
	feed.crmUser = crmUserName;
	feed.reason = reason;
	feed.message=reporteeName+' has been reported abuse by '+reporterName+' with the following reason:'+reason;
	ajaxData={'feed':feed,'CMDSubmit':1};
	var url='/faq/reportAbuseForUserLog';


		$.ajax({
				url: url,
				type: "POST",
				data: ajaxData,
				//crossDomain: true,
				success: function(result){
			         if(typeof(result) != 'object')
					  var out = JSON.parse(result);

					if(typeof(result) != 'undefined' && result.responseStatusCode == "1")
		                 {

		                 		$('#formForReportAbuse').hide();
		                 		$('#successfullDisplay').html(result.message).css('display','block');
		                 		$('#goBackforRishav').css('display','block');

		                 		obj.disabled = false;
		                 		return;
		                 }

					 if(typeof(out) != 'undefined' && out['message'] == "both are not correct")
		                 {
		                 		//$('#formForReportAbuse').hide();
		                 		$('#reporterNp').css('display','block');
		                 		$('#reporteeNp').css('display','block');
		                 } 
					  
					 else if(typeof(out) != 'undefined' && out['message'] == "reportee profileID is not correct")
		                 {
		                 		//$('#formForReportAbuse').hide();
		                 		$('#reporterNp').css('display','none');
		                 		$('#reporteeNp').css('display','block');
		                 } 

 					else if(typeof(out) != 'undefined' && out['message'] == "reporter profileID is not correct")
		                 {
		                 		//$('#formForReportAbuse').hide();
		                 		$('#reporteeNp').css('display','none');
		                 		$('#reporterNp').css('display','block');
		                 } 

		                 else if (result.responseStatusCode == '0')
		                 { 		
		                 		$('#formForReportAbuse').hide();
		                 		$('#successfullDisplay').css('display','block');
		                 		$('#goBackforRishav').css('display','block');
		                 }  	            
		                 obj.disabled = false;
;
		              },

		         error: function(result)
		         {   
                 		$('#formForReportAbuse').hide();
                 		$('#invalidEntries').css('display','block');
                 		$('goBackforRishav').css('display','block');
                 		obj.disabled = false;

		         }
		});

	

}