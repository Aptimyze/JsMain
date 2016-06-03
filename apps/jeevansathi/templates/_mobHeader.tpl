<header> <a href="~sfConfig::get("app_site_url")`" class="hdLogo"><img src="~$IMG_URL`/images/mobilejs/logo.png" alt="logo"/></a>
  <nav class="grid-col-4 navPanel">
	~if $sf_request->getAttribute('login')`
    <div class="gridCol"> <a href="~sfConfig::get("app_site_url")`/P/mainmenu.php" ~if $page eq 'Register'`class="active"~/if`><em class="homeIc"></em>Home</a> </div>
    <div class="gridCol"> <a href="~sfConfig::get("app_site_url")`/search/partnermatches" ~if $page eq 'Matches'`class="active"~/if`><em class="matchesIc"></em>Matches</a> </div>
    ~else`
    <div class="gridCol"> <a href="~sfConfig::get("app_site_url")`/register/page1?source=mobreg1" ~if $page eq 'Register'`class="active"~/if`><em class="reg"></em>Register</a> </div>
    <div class="gridCol"> <a href="~sfConfig::get("app_site_url")`/P/mainmenu.php" ~if $page eq 'Login'`class="active"~/if`><em class="lgnIc"></em>Login</a> </div>
    ~/if`
    <div class="gridCol"> <a href="~sfConfig::get("app_site_url")`/search/topSearchBand?isMobile=Y" ~if $page eq 'Search'`class="active"~/if`><em class="srchIc"></em>Search</a> </div>
    <div class="gridCol"> <a href="tel:18004196299" style="color:white !important" ~if $page eq 'CallUs'`class="active"~/if`><em class="calIc"></em>Call us</a> </div>
  </nav>
</header>
