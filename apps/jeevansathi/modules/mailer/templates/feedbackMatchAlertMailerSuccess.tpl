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
</script>

~if $feedbackValue eq Y`
	<span id="feedbackYes" style="font-size:18px;padding: 20px;display: block;">Thank you for your feedback, redirecting to Daily Recommendations...</span>
~else`
	<span id="feedbackNo" style="font-size:18px;padding: 20px;display: block;">Thank you for your feedback, this will help us improve our recommendations.
If you wish to get Daily Recommendations strictly as per your desired partner profile, you can choose the option from the 'Daily Recommendations' page of the desktop website.</span>
<span style="ffont-size:18px;padding: 15px;margin-top: 50px;display: block;" align="center"><a href="/~$SITE_URL`">Go to Home </a></span>
~/if`