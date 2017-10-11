if ('serviceWorker' in navigator) 
{
    if(Notification.permission === 'default' || Notification.permission === 'granted')
    {
        Notification.requestPermission(function(permission){
            if(permission === 'granted'){
                var url = ssl_siteUrl+'/js/sw.js';
                navigator.serviceWorker.register(url).then(function(reg){
                    setTimeout(function(){
                        reg.pushManager.subscribe({
                            userVisibleOnly: true
                        }).then(function(sub){
			    if(browserNotificationRegistered && browserNotificationCookie!='Y'){	
                            	var endpoint = sub.endpoint;
                            	endPointArr = endpoint.split('/');
                            	var regId = endPointArr[endPointArr.length - 1];
                            	//var chromeVersion = navigator.userAgent.match(/Chrom(e|ium)\/([0-9]+)\./)[2];
                            	url = "/api/v1/notification/insertChromeId"
                            	$.ajax({
                            	    type: 'POST',
                                	url: url,
                                	data:{
                                	    regId: regId,
                                	},
                                	success: function(data) {
                                	}
                            	});
			    }	
                        },function(rea){
                            console.log(rea);
                        });
                    },1000);
                }, function(reason){
                    console.log(reason);
                }).catch(function(error){
                    console.log(':^(', error);
                });
            }
            else{
                //$("#permissionResponse").html("Notifications blocked for this site");
            }
        });
    }
    else if(Notification.permission === 'denied')
    {
    }
}
