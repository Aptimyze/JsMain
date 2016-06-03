<div class="hpoverlay z4 disp-none"></div>
<!--end:overlaye layer--> 
<!--start:login layer-->
<div id="login-layer" class="z5 pos_fix disp-none"> 
  <!-- start:close button--> 
  <i id="cls-login" class="sprite2 close pos_fix closepos cursp"></i> 
  <!-- end:close button--> 
  <!--start:login layer-->
  <div class="pos_fix fullwid fontlig" style="top:10%">
   	<div class="mauto layerbg wid520">
    	<div class="layerp3">
        	<div class="f17 grey5">Login to continue..</div>
            <div class="mt20">
            	<form id="homePageLogin" method="post" target="iframe_login" onsubmit="return LoginValidation()">
                	<div id="EmailContainer" class="clearfix wid92p brderinp layerp2 ">
                       <input type="text" class="bgnone f15 grey6 brdr-0 fl wid64p" placeholder="Email ID" value="" id="homeLoginEmail" name="homeLoginEmail">
                       <span id="emailErr" class="errcolr fr disp-none"></span>
                    </div>
                    <div id="PasswordContainer" class="clearfix wid92p brderinp layerp2 mt10 ">
                       <input type="text" class="bgnone f15 grey6 brdr-0 fl wid64p" placeholder="Password" value="" id="homeLoginPassword" name="homeLoginPassword">
                       <span id="passwordErr" class="errcolr fr disp-none"></span>
                    </div>
                    <div class="clearfix mt20">
                    	<div class="fl">
                        	<div class="wid300 clearfix">
                            	<div class="f15 grey5 fl pt3 pr10">Rememeber me</div>
                                <div class="fl">
                                  <div class="checkboxThree">
                                    <input type="checkbox" value="1" id="homeLoginRemember" name="homeLoginRemember" />
                                    <div class="pos-abs" style="top: 6px; left: 4px;">
                                      <div class="fl colrw f9 cursp remY" style="width:22px" >Yes</div>
                                      <div class="fl colrw f9 cursp remN" style="width:17px" >No</div>
                                    </div>
                                    <div id="selopt" class="pos-abs posselecrem">
                                      <div class="whitecir"></div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="fr">
                        	<a href="#" class="grey5 f15">Forgot Password</a>
                        </div>
                    </div>
                    <div class="mt15">
        				<button class="fullwid bg5 lh63 txtc f18 fontlig colrw brdr-0">LOGIN</button>
        			</div>                
                </form>
            </div>
        </div>
    	<div class="brdt1 layerp3">
        	<p class="txtc f17 fontlig grey5">New on Jeevansathi?</p>
             <div class="mt15">
        				<button class="fullwid bg_pink lh63 txtc f18 fontlig colrw brdr-0 allcaps">Register free</button>
        	</div>  
        </div>
    </div>
  </div>
  <!--end:login layer--> 
</div>
<div id="Hidden_iFrame">
	<iframe id="iframe_login"  style="display:none"  name="iframe_login">
	</iframe>
</div>
<!--end:login layer--> 
<!--start:header-->

<header>
  <div class="hp-header pos-rel scrollhid"> 
    <div class="container mainwid">
      <div> 
        <!--start:top nav-->
        <div class="pos_fix mainwid z2 js-topnav">
          <div class="fullwid clearfix"> 
            <!--start:logo-->
            <div class="fl hpwid1 lh63 logowhite txtc disp-tbl"> <a href="#" class="disp-cell vmid"> <img src="/images/jspc/commonimg/logo1.png" alt="Indian Matrimonials - We Match Better" class="brdr-0 vmid"> </a> </div>
            <!--end:logo--> 
            <!--start:registration-->
            <div class="fr hpbg1 hpwid3 lh63 txtc disp-tbl"> <a href="/profile/registration_pg1.php?source=hp_head" class="disp-cell vmid fontreg f14 colrw">REGISTER FREE</a> </div>
            <!--end:registration--> 
            <!--start:middle-->
            <div class="fl ml2 hpblue1 hpwid2 colrw f14">
              <div class="fullwid clearfix fontreg">
                <div class="padallp"> 
                  <!--start:browse matches-->
                  <div class="fl pt22 pos-rel">
                    <div class="cursp f14 colrw pb23" id="BrowseTab"> Browse Matches By <i class="sprite2 arowsmall"></i> </div>
                    <!--start:hover box-->
                    <div id="BrowseTab_content" class="TabsContent coloropa1 wid661">
                      <div class="InneerTabContent">
                        <div class="TabsMenu fl coloropa2 fontreg"> <a href="#" id="mtongue" class="sub_h">Mother tongue</a> <a href="#" id="caste" class="sub_h">Caste</a> <a href="#" id="religion" class="sub_h">Religion</a> <a href="#" id="city" class="sub_h">City</a> <a href="#" id="occupation" class="sub_h">Occupation</a> <a href="#" id="state" class="sub_h">State</a> <a href="#" id="nri" class="sub_h">NRI</a> <a href="#" id="scases" class="sub_h">Special Cases</a> </div>
                        <div class="BrowseContent fl">
                          <figure class="mtongue_h" style="display: block;">
                            <figcaption>
                              <div class="fullwidth clearfix pl10">
                                <div class="fl wid150">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/bihari-matrimonial/" title="Bihari Matrimony">Bihari</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/bengali-matrimonial/" title="Bengali Matrimony">Bengali</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/hindi-matrimonial/" title="Hindi Matrimony">Hindi</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/gujarati-matrimonial/" title="Gujarati Matrimony">Gujarati</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/kannada-matrimonial/" title="Kannada Matrimony">Kannada</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/malayalee-matrimonial/" title="Malayalam Matrimony">Malayalam</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/marathi-matrimonial/" title="Marathi Matrimony">Marathi</a></li>
                                  </ul>
                                </div>
                                <div class="fl wid150">
                                  <ul class="mrg1">
                                    <li><a href="http://www.jeevansathi.com/matrimonials/oriya-matrimonial/" title="Oriya Matrimony">Oriya</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/punjabi-matrimonial/" title="Punjabi Matrimony">Punjabi</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/rajasthani-matrimonial/" title="Rajasthani Matrimony">Rajasthani</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/tamil-matrimonial/" title="Tamil Matrimony">Tamil</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/telugu-matrimonial/" title="Telugu Matrimony">Telugu</a></li>
                                    <li><a href="http://www.jeevansathi.com/hindi-up-matrimony-matrimonials" title="Hindi UP Matrimony">Hindi-UP</a></li>
                                    <li><a href="http://www.jeevansathi.com/hindi-mp-matrimony-matrimonials" title="Hindi MP Matrimony">Hindi-MP</a></li>
                                  </ul>
                                </div>
                                <div class="fl wid150">
                                  <ul class="mrlr">
                                    <li><a href="http://www.jeevansathi.com/konkani-matrimony-matrimonials" title="Konkani Matrimony">Konkani</a></li>
                                    <li><a href="http://www.jeevansathi.com/himachali-matrimony-matrimonials" title="Himachali Matrimony">Himachali</a></li>
                                    <li><a href="http://www.jeevansathi.com/haryanvi-matrimony-matrimonials" title="Haryanvi Matrimony">Haryanvi</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/assamese-matrimonial/" title="Assamese Matrimony">Assamese</a></li>
                                    <li><a href="http://www.jeevansathi.com/kashmiri-matrimony-matrimonials" title="Kashmiri Matrimony">Kashmiri</a></li>
                                    <li><a href="http://www.jeevansathi.com/sikkim-nepali-matrimony-matrimonials" title="Sikkim Nepali Matrimony">Sikkim/Nepali</a></li>
                                  </ul>
                                </div>
                              </div>
                            </figcaption>
                          </figure>
                          <figure class="caste_h" style="display: none;">
                            <figcaption>
                              <div class="fullwidth clearfix pl10 fontRobReg">
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/agarwal-matrimonial/" title="Aggarwal Matrimony">Aggarwal</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/brahmin-matrimonial/" title="Brahmin Matrimony">Brahmin</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/iyer-matrimonial/" title="Brahmin Iyer Matrimony">Brahmin Iyer</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/catholic-matrimonial/" title="Catholic Matrimony">Catholic</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/jat-matrimonial/" title="Jat Matrimony">Jat</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/kayastha-matrimonial/" title="Kayastha Matrimony">Kayastha</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/khatri-matrimonial/" title="Khatri Matrimony">Khatri</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/kshatriya-matrimonial/" title="Kshatriya Matrimony">Kshatriya</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/lingayat-matrimonial/" title="Lingayat Matrimony">Lingayat</a></li>
                                    <li><a href="http://www.jeevansathi.com/maratha-matrimony-matrimonials" title="Maratha Matrimony">Maratha</a></li>
                                  </ul>
                                </div>
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/nair-matrimonial/" title="Nair Matrimony">Nair</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/rajput-matrimonial/" title="Rajput Matrimony">Rajput</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/sindhi-matrimonial/" title="Sindhi Matrimony">Sindhi</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/sunni-matrimonial/" title="Sunni Matrimony">Sunni</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/arora-matrimonials/" title="Arora Matrimony">Arora</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/shwetamber-matrimonial/" title="Shwetamber Matrimony">Shwetamber</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/yadav-matrimonial/" title="Yadav Matrimony">Yadav</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/bania-matrimonial/" title="Bania Matrimony">Bania</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/scheduled-caste-matrimonial/" title="Scheduled Caste Matrimony">Scheduled Caste</a></li>
                                  </ul>
                                </div>
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/roman-catholic-matrimony-matrimonials" title="Catholic Roman Matrimony">Catholic - Roman</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/patel-matrimonial/" title="Patel Matrimony">Patel</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/digamber-matrimonial/" title="Digamber Matrimony">Digamber</a></li>
                                    <li><a href="http://www.jeevansathi.com/sikh-jat-matrimony-matrimonials" title="Sikh Jat Matrimony">Sikh-Jat</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/gupta-matrimonial/" title="Gupta Matrimony">Gupta</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/teli-matrimonial/" title="Teli Matrimony">Teli</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/vishwakarma-matrimonial/" title="Vishwakarma Matrimony">Vishwakarma</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/vaishnav-matrimonial/" title="Vaishnav Matrimony">Vaishnav</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/jaiswal-matrimonial/" title="Jaiswal Matrimony">Jaiswal</a></li>
                                  </ul>
                                </div>
                              </div>
                            </figcaption>
                          </figure>
                          <figure class="religion_h" style="display: none;">
                            <figcaption>
                              <div class="fullwidth clearfix pl10 fontRobReg">
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/hindu-matrimonial/" title="Hindu Matrimony">Hindu</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/muslim-matrimonial/" title="Muslim Matrimony">Muslim</a></li>
                                  </ul>
                                </div>
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/christian-matrimonial/" title="Christian Matrimony">Christian</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/sikh-matrimonial/" title="Sikh Matrimony">Sikh</a></li>
                                  </ul>
                                </div>
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/buddhist-matrimonial/" title="Buddhist Matrimony">Buddhist</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/jain-matrimonial/" title="Jain Matrimony">Jain</a></li>
                                  </ul>
                                </div>
                              </div>
                            </figcaption>
                          </figure>
                          <figure class="city_h" style="display: none;">
                            <figcaption>
                              <div class="fullwidth clearfix pl10 fontRobReg">
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/delhi-matrimonials/" title="New Delhi Matrimony">New Delhi</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/mumbai-matrimonial/" title="Mumbai Matrimony">Mumbai</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/kolkata-matrimonial/" title="Kolkata Matrimony">Kolkata</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/chennai-matrimonial/" title="Chennai Matrimony">Chennai</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/bangalore-matrimonial/" title="Bangalore Matrimony">Bangalore</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/pune-matrimonial/" title="Pune Matrimony">Pune</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/ahmedabad-matrimonial/" title="Ahmedabad Matrimony">Ahmedabad</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/hyderabad-matrimonial/" title="Hyderabad Matrimony">Hyderabad</a></li>
                                  </ul>
                                </div>
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/lucknow-matrimony-matrimonials" title="Lucknow Matrimony">Lucknow</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/chandigarh-matrimonial/" title="Chandigarh Matrimony">Chandigarh</a></li>
                                    <li><a href="http://www.jeevansathi.com/nagpur-matrimony-matrimonials" title="Nagpur Matrimony">Nagpur</a></li>
                                    <li><a href="http://www.jeevansathi.com/jaipur-matrimony-matrimonials" title="Jaipur Matrimony">Jaipur</a></li>
                                    <li><a href="http://www.jeevansathi.com/noida-matrimony-matrimonials" title="Noida Matrimony">Noida</a></li>
                                    <li><a href="http://www.jeevansathi.com/indore-matrimony-matrimonials" title="Indore Matrimony">Indore</a></li>
                                    <li><a href="http://www.jeevansathi.com/gurgaon-matrimony-matrimonials" title="Gurgaon Matrimony">Gurgaon</a></li>
                                    <li><a href="http://www.jeevansathi.com/patna-matrimony-matrimonials" title="Patna Matrimony">Patna</a></li>
                                  </ul>
                                </div>
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/bhubaneshwar-matrimony-matrimonials" title="Bhubaneshwar Matrimony">Bhubaneshwar</a></li>
                                    <li><a href="http://www.jeevansathi.com/ghaziabad-matrimony-matrimonials" title="Ghaziabad Matrimony">Ghaziabad</a></li>
                                    <li><a href="http://www.jeevansathi.com/kanpur-matrimony-matrimonials" title="Kanpur Matrimony">Kanpur</a></li>
                                    <li><a href="http://www.jeevansathi.com/faridabad-matrimony-matrimonials" title="Faridabad Matrimony">Faridabad</a></li>
                                    <li><a href="http://www.jeevansathi.com/ludhiana-matrimony-matrimonials" title="Ludhiana Matrimony">Ludhiana</a></li>
                                    <li><a href="http://www.jeevansathi.com/thane-matrimony-matrimonials" title="Thane Matrimony">Thane</a></li>
                                  </ul>
                                </div>
                              </div>
                            </figcaption>
                          </figure>
                          <figure class="occupation_h" style="display: none;">
                            <figcaption>
                              <div class="fullwidth clearfix pl10 fontRobReg">
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/it-software-engineers-matrimony-matrimonials" title="IT Software Matrimony">IT Software</a></li>
                                    <li><a href="http://www.jeevansathi.com/teachers-matrimony-matrimonials" title="Teacher Matrimony">Teacher</a></li>
                                    <li><a href="http://www.jeevansathi.com/ca-accountant-matrimony-matrimonials" title="CA Accountant Matrimony">CA/Accountant</a></li>
                                  </ul>
                                </div>
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/businessman-matrimony-matrimonials" title="Businessman Matrimony">Businessman</a></li>
                                    <li><a href="http://www.jeevansathi.com/doctors-nurse-matrimony-matrimonials" title="Doctors Nurse Matrimony">Doctors/Nurse</a></li>
                                    <li><a href="http://www.jeevansathi.com/government-services-matrimony-matrimonials" title="Govt. Services Matrimony">Govt. Services</a></li>
                                  </ul>
                                </div>
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/lawyers-matrimony-matrimonials" title="Lawyers Matrimony">Lawyers</a></li>
                                    <li><a href="http://www.jeevansathi.com/defence-matrimony-matrimonials" title="Defence Matrimony">Defence</a></li>
                                    <li><a href="http://www.jeevansathi.com/ias-matrimony-matrimonials" title="IAS Matrimony">IAS</a></li>
                                  </ul>
                                </div>
                              </div>
                            </figcaption>
                          </figure>
                          <figure class="state_h" style="display: none;">
                            <figcaption>
                              <div class="fullwidth clearfix pl10 fontRobReg">
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/maharashtra-matrimonial/" title="Maharashtra Matrimony">Maharashtra</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/uttar-pradesh-matrimonial/" title="Uttar Pradesh Matrimony">Uttar Pradesh</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/karnataka-matrimonial/" title="Karnataka Matrimony">Karnataka</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/andhra-pradesh-matrimonial/" title="Andhra Pradesh Matrimony">Andhra Pradesh</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/tamil-nadu-matrimonial/" title="Tamil Nadu Matrimony">Tamil Nadu</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/west-bengal-matrimonials/" title="West Bengal Matrimony">West Bengal</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/madhya-pradesh-matrimonial/" title="Madhya Pradesh Matrimony">Madhya Pradesh</a></li>
                                  </ul>
                                </div>
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/gujarat-matrimonial/" title="Gujarat Matrimony">Gujarat</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/haryana-matrimonial/" title="Haryana Matrimony">Haryana</a></li>
                                    <li><a href="http://www.jeevansathi.com/bihar-matrimony-matrimonials" title="Bihar Matrimony">Bihar</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/kerala-matrimonial/" title="Kerala Matrimony">Kerala</a></li>
                                    <li><a href="http://www.jeevansathi.com/rajasthan-matrimony-matrimonials" title="Rajasthan Matrimony">Rajasthan</a></li>
                                    <li><a href="http://www.jeevansathi.com/punjab-matrimony-matrimonials" title="Punjab Matrimony">Punjab</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/orissa-matrimonial/" title="Orissa Matrimony">Orissa</a></li>
                                  </ul>
                                </div>
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/assam-matrimonial/" title="Assam Matrimony">Assam</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/jammu-kashmir-matrimonial/" title="Jammu &amp; Kashmir Matrimony">Jammu &amp; Kashmir</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/himachal-pradesh-matrimonial/" title="Himachal Pradesh Matrimony">Himachal Pradesh</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/jharkhand-matrimony-matrimonials/" title="Jharkhand Matrimony">Jharkhand</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/chhattisgarh-matrimony-matrimonials/" title="Chhattisgarh Matrimony">Chhattisgarh</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/uttarakhand-matrimony-matrimonials/" title="Uttarakhand Matrimony">Uttarakhand</a></li>
                                  </ul>
                                </div>
                              </div>
                            </figcaption>
                          </figure>
                          <figure class="nri_h" style="display: none;">
                            <figcaption>
                              <div class="fullwidth clearfix pl10 fontRobReg">
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/nri-matrimony-matrimonials" title="NRI Matrimony">NRI </a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/usa-matrimonial/" title="United States Matrimony">United States</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/canada-matrimonial/" title="Canada Matrimony">Canada</a></li>
                                  </ul>
                                </div>
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/uk-matrimonial/" title="United Kingdom Matrimony">United Kingdom</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/uae-matrimonial/" title="United Arab Emirates Matrimony">United Arab Emirates</a></li>
                                    <li><a href="http://www.jeevansathi.com/matrimonials/pakistan-matrimonial/" title="Pakistan Matrimony">Pakistan</a></li>
                                  </ul>
                                </div>
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/australia-matrimony-matrimonials" title="Australia Matrimony">Australia</a></li>
                                  </ul>
                                </div>
                              </div>
                            </figcaption>
                          </figure>
                          <figure class="scases_h" style="display: none;">
                            <figcaption>
                              <div class="fullwidth clearfix pl10 fontRobReg">
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/hiv-positive-matrimony-matrimonials" title="HIV Positive Matrimony">HIV Positive</a></li>
                                    <li><a href="http://www.jeevansathi.com/thalassemia-major-matrimony-matrimonials" title="Thalassemia Major Matrimony">Thalassemia Major</a></li>
                                    <li><a href="http://www.jeevansathi.com/deaf-matrimony-matrimonials" title="Hearing Impaired Matrimony">Hearing Impaired</a></li>
                                    <li><a href="http://www.jeevansathi.com/dumb-matrimony-matrimonials" title="Speech Impaired Matrimony">Speech Impaired</a></li>
                                  </ul>
                                </div>
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/blind-matrimony-matrimonials" title="Visually Impaired Matrimony">Visually Impaired</a></li>
                                    <li><a href="http://www.jeevansathi.com/handicapped-matrimony-matrimonials" title="Handicapped Matrimony">Handicapped</a></li>
                                    <li><a href="http://www.jeevansathi.com/cancer-survivor-matrimony-matrimonials" title="Cancer Survivor Matrimony">Cancer Survivor</a></li>
                                  </ul>
                                </div>
                                <div class="fl wid144">
                                  <ul>
                                    <li><a href="http://www.jeevansathi.com/diabetic-matrimony-matrimonials" title="Diabetic Matrimony">Diabetic</a></li>
                                    <li><a href="http://www.jeevansathi.com/leucoderma-vitiligo-white-patches-white-spots-matrimony-matrimonials" title="Leucoderma Matrimony">Leucoderma</a></li>
                                    <li><a href="http://www.jeevansathi.com/divorcee-matrimony-matrimonials" title="Divorcee Matrimony">Divorcee</a></li>
                                  </ul>
                                </div>
                              </div>
                            </figcaption>
                          </figure>
                        </div>
                      </div>
                    </div>
                    <!--end:hover box--> 
                  </div>
                  <!--end:browse matches--> 
                  <!--start:help/login-->
                  <div class="fr pt18">
                    <ul class="notlogged">
                      <li class="pos-rel">
                        <div class="clearfix cursp" id="login">
                          <div class="fl pt5 pr10">LOGIN</div>
                          <div class="sprite2 fl loginicon"></div>
                        </div>
                      </li>
                    </ul>
                  </div>
                  <!--end:help/login--> 
                </div>
              </div>
            </div>
            <!--end:middle--> 
            
          </div>
        </div>
        <!--end:top nav--> 
        <!--start:search-->
        <div class="hpp3">
          <div class="fullwid hpwhite clearfix"> 
            <!--start:left-->
            <div class="fl">
              <div class="fontlig f20 colr4 opa70 hpp2">Bride / Groom</div>
            </div>
            <!--end:left--> 
            <!--start:right-->
            <div class="fr wid124 bg_pink txtc hpp1"> <i class="sprite2 hpic1 cursp"></i> </div>
            <!--end:right--> 
          </div>
        </div>
        <!--end:search--> 
        <!--start:links-->
        <div class="clearfix pt16 pb30">
          <ul class="hor_list fr f14">
            <li class="pr10" style="border-right:1px solid #fff"><a href="#" id="srchbyid" class="colrw fontlig">Search by Profile ID</a></li>
            <li class="pl10"><a href="#" class="colrw fontlig">Advanced Search</a></li>
          </ul>
        </div>
        <!--end:links--> 
      </div>
    </div>
  </div>
</header>
<!--end:header--> 
<!--start:row 1-->
<article id="hpblk2">
  <div class="bg_pink">
    <div class="container mainwid txtc pt25 pb40 colrw fontlig">
      <div class="f30">Find the Most Genuine Partner Search Experience</div>
      <div class="pt35"><img src="/images/jspc/commonimg/Hp-image1.png" class="brdr-0"/></div>
      <p class="pt20">100% screening of profiles before they start appearing in your search results</p>
      <p>'Verified Seal' added to members who we have met in person and collected their documents on ID, education, income etc. </p>
      <div class="mauto wid280 mt30">
        <div class="fullwid txtc fontlig f24 bg5 lh63"> <a href="/profile/registration_pg1.php?source=hp_black" class="colrw">Register Free</a> </div>
      </div>
    </div>
  </div>
</article>
<!--end:row 1--> 
<!--start:row 2-->
<article>
  <div class="hpbg3"> 
    <!--start:div-->
    <div class="mauto hpwid5 fontlig hpp4">
      <p class="txtc fontlig f30 color11">Get a Paid Membership for Special Benefits.</p>
      <!--start:div-->
      <div class="clearfix fullwid pt50"> 
        <!--start:left-->
        <div class="fl wid55p">
          <ul class="hor_list clearfix fontlig f17 colr2">
            <li><i class="sprite2 hpic2"></i></li>
            <li class="wid80p pl18">
              <p class="fontrobbold f19 color11">View Contacts</p>
              <p class="pt8">See Mobile & Landline numbers.</p>
              <p>Call directly. Send Text messages.</p>
            </li>
          </ul>
        </div>
        <!--end:left--> 
        <!--start:right-->
        <div class="fr wid40p">
          <ul class="hor_list clearfix fontlig f17 colr2">
            <li><i class="sprite2 hpic3"></i></li>
            <li class="wid70p pl17">
              <p class="fontrobbold f19 color11">Send Messages</p>
              <p>Send Personalized Messages </p>
              <p>while expressing Interest.</p>
            </li>
          </ul>
        </div>
        <!--end:right--> 
      </div>
      <!--end:div--> 
      <!--start:div-->
      <div class="clearfix fullwid pt50"> 
        <!--start:left-->
        <div class="fl hpwid8">
          <ul class="hor_list clearfix fontlig f17 colr2">
            <li><i class="sprite2 hpic4"></i></li>
            <li class="wid70p pl5">
              <p class="fontrobbold f19 color11">See Email</p>
              <p>Talk via emails. Share more</p>
              <p> pictures, biodata, kundli etc.</p>
            </li>
          </ul>
        </div>
        <!--end:left--> 
        <!--start:right-->
        <div class="fr wid40p">
          <ul class="hor_list clearfix fontlig f17 colr2">
            <li><i class="sprite2 hpic7"></i></li>
            <li class="wid70p pl10">
              <p class="fontrobbold f19 color11">Chat</p>
              <p>Chat instantly with other</p>
              <p>members who are online. </p>
            </li>
          </ul>
        </div>
        <!--end:right--> 
      </div>
      <!--end:div-->
      <div class="mauto bg_pink txtc lh63 wid45p pos-rel scrollhid mt40"><a href="/profile/mem_comparison.php" class="colrw pinkRipple hoverRed f24">Browse Membership Plans</a></div>
      <p class="txtc pt15 colr2">To know more, call us @ <span class="fontreg">1-800-419-6299</span></p>
    </div>
    <!--end:div--> 
  </div>
</article>
<!--end:row 2--> 
<!--start:row 3-->
<article>
  <div class="container mainwid hpp5">
    <p class="f30 fontlig color11 txtc">Matched By Jeevansathi</p>
    <div class="pt55">
      <ul class="hor_list clearfix mtch f14 color11 fontlig">
        ~foreach from=$successStoryData key=k item=successStory`
    ~if $k eq 0`
          <li class="center"> 
    ~else`
          <li class="center imggapl imggapl_ie ">
    ~/if`
      <a href="~$SITE_URL`/successStory/completestory?sid=~$successStory.SID`"> <img src="~$successStory.SQUARE_PIC_URL`"  style="height:220px;width:220px"/> </a>
            <div class="txtc pt10"> <a href="~$SITE_URL`/successStory/completestory?sid=~$successStory.SID`" class="color11 f14"> ~$successStory.NAME2` weds ~$successStory.NAME1`</a> </div>
          </li>
  ~/foreach`
      </ul>
    </div>
  </div>
</article>
<!--end:row 3--> 
<!--start:row 4-->
<article>
  <div class="hpbg4">
    <div class="mainwid container">
      <div class="hpp6">
        <div class="fullwid clearfix"> 
          <!--start:left-->
          <div class="fl"> <img src="/images/jspc/commonimg/android.png" class="brdr-0"/> </div>
          <!--end:left--> 
          <!--start:right-->
          <div class="fr fontlig wid50p hpp7">
            <p class="hpcolr1 f30">Jeevansathi Apps</p>
            <p class="pt40 f18 txtj lh26">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut laboreet edolore magna aliqua. Ut enim ad minim veniam.</p>
            <ul class="hor_list clearfix pt40">
              <li><a href="/common/appPromotionDesktop" class="headfootsprtie hpic5 disp_b"></a></li>
              <li class="pl17"><a href="/common/appPromotionDesktop" class="headfootsprtie hpic6 disp_b"></a></li>
            </ul>
             <div class="pt40">
            <div id="GetLink" class="pt40">
            <div id="Error" class="fullwid" style="color:#D9475C;font-size:11px"></div>
              <div class="bg-white clearfix wid80p">
                <div class="fl wid64p f14"> <span class="disp_ib lh40 color11 opa50 pl17">+91 - </span>
                  <input id="mobile_id" name="mobile" type="text" class="outwhi brdr-0 bgnone f14" placeholder="Enter your mobile number"/>
                </div>
                <button onclick="ValidateMobileNumber();" class="bg_pink lh40 txtc colrw fontlig fr brdr-0 wid36p cursp">Get App Link</button>
              </div>
            </div>

            <div id="SendLink" class="clearfix disp-none">
                <i id="SendIcon" class="sprite2 hpic9 fl"></i>
                <p id="MsgText"class="fl pl10 f18 color11 fontlig mtn2">Mesage has been sent to 9995394924.</p>
              </div>

          </div>
          </div>
          <!--end:right--> 
        </div>
      </div>
    </div>
  </div>
</article>
<!--end:row 4--> 
<!--start:row 5-->
<article>
  <div class="container mainwid">
    <div class="hpp8">
      <p class="txtc color11 fontlig f30">Browse Matrimonial Profiles by</p>
      <div class="txtc">
        <ul class="tabs hp_btm_tabs hor_list clearfix fontreg f16 pt40 disp_ib">
          <li class="active" rel="tab1">Mother tongue</li>
          <li rel="tab2">Caste</li>
          <li rel="tab3">Religion</li>
          <li rel="tab4">City </li>
          <li rel="tab5">Occupation</li>
          <li rel="tab6">State</li>
          <li rel="tab7">NRI</li>
          <li rel="tab8">Special Cases</li>
        </ul>
      </div>
      <!--start:tab content-->
      <div class="tab_container hphgt1"> 
        <!--start:tab1-->
        <div id="tab1" class="tab_content visb">
          <div class="browsebyp">
            <ul class="clearfix pt10 pb10">
              <li id="Bihari" class="sub_h"> <a href="http://www.jeevansathi.com/matrimonials/bihari-matrimonial/" title="Bihari Matrimony">Bihari</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/bihari-brides-girls" title="Bihari brides Matrimony">Bihari Brides</a> <span>|</span> <a href="http://www.jeevansathi.com/bihari-grooms-boys" title="Bihari grooms Matrimony">Bihari Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Bengali" class="sub_h"> <a href="http://www.jeevansathi.com/matrimonials/bengali-matrimonial/" title="Bengali Matrimony">Bengali</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/bengali-brides-girls" title="Bengali brides Matrimony">Bengali Brides</a> | <a href="http://www.jeevansathi.com/bengali-grooms-boys" title="Bengali grooms Matrimony">Bengali Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Hindi" class="sub_h"> <a href="http://www.jeevansathi.com/matrimonials/hindi-matrimonial/" title="Hindi Matrimony">Hindi</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/hindi-brides-girls" title="Hindi brides Matrimony">Hindi Brides</a> | <a href="http://www.jeevansathi.com/hindi-grooms-boys" title="Hindi grooms Matrimony">Hindi Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Gujarati" class="sub_h"> <a title="Gujarati Matrimony" href="http://www.jeevansathi.com/matrimonials/gujarati-matrimonial/">Gujarati</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a title="Gujarati brides Matrimony" href="http://www.jeevansathi.com/gujarati-brides-girls">Gujarati Brides</a> | <a title="Gujarati grooms Matrimony" href="http://www.jeevansathi.com/gujarati-grooms-boys">Gujarati Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Kannada" class="sub_h"> <a title="Kannada Matrimony" href="http://www.jeevansathi.com/matrimonials/kannada-matrimonial/">Kannada</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a title="Kannada brides Matrimony" href="http://www.jeevansathi.com/kannada-brides-girls">Kannada Brides</a> | <a title="Kannada grooms Matrimony" href="http://www.jeevansathi.com/kannada-grooms-boys">Kannada Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Malayalam" class="sub_h"> <a title="Malayalam Matrimony" href="http://www.jeevansathi.com/matrimonials/malayalee-matrimonial/">Malayalam</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Malayalee brides Matrimony" href="http://www.jeevansathi.com/malayalee-brides-girls">Malayalee Brides</a> | <a title="Malayalee grooms Matrimony" href="http://www.jeevansathi.com/malayalee-grooms-boys">Malayalee Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Marathi" class="sub_h"> <a title="Marathi Matrimony" href="http://www.jeevansathi.com/matrimonials/marathi-matrimonial/">Marathi</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Marathi brides Matrimony" href="http://www.jeevansathi.com/marathi-brides-girls">Marathi Brides</a> | <a title="Marathi grooms Matrimony" href="http://www.jeevansathi.com/marathi-grooms-boys">Marathi Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Oriya" class="sub_h"> <a title="Oriya Matrimony" href="http://www.jeevansathi.com/matrimonials/oriya-matrimonial/">Oriya</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Oriya brides Matrimony" href="http://www.jeevansathi.com/oriya-brides-girls">Oriya Brides</a> | <a title="Oriya grooms Matrimony" href="http://www.jeevansathi.com/oriya-grooms-boys">Oriya Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Punjabi" class="sub_h"> <a title="Punjabi Matrimony" href="http://www.jeevansathi.com/matrimonials/punjabi-matrimonial/">Punjabi</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Punjabi brides Matrimony" href="http://www.jeevansathi.com/punjabi-brides-girls">Punjabi Brides</a> | <a title="Punjabi grooms Matrimony" href="http://www.jeevansathi.com/punjabi-grooms-boys">Punjabi Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Rajasthani" class="sub_h"> <a title="Rajasthani Matrimony" href="http://www.jeevansathi.com/matrimonials/rajasthani-matrimonial/">Rajasthani</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Rajasthani brides Matrimony" href="http://www.jeevansathi.com/rajasthani-brides-girls">Rajasthani Brides</a> | <a title="Rajasthani grooms Matrimony" href="http://www.jeevansathi.com/rajasthani-grooms-boys">Rajasthani Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Tamil" class="sub_h"> <a title="Tamil Matrimony" href="http://www.jeevansathi.com/matrimonials/tamil-matrimonial/">Tamil</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Tamil brides Matrimony" href="http://www.jeevansathi.com/tamil-brides-girls">Tamil Brides</a> | <a title="Tamil grooms Matrimony" href="http://www.jeevansathi.com/tamil-grooms-boys">Tamil Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Telugu" class="sub_h"> <a title="Telugu Matrimony" href="http://www.jeevansathi.com/matrimonials/telugu-matrimonial/">Telugu</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Telugu brides Matrimony" href="http://www.jeevansathi.com/telugu-brides-girls">Telugu Brides</a> | <a title="Telugu grooms Matrimony" href="http://www.jeevansathi.com/telugu-grooms-boys">Telugu Grooms</a></div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="browsebyp">
            <ul class="clearfix pb10">
              <li id="Hindi-UP" class="sub_h"> <a title="Hindi UP Matrimony" href="http://www.jeevansathi.com/hindi-up-matrimony-matrimonials">Hindi-UP</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Hindi UP brides Matrimony" href="http://www.jeevansathi.com/hindi-up-brides-girls">Hindi-UP Brides</a> | <a title="Hindi UP grooms Matrimony" href="http://www.jeevansathi.com/hindi-up-grooms-boys">Hindi-UP Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Hindi-MP" class="sub_h"> <a title="Hindi MP Matrimony" href="http://www.jeevansathi.com/hindi-mp-matrimony-matrimonials">Hindi-MP</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Hindi MP brides Matrimony" href="http://www.jeevansathi.com/hindi-mp-brides-girls">Hindi-MP Brides</a> | <a title="Hindi MP grooms Matrimony" href="http://www.jeevansathi.com/hindi-mp-grooms-boys">Hindi-MP Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Konkani" class="sub_h"> <a title="Konkani Matrimony" href="http://www.jeevansathi.com/konkani-matrimony-matrimonials">Konkani</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Konkani brides Matrimony" href="http://www.jeevansathi.com/konkani-brides-girls">Konkani Brides</a> | <a title="Konkani grooms Matrimony" href="http://www.jeevansathi.com/konkani-grooms-boys">Konkani Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Himachali" class="sub_h"> <a title="Himachali Matrimony" href="http://www.jeevansathi.com/himachali-matrimony-matrimonials">Himachali</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Himachali brides Matrimony" href="http://www.jeevansathi.com/himachali-brides-girls">Himachali Brides</a> | <a title="Himachali grooms Matrimony" href="http://www.jeevansathi.com/himachali-grooms-boys">Himachali Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Haryanvi" class="sub_h"> <a title="Haryanvi Matrimony" href="http://www.jeevansathi.com/haryanvi-matrimony-matrimonials">Haryanvi</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Haryanvi brides Matrimony" href="http://www.jeevansathi.com/haryanvi-brides-girls">Haryanvi Brides</a> | <a title="Haryanvi grooms Matrimony" href="http://www.jeevansathi.com/haryanvi-grooms-boys">Haryanvi Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Assamese" class="sub_h"> <a title="Assamese Matrimony" href="http://www.jeevansathi.com/matrimonials/assamese-matrimonial/">Assamese</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Assamese brides Matrimony" href="http://www.jeevansathi.com/assamese-brides-girls">Assamese Brides</a> | <a title="Assamese grooms Matrimony" href="http://www.jeevansathi.com/assamese-grooms-boys">Assamese Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Kashmiri" class="sub_h"> <a title="Kashmiri Matrimony" href="http://www.jeevansathi.com/kashmiri-matrimony-matrimonials">Kashmiri</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Kashmiri brides Matrimony" href="http://www.jeevansathi.com/kashmiri-brides-girls">Kashmiri Brides</a> | <a title="Kashmiri grooms Matrimony" href="http://www.jeevansathi.com/kashmiri-grooms-boys">Kashmiri Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Sikkim" class="sub_h"> <a title="Sikkim Nepali Matrimony" href="http://www.jeevansathi.com/sikkim-nepali-matrimony-matrimonials">Sikkim/Nepali</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Sikkim Nepali brides Matrimony" href="http://www.jeevansathi.com/sikkim-nepali-brides-girls">Sikkim/Nepali Brides</a> | <a title="Sikkim Nepali grooms Matrimony" href="http://www.jeevansathi.com/sikkim-nepali-grooms-boys">Sikkim/Nepali Grooms</a></div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <!--end:tab1--> 
        <!--start:tab2-->
        <div id="tab2" class="tab_content hpvishid">
          <div class="browsebyp">
            <ul class="clearfix pt10 pb10">
              <li id="Aggarwal" class="sub_h"><a title="Aggarwal Matrimony" href="http://www.jeevansathi.com/matrimonials/agarwal-matrimonial/">Aggarwal</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a title="Aggarwal brides Matrimony" href="http://www.jeevansathi.com/aggarwal-brides-girls">Aggarwal Brides</a> | <a title="Aggarwal grooms Matrimony" href="http://www.jeevansathi.com/aggarwal-grooms-boys">Aggarwal Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Brahmin" class="sub_h"><a title="Brahmin Matrimony" href="http://www.jeevansathi.com/matrimonials/brahmin-matrimonial/">Brahmin</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a title="Brahmin brides Matrimony" href="http://www.jeevansathi.com/brahmin-brides-girls">Brahmin Brides</a> | <a title="Brahmin grooms Matrimony" href="http://www.jeevansathi.com/brahmin-grooms-boys">Brahmin Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="BrahminIyer" class="sub_h"><a title="Brahmin Iyer Matrimony" href="http://www.jeevansathi.com/matrimonials/iyer-matrimonial/">Brahmin Iyer</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a title="Brahmin Iyer brides Matrimony" href="http://www.jeevansathi.com/iyer-brides-girls">Brahmin Iyer Brides</a>| <a title="Brahmin Iyer grooms Matrimony" href="http://www.jeevansathi.com/iyer-grooms-boys">Brahmin Iyer Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Catholic" class="sub_h"><a title="Catholic Matrimony" href="http://www.jeevansathi.com/matrimonials/catholic-matrimonial/">Catholic</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Catholic brides Matrimony" href="http://www.jeevansathi.com/catholic-brides-girls">Catholic Brides</a> | <a title="Catholic grooms Matrimony" href="http://www.jeevansathi.com/catholic-grooms-boys">Catholic Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Jat" class="sub_h"><a title="Jat Matrimony" href="http://www.jeevansathi.com/matrimonials/jat-matrimonial/">Jat</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Jat brides Matrimony" href="http://www.jeevansathi.com/jat-brides-girls">Jat Brides</a> | <a title="Jat grooms Matrimony" href="http://www.jeevansathi.com/jat-grooms-boys">Jat Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Kayastha" class="sub_h"><a title="Kayastha Matrimony" href="http://www.jeevansathi.com/matrimonials/kayastha-matrimonial/">Kayastha</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Kayastha brides Matrimony" href="http://www.jeevansathi.com/kayastha-brides-girls">Kayastha Brides</a> | <a title="Kayastha grooms Matrimony" href="http://www.jeevansathi.com/kayastha-grooms-boys">Kayastha Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Khatri" class="sub_h"><a title="Khatri Matrimony" href="http://www.jeevansathi.com/matrimonials/khatri-matrimonial/">Khatri</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Khatri brides Matrimony" href="http://www.jeevansathi.com/khatri-brides-girls">Khatri Brides</a> | <a title="Khatri grooms Matrimony" href="http://www.jeevansathi.com/khatri-grooms-boys">Khatri Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Kshatriya" class="sub_h"><a title="Kshatriya Matrimony" href="http://www.jeevansathi.com/matrimonials/kshatriya-matrimonial/">Kshatriya</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a title="Kshatriya brides Matrimony" href="http://www.jeevansathi.com/kshatriya-brides-girls">Kshatriya Brides</a> | <a title="Kshatriya grooms Matrimony" href="http://www.jeevansathi.com/kshatriya-grooms-boys">Kshatriya Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Lingayat" class="sub_h"><a title="Lingayat Matrimony" href="http://www.jeevansathi.com/matrimonials/lingayat-matrimonial/">Lingayat</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Lingayat brides Matrimony" href="http://www.jeevansathi.com/lingayat-brides-girls">Lingayat Brides</a> | <a title="Lingayat grooms Matrimony" href="http://www.jeevansathi.com/lingayat-grooms-boys">Lingayat Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Maratha" class="sub_h"><a title="Maratha Matrimony" href="http://www.jeevansathi.com/maratha-matrimony-matrimonials">Maratha</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a title="Maratha brides Matrimony" href="http://www.jeevansathi.com/maratha-brides-girls">Maratha Brides</a> | <a title="Maratha grooms Matrimony" href="http://www.jeevansathi.com/maratha-grooms-boys">Maratha Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Nair" class="sub_h"><a title="Nair Matrimony" href="http://www.jeevansathi.com/matrimonials/nair-matrimonial/">Nair</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Nair brides Matrimony" href="http://www.jeevansathi.com/nair-brides-girls">Nair Brides</a> | <a title="Nair grooms Matrimony" href="http://www.jeevansathi.com/nair-grooms-boys">Nair Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Rajput" class="sub_h"><a title="Rajput Matrimony" href="http://www.jeevansathi.com/matrimonials/rajput-matrimonial/">Rajput</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a title="Rajput brides Matrimony" href="http://www.jeevansathi.com/rajput-brides-girls">Rajput Brides</a> | <a title="Rajput grooms Matrimony" href="http://www.jeevansathi.com/rajput-grooms-boys">Rajput Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Sindhi" class="sub_h"><a title="Sindhi Matrimony" href="http://www.jeevansathi.com/matrimonials/sindhi-matrimonial/">Sindhi</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Sindhi brides Matrimony" href="http://www.jeevansathi.com/sindhi-brides-girls">Sindhi Brides</a> | <a title="Sindhi grooms Matrimony" href="http://www.jeevansathi.com/sindhi-grooms-boys">Sindhi Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Sunni" class="sub_h"><a title="Sunni Matrimony" href="http://www.jeevansathi.com/matrimonials/sunni-matrimonial/">Sunni</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Sunni brides Matrimony" href="http://www.jeevansathi.com/sunni-brides-girls">Sunni Brides</a> | <a title="Sunni grooms Matrimony" href="http://www.jeevansathi.com/sunni-grooms-boys">Sunni Grooms</a> </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="browsebyp">
            <ul class="clearfix pb10">
              <li id="Arora" class="sub_h"><a title="Arora Matrimony" href="http://www.jeevansathi.com/matrimonials/arora-matrimonials/">Arora</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Arora brides Matrimony" href="http://www.jeevansathi.com/arora-brides-girls">Arora Brides</a> | <a title="Arora grooms Matrimony" href="http://www.jeevansathi.com/arora-grooms-boys">Arora Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Shwetamber" class="sub_h"><a title="Shwetamber Matrimony" href="http://www.jeevansathi.com/matrimonials/shwetamber-matrimonial/">Shwetamber</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a title="Shwetamber brides Matrimony" href="http://www.jeevansathi.com/shwetamber-brides-girls">Shwetamber Brides</a> | <a title="Shwetamber grooms Matrimony" href="http://www.jeevansathi.com/shwetamber-grooms-boys">Shwetamber Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Yadav" class="sub_h"><a title="Yadav Matrimony" href="http://www.jeevansathi.com/matrimonials/yadav-matrimonial/">Yadav</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a title="Yadav brides Matrimony" href="http://www.jeevansathi.com/yadav-brides-girls">Yadav Brides</a> | <a title="Yadav grooms Matrimony" href="http://www.jeevansathi.com/yadav-grooms-boys">Yadav Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Bania" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/bania-matrimonial/" title="Bania Matrimony">Bania</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a href="http://www.jeevansathi.com/bania-brides-girls" title="Bania brides Matrimony">Bania Brides</a> | <a href="http://www.jeevansathi.com/bania-grooms-boys" title="Bania grooms Matrimony">Bania Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="SC" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/scheduled-caste-matrimonial/" title="Scheduled Caste Matrimony">Scheduled Caste</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a href="http://www.jeevansathi.com/scheduled-caste-brides-girls" title="Scheduled Caste brides Matrimony">Scheduled Caste Brides</a> | <a href="http://www.jeevansathi.com/scheduled-caste-grooms-boys" title="Scheduled Caste grooms Matrimony">Scheduled Caste Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Catholic" class="sub_h"><a href="http://www.jeevansathi.com/roman-catholic-matrimony-matrimonials" title="Catholic Roman Matrimony">Catholic - Roman</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a href="http://www.jeevansathi.com/roman-catholic-brides-girls" title="Catholic Roman brides Matrimony">Catholic - Roman Brides</a> | <a href="http://www.jeevansathi.com/roman-catholic-grooms-boys" title="Catholic Roman grooms Matrimony">Catholic - Roman Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Patel" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/patel-matrimonial/" title="Patel Matrimony">Patel</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/patel-brides-girls" title="Patel brides Matrimony">Patel Brides</a> | <a href="http://www.jeevansathi.com/patel-grooms-boys" title="Patel grooms Matrimony">Patel Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Digamber" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/digamber-matrimonial/" title="Digamber Matrimony">Digamber</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a href="http://www.jeevansathi.com/digamber-brides-girls" title="Digamber brides Matrimony">Digamber Brides</a> | <a href="http://www.jeevansathi.com/digamber-grooms-boys" title="Digamber grooms Matrimony">Digamber Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Digamber" class="sub_h"><a href="http://www.jeevansathi.com/sikh-jat-matrimony-matrimonials" title="Sikh Jat Matrimony">Sikh-Jat</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/sikh-jat-brides-girls" title="Sikh Jat brides Matrimony">Sikh-Jat Brides</a> | <a href="http://www.jeevansathi.com/sikh-jat-grooms-boys" title="Sikh Jat grooms Matrimony">Sikh-Jat Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
            </ul>
          </div>
          <div class="browsebyp">
            <ul class="clearfix pb10">
              <li id="Gupta" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/gupta-matrimonial/" title="Gupta Matrimony">Gupta</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a href="http://www.jeevansathi.com/gupta-brides-girls" title="Gupta brides Matrimony">Gupta Brides</a> | <a href="http://www.jeevansathi.com/gupta-grooms-boys" title="Gupta grooms Matrimony">Gupta Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Teli" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/teli-matrimonial/" title="Teli Matrimony">Teli</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/teli-brides-girls" title="Teli brides Matrimony">Teli Brides</a> | <a href="http://www.jeevansathi.com/teli-grooms-boys" title="Teli grooms Matrimony">Teli Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Vishwakarma" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/vishwakarma-matrimonial/" title="Vishwakarma Matrimony">Vishwakarma</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a href="http://www.jeevansathi.com/vishwakarma-brides-girls" title="Vishwakarma brides Matrimony">Vishwakarma Brides</a> | <a href="http://www.jeevansathi.com/vishwakarma-grooms-boys" title="Vishwakarma grooms Matrimony">Vishwakarma Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Vaishnav" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/vaishnav-matrimonial/" title="Vaishnav Matrimony">Vaishnav</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/vaishnav-brides-girls" title="Vaishnav brides Matrimony">Vaishnav Brides</a> | <a href="http://www.jeevansathi.com/vaishnav-grooms-boys" title="Vaishnav grooms Matrimony">Vaishnav Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Jaiswal" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/jaiswal-matrimonial/" title="Jaiswal Matrimony">Jaiswal</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/jaiswal-brides-girls" title="Jaiswal brides Matrimony">Jaiswal Brides</a> | <a href="http://www.jeevansathi.com/jaiswal-grooms-boys" title="Jaiswal grooms Matrimony">Jaiswal Grooms</a></div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <!--end:tab2--> 
        <!--start:tab3-->
        <div id="tab3" class="tab_content hpvishid">
          <div class="browsebyp">
            <ul class="clearfix pt10 pb10">
              <li id="Hindu" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/hindu-matrimonial/" title="Hindu Matrimony">Hindu</a>
                  <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Hindu-brides-girls" title="Hindu brides Matrimony">Hindu Brides</a> | <a href="http://www.jeevansathi.com/Hindu-grooms-boys" title="Hindu grooms Matrimony">Hindu Grooms</a></div>
                  </div>
                </div>
               </li>
              <li class="color6">|</li>
              <li id="Muslim" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/muslim-matrimonial/" title="Muslim Matrimony">Muslim</a> 
                  <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Muslim-brides-girls" title="Muslim brides Matrimony">Muslim Brides</a> | <a href="http://www.jeevansathi.com/Muslim-grooms-boys" title="Muslim grooms Matrimony">Muslim Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Christian" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/christian-matrimonial/" title="Christian Matrimony">Christian</a> 
                   <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Christian-brides-girls" title="Christian brides Matrimony">Christian Brides</a> | <a href="http://www.jeevansathi.com/Christian-grooms-boys" title="Christian grooms Matrimony">Christian Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Sikh" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/sikh-matrimonial/" title="Sikh Matrimony">Sikh</a> 
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Sikh-brides-girls" title="Sikh brides Matrimony">Sikh Brides</a> | <a href="http://www.jeevansathi.com/Sikh-grooms-boys" title="Sikh grooms Matrimony">Sikh Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Buddhist" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/buddhist-matrimonial/" title="Buddhist Matrimony">Buddhist</a> 
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Buddhist-brides-girls" title="Buddhist brides Matrimony">Buddhist Brides</a> | <a href="http://www.jeevansathi.com/Buddhist-grooms-boys" title="Buddhist grooms Matrimony">Buddhist Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Jain" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/jain-matrimonial/" title="Jain Matrimony">Jain</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/jain-brides-girls" title="Jain brides Matrimony">Jain Brides</a> | <a href="http://www.jeevansathi.com/jain-grooms-boys" title="Jain grooms Matrimony">Jain Grooms</a></div>
                  </div>
                </div>
               </li>
            </ul>
          </div>
        </div>
        <!--end:tab3--> 
        <!--start:tab4-->
        <div id="tab4" class="tab_content hpvishid">
          <div class="browsebyp">
            <ul class="clearfix pt10 pb10">
              <li id="Delhi" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/delhi-matrimonials/" title="New Delhi Matrimony">New Delhi</a> 
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/delhi-brides-girls" title="New Delhi brides Matrimony">New Delhi Brides</a> | <a href="http://www.jeevansathi.com/delhi-grooms-boys" title="New Delhi grooms Matrimony">New Delhi Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Mumbai" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/mumbai-matrimonial/" title="Mumbai Matrimony">Mumbai</a> 
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/mumbai-brides-girls" title="Mumbai brides Matrimony">Mumbai Brides</a> | <a href="http://www.jeevansathi.com/mumbai-grooms-boys" title="Mumbai grooms Matrimony">Mumbai Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Kolkata" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/kolkata-matrimonial/" title="Kolkata Matrimony">Kolkata</a> 
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/kolkata-brides-girls" title="Kolkata brides Matrimony">Kolkata Brides</a> | <a href="http://www.jeevansathi.com/kolkata-grooms-boys" title="Kolkata grooms Matrimony">Kolkata Grooms</a></div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Chennai" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/chennai-matrimonial/" title="Chennai Matrimony">Chennai</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/chennai-brides-girls" title="Kolkata brides Matrimony">Chennai Brides</a> | <a href="http://www.jeevansathi.com/chennai-grooms-boys" title="Chennai grooms Matrimony">Chennai Grooms</a></div>
                  </div>
                </div>
               </li>
              <li class="color6">|</li>
              <li id="Bangalore" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/bangalore-matrimonial/" title="Bangalore Matrimony">Bangalore</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/bangalore-brides-girls" title="Bangalore brides Matrimony">Bangalore Brides</a> | <a href="http://www.jeevansathi.com/bangalore-grooms-boys" title="Bangalore grooms Matrimony">Bangalore Grooms</a></div>
                  </div>
                </div>
               </li>
              <li class="color6">|</li>
              <li id="Pune" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/pune-matrimonial/" title="Pune Matrimony">Pune</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/pune-brides-girls" title="Pune brides Matrimony">Pune Brides</a> | <a href="http://www.jeevansathi.com/pune-grooms-boys" title="Pune grooms Matrimony">Pune Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Ahmedabad" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/ahmedabad-matrimonial/" title="Ahmedabad Matrimony">Ahmedabad</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/ahmedabad-brides-girls" title="Ahmedabad brides Matrimony">Ahmedabad Brides</a> | <a href="http://www.jeevansathi.com/ahmedabad-grooms-boys" title="Ahmedabad grooms Matrimony">Ahmedabad Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Hyderabad" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/hyderabad-matrimonial/" title="Hyderabad Matrimony">Hyderabad</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/hyderabad-brides-girls" title="Hyderabad brides Matrimony">Hyderabad Brides</a> | <a href="http://www.jeevansathi.com/hyderabad-grooms-boys" title="Hyderabad grooms Matrimony">Hyderabad Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Lucknow" class="sub_h"><a href="http://www.jeevansathi.com/lucknow-matrimony-matrimonials" title="Lucknow Matrimony">Lucknow</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/lucknow-brides-girls" title="Lucknow brides Matrimony">Lucknow Brides</a> | <a href="http://www.jeevansathi.com/lucknow-grooms-boys" title="Lucknow grooms Matrimony">Lucknow Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Chandigarh" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/chandigarh-matrimonial/" title="Chandigarh Matrimony">Chandigarh</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/chandigarh-brides-girls" title="Chandigarh brides Matrimony">Chandigarh Brides</a> | <a href="http://www.jeevansathi.com/lucknow-grooms-boys" title="chandigarh grooms Matrimony">Chandigarh Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Nagpur" class="sub_h"><a href="http://www.jeevansathi.com/nagpur-matrimony-matrimonials" title="Nagpur Matrimony">Nagpur</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/nagpur-brides-girls" title="Nagpur brides Matrimony">Nagpur Brides</a> | <a href="http://www.jeevansathi.com/nagpur-grooms-boys" title="Nagpur grooms Matrimony">Nagpur Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Jaipur" class="sub_h"><a href="http://www.jeevansathi.com/jaipur-matrimony-matrimonials" title="Jaipur Matrimony">Jaipur</a> 

              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/jaipur-brides-girls" title="Jaipur brides Matrimony">Jaipur Brides</a> | <a href="http://www.jeevansathi.com/jaipur-grooms-boys" title="Jaipur grooms Matrimony">Jaipur Grooms</a></div>
                  </div>
                </div>
                </li>
              <li class="color6">|</li>
              <li id="Noida" class="sub_h"><a href="http://www.jeevansathi.com/noida-matrimony-matrimonials" title="Noida Matrimony">Noida</a> 
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/noida-brides-girls" title="Noida brides Matrimony">Noida Brides</a> | <a href="http://www.jeevansathi.com/noida-grooms-boys" title="Noida grooms Matrimony">Noida Grooms</a></div>
                  </div>
                </div>
                </li>
              <li class="color6">|</li>
            </ul>
          </div>
          <div class="browsebyp">
            <ul class="clearfix">
              <li id="Indore" class="sub_h"><a href="http://www.jeevansathi.com/indore-matrimony-matrimonials" title="Indore Matrimony">Indore</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/noida-brides-girls" title="Indore brides Matrimony">Indore Brides</a> | <a href="http://www.jeevansathi.com/indore-grooms-boys" title="Indore grooms Matrimony">Indore Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Gurgaon" class="sub_h"><a href="http://www.jeevansathi.com/gurgaon-matrimony-matrimonials" title="Gurgaon Matrimony">Gurgaon</a>
               <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Gurgaon-brides-girls" title="Gurgaon brides Matrimony">Gurgaon Brides</a> | <a href="http://www.jeevansathi.com/Gurgaon-grooms-boys" title="Gurgaon grooms Matrimony">Gurgaon Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Patna" class="sub_h"><a href="http://www.jeevansathi.com/patna-matrimony-matrimonials" title="Patna Matrimony">Patna</a> 
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Patna-brides-girls" title="Patna brides Matrimony">Patna Brides</a> | <a href="http://www.jeevansathi.com/Patna-grooms-boys" title="Patna grooms Matrimony">Patna Grooms</a></div>
                  </div>
                </div>
                </li>
              <li class="color6">|</li>
              <li id="Bhubaneshwar" class="sub_h"><a href="http://www.jeevansathi.com/bhubaneshwar-matrimony-matrimonials" title="Bhubaneshwar Matrimony">Bhubaneshwar</a> 
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Bhubaneshwar-brides-girls" title="Bhubaneshwar brides Matrimony">Bhubaneshwar Brides</a> | <a href="http://www.jeevansathi.com/Bhubaneshwar-grooms-boys" title="Bhubaneshwar grooms Matrimony">Bhubaneshwar Grooms</a></div>
                  </div>
                </div>
                </li>
              <li class="color6">|</li>
              <li id="Ghaziabad" class="sub_h"><a href="http://www.jeevansathi.com/ghaziabad-matrimony-matrimonials" title="Ghaziabad Matrimony">Ghaziabad</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Ghaziabad-brides-girls" title="Ghaziabad brides Matrimony">Ghaziabad Brides</a> | <a href="http://www.jeevansathi.com/Ghaziabad-grooms-boys" title="Ghaziabad grooms Matrimony">Ghaziabad Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Kanpur" class="sub_h"><a href="http://www.jeevansathi.com/kanpur-matrimony-matrimonials" title="Kanpur Matrimony">Kanpur</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Kanpur-brides-girls" title="Kanpur brides Matrimony">Kanpur Brides</a> | <a href="http://www.jeevansathi.com/Kanpur-grooms-boys" title="Kanpur grooms Matrimony">Kanpur Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Faridabad" class="sub_h"><a href="http://www.jeevansathi.com/faridabad-matrimony-matrimonials" title="Faridabad Matrimony">Faridabad</a> 
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Faridabad-brides-girls" title="Faridabad brides Matrimony">Faridabad Brides</a> | <a href="http://www.jeevansathi.com/Faridabad-grooms-boys" title="Faridabad grooms Matrimony">Faridabad Grooms</a></div>
                  </div>
                </div>
                </li>
              <li class="color6">|</li>
              <li id="Ludhiana" class="sub_h"><a href="http://www.jeevansathi.com/ludhiana-matrimony-matrimonials" title="Ludhiana Matrimony">Ludhiana</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Ludhiana-brides-girls" title="Ludhiana brides Matrimony">Ludhiana Brides</a> | <a href="http://www.jeevansathi.com/Ludhiana-grooms-boys" title="Ludhiana grooms Matrimony">Ludhiana Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Thane" class="sub_h"><a href="http://www.jeevansathi.com/thane-matrimony-matrimonials" title="Thane Matrimony">Thane</a> 
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Thane-brides-girls" title="Thane brides Matrimony">Thane Brides</a> | <a href="http://www.jeevansathi.com/Thane-grooms-boys" title="Thane grooms Matrimony">Thane Grooms</a></div>
                  </div>
                </div>
                </li>
            </ul>
          </div>
        </div>
        <!--end:tab4--> 
        <!--start:tab5-->
        <div id="tab5" class="tab_content hpvishid">
          <div class="browsebyp">
            <ul class="clearfix pt10 pb10">
              <li id="IT" class="sub_h"><a href="http://www.jeevansathi.com/it-software-engineers-matrimony-matrimonials" title="IT Software Matrimony">IT Software</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Thane-brides-girls" title="IT Software brides Matrimony">IT Software Brides</a> | <a href="http://www.jeevansathi.com/Thane-grooms-boys" title="Thane grooms Matrimony">IT Software Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Teacher" class="sub_h"><a href="http://www.jeevansathi.com/teachers-matrimony-matrimonials" title="Teacher Matrimony">Teacher</a> 
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Teacher-brides-girls" title="Teacher brides Matrimony">Teacher Brides</a> | <a href="http://www.jeevansathi.com/Teacher-grooms-boys" title="Teacher grooms Matrimony">Teacher Grooms</a></div>
                  </div>
                </div>
                </li>
              <li class="color6">|</li>
              <li id="Accountant" class="sub_h"><a href="http://www.jeevansathi.com/ca-accountant-matrimony-matrimonials" title="CA Accountant Matrimony">CA/Accountant</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/ca-accountant-brides-girls" title="CA/Accountant brides Matrimony">CA/Accountant</a> | <a href="http://www.jeevansathi.com/ca-accountant-grooms-boys" title="CA/Accountant grooms Matrimony">CA/Accountant Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Businessman" class="sub_h"><a href="http://www.jeevansathi.com/businessman-matrimony-matrimonials" title="Businessman Matrimony">Businessman</a> 
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Businessman-brides-girls" title="Businessman brides Matrimony">Businessman Brides</a> | <a href="http://www.jeevansathi.com/Businessman-grooms-boys" title="Businessman grooms Matrimony">Businessman Grooms</a></div>
                  </div>
                </div>
                </li>
              <li class="color6">|</li>
              <li id="Doctors" class="sub_h"><a href="http://www.jeevansathi.com/doctors-nurse-matrimony-matrimonials" title="Doctors Nurse Matrimony">Doctors/Nurse</a> 
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/doctors-nurse-brides-girls" title="Doctors/Nurse brides Matrimony">Doctors/Nurse Brides</a> | <a href="http://www.jeevansathi.com/doctors-nurse-grooms-boys" title="Doctors/Nurse grooms Matrimony">Doctors/Nurse Grooms</a></div>
                  </div>
                </div>
                </li>
              <li class="color6">|</li>
              <li id="Govt" class="sub_h"><a href="http://www.jeevansathi.com/government-services-matrimony-matrimonials" title="Govt. Services Matrimony">Govt. Services</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/government-services-brides-girls" title="Govt. Services brides Matrimony">Govt. Services Brides</a> | <a href="http://www.jeevansathi.com/government-services-grooms-boys" title="Govt. Services grooms Matrimony">Govt. Services Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Lawyers" class="sub_h"><a href="http://www.jeevansathi.com/lawyers-matrimony-matrimonials" title="Lawyers Matrimony">Lawyers</a> 
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Lawyers-brides-girls" title="Lawyers brides Matrimony">Lawyers Brides</a> | <a href="http://www.jeevansathi.com/Lawyers-grooms-boys" title="Lawyers grooms Matrimony">Lawyers Grooms</a></div>
                  </div>
                </div>
                </li>
              <li class="color6">|</li>
              <li id="Defence" class="sub_h"><a href="http://www.jeevansathi.com/defence-matrimony-matrimonials" title="Defence Matrimony">Defence</a> 
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Defence-brides-girls" title="Defence brides Matrimony">Defence Brides</a> | <a href="http://www.jeevansathi.com/Defence-grooms-boys" title="Defence grooms Matrimony">Defence Grooms</a></div>
                  </div>
                </div>
                </li>
              <li class="color6">|</li>
              <li id="IAS" class="sub_h"><a href="http://www.jeevansathi.com/ias-matrimony-matrimonials" title="IAS Matrimony">IAS</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/IAS-brides-girls" title="IAS brides Matrimony">IAS Brides</a> | <a href="http://www.jeevansathi.com/IAS-grooms-boys" title="IAS grooms Matrimony">IAS Grooms</a></div>
                  </div>
                </div>
                 </li>
            </ul>
          </div>
        </div>
        <!--end:tab5--> 
        <!--start:tab6-->
        <div id="tab6" class="tab_content hpvishid">
          <div class="browsebyp">
            <ul class="clearfix pt10 pb10">
              <li id="Maharashtra" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/maharashtra-matrimonial/" title="Maharashtra Matrimony">Maharashtra</a> 
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Maharashtra-brides-girls" title="Maharashtra brides Matrimony">Maharashtra Brides</a> | <a href="http://www.jeevansathi.com/Maharashtra-grooms-boys" title="Maharashtra grooms Matrimony">Maharashtra Grooms</a></div>
                  </div>
                </div>
                </li>
              <li class="color6">|</li>
              <li id="UP" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/uttar-pradesh-matrimonial/" title="Uttar Pradesh Matrimony">Uttar Pradesh</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/uttar-pradesh-brides-girls" title="Uttar Pradesh brides Matrimony">Uttar Pradesh Brides</a> | <a href="http://www.jeevansathi.com/uttar-pradesh-grooms-boys" title="Uttar Pradesh grooms Matrimony">Uttar Pradesh Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Karnataka" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/karnataka-matrimonial/" title="Karnataka Matrimony">Karnataka</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Karnataka-brides-girls" title="Karnataka brides Matrimony">Karnataka Brides</a> | <a href="http://www.jeevansathi.com/Karnataka-grooms-boys" title="Karnataka grooms Matrimony">Karnataka Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="AP" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/andhra-pradesh-matrimonial/" title="Andhra Pradesh Matrimony">Andhra Pradesh</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/andhra-pradesh-brides-girls" title="Andhra Pradesh brides Matrimony">Andhra Pradesh Brides</a> | <a href="http://www.jeevansathi.com/andhra-pradesh-grooms-boys" title="Andhra Pradesh grooms Matrimony">Andhra Pradesh Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Tamil" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/tamil-nadu-matrimonial/" title="Tamil Nadu Matrimony">Tamil Nadu</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/tamil-nadu-brides-girls" title="Tamil Nadu brides Matrimony">Tamil Nadu Brides</a> | <a href="http://www.jeevansathi.com/tamil-nadu-grooms-boys" title="Tamil Nadu grooms Matrimony">Tamil Nadu Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li>|</li>
              <li id="WB" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/west-bengal-matrimonials/" title="West Bengal Matrimony">West Bengal</a> 
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/west-bengal-brides-girls" title="West Bengal brides Matrimony">West Bengal Brides</a> | <a href="http://www.jeevansathi.com/west-bengal-grooms-boys" title="West Bengal grooms Matrimony">West Bengal Grooms</a></div>
                  </div>
                </div>
                </li>
              <li class="color6">|</li>
              <li id="MP" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/madhya-pradesh-matrimonial/" title="Madhya Pradesh Matrimony">Madhya Pradesh</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/madhya-pradesh-brides-girls" title="Madhya Pradesh brides Matrimony">Madhya Pradesh Brides</a> | <a href="http://www.jeevansathi.com/madhya-pradesh-grooms-boys" title="Madhya Pradesh grooms Matrimony">Madhya Pradesh Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Gujarat" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/gujarat-matrimonial/" title="Gujarat Matrimony">Gujarat</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Gujarat-brides-girls" title="Gujarat brides Matrimony">Gujarat Brides</a> | <a href="http://www.jeevansathi.com/Gujarat-grooms-boys" title="Gujarat grooms Matrimony">Gujarat Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Haryana" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/haryana-matrimonial/" title="Haryana Matrimony">Haryana</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Haryana-brides-girls" title="Haryana brides Matrimony">Haryana Brides</a> | <a href="http://www.jeevansathi.com/Haryana-grooms-boys" title="Haryana grooms Matrimony">Haryana Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Bihar" class="sub_h"><a href="http://www.jeevansathi.com/bihar-matrimony-matrimonials" title="Bihar Matrimony">Bihar</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Bihar-brides-girls" title="Bihar brides Matrimony">Bihar Brides</a> | <a href="http://www.jeevansathi.com/Bihar-grooms-boys" title="Bihar grooms Matrimony">Bihar Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Kerala" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/kerala-matrimonial/" title="Kerala Matrimony">Kerala</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Kerala-brides-girls" title="Kerala brides Matrimony">Kerala Brides</a> | <a href="http://www.jeevansathi.com/Kerala-grooms-boys" title="Kerala grooms Matrimony">Kerala Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
            </ul>
          </div>
          <div class="browsebyp">
            <ul class="clearfix">
              <li id="Rajasthan" class="sub_h"><a href="http://www.jeevansathi.com/rajasthan-matrimony-matrimonials" title="Rajasthan Matrimony">Rajasthan</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Rajasthan-brides-girls" title="Rajasthan brides Matrimony">Rajasthan Brides</a> | <a href="http://www.jeevansathi.com/Rajasthan-grooms-boys" title="Rajasthan grooms Matrimony">Rajasthan Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Punjab" class="sub_h"><a href="http://www.jeevansathi.com/punjab-matrimony-matrimonials" title="Punjab Matrimony">Punjab</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Punjab-brides-girls" title="Punjab brides Matrimony">Punjab Brides</a> | <a href="http://www.jeevansathi.com/Punjab-grooms-boys" title="Punjab grooms Matrimony">Punjab Grooms</a></div>
                  </div>
                </div>
                 </li>
              <li class="color6">|</li>
              <li id="Orissa" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/orissa-matrimonial/" title="Orissa Matrimony">Orissa</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Orissa-brides-girls" title="Orissa brides Matrimony">Orissa Brides</a> | <a href="http://www.jeevansathi.com/Orissa-grooms-boys" title="Orissa grooms Matrimony">Orissa Grooms</a></div>
                  </div>
                   </li>
              <li class="color6">|</li>
              <li id="Assam" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/assam-matrimonial/" title="Assam Matrimony">Assam</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Assam-brides-girls" title="Assam brides Matrimony">Assam Brides</a> | <a href="http://www.jeevansathi.com/Assam-grooms-boys" title="Assam grooms Matrimony">Assam Grooms</a></div>
                  </div>
                   </li>
              <li class="color6">|</li>
              <li id="JK" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/jammu-kashmir-matrimonial/" title="Jammu &amp; Kashmir Matrimony">Jammu &amp; Kashmir</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/jammu-kashmir-brides-girls" title="Jammu &amp; Kashmir brides Matrimony">Jammu &amp; Kashmir Brides</a> | <a href="http://www.jeevansathi.com/jammu-kashmir-grooms-boys" title="Jammu &amp; Kashmir grooms Matrimony">Jammu &amp; Kashmir Grooms</a></div>
                  </div>
                   </li>
              <li class="color6">|</li>
              <li id="HP" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/himachal-pradesh-matrimonial/" title="Himachal Pradesh Matrimony">Himachal Pradesh</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/himachal-pradesh-brides-girls" title="Himachal Pradesh brides Matrimony">Himachal Pradesh Brides</a> | <a href="http://www.jeevansathi.com/himachal-pradesh-grooms-boys" title="Himachal Pradesh grooms Matrimony">Himachal Pradesh Grooms</a></div>
                  </div>
                   </li>
              <li class="color6">|</li>
              <li id="Chhattisgarh" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/chhattisgarh-matrimony-matrimonials/" title="Chhattisgarh Matrimony">Chhattisgarh</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Chhattisgarh-brides-girls" title="Chhattisgarh brides Matrimony">Chhattisgarh Brides</a> | <a href="http://www.jeevansathi.com/Chhattisgarh-grooms-boys" title="Chhattisgarh grooms Matrimony">Chhattisgarh Grooms</a></div>
                  </div>
                   </li>
              <li class="color6">|</li>
              <li id="Uttarakhand" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/uttarakhand-matrimony-matrimonials/" title="Uttarakhand Matrimony">Uttarakhand</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Uttarakhand-brides-girls" title="Uttarakhand brides Matrimony">Uttarakhand Brides</a> | <a href="http://www.jeevansathi.com/Uttarakhand-grooms-boys" title="Uttarakhand grooms Matrimony">Uttarakhand Grooms</a></div>
                  </div>
                   </li>
            </ul>
          </div>
        </div>
        <!--end:tab6--> 
        <!--start:tab7-->
        <div id="tab7" class="tab_content hpvishid">
          <div class="browsebyp">
            <ul class="clearfix pt10 pb10">
              <li id="NRI" class="sub_h"><a href="http://www.jeevansathi.com/nri-matrimony-matrimonials" title="NRI Matrimony">NRI </a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/nri-brides-girls" title="NRI brides Matrimony">NRI Brides</a> | <a href="http://www.jeevansathi.com/nri-grooms-boys" title="NRI grooms Matrimony">NRI Grooms</a></div>
                  </div>
                   </li>
              <li class="color6">|</li>
              <li id="US" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/usa-matrimonial/" title="United States Matrimony">United States</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/usa-brides-girls" title="United States brides Matrimony">United States Brides</a> | <a href="http://www.jeevansathi.com/usa-grooms-boys" title="United States grooms Matrimony">United States Grooms</a></div>
                  </div>
                   </li>
              <li class="color6">|</li>
              <li id="Canada" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/canada-matrimonial/" title="Canada Matrimony">Canada</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Canada-brides-girls" title="Canada brides Matrimony">Canada Brides</a> | <a href="http://www.jeevansathi.com/Canada-grooms-boys" title="Canada grooms Matrimony">Canada Grooms</a></div>
                  </div>
                   </li>
              <li class="color6">|</li>
              <li id="UK" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/uk-matrimonial/" title="United Kingdom Matrimony">United Kingdom</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/uk-brides-girls" title="United Kingdom brides Matrimony">United Kingdom Brides</a> | <a href="http://www.jeevansathi.com/uk-grooms-boys" title="United Kingdom grooms Matrimony">United Kingdom Grooms</a></div>
                  </div>
                   </li>
              <li class="color6">|</li>
              <li id="UAE" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/uae-matrimonial/" title="United Arab Emirates Matrimony">United Arab Emirates</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/uae-brides-girls" title="United Arab Emirates brides Matrimony">United Arab Emirates Brides</a> | <a href="http://www.jeevansathi.com/uae-grooms-boys" title="United Arab Emirates grooms Matrimony">United Arab Emirates Grooms</a></div>
                  </div>
                   </li>
              <li class="color6">|</li>
              <li id="Pakistan" class="sub_h"><a href="http://www.jeevansathi.com/matrimonials/pakistan-matrimonial/" title="Pakistan Matrimony">Pakistan</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Pakistan-brides-girls" title="Pakistan brides Matrimony">Pakistan Brides</a> | <a href="http://www.jeevansathi.com/Pakistan-grooms-boys" title="Pakistan grooms Matrimony">Pakistan Grooms</a></div>
                  </div>
                   </li>
              <li class="color6">|</li>
              <li id="Australia" class="sub_h"><a href="http://www.jeevansathi.com/australia-matrimony-matrimonials" title="Australia Matrimony">Australia</a>
              <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/Australia-brides-girls" title="Australia brides Matrimony">Australia Brides</a> | <a href="http://www.jeevansathi.com/Australia-grooms-boys" title="Australia grooms Matrimony">Australia Grooms</a></div>
                  </div>
                   </li>
            </ul>
          </div>
        </div>
        <!--end:tab7--> 
        <!--start:tab8-->
        <div id="tab8" class="tab_content hpvishid">
          <div class="browsebyp">
            <ul class="clearfix pt10 pb10">
              <li id="HIV" class="sub_h"><a href="http://www.jeevansathi.com/hiv-positive-matrimony-matrimonials" title="HIV Positive Matrimony">HIV Positive</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a href="http://www.jeevansathi.com/hiv-positive-brides-girls" title="HIV Positive brides Matrimony">HIV Positive Brides</a> | <a href="http://www.jeevansathi.com/hiv-positive-grooms-boys" title="HIV Positive grooms Matrimony">HIV Positive Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Thalassemia" class="sub_h"><a href="http://www.jeevansathi.com/thalassemia-major-matrimony-matrimonials" title="Thalassemia Major Matrimony">Thalassemia Major</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a href="http://www.jeevansathi.com/thalassemia-major-brides-girls" title="Thalassemia Major brides Matrimony">Thalassemia Major Brides</a> | <a href="http://www.jeevansathi.com/thalassemia-major-grooms-boys" title="Thalassemia Major grooms Matrimony">Thalassemia Major Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Hearing" class="sub_h"><a href="http://www.jeevansathi.com/deaf-matrimony-matrimonials" title="Hearing Impaired Matrimony">Hearing Impaired</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a href="http://www.jeevansathi.com/deaf-brides-girls" title="Hearing Impaired brides Matrimony">Hearing Impaired Brides</a> | <a href="http://www.jeevansathi.com/deaf-grooms-boys" title="Hearing Impaired grooms Matrimony">Hearing Impaired Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Speech" class="sub_h"><a href="http://www.jeevansathi.com/dumb-matrimony-matrimonials" title="Speech Impaired Matrimony">Speech Impaired</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a href="http://www.jeevansathi.com/dumb-brides-girls" title="Speech Impaired brides Matrimony">Speech Impaired Brides</a> | <a href="http://www.jeevansathi.com/dumb-grooms-boys" title="Speech Impaired grooms Matrimony">Speech Impaired Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Visually" class="sub_h"><a href="http://www.jeevansathi.com/blind-matrimony-matrimonials" title="Visually Impaired Matrimony">Visually Impaired</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a href="http://www.jeevansathi.com/blind-brides-girls" title="Visually Impaired brides Matrimony">Visually Impaired Brides</a> | <a href="http://www.jeevansathi.com/blind-grooms-boys" title="Visually Impaired grooms Matrimony">Visually Impaired Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Handicapped" class="sub_h"><a href="http://www.jeevansathi.com/handicapped-matrimony-matrimonials" title="Handicapped Matrimony">Handicapped</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a href="http://www.jeevansathi.com/handicapped-brides-girls" title="Handicapped brides Matrimony">Handicapped Brides</a> | <a href="http://www.jeevansathi.com/handicapped-grooms-boys" title="Handicapped grooms Matrimony">Handicapped Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Cancer" class="sub_h"><a href="http://www.jeevansathi.com/cancer-survivor-matrimony-matrimonials" title="Cancer Survivor Matrimony">Cancer Survivor</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a href="http://www.jeevansathi.com/cancer-survivor-brides-girls" title="Cancer Survivor brides Matrimony">Cancer Survivor Brides</a> | <a href="http://www.jeevansathi.com/cancer-survivor-grooms-boys" title="Cancer Survivor grooms Matrimony">Cancer Survivor Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Diabetic" class="sub_h"><a href="http://www.jeevansathi.com/diabetic-matrimony-matrimonials" title="Diabetic Matrimony">Diabetic</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a href="http://www.jeevansathi.com/diabetic-brides-girls" title="Diabetic brides Matrimony">Diabetic Brides</a> | <a href="http://www.jeevansathi.com/diabetic-grooms-boys" title="Diabetic grooms Matrimony">Diabetic Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
              <li id="Leucoderma" class="sub_h"><a href="http://www.jeevansathi.com/leucoderma-vitiligo-white-patches-white-spots-matrimony-matrimonials" title="Leucoderma Matrimony">Leucoderma</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"><a href="http://www.jeevansathi.com/leucoderma-vitiligo-white-patches-white-spots-brides-girls" title="Leucoderma brides Matrimony">Leucoderma Brides</a> | <a href="http://www.jeevansathi.com/leucoderma-vitiligo-white-patches-white-spots-grooms-boys" title="Leucoderma grooms Matrimony">Leucoderma Grooms</a> </div>
                  </div>
                </div>
              </li>
              <li class="color6">|</li>
            </ul>
          </div>
          <div class="browsebyp">
            <ul class="clearfix">
              <li id="Divorcee" class="sub_h"><a href="http://www.jeevansathi.com/divorcee-matrimony-matrimonials" title="Divorcee Matrimony">Divorcee</a>
                <div class="subhobver">
                  <div class="icons pos-abs hpic8 hppos2"></div>
                  <div class="pos-abs z2 sub">
                    <div class="hphgt2"></div>
                    <div class="hpbg5 wr1 pos-rel"> <a href="http://www.jeevansathi.com/divorcee-brides-girls" title="Divorcee brides Matrimony">Divorcee Brides</a> | <a href="http://www.jeevansathi.com/divorcee-grooms-boys" title="Divorcee grooms Matrimony">Divorcee Grooms</a> </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <!--end:tab8--> 
      </div>
      <!--end:tab content--> 
      
    </div>
  </div>
</article>
<!--end:row 5--> 
<!--start:banner-->
<div class="txtc pb48"> <img src="images/banner.jpg" /> </div>
<!--end:banner--> 
<!--start:footer-->
<footer> 
  <!--start:footer band 1-->
  <div class="bg_2">
    <div class="container mainwid clearfix fontreg f15">
      <div class="fl link wid500">
        <ul class="lh50">
          <li>Toll Free (india)1-800-419-6299</li>
          <li>|</li>
          <li>Request Callback </li>
          <li>|</li>
          <li>Live Chat</li>
        </ul>
      </div>
      <div class="fl cards">
        <ul class="mt8">
          <li><i class="headfootsprtie visa"></i></li>
          <li><i class="headfootsprtie mcard"></i></li>
        </ul>
      </div>
      <div class="fr socialicons">
        <ul class="mt8">
          <li><a href="#" class="disp_b headfootsprtie fb"></a></li>
          <li><a href="#" class="disp_b headfootsprtie twit"></a></li>
          <li><a href="#" class="disp_b headfootsprtie in"></a></li>
          <li><a href="#" class="disp_b headfootsprtie gplus"></a></li>
        </ul>
      </div>
    </div>
  </div>
  <!--endt:footer band 1--> 
  <!--start:partner site-->
  <div class="bg-white">
    <div class="container mainwid">
      <div class="pt10 wid800 clearfix txtc pl128">
        <div class="f12 color6 fl pt30 pr36 fontreg">Partner Sites</div>
        <!--start:slider-->
        <div class="fl" style="width:600px;height:80px">
          <div id="slider">
            <div id="images">
              <div class="basic">
                <ul>
                  <li><a href="#" title="99acres.com"><i class="headfootsprtie acre"></i></a></li>
                  <li><a href="#" title="naukri.com"><i class="headfootsprtie nc"></i></a></li>
                  <li><a href="#" title="naukrigulf"><i class="headfootsprtie ng"></i></a></li>
                  <li><a href="#" title="shiksha"><i class="headfootsprtie shiksha"></i></a></li>
                </ul>
              </div>
              <div class="basic">
                <div>
                  <ul>
                    <li><a href="#" title="mydala"><i class="headfootsprtie mydala"></i></a></li>
                    <li><a href="#" title="policybazar"><i class="headfootsprtie pb"></i></a></li>
                    <li><a href="#" title="zomato"><i class="headfootsprtie zomato"></i></a></li>
                    <li><a href="#" title="meritnation"><i class="headfootsprtie meritn"></i></a></li>
                  </ul>
                </div>
              </div>
            </div>
            <a id="prev" href="javascript:void(0);"> <i class="headfootsprtie leftslide"></i> </a> <a id="next" href="javascript:void(0);"> <i class="headfootsprtie rightsmall"></i> </a> </div>
        </div>
        <!--endt:slider--> 
      </div>
      <div class="txtc pb15">
        <ul class="hor_list clearfix f13 fontlig disp_ib">
          <li class="color12 brdrr-2 pr5">Desktop</li>
          <li class="pl5"><a href="#" class="color11">Mobile</a></li>
        </ul>
      </div>
    </div>
  </div>
  <!--end:partner site-->
  <div class="bg_3">
    <div class="padall-10 txtc f12 fontreg colr2"> All rights reserved © 2013 Info Edge India Ltd. </div>
  </div>
</footer>
<!--end:footer--> 
<!--start:help widget-->
<div class="pos_fix hlpwhite fontreg hlppos1 wid200">
  <div class="pos-rel clearfix">
    <div class="Widposabs hlpcl1">
      <div class="vertical-text f12">HELP</div>
    </div>
    <div class="fr" style="width:171px">
      <div class="clearfix padalls wid80p brdrb-8"> <i class="sprite2 helpic1 fl"></i>
        <div class="fl color11 f14 pl10">1-800-419-6299</div>
      </div>
      <div class="clearfix padalls wid80p brdrb-8"> <a href="#"><i class="sprite2 helpic2 fl"></i>
        <div class="fl color11 f14 pl10">Request callback</div>
        </a> </div>
      <div class="clearfix padalls wid80p"> <a href="#"><i class="sprite2 helpic3 fl"></i>
        <div class="fl color11 f14 pl10">Live online chat</div>
        </a> </div>
    </div>
  </div>
</div>
<!--end:help widget-->
<script type="text/javascript">
$(document).ready(function() {
	slider();
});

</script>
