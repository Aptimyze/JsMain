<script>

var feedBackVal = "~$feedbackValue`";
var redirectLink = "~$redirectLink`";

if(feedBackVal == 'Y')
{
	setTimeout(function()
	{
    	window.location.href = "~$redirectLink`";
	}, 5000);
}
else
{
        if(feedBackVal == "Z"){
                var timer = window.setInterval(function () {
                        var count = parseInt($("#desiredRedirectTimer").text());
                        if ( !isNaN(count) && count > 0  )
                        {
                                count--;
                                $("#desiredRedirectTimer").text(count)
                        }
                        else
                        {
                                window.clearInterval(timer);
                                window.location.href ="~$SITE_URL`/profile/dpp#Dpp";
                        }
                },1000);
        }else{
        }
}
</script>

~if $feedbackValue eq Y`
	<span id="feedbackYes" style="font-size:18px;padding: 20px;display: block;">Thank you for your feedback, redirecting to Daily Recommendations...</span>
~else`
        ~if $feedbackValue eq Z`
                <span id="feedbackNo" style="font-size:18px;padding: 20px;display: block;">The matches you received were as per your 'Desired Partner Profile'.Looks like you have not set your 'Desired Partner Profile' correctly.
                <br>
                <br>
                Please fill your Desired Partner Profile carefully, only putting filters which are absolutely necessary, and making sure that Mutual Matches are at least 100.
                <br>
                <br>
                Please wait while we redirect you in <span id="desiredRedirectTimer">~$countRedirectDpp`</span> seconds.
                <span style="ffont-size:18px;padding: 15px;margin-top: 50px;display: block;" align="center"><a href="~$SITE_URL`/profile/dpp#Dpp">Edit Desired Partner Profile </a></span>
                </span>
        ~else`
        <div class="Ms-header posrel dispMS">
                <div class="fs1 color1 txtc f20 pb10">
                    <p class="pad14">Feedback</p>
                </div>
        </div>    
        <div class="M-header posrel dispPC">
            <div class="container wida">
                <div class="pt10 f30 fs1 color1 pl28">
                    <p>Feedback</p>            
                </div>
            </div>
        </div>
    <div class="bg1">
        <div class="container widb pc-padb clearfix">
            <div class="fl ms-widd pc-widd">
                <div class="ms-pad3">
                    <p class="fsos2 f15 color4 pb10 pc-pt10">Please enter the following details:</p>
                    <div class="bg2 fullw shade1">
                        <div class="ms-pad5 pc-pad3 fs2 mb40" id="submitform">
                            <form id="submitform" action="/mailer/feedbackMatchAlertMailer/?submitForm=1" method="POST" enctype="multipart/form-data">
                                <div id="ques1" class="clearfix pb30 f15">
                                    <div class="pc-fl fullw">
                                        <label>Why don't you like the matches recommended:</label>
                                     
                                        <span id="error1" class="dn pt5 color6 fl f11 pr8 errorDiv fullwid"></span>
                                        <br />
                                        <input type="checkbox" name="reason1[]" value="1"><span class="inpText">They don't match my partner preference as specified in my 'Desired Partner Profile'</span></input>
                                        <br />
 					<input type="checkbox" name="reason1[]" value="2"><span class="inpText">Matches recommended are repetitive</span></input>
                                        <br />
                                        <input type="checkbox" name="reason1[]" value="3"><span class="inpText">They match my partner preference as specified on my 'Desired Partner Profiles', but I still find them irrelevant (specify reason)</span></input>
 										<div id="secondInput" class="dn">
                                            <span id="error3" class="dn pt5 color6 fl f11 pr8 errorDiv fullwid"></span>
                                            <br />
                                            <input name="txtReason1" type="text" id="inpFeild2" class="fullwid mt12 pad20" maxlength="2000" placeholder="Please specify....." />
                                        </div>
                                    </div>
                                </div>
                                <div id="ques2" class="clearfix pb30 f15">
                                    <div class="pc-fl fullw">
                                        <label>Which of your criteria are the matches recommended not matching mostly?</label>
                                        <span id="error2" class="dn pt5 color6 fl f11 pr8 errorDiv fullwid"></span>
                                        <br />
                                        <input type="checkbox" name="reason2[]" value="1"><span class="inpText">Age</span></input>
                                        <br />
                                        <input type="checkbox" name="reason2[]" value="2"><span class="inpText">Height</span></input>
                                        <br />
                                        <input type="checkbox" name="reason2[]" value="3"><span class="inpText">Marital Status</span></input>
                                        <br />
                                        <input type="checkbox" name="reason2[]" value="4"><span class="inpText">Location</span></input>
                                        <br />
                                        <input type="checkbox" name="reason2[]" value="5"><span class="inpText">Income</span></input>
                                        <br />
                                        <input type="checkbox" name="reason2[]" value="6"><span class="inpText">Education</span></input>
                                        <br />
                                        <input type="checkbox" name="reason2[]" value="7"><span class="inpText">Occupation</span></input>
                                        <br />
                                        <input type="checkbox" name="reason2[]" value="8"><span class="inpText">Manglik</span></input>
                                        <br />
                                        <input type="checkbox" name="reason2[]" value="9"><span class="inpText">Photo</span></input>
                                        <br />
                                    </div>
                                </div>
                                <div class="clearfix pb30 f15">
                                    <div class="pc-fl fullw">
                                    	<label>Please let us know any other specific reason you don't like our recommendations</label>
                                        <input name="txtReason2" type="text" id="inpFeild" class="fullwid mt12 pad20" maxlength="2000" placeholder="Leave your suggestions here... " />
                                    </div>
                                </div>
                                    <input type ="hidden" name="MA_DATE" value="~$mailSentDate`">
                                    <input type ="hidden" name="STYPE" value="~$stype`">
                                    <input type ="hidden" name="checksum" value="~$checksum`">
                                    <input type ="hidden" name="echecksum" value="~$echecksum`">
                                    <input type ="hidden" name="matchAlertLink" value="~$matchAlertLink`">
                                        <div class="ms-widf pc-widf ms-pta">
                                            <input type="submit" id="subbtn" class="subbtn txtc" style="width:99%" value="Submit" />
                                        </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        ~/if`
~/if`
