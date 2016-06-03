<SCRIPT>

	function validate(){

		document.getElementById('err2').style.display='none'

		document.getElementById('err1').style.display='none'
		email=document.getElementById("email");

		var x = email.value;

		var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9])+$/;

		err=0;

		if(email.value.length==0)

		{

			document.getElementById('err2_show').innerHTML='Please enter your Email address';

			document.getElementById('err2').style.display='block';

	//		alert('Please enter your Email address');

			err++;

		}

		else if(!filter.test(x))

		{

			document.getElementById('err2_show').innerHTML=email.value+' is not a valid Email-id';

			document.getElementById('err2').style.display='block';

			err++;

		}

		message=document.getElementById("message");

		if(message.value.length==0)

		{

			document.getElementById("err1").style.display="block";

			err++;

		}

		if(err)

		return false;

		else return true;

	}

</SCRIPT>

<style>

div.frm-container{margin-top:1px;}

</style>
<div id="mainpart">

	<section class="s-info-bar">

		<div class="pgwrapper">

		Call Us / Feedback

		</div>

	</section>

	<section>

		<div class="pgwrapper">

			<div class="js-content">

				<p>Call our customer care for assistance.</p>

				<div class="frm-container">

					<div class="row04">

						<div><a href="tel:18004196299"><input type="button" value="Call Us" class="actived-btn"></a></div>

					</div>

				</div>

				<form name="form1" action="~$SITE_URL`/faq/feedback" method="POST" onsubmit="javascript: return validate();">
				~$form['_csrf_token']->render()`
				<input type="hidden" name="feed[category]" value="wapsite"/>
				<input type='hidden' name=feed[name] value="" />
				<input type='hidden' name=feed[username] value="~$USERNAME`" />
				<p>You can also send us your feedback</p>

				<p>Your email</p>

				<div class="frm-container">

					<div class="row04">

						<div>
~$form['email']->render([class=>'w90p','style'=>'padding:4px 5px','size'=>'55','id'=>'email'])`
</div>
					

						<div id="err2" ~if !$form['email']->renderError()`style="display:none" ~/if`>

			<div id='err2_show' class="error-msg"></div>

		</div>

					</div>

				</div>

				<p>Your message</p>

				<div class="frm-container">

					<div class="row04">

						<div>

~$form['message']->render([id=>"message",rows=>7,class=>"feed_ta"])`

					</div>

					<div id="err1" class="error-msg" style="display:none">

						Please enter your message

					</div>

				</div>

				</div>

				<div class="frm-container">

					<div class="row04">

						<div><input type="submit" name="CMDSubmit" value="Send" class="normal-btn colorb" /></div>

					</div>

				</div>



				</form>

		</div> 

	</div>

	</section>

</div>
<script>

~if $ERROR`

document.getElementById('err2_show').innerHTML=email.value+' is not a valid Email-id';

document.getElementById('err2').style.display='block';

~/if`

$('#email').bind('invalid', function() {

    return false;

});

</script>
