~if $NameValueArr|@count >0`
<h6>
	<span class="title">~$LabelHeading`</span>
	<a href="javascript:void(0)" onclick="plusMinuschange('~$id`')" id="~$id`A" class="icon-minus">&nbsp;</a>
</h6>
<div class="js-content" id="~$id`Div" style="display:block">
	<table cellpadding="5" cellspacing="0" style="width:100%;">
		<tbody>
			~foreach from=$NameValueArr item=Value key=Label`		
				<tr>
					<td class="vatop prof_width50">~$Label`</td>
					<td class="vatop">:&nbsp;</td>
					<td class="vatop padright0 prof_width48" >
						<table cellspacing="0" cellpadding="0">
							<tr>
								<td class="vatop">~$Value|decodevar`</td>
							</tr>
						</table>	
					</td>
				</tr>		
			~/foreach`
		</tbody>
	</table>
</div>
~/if`
