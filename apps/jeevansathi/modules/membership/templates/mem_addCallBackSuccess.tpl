<!--new css added -->
<style type="text/css">
.s-info-bar-mem{background:#d8d8d8;font-size:12px;color:#111;padding:10px 0px;}
.s-info-bar-mem:before, .s-info-bar-mem:after{display:table;line-height:0;content:""}
.s-info-bar-mem:after{clear:both}
.fntmem16{font-size:16px;}
.fntmem12{font-size:12px;}
.fntwe700{font-weight:bold; }
.blackclr{color:#000;}
.clor666{color:#666666;}
.clorgrey2{color:#333333;}
.membrdrbtm{border-bottom:1px solid #e1e1e1;}
.mempadcell{padding:20px 0px;}
.mempadcel2{padding:10px 0px 20px 0px;}
.mempadcel3{text-align:center; padding-bottom:20px;}
.mempadcel4{padding-bottom:5px;}
.mempadcel5{padding-bottom:20px;}
.lh30{line-height:30px;}
#memtypes ul{ margin:0px; padding:0px 0px 0px 15px;}
a.callcust-btn {background-color: #f16c01;background-image: -moz-linear-gradient(top, #fcad3d, #f16c01);background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#fcad3d), to(#f16c01));background-image: -webkit-linear-gradient(top, #fcad3d, #f16c01);background-image: -o-linear-gradient(top, #fcad3d, #f16c01);background-image: linear-gradient(to bottom, #fcad3d, #f16c01);background-repeat: repeat-x;color: #fff;font-size: 13px;font-weight: bold;text-shadow: none;padding: 10px 0;width: 100%;}
ol,ul,li {list-style: disc outside none;}
b, strong, .fwB {font-weight: normal !important;}
</style>

<script>
var festDurBanner=new Array();
~foreach from=$festDurBanner key=k item=v`
festDurBanner["~$k`"]="~$v`";       
~/foreach`
</script>

<div id="main" class="clearfix">
	<div id="maincomponent">
        <!--end:header-->
		<div>
			<!-- start:Sub Title -->
			<section class="s-info-bar">
				<div class="pgwrapper clearfix">
					<div class="pull-left" style="padding-top: 7px;">Call Request Accepted</div>
                     <div class="pull-right">
                        <a href="~$referer`" class="btn pre-next-btn wid100">Go Back</a>
                     </div>
                    </form> 
				</div>
			</section>      
            <!-- end:Sub Title -->
            <section class="mempadcell">
                    <div class="pgwrapper fntmem12">
                        <div class="blackclr">
                            Dear ~$username`,
                            <br><br><p>Thank you for showing interest in our services. Our customer service executive will contact you on your mobile number (~$mobNumber`) within a day.</p>
                            <br>
                            Regards
                            <br>
                            Team Jeevansathi
                        </div> 
                    </div>
            </section>
        </div>
	</div>
</div>
