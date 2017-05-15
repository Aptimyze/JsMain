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
	window.setInterval(function () {
		var count = parseInt($("#desiredRedirectTimer").text());
		if ( !isNaN(count) && count > 0  )
		{
			count--;
			$("#desiredRedirectTimer").text(count)
		}
		else
		{
			window.location.href ="~$SITE_URL`/profile/dpp#Dpp";
		}
	},1000);
}
</script>

~if $feedbackValue eq Y`
	<span id="feedbackYes" style="font-size:18px;padding: 20px;display: block;">Thank you for your feedback, redirecting to Daily Recommendations...</span>
~else`
	<span id="feedbackNo" style="font-size:18px;padding: 20px;display: block;">The matches you received were as per your 'Desired Partner Profile'.
Looks like you have not set your 'Desired Partner Profile' correctly.
<br>
<br>
Please fill your Desired Partner Profile carefully, only putting filters which are absolutely necessary, and making sure that Mutual Matches are at least 100.
<br>
<br>
Please wait while we redirect you in <span id="desiredRedirectTimer">~$countRedirectDpp`</span> seconds.
<span style="ffont-size:18px;padding: 15px;margin-top: 50px;display: block;" align="center"><a href="~$SITE_URL`/profile/dpp#Dpp">Edit Desired Partner Profile </a></span>
</span>
~/if`