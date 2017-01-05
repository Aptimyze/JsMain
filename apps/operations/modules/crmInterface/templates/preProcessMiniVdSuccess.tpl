~include_partial('global/header')`

<form action="~sfConfig::get('app_site_url')`/operations.php/crmInterface/preProcessMiniVd?cid=~$cid`" method="POST" name="insertForm">
	<input type=hidden name=cid value="~$cid`">
	<table width=900 align=center >
		<tr class="formhead" align=center><td colspan=3>	
			<a href="~sfConfig::get('app_site_url')`/operations.php/crmInterface/manageVdOffer?cid=~$cid`">Back</a>
		</tr>
		<tr class="formhead" align=center><td colspan=5>Customized Variable Discount Offer Selection Screen</tr>

		~if $errorMsg0`
			<tr align=center><td class=fieldsnew colspan=100%><font size=2><b>~$errorMsg0`</b></font></td></tr>			
		~else`
			~if !$successMsg and $errorMsg`
				<tr align=center><td class=fieldsnew colspan=100%><font size=2 color="red"><b>~$errorMsg`</b></font></td></tr>			
			~/if`
			~if $successMsg`
				<tr align=center><td class=fieldsnew colspan=100%><font size=2><b>~$successMsg`</b></font></td></tr>			
			~else`
				<tr class="formhead" align=center>
					<td class=fieldsnew width=10% ><input type='hidden' name=checkArr[vdOfferDate] value='1' /></td>
					<td class=fieldsnew width=60%>Select Start Date of Offer *</td>	
					<td class=fieldsnew width=30% colspan=100%>
						<select name=dataArr[vdOfferDate1]>
	                                        	~foreach from=$vdDateDropdown key=k item=v`
        	                                        	<option value="~$k`" ~if $dataArr.vdOfferDate1 eq $k` selected ~/if` >~$v`</option>
        	                                	~/foreach`
						<rselect>

					</td>
				</tr>
                                <tr class="formhead" align=center>
					<td class=fieldsnew width=10% ></td>
                                        <td class=fieldsnew width=60%>
						~if $errorArr.vdOfferDate`<font color='red'>~/if`
						Select End Date of Offer *
					</td>
                                        <td class=fieldsnew width=30% colspan=100%>
                                                <select name=dataArr[vdOfferDate2]>
                                                        ~foreach from=$vdDateDropdown key=k item=v`
                                                                <option value="~$k`" ~if $dataArr.vdOfferDate2 eq $k` selected ~/if`>~$v`</option>
                                                        ~/foreach`
                                                </select>
                                        </td>
                                </tr>
				<br>
                                <tr class="formhead" align=center>
                                        <td class=fieldsnew width=10% ><b>Criteria Selection</b></td>
                                        <td class=fieldsnew width=60% ><b>Criteria Entry</b></td>
                                        <td class=fieldsnew width=30% colspan=100%>
                                        </td>
                                </tr>
				<tr class="formhead" align=center />
                                <tr class="formhead" align=center>
					<td class=fieldsnew width=10% ><input type='checkbox' name="checkArr[loginDate]" ~if $checkArr.loginDate`Checked~/if`/></td>
                                        <td class='fieldsnew' width=60%>~if $errorArr.loginDate`<font color='red'>~/if`Last Login Date</td>
                                        <td class=fieldsnew width=30% colspan=100%>
		                                <input type=text name=dataArr[loginDate] value="~$dataArr.loginDate`" size=20 maxlength=99 tabindex="1" id="field_1_1"/>
		                                <script type="text/javascript">
						<!--
                		                document.write('<a title="Calendar" href="javascript:openCalendar(\'lang=en-iso-8859-1&amp;server=1\', \'insertForm\', \'field_1_1\', \'date\',\'~sfConfig::get('app_site_url')`\')"><img class="calendar" src="~sfConfig::get('app_site_url')`/crm/img/b_calendar.png" alt="Calendar"/ border=0></a>');
						//-->
                                		</script>

                                        </td>
                                </tr>
                                <tr class="formhead" align=center>
                                        <td class=fieldsnew width=10% ><input type='checkbox' name=checkArr[activated] ~if $checkArr.activated`Checked~/if`/></td>
                                        <td class=fieldsnew width=60% >Activated & Mobile Verified Profiles Only</td>
                                        <td class=fieldsnew width=30% colspan=100%><input type='hidden' name=dataArr[activated] value='1' /></td>
                                        </td>
                                </tr>
                                <tr class="formhead" align=center>
                                        <td class=fieldsnew width=10% ><input type='checkbox' name=checkArr[age] ~if $checkArr.age`Checked~/if`/></td>
                                        <td class=fieldsnew width=60% >Exclude Male < 23 years of age and Female < 20 years of age</td>
                                        <td class=fieldsnew width=30% colspan=100%><input type='hidden' name=dataArr[age] value='1' /></td>
                                </tr>
                                <tr class="formhead" align=center>
                                        <td class=fieldsnew width=10% ><input type='checkbox' name=checkArr[neverPaid] ~if $checkArr.neverPaid`Checked~/if`/></td>
                                        <td class=fieldsnew width=60% >Never Paid</td>
                                        <td class=fieldsnew width=30% colspan=100%><input type='hidden' name=dataArr[neverPaid] value='1' /></td>
                                </tr>
                                <tr class="formhead" align=center>
                                        <td class=fieldsnew width=10% ><input type='checkbox' name=checkArr[everPaid] ~if $checkArr.everPaid`Checked~/if`/></td>
                                        <td class=fieldsnew width=60% >Ever Paid</td>
                                        <td class=fieldsnew width=30% colspan=100%><input type='hidden' name=dataArr[everPaid] value='1' /></td>
                                </tr>
                                <tr class="formhead" align=center>
                                        <td class=fieldsnew width=10% ><input type='checkbox' name=checkArr[regDate] ~if $checkArr.regDate`Checked~/if`/></td>
                                        <td class=fieldsnew width=60% >
						~if $errorArr.regDate`<font color='red'>~/if`	
						Registration Date
					</td>
                                        <td class=fieldsnew width=30% colspan=100%>
                                                <input type=text name=dataArr[regDate1] value="~$dataArr.regDate1`" size=10 maxlength=99 tabindex="1" id="field_2_1"/>
                                                <script type="text/javascript">
                                                <!--
                                                document.write('<a title="Calendar" href="javascript:openCalendar(\'lang=en-iso-8859-1&amp;server=1\', \'insertForm\', \'field_2_1\', \'date\',\'~sfConfig::get('app_site_url')`\')"><img class="calendar" src="~sfConfig::get('app_site_url')`/crm/img/b_calendar.png" alt="Calendar"/ border=0></a>');
                                                //-->
                                                </script>
						To 	
                                                <input type=text name=dataArr[regDate2] value="~$dataArr.regDate2`" size=10 maxlength=99 tabindex="1" id="field_2_2"/>
                                                <script type="text/javascript">
                                                <!--
                                                document.write('<a title="Calendar" href="javascript:openCalendar(\'lang=en-iso-8859-1&amp;server=1\', \'insertForm\', \'field_2_2\', \'date\',\'~sfConfig::get('app_site_url')`\')"><img class="calendar" src="~sfConfig::get('app_site_url')`/crm/img/b_calendar.png" alt="Calendar"/ border=0></a>');
                                                //-->
                                                </script>
						<br>
						<!--<input type='checkbox' name=checkArr[regDateBegin]> Checkbox for beginning of time-->
                                        </td> 

                                </tr>
                                <tr class="formhead" align=center>
                                        <td class=fieldsnew width=10% ><input type="checkbox" name=checkArr[expiryDate] ~if $checkArr.expiryDate`Checked~/if`/></td>
                                        <td class=fieldsnew width=60% >
						~if $errorArr.expiryDate`<font color='red'>~/if`
						Last Subscription Expiry Date
					</td>
                                        <td class=fieldsnew width=30% colspan=100%>
                                                <input type=text name=dataArr[expiryDate1] value="~$dataArr.expiryDate1`" size=20 maxlength=99 tabindex="1" id="field_3_1"/>
                                                <script type="text/javascript">
                                                <!--
                                                document.write('<a title="Calendar" href="javascript:openCalendar(\'lang=en-iso-8859-1&amp;server=1\', \'insertForm\', \'field_3_1\', \'date\',\'~sfConfig::get('app_site_url')`\')"><img class="calendar" src="~sfConfig::get('app_site_url')`/crm/img/b_calendar.png" alt="Calendar"/ border=0></a>');
                                                //-->
                                                </script>
                                                To
                                                <input type=text name=dataArr[expiryDate2] value="~$dataArr.expiryDate2`" size=20 maxlength=99 tabindex="1" id="field_3_2"/>
                                                <script type="text/javascript">
                                                <!--
                                                document.write('<a title="Calendar" href="javascript:openCalendar(\'lang=en-iso-8859-1&amp;server=1\', \'insertForm\', \'field_3_2\', \'date\',\'~sfConfig::get('app_site_url')`\')"><img class="calendar" src="~sfConfig::get('app_site_url')`/crm/img/b_calendar.png" alt="Calendar"/ border=0></a>');
                                                //-->
                                                </script>
						<br>
						<!--<input type='checkbox' name=checkArr[expDateBegin]> Checkbox for beginning of time-->
                                        </td>
                                </tr>
                                <tr class="formhead" align=center>
                                        <td class=fieldsnew width=10% ><input type="checkbox" name="checkArr[mtongue]" ~if $checkArr.mtongue`Checked~/if`/></td>
                                        <td class=fieldsnew width=60% >
						~if $errorArr.mtongue`<font color='red'>~/if`
						Mother Tongue
					</td>
                                        <td class=fieldsnew width=30% colspan=100%>
                                                <select name="dataArr[mtongue][]" value="" multiple="multiple">
                                                        ~foreach from=$mtongueDropdown key=k item=v`
								~foreach from=$dataArr.mtongue key=kk item=vv`
									~if $vv eq $k`	
										~assign var='alreadySel' value=$vv`	
									~/if`
								~/foreach`
								<option value="~$k`" ~if $alreadySel eq $k`selected ~/if` >~$v`</option>
                                                        ~/foreach`
                                                </select>
                                        </td>
                                </tr>
                                <tr class="formhead" align=center>
                                        <td class=fieldsnew width=10% ><input type="checkbox" name="checkArr[score]" ~if $checkArr.score`Checked~/if`/></td>
                                        <td class=fieldsnew width=60% >
						~if $errorArr.score`<font color='red'>~/if`
						Analytics Score
					</td>
                                        <td class=fieldsnew width=30% colspan=100%>
					<input type='text' name='dataArr[score1]' value="~$dataArr.score1`" size=5%> 
						To 
					<input type='text' name=dataArr[score2] value="~$dataArr.score2`" size=5%>
                                        </td>
                                </tr>
                                <tr class="formhead" align=center>
                                        <td class=fieldsnew width=10% ><input type='hidden' name=checkArr[discount] value='1' /></td>
                                        <td class=fieldsnew width=60% >
						~if $errorArr.discount`<font color='red'>~/if`
						Discount Value (in percentage) *
					</td>
                                        <td class=fieldsnew width=30% colspan=100%>
						<input type='text' name=dataArr[discount] value="~$dataArr.discount`">
                                        </td>
                                </tr>
                                <tr class="formhead" align=center>
                                        <td class=fieldsnew width=10% ><input type='hidden' name=checkArr[cluster] value='1' /></td>
                                        <td class=fieldsnew width=60% >
						~if $errorArr.cluster`<font color='red'>~/if`
						Cluster Name *
					</td>
                                        <td class=fieldsnew width=30% colspan=100%>
						<input type='text' name=dataArr[cluster] value="~$dataArr.cluster`">
                                        </td>
                                </tr>
				<tr align=center>
					<td class=fieldsnew colspan=100%>
						<input type=submit name=submit value="Add Offer">
					</td>
				</tr>
			~/if`
		~/if`
	</table>
</form>
~include_partial('global/footer')`
