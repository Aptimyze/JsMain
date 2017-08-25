~include_partial('global/header')`

	<script type="text/javascript">
	var startDate,endDate,rowHtml="<tr style='font-size:15px' class='label RARowHtml' align='center'><td></td><td class='RAreportee'></td><td class='RAreporteeEmail'></td><td class='RAreporter'></td><td class='RAreporterEmail'></td><td class='RAcategory'></td><td class='RAOther'></td><td class='RADate'></td><td class='RACount'></td><td class='Attachment'><input class='attach_id' id='-1' type='button' disabled value='Download'></td></tr>";
	function getRowHtml(rowJson){

		var tempHtml=$(rowHtml);
		tempHtml.find('.RAreportee').text(rowJson.reportee_id);
		tempHtml.find('.RAreporteeEmail').text(rowJson.reportee_email);
		tempHtml.find('.RAreporter').text(rowJson.reporter_id);
		tempHtml.find('.RAreporterEmail').text(rowJson.reporter_email);
		tempHtml.find('.RAcategory').text(rowJson.reason);
		tempHtml.find('.RAOther').text(rowJson.comments);
		tempHtml.find('.RADate').text(rowJson.timestamp);
		tempHtml.find('.RACount').text(rowJson.count);
                if(rowJson.attachment_id != -1) {
                    tempHtml.find('.attach_id').prop('disabled',false).attr('id',rowJson.attachment_id);
                }
		return tempHtml;

	}

        function downloadAttachment()
        {
            var id = $(this).attr('id');
            if(id == -1) {
                return;
            }
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
        
	function parseDate(str) 
	{
    var mdy = str.split('-');
    return new Date(mdy[0], mdy[1], mdy[2]);
	}

	
	function daydiff(first, second) 
	{
    return Math.round((second-first)/(1000*60*60*24));
	}


	function sendAjax()
	{	
		startDate=$('#startDate').val();
		endDate=$('#endDate').val();

		if(!startDate || !endDate){

		$("#RAMainTable").hide();
		$("#dateError").show();
		$("#dateError2").hide();
		$("#dateError3").hide();

			return;
		}

		if(startDate>endDate){

		$("#RAMainTable").hide();
		$("#dateError2").text("*Start date cannot be greater than End date.").show();
		$("#dateError").hide();
		$("#dateError3").hide();

		return;
		}


		if( daydiff( parseDate( $('#startDate').val()), parseDate($('#endDate').val() ) ) >30) 
		{

		$("#RAMainTable").hide();
		$("#dateError2").text("The maximum day difference is of 30 days. Please search for a narrow date duration.").show();
		$("#dateError").hide();
		$("#dateError3").hide();

		return;
		}

		$("#RAMainTable").show();
		$("#dateError2").hide();
		$("#dateError").hide();
		$("#dateError3").hide();


		$("#timePeriodText").text('Selected Time Period '+startDate+' To '+ endDate);
		$.ajax({
			'url':'/operations.php/feedback/reportAbuseLog',
			'data':{'RAStartDate':startDate,'RAEndDate':endDate},
			success:function(response)
			{ 

				try
				{	
					var mainDiv=$("#RAMainTable");
					mainDiv.find('.RARowHtml').remove();
					if(!response){
							$("#dateError3").show();
							return;

					}
					var jObject=JSON.parse(response);
					if(response)
					{
						
						for(i=0;i<jObject.length;i++)
						{
							htmlString=getRowHtml(jObject[i]);
							mainDiv.find('tr:last').after(htmlString);
						}
                                            $('.attach_id').on('click',downloadAttachment);
					} 
					
					

				}	
				catch(e)
				{

					window.location.href="/jsadmin";
				}
			
			}


		})
	}

	</script>
        		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 

		
	<br>
	</br>	
		<title>Success Story Selection Page</title>

                <link rel="stylesheet" href="../jsadmin/jeevansathi.css" type="text/css">
                <link rel="stylesheet" href="../profile/images/styles.css" type="text/css">

<!--<body bgcolor='#ADDFFF'>-->

<p align="center"><b>Welcome to Report Abuse History Page<i> Kindly Enter Date Range Below and Press Submit</b></i></p>
</br>
<table width= "714" align="center" bgcolor="" border="0" style="width:614px; height:126px">
<tr>
<td><b>Select Your Date Range</b></td>
<td>



<input type="date" id="startDate">


<b><i>To</i></b>

<input type="date" id="endDate">


</td>
<td><input type="button" value="Submit" name="submit" onclick="sendAjax();">
</td>
</tr>

<!--<input type="submit" value="submit" name="submit"> -->
</table>
<div id="dateError"  style="display:none;color:red;text-align: center;">*Please select both the dates</div>
<div id="dateError2"  style="display:none;color:red;text-align: center;">*Start date cannot be greater than End date.</div>
<table id='RAMainTable' style='display:none;' width="100%" border="0" cellpadding="4" cellspacing="4" align="center" >
<tr class="formhead" align="center">
<td  id='timePeriodText' colspan="100%"></td>
</tr>
<tr style="background-color: lightyellow;" class="label" align="center">
<td></td>
<td>REPORTEE</td>
<td>REPORTEE EMAIL</td>
<td>REPORTER</td>
<td>REPORTER EMAIL</td>
<td>CATEGORY</td>
<td>OTHER REASON</td>
<td>DATE</td>
<td>COUNT</td>
<td>Attachment</td>
</tr>

</table>

<div id="dateError3"  style="display:none;color:red;text-align: center;">No records found for the given duration.</div>

</br>
</br>


~include_partial('global/footer')`