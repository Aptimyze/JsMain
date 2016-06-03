<!--Start:Request Subtype listings-photo/horoscope-->
<div class="ccp9 disp-none" id="js-requestTypeSelectListing">
	<div class="clearfix ccbrdb1 pb5">
			<ul class="fr seclvl f14 color13 hor_list pr10" id="RequestTypesLi">
					~foreach from=$ccRequestTypeListMapping key=k item=v`
					<li class="js-ccRequestTypeLists" data-id="~$k`" id="Request~$k`">~$v["RequestType"]`</li> 
					~/foreach`    
			</ul>  
	</div>   
</div>
<!--End:Request Subtype listings-photo/horoscope-->

<div id="mainUploadRequestDiv" class="disp-none">
</div>

<!--Start:Upload Request Block-photo/horoscope-->
<div id="basicUploadRequestDiv" class="disp-none">
	<p class="txtc fontlig f18 pt24" id="requestMessage">{requestMessage}</p>
	<div class="bg_pink lh40 wid29p mauto txtc mt10">
		<div class="colrw fontlig f15 cursp {requestClass}" data-id={requestID}>{requestButton}</div>
	</div>
</div> 
<!--End:Upload Request Block-photo/horoscope-->

