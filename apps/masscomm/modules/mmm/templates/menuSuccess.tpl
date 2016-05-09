<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<br>
	<tr>
		<td  align="center">
			<h2>Menu</h2>
		</td>
	</tr>

	<tr class="bgred">
		<td height="1"></td>
		<SPACER height="1" type="block"></SPACER> 
	</tr>

	<tr>
</table>

<br>

<table width=500> 
	~foreach from=$leftMenu key=k1 item=v1`
	<tr class="bigblack">
		<td class="class4">
		~if $v1["name"] eq 'Logout'`
			~$k1+1`) <a href="~$v1["url"]`" target="_parent" style="color: Black">~$v1["name"]` </a>
		~else`
			~$k1+1`) <a href="~$v1["url"]`" target="right" style="color: Black">~$v1["name"]` </a>
		~/if`
		<br><br>
		</td>
	</tr>
	~/foreach`
</table>
