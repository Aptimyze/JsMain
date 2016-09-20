~if !$sf_request->getParameter("actiontocall")`
<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr> 
<td class=mediumblack height="2"> 
	<div align="center">
		<span class="class3">
			<a href="https://www.naukri.com"><br>Naukri.com</a> |
			<a href="~sfConfig::get('app_site_url')`/profile/faqs.php?checksum=~$sf_request->getAttribute('cid')`"> FAQs </a>| 
			<!--
			<a href="~sfConfig::get('app_site_url')`/profile/feedback.php?checksum=~$sf_request->getAttribute('cid')`">Feedback</a> | 
			-->
			<a href="~sfConfig::get('app_site_url')`/profile/site_map.php?checksum=~$sf_request->getAttribute('cid')`">Site Map</a> | 
			<a href="~sfConfig::get('app_site_url')`/profile/contact.php?checksum=~$sf_request->getAttribute('cid')`">Contact Us</a>
		</span>
		<br><br>
		Copyright &copy; 2004, JeevanSathi Internet Services. 
	</div>
</td>
</tr>
</table>
~/if`
