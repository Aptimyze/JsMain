~assign var=zedoValue value= $sf_request->getAttribute('zedo')`
~assign var=zedo value= $zedoValue["zedo"]`
<script>
 try {
  var finalResponse= ~$finalResponse|decodevar`;
  var LoggedoutPage=1;
  var logoutChat =~$logoutChat`;
  if(logoutChat) localStorage.setItem("cout","1");
  var userCity="~$city`";
  var isMob="~$isMob`";
  var defaultCityKey="";
  var mapUserCity="";
  var cityJson=finalResponse.servicesData.data.cross_selling_section.cities;
  var cityCount=cityJson.length;
}
catch(err) {
  //console.log(err);
        window.top.location.href = "/static/LogoutPage";
    }
</script>
<div class="">
  <div class="container mainwid  "> 
    <!--start:top nav case logged in-->
    <div class ="ucl_cover2">
       ~include_partial("global/JSPC/_jspcCommonTopNavBar",["stickyTopNavBar"=>1])`      

    <!--end:top nav case logged in--> 
<!--start:cover-->
            <div class="fullwid " style="height:150px">
              <div class="txtc">
                  <div class="f20 fontmed pt55">Congratulations!!!</div>
                    <div class="f18 fontlig pt10">We are happy you found your life partner</div>
                </div>            
            </div>
            </div>
            <!--end:cover-->
            <!--start:section-->
            <div id= "poweredBy" class="pt20 pb20 cursp txtc ulRedirectionUrlBinding">
              <div class="f24 fontlig ucl_color1">We have useful services for your wedding</div>
                <div class="pt10">
                  <img src="/images/postWeddingServices/UC-logo.png"/>
                </div>
            </div>
            <!--end:section-->
            <!--start:tab-->
            <div id="ucltab" class=" width790">
              <ul id="citiesList" class=" ucl_bdr1 clearfix fontlig ucl_color1 f12">
                </ul>            
            </div>
            <!--end:tab-->
            <!--start:content-->
            <div id="ulctabcontent" class=" width790 active">
              
            </div>

             <div id="ulctabcontent_sub" class=" pt90 width790 txtc fontmed ucl_color1">
              Find 80+  services from trusted and verified professionals
            </div>

            <div  id="ulExploreServices" class="mauto cursp fontreg wid177 pt20 mb30 ulRedirectionUrlBinding">
            <a class="sendInterest" onclick=""><button class="bg_pink colrw f17 brdr-0 fullwid lh40 cursp">Explore services</button></a>
</div>
      </div>      
    </div>      <!--end:content-->
    <footer> 
  ~include_partial('global/JSPC/_jspcCommonFooter')`
</footer>