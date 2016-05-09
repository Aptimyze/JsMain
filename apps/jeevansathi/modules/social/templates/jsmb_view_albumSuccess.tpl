
<div class="backbtn fbld">~$sf_request->getAttribute('BREADCRUMB')|replace:'&lt;':'<'|replace:'&gt;':'>'|replace:'&amp;':'&'|replace:'&quot;':'"'|replace:'&#039;':'\''`
~if $LOGGEDIN`
    <a href="~$SITE_URL`/P/logout.php?mobile_logout=1" style="font: 12px arial; float: right; font-weight: bold; color: #0046C5; margin-right: 10px;">Logout</a>
    ~/if`
</div>
<div class="existing1 fbld">
	<Span class="fl">Album - ~$USERNAME`</Span>
	<span class="blk mar10pxright fr" >Photo ~$currentPicNumber` of ~$countPics`
	</span>
</div>

~if $titleMob || $keywordMob`
<div class="box">
~if $titleMob`
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Title - ~$titleMob` 
	~if $keywordMob`
	<br>
	~/if`
~/if`
~if $keywordMob`
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Keywords - ~$keywordMob`
~/if`
</div>
~/if`
<div class="clear">&nbsp;</div>


<div align="center" style="padding-top:5px;width:auto;overflow-x:auto">
	<table>
	<tbody>
	<tr>
		<td>&nbsp;</td>

		<td>
		<div style="padding-bottom:10px;text-align:center; font:13px Arial, Helvetica, sans-serif;">
			 <a href="~sfConfig::get('app_site_url')`/social/album?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&pg1=~$prev`&~$NAVIGATOR`&nav_type=~$NAV_TYPE`"><img src="~sfConfig::get('app_img_url')`/images/left-arrw.gif" style="vertical-align:bottom"></a>&nbsp;
			Photo ~$currentPicNumber` of ~$countPics`

			&nbsp;
				<a href="~sfConfig::get('app_site_url')`/social/album?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&pg1=~$next`&~$NAVIGATOR`&nav_type=~$NAV_TYPE`"><img src="~sfConfig::get('app_img_url')`/images/right-arrw.gif" style="vertical-align:bottom"></a>
		</div>
		</td>
		<td>&nbsp;</td>
	</tr>

	<tr>
		<td></td>
		<td>
			<img src="~$mob_img_url`"  border="0" align="absmiddle">
		</td>
	
		<td>
		</td>
	</tr>

	<tr>
		<td>&nbsp;</td>
		<td>
			<div style="padding-top:10px;text-align:center; font:13px Arial, Helvetica, sans-serif;">
                        <a href="~sfConfig::get('app_site_url')`/social/album?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&pg1=~$prev`&~$NAVIGATOR`&nav_type=~$NAV_TYPE`"><img src="~sfConfig::get('app_img_url')`/images/left-arrw.gif" style="vertical-align:bottom"></a>&nbsp;
                        Photo ~$currentPicNumber` of ~$countPics`

                        &nbsp;

                        <a href="~sfConfig::get('app_site_url')`/social/album?checksum=~$CHECKSUM`&profilechecksum=~$PROFILECHECKSUM`&pg1=~$next`&~$NAVIGATOR`&nav_type=~$NAV_TYPE`"><img src="~sfConfig::get('app_img_url')`/images/right-arrw.gif" style="vertical-align:bottom"></a>
			</div>

		</td>
		<td>&nbsp;</td>
	</tr>
	</tbody>	
	</table>
</div>

