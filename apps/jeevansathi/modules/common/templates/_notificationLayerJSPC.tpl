~if $showEnableNotificationsLayer eq 1` 
	<div class="mt40 myjs-bg3 fullwid"> 
		<div id="enable_notifications" class="myjs-p9 clearfix"> 
			<i class="fl myjs-ic4"></i> 
			<div class="fl pl20 fontlig color11"> 
				<p class="f18">Receive Push Notifications</p>
				<p class="f15 pt5">Activate Push Notifications to get notified about important updates related to your account . </p>
			</div>
			<div class="fr bg_pink fontreg colrw lh46 myjs-wid21 txtc cursp" id="enable_notifications_action">
				Activate Notifications
			</div>
		</div>
	</div>
~/if`
<script>
$(document).ready(function(){
	$("#enable_notifications_action").click(function(e){
		$("#enable_notifications_action").removeClass("cursp");
		$("#enable_notifications_action").addClass("js-disabled cursd");
		$("#enable_notifications_action").html("Notification Settings Saved");
		window.open('~JsConstants::$ssl_siteUrl`/notification/notify','Jeevansathi PC Notifications');
	});
});
</script> 