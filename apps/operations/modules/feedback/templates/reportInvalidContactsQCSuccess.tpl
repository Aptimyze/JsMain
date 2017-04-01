~include_partial('global/header')`

	<script type="text/javascript">
	var startDate,endDate,rowHtml="<tr class='label'></tr>",month,year,noOfDays;
		/*<td></td><td class='RAreportee'></td><td class='RAreporteeEmail'></td><td class='RAreporter'></td><td class='RAreporterEmail'></td><td class='RAcategory'></td><td class='RAOther'></td><td class='RADate'></td><td class='RACount'></td></tr>";
		*/
		var dayRow="<td></td>";
		var firstColumn = '<td align="center" width="6%"></td>';
		var defaultColumn = '<td align="center"></td>';
		var defaultColourColumn = "<td align='center'><font class= 'noActionClass'  color='green'></font><br><font class='unVerifiedClass' color='red'></font></td>";
		var lastRowColumn = "<td align='center'><font class= 'noActionClass'  color='green'></font><br><font class= 'unVerifiedClass'  color='red'></font><br><font class='totalClass' color='grey'></font></td>";
	function getRowHtml(rowJson){
		var row = $(rowHtml);	

		var temp2 = $(firstColumn);
		temp2.text(rowJson['user']);
		var rowHtml2 = "";
		rowHtml2+=temp2.outerHTML();
		var tempHtml=$(defaultColourColumn);

		for(j=1;j<=noOfDays;j++){
		tempHtml.find('.noActionClass').text(rowJson[j] ? rowJson[j].B ? rowJson[j].B:'-' : '-');
		tempHtml.find('.unVerifiedClass').text(rowJson[j] ? rowJson[j].N ? rowJson[j].N:'-' : '-');
		rowHtml2 += tempHtml.outerHTML();
		}

		return rowHtml2;

	}


	function parseDate(str) 
	{
    var mdy = str.split('-');
    return new Date(mdy[0], mdy[1], mdy[2]);
	}

	jQuery.fn.outerHTML = function(s) {
    return s
        ? this.before(s).remove()
        : jQuery("<p>").append(this.eq(0).clone()).html();
};
	
	function daydiff(first, second) 
	{
    return Math.round((second-first)/(1000*60*60*24));
	}

	function daysInMonth(month, year)
	{
		var monthStart = new Date(year, month-1, 1);
		var monthEnd = new Date(year, month, 1);
		var monthLength = (monthEnd - monthStart) / (1000 * 60 * 60 * 24);
		return monthLength;
	}
	function sendAjax()
	{	
		month = $("#monthEntered").val();
		year = $('#yearEntered').val();

		if(!month || !year){

		$("#RAMainTable").hide();
		$("#dateError").show();
		$("#dateError2").hide();
		$("#dateError3").hide();

			return;
		}

		$("#RAMainTable").show();
		$("#dateError").hide();


		$.ajax({
			'url':'/operations.php/feedback/reportInvalidContactsQCLog',
			'data':{'month':month,'year':year},
			success:function(response)
			{ 

			
					var mainDiv=$("#mainDiv");
//					mainDiv.find('.RARowHtml').remove();
					if(!response){
							$("#dateError3").show();
							return;

					}

					var jObject=JSON.parse(response);

					if(response)
					{
						var totalHtml;
						var htmlString='';
// first row head
						var first = $(firstColumn);
						first.text('Day');
						totalHtml += first.outerHTML();
						noOfDays = daysInMonth(parseInt(month),parseInt(year));
						var defColumn = $(defaultColumn);
						for(i=1;i<=noOfDays;i++)
						{
							defColumn.text(i);
							totalHtml +=defColumn.outerHTML();
						}	
						var row = $(rowHtml);	
						row.html(totalHtml);
						htmlString += row.outerHTML();
						mUserArray=jObject.OPS ? jObject.OPS : {};
						for(i=0;i<(mUserArray.length ? mUserArray.length : 0);i++)
						{
							console.log(i);
							row.html(getRowHtml(mUserArray[i]));
							htmlString+=row.outerHTML();
							//mainDiv.find('tr:last').after(htmlString);
						}

						var totalArray=jObject.TOTALARRAY ? jObject.TOTALARRAY : {} ;
						var reportArray=jObject.INVALID_REPORT ? jObject.INVALID_REPORT : {};
						row = $(lastRowColumn);

						row.find('.noActionClass').text('Total NO Action');
						row.find('.unVerifiedClass').text('Total UnVerified');
						row.find('.totalClass').text('Total Reported Invalid');
						htmlString+=row.outerHTML();
						for(j=1;j<=noOfDays;j++)
						{

							row.find('.noActionClass').text(totalArray[j] ? totalArray[j].B : '-');
							row.find('.unVerifiedClass').text(totalArray[j] ? totalArray[j].N : '-');
							row.find('.totalClass').text(reportArray[j] ? reportArray[j] : '-');
							htmlString+=row.outerHTML();
							//mainDiv.find('tr:last').after(htmlString);
						}

						mainDiv.html(htmlString);						
						mainDiv.show();
				}	
			}


		})
	}

	</script>
	 <table width=40% border=0 align="center" cellpadding=5>
	<tr>
	<td>
	Month:
	</td>
	<td>
		<select name="monthEntered" id='monthEntered'>
			~foreach from=$monthArray key=k item=v`
			~if $k!=$todMonth`
			<option value=~$k`>~$v`</option>
			~else`
			<option value=~$k` selected="selected">~$v`</option>
			~/if`
			~/foreach`
		</select>
	</td>
	</tr>
	<tr>
	<td>
	Year:
	</td>
	<td>
		<select name="yearEntered" id ='yearEntered' >
			~foreach from=$yearArray key=k item=v`
			~if $v!=$todYear`
			<option value=~$v`>~$v`</option>
			~else`
			<option value=~$v` selected="selected">~$v`</option>
			~/if`
			~/foreach`
		</select>
	</td>
	</tr>

	<div style='text-align:center;'></div>
	<tr>
	<td width=100% align="center">
       <input type="hidden" name="checksum" value=~$CHECKSUM`></input>
	<input type='button' name="CMDGo" onclick ='sendAjax();' value="GO!"></input>
	</td>
	</tr>
	</table>

<table width="100%" border="0" align="center">
<tbody><tr><td><font color="green">For No Action  Color=Green</font></td></tr>
<tr><td><font color="red">For Invalid Marked  Color=Red</font></td></tr>
<tr class="formhead">
<td colspan="100%"  align="center">&nbsp;Month :  <span id='monthLabel' ></span>  Year : <span id='yearLabel'></span></td>
</tr>
</tbody>
</table>


<table id ='mainDiv' width="200%" style='display: none;'>
</table>	


<div id ='dataDiv'></div>

~include_partial('global/footer')`