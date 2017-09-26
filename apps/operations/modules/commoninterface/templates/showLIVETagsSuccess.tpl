~include_partial('global/header')`

<table width="100%" border="0" cellspacing="1" cellpadding="4" align="center">
	<tr class="fieldsnew">

		<td class="formhead" valign="middle" colspan="6" align="center" >TAG RESULTS</td>
	</tr>
	<tr>
		<td width="28%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="center">Tag Name
		</td>
		<td width="28%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="center">Tag Date
		</td>
		<td width="28%" class="label" bgcolor="#F9F9F9" style="font-size:14px" align="center">Tag Description
		</td>
	</tr>
	~foreach from=$tagArr key=tagName item=valueArr`
	<tr>
	<td class="label" valign="middle" colspan="1" align="center"> <a target="_blank" href="http://gitlabweb.infoedge.com/Jeevansathi/JsMain/tags/~$valueArr.tagName`">~$valueArr.tagName`</a></td>
	<td class="label" valign="middle" colspan="1" align="center"> ~$valueArr.dateTime`</td>
	<td class="label" valign="middle" colspan="1" align="center">
	~foreach from=$valueArr.description key=k1 item=jiraId`
	<a target="_blank" href="https://jsba99.atlassian.net/browse/~$jiraId`">~$jiraId`</a>, 
	~/foreach`
	</td>
	</tr>
	~/foreach`
</table>
