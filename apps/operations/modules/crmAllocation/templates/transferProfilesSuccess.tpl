<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	  	<title>JeevanSathi</title>
	  	<script language="javascript">
	  	<!--
	  	function go(){
			document.getElementById("sub").style.display="none";
			document.getElementById("process").style.display="block";	
		  }
		  //-->
	  	</script>
	</meta>	
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
~include_partial('global/header')`
	<form name="form1" action="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/transferProfiles" method="post">
	<input type="hidden" name="subMethod" value="~$subMethod`">
	<input type="hidden" name="cid" value="~$cid`">
	<input type="hidden" name="singleProfile" value="~$singleProfile`">
	~if $profilesTransferred eq ''`
        <table width=760 align="center">
		~if $subMethod eq "FRESH"`
                        <td align="CENTER" class="fieldsnew"><font size=2>&nbsp;Transfer today's fresh profiles </font></td>
		~else`			
			<tr class="formhead">
				<td align="center" width="50%">&nbsp;Transfer profiles of
					~html_select_date prefix='Allocation' start_year='-2' end_year='+1'`
				</td>
			</tr>
		~/if`
		<tr>
			<td align="center" width="50%">
		                <select name="agentFrom">
					~foreach from=$agentList item=agentName key=agentK`
                                		<option value=~$agentName`>~$agentName`</option>
		                        ~/foreach`
                		</select> to
                		<select name="agentTo">
					~foreach from=$agentList item=agentName key=agentK`
						<option value=~$agentName`>~$agentName`</option>
		                        ~/foreach`
		                </select>
                	</td>
		</tr>
		~if $singleProfile eq 1`
			<tr class="formhead">
				<td align="center" width="50%">~if $errorUsername eq 'NO_USERNAME'`<font color="red"> Username* </font> ~else` Username* ~/if`
                                ~if $errorUsername eq 'WRONG_USERNAME'`
                                      <font color="red">
                                                Username does not exist
                                      </font>
                                ~/if`
				~if $errorAlloted eq 'NOT_ALLOTED'`
                                      <font color="red">
                                                Username currently not alloted to agentFrom
                                      </font>
                                ~/if`
				<input type='text' name='userName' value=''>
                                </td>
                        </tr>
		~/if`
		<tr align="CENTER" class="fieldsnew" id="sub" style="display:block;">
		      <td colspan="2"><input type="submit" name="submit" value="Transfer" onclick="go();">&nbsp;&nbsp;&nbsp;</td>
     		</tr>
		<tr align="CENTER" class="fieldsnew" id="process" style="display:none;">
                      <td colspan="2">Profile transferring please wait...&nbsp;&nbsp;&nbsp;</td>
                </tr>
        </table>
	~else`<p align="center">
		~if $profilesCnt gt 0`
			 ~if $singleProfile eq 1`
				~$profilesCnt` profiles transferred from ~$agentFrom` to ~$agentTo`. ~if $subMethod eq 'FIELD_SALES'` ~$remainingCnt` profiles left that can be transferred. ~/if`<a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/transferProfiles?cid=~$cid`&subMethod=~$subMethod`&singleProfile=1">&nbsp<b>Next</b></a>
			~else`
				~$profilesCnt` profiles transferred from ~$agentFrom` to ~$agentTo`. ~if $subMethod eq 'FIELD_SALES'` ~$remainingCnt` profiles left that can be transferred. ~/if`<a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/transferProfiles?cid=~$cid`&subMethod=~$subMethod`">&nbsp<b>Next</b></a> 
			~/if` 
		~else`
			~if $singleProfile eq 1`
				 No profile alloted to ~$agentTo` on ~$allocationDt` <a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/transferProfiles?cid=~$cid`&subMethod=~$subMethod`&singleProfile=1">&nbsp<b>Back</b></a>
			~else`
				No profile alloted to ~$agentTo` on ~$allocationDt` <a href="~sfConfig::get('app_site_url')`/operations.php/crmAllocation/transferProfiles?cid=~$cid`&subMethod=~$subMethod`">&nbsp<b>Back</b></a>
			~/if`
		~/if`
	</p>
	~/if`
	</form>
  <br><br><br><br>
  ~include_partial('global/footer')`
 </body
</html>
