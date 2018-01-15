<!--Header starts here-->
~include_partial('global/header',[pageName=>'membership'])`
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
  var showTable="";
  var tableToShow="";
  var vaMem=new Array();
  var user=new Object();
  user.memStatus="~$userObj->memStatus`";
  user.ipAddress="~$userObj->ipAddress`";
  user.currency="~$userObj->currency`";
  user.userType="~$userObj->userType`";
  user.profileid="~$userObj->profileid`";
  var addonOrder=new Array();
  var selectedAddon=new Array();
  var eSathiSpecials=new Array();
  var k=0;
  var lowestPrices=new Array();
  var currencyLabel="";
  ~foreach from=$eSSpcls key=service item=value`
  eSathiSpecials[k]="~$service`"
  k=k+1;
  ~/foreach`
  ~foreach from=$lowestPrices key=main item=value`
  lowestPrices["~$main`"]="~$value`"
  ~/foreach`
  ~foreach from=$vaMembership key=service item=value`
  vaMem["~$service`"]=new Array();
  ~foreach from=$value key=subService item=value1`
  vaMem["~$service`"]["~$subService`"]=new Array();
  ~foreach from=$value1 key=attribute item=value2`
  vaMem["~$service`"]["~$subService`"]["~$attribute`"]="~$value2`";
  ~/foreach`
  ~/foreach`
  ~/foreach`
  ~foreach from=$addonOrder key=k item=value`
  addonOrder["~$k`"]="~$value`";
  selectedAddon["~$value`"]="";
  ~/foreach`
  var membership=new Object();
  var cartElements=new Array();
  var allDiscounts=new Array();
  var festDurBanner=new Array();
  var fromPayment="~$fromPayment`";
  var selMemDur="~$selMemDur`";
  ~foreach from=$cartElements key=k item=v`
  cartElements["~$k`"]=new Array();
  cartElements["~$k`"]["ID"]="~$v.ID`";
  cartElements["~$k`"]["PRICE"]="~$v.PRICE`";
  cartElements["~$k`"]["DURATION"]="~$v.DURATION`";
  cartElements["~$k`"]["NAME"]="~$v.NAME`";
  ~/foreach`
  var mainMem="~$mainMem`"
  var cartMainMemDuration="~$cartMainMemDuration`"
  var cartMainMemPrice="~$cartMainMemPrice`"
  var cartMainMemIcon="~$cartMainMemIcon`"
  var subMem="~$mainSubMemId`"
  var landingFreebie="~$landingFreebie`"
  var landingVAS="~$landingVAS`";
  var freebieSum="~$freebieSum`";
  var discountType="~$discountType`";
  var matriPrice="~$matriPrice`";
  ~foreach from=$allDiscounts key=k item=v`
  allDiscounts["~$k`"]="~$v`";
  ~/foreach`
  ~if $festiveDiscountArr`
  allDiscounts['FESTIVE'] = new Array();
  ~foreach from=$festiveDiscountArr key=k item=v`
  allDiscounts["FESTIVE"]["~$k`"]="~$v`";
  ~/foreach`
  ~/if`
  var fest="~$fest`";
  var specialActive="~$specialActive`"
  var discountActive="~$discountActive`"
  ~foreach from=$festDurBanner key=k item=v`
  festDurBanner["~$k`"] = new Array();
  ~foreach from=$v key=kk item=vv`
  festDurBanner["~$k`"]["~$kk`"]="~$vv`";
  ~/foreach`
  ~/foreach`
</script>
<!--Main container starts here-->
<div id="main_cont">
  <div class="sp10"></div>
  <div class="sp10"></div>
  <div class="sp10"></div>
  <div class="sp5"></div>
  <div class="mem-tabs pos-rel">
    <div class="sp10 clear"></div>
    <div class="f17 pos-rel">
      <div>
        ~if $userObj->userType eq 5 or $userObj->userType eq 6 or $userObj->userType eq 7`
        ~assign var=countOnlyVAS value=0`
        ~foreach from=$subStatus item=v`
        ~if $v.LINK eq 'B' or $v.LINK eq 'Y' && $userObj->userType neq 7`
        Your subscription of ~$v.SERVICE` expires on ~$v.EXPIRY_DT`
        ~else if $v.LINK eq 'N' && $userObj->userType eq 7 && $countOnlyVAS eq '0'`
        Your subscription of ~$v.SERVICE` expires on ~$v.EXPIRY_DT`
        ~assign var=countOnlyVAS value=$countOnlyVAS+1`
        ~/if`
        ~/foreach`
      </div>
      <div style="padding: 2px 0px 5px;">
        <a id="subscriptions" style="text-decoration:underline;cursor:pointer;color:#0f7eab">See all your subscriptions</a>
      </div>
      ~else if $subStatus && $userObj->userType neq 1 && $userObj->userType neq 2`
      ~assign var=countCheck value=0`
      ~foreach from=$subStatus item=v`
      ~if $v.LINK eq 'N' && $userObj->userType neq 7 && $countCheck eq '0'`
      Your subscription of ~$v.SERVICE` expires on ~$v.EXPIRY_DT`
      ~assign var=countCheck value=$countCheck+1`
      ~/if`
      ~/foreach`
    </div>
    <div style="padding: 2px 0px 5px;">
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
                                  <div id="closeSubscription" class="btn-close fl">X</div>
                                </div>
                              </div>
                            </div>
                            ~if $bannerDisplay eq '1'`
                            ~if $specialActive eq '1'`
                            <!--Discount offer-->
                            <div class="mem-holi2 fl" style="margin-top:5px;">
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
                                  Renew before
                                  <font class="maroon" style=" font-size:30px">~$userObj->expiryDate`</font> and get <span > <font class="maroon" style=" font-size:30px">~$renewalPercent`% OFF
                                  <font class="fs24 black">on all plans</font></font> </span>
                                </div>
                              </div>
                            </div>
                            ~else`
                            <div class="mem-holi2 fl">
                              <div class="fl" style="margin-left:173px; margin-top:29px">
                                <div class="fl fs22" style="width:280px">
                                  <p>Renew before</p>
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
                              <div class="mem-holi2 fl" style="margin-top:5px;">
                                <div class="fl" style="margin-left:173px; width:auto; margin-top:36px">
                                  <div class="  fs24">
                                    Buy before
                                    <font class="maroon" style=" font-size:30px">~$discount_expiry`</font> and get ~$cashDiscountDisplayText` <span > <font class="maroon" style=" font-size:30px">~$discountPercent`%
                                    <font class="fs24 black">discount</font></font> </span>
                                  </div>
                                </div>
                              </div>
                              ~else if $fest eq '1'`
    <!--  <div style="padding:15px 0px">
            <img src='/~$festBanner`'/>
          </div> -->
          ~/if`
          ~/if`
          <ul class="tabs ">
            <li ><a class="carryForms" id="chooseMembershipTab" goTo="membershipTab" style="cursor:pointer;height:24px;">Choose Membership Plan</a></li>
            <li><a class="carryForms active" href="#tab2">Choose Additional Services</a></li>
            <li><a class="carryForms" id="choosePaymentTab" goTo="paymentTab" style="cursor:pointer;height:24px;">Payment Options</a></li>
          </ul>
          <input type="hidden" name="fromVAS" id="fromVAS" value="1"/>
          <!-- TAB 2 CONTENT STARTS -->
          <div style="padding:20px; width:890px; float:left;" id="tab2">
            <div class="center fs14 pad-new color-new fullwidth b">
              Know More how Additional Services help you - Call us at <span class="color-new1">~if $currency eq 'DOL'`+911204393500~else`1800-419-6299 (Toll Free)~/if`</span> or just
              <a style="cursor:pointer;" class="color-blue" id="excCallNew" onClick="execCallback('2','JS_ALL');">Request a Call Back from us</a>.
              <div class="pos-rel">
                ~include_partial('global/membershipAlertLayout')`
              </div>
            </div>
            <div class="clr"></div>
            <div  class="mem-tab2-leftcon pos-rel" style="width:640px;margin-right:10px;">
              <div id="lightbox" class="dialog" style="display:none;" >
                <i class="ico-indicator sprte-mem fl dialog-ico-indicator">  &nbsp;</i>
                <div class="container fl">
                  <div id="lightboxData" class="fl">
                    <div><input type="radio" class="widthauto" />12 months for Rs. 1495</div>
                    <div><input type="radio" class="widthauto" />9 months for Rs. 1295</div>
                    <div><input type="radio" class="widthauto" />6 months for Rs. 895</div>
                    <div><input type="radio" class="widthauto" />4 months for Rs. 595</div>
                    <div><input type="radio" class="widthauto"/>2 months for Rs. 295</div>
                  </div>
                  <div class="btn-close fl" id="greyBoxClose">X</div>
                </div>
              </div>
              <div id="top3" >
                <div id="Taddon" class="tuples mar5left mar5right" style="position:relative;width:202px;">
                  <div><input type="checkbox" id="Tcheck" value="Response Booster" class="vam chbx fl " selectedId="" /> <div class="fl mar2left" ><font class="fs16"> Response Booster</font>
                    <div class="sp5">&nbsp;</div>
                    <div id="TSelectedCheckValue"><span class="TPrice">
                      ~foreach from=$vaMembership.T item=row name=loop`
                      ~if $smarty.foreach.loop.last`<strong>Starts @ ~if $currency eq 'DOL'`$~else`Rs. ~/if`~$row.PRICE` </strong><input type="hidden" id="TstartPrice" value=~$row.PRICE` />~/if`
                      ~/foreach`
                    </span>
                    <a  class="b selectLightbox" style="cursor:pointer;" id="TSelOrChange">Select Plan</a></div>
                  </div></div>
                  <div class="ico-image-1 fl sprte-mem">&nbsp;</div><br><br><br>
                  <div>Busy? Don't worry!
                    Allow Jeevansathi.com to contact
                    people who match your criteria
                    and get 8 times more response.</div>
                  </div>
                  <div id="Raddon" class="tuples mar5left mar5right" style="width:202px;">
                    <div>
                      <input type="checkbox" class="vam chbx fl " id="Rcheck" value="Featured Profile" selectedId="" />
                      <div class="fl mar2left" ><font class="fs16"> Featured Profile</font>
                        <div class="sp5">&nbsp;</div>
                        <div id="RSelectedCheckValue">
                          <span class="RPrice">
                            ~foreach from=$vaMembership.R item=row name=loop`
                            ~if $smarty.foreach.loop.last`<strong>Starts @ ~if $currency eq 'DOL'`$~else`Rs. ~/if`~$row.PRICE` </strong>~/if`
                            ~/foreach`</span>
                            <a class="b selectLightbox" id="RSelOrChange" style="cursor:pointer">Select Plan</a></div><div id="RLightbox">          </div>
                          </div>
                        </div>
                        <div class="ico-image-2 fl sprte-mem">&nbsp;</div>
                        <br><br><br>
                        <div style="position:relative">Want to grab your partner's attention?
                          Feature in a special section on top
                          of all relevant searches.
                          <br/><a class="b Sample" id="R" style="cursor:pointer">View Sample</a>
                          <div style="position: absolute; background-color: rgb(255, 255, 255); z-index: 10001; top: -86px; left: 97px;" id="RSample" class="sampleImage">
                            <div style="border: 5px solid rgb(155, 154, 154); width: 408px; height: 571px;">
                              <div style="border:2px solid #75C42B;">
                                <div class="sample-close" style="border: 1px solid rgb(117, 196, 56); color: rgb(117, 196, 56); cursor: pointer; font-size: 13px; font-weight: bold; height: 15px; position: absolute; text-align: center; width: 15px; top: 9px; right: 10px;">X</div>
                                <i class="ico-indicator sprte-mem fl" style="top: 230.667px; left:-13px">   </i>
                                <img border="0" src="/css/sample-featured.jpg">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div id="Iaddon" class="tuples mar5left mar5right" style="width:202px;">
                        <div><input type="checkbox" id="Icheck" class="vam chbx fl " value="We Talk For You" selectedId=""  /> <div class="fl mar2left" ><font class="fs16"> We Talk For You</font>
                          <div class="sp5">&nbsp;</div>
                          <div id="ISelecetedCheckValue"><span class="IPrice">
                            ~foreach from=$vaMembership.I item=row name=loop`
                            ~if $smarty.foreach.loop.last`<strong>Starts @ ~if $currency eq 'DOL'`$~else`Rs. ~/if`~$row.PRICE` </strong>~/if`
                            ~/foreach`</span>
                            <a class="b selectLightbox" id="ISelOrChange" style="cursor:pointer">Select Plan</a></div>
                          </div></div>
                          <div class="ico-image-3 fl sprte-mem">&nbsp;</div><br><br><br>
                          <div>Busy? Personalized service where
                            Jeevansathi executive will speak to
                            profiles you like.</div>
                          </div>
                        </div>
                        <span id="spanmore" style="display:none">
                          <div id="Aaddon" class="tuples mar5left mar5right" style="position:relative;width:202px;">
                            <div><input type="checkbox" id="Acheck" class="vam chbx fl " value="Astro Compatibility" selectedId="" /> <div class="fl mar2left" ><font class="fs16"> Astro Compatibility</font>
                              <div class="sp5">&nbsp;</div>
                              <div id="ASelectedCheckValue"><span class="APrice">
                                ~foreach from=$vaMembership.A item=row name=loop`
                                ~if $smarty.foreach.loop.last`<strong>Starts @ ~if $currency eq 'DOL'`$~else`Rs. ~/if`~$row.PRICE` </strong>~/if`
                                ~/foreach` </span>
                                <a class="b selectLightbox" id="ASelOrChange" style="cursor:pointer">Select Plan</a></div><div id="ALightbox"></div>
                              </div></div>
                              <div class="ico-image-4 fl sprte-mem">&nbsp;</div><br><br><br>
                              <div style="position:relative">Horoscope match a must?
                                Get detailed kundli matching reports
                                with profiles you like. <br/><a class="b Sample" id="A" style="cursor:pointer">View Sample</a>
                                <div style="position: absolute; background-color: rgb(255, 255, 255); z-index: 10001; top: -103px; left: 96px;" id="ASample" class="sampleImage">
                                  <div style="border: 5px solid rgb(155, 154, 154); width: 408px; height: 551px;">
                                    <div style="border:2px solid #75C42B;">
                                      <div class="sample-close" style="border: 1px solid rgb(117, 196, 56); color: rgb(117, 196, 56); cursor: pointer; font-size: 13px; font-weight: bold; height: 15px; position: absolute; text-align: center; width: 15px; top: 9px; right: 10px;">X</div>
                                      <i class="ico-indicator sprte-mem fl" style="top: 230.667px; left:-13px">   </i>
                                      <img border="0" src="/css/sample-astro.jpg">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div id="Baddon" class="tuples mar5left mar5right" style="width:202px;">
                              <div><input type="checkbox" id="Bcheck" class="vam chbx fl " value="Profile Highlighting" selectedId="" /> <div class="fl mar2left" ><font class="fs16"> Profile Highlighting</font>
                                <div class="sp5">&nbsp;</div>
                                <div id="BSelectedCheckValue"><span class="BPrice">
                                  ~foreach from=$vaMembership.B item=row name=loop`
                                  ~if $smarty.foreach.loop.last`<strong>Starts @ ~if $currency eq 'DOL'`$~else`Rs. ~/if`~$row.PRICE` </strong>~/if`
                                  ~/foreach` </span>
                                  <a class="b selectLightbox" id="BSelOrChange" style="cursor:pointer">Select Plan</a></div>
                                </div></div>
                                <div class="ico-image-5 fl sprte-mem">&nbsp;</div><br><br><br>
                                <div style="position:relative">Want to stand out? Highlight your
                                  profile in different color and get 3
                                  times higher response.  <br />
                                  <a class="b Sample" id="B" style="cursor:pointer">View Sample</a>
                                  <div style="position: absolute; background-color: rgb(255, 255, 255); z-index: 10001; top: -100px; left: 93px;" id="BSample" class="sampleImage">
                                    <div style="border: 5px solid rgb(155, 154, 154); width: 408px; height: 519px;">
                                      <div style="border:2px solid #75C42B;">
                                        <div class="sample-close" style="border: 1px solid rgb(117, 196, 56); color: rgb(117, 196, 56); cursor: pointer; font-size: 13px; font-weight: bold; height: 15px; position: absolute; text-align: center; width: 15px; top: 9px; right: 10px;">X</div>
                                        <i class="ico-indicator sprte-mem fl" style="top: 230.667px; left:-13px">   </i>
                                        <img border="0" src="/css/sample-highlighted.jpg">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div id="Maddon" class="tuples mar5left mar5right" style="width:202px;">
                                <div><input type="checkbox" id="Mcheck" class="vam chbx fl " value="Matri Profile" selectedId="" /> <div class="fl mar2left" ><font class="fs16"> Matri Profile</font>
                                  <div class="sp5">&nbsp;</div>
                                  <div id="MSelectedCheckValue"><span class="MPrice">
                                    <strong>For ~if $currency eq 'DOL'`$~$matriPrice` ~else`Rs. ~$matriPrice`~/if`  </strong></span>
                                    <!--<a class="b selectLightbox" id="MSelOrChange" style="cursor:pointer">Select Plan</a>--></div>
                                  </div></div>
                                  <div class="ico-image-6 fl sprte-mem">&nbsp;</div><br><br><br>
                                  <div class="fl">Express Yourself! Get our experts to
                                    create a comprehensive well-written
                                    profile for you. </div>
                                  </div>
    <!-- Profile Home Delivery Stopped
    <div id="Laddon" class="tuples mar5left mar5right" style="width:202px;">
        <div>
            <input type="checkbox" id="Lcheck" class="vam chbx fl " value="Profile Home Delivery" selectedId="" />
            <div class="fl mar2left" ><font class="fs16"> Profile Home Delivery</font>
                    <div class="sp5">&nbsp;</div>
                    <div id="LSelectedCheckValue" ><span class="LPrice">
                        ~foreach from=$vaMembership.L item=row name=loop`
                        ~if $smarty.foreach.loop.last`<strong>Starts @ ~if $currency eq 'DOL'`$~else`Rs. ~/if`~$row.PRICE` </strong>~/if`
                        ~/foreach`</span>
                        <a class="b selectLightbox" id="LSelOrChange" style="cursor:pointer;position:relative;">Select Plan
                        </a>
                </div>
            </div>
        </div>
            <div class="ico-image-7 fl sprte-mem">&nbsp;</div><br><br><br>
            <div>Want to show profiles to your parents? Jeevansathi will send print-copies of profiles interested in you to your parents' house.</div>
    </div>
  -->
</span>
<div class="mem-show center" style="display:none;"> <a class="b fs16" onclick="showmore()" style="color:#3490b6;cursor:pointer;" id="showlink">Show more</a></div>
</div>
<div class="fl w238">
  <div id="mem-tab2-rightcon">
    <div class="summary fs16 b sprte-mem pos-rel"> Review your Order
      <div class="mem-ico-grey-down sprte-mem">&nbsp;</div>
    </div>
    <div id="cartElements" class="cont-sum" style="min-height:143px;">
      <div id='noService'>&nbsp;&nbsp;&nbsp;<b>No service selected</b></div>
    </div>
    ~if $freebieSum or $specialActive or $discountActive or $festBanner or $discountType eq 'RENEWAL'`
    <div id="cartDiscount" class="cont-sum" style="color:#e15404;font-weight:bold;padding:15px 15px 8px;"><div class='fl' style="margin-left:10px;width:100px;border-top:none;font-size:18px;padding:0px;">Discount</div><div class='fl' id='discountValue' style="width:90px;text-align:right;border-top:none;font-size:18px;padding:0px;" >-&nbsp;~$freebieSum`</div></div>
    ~/if`
    <div class="tot "><div class="div-left fs20 " style="margin-left:16px;width:87px;">Total</div><div id='totalCartValue' style="width:109px;text-align:right;" class="fl fs20">~if $currency eq 'DOL'`$~else`₹~/if`~$totalCartValue`</div> </div>
  </div>
  <div class="fs11 mar10top fr" style="color:#444;width:225px;margin-left:13px;" align="left" >All Prices are in ~$currencyType` inclusive of ~$tax_rate`% service tax (including Swachh Bharat Cess).</div>
  <div class="clr"></div>
  <!-- start:continue button -->
  <div class="center mt_20"><form action="/membership/paymentOptions" id="carryCartForm" method="POST" target="_top">
    <div id="carryCart" style="display:none"></div>
    <input type='hidden' id='mainSubMemId' name='mainSubMemId' value='~$mainSubMemId`'></input>
    <input type='hidden' id="allMemberships" name='allMemberships' value=''></input>
    <input type="hidden" name="selMembrshpToPayment" id="selMembrshpToPayment" value="~$selMembrshp`"/>
    <input type="hidden" name="navigationStringToPayment" value=""/>
    <input type="hidden" name="selectedStringToPayment" value=""/>
    <input type="hidden" name="VASImpressionToPayment" value=""/>
    <input type="hidden" name="showAllToPayment" value=""/>
    <!--<input type="button" id="continueToPay" goTo="paymentTab" class="carryForms mem-btn-green sprte-mem widthauto fl" value="Continue" style="margin-top:0px;cursor:pointer" />-->
    <div> <button style="margin-top:0px;font-size:20px;" class="carryForms cont-btn-green" id="continueToPay" goTo="paymentTab">Continue</button></div>
  </form></div>
  <!-- end:continue button -->
</div>
<div class="fl w392" style="margin-left:238px;margin-top:25px; display:none;">
  <div>
    <div><form action="/membership/membershipMaster" id="backToMembership" name="backToMembership" method="POST" target="_top"> <i style="margin-top:12px;" class="sprte-mem mem-ico-blue-arrw fl"></i>
      <input type="hidden" name="backSubId" value="~$mainSubMemId`">
      <input type="hidden" name="activeTable" id="activeTable" value="~$activeTable`">
      <input type="hidden" name="selMembrshp" id="selMembrshp" value="~$selMembrshp`"/>
      <input type="hidden" name="fromVAS" id="fromVAS" value="1"/>
      <input type="hidden" name="jsSel" id="jsSel" value="~$jsSel`"/>
      <input type="hidden" name="navigationString" value=""/>
      <input type="hidden" name="selectedString" value=""/>
      <input type="hidden" name="VASImpression" value=""/>
      <input type="hidden" name="showAll" value=""/>
      <a id="valueAddedBackButton" goTo="membershipTab" value="Back" class="carryForms mar10right fs20 fl" style="margin-top:14px;cursor:pointer;color:#0484AE;">Back</a></form></div>
    </div>
  </div>
</div>
<!-- TAB 2 CONTENT FINISH -->
</div>
<div class="sp10"></div>
<div class="sp5"></div>
<div class="sp15"></div>
<div class="sp15"></div>
<div class="sp15"></div>
<div class="sp10"></div>
<div class="sp15"></div>
<div class="sp15"></div>
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
      $("#tab2").show();
      var username = "~$userDetails.USERNAME`";
      var email = "~$userDetails.EMAIL`";
      setInterval(function(){
        autoPopulateFreshdeskDetails(username,email);
      },100);
      setTimeout(function(){
        autoPopupFreshdesk(username,email);
      }, 10000);
    });
</script>
