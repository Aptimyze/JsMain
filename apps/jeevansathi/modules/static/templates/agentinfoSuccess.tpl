~assign var=profileid value= $sf_request->getAttribute('profileid')`
<body>
<!--start:header-->
 <div class="cover1">
        <div class="container mainwid pt35 pb48">
            ~include_partial("global/JSPC/_jspcCommonTopNavBar")`
        </div>
    </div>
<!--end:header--> 
<!--start:middle-->
<div class="bg-4">
  <div class="mainwid container pt30 pb30">
      <div class="bg-white fontlig">
          <p class="txtc color5 f24 fontlig pt20 pb20 brdb10">Genuine & Verified Profiles at Jeevansathi.com</p>
            <div class="padalln">
                <!--start:div-->
                <div class="clearfix">
                    <div class="fl wid661">
                        <p class="color5 f19">Who is a Relationship Executive?</p>
                        <p class="color11 f15 pt10">A Jeevansathi relationship executive is sent by Jeevansathi.com to meet you and verify your details. After a user registers a profile in jeevansathi.com, a relationship executive is allocated to the user. He would call the user within 48 hours and schedule a verification visit at the user's home/ office address. He would collect required documents and help you utilize our website in the best way.</p>
                    </div>
                    <div class="fr wid200">
                      <div class="kycImg1 kycIcon1 mauto mt10"></div>
                    </div>            
                </div>            
                <!--end:div-->
                 <!--start:div-->
                <div class="clearfix pt40">
                    <div class="fl wid661">
                        <p class="color5 f19">What is user verification?</p>
                        <p class="color11 f15 pt10">User verification is a process in which a newly registered profile is checked for its credibility and genuineness. It involves a face to face interation with a relationship executive from jeevansathi.com at user's home or office address. The relationship executive verifies some key details in the profile and collects some documents listed below. These documents will not be displayed on the website and are fully secured with us.</p>
                    </div>
                    <div class="fr wid200">
                      <div class="kycImg1 kycIcon2 mauto mt10"></div>
                    </div>            
                </div>            
                <!--end:div-->
                 <!--start:div-->
                <div class="clearfix pt40">
                    <div class="fl wid661">
                        <p class="color5 f19">What all Documents Required for Verification</p>
                        <div class="pt10">
                          <ul class="docreq color11 f15">
                              <li>
                                  <div><p>Proof of Date of Birth</p></div>
                                    <div><p>PAN Card/Driving License/Passport</p></div>                                
                                </li>
                                <li class="mt1">
                                  <div><p>Proof of Address </p></div>
                                    <div><p>Ration Card/Passport/Voter ID/ Rent agreement</p></div>                                
                                </li>
                                 <li class="mt1">
                                  <div><p>Proof of Highest Qualification </p></div>
                                    <div><p>Mark sheet or Certificate for every degree/diploma mentioned on the profile</p></div>                                
                                </li>
                                 <li class="mt1">
                                  <div><p>Proof of Occupation/Income</p></div>
                                    <div><p>If applicable</p></div>                                
                                </li>
                                 <li class="mt1">
                                  <div><p>Proof of Divorce</p></div>
                                    <div><p>If applicable</p></div>                                
                                </li>
                            </ul>                        
                        </div>
                    </div>
                    <div class="fr wid200">
                      <div class="kycImg1 kycIcon3 mauto mt10"></div>
                    </div>            
                </div>            
                <!--end:div-->
                <!--start:div-->
                <div class="pt30">
                  <p class="color5 f19 pb20">Benefits of Verification</p>
                    <ul class="hor_list clearfix verfiBenefit f15">                    
                      <li class="kycImg2 kycIcon4 kycp1" >
                          <p class="pl60">Your Profile is marked 'verified'</p>
                        </li>
                        <li class="kycImg2 kycIcon5 kycbdr1">
                          <p class="pl60">You get more & better responses</p>
                        </li>
                        <li class="kycImg2 kycIcon6">
                          <p class="kycp2">Get to meet genuine & verified profiles like you</p>
                        </li>
                    </ul>                
                </div>
                <!--end:div-->
                <!--start:div-->
                 <div class="pt30">
                  <p class="color5 f19 pb10">How can i get a profile verified?</p>
                    <p class="f15 color11">Your profile can be verified by scheduling a home visit with your relationship executive when he calls. Please keep copies of required documents ready and submit to him.</p>
                 </div>
                <!--end:div-->
                <!--start:div-->
                <ul class="listnone f17 color11 mt30">
                  ~if $personalVerif neq '0'`
                  <div class="hlpcl1 txtc cursp fontreg" style="position: relative; line-height: 30px; padding: 5px; width: 250px; line-height: 46px; font-weight: 400;" id="scheduleVisitDiv">
                  <span onclick="scheduleVisit(~$profileid`);" class="white fontlig scheduleSpan">Request a Visit</span>
                  </div>
                  ~else`
                  <li class="kycImg2 kycIcon7 hgt30 pl40 pt2">For more details call 1800-419-6299</li>
                  ~/if`
                </ul>
                <!--end:div-->
             </div>
             
        </div>
  
  </div>
</div>

    <!--end:middle-->
    <!--start:footer-->
    ~include_partial('global/JSPC/_jspcCommonFooter')`
<!--end:footer--> 
<script type="text/javascript">
function scheduleVisit(profileid){
    $.ajax({
        type: 'POST',
        url: "/membership/scheduleVisit",
        data: {profileid:profileid},
        success: function(data) {
            $(".scheduleSpan").text('Visit Scheduled');
            $("#scheduleVisitDiv").attr('disabled','disabled').off("click");
        }
    });
}

$(document).ready(function() {
customCheckbox("EDUCATION_GROUPING");
customCheckbox("MSTATUS[]");
customCheckbox("OCCUPATION_GROUPING[]");
customCheckbox("MANGLIK[]");
customCheckbox("HOROSCOPE[]");
customCheckbox("DIET[]");
customCheckbox("MTONGUE[]");
customCheckbox("RELIGION[]");
slider();
  
});

</script>
</body>
