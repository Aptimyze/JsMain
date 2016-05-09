~include_partial('global/header')`
<script>
    $(document).ready(function(){
         $('#reset').click(function(event){
             var cid = $("#cid").val();
             window.location.href = "~sfConfig::get('app_site_url')`/operations.php/bms/trackTransactionDetails?source=master&cid="+cid;
         });
         $('#submit').click(function(event){
            var transactionId = $("#transactionId").val();
            if($.trim(transactionId)==='') // check for empty transaction id
            {
                alert("Please enter transaction Id and search again.");
                return false;
            }
            var regex = /^[0-9\b\s*]+$/;    // allow only numbers [0-9] and spaces
            if( !regex.test(transactionId) ) {
                alert("Please enter numeric transaction Id and search again.");
                return false;
            }
         });
    });
</script>
	<form action="~sfConfig::get('app_site_url')`/operations.php/bms/trackTransactionDetails?source=master&cid=~$cid`" method="POST">
		<input type=hidden name="cid" id ="cid" value="~$cid`">
		<table width=760 align="CENTER" >
			<tr class="formhead" align="CENTER" >
				<td colspan=3>Track Transaction by ID
			</tr>
			<tr align="CENTER">
				<td width=30%>Enter Transaction ID
				</td>
				<td align="center"><input type="text" id='transactionId' name="transactionId" value="~$transactionId`" >
				</td>
				~if $error && $transactionId`
                                    <td>
                                        <font color="red">
                                                Wrong Transaction ID entered!
                                        </font>
                                    </td>
				~/if`
			</tr>
                        <tr align="CENTER">
                            <td colspan=3>
                                <input type="reset" id="reset" name="submit" value="Reset">
                                <input type="submit" id="submit" name="submit" value="Search">
                            <td>    
                        </tr>
		   </table>
	</form>
~if $transactionData && !$error`
    <table width=760 align="CENTER" id ="trans_data">
        <tr class="formhead" align="CENTER" style="background-color:lightblue";>
            <td width=20%>Transaction ID</td>
            <td width=20%>Company Name</td>
            <td width=15%>Start Date</td>
            <td width=15%>End Date</td>
            <td width=30%>Sale Description</td>
        </tr>
        <tr align="CENTER">
            <td align="center">
                ~$transactionData['SALEID']`
            </td>
            <td align="center">
                ~$transactionData['COMP_NAME']`
            </td>
            <td align="center">
                ~$transactionData['START_DATE']`
            </td>
            <td align="center">
                ~$transactionData['END_DATE']`
            </td>
            <td align="center">
                ~$transactionData['SALE_DES']`
            </td>
        </tr>
    </table>
~/if`
~include_partial('global/footer')`