~if $partnerTab eq 0`
	<!-- 
	I have already write a html as per the Accordion js of Jquery; So you can easily implementing 
	-->

	<!-- Basic Info -->
	<h6>
		<span class="title">Basic Info</span>
		<a href="javascript:void(0)" onclick="plusMinuschange('~$id`')" id="~$id`A" class="icon-minus">&nbsp;</a>
	</h6>
	
	<div class="js-content" id="~$id`Div">
	<!-- About me-->
	~if $MobileAbtArr.YOURINFO neq ''`
		<span class=""><b>About me</b>
			<p id="yourinfo" name="yourinfo" class="textaligninfo">
				~$MobileAbtArr.YOURINFO|decodevar`
			</p>
		<div class="clr" style="height:10px"></div>
		</span>
	~/if`
	
	
	<!-- About Family-->
	~if $MobileAbtArr.FAMILYINFO neq ''`
		<span ><b>About Family</b>
			<p id="familyinfo" name="familyinfo" class="textaligninfo">
				~$MobileAbtArr.FAMILYINFO|decodevar`
			</p>
		</span>
		<div class="clr" style="height:10px"></div>
	~/if`
	

	<!-- About Education-->
	~if $MobileAbtArr.EDUINFO neq ''`
		<span ><b>About Education</b>
			<p id="eduinfo" name="eduinfo" class="textaligninfo">
				~$MobileAbtArr.EDUINFO|decodevar`
			</p>
		</span>
		<div class="clr" style="height:10px"></div>
	~/if`
	
	
	<!-- About Work-->
	~if $MobileAbtArr.OCCINFO neq ''`
		<span ><b>About Work</b>
			<p id="occinfo" name="occinfo" class="textaligninfo">
				~$MobileAbtArr.OCCINFO|decodevar`
			</p>
		</span>
		<div class="clr" style="height:20px"></div>
	~/if`
	
	
		<table cellpadding="5" cellspacing="0" style="width:100%;">
			<tbody>
				<tr>
					<td class="prof_width50">Height</td>
					<td>: ~$HEIGHT|decodevar`</td>
				</tr>
				<tr>
					<td class="prof_width50">Marital status</td>
					<td>: ~$MSTATUS`</td>
				</tr>
			</tbody>
		</table>
	</div>

~else`
	<h6>
		<span class="title">Basic info</span>
		<a href="javascript:void(0)" onclick="plusMinuschange('~$id`Basic')" id="~$id`BasicA" class="icon-minus">&nbsp;</a>
	</h6>
	<div class="js-content" id="~$id`BasicDiv">
		<!-- About Partner-->
		~if $MobileAbtArr.SPOUSEINFO neq ''`
				<p id="spouseinfo" name="spouseinfo" class="textaligninfo">
					~$MobileAbtArr.SPOUSEINFO|decodevar`
				</p>
			<div class="clr" style="height:10px"></div>
		~/if`
		
		<table cellpadding="5" cellspacing="0" style="width:100%;">
			<tbody>
				~if $dpartner->getDecoratedLHEIGHT() neq ''`
					<tr>
						<td class="prof_width50">Height</td>
						<td>: ~$dpartner->getDecoratedLHEIGHT()|decodevar` to ~$dpartner->getDecoratedHHEIGHT()|decodevar`</td>
					</tr>
				~/if`
				~if $dpartner->getDecoratedHAGE() neq ''`
					<tr>
						<td class="prof_width50">Age</td>
						<td>: ~$dpartner->getDecoratedLAGE()` to ~$dpartner->getDecoratedHAGE()`</td>
					</tr>
				~/if`
				~if $dpartner->getDecoratedPARTNER_MSTATUS() neq ''`
					<tr>
						<td class="prof_width50">Marital Status</td>
						<td>: ~$dpartner->getDecoratedPARTNER_MSTATUS()`</td>
					</tr>
				~/if`
				~if $dpartner->getDecoratedCHILDREN() neq ''`
					~if  $dpartner->getDecoratedPARTNER_MSTATUS() neq 'Never Married' and $dpartner->getDecoratedPARTNER_MSTATUS() neq "-"`
						<tr>
							<td class="prof_width50">Have Childern</td>
							<td>: ~$dpartner->getDecoratedCHILDREN()`</td>
						</tr>
					~/if`
				~/if`
			</tbody>
		</table>
	</div>
~/if`
<style>
#yourinfo span{float:none!important;}
#familyinfo span{float:none!important;}
#eduinfo span{float:none!important;}
#occinfo span{float:none!important;}
#spouseinfo span{float:none!important;}
</style>
