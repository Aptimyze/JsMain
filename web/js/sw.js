//console.log("SW");
//console.log('Started', self);
var regId="";
//console.log("1");
/*
setTimeout(function(){
    self.registration.pushManager.getSubscription().then(function(subscription) {
        console.log("got subscription id: ", subscription.endpoint);
        regId = subscription.endpoint;
        regId = regId.split('/');
        regId = regId[regId.length - 1];
        console.log("FinalRegId:"+regId);
    });
},2000);

    self.registration.pushManager.getSubscription().then(function(subscription) {
        console.log("got subscription id: ", subscription.endpoint);
        regId = subscription.endpoint;
        regId = regId.split('/');
        regId = regId[regId.length - 1];
        console.log("FinalRegId:"+regId);
    });
*/
    
self.addEventListener('install', function(event) {
    //console.log("2");
  self.skipWaiting();
  //console.log('Installed', event);
  //console.log("3");
});

self.addEventListener('activate', function(event) {
    //console.log("4");
	 //console.log('Activated', event);
    //console.log("5");
});

self.addEventListener('push', function(event) {
    
    var apiPath = 'https://www.jeevansathi.com/api/v1/notification/getNotification?regId=';
    //console.log("6");
    //var apiPath = 'http://localhost/api/v1/notification/getNotification?regId=';
    event.waitUntil(registration.pushManager.getSubscription().then(function (subscription){
        //console.log("7");
        apiPath = apiPath + subscription.endpoint.split("/").slice(-1);
        //console.log("outside");
        //console.log(apiPath);
        return fetch(apiPath).then(function(response){
            //console.log("inside");
            //console.log(apiPath);
            //console.log("8");
            //console.log(response);
            if(response.status !== 200){
                //console.log("9");
                //console.log("Problem Occurred:"+response.status);
                throw new Error();
            }

            return response.json().then(function(data){
                //console.log("10");
                //console.log("Notification data");
                //console.log(data);
                var title = data.title;
                var message = data.body;
                var icon = data.icon;
                var tag = data.tag;
                var url = data.url;
                if(icon == 'D')
                    icon = '/images/JSLogo.png';
                //console.log("Icon:"+icon);
                if(title){
                    return self.registration.showNotification(title,{
                       body: message,
                       icon: icon,
                       tag: tag,
                       data: url,
                       requireInteraction: true
                    });
                }
            })
        }).catch(function(err){
            //console.log("11");
            //console.error('Unable to retrieve data', err);
            var title = 'Jeevansathi Notification';
            var message = 'You have new notifications';
        })
        
    }));
    /*
	console.log('Push message received', event);
    console.log("InPush");
    console.log(self);
	var title = 'JS Push message';
	event.waitUntil(
    self.registration.showNotification(title, {
		body: 'The Message',
		icon: '/images/JSLogo.png',
		tag: 'my-tag'
    }));
    */
	return;
});

self.addEventListener('notificationclick', function(event){
   //console.log("Notification click:");
   //console.log(event.notification);
   if(Notification.prototype.hasOwnProperty('data')){
       //console.log("Data");
       var url = event.notification.data;
       event.waitUntil(
           clients.matchAll({
               type: "window"
           })
            .then(function(clientList){
                for(var i = 0; i<clientList.length;i++){
                    var client = clientList[i];
                    if(client.url === url && 'focus' in client)
                        return client.focus();
                }
                if(clients.openWindow){
                    return clients.openWindow(url);
                }
           })
        );
   }
   event.notification.close();
   
});