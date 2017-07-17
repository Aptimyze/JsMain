~include_partial('global/header')`
 <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
 	<form name="screenRBForm" action="~sfConfig::get('app_site_url')`/operations.php/crmMis/gatewayWiseMis" method="post">
 		<div width="60%" style="background-color:lightblue;text-align:center;font-size:12px;font-weight:bold;">
			<div>Screen RB Interests</div>
			<br>
			~if $infoMsg`
				<div>~$infoMsg`</div>
			~else`
				<div>Client-<a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$clientId`" target="_blank">~$clientId`</a></div>
			~/if`
			<br>
		</div>

 		<table border="0" align="center" width="60%" table-layout="auto">
			~if $pogRBInterestsPool` 
				~foreach from=$pogRBInterestsPool item=valued key=pid`
					<tr class="formhead" align="center">
					    <td height="21" align="CENTER">
					    ~$valued.USERNAME`
					    </td>
					    <br>
				    </tr>
				~/foreach`
		    ~/if`     	
 		</table>
 	</form>
</br>
~include_partial('global/footer')`
 </body>
