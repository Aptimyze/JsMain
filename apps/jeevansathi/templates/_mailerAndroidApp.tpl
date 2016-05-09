<table border="0" cellspacing="0" cellpadding="0" width="93%" align="center" style="text-align:left">
	<tr>
		<td width="488" style="font-family:Arial; font-size:12px; color:#000000; padding:6px 0px 8px 11px; border:1px dashed #d9d9d9;" >
			<table border="0" align="left" cellspacing="0" cellpadding="0" style="max-width:400px;">
				<tr>
						~if $data.IOS.ICON eq 1`
							<td width="170" style="font-family:Arial; font-size:12px; color:#000000; padding:10px 0"> 
					
							Download our Free Apps
						~else`
							<td width="430" style="font-family:Arial; font-size:12px; color:#000000; padding:10px 0"> 
					
						  Get instant notifications. Download the Free <a href="~$mailerLinks['GOOGLE_PLAY_APP']`&~$data.ANDROID.TRACKING`" target="_blank" style="text-decoration:none; color:#0f529d;">Jeevansathi Android App</a>.</strong>
						~/if`
					</td>
				</tr>
			</table>
			<table width="130" align="left" border="0" cellspacing="0" cellpadding="0">
				<tr>
					~if $data.IOS.ICON eq 1`
						<td style="padding-right:10px"><a href="~$mailerLinks['I_TUNES_APP']`&~$data.IOS.TRACKING`" target="_blank"><img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/iTuneBtn.gif" width="130" height="38" align="left" vspace="0" hspace="0" border="0" alt="Get it on iTunes" /></a></td>
					~/if`
					<td><a href="~$mailerLinks['GOOGLE_PLAY_APP']`&~$data.ANDROID.TRACKING`" target="_blank"><img src="~sfConfig::get('app_img_url')`/images/symfonyMailer/googleBTn.gif" width="130" height="38" align="left" vspace="0" hspace="0" border="0" alt="Get it on Google Play" /></a></td>
					
				</tr>
			</table>
		</td>
	</tr>
</table>

