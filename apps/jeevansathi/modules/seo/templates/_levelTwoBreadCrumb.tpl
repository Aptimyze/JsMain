~if $breadCrumbObj->getLevelTwoBreadCrumb()`
	<div class="clr"></div>
	<div class="cat_bg">
	<table border="0" cellspacing="0" cellpadding="0" align="center" style="text-align:center; margin:0 auto;">
	  <tr>
	      <td style="text-align:center">
			<ul id="nav" style="width:auto; margin-left:5px;height:20px;overflow:hidden;">
				~assign var=LevelTwoBreadCrumb value=$breadCrumbObj->getLevelTwoBreadCrumb()`
				~foreach $LevelTwoBreadCrumb as $key=>$val`
				~if $val`
					<ul id="nav1" class="nav flowleft">
						<span class="mnuhding" style="font-weight:normal;">~$val[3][0]`:</span>
						<div style="float:left;height:20px;">
							~assign var="tab" value=0`
							~section name="tr1" start=0 loop=count($val[0])`
							~if $tab lt $val[4][0]`						
								<a   href="~$val[2][$tab]`" style="~$val[7][$tab]`" title="~$val[6][$tab]`" class="daddy_out">~$val[1][$tab]`</a>
							~/if`
							~assign var="tab" value=$tab+1`
							~/section`
						</div>
						<li>
						    <a href="#" class="arrowmain">&nbsp;</a>
						    <ul style="~$val[5][0]`">
						    	<span class="arrowbox">&nbsp;</span>
							~assign var="tab" value=0`
							<li><span class="flowleft" style="padding:5px 0 0 0;">~$val[3][0]`:</span></li>
							~section name="tr1" start=0 loop=count($val[0])`
								<li><a href="~$val[2][$tab]`"  style="~$val[7][$tab]`" title="~$val[6][$tab]`" class="daddy">~$val[1][$tab]`</a></li>
							~assign var="tab" value=$tab+1`
							~/section`		
						    </ul>
						</li>
					</ul>
				~/if`
				~/foreach`

			</ul>
			</td>
			</tr>
			</table>
	</div>
	<p class="clr_4"></p>
	<p class="clr_4"></p>
	<p class="clr_4"></p>
~/if`
