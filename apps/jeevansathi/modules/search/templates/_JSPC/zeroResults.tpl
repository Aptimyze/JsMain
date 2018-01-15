~if $resultCount eq 0`
<div class="mainwid container pt30 pb30 disp-none" id="zeroResultSection">
~else`
<div class="mainwid container pt30 pb30" id="zeroResultSection">
~/if`
	<div class="srppad19">
		<div class="txtc fontlig f15">
			<div class="f26 colr5" id="zeroPageHeading">~$pageHeading`</div>
			<div class="colr2 pt37" id="zeroPageMsg">~$noresultmessage`</div>
		</div>
	</div>
</div>
