<script>
$(document).ready(function() {
	$('body').css('background-color','#09090b');
} )
    function criticalLayerButtonsAction(clickAction,button) {
    			$("#CALButton"+button).attr('onclick','');
                var layerId= $("#CriticalActionlayerId").val();
                                   window.location = "/static/CALRedirection?layerR="+layerId+"&button="+button; 
                               
        }
            
</script>
<style>
.pad18Incomplete{padding:15% 0 8% 0;}

 @media (min-width: 280px) {
 	.image_incomplete{ width:80px; height:80px; margin-top: 4px; margin-left: 4px; z-index:3; position:relative; border-radius:100%;}
 }
@media (min-width: 321px) {
.image_incomplete{ width:80px; height:80px;  z-index:3;  border-radius:100%; }
}

.pdt15{
	padding-top:15%;
	}
</style>

<input type="hidden" id="CriticalActionlayerId" value="~$calObject.LAYERID`">
<div style="background-color: #09090b;">
  <div  class="posrel pad18Incomplete">

	<div class="br50p txtc" style='height:80px;'>
			~if $showPhoto eq '1'`
			~if $gender eq 'M'` 	
				<img id="profilepic" class="image_incomplete" src="~StaticPhotoUrls::noPhotoMaleJSMS`"> 
				~else`<img id="profilepic" class="image_incomplete" src="~StaticPhotoUrls::noPhotoFemaleJSMS`"> 
				~/if`
			~/if`
		</div>
		 
	</div>
	 
	<div class="txtc">	 
	<div class="fontlig white f18 pb10 color16">~$calObject.TITLE`</div>
	<div class="pad1 lh25 fontlig f14" style='color:#cccccc;'>~$calObject.TEXT`</div>
  </div>
  <!--start:div-->
  <div style='padding: 25px 0 8% 0;'>
	<div id='CALButtonB1' class="bg7 f18 white lh30 fullwid dispbl txtc lh50" onclick="criticalLayerButtonsAction('~$calObject.ACTION1`','B1');">~$calObject.BUTTON1`</div>
  </div>
  <!--end:div-->
  <div id='CALButtonB2' onclick="criticalLayerButtonsAction('~$calObject.ACTION2`','B2');" style='color:#cccccc;' class="pdt15 pb10 txtc white f14">~$calObject.BUTTON2`</div>
  </div>
