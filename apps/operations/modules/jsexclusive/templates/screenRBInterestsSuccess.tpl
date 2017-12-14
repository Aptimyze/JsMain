~include_partial('global/header',["showExclusiveServicingBack"=>'Y'])`
<script language="javascript">
function MM_openBrWindow(theURL,winName,features){
        window.open(theURL,winName,features);
}

</script>
<body>
	<br>
	<div style="background-color:lightblue;text-align:center;font-size:12px;width:80%;margin-left:131px;">
		<div style="font-weight:bold;"><font size=4px>Screen RB Interests</font></div>
		<div style="margin-left:704px;"><font size=4px>Clients left-~$unscreenedClientsCount`</font></div>
		~if $infoMsg`
			<div style="padding:4px;font-size:medium;">~$infoMsg`</div>
		~/if`
	</div>
	<div style="font-size:12px;width:80%;margin-left:131px;">
		~if $clientData`
		<div style="font-size: 15px"><a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$clientId`" target="_blank">~$clientData.clientUsername`</a></div>
                <div style="font-size: 15px"><a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$clientId`" target="_blank">~$clientData.clientName`</a></div>
		~if $clientData.HoroscopeMatch eq 'Y'`
			<div style="font-size: 20px">Horoscope match is Necessary</div>
		~/if`
	~/if`
	</div>
        <input type="hidden" id="client" name ="client" value="~$clientId`">
        ~if $clientData`
            <table border="0" align="center" width="80%" table-layout="auto" style="border-spacing: 10px;">
            <tr class="formhead" align="LEFT">
                <td height="21" width="10%"align="CENTER">
                    <img src="~$clientData.clientImage`">
                <td align="CENTER" width="85%">
                    <textarea readonly maxlength="2000" rows="5" style="width:100%" id="notes" name="notes">~$clientData.clientNotes`</textarea>
                </td>
                <td height="10" align="CENTER" width="5%"> 
                    <input type="button" value="Edit Note" onclick="enableTextEdit()">
                    <br>
                    <br>
                    <input type="button" value="Save Note" onclick="sendClientNotes()">
                </td>
	   </tr>
        </table>
        ~/if`
        <br><br>
        <br><br> <hr size="3">
        <br><br>
        <br><br>
	<form name="screenRBForm" action="~sfConfig::get('app_site_url')`/operations.php/jsexclusive/submitScreenRBInterests" method="post">
		<input type="hidden" name="clientIndex" value="~$clientIndex`">
		<input type="hidden" name="clientId" value="~$clientId`">
		<input type="hidden" name="clientUsername" value="~$clientData.clientUsername`">
 		<table border="0" align="center" width="80%" table-layout="auto" style="
    border-spacing: 10px;">
			~if $pogRBInterestsPool` 
				~foreach from=$pogRBInterestsPool item=valued key=k`
					<tr class="formhead" align="center">
					    <td height="21" align="CENTER"><a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$valued.PROFILEID`" target="_blank">~$valued.USERNAME`</a>
					    </td>
					    ~if $valued.GUNA_SCORE`
					    	<td height="10" align="CENTER"><div style="font-size:18px;color:~if $valued.GUNA_SCORE lt 18`#d9475c~else`#000000~/if`;">~$valued.GUNA_SCORE`/36</div></td>
					    ~else`
					    	<td height="10" align="CENTER"></td>
						~/if`
                                        </tr>
					<tr class="formhead" align="left">
					    <td height="21" align="CENTER"><img src="~$valued.PHOTO_URL`"><br>
					      ~if $valued.PHOTO_URL neq '' && $valued.HAVEPHOTO eq 'Y'`	
						      <a href="" onclick="MM_openBrWindow('/P/photocheck.php?profilechecksum=~$valued.PROFILE_CHECKSUM`&seq=1','','width=400,height=500,scrollbars=yes'); return false;">Click here for Album</a>
						~/if`
					    </td>
					    <td>
					    	<table style="margin:auto;width:100%; text-align:center">
         			       		<tr>	
                    				<td  colspan="4" height="21" align="CENTER" style="font-weight: normal;">~$valued.ABOUT_ME`</td>                    
    			            	</tr>
    			            	<tr><td height="10"></td></tr>
    			            	<tr style="font-weight:normal;width:100%;">
    			            		<td style="width:20%">~$valued.AGE`</td>
    			            		<td style="width:20%">~$valued.OCCUPATION`</td>
    			            		<td style="width:40%">~$valued.PG_DEGREE` : ~$valued.PG_COLLEGE`</td>
    			            		<td style="width:20%">~$valued.GOTHRA`</td>
    			            	</tr>
    			            	<tr style="font-weight:normal;">
				  					<td style="width:20%">~$valued.CITY_RES`,~$valued.STATE_RES`,~$valued.COUNTRY_RES`</td>
						    		<td style="width:20%">~$valued.INCOME`</td>
						   		 	<td style="width:40%">~$valued.UG_DEGREE` : ~$valued.UG_COLLEGE`</td>
				   				 	<td style="width:20%">~$valued.FAMILY_STATUS`</td>
				  				</tr >
    			            	<tr style="font-weight:normal;">
    			            		<td style="width:20%">~$valued.CITY_BIRTH`</td>
    			            		<td style="width:20%"></td>
    			            		<td style="width:40%"></td>
				    				<td style="width:20%">~$valued.RES_STATUS`</td>	
    			            	</tr>
        			    	</table> 
					    </td>
					    <td height="21" align="CENTER"><input type="checkbox" name="DISCARD[]" value="~$valued.PROFILEID`">DISCARD<input type="hidden" name="ACCEPT[]" value="~$valued.PROFILEID`"></td></td>
                                        </tr>
				    
					<tr align="center">
				    	<td height="10" align="CENTER"></td>
				    </tr>
				~/foreach`
				<br>
				<tr align="center">
					<td align="left" class="label" colspan="2" height="20" style="background-color:Moccasin">
						<input type="submit" name="submit" value="SKIP">
						<input type="submit" style="margin-left: 42%;" name="submit" value="SUBMIT">
					</td>
					
				</tr>
		    ~else if $showNextButton eq 'Y'`
		    	<br>
				<tr align="center">
					<td class="label" colspan="2" height="20" style="background-color:Moccasin">
						<input type="submit" name="submit" value="NEXT">
					</td>
				</tr>
		    ~/if`     	
 		</table>
 	</form>
</br>
~include_partial('global/footer')`
 </body>
