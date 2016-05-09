<style>
.gtit_full{width:100%;font: 12px Arial,Helvetica,sans-serif;}
.gtit_brdr{border:1px solid #D3D3D3;}
.gtit_colr1{color:#777777;}
.gtit_btn{background-color:#A92E41;}
.gtit_btn a{color:#fff; text-decoration:none}
.gtit_full .pd7{ padding: 7px;}
.gtit_full .fl{float:left}
.gtit_full .b {font-weight: bold;}
.gtit_full .fr{float:right}
.gtit_full .pad6top {padding-top: 6px;}
.clearfixGt{*zoom:1}
.clearfixGt:before,
.clearfixGt:after{display:table;line-height:0;content:""}
.clearfixGt:after{clear:both}
</style>
<script type="text/javascript">
	function gotItCall()
	{
		var GotItBandPage = $('#GotItBandPage').val();
		var postData ={	'GotItBandPage': GotItBandPage };
		$.ajax({
		  url: "~sfConfig::get('app_site_url')`/common/gotItUpdate/",
		  type: "POST",
		  data: postData
		}).done(function(res){
                                $('#band').hide();
                        });
	}
</script>
<input type="hidden" name="GotItBandPage" id = "GotItBandPage" value="~$GotItBandPage`">
    	<!--start:My Jeevansathi-->
        <div class="gtit_full gtit_brdr" id="band">
        	<div class="clearfixGt  pd7">
            	<div class="fl gtit_colr1 b pad6top">
                        ~$GotItBandMessage`
                </div>
                <div class="fr">
                	<div class="gtit_btn pd7"><a href="#" id = "gotItButton" onClick="gotItCall();">Got it</a></div>
                </div>
            </div>
        </div>        
        <!--end:My Jeevansathi-->
