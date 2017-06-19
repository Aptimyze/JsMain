<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	  	<title>JeevanSathi</title>
	  	<script language="javascript">
	  	<!--
		function popLimit(thisform){
			var docF=document.form1;	
			centerName =docF.elements['locality'].value;
			if(centerName){
				var limitArr=new Array();
				~foreach from=$fieldSalesLocalityLimitArr item=limitVal key=centerKey`
					limitArr['~$centerKey`']="~$limitVal`";
				~/foreach`
				limit =limitArr[centerName];
				docF.elements['limit'].value=limit;
			}
			else
				docF.elements['limit'].value='0';	
		}
		//-->
	  	</script>
	</meta>	
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
~include_partial('global/header')`
	<form name="form1" action="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/fieldSalesAllocationLimit" method="post">
	<input type="hidden" name="subMethod" value="~$subMethod`">
	<input type="hidden" name="cid" value="~$cid`">
        <table width=760 align="center">
		<br>
		~if $error`
			<tr class="formhead">
				<td align="center"><font color="red">
					Please select valid Locality/ Limit
					</font>
				</td>	
			</tr>	
		~elseif $success`
                        <tr class="formhead">
                                <td align="center">
                                       	Limit Update Successfully 
                                        </font>
                                </td>
                        </tr>
		~/if`
		<br>
                <tr class="formhead">
                        <td align="center" width="50%">&nbsp; Set Allocation Limit for locality:
				<select name="locality" onChange="popLimit(this);">
					<option value=''>Select Locality</option>	
					~foreach from=$fieldSalesLocalityLimitArr item=limitVal key=localityName`
						<option value="~$localityName`" ~if $center eq $localityName`selected ~/if`>~$localityName`</option>
					~/foreach`
				</select>
			</td>
                </tr>
		<tr>
			<td align="center" width="20%">
				~if $errorLimit`
					<font color="red">Please enter integer value</font>
				~/if`
				<input type="text" name="limit" value="~$limit`">
                	</td>
		</tr>
		<tr align="CENTER" class="fieldsnew" id="sub" style="display:block;">
		      <td colspan="2"><input type="submit" name="submit" value="submit">&nbsp;&nbsp;&nbsp;</td>
     		</tr>
        </table>
	</form>
  <br><br><br><br>
  ~include_partial('global/footer')`
 </body
</html>
