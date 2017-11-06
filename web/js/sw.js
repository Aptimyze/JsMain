
var regId="";

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

  self.skipWaiting();

});

self.addEventListener('activate', function(event) {

});

self.addEventListener('push', function(event) {
    
    var apiPath = 'https://www.jeevansathi.com/api/v1/notification/getNotification?regId=';

    //var apiPath = 'http://localhost/api/v1/notification/getNotification?regId=';
    event.waitUntil(registration.pushManager.getSubscription().then(function (subscription){

        apiPath = apiPath + subscription.endpoint.split("/").slice(-1);

        return fetch(apiPath).then(function(response){

            if(response.status !== 200){


                throw new Error();
            }

            return response.json().then(function(data){

                var title = data.title;
                var message = data.body;
                var icon = data.icon;
                var tag = data.tag;
                var url = data.url;
                if(icon == 'D')
                    icon = '/images/JSLogo.png';

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

            var title = 'Jeevansathi Notification';
            var message = 'You have new notifications';
        })
        
    }));
    /*

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

   if(Notification.prototype.hasOwnProperty('data')){

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