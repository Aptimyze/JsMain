<!--start:She Likes-->
               <div class="prfbr3 js-hobbySection">
              <div class="prfp7"  id="section-likes"> 
                <div class="clearfix"> <i class="sprite2 fl prfic15"></i>
                  <div class="fl colr5 pl8 f17 pt3">
                    ~if $bEditView`
                      Your
                    ~elseif $apiData["about"]["gender"] eq "Female"`
                      She
                    ~else`
                      He
                    ~/if`
                    Likes
                    </div>
                  ~if $bEditView`
                    <div class="fr pt4"><a class="cursp color5 fontlig f15 js-editBtn editableSections" data-section-id="likes">Edit</a> </div>
                  ~/if`
                </div>
                <div class="prflist1 likelist pl32 color11 fontlig f15 js-hobbies js-likesView"> 
                  ~if $apiData['lifestyle']['hobbies']['HOBBY'] neq null || $bEditView`
                  <!--start:div-->
                  <div class="clearfix pt30"> 
                  <div class="fl pos-rel hobbyParent">
                  <i class="sprite2 prfic16"></i>
                  <!--start:tooltip-->
                  <div class="hobby">
                  <div class="boxtip1 colrw fontlig prfp8">
                      Hobbies
                    </div>                                    
                  </div>
                  </div>
                    <div class="fl prfp14 prfwid7"><span id='hobbyView' ~if $bEditView && $apiData['lifestyle']['hobbies']['HOBBY'] eq $notFilledInText`  class="color5 more" ~else` class="more" ~/if` >~$apiData['lifestyle']['hobbies']['HOBBY']`</span></div>
                  </div>
                  <!--end:div--> 
                  ~/if`
                  ~if $apiData['lifestyle']['hobbies']['INTEREST'] neq null || $bEditView`
                  <!--start:div-->
                  <div class="clearfix pt30"> 
                  <div class="fl pos-rel interestParent">
                  <i class="sprite2 prfic17"></i>
                  <!--start:tooltip-->
                  <div class="interest">
                  <div class="boxtip1 colrw fontlig prfp8">
                      Interests
                    </div>                                    
                  </div>
                  </div>
                    <div class="fl prfp14 prfwid7"><span id='interestView'  ~if $bEditView && $apiData['lifestyle']['hobbies']['INTEREST'] eq $notFilledInText`  class="color5 more" ~else` class="more"   ~/if` >~$apiData['lifestyle']['hobbies']['INTEREST']`</span></div>
                  </div>
                  <!--end:div--> 
                  ~/if`
                  ~if $apiData['lifestyle']['hobbies']['MUSIC'] neq null || $bEditView`
                  <!--start:div-->
                  <div class="clearfix pt30"> 
                  <div class="fl pos-rel musicParent">
                  <i class="sprite2 prfic18"></i>
                  <!--start:tooltip-->
                  <div class="music">
                  <div class="boxtip1 colrw fontlig prfp8">
                      Music
                    </div>                                    
                  </div>
                  </div>
                    <div class="fl prfp14 prfwid7"><span id='musicView' ~if $bEditView && $apiData['lifestyle']['hobbies']['MUSIC'] eq $notFilledInText`  class="color5 more" ~else` class="more"   ~/if` >~$apiData['lifestyle']['hobbies']['MUSIC']`</span></div>
                  </div>
                  <!--end:div--> 
                  ~/if`
                  ~if $apiData['lifestyle']['hobbies']['BOOK'] neq null || $bEditView`
                   <!--start:div-->
                  <div class="clearfix pt30"> 
                  <div class="fl pos-rel bookParent">
                  <i class="sprite2 prfic20"></i>
                  <!--start:tooltip-->
                  <div class="book">
                  <div class="boxtip2 colrw fontlig prfp8 wd79">
                      Favourite read
                    </div>                                    
                  </div>
                  </div>
                    <div class="fl prfp14 prfwid7 ]"><span id='bookView' ~if $bEditView && $apiData['lifestyle']['hobbies']['BOOK'] eq $notFilledInText`  class="color5 more" ~else` class="more"   ~/if` >~$apiData['lifestyle']['hobbies']['BOOK']`</span></div>
                    ~if $apiData['lifestyle']['hobbies']['FAV_BOOK'] neq null || $bEditView`<div class="fl prfp14 prfwid7 color12 pdl112" id="fav_bookLabelParent" >Favourites : <span id='fav_bookView' ~if $bEditView && $apiData['lifestyle']['hobbies']['FAV_BOOK'] eq $notFilledInText`  class="color5"   ~/if` >~$apiData['lifestyle']['hobbies']['FAV_BOOK']`</span>
                    <span class="~if $bEditView && ($editApi.Lifestyle.FAV_BOOK.value|count_characters) eq 0 || $editApi.Lifestyle.FAV_BOOK.screenBit neq 1` disp-none ~/if` js-undSecMsg"><span class="disp_ib color5 f13" > Under Screening</span></span>
                    </div>~/if`
                  </div>
                  <!--end:div--> 
                  ~/if`
                  ~if $apiData['lifestyle']['hobbies']['DRESS'] neq null || $bEditView`
                  <!--start:div-->
                  <div class="clearfix pt30"> 
                  <div class="fl pos-rel dressParent">
                  <i class="sprite2 prfic19"></i>
                  <!--start:tooltip-->
                  <div class="dress">
                  <div class="boxtip2 colrw fontlig prfp8 wd60">
                      Dress style
                    </div>                                    
                  </div>
                  </div>
                    <div class="fl prfp14 prfwid7"><span id='dressView' ~if $bEditView && $apiData['lifestyle']['hobbies']['DRESS'] eq $notFilledInText`  class="color5 more" ~else` class="more" ~/if` >~$apiData['lifestyle']['hobbies']['DRESS']`</span></div>
                  </div>
                  <!--end:div--> 
                  ~/if`
                  ~if $apiData['lifestyle']['hobbies']['FAV_TVSHOW'] neq null || $bEditView`
                   <!--start:div-->
                  <div class="clearfix pt30"> 
                  <div class="fl pos-rel tvParent">
                  <i class="sprite2 prfic21"></i>
                  <!--start:tooltip-->
                  <div class="tv">
                  <div class="boxtip2 colrw fontlig prfp8 wd107">
                      Favourite TV shows
                    </div>                                    
                  </div>
                  </div>
                    <div class="fl prfp14 prfwid7" id="fav_tvshowLabelParent" >
                    <span id='fav_tvshowView' ~if $bEditView && $apiData['lifestyle']['hobbies']['FAV_TVSHOW'] eq $notFilledInText`  class="color5 more" ~else` class="more" ~/if` >
                    ~if $bEditView && ($apiData['lifestyle']['hobbies']['FAV_TVSHOW']|count_characters) eq 0`
                      ~$notFilledInText`
                    ~else`
                      ~$apiData['lifestyle']['hobbies']['FAV_TVSHOW']`
                    ~/if`  
                    </span>
                    <span class="~if $bEditView && ($editApi.Lifestyle.FAV_TVSHOW.value|count_characters) eq 0 || $editApi.Lifestyle.FAV_TVSHOW.screenBit neq 1` disp-none ~/if` js-undSecMsg"><span class="disp_ib color5 f13" > Under Screening</span></span>
                    </div>
                  </div>
                  <!--end:div--> 
                  ~/if`
                  ~if $apiData['lifestyle']['hobbies']['MOVIE'] neq null || $bEditView`
                  <!--start:div-->
                  <div class="clearfix pt30"> 
                  <div class="fl pos-rel movieParent">
                  <i class="sprite2 prfic22"></i>
                  <!--start:tooltip-->
                  <div class="movie">
                  <div class="boxtip2 colrw fontlig prfp8 wd92">
                      Preferred Movies
                    </div>                                    
                  </div>
                  </div>
                    <div class="fl prfp14 prfwid7"><span id='movieView' ~if $bEditView && $apiData['lifestyle']['hobbies']['MOVIE'] eq $notFilledInText`  class="color5 more" ~else` class="more" ~/if` >~$apiData['lifestyle']['hobbies']['MOVIE']`</span></div>
                    ~if $apiData['lifestyle']['hobbies']['FAV_MOVIE'] neq null || $bEditView`<div class="fl prfp14 prfwid7 color12 pdl112" id="fav_movieLabelParent">Favourites : <span id='fav_movieView' ~if $bEditView && $apiData['lifestyle']['hobbies']['FAV_MOVIE'] eq $notFilledInText`  class="color5"   ~/if` >~$apiData['lifestyle']['hobbies']['FAV_MOVIE']`</span>
                    <span class="~if $bEditView && ($editApi.Lifestyle.FAV_MOVIE.value|count_characters) eq 0 || $editApi.Lifestyle.FAV_MOVIE.screenBit neq 1` disp-none ~/if` js-undSecMsg"><span class="disp_ib color5 f13" > Under Screening</span></span>
                    </div>~/if`
                  </div>
                  <!--end:div--> 
                  ~/if`
                  ~if $apiData['lifestyle']['hobbies']['SPORTS'] neq null || $bEditView`
                   <!--start:div-->
                  <div class="clearfix pt30"> 
                  <div class="fl pos-rel sportsParent">
                  <i class="sprite2 prfic23"></i>
                  <!--start:tooltip-->
                  <div class="sports">
                  <div class="boxtip2 colrw fontlig prfp8 wd77">
                      Sports Fitness
                    </div>                                    
                  </div>
                  </div>
                    <div class="fl prfp14 prfwid7"><span id='sportsView' ~if $bEditView && $apiData['lifestyle']['hobbies']['SPORTS'] eq $notFilledInText`  class="color5 more" ~else` class="more" ~/if` >~$apiData['lifestyle']['hobbies']['SPORTS']`</span></div>
                  </div>
                  <!--end:div--> 
                  ~/if`
                  ~if $apiData['lifestyle']['hobbies']['CUISINE'] neq null || $bEditView`
                   <!--start:div-->
                  <div class="clearfix pt30"> 
                  <div class="fl pos-rel foodParent">
                  <i class="sprite2 prfic24"></i>
                  <!--start:tooltip-->
                  <div class="food">
                  <div class="boxtip2 colrw fontlig prfp8 wd93">
                      Favourite Cuisine
                    </div>                                    
                  </div>
                  </div>
                    <div class="fl prfp14 prfwid7"><span id='cuisineView' ~if $bEditView && $apiData['lifestyle']['hobbies']['CUISINE'] eq $notFilledInText`  class="color5 more" ~else` class="more" ~/if` >~$apiData['lifestyle']['hobbies']['CUISINE']`</span></div>
                    ~if $apiData['lifestyle']['hobbies']['FAV_FOOD'] neq null || $bEditView`<div class="fl prfp14 prfwid7 color12 pdl112" id="fav_foodLabelParent" >I Cook : <span id='fav_foodView' ~if $bEditView && $apiData['lifestyle']['hobbies']['FAV_FOOD'] eq $notFilledInText`  class="color5"   ~/if` >~$apiData['lifestyle']['hobbies']['FAV_FOOD']`</span>
                    <span class="~if $bEditView && ($editApi.Lifestyle.FAV_FOOD.value|count_characters) eq 0 || $editApi.Lifestyle.FAV_FOOD.screenBit neq 1` disp-none ~/if` js-undSecMsg"><span class="disp_ib color5 f13" > Under Screening</span></span>
                    </div>~/if`
                  </div>
                  <!--end:div--> 
                  ~/if`
                  ~if $apiData['lifestyle']['hobbies']['FAV_VAC_DEST'] neq null || $bEditView`
                    <!--start:div-->
                  <div class="clearfix pt30"> 
                  <div class="fl pos-rel vacationParent">
                  <i class="sprite2 prfic25"></i>
                  <!--start:tooltip-->
                  <div class="vacation">
                  <div class="boxtip2 colrw fontlig prfp8 wd162">
                      Favourite Vacation destination
                    </div>                                    
                  </div>
                  </div>
                    <div class="fl prfp14 prfwid7" id="fav_vac_destLabelParent">
                    <span id='fav_vac_destView' ~if $bEditView && ($apiData['lifestyle']['hobbies']['FAV_VAC_DEST']) eq $notFilledInText`   class="color5 more" ~else` class="more" ~/if` > 
                          ~if $bEditView && ($apiData['lifestyle']['hobbies']['FAV_VAC_DEST']|count_characters) eq 0`
                            ~$notFilledInText`
                          ~else`
                            ~$apiData['lifestyle']['hobbies']['FAV_VAC_DEST']`
                          ~/if`  
                    </span>
                    <span class="~if $bEditView && ($editApi.Lifestyle.FAV_VAC_DEST.value|count_characters) eq 0 || $editApi.Lifestyle.FAV_VAC_DEST.screenBit neq 1` disp-none ~/if` js-undSecMsg"><span class="disp_ib color5 f13" > Under Screening</span></span>
                    </div>
                  </div>
                  <!--end:div--> 
                  ~/if`
                 
                </div>
                ~if $bEditView`
                  <!--start:Edit Likes -->
                  <div class="pl30 ceditform" id="likesEditForm"><!---Edit Form--></div>
                  <!--end:Edit Likes -->
                ~/if`
              </div>
             </div>
              <!--end:She Likes--> 