<!--Header starts here-->
~include_partial('global/header',[pageName=>'membership'])`
<script>
  var user=new Object();
  user.memStatus="~$userObj->memStatus`";
  user.userType="~$userObj->userType`";
  user.ipAddress="~$userObj->ipAddress`";
  user.currency="~$currency`";
  user.profileid="~$profileid`"
  var username="~$USERNAME`";
  var email="~$EMAIL`";
  var checksum="~$profileChecksum`";
  var membership=new Object();
  membership.mainMem="";
  membership.VaMem="";
  membership.SelectedCheck= new Array();
  var tableToShow="";
  var showTable="";
  var mainMem="~$mainMem`";
  var subMem="~$subMem`";
  var cartMainMemPrice="~$mainMemPrice`";
  var cartMainMemIcon="~$mainMemIcon`";
  var fest="~$fest`";
  var landingVAS="~$allMemberships`";
  var discountType="~$discountType`";
  var landingFreebie="~$landingFreebie`";
  var specialActive="~$specialActive`";
  var discountActive="~$discountActive`";
  var fromBackend="~$fromBackend`";
  var backendId="~$backendId`";
  var discountBackend="~$discountBackend`";
  var backendCheckSum="~$backendCheckSum`";
  var matriPrice="~$matriPrice`";
  var allDiscounts=new Array();
  ~foreach from=$allDiscounts key=k item=v`
  allDiscounts["~$k`"]="~$v`";
  ~/foreach`
  ~if $festiveDiscountArr`
  allDiscounts['FESTIVE'] = new Array();
  ~foreach from=$festiveDiscountArr key=k item=v`
  allDiscounts["FESTIVE"]["~$k`"]="~$v`";
  ~/foreach`
  ~/if`
  var exclusiveInfo=new Array();
  ~foreach from=$exclusiveInfo key=k item=v`
  exclusiveInfo["~$k`"]="~$v`";
  ~/foreach`
  var festDurBanner=new Array();
  ~foreach from=$festDurBanner key=k item=v`
  festDurBanner["~$k`"] = new Array();
  ~foreach from=$v key=kk item=vv`
    festDurBanner["~$k`"]["~$kk`"]="~$vv`";
  ~/foreach`
  ~/foreach`
  var eSathiSpecials=new Array();
  var k=0;
  ~foreach from=$eSSpcls key=service item=value`
  eSathiSpecials[k]="~$service`"
  k=k+1;
  ~/foreach`
  var vaMem=new Array();
  ~foreach from=$vaMem key=service item=value`
  vaMem["~$service`"]=new Array();
  ~foreach from=$value key=subService item=value1`
  vaMem["~$service`"]["~$subService`"]=new Array();
  ~foreach from=$value1 key=attribute item=value2`
  vaMem["~$service`"]["~$subService`"]["~$attribute`"]="~$value2`";
  ~/foreach`
  ~/foreach`
  ~/foreach`
  var banks=new Array();
  var days=new Array();
  var months=new Array();
  var year=new Array();
  var curDate="~$cur_day`";
  var curMon="~$cur_month`";
  var curYear="~$cur_year`";
  ~foreach from=$banks key=k item=value`
  banks["~$k`"]="~$value`";
  ~/foreach`
  ~foreach from=$ddarr key=k item=value`
  days["~$k`"]="~$value`";
  ~/foreach`
  ~foreach from=$mmarr key=k item=value`
  months["~$k`"]="~$value`";
  ~/foreach`
  ~foreach from=$yyarr key=k item=value`
  year["~$k`"]="~$value`";
  ~/foreach`
  var cheque_in_US="~$cheque_in_US`";
  var phone_mob="~$PHONE_MOB`";
  var discountSpecial = "~$discountSpecial`";
</script>
<!--Main container starts here-->
<div id="main_cont">
  <div class="sp10"></div>
  <div class="sp10"></div>
  <div class="sp10"></div>
  <div class="sp5"></div>
  <div class="mem-tabs pos-rel">
    <div>
      ~if $userObj->userType eq 7`
      ~foreach from=$newSubStatus item=v`
      Your subscription of ~$v.SERVICE` expires on ~$v.EXPIRY_DT`
      ~/foreach`
      ~/if`
      ~if $userObj->userType eq 5 or $userObj->userType eq 6 or $userObj->userType eq 7`
      ~foreach from=$subStatus item=v`
      ~if $v.LINK eq 'B' or $v.LINK eq 'Y' && $userObj->userType neq 7`
      Your subscription of ~$v.SERVICE` expires on ~$v.EXPIRY_DT`
      ~/if`
      ~/foreach`
    </div>
    <div style="padding: 2px 0px 5px;display: none;">
      <a id="subscriptions" style="text-decoration:underline;cursor:pointer;color:#0f7eab">See all your subscriptions</a>
    </div>
    ~/if`
    <div style="display:none;left:218px; top:-48px; z-index:999999; border-width:3px; border-color:#b2b2b2" class="pos-rel subs" id="lightbox">
      <i id="pointIndicator" class="ico-indicator2 sprte-mem fl"> &nbsp;</i>
      <div style="width:450px; padding-top:10px" class="container2 fl">
        <div class="fl" style="width:420px">
          <div id="subscription-cont">
            <strong>Your current subscriptions</strong>
            <strong style="width:110px">Expires on</strong>
            <ol>
                            <!--~foreach from=$subStatus key=k item=v`
                                                    <li>
                                                        <div>~$v.SERVICE`</div>
                                                        <p>~$v.EXPIRY_DT`</p>
                                                    </li>
                                                    ~/foreach`-->
                                                    ~foreach from=$subStatus item=v`
                                                    ~if $v.LINK neq 'N'`
                                                    <li style='float:none;'>
                                                      <div style="width:400px;">
                                                        <div style="display:inline-block">~$v.SERVICE`</div>
                                                        <div style="display:inline-block;width:120px">~$v.EXPIRY_DT`</div>
                                                      </div>
                                                    </li>
                                                    ~/if`
                                                    ~/foreach`
                                                    ~foreach from=$subStatus key=k item=v`
                                                    ~if $v.LINK eq 'N'`
                                                    <li style='float:none;'>
                                                      <div style="width:400px;">
                                                        <div style="display:inline-block">~$v.SERVICE`</div>
                                                        <div style="display:inline-block;width:120px">~$v.EXPIRY_DT`</div>
                                                      </div>
                                                    </li>
                                                    ~/if`
                                                    ~/foreach`
                                                  </ol>
                                                  <div class="clear"></div>
                                                </div>
                                              </div>
                                              <div class="btn-close fl">X</div>
                                            </div>
                                          </div>
                                        </div>
                                        ~if $bannerDisplay eq '1'`
                                        ~if $specialActive eq '1'`
                                        <!--Discount offer-->
                                        <div class="mem-holi2 fl" style="margin-top:10px;">
                                          <div class="fl" style="margin-left:173px; width:auto; margin-top:36px">
                                            <div class="  fs24">
                                              Buy before
                                              <font class="maroon" style=" font-size:30px">~$variable_discount_expiry`</font> and get ~$discountLimitTextVal` <span > <font class="maroon" style=" font-size:30px">~$discountSpecial`% OFF
                                              <font class="fs24 black">on plans</font></font> </span>
                                            </div>
                                          </div>
                                        </div>
                                        ~else if $userObj->userType eq 6 or $userObj->userType eq 4`
                                        ~if $userObj->contactsRemaining eq '' or $userObj->contactsRemaining eq 0 or $userObj->userType eq 4`
                                        <div class="sp10 clear"></div>
                                        <div class="mem-holi2 fl">
                                          <div class="fl" style="margin-left:173px; width:auto; margin-top:36px">
                                            <div class="  fs22">
                                              Renew by
                                              <font class="maroon" style=" font-size:30px">~$userObj->expiryDate`</font> and get <span > <font class="maroon" style=" font-size:30px">~$renewalPercent`% OFF
                                              <font class="fs24 black">on all plans</font></font> </span>
                                            </div>
                                          </div>
                                        </div>
                                        ~else`
                                        <div class="mem-holi2 fl">
                                          <div class="fl" style="margin-left:173px; margin-top:29px">
                                            <div class="fl fs22" style="width:280px">
                                              <p>Renew by</p>
                                              <p><font class="maroon">~$userObj->expiryDate`</font> and get </p>
                                            </div>
                                            <div class="fl" style="width:110px">
                                              <font class="maroon" style=" font-size:30px;"><span class="fs22">~$renewalPercent`% OFF</span><br /><font class="fs20 black">on all plans</font></font>
                                            </div>
                                            <div class="fl mar10right"><img src="/images/plussign.png"></img></div>
                                            <div class=" fl">
                                              <font class="maroon" style=" font-size:30px"><span class="fs22">~$userObj->contactsRemaining` additional </span><br /><font class="fs20 black">contact details</font></font></div>
                                            </div>
                                          </div>
                                          ~/if`
                                          ~else if $discountActive eq '1'`
                                          <!--special discount-->
                                          <div class="mem-holi2 fl" style="margin-top:10px;">
                                            <div class="fl" style="margin-left:173px; width:auto; margin-top:36px">
                                              <div class="  fs24">
                                                Buy before
                                                <font class="maroon" style=" font-size:30px">~$discount_expiry`</font> and get ~$cashDiscountDisplayText` <span > <font class="maroon" style=" font-size:30px">~$discountPercent`%
                                                <font class="fs24 black">discount</font></font> </span>
                                              </div>
                                            </div>
                                          </div>
                                          ~else if $fest eq '1'`
    <!-- <div style="padding:15px 0px">
            <img src='/~$festBanner`'/>
          </div> -->
          ~/if`
          ~/if`
          <ul class="tabs ">
            <li ><a class="carryForms" style="height:24px;cursor:pointer" goTo="chooseMem">Choose Membership Plan</a></li>
            <li><a class="carryForms" style="height:24px;cursor:pointer" goTo="chooseValue">Choose Additional Services</a></li>
            <li><a class="active" href="#tab3">Payment Options</a></li>
          </ul>
          <form action="/membership/valueAddedMembership" id="backToValueAddedForm" method="POST" >
            <input type="hidden" name="jsSel" id="jsSel" value="~$jsSel`"/>
            <input type='hidden' name="navigationStringToVas" id="navigationStringToVas" value=''></input>
            <input type='hidden' name='track_discount_vas' value=''></input>
            <input type='hidden' name='track_total_vas' value=''></input>
            <input type='hidden' name='mainSubMemId' id="mainSubMemId" value='~$subMem`'></input>
            <input type='hidden' name='selMemberships' value='~$selMemberships`'></input>
            <input type="hidden" name="allMembershipsToVAS" value="" />
          </form>
          <!-- TAB 3 CONTENT STARTS -->
          <div id="tab3">
            <div class="center fs14 pad-new color-new fullwidth b">
              Got any Questions about Paying Online? Call us at <span class="color-new1">~if $currency eq 'RS'`1800-419-6299 (Toll Free)~else`+911204393500~/if`</span> or just
              <a style="cursor:pointer;" class="color-blue" id="excCallNew" onClick="execCallback('3','JS_ALL');">Request a Call Back from us</a>.
              <div class="pos-rel">
                ~include_partial('global/membershipAlertLayout')`
              </div>
            </div>
            <div style="display: none; padding:0px 20px; width:890px;" id="tab3New">
              <div class="mem-tab3-leftcon" style="position:relative">
                <div id="lightbox" class="sampleCheque" style="top:118px; left:410px; height:auto;visibility:hidden">
                  <i class="ico-indicator sprte-mem fl sample-indicator" style="top:135px">   &nbsp;</i>
                  <div class="container fl" style="width:339px" >
                    <div class="fl w296">
                      <div class="b">Sample Cheque</div>
                      <!--<div class="mem-ico-cheque sprte-mem ">&nbsp;</div>-->
                      <div>
                        <div ~if $currency eq 'RS'` class="mem-ico-rupee-cheque sprte-mem" ~else` class="mem-ico-dollar-cheque sprte-mem" ~/if` name="pickup_image" id="pickup_image2">
                          <span style='margin-left:220px;display:inline-block;margin-top:7px;'>~$ORDERDATE`</span>
                          <!--<h5>Jeevansathi Internet Services</h5>-->
                          <b><div id="amt_words" style='margin-left:50px;margin-top:36px;height:27px;'>~$AMOUNT_WORDS`</div></b>
                          <div style='margin-left:210px;margin-top:16px;'>~if $currency eq 'RS'` <span style='font-family:WebRupee'>Rs.</span>&nbsp;<span id="tot_price"> ~$totalCartValue`</span>~else`$<span id=tot_price> ~$totalCartValue`</span>~/if`</span></div>
                        </div>
                        <div class="chk_bg_new" name="pickup_image_new" id="pickup_image2_new" style="display:none">
                          <u>~$ORDERDATE`</u>
                          <b>~$AMOUNT_WORDS`</b>
                          <em>~if $currency eq 'RS'`Rs. ~$PRICE`~else`~$PRICE` $~/if`</em>
                        </div>
                      </div>
                      <div class="maroon b">Important Instructions</div>
                      <div class="fl">
                        <ul>
                          <li style="padding:3px 0px;" class="block"><span style="display: inline-block; width: 10px; height: 10px; background: url(/images/spriteblueimg1.png) no-repeat scroll -3px -55px transparent;"></span><span>Do not forget to sign your cheque.</span> </li>
                          <li style="padding-bottom:3px" class="block"><span style="display: inline-block; width: 10px; height: 10px; background: url(/images/spriteblueimg1.png) no-repeat scroll -3px -55px transparent;"></span><span>Remember to mention ~$USERNAME` on the reverse of the Cheque / Demand Draft. </span></li>
                          <li style="padding-bottom:3px" class="block">
                            <span style="display: inline-block; width: 10px; height: 10px; background: url(/images/spriteblueimg1.png) no-repeat scroll -3px -55px transparent;"></span><span>Please quote ~$USERNAME` and date in all future correspondence with us.</span> </li>
                            <li class="block"><span style="display: inline-block; width: 10px; height: 10px; background: url(/images/spriteblueimg1.png) no-repeat scroll -3px -55px transparent;"></span><span>Your subscription will be activated within 2 working days of receipt of your payment.</span></li>
                          </ul>
                                <!--<ul id="sampleList" style="list-style:none;list-style-type:disc outside none;padding-left:2px;">
                                                <li>Do not forget to sign your cheque. </li>
                                    <li>Remember to mention ~$USERNAME` and Date on the reverse of the Cheque / Demand Draft. </li>
                                    <li>
                                    Please quote ~$USERNAME` and date in all future correspondence with us. </li>
                                    <li>Your subscription will be activated within 2 working days of receipt of your payment.</li>
                                  </ul>-->
                                </div>
                              </div><div class="btn-close fl">X</div>
                            </div>
                          </div>
                          <div id="lightbox" class="postLightbox" style=" left: 400px; top: 123px; height:auto;visibility:hidden">
                            <i class="ico-indicator sprte-mem fl" style="top:125px"> &nbsp;</i>
                            <div class="container fl" style="width:339px" >
                              <div class="fl w296">
                                <div class="b fs14">Sample FREE Post</div>
                                <div class="sp10"></div>
                                <div class="mem-ico-post sprte-mem fl">&nbsp;</div>
                                <div class="fl w140 mar25left">
                                  <div class="mar50top">
                                    <!--1 Put your cheque in an envelop and write <b>Post No. 201951 -->
                                    <div style="width: 150px;">
                                      <div style="font-weight: bold; padding-right: 5px; width: 12px;" class="fl"><div style="font-size:20px">1</div></div>
                                      <div class="fl" style="width: 128px;">
                                        <div>Put your cheque in an envelop and write </div>
                                        <div><b>Post No. 201951 </b></div>
                                      </div>
                                      <div style="clear:both;"></div>
                                    </div>
                                  </div>
                                  <div class="mar50top">
                                    <!--2 Simply post this in any post box-->
                                    <div style="width: 150px;">
                                      <div style="font-weight: bold; padding-right: 5px; width: 12px;" class="fl"><div style="font-size:20px">2</div></div>
                                      <div class="fl" style="width: 128px;">
                                        <div>Simply post this in any post box</div>
                                      </div>
                                      <div style="clear:both;"></div>
                                    </div>
                                  </div>
                                </div>
                              </div><div class="btn-close fl post-close">X</div>
                            </div>
                          </div>
                          <form action="/membership/membershipMaster" id="backToMembership" name="backToMembership" method="POST">
                            <input type="hidden" name="jsSel" id="jsSel" value="~$jsSel`"/>
                            <input type="hidden" name="allMembershipsToMain" value="" />
                            <input type="hidden" name="fromPaymentTab" id="fromPaymentTab" value="1"/>
                          </form>
                          <!--<form id="paymentOptionsFormParent" method="POST">-->
                          <div class="verticalslider" id="textExample">
                            <!--<form id="paymentOptionsForm" method="POST">-->
                            <ul class="verticalslider_tabs">
                              <li><a href="#" class="nonForm creditLink" trackid="CR">Credit Cards</a></li>
                              <li><a href="#" class="nonForm debitLink" trackid="DC">Debit Cards</a></li>
                              ~if $paypal_visible eq 'Y'`
                              <li><a href="#" class="nonForm paypalLink" trackid="PP">Paypal</a></li>
                              ~/if`
                              ~if $currency eq 'RS'`
                              <li><a href="#" class="nonForm netBankMain" trackid="NB">Net Banking</a></li>
                              ~else`
                              <li style="display:none"><a href="#" class="nonForm netBankMain" trackid="NB">Net Banking</a></li>
                              ~/if`
                              ~if $cash_card_visible eq 'Y' && $calculatedTotal lte '10000'`
                              <li><a href="#" class="nonForm cashCardLink" trackid="CSH">Wallets</a></li>
                              ~/if`
                              <li id="chequeDep"><a href="#" class="cashForm" trackid="CCD"> Cash / Cheque Deposit</a></li>
                              ~if $courier_visible eq 'Y'`
                              <li id="pickup"><a href="#" class="requestCashForm" trackid="CCP">Cash / Cheque FREE pick-up</a></li>
                              ~/if`
                              ~if $pay_at_branches eq 'Y'`
                              <li><a href="#" class="pay_at_branches" trackid="PB">Pay at our Branches     </a></li>
                              ~/if`
                            </ul>
                            <ul class="verticalslider_contents">
                              <li>
                                <div class="fl b fs14">All major Credit cards are accepted. </div>
                                <div class="sp15"></div>
                                <div class="sp15"></div>
                                <div class="mem-div-card">
                                  <input type="radio" class="fl vam chbx creditVisa" id="r1" name="paymode" value="card" /><i class="ico-visa sprte-mem fl">&nbsp;</i>
                                </div>
                                <div class="mem-div-card">
                                  <input type="radio" class="fl vam chbx" id="r1" name="paymode" value="card" /><i class="ico-master sprte-mem fl">&nbsp;</i>
                                </div>
                                <div class="sp15"></div>
                                <div class="sp15"></div>
                                <div class="mem-div-card">
                                  <input type="radio" class="fl vam chbx" id="r2" name="paymode" value="card2"/><i class="ico-amex sprte-mem fl">&nbsp;</i>
                                </div>
                                <div class="mem-div-card">
                                  <input type="radio" class="fl vam chbx" id="r2" name="paymode" value="card2" /><i class="ico-diners sprte-mem fl">&nbsp;</i>
                                </div>
                                <div class="sp15"></div>
                                <div class="sp15"></div>
                                <div class="mem-div-card">
                                  <input type="radio" class="fl vam chbx" id="r2" name="paymode" value="card2" /><i class="ico-jcb sprte-mem fl">&nbsp;</i>
                                </div>
                              </li>
                              <li>
                                <div class="fl b fs14">All major Debit cards are accepted. </div>
                                <div class="sp15"></div>
                                <div class="sp15"></div>
                                <div class="mem-div-card">
                                  <input type="radio" class="fl vam chbx debitVisa" id="r10" name="paymode" value="card9" /><i class="ico-visa sprte-mem fl">&nbsp;</i>
                                </div>
                                <div class="mem-div-card">
                                  <input type="radio" class="fl vam chbx" id="r10" name="paymode" value="card9" /><i class="ico-master sprte-mem fl">&nbsp;</i>
                                </div>
                                <div class='sp15'></div>
                                <div class='sp15'></div>
                                <div class="mem-div-card">
                                  <input type="radio" class="fl vam chbx" id="r2" name="paymode" value="card2" /><i class="ico-rupay sprte-mem fl">&nbsp;</i>
                                </div>
                              </li>
                              ~if $paypal_visible eq 'Y'`
                              <li>
                                <div class="sp15"></div>
                                <div class="sp15"></div>
                                <div class="mem-div-card">
                                  <input type="radio" class="fl vam chbx payPalOption" id="r7" name="paymode" value="pcard" /><img src="/images/paypal_img1.jpg"/>&nbsp;</i>
                                </li>
                                ~/if`
                                <li>
                                  <div class="fl b fs14">Pay through Net Banking of any of these banks: </div>
                                  <div class="sp10"></div>
                                  <div class="sp10"></div>
                                  <div>
                                    <div class="fl lh18 w175 ">
                                      <a id="r11" class="netBank" value="AND_N" style="cursor:pointer">     Andhra Bank</a><br />
                                      <a id="r11" class="netBank" value="UTI_N" style="cursor:pointer"> Axis Bank</a><br />
                                      <a id="r11" class="netBank" value="BOB_N" style="cursor:pointer"> Bank of Baroda</a><br />
                                      <a id="r11" class="netBank" value="BOI_N" style="cursor:pointer"> Bank of India</a><br />
                                      <a id="r11" class="netBank" value="CAN_N" style="cursor:pointer"> Canara Bank</a><br />
                                      <a id="r11" class="netBank" value="CBIBAN_N" style="cursor:pointer"> Citibank Bank</a><br />
                                      <a id="r11" class="netBank" value="COP_N" style="cursor:pointer"> Corporation Bank</a><br />
                                      <a id="r11" class="netBank" value="DCB_N" style="cursor:pointer"> DCB Bank</a><br />
                                      <a id="r11" class="netBank" value="FDEB_N" style="cursor:pointer"> Federal Bank</a><br />
                                      <a id="r11" class="netBank" value="HDEB_N" style="cursor:pointer"> HDFC Bank</a><br />
                                      <a id="r11" class="netBank" value="ICPRF_N" style="cursor:pointer"> ICICI Bank</a><br />
                                      <a id="r11" class="netBank" value="IDBI_N" style="cursor:pointer"> IDBI Bank</a><br />
                                      <a id="r11" class="netBank" value="IOB_N" style="cursor:pointer"> Indian Overseas Bank</a><br />
                                      <a id="r11" class="netBank" value="NIIB_N" style="cursor:pointer"> IndusInd Bank</a><br />
                                      <a id="r11" class="netBank" value="ING_N" style="cursor:pointer"> ING Vysya Bank</a><br />
                                    </div>
                                    <div class="fl lh18">
                                      <a id="r11" class="netBank" value="JKB_N" style="cursor:pointer"> Jammu &amp; Kashmir Bank</a><br />
                                      <a id="r11" class="netBank" value="KVB_N" style="cursor:pointer"> Karur Vysya Bank</a><br />
                                      <a id="r11" class="netBank" value="NKMB_N" style="cursor:pointer"> Kotak Mahindra Bank</a><br />
                                      <a id="r11" class="netBank" value="LVB_N" style="cursor:pointer"> Lakshmi Vilas Bank</a><br />
                                      <a id="r11" class="netBank" value="OBPRF_N" style="cursor:pointer"> Oriental Bank of Commerce</a><br />
                                      <a id="r11" class="netBank" value="NPNB_N" style="cursor:pointer"> Punjab National Bank</a><br />
                                      <a id="r11" class="netBank" value="SIB_N" style="cursor:pointer"> South Indian Bank</a><br />
                                      <a id="r11" class="netBank" value="SCB_N" style="cursor:pointer"> Standard Chartered Bank</a><br />
                                      <a id="r11" class="netBank" value="SBJ_N" style="cursor:pointer"> State Bank of Bikaner and Jaipur</a><br />
                                      <a id="r11" class="netBank" value="SBH_N" style="cursor:pointer"> State Bank of Hyderabad</a><br />
                                      <a id="r11" class="netBank" value="SBI_N" style="cursor:pointer"> State Bank of India</a><br />
                                      <a id="r11" class="netBank" value="SBP_N" style="cursor:pointer"> State Bank of Patiala</a><br />
                                      <a id="r11" class="netBank" value="UNI_N" style="cursor:pointer"> Union Bank of India</a><br />
                                      <a id="r11" class="netBank" value="YES_N" style="cursor:pointer"> YES Bank</a><br />
                                      <a id="r11" class="netBank" value="" style="cursor:pointer"> Other Banks</a><br />
                                    </div>
                                  </div>
                                </li>
                                ~if $cash_card_visible eq 'Y' && $calculatedTotal lte '10000'`
                                <li>
                                  <div class="fl b fs14">Please select a cash card.</div>
                                  <div class="sp15"></div>
                                  <div class="sp15"></div>
                                  <div class="mem-div-card">
                                    <input type="radio" class="fl vam chbx" name="paymode" id="r4" value="card3" selectedCard="MOBKP_N"/><i class="ico-mobikwik sprte-mem fl">&nbsp;</i>
                                  </div>
                                  ~if $calculatedTotal lte '6000'`
                                  <div class="mem-div-card">
                                    <input type="radio" class="fl vam chbx" name="paymode" id="paytm" value="paytm" selectedCard="paytm"/><i class="ico-paytm sprte-mem fl">&nbsp;</i>
                                  </div>
                                  ~/if`
                                  <div class="sp15"></div>
                                  <div class="sp15"></div>
                                </li>
                                ~/if`
                                <li>
                                  <!-- ACCORDION STARTS -->
                                  <div id="Accordion1" class="Accordion" tabindex="0" style="outline:none">
                                    <div class="AccordionPanel">
                                      <div class="AccordionPanelTab fs14" style="font-weight:bold;line-height:2.1"><input type="radio" name="deposit" class="vam chbx form3Check" style=" vertical-align:middle" id="transfer"/> Transfer fund online</div>
                                      <div class="AccordionPanelContent" id="transferDiv" style="display:none">
                                        <div class="fl">
                                         <pre>
                                          A/c Name  : Jeevansathi Internet Services
                                          A/c No.   : 003705010255
                                          Bank      : ICICI Bank
                                          Branch    : Preet Vihar, New Delhi - 110096
                                          IFSC Code : ICIC0000037</pre>
                                          <div class="sp10"></div>
                                          <div class="b">After transferring the amount, please enter the details below.</div>
                                          <div id="mem-tab3-form">
                                            <div><label>Transaction No. </label> : <input type="text" /></div>
                                            <div><label>Date</label> : <select class="w40" id="day"><option>28</option></select> <select class="w40" id="month" style="margin:0px 8px"><option>1</option></select><select class="w55" id="year"><option>2012</option></select>
                                            </div>
                                            <div><label>Bank Name   </label> : <select><option></option></select></div>
                                            <div><label>If Other  </label> : <input type="text" /></div>
                                            <div><label>City </label> : <input type="text" /></div>
                                            <div><label>Mobile number </label> : <span><input type="text" /><br /><font class="err mar95left">Error - Error message format for all field<br /></font></span>
                                            </div>
                                            <div><label>Amount  </label> :  <strong>Rs.  6645</strong></div>
                                            <div><label>Comments  </label> :<span> <textarea rows="5" cols="10"></textarea><br /><font class="err mar95left">Error - Error message format for all field</font></span>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="AccordionPanelTab fs14" style="font-weight:bold;line-height:2.1"><input type="radio" name="deposit" class="vam chbx form3Check" style=" vertical-align:middle" id="drop"/> Drop cheque at any ICICI Bank drop box</div>
                                      <div class="AccordionPanelContent" id="dropDiv" style="display:none">
                                        <div class="fl">
                                         <pre>
                                          A/c Name  : Jeevansathi Internet Services
                                          A/c No.   : 003705010255
                                          <a id="seeCheque" class="b fs12 " style="cursor:pointer"><u>See Sample cheque</u></a>
                                        </pre>                          <div class="sp10"></div>
                                        <div class="b">After transferring the amount, please enter the details below.</div>
                                        <div id="mem-tab3-form">
                                          <div><label>Cheque No. </label> : <input type="text" /></div>
                                          <div><label>Date</label> : <select class="w40" id="day"><option>28</option></select> <select class="w40" id="month" style="margin:0px 8px"><option>1</option></select><select class="w55" id="year"><option>2012</option></select>
                                          </div>
                                          <div><label>Bank Name   </label> : <select><option></option></select></div>
                                          <div><label>If Other  </label> : <input type="text" /></div>
                                          <div><label>Cheque City    </label> : <input type="text" /></div>
                                          <div><label>Mobile number </label> : <span><input type="text" /><br /><font class="err mar95left">Error - Error message format for all field<br /></font></span>
                                          </div>
                                          <div><label>Amount  </label> :  <strong>Rs.  6645</strong></div>
                                          <div><label>Comments  </label> :<span> <textarea rows="5" cols="10"></textarea><br /><font class="err mar95left">Error - Error message format for all field</font></span>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="AccordionPanelTab fs14" style="font-weight:bold;line-height:2.1"><input type="radio" name="deposit" class="vam chbx" style=" vertical-align:middle" id="courier"/> Courier cheque to our head office</div>
                                  </div>
                                  <form action="/profile/pg/transecute/chequedrop.php" method="POST" id="form3">
                                    <input type="hidden" name="checksum" value="~$profileChecksum`">
                                    <input type="hidden" name="curtype" value="~$currency`">
                                    <input type="hidden" name="submitType" id="submitType_form3" value=""/>
                                    <input type="hidden" name="service_main" id="service_main_form3" value=""/>
                                    <input type="hidden" name="type" id="type_form3" value=""/>
                                    <input type="hidden" name="service" id="service_form3" value=""/>
                                    <input type=hidden name=USERNAME value="~$USERNAME`">
                                    <input type="hidden" name="depositType" id="depositType" value=""/>
                                    <div class="AccordionPanelContent" id="chequeForm" style="height:auto!important; position:relative;visibility:hidden">
                                      <div id="staticDiv" style="display:none;">
                                        <div> Payable to <strong>&quot;Jeevansathi Internet Services&quot;</strong>
                                          <br />
                                          <a href="#" class="b fs12 ">See Sample cheque</a>
                                        </div>
                                        <div class="sp15"></div>
                                        <div>
                                          Please mention <strong>&lt;Username&gt;</strong> or  <strong> &lt;Email&gt; </strong> and <strong> services</strong>
                                          on the back of the cheque.
                                        </div>
                                        <div class="sp15"></div>
                                        <div>
                                          Post your Cheque for <strong>FREE</strong> in an envelop addressed
                                          to <strong>Post No. 201951</strong> and drop it in any post box.
                                          You don't need to paste any stamp. <br />
                                          <a href="#" class="b fs12">How to Post for FREE</a>
                                        </div>
                                        <div class="sp15"></div>
                                        <div>
                                          <strong>OR send your cheque to</strong> <br />
                                          Jeevansathi Client Relations,<br />
                                          B - 8, Sector - 132, <br />
                                          Noida - 201301<br />
                                          Phone : +91-120-4393500<br />
                                        </div>
                                      </div>
                                    </div>
                                  </form>
                                </div>
                                <!-- ACCORDION FINISH -->
                              </li>
                              ~if $courier_visible eq 'Y'`
                              <li>
                                <form id="form1" action="/profile/pg/transecute/chequedrop.php" method="POST">
                                  <div class="fl b fs14">Please provide details for FREE cheque pickup. </div>
                                  <div class="fl">
                                    <a id="seeCheque" class="seeCheque b fs12 " style="cursor:pointer"><u>See Sample cheque</u></a>
                                    <div class="sp10"></div>
                                    <div id="mem-tab3-form">
                                      <!--<form id="form1" name="form1" action="/profile/pg/transecute/chequedrop.php" method="POST">-->
                                      <input type="hidden" name="ser_main" value="~$SER_MAIN`">
                                      <input type=hidden name=stp value="~$STP`">
                                      <input type=hidden name=checksum value="~$profileChecksum`">
                                      <input type=hidden name=USERNAME value="~$USERNAME`">
                                      <input type=hidden name=EMAIL value="~$EMAIL`">
                                      <input type=hidden name=SERVICE id="ReqService" value="">
                                      <input type=hidden name=profileid value="~$PROFILEID`">
                                      <input type=hidden name=AMOUNT value="~$AMOUNT`">
                                      <input type=hidden name=ADDON_SER value="~$ADDON_SER`">
                                      <input type=hidden name=ADDON_SERVICES value="~$ADDON_SERVICES`">
                                      <input type=hidden name=REQUESTID value="~$ORDERID`">
                                      <input type=hidden name=MAIN_SER_NAME value="~$MAIN_SER_NAME`">
                                      <input type=hidden name=CUR_TYPE value="~$CUR_TYPE`">
                                      <input type=hidden name=dec_ag value="~$DEC_AG`">
                                      <input type=hidden name=COURIER value="~$COURIER`">
                                      <input type=hidden name=PINCODE value="~$PINCODE`">
                                      <!--<input type=hidden name=city value="" id='city_form1'>-->
                                      <input type="hidden" name="submitType" id="submitType_form1" value=""/>
                                      <input type="hidden" name="service_main" id="service_main_form1" value=""/>
                                      <input type="hidden" name="type" id="type_form1" value=""/>
                                      <input type="hidden" name="service" id="service_form1" value=""/>
                                      <input type="hidden" name="requestSubmit" id="requestSubmit" value=""/>
                                      <div>
                                        <label>Name </label> : <input type="text" id="f" name="NAME1" class="required" value='~$USERNAME`'/><br/><font id="f_error" class="err mar95left"></font>
                                      </div>
                                      <div>
                                        <label>Phone Number </label> : <input type="text" id="g" name="PHONE_RES" class="required" value='~$PHONE_RES`'/><br/><font id="g_error" class="err mar95left"></font>
                                      </div>
                                      <div>
                                        <label>Mobile number </label> : <span><input type="text" name="PHONE_MOB" id="h" class="required" value="~$PHONE_MOB`"/><br/><font id="h_error" class="err mar95left"></font></span>
                                      </div>
                                      <div>
                                        <label> City    </label> : <select name="city" id="i">~foreach from=$nearByCities key=k item=v`<option id="~$k`" ~if $k eq $city_res` selected ~/if` value="~$k`">~$v`</option>~/foreach`</select><br/><font id="i_error" class="err mar95left"></font>
                                      </div>
                                      <div>
                                        <label>Address  </label> :<span> <textarea class="required" name="ADDRESS" id="j" rows="5" cols="10" value="~$ADDRESS`">~$ADDRESS`</textarea><br /><br/><font id="j_error" class="err mar95left"></font></span>
                                      </div>
                                      <div>
                                        <label>Preferred Date</label> :~assign var=cur_day value=$cur_day+2` <select class="w40 afterTom" name="pref_day" id="k">~foreach from=$ddarr key=k item=v`<option ~if $v eq $cur_day` selected ~/if` >~$v`</option>~/foreach`</select> <select class="w40 afterTom" name="pref_month" id="l" style="margin:0px 8px">~foreach from=$mmarr key=k item=v` <option ~if $v eq $cur_month` selected ~/if`>~$v`</option>~/foreach`</select><select class="w55 afterTom" name="pref_year" id="m">~foreach from=$yyarr key=k item=v`<option ~if $v eq $cur_year` selected ~/if`>~$v`</option>~/foreach`</select><br />
                                        <div style="margin-left:95px;" class="fs11">(Pick up request takes 48 hours to execute)</div>
                                      </div>
                                      <font id="pref_date_error" class="err mar30left"></font>
                                      <div>
                                        <label>Comments  </label> :
                                        <span>
                                          <textarea rows="5" cols="10" id="COMMENTS" name="COMMENTS"></textarea>
                                          <br />
                                          <font class="mar95left fr fs11">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="MBenefit" class="ques-icn sprte-mem" style="cursor:pointer"></span>&nbsp;For example, I want you to give me a call <br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;before coming to my place; I want you to &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; pick the cheque up at 3 pm.</font>
                                        </div>
                                        <div class="center mt-10" style="float:none;">
                                          <input type="button" id="cashSubmit" name="cashSubmit" value="Submit Request" style="cursor: pointer; display: none; width: 190px; height: 50px;" class="cont-btn-green">
                                        </div>
                                      </div>
                                    </div>
                                  </form>
                                </li>
                                ~/if`
                                ~if $pay_at_branches eq 'Y'`
                                <li>
                                  <div class="fl b fs14">Choose City: <select id="city" name="city">~foreach from=$states key=k item=v`<option value="~$v.STATE`" ~if $v.STATE eq $nearByBranches[0].STATE` selected ~/if`>~$v.STATE`</option>~/foreach`</select>
                                    <div class="sp10"></div>
                                    <a id="seeCheque" class="seeCheque b fs12 "  style="text-decoration:underline;cursor:pointer"><u>See Sample cheque</u></a> </div>
                                    <div id="mem-tab-scroll-con" style="height:350px;">
                                      <!--<div id="nearByBranches">-->
                                      <!--<span id="state_name"><h3>~$nearByBranches[0].STATE`</h3></span>-->
                                      <div id="nearByBranches"><div>
                                        ~foreach from=$nearByBranches key=k item=v`
                                        <span id="state_name"><h3>~$v.NAME`</h3></span>
                                        <div class="fl"><div class="tit-label">CONTACT</div><div class="fl" id="contact">~$v.CONTACT_PERSON`</div></div>
                                        <div class="fl">
                                          <div class="tit-label">ADDRESS</div>
                                          <div class="fl w250" id="address">~$v.ADDRESS`</div>
                                        </div>
                                        <div class="fl fullwidth"><div class="tit-label">PHONE</div><div class="fl" id="phone">~$v.PHONE`</div></div>
                                        <div class="fl"><div class="tit-label">MOBILE</div> <div class="fl">~$v.MOBILE`</div></div>
                                        <br />~/foreach`</div>
<!--<h3>Pitampura</h3>
                    <div class="fl"><div class="tit-label">CONTACT</div><div class="fl">Parul Singh</div></div>
                <div class="fl">
                    <div class="tit-label">ADDRESS</div>
                                    <div class="fl w250">   711, 7th Floor, ITL Twin Towers, B-09, Netaji Subhash Place, Opp.Wazirpur, District Centre,                 Pitampura, Delhi</div>
                </div>
                <div class="fl fullwidth"><div class="tit-label">PHONE</div><div class="fl">9910007594 </div></div>
                <div class="fl"><div class="tit-label">MOBILE</div> <div class="fl">9910007594 </div></div>
<h3>Nehru Place</h3>
                    <div class="fl"><div class="tit-label">CONTACT</div><div class="fl">Ritu Rani/ Shiv Kumar</div></div>
                <div class="fl">
                    <div class="tit-label">ADDRESS</div>
                    <div class="fl w250">   GF-12A, 94, Meghdoot Buliding, Nehru Place,
                            New Delhi - 1100019</div>
                </div>
                <div class="fl fullwidth"><div class="tit-label">PHONE</div><div class="fl">9910006935/9910006341 </div></div>
                <div class="fl"><div class="tit-label">MOBILE</div> <div class="fl">9910006935/9910006341 </div></div>
<h3>Malviya Nagar</h3>
                <div>
                        <div class="fl"><div class="tit-label">CONTACT</div><div class="fl">Anuradha/Jayaprabha</div></div>
                    <div class="fl">
                        <div class="tit-label">ADDRESS</div>
                        <div class="fl w250">   D-88 Lower Basement, Near Costa Coffee,
        Malviya Nager, New Delhi - 110017GF-12A, 94, Meghdoot Buliding, Nehru Place,
                                New Delhi - 1100019</div>
                    </div>
                    <div class="fl fullwidth"><div class="tit-label">PHONE</div><div class="fl">9910006538/9971175142 </div></div>
                    <div class="fl"><div class="tit-label">MOBILE</div> <div class="fl">9910006935/9910006341 </div></div>
                  </div>-->
                </div>
              </li>
              ~/if`
            </ul>
          </div>
          <!--</form>-->
          <br/>
          <div class="sp10"></div>
          <div class="sp5"></div>
        </div>
        <div class="fl w238">
          <div id="mem-tab2-rightcon">
            <div class="summary fs16 b sprte-mem pos-rel"> Review your Order
              <div class="mem-ico-grey-down sprte-mem">&nbsp;</div>
            </div>
            <div id="cartElements" class="cont-sum" style="min-height:143px;">
              <div id='noService'>&nbsp;&nbsp;&nbsp;<b>No service selected</b></div>
            </div>
            <div id="cartDiscount" class="cont-sum" style="color:#e15404;font-weight:bold;padding:15px 15px 8px;">
              <div class='fl' style="width:100px;margin-left:10px;border-top:none;font-size:18px;padding:0px">
                Discount
              </div>
              <div class='fl' id='discountValue' style="width:90px;text-align:right;border-top:none;font-size:18px;padding:0px" >-  ~$freebieSum`</div>
            </div>
            <div class="tot">
              <div class="div-left fs20 " style="margin-left:16px;width:68px;">Total</div>
              <div id="totalCartValue" style="width:130px;text-align:right;" class="fl fs20">₹~$totalCartValue`</div>
            </div>
          </div>
          <div id="coupon_code" class="center" style="margin:0 auto;">
            <div id="coupFailure" class="fs12 mar10top fl" style="color:red;width:225px;margin-left:13px;display:none;margin-top:10px;"></div>
            <div id="coupSuccess" class="fs12 mar10top fl" style="color:green;width:225px;margin-left:13px;display:none;margin-top:10px;"></div>
            <div id="coupText" class="fs11 mar10top fl" style="color:#444;width:225px;margin-left:13px;margin-top:10px;">Enter Coupon Code below to avail discount</div>
            <input id="couponVal" type="text" style="margin-top: 10px; padding-left: 10px; font-size: 15px; width: 125px; height: 24px; text-align: left;" class="fs20" name="coupon_code">
            <input id="applyCoupon" type="button" style="cursor: pointer; font-size: 15px; margin-top: -3px; width: 95px; height: 28px;" value="Apply" class="coup-apply-btn-orange" onclick="applyCoupon(); return false;">
          </div>
          <div class="clr"></div>
          <div class="fs11 mar10top fl" style="color:#444;width:225px;margin-left:13px;">
            All Prices are in ~$currencyType` inclusive of ~$tax_rate`% service tax (including Swachh Bharat Cess).
          </div>
          <div class="clr"></div>
          <!-- start:payment button -->
          <div class="center">
            <div>
              <div style="margin-top:10px;">
                <form name="form41" id="makePaymentForm" method="POST">
                  ~if $DISC eq 'N'`
                  <input type="hidden" id="voucher_code" name="voucher_code" value="~$voucher_code`">
                  <input type="hidden" id="avail_discount" name="avail_discount" value="~if $avail_discount eq 'Y'`~$avail_discount`~else`N~/if`">
                  ~/if`
                  <!--<input type="hidden" name="netBankingCards" id="net_banking_cards"/>-->
                  <input type="hidden" id="paymode" name="paymode"/>
                  <input type="hidden" id="DISCOUNT_MSG" name="DISCOUNT_MSG" value="~$DISCOUNT_MSG`">
                  <input type="hidden" id="DISCOUNT_TYPE" name="DISCOUNT_TYPE" value="~$DISCOUNT_TYPE`">
                  <input type="hidden" id="DISCOUNT" name="DISCOUNT" value="~if $DISC eq 'Y'`~$DISCOUNTED_PRICE`~else`~$DISCOUNT`~/if`">
                  <input type="hidden" name="checksum" value="~$profileChecksum`">
                  <input type="hidden" name="from_source" value="~$from_source`">
                  <input type="hidden" name="type" id="type" value="~$currency`">
                  <input type="hidden" name="service_main" id="service_main" value="~$IDS`">
                  <input type="hidden" name="PRICE" id='pl' value="~$PRICE`">
                  <input type="hidden" name="service" id='service' value="~$MID`">
                  <input type="hidden" name="serveprice" value="~$MPRICE`">
                  <input type="hidden" name="cardOption" id="card_option">
                  <input type="hidden" name="netBankingCards" id="net_banking_cards"/>
                  <input type="hidden" name="serveprice" value="~$MPRICE`">
                  <input type="hidden" name="discSel" value="">
                  <input type="hidden" name="navigationString" value="CR">
                  <input type="hidden" name="track_discount" value="">
                  <input type="hidden" name="track_total" value="">
                  <input type="hidden" name="track_memberships" value="">
                  <input type="hidden" name="couponCodeVal" value="">
                  <input type="hidden" name="CCRDType" id="CCRDType">
                  <input type="hidden" name="device" value="~$device`"/>
                  <input type="button" id="makePaymentButton" class="cont-btn-green" value="Make Payment" style="margin-top:0px;cursor:pointer;width:190px;height:50px;"/>
                </form>
              </div>
              <div>
                <!--input type="button" id="cashSubmit" name="cashSubmit" value="Submit" class="cont-btn-green" style="margin-top:0px;cursor:pointer;display:none;width:130px;height:50px;"/-->
              </div>
            </div>
            <div style="clear:both;margin:10px 0px 0px 50px;display:block" class="secure_tran">
              <i class="mem-ico-lock sprte-mem fl"></i><font class="fl b lh18">Secure Transaction</font>
            </div>
            <div class="fs11 mar10top fl" style="color:#444;width:225px;margin-left:13px;">
              Do review your purchase above before you make the payment because your payment is <b>non-refundable</b> either in part or in full.
            </div>
          </div>
          <!-- end:payment button -->
        </div>
        <div style="clear:both"></div>
      </div>
      <!-- TAB 3 CONTENT FINISH -->
      <div style="padding-top:10px;">
        <div style="border-top:1px solid #eee;padding:10px;text-align:center;">Every transaction on Jeevansathi.com is secure. Any personal information provided by you will be handled according to our <a href="/profile/privacy_policy.php" target="_blank">Privacy Policy</a>.</div>
      </div>
    </div>
  </div>
  <div class="sp10"></div>
  <div class="sp5"></div>
  <div class="sp15"></div>
  <div class="sp15"></div>
  <div class="sp15"></div>
  <div class="sp10"></div>
  <div class="sp15"></div>
  <div class="sp15"></div>
</div>
<div class="sp10"></div>
</div><!--Main container ends here-->
~include_partial('global/footer')`
<script>
    function autoPopupFreshdesk(username, email){
        var len = $("#fc_chat_layout").length;
        if(len){
            $("#fc_chat_layout").click();
            if($("#fc_chat_layout input[id*='name']").length){
                $("#fc_chat_layout input[id*='name']").val(username);
            }
            if($("#fc_chat_layout input[id*='email']").length){
                $("#fc_chat_layout input[id*='email']").val(email); 
            }
            $("#fc_chat_header").click();
        }
    }
    function autoPopulateFreshdeskDetails(username, email){
        if($("#fc_chat_layout input[id*='name']").length){
            var checkName = $("#fc_chat_layout input[id*='name']").val();
            if(checkName != ''){
                $("#fc_chat_layout input[id*='name']").val(username);
            }
        }
        if($("#fc_chat_layout input[id*='email']").length){
            var checkEmail = $("#fc_chat_layout input[id*='email']").val(); 
            if(checkEmail != ''){
                $("#fc_chat_layout input[id*='email']").val(email); 
            }
        }
    }
    // Wait until the DOM has loaded before querying the document
    $(document).ready(function(){
      $("#tab3New").show();
      $("#lightbox1").hide();
      $("#lightbox2").hide();
    });
</script>
