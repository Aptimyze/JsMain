<div id="mainContent">
  <div class="loader" id="pageloader"></div>
  <div> 
    <!--start:top-->
    <div class="bg1 txtc pad15">
      <div class="posrel">
        <div class="fontthin f20 white">Profile Visible</div>
        ~if $webView eq 1`
          <a href="/myhome"><i class="mainsp posabs set_arow1 set_pos1"></i></a> </div>
        ~else`
        <a href="/"><i class="mainsp posabs set_arow1 set_pos1"></i></a> </div>
        ~/if`
    </div>
    <!--end:top--> 
    <!--start:option-->
    <div class="pad18 bg4 f16 fontlig color13 " align="center">
      <div>Your profile is now visible to others.</div>
    </div>

    <div class="txtc set_btmlink pt15">
      ~if $webView eq 1`  
        <div><a href="/myhome">Go To Home</a></div>
      ~else`
        <div><a href="/">Go To Home</a></div>
      ~/if`
      
      
    </div>
</div>
<script>
    var webView = '~$webView`';
    if(webView) {
      function onUnHideResultBack() {
        if(location.href.indexOf("static/unHideResult") !== -1) {
          location.href = "/";
          return true;
        }
        return false;
      }

      $(document).ready(function(){
        if(typeof historyStoreObj != 'undefined'){
          historyStoreObj.push(onUnHideResultBack,"#unHideSuccess");
        }
      });  
    }
    
</script>
