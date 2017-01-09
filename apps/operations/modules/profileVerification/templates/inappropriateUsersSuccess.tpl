~include_partial('global/header')`

	<script type="text/javascript">
	var startDate,endDate,rowHtml="<tr style='font-size:15px' class='label RARowHtml' align='center'><td></td><td class='IUUsername'></td><td class='IUReligion'></td><td class='IUMarriage'></td><td class='IUAge'></td><td class='IUTotalScore'></td><td class='abuseReported'></td><td class='invalidReported'></td></tr>";
	function getRowHtml(rowJson){

		var tempHtml=$(rowHtml);
		tempHtml.find('.IUUsername').text(rowJson.USERNAME);
		tempHtml.find('.IUReligion').text(rowJson.RELIGION_COUNT);
		tempHtml.find('.IUMarriage').text(rowJson.MSTATUS_COUNT);
		tempHtml.find('.IUAge').text(rowJson.AGE_COUNT);
		tempHtml.find('.IUTotalScore').text(rowJson.TOTAL_SCORE);
		tempHtml.find('.abuseReported').text(rowJson.REPORT_ABUSE_COUNT);
		tempHtml.find('.invalidReported').text(rowJson.REPORT_INVALID_COUNT);
		return tempHtml;

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

        function getTodayDate(){
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; //January is 0!
            var yyyy = today.getFullYear();

            if(dd<10) {
                dd='0'+dd
            } 

            if(mm<10) {
                mm='0'+mm
            } 

            today = yyyy+'-'+mm+'-'+dd;
            return today;
            
        }
	function sendAjax()
	{	
		startDate=$('#startDate').val();
		if(!startDate){

		$("#RAMainTable").hide();
		$("#dateError").show();
		$("#dateError2").hide();
		$("#dateError3").hide();

			return;
		}


		if( daydiff( parseDate( $('#startDate').val()), parseDate(getTodayDate())) >7) 
		{

		$("#RAMainTable").hide();
		$("#dateError2").text("Please select a date not more than 7 days ago.").show();
		$("#dateError").hide();
		$("#dateError3").hide();

		return;
		}
                if( daydiff( parseDate( $('#startDate').val()), parseDate(getTodayDate())) <0) 
		{

		$("#RAMainTable").hide();
		$("#dateError2").text("Please select a valid date (earlier or equal to today's Date)").show();
		$("#dateError").hide();
		$("#dateError3").hide();

		return;
		}

		$("#RAMainTable").show();
		$("#dateError2").hide();
		$("#dateError").hide();
		$("#dateError3").hide();


		$("#timePeriodText").text('Selected Date :'+startDate);
		$.ajax({
			'url':'/operations.php/profileVerification/InappropriateUsersReport',
			'data':{'RAStartDate':startDate},
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

<p align="center"><b>Welcome to Inappropriate Users Reports Page<i> Kindly Enter Date Below and Press Submit</b></i></p>
</br>
<table width= "714" align="center" bgcolor="" border="0" style="width:614px; height:126px">
<tr>
<td><b>Select Your Date</b></td>
<td>



<input type="date" id="startDate">


</td>
<td><input type="button" value="Submit" name="submit" onclick="sendAjax();">
</td>
</tr>

<!--<input type="submit" value="submit" name="submit"> -->
</table>
<div id="dateError"  style="display:none;color:red;text-align: center;">*Please select the date</div>
<div id="dateError2"  style="display:none;color:red;text-align: center;">*Start date cannot be greater than End date.</div>
<table id='RAMainTable' style='display:none;' width="100%" border="0" cellpadding="4" cellspacing="4" align="center" >
<tr class="formhead" align="center">
<td  id='timePeriodText' colspan="100%"></td>
</tr>
<tr style="background-color: lightyellow;" class="label" align="center">
<td></td>
<td>Username</td>
<td>Outside Religion Contact</td>
<td>Outside Marital Status Contact</td>
<td>Outside Age Bracket Contact</td>
<td>Overall negative score</td>
<td>Abused Reported</td>
<td>Invalid Reported</td>
</tr>

</table>

<div id="dateError3"  style="display:none;color:red;text-align: center;">No records found for the given date.</div>

</br>
</br>


~include_partial('global/footer')`