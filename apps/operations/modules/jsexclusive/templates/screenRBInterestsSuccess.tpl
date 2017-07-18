~include_partial('global/header',["showExclusiveServicingBack"=>'Y'])`
<body>
	<br>
	<div style="background-color:lightblue;text-align:center;font-size:12px;font-weight:bold;width:80%;margin-left:131px;">
	<div><font size=4px>Screen RB Interests</font></div>
	<br>
	~if $infoMsg`
		<div>~$infoMsg`</div>
	~/if`
	~if $clientData`
		<div>Client-<a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$clientId`" target="_blank">~$clientData.clientUsername`</a></div>
		~if $clientData.HoroscopeMatch eq 'Y'`
			<div>Horoscope match is Necessary</div>
		~/if`
	~/if`
	</br>
	</div>
	<br>
	<form name="screenRBForm" action="~sfConfig::get('app_site_url')`/operations.php/jsexclusive/submitScreenRBInterests" method="post">
		<input type="hidden" name="clientIndex" value="~$clientIndex`">

 		<table border="0" align="center" width="80%" table-layout="auto" style="
    border-spacing: 10px;">
			~if $pogRBInterestsPool` 
				~foreach from=$pogRBInterestsPool item=valued key=k`
					<tr class="formhead" align="center">
					    <td height="21" align="CENTER"><a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$valued.PROFILEID`" target="_blank">~$valued.USERNAME`</a>
					    </td>
				    </tr>
					<tr class="formhead" align="left">
					    <td height="21" align="CENTER"><img src="~$valued.PHOTO_URL`">
					    </td>
					    <td height="21" align="CENTER">~$valued.ABOUT_ME`</td>
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
