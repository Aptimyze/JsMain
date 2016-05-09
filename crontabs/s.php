<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

$lavesh=10;
$rawat=10.00;
$sadaf=344;
$alam=344.55;

echo "int by int \n";
echo $lavesh/$sadaf;
echo "int by float \n";
echo $lavesh/$alam;
echo "float by int \n";
echo $rawat/$sadaf;
echo "float by float \n";
echo $rawat/$alam;
?>

