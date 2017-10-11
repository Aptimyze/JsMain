~if $showEnableNotificationsLayer eq 1`
<script>
function notificationLayerAction(buttonClick){
url = '/api/v1/notification/notificationLayerSettings';
$.ajax({
        type: 'POST',
        url: url,
        data:{
            active: buttonClick
        },
        success: function(data){
            $("#notifBar").addClass("disp-none");
        }
    });
}
$(document).ready(function(){
    
    $("body").append('<div id="notifBar" class="fullwid pos_fix hgt100 bg-white btm0 boxshadow_new z1000"><div class="txtc padall-10 centerdiv"><div style="text-align: initial;" class="fl wid685 padall-10"><div class="f24 fontlig color11">Activate Notifications</div><div class="color7 f18 fontlig">Jeevansathi would like to notify you about new matches, interests and acceptances</div></div><div class="pos_rel dispib bg_pink wid125 fontthin f20 colrw lh40 txtc cursp hoverPink margin_new18" id="activateNotif">Allow</div><div class="pos_rel dispib bg_pink wid125 fontthin f20 colrw lh40 cursp hoverPink margin_new18" id="notNow" style="margin-left:5px;">Not Now</div></div></div>');
    
	$("#activateNotif").click(function(e){
        notificationLayerAction("Y");
		window.open('~JsConstants::$ssl_siteUrl`/notification/notify','Jeevansathi PC Notifications');
	});
    $("#notNow").click(function(e){
        notificationLayerAction("N");
    });
});
</script> 
~/if`