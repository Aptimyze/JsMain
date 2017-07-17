~include_partial('global/header')`
<body>
	<br>
	<div style="background-color:lightblue;text-align:center;font-size:12px;font-weight:bold;width:80%;margin-left:126px;">
	<div>Screen RB Interests</div>
	<br>
	~if $infoMsg`
		<div>~$infoMsg`</div>
	~else`
		<div>Client-<a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$clientId`" target="_blank">~$clientId`</a></div>
	~/if`
	~if $clientData && $clientData.HoroscopeMatch eq 'Y'`
		<div>Horoscope match is Necessary</div>
	~/if`
	</br>
	</div>
	<br>
	<form name="screenRBForm" action="~sfConfig::get('app_site_url')`/operations.php/jsexclusive/submitScreenRBInterests" method="post">
 		<table border="0" align="center" width="80%" table-layout="auto">
			~if $pogRBInterestsPool` 
				~foreach from=$pogRBInterestsPool item=valued key=pid`
					<tr class="formhead" align="center">
					    <td height="21" align="CENTER"><a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$pid`" target="_blank">~$valued.USERNAME`</a>
					    </td>
				    </tr>
					<tr class="formhead" align="left">
					    <td height="21" align="CENTER"><img src="~$valued.PHOTO_URL`">
					    </td>
				    </tr>
				    <tr class="formhead" align="center">
					    <td height="21" align="CENTER">
					    ~$valued.ABOUT_ME`
					    </td>
				    </tr>
				~/foreach`
		    ~/if`     	
 		</table>
 	</form>
</br>
~include_partial('global/footer')`
 </body>
