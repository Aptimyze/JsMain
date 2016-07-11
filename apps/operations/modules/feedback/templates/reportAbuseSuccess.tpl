~include_partial('global/header')`

	<script type="text/javascript">
	var startDate,endDate,rowHtml="<tr style='font-size:15px' class='label RARowHtml' align='center'><td></td><td class='RAreportee'></td><td class='RAreporteeEmail'></td><td class='RAreporter'></td><td class='RAreporterEmail'></td><td class='RAcategory'></td><td class='RAOther'></td><td class='RADate'></td></tr>";
	function getRowHtml(rowJson){

		var tempHtml=$(rowHtml);
		tempHtml.find('.RAreportee').text(rowJson.reportee_id);
		tempHtml.find('.RAreporteeEmail').text(rowJson.reportee_email);
		tempHtml.find('.RAreporter').text(rowJson.reporter_id);
		tempHtml.find('.RAreporterEmail').text(rowJson.reporter_email);
		tempHtml.find('.RAcategory').text(rowJson.reason);
		tempHtml.find('.RAOther').text(rowJson.comments);
		tempHtml.find('.RADate').text(rowJson.timestamp);
		return tempHtml;

	}
	function sendAjax()
	{	
		startDate=$('#startDate').val();
		endDate=$('#endDate').val();

		if(!startDate || !endDate){

		$("#RAMainTable").hide();
		$("#dateError").show();
		$("#dateError2").hide();

			return;
		}

		if(startDate>endDate){

		$("#RAMainTable").hide();
		$("#dateError2").show();
		$("#dateError").hide();

		return;
		}

		$("#RAMainTable").show();
		$("#dateError2").hide();
		$("#dateError").hide();


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
					var jObject=JSON.parse(response);
					if(response)
					{
						
						for(i=0;i<jObject.length;i++)
						{
							htmlString=getRowHtml(jObject[i]);
							mainDiv.find('tr:last').after(htmlString);
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
</tr>

</table>
</br>
</br>


~include_partial('global/footer')`