<!--start:header-->
~include_partial("inbox/JSPC/inboxHeader")`
<!--end:header--> 

<!--start:middle-->
<div class="bg-4">
  <div class="mainwid container pt30 pb30">
    <div class="bg-white erorr404p1">
		<div class="mauto pos-rel err404wid1">
        	<img src="~sfConfig::get('app_img_url')`/images/404error.jpg"/>
            <div class="pos-abs color5 fontrobbold err404pos2">
            	<div class="f40 txtr">500</div>
                <div class="f80 err404lh1 txtr err404m1">error</div>
                <div class="f15 pt10">Something went wrong</div>
                <div class="f15 txtc pt10 color11 cursp" id="refreshPage">Refresh</div>
            </div>               
        </div>
    </div>
  </div>
</div>
<!--end:middle--> 

<!--start:footer-->
~include_partial('global/JSPC/_jspcCommonFooter')`
<!--end:footer-->

<script>
$(document).ready(function() {
	$("#refreshPage").on("click",function(){
		location.reload();
	});
    if(typeof trackJsEventGA != 'undefined')
    trackJsEventGA('500-error','jspc','-');
});
</script>