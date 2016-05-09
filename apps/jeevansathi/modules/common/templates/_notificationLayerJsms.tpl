~if $showLayer eq 1`
	<!--start:activate notification-->
	<div id="notificationLayer" class="fullwid bg10 pad18 white fontlig cursp">
		<p class="f20">Activate Notifications</p>
		<p class="f14 pt6">Get Notifications on receiving New Interest</p>	
	</div>    
	<!--end:activate notification-->
	<script type="text/javascript">
		$(document).ready(function(){
			$("#notificationLayer").click(function(e){
				console.log('~JsConstants::$ssl_siteUrl`/notification/notify');
				window.open('~JsConstants::$ssl_siteUrl`/notification/notify','Jeevansathi Notifications').focus();
			})
		});
	</script>
~/if`
