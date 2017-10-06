<!--start:header-->
 <div class="perspective" id="perspective">
<div class="pcontainer" id="pcontainer">
<div class="fullwid bg10 pad18 posrel">
	<div class="posrel ">
		<div style="position: absolute;left:1px;"><i id ="hamburgerIcon" class="mainsp baricon " hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1"></i></div>
    <div class="color5 fontthin f19 txtc">Browse By Community</div> </div>
</div>
<!--end:header--> 
<!--start:index-->
<div class="bg4 pt15 fontlig"> 
  <!--start:div-->
  ~$page="N"`
  ~foreach item=k from='a'|@range:'z'`
    ~$found=0`
    ~$tabs=null`
    ~$tabs2=null`
    ~$i=0`
    ~foreach from=$SEO_FOOTER["MTONGUE"] item=v name=seoFoot`
        ~if $k==($v[$page][1]|substr:0:1)|lower`  
            ~$tabs[$i]=$v[$page][0]`
            ~$tabs2[$i]=$v[$page][1]`
            ~$found=1`
            ~$i=$i+1`
        ~/if`
    ~/foreach`
    ~if $found==1`
        <div class="brdr1 pad18 clearfix">
          <div class="fl comH_brdr1 comH_wid1 txtc">
            <div class="color7 f20 comH_pad1">~$k`</div>
          </div>
            <div class="fl comH_list padl20">
              <ul>
                ~foreach item=j from=0|@range:$i`
                <li><a href="~$tabs[$j]`#brides" bind-slide="1" title="~$tabs2[$j]`">~$tabs2[$j]` ~if $tabs2[$j]`Matrimonial~/if`</a></li>
                ~$j=$j+1`
                ~/foreach`
              </ul>
            </div>
        </div>
    ~/if`
   ~/foreach` 
  <!--end:div--> 
</div>
<!--end:index-->
</div>
	~include_component('static', 'newMobileSiteHamburger')`	
</div>

