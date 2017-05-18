<div class="bg-white fullwid ccbrdb1 f13 fontlig color2 ">
  <div class="ccp1">
    <div class="fullwid scrollhid">
      <ul class="hor_list clearfix cclist1 pos-rel" style="width:16000px;" id="ccHorizontalTabsBar">
        ~foreach from=$contactCenterTabMapping key=k item=v`
          ~foreach from=$v["horizontalTabsArr"] key=kk item=vv name=horizontalTabsLoop`
          ~if $showIdfy && ($vv["HTabId"] eq 8 || $vv["HTabId"] eq 11)`
          <li  id="idfyDiv" class="idfyDiv"><span class="color5 f12 idfyText">Get details of users verified</span><i class="idfyIcon"></i></li>
          ~elseif $vv["HTabId"] eq 10`
          <li class="vishid">1</li>
          ~else`
           <li data-id="~$vv["HTabId"]`" data-infoId="~$vv["infoTypeID"]`" id="HorizontalTab~$vv["infoTypeID"]`" class="js-ccHorizontalLists cursp">~$vv["Hname"]`</li>
           ~/if`
          ~/foreach` 
          ~assign var=TabsArrLength value=$v["horizontalTabsArr"]|count`
          ~assign var=blankTabsCount value=3-$TabsArrLength`
          ~if $blankTabsCount`
            ~for $i = 1 to $blankTabsCount`
              <li class="vishid">~$i`</li>                  
            ~/for`  
          ~/if`    
        ~/foreach`    
          <li class="pos-abs bg5 cssline" style="bottom:0; height:2px;" id="horizontalActiveLine"></li>
      </ul>
    </div>
  </div>
</div> 