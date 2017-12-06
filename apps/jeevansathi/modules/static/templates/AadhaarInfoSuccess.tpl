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
                        <p class="color5 f19">Why are you verifying my Aadhar details?</p>
                        <p class="color11 f15 pt10">This is for the benefit of our users, which includes you. By verifying Aadhar, we are trying to make sure that users on Jeevansathi are not misrepresenting their identity.</p>
                    </div>
                    <div class="fr wid200">
                      <div class="kycImg1 kycIcon1 mauto mt10"></div>
                    </div>            
                </div>            
                <!--end:div-->
                 <!--start:div-->
                <div class="clearfix pt40">
                    <div class="fl wid661">
                        <p class="color5 f19">What are benefits of verifying my Aadhar?</p>
                        <p class="color11 f15 pt10">By verifying Aadhar, you can signal to other users that your profile is genuine, and address any apprehensions they may have. You will also featured in 'Verified Profiles' listing.</p>
                    </div>
                    <div class="fr wid200">
                      <div class="kycImg1 kycIcon2 mauto mt10"></div>
                    </div>            
                </div>            
                <!--end:div-->
                 <!--start:div-->
                <div class="clearfix pt40">
                    <div class="fl wid661">
                        <p class="color5 f19">Is it mandatory to verify my Aadhar?</p>
                        <p class="color11 f15 pt10">It is optional for now, but we strongly suggest you to verify Aadhar to let other users know that your profile is genuine.</p>
                    </div>
                    <div class="fr wid200">
                      <div class="kycImg1 kycIcon2 mauto mt10"></div>
                    </div>            
                </div>            
                <!--end:div-->
                <!--start:div-->
                <div class="clearfix pt40">
                    <div class="fl wid661">
                        <p class="color5 f19">Will my Aadhar number be shown to anyone?</p>
                        <p class="color11 f15 pt10">Your Aadhar number will NOT be shown to other users and not be shared by any third parties unless if required by law. It will just be displayed to other users that your ‘Aadhar has been verified against your profile’.</p>
                    </div>
                    <div class="fr wid200">
                      <div class="kycImg1 kycIcon2 mauto mt10"></div>
                    </div>            
                </div>            
                <!--end:div-->
                <!--start:div-->
                <div class="clearfix pt40">
                    <div class="fl wid661">
                        <p class="color5 f19">How can I be certain that my identity will be protected?</p>
                        <p class="color11 f15 pt10">We at Jeevansathi take your privacy seriously. You can be completely assured that your Aadhar number will not even be accessible to internal staff, unless required by law. Your information is stored in a secured database, with stringent steps implemented to prevent data theft.</p>
                    </div>
                    <div class="fr wid200">
                      <div class="kycImg1 kycIcon2 mauto mt10"></div>
                    </div>            
                </div>            
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
