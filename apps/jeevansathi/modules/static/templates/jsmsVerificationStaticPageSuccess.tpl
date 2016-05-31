<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Jeevansathi</title>
    <base href="#" />
</head>
~assign var=profileid value= $sf_request->getAttribute('profileid')`
<body class="bg4">
    <div class="fullwid header">
~if MobileCommon::isAppWebView()`
<a href="/myjs/perform">
        <div class="pad5 cursp" style="float:left;"> <i class="mainsp backIcon"></i>

        </div></a>
        ~elseif $removeBack != 1`
        <div class="pad5 cursp goBack" style="float:left;"> <i class="mainsp backIcon"></i>

        </div>
        ~/if`        
<div class="padd22 f16" style="position: absolute;display: inline-block;left: 32%;">Verified Profiles</div>
    </div>
    <div class="bodyElem pad5 f13">
        <div>Genuine & Verified Profiles at Jeevansathi.com</div>
        <div class="color2 pt25"> Who is a Relationship Executive?</div>
        <div class="pt8">A Jeevansathi relationship executive is sent by Jeevansathi.com to meet you and verify your details. After a user registers a profile in jeevansathi.com, a relationship executive is allocated to the user. He would call the user within 48 hours and schedule a verification visit at the user's home/ office address. He would collect required documents and help you utilize our website in the best way.</div>
        <div class="color2 pt25">What is user verification?</div>
        <div class="pt8">User verification is a process in which a newly registered profile is checked for its credibility and genuineness. It involves a face to face interation with a relationship executive from jeevansathi.com at user's home or office address. The relationship executive verifies some key details in the profile and collects some documents listed below. These documents will not be displayed on the website and are fully secured with us.
        </div>
        <table class="f10 tableBorder">
            <tr>
                <td class="bgColor">Proof of Date of Birth</td>
                <td>PAN Card/Driving License/Passport</td>
            </tr>
            <tr>
                <td class="bgColor">Proof of Address</td>
                <td>Ration Card/Passport/Voter ID/ Rent agreement</td>
            </tr>
            <tr>
                <td class="bgColor">Proof of Highest Qualification</td>
                <td>Mark sheet / Certificate for every degree</td>
            </tr>
            <tr>
                <td class="bgColor">Proof of Occupation/Income</td>
                <td>If applicable</td>
            </tr>
            <tr>
                <td class="bgColor">Proof of Divorce</td>
                <td>If applicable</td>
            </tr>
        </table>
        <div class="color2 pt8">Benefits of Verification </div>
        <table class="f10 fullwid pt10">
            <tr>
                <td class="txtc"><i class="mainsp rightIcon"></i></td>
                <td class="txtc"><i class="mainsp mailIcon"></i></td>
                <td class="txtc" style="width: 35%;"><i class="mainsp faceIcon"></i></td>
            </tr>
            <tr>
                <td class="padr10 f10 txtc">Your Profile is marked 'verified'</td>
                <td class="padl10 padr10 f10 txtc">You get more & better responses</td>
                <td class="padl10 f10 txtc">Get to meet genuine & verified profiles like you</td>
            </tr>
        </table>
        <div class="color2 pt25">How can i get a profile verified?</div>
        <div class="pt8">Your profile can be verified by scheduling a home visit with your relationship executive when he calls. Please keep copies of required documents ready and submit to him.
        </div>
    </div>
    ~if $personalVerif`
    <div id="scheduleVisitDiv" style="position:relative; top:45px; line-height: 30px; padding: 5px;" class="bg7 fullwid txtc mt20 cursp">
        <span class="white fontlig scheduleSpan" onclick='scheduleVisit(~$profileid`);'>Schedule Visit</span>
    </div>
    ~else`
    <div style="position:relative; top:45px; line-height: 30px;" class="bg7 fullwid txtc mt20">
    	<i class="mainsp callIcon"></i>
        <a href="tel:1800-419-6299" class="white fontlig callDiv" style="margin-top: 7px;">Call for more details</a>        
    </div>
    ~/if`
</body>
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
    var webView= "~$webView`";
</script>
</html>
