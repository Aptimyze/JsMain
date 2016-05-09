	<div style="width:100%;background:none; border:0px;" class="seo_top_links ">
		<div style="background:none; border:0px;" class="seo_top_style">
			<div style="~if $LESS_WIDTH neq '1'`width:~if $levelObj->getPageSource() neq 'N'`786px~else`780px~/if`;~else`width:425px;~/if` margin:0 auto; text-align:center;">
				<div style="float:left;">
					~assign var=LevelOneBreadCrumb value=$breadCrumbObj->getLevelOneBreadCrumb()`
					~section name=foo loop=count($LevelOneBreadCrumb)`
					<a style="~$LevelOneBreadCrumb[foo][3]`" href="~$LevelOneBreadCrumb[foo][0]`" title="~$LevelOneBreadCrumb[foo][1]`">&nbsp;~$LevelOneBreadCrumb[foo][2]`&nbsp;</a>~if $smarty.section.foo.index lt count($LevelOneBreadCrumb)-1` | ~/if`
					~/section`
				</div>
				~if $NOMORE neq 'TRUE'`
				<ul style="width:auto; margin-top:-7px;float:left;" class="nav flowleft" id="nav">
					<li>
						<a class="arrowmore" style="float:left;" href="#"><div style="margin-top:3px;*margin-top:8px;">more</div></a>
						<ul style="position:absolute; z-index:1000; margin-top:27px; ~if $MORE_WIDTH`margin-left:-500px;width:558px;~else`margin-left:-207px ~/if`">
						~assign var=LevelOneDropDown value=$breadCrumbObj->getLevelOneDropDown()`
							~section name=foo loop=count($LevelOneDropDown)`
								<li><a class="daddy" style="~$LevelOneDropDown[foo][3]`" href="~$LevelOneDropDown[foo][0]`" title="~$LevelOneDropDown[foo][1]`">~$LevelOneDropDown[foo][2]`&nbsp;</a></li>
							~/section`	
						</ul>
					</li>
				</ul>
				~/if`
			</div>
		</div>
	</div>