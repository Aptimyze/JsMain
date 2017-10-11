<SCRIPT type="text/javascript" language="Javascript" SRC="~$IMG_URL`/min/?f=/profile/js/~$registration_js`,/profile/js/~$gadget_as_js`,/js/~$advance_search_js`"></SCRIPT>
<script>
	var docF=document.form1;
	var page="edit1";
	function validate_income()
	{
	    var rsHIncome=document.getElementById('rsHIncome').value;
		var rsLIncome=document.getElementById('rsLIncome').value;
		var doLIncome=document.getElementById('doLIncome').value;
		var doHIncome=document.getElementById('doHIncome').value;
		if(rsHIncome==19)
		   rsHIncome=30;
		if(doHIncome==19)
			doHIncome=30;
		if( rsLIncome && rsHIncome!='')
		{
			if(parseInt(rsLIncome) > parseInt(rsHIncome) || (parseInt(rsLIncome) == parseInt(rsHIncome) && parseInt(rsHIncome)!=0  ))		  		  {
				document.getElementById('my_income').style.display='inline';
				document.getElementById('my_income_error').style.display='none';
				document.getElementById('my_income_error_dol').style.display='none';
		                document.getElementById('rsLIncome').focus();
		                return false;
			}
		}
		
		if((doLIncome!='') && (doHIncome!=''))
		{
			 if(parseInt(doLIncome) > parseInt(doHIncome) || (parseInt(doLIncome) == parseInt(doHIncome) && parseInt(doHIncome)!=0))
			 {
				document.getElementById('my_income').style.display='inline';
				document.getElementById('my_income_error').style.display='none';
				document.getElementById('my_income_error_dol').style.display='none';
		                document.getElementById('doLIncome').focus();
		                return false;
			 }
		}

		if((rsLIncome!='') && (rsHIncome==''))
		{
			document.getElementById('my_income_error').style.display='inline';
			document.getElementById('my_income_error_dol').style.display='none';
			document.getElementById('my_income').style.display='none';
			document.getElementById('rsHIncome').focus();
			return false;
		}

		if((rsLIncome=='') && (rsHIncome!=''))
		{
			document.getElementById('my_income_error').style.display='inline';
			document.getElementById('my_income_error_dol').style.display='none';
			document.getElementById('my_income').style.display='none';
			document.getElementById('rsLIncome').focus();
			return false;
		}

		if((doLIncome!='') && (doHIncome==''))
		{
			document.getElementById('my_income_error_dol').style.display='inline';
			document.getElementById('my_income_error').style.display='none';
			document.getElementById('my_income').style.display='none';
			document.getElementById('doHIncome').focus();
			return false;
		}

		if(doLIncome=='' && doHIncome!='')
		{
			document.getElementById('my_income_error_dol').style.display='inline';
			document.getElementById('my_income_error').style.display='none';
			document.getElementById('my_income').style.display='none';
			document.getElementById('doLIncome').focus();
			return false;
		}

		document.getElementById('my_income_error_dol').style.display='none';
		document.getElementById('my_income_error').style.display='none';
		document.getElementById('my_income').style.display='none';
		return true;
	}
</script>
~$sf_data->getRaw('hiddenInput')`
<div class="edit_scrollbox2_1 t12">
<div style="padding:5px;width:96%">
<div class="lf gray b t12" style="width:90px;text-align:right;margin-right:10px">Education :</div>
<div class="lf t11" style="width:260px">
<div style="padding:0 5px 0 2px;"><div class="lf">Select Items</div><div class="rf"><a id="partner_education_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scrollbox3 t12">
	<div style="display:none" id="partner_education_div">
                <input type="hidden" name="partner_education_str" id="partner_education_str" value="~$partner_education_str`">
               ~$finalHiddenStr|decodevar`
        </div>
        <div style="overflow:hidden;" id="partner_education_source_div">
               ~$finalShownStr|decodevar`
        </div>
</div>
</div>
<div class="lf t11" style="width:260px; margin-left:20px;">
<div style="padding:0 5px 0 2px;"><div class="lf">Selected Items</div><div class="rf"><a id="partner_education_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div></div>
<div class="lf scrollbox3 t12">
	<div style="overflow:hidden;" id="partner_education_target_div">
        <div id="partner_education_DM"><label>Doesn't Matter</label></div>
        </div>
</div></div></div>
<div class="sp12"></div>
<div style="padding:5px;width:96%">
<div class="lf gray b t12" style="width:90px;text-align:right;margin-right:10px">Occupation :</div>
<div class="lf t11" style="width:260px">
<div style="padding:0 5px 0 2px;"><div class="lf">Select Items</div><div class="rf"><a id="partner_occupation_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scrollbox3 t12">
	<div style="display:none" id="partner_occupation_div">
                <input type="hidden" name="partner_occupation_str" id="partner_occupation_str" value="~$partner_occupation_str`">
                ~foreach from=$OCC_ARR item=val key=k`
                <input type="checkbox" value="~$k`" name="partner_occupation_arr[]" id="partner_occupation_~$k`"><label id="partner_occupation_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
        <div style="overflow:hidden;" id="partner_occupation_source_div">
                ~foreach from=$OCC_ARR item=val key=k`
                <input id="partner_occupation_displaying_~$k`" class="chbx" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="partner_occupation_displaying_arr[]"><label id="partner_occupation_displaying_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
</div>
</div>
<div class="lf t11" style="width:260px; margin-left:20px;">
<div style="padding:0 5px 0 2px;"><div class="lf">Selected Items</div><div class="rf"><a id="partner_occupation_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div></div>
<div class="lf scrollbox3 t12">
	<div style="overflow:hidden;" id="partner_occupation_target_div">
        <div id="partner_occupation_DM"><label>Doesn't Matter</label></div>
        </div>
</div></div></div>

<div class="sp12"></div>
		<li style="padding:5px;width:80%">
				<label id="income_label" class="gray b">Annual Income :</label>
				<span style="width: 125px;font-weight:normal;">Select income range</span>
				<div class="clr"></div>
				<div style="height: 80px;" class="scrollbox_adv">
					<div style="margin-bottom: 3px;padding: 3px 3px 3px 6px;">
						<span style="display: none; color: rgb(255, 0, 0);" id="invalidUser">
							<img src="images/iconError_16x16.gif"><b>Please make a selection before continuing.</b>
						</span>
					</div>
					<div style="padding: 0pt 0pt 0pt 100px;font-weight:normal;">
						<span style="color:#4B4B4B;font-weight:bold;">Indian Rupee</span>&nbsp;
						<select style="width: 150px;" name="rsLIncome" id="rsLIncome">
							<option value="">Please Select</option>
                                                         ~foreach from=$MIN_LABEL_RS key=k1 item=v1`
									<option value="~$k1`" ~if $partner_lincome neq '' && $k1 eq $partner_lincome`selected~/if`>~$v1`</option>
                                                         ~/foreach`
						</select> &nbsp;&nbsp;to&nbsp;&nbsp;

						<select style="width: 150px;" name="rsHIncome" id="rsHIncome">
							<option value="">Please Select</option>
							~foreach from=$MAX_LABEL_RS key=k1 item=v1`
                                                                    <option value="~$k1`"  ~if $partner_hincome neq '' && $k1 eq $partner_hincome`selected~/if`>~$v1`</option>
                                                        ~/foreach`
						</select>
					</div>
					<div style="padding: 20px 0pt 0pt 85px;font-weight:normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<span style="color:#4B4B4B;font-weight:bold;">US Dollar</span>&nbsp;
						<select style="width: 150px;" name="doLIncome" id="doLIncome">
							<option value="">Please Select</option>
                                                         ~foreach from=$MIN_LABEL_DO key=k1 item=v1`
                                                              <option value="~$k1`"  ~if $partner_lincome_dol neq '' && $k1 eq $partner_lincome_dol`selected~/if`>~$v1`</option>
                                                         ~/foreach`
						</select> &nbsp;&nbsp;to&nbsp;&nbsp;
						<select style="width: 150px;" name="doHIncome" id="doHIncome">
							 <option value="">Please Select</option>
                                                         ~foreach from=$MAX_LABEL_DO key=k1 item=v1`
                                                              <option value="~$k1`"  ~if $partner_hincome_dol neq '' && $k1 eq $partner_hincome_dol`selected~/if`>~$v1`</option>
                                                         ~/foreach`
						</select>
					</div>
				</div>
				<div class="red_new" id="my_income" style="display:none;padding-left:170px;">
					<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>
					Maximum income should be greater than minimum income.
				</div>
				<div class="clr"></div>
				<div class="red_new" id="my_income_error" style="display:none;padding-left:170px;">
					<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>
					Please enter both the values to define income range.
				</div>
				<div class="red_new" id="my_income_error_dol" style="display:none;padding-left:170px;">
					<img style="vertical-align: bottom;" src="~$IMG_URL`/profile/images/registration_new/alert.gif"/>
					Please enter both the values to define dollar range.
				</div>
			</li>
</div>
<script>
        var ppeo = new Array("partner_income","partner_occupation","partner_education");
        fill_details(ppeo);
</script>
