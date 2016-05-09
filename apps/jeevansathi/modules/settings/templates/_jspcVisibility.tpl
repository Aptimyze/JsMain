      <!--start:profile visibility-->
      <div class="ProfileVisbility">
        <div class="clearfix f15 fontlig color2">
          <div id="equalheight" class="clearfix">
            <div class="fl setwid12">
              <div class="column1 hgt pos-rel" style="height: 190px;">
                <div class="setp8">
                  <p class="txtc">Allow my detailed profile to be viewed by all visitors. </p>                 
                </div>
                <div class="pos-abs fullwid txtc color5 setpod6">Recommended</div>
              </div>
              ~if $privacyValue eq 'A'`
              <div id="VisibleAll" onclick="ajaxPrivacy('A');" class="bg_pink lh51 colrw txtc applied1"><div class="colrw f15 fontlig">Applied</div></div>
              ~else`
              <div  id="VisibleAll" onclick="ajaxPrivacy('A');" class="bg_pink lh51 colrw txtc"><div class="colrw f15 fontlig cursp">Set</div></div>
              ~/if`
            </div>
            <div class="fl setwid12 setp9">
              <div class="column1  hgt pos-rel" style="height: 190px;">
                <div class="setp8">
                  <p class="txtc">Allow my detailed profile to be viewed only by registered users who pass my filters. </p>                  
                </div>
                <div class="pos-abs fullwid txtc color11 setpod6"><a href="/profile/dpp">Manage Filters</a></div>
              </div>
              
              ~if $privacyValue eq 'F'`
              <div id="VisibleCriteria" onclick="ajaxPrivacy('F');" class="bg_pink lh51 colrw txtc applied1"><div class="colrw f15 fontlig">Applied</div></div>
              ~else`
              <div id="VisibleCriteria" onclick="ajaxPrivacy('F');" class="bg_pink lh51 colrw txtc cursp"><div class="colrw f15 fontlig cursp">Set</div></div>
              ~/if`
            </div>
            <div class="fl setwid12">
              <div class="column1 hgt pos-rel">
                <div class="setp8">
                  <p class="txtc">Don't show my detailed profile or summary profile to any user, I will search and contact profiles.</p>
                  <p class="pt30 txtc pb30">Summary profile will also not viewable by any visitor</p>
                </div>
              </div>
              ~if $privacyValue eq 'C'`
              <div id="VisibleNone" onclick="ajaxPrivacy('C');" class="bg_pink lh51 colrw txtc applied1"><div class="colrw f15 fontlig">Applied</div></div>
              ~else`
              <div id="VisibleNone" onclick="ajaxPrivacy('C');" class="bg_pink lh51 colrw txtc"><div class="colrw f15 fontlig cursp">Set</div></div>
              ~/if`
            </div>
          </div>
        </div>
      </div>
      <!--end:profile visibility--> 
