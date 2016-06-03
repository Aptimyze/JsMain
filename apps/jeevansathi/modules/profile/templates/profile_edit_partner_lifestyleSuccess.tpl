<SCRIPT type="text/javascript" language="Javascript" SRC="~$IMG_URL`/min/?f=/profile/js/~$registration_js`,/profile/js/~$gadget_as_js`,/js/~$advance_search_js`"></SCRIPT>
<script>
var docF=document.form1;
var page="edit1";
</script>
~$sf_data->getRaw('hiddenInput')`
<div class="clear"></div>
<div class="edit_scrollbox2_1 t12">
<div style="padding:5px;width:96%">
<div class="lf gray b t12" style="width:90px;text-align:right;margin-right:10px">Diet :</div>
<div class="lf t11" style="width:260px">
<div style="padding:0 5px 0 2px;"><div class="lf">Select Items</div><div class="rf"><a id="partner_diet_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scrollbox3 t12">
	<div style="display:none" id="partner_diet_div">
                <input type="hidden" name="partner_diet_str" id="partner_diet_str" value="~$partner_diet_str`">
                ~foreach from=$DIET item=val key=k`
                <input type="checkbox" value="~$k`" name="partner_diet_arr[]" id="partner_diet_~$k`"><label id="partner_diet_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
        <div style="overflow:hidden;" id="partner_diet_source_div">
                ~foreach from=$DIET item=val key=k`
                <input id="partner_diet_displaying_~$k`" class="chbx" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="partner_diet_displaying_arr[]"><label id="partner_diet_displaying_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
</div>
</div>
<div class="lf t11" style="width:260px; margin-left:20px;">
<div style="padding:0 5px 0 2px;"><div class="lf">Selected Items</div><div class="rf"><a id="partner_diet_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div></div>
<div class="lf scrollbox3 t12">
	<div style="overflow:hidden;" id="partner_diet_target_div">
        <div id="partner_diet_DM"><label>Doesn't Matter</label></div>
        </div>
</div></div></div>
<div class="sp12"></div>
<div style="padding:5px;width:96%">
<div class="lf gray b t12" style="width:90px;text-align:right;margin-right:10px">Smoke :</div>
<div class="lf t11" style="width:260px">
<div style="padding:0 5px 0 2px;"><div class="lf">Select Items</div><div class="rf"><a id="partner_smoke_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scrollbox3 t12">
        <div style="display:none" id="partner_smoke_div">
                <input type="hidden" name="partner_smoke_str" id="partner_smoke_str" value="~$partner_smoke_str`">
                ~foreach from=$SMOKE item=val key=k`
                <input type="checkbox" value="~$k`" name="partner_smoke_arr[]" id="partner_smoke_~$k`"><label id="partner_smoke_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
        <div style="overflow:hidden;" id="partner_smoke_source_div">
                ~foreach from=$SMOKE item=val key=k`
                <input id="partner_smoke_displaying_~$k`" class="chbx" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="partner_smoke_displaying_arr[]"><label id="partner_smoke_displaying_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
</div>
</div>
<div class="lf t11" style="width:260px; margin-left:20px;">
<div style="padding:0 5px 0 2px;"><div class="lf">Selected Items</div><div class="rf"><a id="partner_smoke_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div></div>
<div class="lf scrollbox3 t12">
        <div style="overflow:hidden;" id="partner_smoke_target_div">
        <div id="partner_smoke_DM"><label>Doesn't Matter</label></div>
        </div>
</div></div></div>
<div class="sp12"></div>
<div style="padding:5px;width:96%">
<div class="lf gray b t12" style="width:90px;text-align:right;margin-right:10px">Drink :</div>
<div class="lf t11" style="width:260px">
<div style="padding:0 5px 0 2px;"><div class="lf">Select Items</div><div class="rf"><a id="partner_drink_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scrollbox3 t12">
        <div style="display:none" id="partner_drink_div">
                <input type="hidden" name="partner_drink_str" id="partner_drink_str" value="~$partner_drink_str`">
                ~foreach from=$DRINK item=val key=k`
                <input type="checkbox" value="~$k`" name="partner_drink_arr[]" id="partner_drink_~$k`"><label id="partner_drink_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
        <div style="overflow:hidden;" id="partner_drink_source_div">
                ~foreach from=$DRINK item=val key=k`
                <input id="partner_drink_displaying_~$k`" class="chbx" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="partner_drink_displaying_arr[]"><label id="partner_drink_displaying_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
</div>
</div>
<div class="lf t11" style="width:260px; margin-left:20px;">
<div style="padding:0 5px 0 2px;"><div class="lf">Selected Items</div><div class="rf"><a id="partner_drink_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div></div>
<div class="lf scrollbox3 t12">
        <div style="overflow:hidden;" id="partner_drink_target_div">
        <div id="partner_drink_DM"><label>Doesn't Matter</label></div>
        </div>
</div></div></div>
<!--
<div class="row4">
<label style="width:96px;padding-right:10px">Drink :</label>
<input type="radio" class="chbx" name="drink" value="Y" id="drink" style="vertical-align:middle" ~if $DRINK eq "Y" or $DRINK eq "'Y'"`checked~/if`> Yes &nbsp;&nbsp;&nbsp;<input type="radio" class="chbx" name="drink" value="N" id="drink" style="vertical-align:middle" ~if $DRINK eq "N" or $DRINK eq "'N'"`checked~/if`> No &nbsp;&nbsp;&nbsp;<input type="radio" class="chbx" name="drink" value="O" id="drink" style="vertical-align:middle" ~if $DRINK eq "O" or $DRINK eq "'O'"`checked~/if`> Occasionally
</div>

<div class="row4">
<label style="width:96px;padding-right:10px">Smoke :</label>
<input type="radio" class="chbx" name="smoke" value="Y" id="smoke" style="vertical-align:middle" ~if $SMOKE eq "Y" or $SMOKE eq "'Y'"`checked~/if`> Yes &nbsp;&nbsp;&nbsp;<input type="radio" class="chbx" name="smoke" value="N" id="smoke" style="vertical-align:middle" ~if $SMOKE eq "N" or $SMOKE eq "'N'"`checked~/if`> No &nbsp;&nbsp;&nbsp;<input type="radio" class="chbx" name="smoke" value="O" id="smoke" style="vertical-align:middle" ~if $SMOKE eq "O" or $SMOKE eq "'O'"`checked~/if`> Occasionally
</div>
-->
<div class="sp12"></div>

<div style="padding:5px;width:96%">
<div class="lf gray b t12" style="width:90px;text-align:right;margin-right:10px">Complexion :</div>
<div class="lf t11" style="width:260px">
<div style="padding:0 5px 0 2px;"><div class="lf">Select Items</div><div class="rf"><a id="partner_complexion_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scrollbox3 t12">
	<div style="display:none" id="partner_complexion_div">
                <input type="hidden" name="partner_complexion_str" id="partner_complexion_str" value="~$partner_complexion_str`">
                ~foreach from=$COMPLEXION item=val key=k`
                <input type="checkbox" value="~$k`" name="partner_complexion_arr[]" id="partner_complexion_~$k`"><label id="partner_complexion_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
        <div style="overflow:hidden;" id="partner_complexion_source_div">
                ~foreach from=$COMPLEXION item=val key=k`
                <input id="partner_complexion_displaying_~$k`" class="chbx" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="partner_complexion_displaying_arr[]"><label id="partner_complexion_displaying_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
</div>
</div>
<div class="lf t11" style="width:260px; margin-left:20px;">
<div style="padding:0 5px 0 2px;"><div class="lf">Selected Items</div><div class="rf"><a id="partner_complexion_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div></div>
<div class="lf scrollbox3 t12">
	<div style="overflow:hidden;" id="partner_complexion_target_div">
        <div id="partner_complexion_DM"><label>Doesn't Matter</label></div>
        </div>
</div></div></div>
<div class="sp12"></div>

<div style="padding:5px;width:96%">
<div class="lf gray b t12" style="width:90px;text-align:right;margin-right:10px">Body Type :</div>
<div class="lf t11" style="width:260px">
<div style="padding:0 5px 0 2px;"><div class="lf">Select Items</div><div class="rf"><a id="partner_body_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scrollbox3 t12">
	<div style="display:none" id="partner_body_div">
                <input type="hidden" name="partner_body_str" id="partner_body_str" value="~$partner_body_str`">
                ~foreach from=$BODY_TYPE item=val key=k`
                <input type="checkbox" value="~$k`" name="partner_body_arr[]" id="partner_body_~$k`"><label id="partner_body_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
        <div style="overflow:hidden;" id="partner_body_source_div">
                ~foreach from=$BODY_TYPE item=val key=k`
                <input id="partner_body_displaying_~$k`" class="chbx" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="partner_body_displaying_arr[]"><label id="partner_body_displaying_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
</div>
</div>
<div class="lf t11" style="width:260px; margin-left:20px;">
<div style="padding:0 5px 0 2px;"><div class="lf">Selected Items</div><div class="rf"><a id="partner_body_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div></div>
<div class="lf scrollbox3 t12">
	<div style="overflow:hidden;" id="partner_body_target_div">
        <div id="partner_body_DM"><label>Doesn't Matter</label></div>
        </div>
</div></div></div>


<div class="sp12"></div>
<div style="padding:5px;width:96%">
<div class="lf gray b t12" id="challenged_label" style="width:90px;text-align:right;margin-right:10px">Challenged :</div>
<div class="lf t11" style="width:260px">
<div style="padding:0 5px 0 2px;"><div class="lf">Select Items</div><div class="rf"><a id="partner_handicapped_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scrollbox3 t12">
	<div style="display:none" id="partner_handicapped_div">
		<input type="hidden" name="partner_handicapped_str" id="partner_handicapped_str" value="~$partner_handicapped_str`">
		<input type="checkbox" value="DM" name="partner_handicapped_arr[]" id="partner_handicapped_DM">
		<label id="partner_handicapped_label_DM">Doesn't Matter</label><br>
			~foreach from=$handicap item=val key=k`
				<input type="checkbox" value="~$k`" name="partner_handicapped_arr[]" id="partner_handicapped_~$k`">
				<label id="partner_handicapped_label_~$k`">~$val`</label><br>
			~/foreach`
	</div>
	<div style="overflow:hidden;" id="partner_handicapped_source_div">
		 ~foreach from=$handicap item=val key=k name=handicaps`
			~if $smarty.foreach.handicaps.first`
			~else`
				<input type="checkbox" class="chbx " name="partner_handicapped_displaying_arr[]" id="partner_handicapped_displaying_~$k`" value="~$k`" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);"><label id="partner_handicapped_displaying_label_~$k`">~$val`</label><br>
			~/if`
		~/foreach`
	</div>
</div></div>
<div class="lf t11" style="width:260px; margin-left:20px;"><div style="padding:0 5px 0 2px;"><div class="lf">Selected Items</div><div class="rf"><a id="partner_handicapped_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div>
<div class="lf scrollbox3 t12">
	<div style="overflow:hidden;" id="partner_handicapped_target_div">
		<div id="partner_handicapped_link_1" onmouseover="highlight(this,'ON');" onmouseout="highlight(this,'OFF');" onclick="remove_checkboxes(this);"><img id="partner_handicapped_image_1" src="images/registration_new/remove_gray.gif">&nbsp;<label>None</label></div>
	</div>
</div>
</div></div></div>

<div class="sp12"></div>
<div id="nature_handicapped" style="display:none;">
<div style="padding:5px;width:96%" >
<div class="lf gray b t12" id="nhandicap_label" style="width:90px;text-align:right;margin-right:10px">Nature of Handicap :</div>
<div class="lf t11" style="width:260px"><div style="padding:0 5px 0 2px;"><div class="lf">Select Items</div><div class="rf"><a onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" id="partner_nhandicapped_select_all" class="blink">Select All</a></div></div>
<div class="lf scrollbox3 t12">
	<div style="display:none" id="partner_nhandicapped_div">
        	<input type="hidden" name="partner_nhandicapped_str" id="partner_nhandicapped_str" value="~$partner_nhandicapped_str`">
                ~foreach from=$nhandicap item=val key=k`
                	<input type="checkbox" value="~$k`" name="partner_nhandicapped_arr[]" id="partner_nhandicapped_~$k`">
                        <label id="partner_nhandicapped_label_~$k`">~$val`</label><br>
                ~/foreach`
	</div>
        <div style="overflow:hidden;" id="partner_nhandicapped_source_div">
        	~foreach from=$nhandicap item=val key=k`
                	<input type="checkbox" class="chbx " name="partner_nhandicapped_displaying_arr[]" id="partner_nhandicapped_displaying_~$k`" value="~$k`" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);"><label id="partner_nhandicapped_displaying_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
</div></div>
<div class="lf t11" style="width:260px; margin-left:20px;">
	<div style="padding:0 5px 0 2px;">
		<div class="lf">Selected Items</div>
		<div class="rf">
		<a id="partner_nhandicapped_clear_all" class="blink" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);">Clear All</a>
		</div>
		<div class="lf scrollbox3 t12">
			<div style="overflow:hidden;" id="partner_nhandicapped_target_div">
			Any
			</div>
	       </div>
        </div>
</div></div></div></div>

<script>
        var ppla = new Array("partner_diet","partner_body","partner_complexion","partner_handicapped","partner_nhandicapped","partner_smoke","partner_drink");
        fill_details(ppla);
</script>
