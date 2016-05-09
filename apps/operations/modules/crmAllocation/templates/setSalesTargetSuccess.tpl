<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	  	<title>JeevanSathi</title>

                <script>
                function run(){
			var m = document.form1.getElementById("month");
			var monthName = m.options[m.selectedIndex].value;
			
			var y = document.form1.getElementById("year");
			var yearName = y.options[y.selectedIndex].value;
                }
                </script>
        </meta> 

</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
~include_partial('global/header')`
	<form name="form1" action="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/setSalesTarget" method="post">
	<input type="hidden" name="cid" value="~$cid`">
	<input type="hidden" name="monthName" value="~$monthName`">
	<input type="hidden" name="yearName" value="~$yearName`">
        <table width=760 align="center">
		<br>
                <tr class="formhead">
                        <td align="center" width="70%">&nbsp; Select Month, Year and Fortnight:
				<select id="month" name="monthValue" onchange="run()">
					~foreach from=$monthArr item=monthVal`
					      <option value="~$monthVal`" ~if $monthVal eq $monthName`selected ~/if`>~$monthVal`</option>
					~/foreach`
				</select>
				<select id="year" name="yearValue" onchange="run()">
					~foreach from=$yearArr item=yearVal`
                                              <option value="~$yearVal`" ~if $yearVal eq $yearName`selected ~/if`>~$yearVal`</option>
					~/foreach`
				</select>
                <select id="fortnight" name="fortnightValue" >
                    <option value="1" ~if $fortnight eq "1"` selected ~/if` >H1</option>
                    <option value="2" ~if $fortnight eq "2"` selected ~/if`>H2</option>
                </select>
				<td align="center" class=fieldsnew colspan=30%>
		        		<input type=Hidden  name=cid    value=~$cid`>
	        			<input type=submit  name=show value=SHOW>
				</td>
			</td>
                </tr>

		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr></tr>
		
		~if $overall_sales_head_check==1 or $editable_error==1`
			~if $overall_sales_head_check == 1`		
				<tr> <td class=fieldsnew align=center><font size=3 color=red><b> Please give 'Sales Head - Overall' privilege to one user. </b></font></td> </tr>
			~/if`
			~if $editable_error == 1`		
				<tr> <td class=fieldsnew align=center><font size=3 color=red><b> Update is not possible for the past data !! </b></font></td> </tr>
			~/if`

		~else`
		~if $SUBMIT_STATUS eq 0`
		<tr>
	        	<th class=fieldsnew><font size=2> NAME </font></th>
	        	<th class=fieldsnew><font size=2> INDIVIDUAL TARGET </font></th>
	        	<th class=fieldsnew><font size=2> FINAL TARGET </font></th>
		</tr>
		<tr></tr>
		<tr></tr>

		~if $editable eq 1`
		~foreach from=$targetInfo name=info item=h`
		<tr>
	        	<td class=fieldsnew><font size=2> ~if $targetInfo[$smarty.foreach.info.index]['DIRECT_REPORTEE_STATUS'] eq 1` ~for $it=0 to $targetInfo[$smarty.foreach.info.index]['LEVEL']` &nbsp;&nbsp;&nbsp; ~/for` <b>~$targetInfo[$smarty.foreach.info.index]['USERNAME']`</b> ~else` ~for $it=0 to $targetInfo[$smarty.foreach.info.index]['LEVEL']` &nbsp;&nbsp;&nbsp; ~/for` ~$targetInfo[$smarty.foreach.info.index]['USERNAME']` ~/if` </font></td>
	        	
			<td class=fieldsnew><input type=number min="0" style="text-align:right" name=INDIVIDUAL_TARGET[~$targetInfo[$smarty.foreach.info.index]['USERNAME']`] value="~$targetInfo[$smarty.foreach.info.index]['INDIVIDUAL_TARGET']`"></td>
	        	
			<td class=fieldsnew width="40%" align="right"><font size=2> ~$targetInfo[$smarty.foreach.info.index]['FINAL_TARGET']`</font></td>
		</tr>
		~/foreach`
		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr></tr>
	
		<tr align=center>
			<td class=fieldsnew colspan=100%>
        			<input type=Hidden  name=cid    value=~$cid`>
	        		<input type=submit  name=calculate value=CALCULATE>
	        		<input type=submit  name=submit value=SUBMIT>
			</td>
		</tr>
		~/if`

		~if $editable eq 0`
		~foreach from=$targetInfo name=info item=h`
		<tr>
	        	<td class=fieldsnew><font size=2> ~if $targetInfo[$smarty.foreach.info.index]['HAS_DIRECT_REPORTEE'] eq 1` ~for $it=0 to $targetInfo[$smarty.foreach.info.index]['MONTHWISE_LEVEL']` &nbsp;&nbsp;&nbsp; ~/for` <b>~$targetInfo[$smarty.foreach.info.index]['USERNAME']`</b> ~else` ~for $it=0 to $targetInfo[$smarty.foreach.info.index]['MONTHWISE_LEVEL']` &nbsp;&nbsp;&nbsp; ~/for` ~$targetInfo[$smarty.foreach.info.index]['USERNAME']` ~/if` </font></td>
	        	<td class=fieldsnew align=right><font size=2> ~$targetInfo[$smarty.foreach.info.index]['INDIVIDUAL_TARGET']` </font></td>
	        	<td class=fieldsnew align=right><font size=2> ~$targetInfo[$smarty.foreach.info.index]['FINAL_TARGET']` </font></td>
		</tr>
		~/foreach`
		~/if`
				
		~/if`

		~if $SUBMIT_STATUS eq 1`
		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr> <td class=fieldsnew align=center><font size=3 color=green><b> UPDATED SUCCESSFULLY!! </b></font></td> </tr>
		~/if`
		~/if`
		
        </table>
	</form>
  <br><br><br><br>
  ~include_partial('global/footer')`
</body>
</html>
