<script type="text/javascript">
    var profileid = "~$profileid`";
    var currency = "~$data.currency`";
    var pageType = 'chequeSuccessPage';
    var preFilledEmail = "~$data.userDetails.EMAIL`";
    var preFilledMobNo = "~$data.userDetails.PHONE_MOB`";
    var helpAllStr;
    var bannerMsg;
    var bannerTimeout;
    var showCountdown;
    var countdown;
    var topBlockMsg;
    var topBlockCLT;
    var topBlockCLN;
    var topBlockDays;
    var topBlockMonths;
    var vasNames;
    var paidBenefits;
    var tabVal;
    var openedCount;
    
</script>
~include_partial('global/JSPC/_jspcCommonMemRegHeader',[pageName=>'membership'])`
<div class="bg-4">
    <div class="container mainwid">
        <!--start:white container-->
        <div class="pt30 pb30">
            <div class="mem_pad21  bg-white txtc fontlig">
                <p class="mem-colr3 f20 pt10">Thank you for showing interest in our paid services.</p>
                <p class="mem-colr3 f20 pt5">A sales executive will contact you shortly.</p>
                <p class="fontmed f17 pt50">Details Submitted by you</p>
                <ul class="pt20 chqpick">
                    <li><span class="colr2">Request ID</span><span class="color11">: ~$data.ref_number`</span></li>
                    <li><span class="colr2">Date</span><span class="color11">: ~$data.dateTime`</span></li>
                    <li><span class="colr2">Cheque City</span><span class="color11">: ~$data.city`</span></li>
                    <li><span class="colr2">Mobile number</span><span class="color11">: ~$data.mobile`</span></li>
                    ~if $data.landline`
                    <li><span class="colr2">Landline number</span><span class="color11">: ~$data.landline`</span></li>
                    ~/if`
                    <li><span class="colr2">Amount</span><span class="color11">: ~if $data.currency eq '$'`USD~else`~$data.currency`~/if` ~$data.amount`</span></li>
                </ul>
                <p class="mem-colr3 f17 pt20">Paid services will be activated within 48 hours of receipt of your payment.</p>
            </div>
        </div>
        <!--end:white container-->
    </div>
</div>
~include_partial('global/JSPC/_jspcCommonFooter')`
<script type="text/javascript">
    $(window).load(function() {
        eraseCookie('paymentMode');
        eraseCookie('cardType');
    });
</script>