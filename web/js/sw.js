importScripts('https://www.gstatic.com/firebasejs/4.3.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/4.3.0/firebase-messaging.js');

// initialize the service worker

var config = {
    messagingSenderId: "209380179960" // change the messaging Id with the InfoEdge
  };
  firebase.initializeApp(config);
  
  // getting the connection and creating the messaging object
  const messaging = firebase.messaging();
  
  /*This method is used for the tracking purpose.
   * When the notification is received by the user, an acknowledgement is 
   * sent to the server.
   * @param messageID : contains unique message id
   */
  function ack(messageId) {
      console.log('msg id ::: '+messageId);
      var url = "http://localhost/api/v1/notification/updateTracking?messageId="+messageId;
  
      fetch(url)  
  .then(  
    function(response) {  
      if (response.status !== 200) {  
        console.log('Looks like there was a problem. Status Code: ' +  
          response.status);  
        return;  
      }
      response.json().then(function(data) {  
//        console.log(data);  
      });  
    }  
  )  
  .catch(function(err) {  
    console.log('Fetch Error :-S', err);  
  });
  }
  
 var data;
 
 /*This method will be called when the notification is received.
  * Additionally, it also contains the data in the 'data' parameter object
  * in json format.
  * @payload : This contains the information which the FCM server sends.
  */
messaging.setBackgroundMessageHandler(function(payload) {
      
      data = JSON.parse(JSON.stringify(payload.data));
      
      console.log(data.MSG_ID);
      ack(data.MSG_ID);
      
  const notificationOptions = {
    body: data.BODY,
    icon: '/firebase-logo.png',
      };
  
  console.log(notificationOptions);
  return self.registration.showNotification(data.TITLE,
      notificationOptions); 
});

/*This method is called when the user clicks on the notification.
 * This method uses the data from the payload object which contains
 * the information for the url which needs to be opened.
 */
self.addEventListener('notificationclick', function(event) {
    console.log(data.ACTION);
  event.notification.close();
  event.waitUntil(
    clients.openWindow(data.ACTION)
  );
});