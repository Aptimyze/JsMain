<style type="text/css">
.fl{float:left;}
.fr{float:right;}
.pos_abs1{position:absolute;}
.pos_rltv1{position:relative;}
.p_tl_01{top:0; left:0;}
.p_tr_01{top:0; right:0;}
.p_bl_01{bottom:0; left:0;}
.p_br_01{bottom:0; right:0;}
.sprte_callhelp{background:url(~sfConfig::get('app_img_url')`/profile/images/call_help_sprte.png) no-repeat;}
.main_form_cont{border:2px solid #f15e18; color:#ffffff;}
.h_w_61{height:6px; width:6px; font-size:2px;}
.h_w_51{height:5px; width:5px; font-size:2px;}
.d_t_l1{background-position:0 0; margin:-2px 0 0 -2px;}
.d_t_r1{background-position:-7px 0;margin:-2px -2px 0 0;}
.d_b_l1{background-position:0 -7px;margin:0 0 -2px -2px; _margin:0 0 -3px -2px;}
.d_b_r1{background-position:-7px -7px;margin:0 -2px -2px 0; _margin:0 -2px -3px 0;}
.b{font-weight:bold;}
.fst{ font-family:Arial, Helvetica, sans-serif;}
.call-left{ float:left;}
.call-left1{ font-size:14px; color:#666666;padding:15px 2px 3px 8px;width:209px;}
.call-left1a{ font-size:13px; color:#666666;padding:15px 5px 3px 8px;}
.call-left3{font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#cd3705;padding:12px 5px 3px 8px; font-weight:bold; line-height:20px; background-image:url(~sfConfig::get('app_img_url')`/profile/images/back.gif); background-repeat:no-repeat;}
</style>
<div id="floatMenu" style="display:none;z-index:1020;">
<IFRAME id="iframeshim"  src="" style="display: inline; z-index: -1; position: absolute; width: 298px; left: 0px; height: 132px; top: 10px;filter: progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0);" frameBorder="0" scrolling="no"></IFRAME>
	<div class="fl mt_10" style="width:298px;  background-color:#ffffff;background-image:url(/profile/images/cuv1.png);">
		<div class="main_form_cont pos_rltv1" style="width:295px;"> 
			<i class="d_t_l1 pos_abs1 h_w_61 sprte_callhelp"></i>
			<i class="d_t_r1 pos_abs1 h_w_61 sprte_callhelp p_tr_01"></i>
			<i class="d_b_r1 pos_abs1 h_w_61 sprte_callhelp p_br_01"></i>
			<i class="d_b_l1 pos_abs1 h_w_61 sprte_callhelp p_bl_01"></i>
			<div style="height:128px;color:#FFFFFF;width:298px">
				<div class="call-left">
					<div class="call-left1 b fst">~$CALL_HELP_MES`</div>
					<div class="call-left3">
					<strong style="color:#000000; font-size:12px;">Call us at: </strong><br />
						~if $IS_NRI neq 1`0120-4393500<br />18004196299 (Toll Free)
						~else`
						+91-120-4393500<br />
						~/if`
					</div>
				</div>
				<div style="float: right; margin: 2px; padding: 2px;">
				    <a href="javascript:close_help();"><img src="/profile/images/close.png"  border="0"/></a>
					
				</div>  
			</div>
		</div>
	</div>
</div>
<script>
var jquery_user;
if(typeof(fin)!='undefined')
	jquery_user=fin;
else
	jquery_user=$;
	
function close_help()
{
	jquery_user("#floatMenu").hide(1000);
	Set_Cookie( "close_help", 1,"", "/");
	
}
var height_help=0;
	
show_help('floatMenu');
~if $is_ie`
jquery_user(document).ready(function(){
			
			
			jquery_user(window).scroll(function () { 
				offset = jquery_user(document).scrollTop()+height_help+"px";
				jquery_user("#floatMenu").animate({top:offset},{duration:500,queue:false});
			});
		});
~/if`
function show_help(id_of_help)
{
	var de = top.document.documentElement;
        var w = window.innerWidth || self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
        var h = top.window.innerHeight || top.document.innerHeight || (de&&de.clientHeight) || document.body.clientHeight;
        var idofhelp=dID(id_of_help);
        var page_size=tb_getPageSize();
	page_size[1]=h;
        var width_help=page_size[0]-320;
	height_help=page_size[1]-176;
	if(top.document==document)
		height_help+=33;
        
        //alert(width_help+" "+height_help);
        idofhelp.style.display='block';
        ~if $is_ie`
			idofhelp.style.position='absolute';
		~else`
			idofhelp.style.position='fixed';
		~/if`	
        idofhelp.style.top=height_help+"px";
        idofhelp.style.left=width_help+"px";
        
}
</script>
