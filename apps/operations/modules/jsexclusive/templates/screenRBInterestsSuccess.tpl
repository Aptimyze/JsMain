~include_partial('global/header',["showExclusiveServicingBack"=>'Y'])`
<body>
	<br>
	<div style="background-color:lightblue;text-align:center;font-size:12px;width:80%;margin-left:131px;">
		<div style="font-weight:bold;"><font size=4px>Screen RB Interests</font></div>
		<div style="margin-left:704px;"><font size=4px>Clients left-~$unscreenedClientsCount`</font></div>
		<br>
		~if $infoMsg`
			<div>~$infoMsg`</div>
		~/if`
	</div>
	<div style="text-align:center;font-size:12px;width:80%;margin-left:131px;">
		~if $clientData`
		<div style="font-size: 15px">Client-<a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$clientId`" target="_blank">~$clientData.clientUsername`</a></div>
		~if $clientData.HoroscopeMatch eq 'Y'`
			<div style="font-size: 20px">Horoscope match is Necessary</div>
		~/if`
	~/if`
	</div>
	<br>
	<form name="screenRBForm" action="~sfConfig::get('app_site_url')`/operations.php/jsexclusive/submitScreenRBInterests" method="post">
		<input type="hidden" name="clientIndex" value="~$clientIndex`">
		<input type="hidden" name="clientId" value="~$clientId`">

 		<table border="0" align="center" width="80%" table-layout="auto" style="
    border-spacing: 10px;">
			~if $pogRBInterestsPool` 
				~foreach from=$pogRBInterestsPool item=valued key=k`
					<tr class="formhead" align="center">
					    <td height="21" align="CENTER"><a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$valued.PROFILEID`" target="_blank">~$valued.USERNAME`</a>
					    </td>
					    ~if $valued.GUNA_SCORE`
					    	<td height="10" align="CENTER"><div>~$valued.GUNA_SCORE`/36</div></td>
						~/if`
				    </tr>
					<tr class="formhead" align="left">
					    <td height="21" align="CENTER"><img src="~$valued.PHOTO_URL`">
					    </td>
					    <td height="21" align="CENTER">~$valued.ABOUT_ME`</td>
					    <td height="21" align="CENTER"><input type="checkbox" name="DISCARD[]" value="~$valued.PROFILEID`">DISCARD<input type="hidden" name="ACCEPT[]" value="~$valued.PROFILEID`"></td></td>
				    </tr>
				    
					<tr align="center">
				    	<td height="10" align="CENTER"></td>
				    </tr>
				~/foreach`
				<br>
				<tr align="center">
					<td class="label" colspan="2" style="background-color:Moccasin">
						<input type="submit" name="submit" value="SUBMIT">
					</td>
				</tr>
		    ~else if $showNextButton eq 'Y'`
		    	<br>
				<tr align="center">
					<td class="label" colspan="2" style="background-color:Moccasin">
						<input type="submit" name="submit" value="NEXT">
					</td>
				</tr>
		    ~/if`     	
 		</table>
 	</form>
</br>
~include_partial('global/footer')`
 </body>
