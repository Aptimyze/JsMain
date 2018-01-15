~if $profile_score gt 349`
	~if $groupname eq 'new_google_structure'`
		<!-- Google Code for Paid Conversion Page -->
		<script language="JavaScript" type="text/javascript">
		<!--
		var google_conversion_id = 1039085133;
		var google_conversion_language = "en_US";
		var google_conversion_format = "3";
		var google_conversion_color = "ffffff";
		var google_conversion_label = "sWVhCInSjwEQzdy87wM";
		//-->
		</script>
		<script language="JavaScript" src="http://www.googleadservices.com/pagead/conversion.js">
		</script>
		<noscript>
		<img height="1" width="1" border="0" src="http://www.googleadservices.com/pagead/conversion/1039085133/?label=sWVhCInSjwEQzdy87wM&amp;guid=ON&amp;script=0"/>
		</noscript>
	~elseif $groupname eq 'New Google' or $groupname eq "google_custom"`
		<!-- Google Code for Paid Conversion Page -->
		<script language="JavaScript" type="text/javascript">
		<!--
		var google_conversion_id = 1056682264;
		var google_conversion_language = "en_US";
		var google_conversion_format = "3";
		var google_conversion_color = "ffffff";
		var google_conversion_label = "lead";
		//-->
		</script>
		<script language="JavaScript" src="http://www.googleadservices.com/pagead/conversion.js">
		</script>
		<noscript>
		<img height="1" width="1" border="0" src="http://www.googleadservices.com/pagead/conversion/1056682264/?label=lead&amp;guid=ON&amp;script=0"/>
		</noscript>
	~elseif $groupname eq 'Google_NRI'`
		<!-- Google Code for Paid Conversion Page -->
		<script language="JavaScript" type="text/javascript">
		<!--
		var google_conversion_id = 1067322789;
		var google_conversion_language = "en_US";
		var google_conversion_format = "3";
		var google_conversion_color = "ffffff";
		var google_conversion_label = "lead";
		//-->
		</script>
		<script language="JavaScript" src="http://www.googleadservices.com/pagead/conversion.js">
		</script>
		<noscript>
		<img height="1" width="1" border="0" src="http://www.googleadservices.com/pagead/conversion/1067322789/?label=lead&amp;guid=ON&amp;script=0"/>
		</noscript>
	~elseif $groupname eq 'Google NRI US'`
		<!-- Google Code for Paid Conversion Page -->
		<script language="JavaScript" type="text/javascript">
		<!--
		var google_conversion_id = 1046502896;
		var google_conversion_language = "en_US";
		var google_conversion_format = "3";
		var google_conversion_color = "ffffff";
		var google_conversion_label = "lead";
		//-->
		</script>
		<script language="JavaScript" src="http://www.googleadservices.com/pagead/conversion.js">
		</script>
		<noscript>
		<img height="1" width="1" border="0" src="http://www.googleadservices.com/pagead/conversion/1046502896/?label=lead&amp;guid=ON&amp;script=0"/>
		</noscript>
	~else`
		<!-- Google Code for Lead Conversion Page -->
		<script language="JavaScript" type="text/javascript">
		var google_conversion_id = 1056682264;
		var google_conversion_language = "en";
		var google_conversion_format = "1";
		var google_conversion_color = "ffffff";
		var google_conversion_label = "lead";
		</script>
		<script language="JavaScript" src="http://www.googleadservices.com/pagead/conversion.js">
		</script>
		<noscript>
			<img height="1" width="1" border="0" src="http://www.googleadservices.com/pagead/conversion/1056682264/?label=lead&amp;script=0"/>
		</noscript>
	~/if`
~/if`
~if $pixelcode`
~$pixelcode|decodevar`
~/if`
~if $pixelcodeRocketFuel`
~$pixelcodeRocketFuel|decodevar`
~/if`
<script language="JavaScript" type="text/javascript">

	~if $reg_comp_frm_ggl`
		var google_conversion_id = 1072672959;
		var google_conversion_language = "en_US";
		var google_conversion_format = "1";
		var google_conversion_color = "666666";
		if(1)
		{
			var google_conversion_value = 1;
		}
		var google_conversion_label = "lead";
	~elseif $reg_comp_frm_ggl_nri`
		var google_conversion_id = 1067322789;
		var google_conversion_language = "en_US";
		var google_conversion_format = "1";
		var google_conversion_color = "666666";
		if(1)
		{
			var google_conversion_value = 1;
		}
		var google_conversion_label = "lead";
	~/if`
</script>
	~if $reg_comp_frm_ggl`
<noscript>
		<img height=1 width=1 border=0 src="http://www.googleadservices.com/pagead/conversion/1072672959/?value=1&label=lead&script=0">
</noscript>
	~elseif $reg_comp_frm_ggl_nri`
<noscript>
	<img height=1 width=1 border=0 src="http://www.googleadservices.com/pagead/conversion/1067322789/?value=1&label=lead&script=0">
</noscript>
	~/if`
~if $reg_comp_frm_ggl or $reg_comp_frm_ggl_nri`
	<script language="JavaScript" src="http://www.googleadservices.com/pagead/conversion.js"></script>
~/if`
