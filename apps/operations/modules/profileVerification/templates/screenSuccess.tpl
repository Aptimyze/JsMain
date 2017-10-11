~include_partial('global/header')`
<style>
	tr {height:20px;}
</style>
<br>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" font-size:1em;>
	~if $SubmissionError`
		<div align="center" style="margin-top:20px;color:red;">
			<b>Submission Error</b> 
			<div style="font-size:18px;margin-top:8px;"><a href="#" onclick="goBack();" >Go Back</a></div>
		</div>
	~else`
	 <div align="right" style="margin-top:20px;margin-bottom:10px;margin-right:20%;">
         	<b>No of Profiles yet to be Screened :~$totalUnscreened` </b>
         </div>
        <div align=center style="margin-bottom:5px;font-size:10px;"><b><h2>Search pending screening queue</h2> </b></div>
	 <table width="600" border="1" cellspacing="1" cellpadding='3' ALIGN="CENTER" >
		<form name="searchUser" method="POST" action="~sfConfig::get('app_site_url')`/operations.php/profileVerification/screen">
		    <tr class=label align=center>
                        <td width=20%>&nbsp;Enter Username to Search:</td>
                        <td width=10%>
				<input name="username">
				~if $userNameInvalid==1`
					<div align="center" style="margin-top:5px;color:red;">
						<b>Enter a valid Username.</b> 
					</div>
				~/if`
			</td>	
                        <td width=10%><input type="submit" name="Submit" value="Search" >&nbsp;&nbsp;&nbsp;</td>
                    </tr>
			<input type=hidden name="name" value="~$name`">
			<input type=hidden name="cid" value="~$cid`">
		</form>
         </table>

	~if $noProfileAvailable || $successfulSubmission || $noDocsAvailable || $userNameInvalid`	
		<div align="center" style="margin-top:20px;color:red;font-color:">
			~if $noProfileAvailable`
				<b>No More Profiles to be Screened. Please try after some time.</b> 
			~/if`

			~if $successfulSubmission`
				<span style="color:green;"><b>Verification Status of all documents successfully updated.</b></span> 
			~/if`

			~if $noDocsAvailable`
				<b>No document pending for this profile.</b> 
			~/if`
			<div style="font-size:18px;margin-top:8px;"><a href="~sfConfig::get('app_site_url')`/operations.php/profileVerification/screen?cid=~$cid`&name=~$name`">Continue</a></div>
		</div>
		
	~else`
 	<div align=center><br><b>Screen Documents for User Name : ~$username` </b></div><br></br>
	<form name="list" id="formScreenSuc" enctype="multipart/form-data" action="~sfConfig::get('app_site_url')`/operations.php/profileVerification/screenSubmit" method="POST">
		<table width="100%" border="0" cellspacing="1" cellpadding='3' ALIGN="CENTER" >
			<tr class=label align=center border=no>
				<td width=10%>SI. NO.</td>
				<td width=10%>Profile Attribute</td>
				<td width=20%>Document Type</td>
				<td width=30%>Value</td>
				<td width=30%>Action(accept/decline)</td>
				
			</tr>	

			~foreach from=$documentArr key=key item=value name=attribute`
			<input type=hidden name="allDocIds[]" value='~$value["DOCUMENT_ID"]`'>
			<tr>
				<td align=center>~$smarty.foreach.attribute.index +1`</td>
                                <td align=center>~$docAttributes[~$value['ATTRIBUTE']`]`</td>
                                <td align=center>~$docs[~$value['DOCUMENT_TYPE']`]`</td>
                                <td align=center>~$value['VERIFICATION_VALUE']`</td>
                                <td align=center>
					</span>
					<input type="radio" name='actionTaken[~$value["DOCUMENT_ID"]`]' value="Y">Accept&nbsp;&nbsp;&nbsp;
					<input type="radio" name='actionTaken[~$value["DOCUMENT_ID"]`]' value="N">Decline
				</td>
			</tr>
			<tr align = "CENTER">
				<td colspan="5" align=center>
					<img src="~$value['DOCURL']`"></img>
				</td>
			</tr>
			<tr style="border-spacing:0 5px;border-spacing:0 5px;"></tr>
			~/foreach`
 			<tr align = "CENTER">
				<input type=hidden name="name" value="~$name`">
				<input type=hidden name="cid" value="~$cid`">
				<input type=hidden name="profileid" value="~$profileid`">
                		<td colspan="5"><br></br><input type="submit" name="Submit" value="Submit" >&nbsp;&nbsp;&nbsp;
                	</tr>
		</table>
	</form>
	~/if`
	~/if`
	<br><br><br></br></br></br>
	~include_partial('global/footer')`
	<script>
	window.onload = function (){
		$("#formScreenSuc").submit(function(){
		if(($(':radio').length/2) !== $(':radio:checked').length)
		{
			alert('Please select Accept/Decline for all documents');
			event.preventDefault();
		}
		});
	}	
	function goBack()
	{
		window.history.go(-2);
	}
	</script>
</body>
