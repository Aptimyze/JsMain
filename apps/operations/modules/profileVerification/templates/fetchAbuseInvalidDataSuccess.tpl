~include_partial('global/header')`

	<script type="text/javascript">
	var startDate,endDate,rowHtml="<tr style='font-size:15px' class='label RARowHtml' align='center'><td></td><td class='Reporter'></td><td class='Type'></td><td class='Date'></td><td class='Reason'></td><td class='OtherReason'></td><td class='Attachment'><input class='attach_id' id='-1' type='button' disabled=true value='Download'>";
	function getRowHtml(rowJson){

		var tempHtml=$(rowHtml);
		tempHtml.find('.Reporter').text(rowJson.REPORTER);
		tempHtml.find('.Type').text(rowJson.TYPE);
		tempHtml.find('.Date').text(rowJson.DATE);
		tempHtml.find('.Reason').text(rowJson.REASON);
		tempHtml.find('.OtherReason').text(rowJson.OTHER_REASON);
                
                if(rowJson.ATTACHMENT_ID != -1) {
                    tempHtml.find('.attach_id').prop('disabled',false).attr('id',rowJson.ATTACHMENT_ID);
                }
		return tempHtml;

	}

        function downloadAll(urls) {
           

            for (var i = 0; i < urls.length; i++) {
              link.setAttribute('href', urls[i]);
              link.click();
            }

            document.body.removeChild(link);
        }
        
        function downloadAttachment()
        {
            var id = $(this).attr('id');
            $.ajax({
                'url':'/operations.php/feedback/GetAbuseAttachments',
                'data':{'attachment_id':id},
                'method':'POST',
                success:function(response)
                { 
                     var link = document.createElement('a');

                    
                    link.style.display = 'none';

                    document.body.appendChild(link);
                    
                    var size = response.length;
                    for ( var i=0 ; i<size ; i++ ) {
                        link.setAttribute('download', response[i].split("/").pop());
                        link.setAttribute('href', response[i]);
                        link.click();
                    }
                    document.body.removeChild(link);
                }
            })
        }
    var finalUserName = '~$userName`';
    
	function sendAjax()
	{	
		userName=$('#userName').val();
		if(finalUserName.length > 0){
			userName =finalUserName;
		}else
		if(!userName){
		$("#RAMainTable").hide();
		$("#noUser").show();
		$("#dateError2").hide();
		$("#dateError3").hide();

			return;
		}

		
		$.ajax({
			'url':'/operations.php/profileVerification/FetchAbuseInvalidDataReport',
			'data':{'inputUser':userName},
			success:function(response)
			{ 

				try
				{	
					var mainDiv=$("#RAMainTable");
					mainDiv.find('.RARowHtml').remove();
					if(!response){
							$("#RAMainTable").hide();
							$("#noUser").hide();
							$("#dateError2").hide();
							$("#dateError3").show();
							return;

					}
					var jObject=JSON.parse(response);
					if(response)
					{
						if(jObject == 'No User With this name')
						{
								$("#RAMainTable").hide();
									$("#dateError2").show();
									$("#dateError3").hide();
									$("#noUser").hide();

						}
						else{
						
						for(i=0;i<jObject.length;i++)
						{
							htmlString=getRowHtml(jObject[i]);
							mainDiv.find('tr:last').after(htmlString);
						}

						$("#RAMainTable").show();
						$("#dateError2").hide();
						$("#noUser").hide();
						$("#dateError3").hide();
                                                $('.attach_id').on('click',downloadAttachment);
					} 
					
					}

				}	
				catch(e)
				{

					window.location.href="/jsadmin";
				}
			
			}


		})
	}

	$(document).ready(function(){
		if(finalUserName.length > 0){
			$("#userName").val(finalUserName);
			sendAjax();
		}
	});

	</script>
        		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 

		
	<br>
	</br>	
		<title>Success Story Selection Page</title>

                <link rel="stylesheet" href="../jsadmin/jeevansathi.css" type="text/css">
                <link rel="stylesheet" href="../profile/images/styles.css" type="text/css">

<!--<body bgcolor='#ADDFFF'>-->

<p align="center"><b>Welcome to Fetch Data for Abuse and Invalid Reported against a user.<i> Kindly Enter a valid Username and Press Submit</b></i></p>
</br>
<table width= "714" align="center" bgcolor="" border="0" style="width:614px; height:126px">
<tr>
<td><b>Enter Username</b></td>
<td>



<input type="text" id="userName">


</td>
<td><input type="button" value="Submit" name="submit" onclick="sendAjax();">
</td>
</tr>

<!--<input type="submit" value="submit" name="submit"> -->
</table>
<div id="noUser"  style="display:none;color:red;text-align: center;">*Please Type a User name</div>
<div id="dateError2"  style="display:none;color:red;text-align: center;">*Not a valid User Name .</div>
<table id='RAMainTable' style='display:none;' width="100%" border="0" cellpadding="4" cellspacing="4" align="center" >
<tr class="formhead" align="center">
<td  id='timePeriodText' colspan="100%"></td>
</tr>
<tr style="background-color: lightyellow;" class="label" align="center">
<td></td>
<td>Reporter</td>
<td>Type</td>
<td>Date</td>
<td>Reason</td>
<td>Other Reason</td>
<td>Attachment</td>
</tr>

</table>

<div id="dateError3"  style="display:none;color:red;text-align: center;">No records found for the given Username.</div>

</br>
</br>


~include_partial('global/footer')`