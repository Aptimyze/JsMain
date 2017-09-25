<div class="perspective fullwid fullheight" id="perspective">
<div class="fullwid fullheight" id="pcontainer">
<div id="sContainer" class="posrel">
		<!-- header section -->
	<div class="fullwid bg1 posfixTop" id="searchHeader">
		<div class="pad5">
			<div class="fl wid10p pt4"><i id ="hamburgerIcon" class="mainsp baricon " hamburgermenu="1" dmove="left" dshow="" dhide="decide" dselect="" dependant="" dcallback="" dindexpos="1"></i></div>
			<div class="fl wid80p txtc color5  fontthin f19" id="totalCountId">~$heading`</div>
			<div class="fr wid10p">
				~if !$dontShowSorting`
				<a href="#" id="sortByDateRelDiv"><i class="mainsp doublearw"></i></a>
				~/if`
			</div>
			<div class="clr"></div>
		</div>
	</div>
	</div>
	</div>
		~include_component('static', 'newMobileSiteHamburger')`	
</div>
<script>
var d = new Date();
var result = "Results have changed since last time you searched. Kindly <a href='/search/topSearchBand?isMobile=Y&stime="+d.getTime()+"' class='color2' >perform your search again. </a>"   ;
addNoResDivs(result ,'#sContainer','#searchHeader');
goToSearch = function()
{
    var d = new Date();
    if(typeof ShowNextPage == "function")
    {
        ShowNextPage('/search/topSearchBand?isMobile=Y&stime='+d.getTime(),1);
    }
    else
    {
        window.location.href = '/search/topSearchBand?isMobile=Y&stime='+d.getTime();
    }
    return false;
}
</script>
