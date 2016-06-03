<br>
	<table width=760 cellspacing="1" cellpadding="7" ALIGN="CENTER">
		<tr class="formhead">
		~foreach from=$tabDetails item=valued key=k`
			<td border="1" align="center" height="11" id="extab~$valued.TABID`" class="jsc-bold"><a href="/operations.php/crmAllocation/getExclusiveMembers?EX_STATUS=~$k`&user=~$user`&cid=~$cid`" class="jsc-preventLinkDefault">~$valued.NAME`</a></td>
		~/foreach`
		</tr>
	</table>
<br>
<script>
	function setActiveTab(tab)
	{
		$("#extab"+tab).removeClass("jsc-bold");
		$("#extab"+tab).addClass("jsc-disabled");
	}

	$(document).ready(function() {
		var activeExTab = '~$activeExTab`';
		setActiveTab(activeExTab);
	});
</script>
