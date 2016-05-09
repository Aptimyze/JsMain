<script>
	var pattern1 = /\#n\#/g;
~foreach from=$drafts item=message key=id`

 temp="~$drafts[$id][1]|decodevar`";
	MES['~$drafts[$id][2]`']=temp.replace(pattern1,"\n");
~/foreach`
</script>
<select name="draft_name~$tab`" onchange="onSelection~$tab`(this.value)" ~if MobileCommon::isMobile() neq 1` ~if $fromSaveDraft eq 1`class="savedraft"~else` style="width:145px; height:35px;"~if !$tab` class="messagedropdown"~/if`~/if` ~/if` id="draft_name~$tab`">
	~foreach from=$drafts item=message key=id`
		<option value="~$drafts[$id][2]`" ~if $drafts[$id][3]`selected~/if`>~$drafts[$id][0]`</option>
	~/foreach`
</select>
