//connect to websocket on crm backend login
var params = [];
var socket = io.connect('http://notifications.jeevansathi.com');
socket.emit('subscribe', readCookie("CRM_NOTIFICATION_AGENT"));

socket.on('update', function(data) {
    if(readCookie("CRM_NOTIFICATION_AGENT"))
    {
        params["ONCLICKLINK"] = data.ONCLICKLINK+"&name="+readCookie("CRM_NOTIFICATION_AGENT")+"&cid="+readCookie("CRM_NOTIFICATION_AGENTID");
        //params["tag"] = data.PROFILE;
        show(data.MESSAGE,params);
    }
});

//show notification
function show(message,params) {
	notify.createNotification("CRM NOTIFICATION", {body:message, icon: "alert.ico",data:params})
}

//read cookie
function readCookie(name) {
    var nameEQ = escape(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));
    }
    return null;
}
