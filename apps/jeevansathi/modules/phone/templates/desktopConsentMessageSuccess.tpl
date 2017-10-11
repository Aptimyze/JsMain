<script>
    function confirmConsent() {
		var URL="/phone/ConsentConfirm";
				$.ajax({
				url: URL,
				type: "POST",
				data: ""
			});
			}
                                            </script>
<div id='DNC-Consent-layer' class="layerMidset layersZ setshare pos_fix calwid1">
        <div class="calhgt1 calbg1 fullwid disp-tbl txtc">
            <div class="disp-cell vmid fontlig color11">
                <div style='width:515px;' class="mauto">
                    <p class="f14 pt25 lh22">Dear ~$username`,<br /> <br />We would like to inform you that as per your account settings you have agreed to receive calls from our customer support team, even though your number is registered in NCPR.<br /><br />
You can always change your preference from the ‘Alert Manager Settings’ page<br />(Page available on the desktop site only)
</p>
                </div>            
            </div>
        </div>
        <div class="clearfix">
            <button id='consentLayerOKButton' onclick='confirmConsent();' class="bg_pink cursp f18 colrw txtc fontreg lh61 brdr-0 fullwid fl">OK</button>
            
        </div>
    </div>