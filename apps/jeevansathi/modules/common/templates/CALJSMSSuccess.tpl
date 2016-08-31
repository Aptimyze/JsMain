<script>
$(document).ready(function() {
	$('body').css('background-color','#09090b');
} )
    var CALButtonClicked=0;
    
        function validateUserName(name){
        if(!name)return false;
        
        var arr=name.split('');
        if(/^[a-zA-Z' .]*$/.test(name) == false)return false;
        return true;
     
    }
    function criticalLayerButtonsAction(clickAction,button) {
        if(CALButtonClicked)return;
        CALButtonClicked=1;
        var CALParams='';
        var layerId= $("#CriticalActionlayerId").val();
        if(layerId==9 && button=='B1')
                    {   
                        var newNameOfUser='',privacyShowName='';
                        newNameOfUser = ($("#nameInpCAL").val()).trim();
                        
                        if(!validateUserName(newNameOfUser))
                        {
                            showError();
                            CALButtonClicked=0;
                            return;
                        }
                        CALParams="&namePrivacy="+namePrivacy+"&newNameOfUser="+newNameOfUser;
                    }

        
                                   window.location = "/static/CALRedirection?layerR="+layerId+"&button="+button+CALParams; 
                               
        }
            
</script>
<style>
.pad18Incomplete{padding:5% 0 8% 0;}

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

~if $calObject.LAYERID !=9`
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
  <div id='CALButtonB2' onclick="criticalLayerButtonsAction('~$calObject.ACTION2`','B2');" style='color:#cccccc; padding-top: 12%;' class="pdt15 pb10 txtc white f14">~$calObject.BUTTON2`</div>
  </div>
  
  ~else`
      <script>
          
          var namePrivacy=~if $namePrivacy neq 'N'`'Y'~else`'N'~/if`;
          function switchColors(id1,id2){
              
              $(id1).css('background-color','#d9475c');
              $(id2).css('background-color','#C6C6C6');
          }
          function showError()
          {
              	$( "#validation_error" ).slideDown( "slow", function() {}).delay( 800 );
		$( "#validation_error" ).slideUp( "slow", function() {});

              
          }
          
      </script>
      <style>
          
   .darkBackgrnd {background-color:#282828; position:fixed; top:0; right:0; bottom:0; left:0;margin:0; padding:0; z-index:102;}
   .pad_new{padding:40px 20px 0px 20px}
   .pad_new2{padding:10px 20px 10px 20px}
   .mt30{margin-top:30px}
   .lh60{line-height:60px}
   .colr8A{color:#8A8A8A}
   .brdrRad2{border-radius:2px}
   .mlNeg2{margin-left:-2px}
   .bgBtnGrey{background-color:#C6C6C6}
   .pt35p{padding-top:15px}
   .hgt110{height: 110px;}
   
      </style>
      <div class="txtc pad12 white fullwid f13 posabs dispnone" id="validation_error"  style="top: 0px;background-color: rgba(102, 102, 102, 0.5);z-index:104;">Please provide a valid name.</div>

      <div class="darkBackgrnd">
	<div class="fontlig">
    	<div class="pad_new app_clrw f20 txtc">Provide Your Name</div>
        <div class="pad_new2 app_clrw f14 txtc ">~$calObject.TEXT`</div>
		<input id='nameInpCAL' value='~$nameOfUser`' type="text" class="bg4 lh60 fontthin mt30 f24 fullwid txtc" placeholder="Your name here">
        <div class="pt10 f15 fontlig fullwid txtc colr8A">This field will be screened</div>
        <div class="mt30 pad_new2 hgt110">
            <div id='CALPrivacy1' onclick="switchColors('#CALPrivacy1','#CALPrivacy2');$('#hideShowText').hide();namePrivacy='Y';" type="submit" class="dispibl f14 txtc fontlig wid49p brdrRad2 ~if $namePrivacy neq 'N'`bg7~else`bgBtnGrey~/if` lh40 app_clrw">Show my name to all</div>
            <div id='CALPrivacy2' onclick="switchColors('#CALPrivacy2','#CALPrivacy1');$('#hideShowText').show();namePrivacy='N';" type="submit" class="dispibl f14 txtc fontlig wid49p brdrRad2 ~if $namePrivacy neq 'N'`bgBtnGrey~else`bg7~/if` lh40 app_clrw mlNeg2">Don't show my name</div>
            <div id="hideShowText" style="display:none" class="pt10 f15 fontlig fullwid txtc colr8A">You will not be able to see names of other members.</div>
        </div>
        <div id="skipBtn" onclick="criticalLayerButtonsAction('~$calObject.ACTION2`','B2');" style='display: inline-block;position: relative;left: 50%;' class="f14 fontlig txtc app_clrw pt35p">~$calObject.BUTTON2`</div>
        <div onclick="criticalLayerButtonsAction('~$calObject.ACTION1`','B1');" type="submit" id="submitName" class="fullwid dispbl lh50 txtc f18 btmo posfix bg7 white">~$calObject.BUTTON1`</div>
    </div>
	
</div>
      
      ~/if`
