<!DOCTYPE html>
~if $allow eq 'Y'`
<html>
<head>
  <title>Push Notification codelab</title>
  <!--<script src="~JsConstants::$ssl_siteUrl`/min/?f=js/jspc/contactus/contactus_js.js"></script>-->
</head>
<body>
  <h1>JS Notification</h1>
  <p></p>
</body>
</html>
<script>
    $(document).ready(function(){
       console.log("Hello123");
       console.log("~JsConstants::$ssl_siteUrl`/js/jspc/contactus/constactus_js.js")
    });
if ('serviceWorker' in navigator) {
 console.log('Service Worker is supported');
 var url ='~JsConstants::$ssl_siteUrl`/min/?f=/js/jspc/contactus/sw.js';
 //var url = '/js/jspc/contactus/sw.js';
 //var url = '';
 navigator.serviceWorker.register(url).then(function(reg) {
   console.log(':^)', reg);
   // TODO
 }).catch(function(err) {
   console.log(':^(', err);
 });
}
</script>
~/if`