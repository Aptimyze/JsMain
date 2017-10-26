<script type="text/javascript">
    var ssl_siteUrl = "~JsConstants::$ssl_siteUrl`";
    var browserNotificationRegistered = "~$browserNotificationRegistered`";
    var browserNotificationCookie = "~$browserNotificationCookie`";
</script>
<script src="https://www.gstatic.com/firebasejs/3.6.1/firebase.js"></script>
<script src="https://www.gstatic.com/firebasejs/4.3.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/4.3.1/firebase-messaging.js"></script>
<script src="~JsConstants::$ssl_siteUrl`/js/main_sw_register.js"></script>

~if $showLayer eq 1`
<script type="text/javascript">
    function notificationLayerAction(buttonClick){
    url = '/api/v1/notification/notificationLayerSettings';
    $.ajax({
            type: 'POST',
            url: url,
            data:{
                active: buttonClick
            },
            success: function(data){
                $("#notifBar").addClass("dn");
            }
        });
    }
    $(document).ready(function(){
        $("body").append('<div id="notifBar" class="fullwid posfix hgt180 btm0 boxshadow_new z1000 bg4"><div class="f20 fb fontlig color7 txtc padActi">Activate Notifications</div><div class="color7 f18 fontlig txtc">Jeevansathi would like to notify you about new matches, interests and acceptances</div><div class="posabs btm0 dispib bg7 white f19 fontthin txtc padd22 mt15 wid497p" id="activateNotif">Allow</div><div class="posabs btm0 dispib bg7 white f19 fontthin txtc padd22 mt15 wid50p" id="notNow" style="right:0px">Not Now</div></div>');

        $("#activateNotif").click(function(e){
            notificationLayerAction("Y");
            console.log('~JsConstants::$ssl_siteUrl`/notification/notify');
            window.open('~JsConstants::$ssl_siteUrl`/notification/notify','Jeevansathi Notifications').focus();
        });
        $("#notNow").click(function(e){
            notificationLayerAction("N");
        });
    });
</script>
~/if`
