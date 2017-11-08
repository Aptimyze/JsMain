~include_partial('global/header',["showExclusiveServicingBack"=>'Y'])`
<body>
	<br>
	<div style="background-color:lightblue;text-align:center;font-size:12px;width:80%;margin-left:131px;">
		<div style="font-weight:bold;"><font size=4px>Add followups from matchmail</font></div>
		
	</div>
	<br>
	<form name="matchmailFollowup" action="~sfConfig::get('app_site_url')`/operations.php/jsexclusive/addFollowUpFromMatchMail?client=~$client`&mailType=~$mailType`" method="post">

 		<table border="0" align="center" width="80%" table-layout="auto" style="
    border-spacing: 10px;">
			~if $matchMailFollowUpData`
				~foreach from=$matchMailFollowUpData item=valued key=date`
                <tr style="background-color:lightgreen;"><td>Match mail sent Date</td><td>~$date`</td></tr>
                    ~foreach from=$valued key=index item=val`
                        <tr class="formhead" align="center">
                            <td height="21" align="CENTER" colspan="2"><a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$val.ACCEPTANCE_ID`" target="_blank">~$val.USERNAME`</a>~if $val.NAME_OF_USER` (~$val.NAME_OF_USER`) ~/if`
                            </td>
                        </tr>
                        <tr class="formhead" align="left">
                            <td height="21" align="CENTER"><img src="~$val.PHOTO_URL`">
                            </td>
                            <td height="21" align="CENTER">
                                <input type="radio" name="followupForm[~$val.ACCEPTANCE_ID`]" value="Y">
                                    Yes
                                <input type="radio" name="followupForm[~$val.ACCEPTANCE_ID`]" value="N">
                                    No
                                <input type="radio" name="followupForm[~$val.ACCEPTANCE_ID`]" value="U" checked>
                                    Undecided
                            </td>
                        </tr>

                        <tr align="center">
                            <td height="10" align="CENTER"></td>
                        </tr>
                    ~/foreach`
                    <br>
                ~/foreach`
                <tr align="center">
                    <td class="label" colspan="2" height="20" style="background-color:Moccasin">
                        <input type="submit" name="submit" value="SUBMIT">
                    </td>
                </tr>
            ~else`
                <tr style="background-color:lightgreen;" align="center"><td>No data Available</td><td>~$date`</td></tr>
		    ~/if`     	
 		</table>
 	</form>
    
    
    <div style="background-color:lightblue;text-align:center;font-size:12px;width:80%;margin-left:131px;">
        <div style="font-weight:bold;"><font size=4px><a href="/operations.php/jsexclusive/addFollowUpFromMatchMail?client=~$client`&declined=1">Declined list</a></font></div>
		
	</div>
    ~if $declinedArr`
        <table border="0" align="center" width="80%" table-layout="auto" style=" border-spacing: 10px;">
            ~foreach from=$declinedArr item=val key=profileid`
                <tr class="formhead" align="center">
                    <td height="21" align="CENTER" colspan="2"><a href="/operations.php/commoninterface/ShowProfileStats?profileid=~$profileid`" target="_blank">~$val.USERNAME`</a>
                    </td>
                </tr>
            ~/foreach`
        </table>
    ~/if`     	
    
</br>
~include_partial('global/footer')`
 </body>
