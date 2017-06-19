<?php

// This code demonstrates how to lookup the country by IP Address

include("geoip.inc");

$gi = geoip_open("/usr/local/share/GeoIP/GeoIP.dat",GEOIP_STANDARD);

echo geoip_country_code_by_addr($gi, "24.24.24.24") . "\t" .
     geoip_country_name_by_addr($gi, "24.24.24.24") . "\n<br>";
echo geoip_country_code_by_addr($gi, "202.131.147.189") . "\t" .
     geoip_country_name_by_addr($gi, "202.131.147.189") . "\n<br>";

geoip_close($gi);
?>
