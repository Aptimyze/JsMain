<!-- Header Starts -->
<!-- Header ends -->

~Messages::setViewerChecksum($userChecksum)`
<div class="existing1 fbld">
~if $showBackLink eq 1`
<a href="~$refererUrl`" class="fs14 flt">&lt;&lt;&nbsp;Back</a>
~/if`
<a href="~sfConfig::get('app_site_url')`/P/logout.php?mobile_logout=1" class="frt fs12" style="padding-right: 5px;">Logout</a>
<div class="clr"></div>
</div>

<div class="quickMob fs16">
<img alt="Free Trial Offer" src="~sfConfig::get('app_img_url')`/images/contactImages/mobilejs/fto-heading.jpg" style="margin: 0 auto; display: block;">
</div>

<div class="quickMob">
<fieldset class="fto-fldset">
<legend class="fs14 fto-legend">See Phone/Email for
FREE</legend>
<div class="content-holder">
<div class="mt10">
<div class="mt10">
Phone/Email locked
</div>
<div class="light-grey-box" style="color:#000; font-size:11px; padding-left:20px">
<div>
<span style="float:left; width:42px">Phone</span> :
<img alt="Phone" src="~sfConfig::get('app_img_url')`/images/contactImages/mobilejs/phone-blur-grey.jpg">
<img src="~sfConfig::get('app_img_url')`/images/contactImages/mobilejs/lock.jpg" alt="Locked">
</div>
<div>
<span style="float:left; width:42px">Email</span>
:<img alt="Email" src="~sfConfig::get('app_img_url')`/images/contactImages/mobilejs/email-blur-grey.jpg">
<img src="~sfConfig::get('app_img_url')`/images/contactImages/mobilejs/lock.jpg" alt="Locked">
</div>
</div>
<div class="mt10">
&nbsp;
</div>
<div class="fs14 take-fto">
Take Free Trial Offer
</div>
<div class="down-arrow">
<img alt="down-arrow" src="~sfConfig::get('app_img_url')`/images/contactImages/mobilejs/arrow-mar-down.jpg">
</div>
<div class="mt10">
Phone/Email gets Unlocked*
</div>
<div class="light-grey-box" style="color:#000; font-size:11px; padding-left:20px">
<div>
<span style="float:left; width:42px">Phone</span> :
9560885799
</div>
<div>
<span style="float:left; width:42px">Email</span> :
kiran210981@yahoo.com
</div>
</div>
<div class="frt" style="font-size:11px">
Note: These are dummy details
</div>
</div>
</div>
</fieldset>
</div>

<div class="clr"></div>

<div class="quickMob">
<fieldset class="fto-fldset">
<legend class="fs14 fto-legend">To take Free Trial
Offer</legend>
~if $subState eq 'C1'`
<div class="content-holder">
<div class="mt10">
<div class="mt10">
Send your photos to
</div>
<div class="fs14 mt10">

<strong>
<a href="mailto:photos@jeevansathi.com?subject=~$loginUsername` : My Photos" style="text-decoration:underline">photos@jeevansathi.com</a>
</strong>
</div>
<div class="mt10 fs14">
&amp;
</div>
<div class="mt10 fs14">
<strong>
<a href="~Messages::getKnowlarityNumber()`" style="text-decoration:underline">Verify your phone</a>
</strong>
</div>
</div>
</div>
~elseif $subState eq 'C2'`
<div class="content-holder">
<div class="mt10">
<div class="mt10">
Send your photos to
</div>
<div class="fs14 mt10">

<strong>
<a href="mailto:photos@jeevansathi.com?subject=~$loginUsername` : My Photos" style="text-decoration:underline">photos@jeevansathi.com</a>
</strong>
</div>
</div>
</div>
~elseif $subState eq 'C3'`
<div class="content-holder">
<div class="mt10">
<div class="mt10 fs14">
<strong>
  <a href="~Messages::getKnowlarityNumber()`" style="text-decoration:underline">Verify your phone</a>
</strong>
</div>
</div>
</div>
~/if`
</fieldset>
<div class="frt" style="font-size:11px">
* For complete details see <a href="~$SITE_URL`/P/disclaimer.php">terms and
conditions</a>
</div>
</div>
<div class="clr"></div>

<div class="quickMob" style="text-align: center;">
~if $subState eq 'C1'`
Send your photo &amp; Verify your phone
~elseif $subState eq 'C2'`
Send your photo
~elseif $subState eq 'C3'`
Verify your phone
~/if`
before
<strong style=" color:#b00800;">~$day`<sup>~$superscript`</sup>&nbsp;~$month`&nbsp;~$year`</strong> to get Free Trial
Offer
</div>

<div class="quickMob fs14" style="text-align: center;">
or call us on <a href="tel:18004196299">1 - 800 - 419 - 6299</a>
</div>
~include_partial("register/reg_tracking",['groupname'=>$sf_request->getParameter('groupname'),'pixelcode'=>$pixelcode])`
