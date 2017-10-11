<style type="text/css">
	.mem_coma{background-position: -3px -238px;
	  width: 28px;
	  height: 44px;
	}
</style>
<!--start:overlay1-->
<div id="callOvrOne" style="display:none;">
	<div class="tapoverlay posfix"></div>
	<div id="callOvrOneInnerDiv" class="posrel txtc fontlig bg4 fullwid ~if $pageType neq 'membership'`js-rcbOverlay~/if`" style="z-index:110;">
		<div id="topHelpPhoneNumber" class="f22 ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` pt30"><a style="cursor:pointer; color:~if $data.device eq 'Android_app'`#8d1316~else`#d9475c~/if` !important;"href="tel:~$data.topHelp.value`">~$data.topHelp.phone_number`</a></div>
		<div id="topHelpCallText" class="f14 color13 pt15">~$data.topHelp.call_text`</div>
		~if $profileid`
		<div id="topHelpOrText" class="f13 color1 pad2">~$data.topHelp.or_text`</div>
		<div id="reqCallBack" style="cursor:pointer;"class="f18 ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` pb20">~$data.topHelp.request_callback`</div>
		~else`
		<div class="pb20"></div>
		~/if`
	</div>
</div>
<!--end:overlay1-->
<!--start:overlay2-->
<div id="callOvrTwo" style="display:none;">
	<div class="tapoverlay posfix"></div>
	<div id="callOvrTwoInnerDiv" class="posrel fontlig bg4 fullwid ~if $pageType neq 'membership'`js-rcbMsgOverlay~/if`" style="z-index:110;">
		<div class="pad19">
			<div class="f14 color13"><i class="mainsp mem_coma"></i>
				<span id="reqCallBackMessage"></span>				
			</div>
      <div id="closeOvr2" class="f14 txtr pt10 ~if $data.device eq 'Android_app'`~$data.device`_color2~else`color2~/if` cursp" >Close</div>
		</div>
	</div>
</div>
<!--end:overlay2-->
<script type="text/javascript">
  var rcbForCAL = '';
  $(document).ready(function(){

  $("#jsmsReqCallbackBtn").on("click",function(e){
		showRCBLayer(e);
	});

  ~if sfContext::getInstance()->getRequest()->getParameter('showRCBForCAL') eq '1'`
      $("#jsmsReqCallbackBtn").trigger('click');
  ~/if`

  $('.tapoverlay').on('click',popBrowserStack);
	$("#closeOvr2").on('click',popBrowserStack);
  
	$("#reqCallBack").on('click',function(e){
		e.preventDefault();
    $('#pageloader').addClass('simple grey image');
		$("#callOvrOne").hide();
		var paramStr = '~$data.topHelp.params`';
		paramStr = paramStr.replace(/amp;/g,'');
    if(rcbForCAL == ''){
        rcbForCAL = '~$from_source`';
    }
    ~if sfContext::getInstance()->getRequest()->getParameter('showRCBForCAL') eq '1'`
        rcbForCAL = 'RCB_CAL';
    ~/if`
		url ="/api/v3/membership/membershipDetails?" + paramStr + rcbForCAL;
    var rcbResponse = $('#reqCallBack').attr('data-rcbResponse');
    if(typeof rcbResponse != "undefined"){
      url += '&rcbResponse='+ rcbResponse;
    }
		$.ajax({
			type: 'POST',
			url: url,
			success:function(data){
				$('#pageloader').removeClass('simple grey image');
        if(data.responseStatusCode == "0"){
          
          $("#callOvrOne").hide();
          var pos = $(window).scrollTop() - $("#callOvrTwo").height() + $(window).innerHeight();
          $('.js-rcbMsgOverlay').css('bottom','-'+pos+'px');          
          $("#callOvrTwo").show();
          ~if $pageType eq 'membership'`
            $("#callOvrTwoInnerDiv").removeClass('posrel').addClass('posfix btmo');
          ~/if`
          var msg = data.message;
          $("#reqCallBackMessage").text(msg);
          
          if(typeof rcbResponse != "undefined"){
            $("#callDiv1").remove();
            //yes code
            $("#callDiv2").remove();
            $("<div class='rel_c bg4' id='callDiv3'><div class='f14 fontlig mainDiv2'>Thank you for showing interest in our plans. Our customer service executive will reach to you shortly.</div></div>").insertAfter("#idd3");     
            }
          }
        }
      });
    });
  });
  function showRCBLayer (e, callbackSource) {
      if(callbackSource){
        rcbForCAL = callbackSource;
      } else {
        rcbForCAL = '';
      }
      e.preventDefault();
      $("#callOvrOne").show();
      $("#callOvrTwo").hide();
      historyStoreObj.push(clearOverlay, "#overlay");
      //$(window).scrollTop($(window).scrollTop());
      ~if $pageType neq 'membership'`
      $('html, body, #mainContent').css({
        //'overflow': 'hidden',
        'height': '100%'
      });
      ~else`
      $('html, body, #mainContent').css({
        'overflow': 'hidden',
        'height': '100%'
      });
      $("#callOvrOneInnerDiv").removeClass('posrel').addClass('posfix btmo');
      $("#jsmsReqCallbackBtn").hide();
      ~/if`
      var pos = $(window).scrollTop() - $("#callOvrOne").height() + $(window).innerHeight();
      var pos2 = $(window).scrollTop() - $("#callOvrTwo").height() + $(window).innerHeight()
      $('.js-rcbOverlay').css('bottom', '-' + pos + 'px');
      $('.js-rcbMsgOverlay').css('bottom', '-' + pos2 + 'px');

      $("#mainContent").addClass('posrel');
      $('.tapoverlay,.js-rcbOverlay,.js-rcbMsgOverlay').on('wheel touchmove', function (event) {
        event.preventDefault();
        event.stopImmediatePropagation();
      });
    }

    function clearOverlay () {
      if ($('#callOvrOne').css('display') != 'none' || $('#callOvrTwo').css('display') != 'none') {
        $("#callOvrTwo").hide();
        $("#callOvrOne").hide();
        ~if $pageType eq 'membership'`
        $('html, body, #mainContent').css({
          'overflow': 'auto',
          'height': 'auto'
        });
        $("#jsmsReqCallbackBtn").show();
        ~/if`
        var rcbResponse = $('#reqCallBack').attr('data-rcbResponse');
        if(typeof rcbResponse != "undefined"){
          $('#reqCallBack').removeAttr('data-rcbResponse');  
          setTimeout(function () {
            $("#callDiv2").remove();
            $("#callDiv3").slideUp();
            setTimeout(function () {
              $("#callDiv3").remove();
            }, 300)
          }, 3000);
        }
        return true;
      }
      return false;
    }
</script>
