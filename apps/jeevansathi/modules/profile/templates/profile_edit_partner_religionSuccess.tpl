<SCRIPT type="text/javascript" language="Javascript" SRC="~$IMG_URL`/min/?f=/profile/js/~$registration_js`,/profile/js/~$gadget_as_js`,/js/~$advance_search_js`"></SCRIPT>
<script>
var docF = document.form1;
var page="edit1";
function fill_default_val()
{
        var caste, rel;
        ~foreach from=$checked_religion key=k item=v`
                if(rel)
			rel+=",'"+"~$v|decodevar`"+"'";
                else
                        rel="'~$v|decodevar`'";
        ~/foreach`
        document.getElementById("partner_religion_str").value=rel;
        ~foreach from=$checked_caste key=k item=v`
                if(caste)
                        caste+=",'"+~$v|decodevar`+"'";
                else
                        caste="'"+~$v|decodevar`+"'";
        ~/foreach`
        document.getElementById("partner_caste_selected").value=caste;
}

</script>
<div class="clear"></div>
~$sf_data->getRaw('hiddenInput')`
<div class="edit_scrollbox2_1 t12">
<div style="padding:5px;width:96%">
<div class="lf gray b t12" style="width:90px;text-align:right;margin-right:10px">Religion :</div>
<div class="lf t11" style="width:260px">
<div style="padding:0 5px 0 2px;"><div class="lf">Select Items</div><div class="rf"><a id="partner_religion_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scrollbox3 t12">
			<input type="hidden" name="partner_religion_str" id="partner_religion_str" value="">
<div style="display:none" id="partner_religion_div">
                                                <input type="checkbox" name="partner_religion_arr[]" id="partner_religion_DM" value="DM"><label id="partner_religion_label_DM">Any</label><br>
                                                        ~$sf_data->getRaw('hidden_religion')`
                                                </div>
                                                <div style="overflow:hidden;" id="partner_religion_source_div">
                                                ~$sf_data->getRaw('shown_religion')`
                                                </div>

</div>
</div>
<div class="lf t11" style="width:260px; margin-left:20px;">
<div style="padding:0 5px 0 2px;"><div class="lf">Selected Items</div><div class="rf"><a id="partner_religion_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div>
<div class="lf scrollbox3 t12">
	<div style="overflow:hidden;" id="partner_religion_target_div">
                                                       All
                                                       </div></div>
</div></div></div>
<div class="sp8"></div>

<div style="padding:5px;width:96%" id="caste">
<div class="lf gray b t12" style="width:90px;text-align:right;margin-right:10px" id="rel_caste">~if $Rel eq 'Muslim' or $Rel eq 'Christian'`Sect~else`Caste~/if` :</div>
<div class="lf t11" style="width:260px">
<div style="padding:0 5px 0 2px;"><div class="lf">Select Items</div><div class="rf"><a id="partner_caste_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scrollbox3 t12">
	<input type="hidden" id="partner_caste_selected" value="" style="display:none;">
        <div style="display:none" id="partner_caste_div">
                                  &nbsp;
        </div>
        <div style="overflow:hidden;" id="partner_caste_source_div">
        </div>
</div>
</div>
<div class="lf t11" style="width:260px; margin-left:20px;">
<div style="padding:0 5px 0 2px;"><div class="lf">Selected Items</div><div class="rf"><a id="partner_caste_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div></div>
<div class="lf scrollbox3 t12">
	<div style="overflow:hidden;" id="partner_caste_target_div">Any
                                                </div>
</div></div></div>

<div class="sp12"></div>

<div style="padding:5px;width:96%">
<div class="lf gray b t12" style="width:90px;text-align:right;margin-right:10px">Mother tongue :</div>
<div class="lf t11" style="width:260px">
<div style="padding:0 5px 0 2px;"><div class="lf">Select Items</div><div class="rf"><a id="partner_mtongue_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scrollbox3 t12">
 <div style="display:none" id="partner_mtongue_div">
		<input type="hidden" id="mton_sel" ~if $checked_mtongue` value="~$checked_mtongue`" ~else` value="" ~/if`>
                <input type="hidden" name="partner_mtongue_str" id="partner_mtongue_str" value="~$partner_mtongue_str`">
                ~foreach from=$MTONGUE_ARR item=val key=k`
                <input type="checkbox" value="~$k`" name="partner_mtongue_arr[]" id="partner_mtongue_~$k`"><label id="partner_mtongue_label_~$k`">~$val|decodevar`</label><br>
                ~/foreach`
        </div>
        <div style="overflow:hidden;" id="partner_mtongue_source_div">
                ~foreach from=$MTONGUE_ARR item=val key=k`
                <input id="partner_mtongue_displaying_~$k`" class="chbx" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="partner_mtongue_displaying_arr[]"><label id="partner_mtongue_displaying_label_~$k`">~$val|decodevar`</label><br>
                ~/foreach`
        </div>
</div>
</div>
<div class="lf t11" style="width:260px; margin-left:20px;">
<div style="padding:0 5px 0 2px;"><div class="lf">Selected Items</div><div class="rf"><a id="partner_mtongue_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div></div>
<div class="lf scrollbox3 t12">
	<div style="overflow:hidden;" id="partner_mtongue_target_div">
        <div id="partner_mtongue_DM"><label>Doesn't Matter</label></div>
        </div>
</div></div></div>

<div class="sp12"></div>

<div style="padding:5px;width:96%">
<div class="lf gray b t12" style="width:90px;text-align:right;margin-right:10px">Manglik :</div>
<div class="lf t11" style="width:260px">
<div style="padding:0 5px 0 2px;"><div class="lf">Select Items</div><div class="rf"><a id="partner_manglik_select_all" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Select All</a></div></div>
<div class="lf scrollbox3 t12">
 <div style="display:none" id="partner_manglik_div">
                <input type="hidden" id="mang_sel" ~if $checked_manglik` value="~$checked_manglik`" ~else` value="" ~/if`>
                <input type="hidden" name="partner_manglik_str" id="partner_manglik_str" value="~$partner_manglik_str`">
                ~foreach from=$MANGLIK_ARR item=val key=k`
                <input type="checkbox" value="~$k`" name="partner_manglik_arr[]" id="partner_manglik_~$k`"><label id="partner_manglik_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
        <div style="overflow:hidden;" id="partner_manglik_source_div">
                ~foreach from=$MANGLIK_ARR item=val key=k`
                <input id="partner_manglik_displaying_~$k`" class="chbx" type="checkbox" onclick="add_checkboxes(this); remove_doesnt_matter_conflict(this);" value="~$k`" name="partner_manglik_displaying_arr[]"><label id="partner_manglik_displaying_label_~$k`">~$val`</label><br>
                ~/foreach`
        </div>
</div>
</div>
<div class="lf t11" style="width:260px; margin-left:20px;">
<div style="padding:0 5px 0 2px;"><div class="lf">Selected Items</div><div class="rf"><a id="partner_manglik_clear_all" onclick="remove_checkboxes(this); remove_doesnt_matter_conflict(this);" class="blink">Clear All</a></div></div>
<div class="lf scrollbox3 t12">
        <div style="overflow:hidden;" id="partner_manglik_target_div">
        <div id="partner_manglik_DM"><label>Doesn't Matter</label></div>
        </div>
</div></div></div>

<div class="sp8"></div>
</div>
<script>
	fill_default_val();
        var ppre = new Array("partner_religion","partner_mtongue","partner_manglik");
        fill_details(ppre);
</script>
