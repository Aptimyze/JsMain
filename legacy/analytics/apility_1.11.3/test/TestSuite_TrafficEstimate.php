<?php
	echo "<h2 style='color:blue;'>TrafficEstimates</h2>";
	require_once('apility.php');

// getNewKeywordEstimate
	$text = "being in love";
	$type = "Exact";
	$maxCpc = 1.0;
	$isNegative = false	;
	if (is_array(getNewKeywordEstimate($text, $type, $maxCpc, $isNegative))) echo "getNewKeywordEstimate <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getNewKeywordEstimate<br />\n";

// getNewKeywordListEstimate
	$keyword1 = array('text' => "Test".rand(0, 32768), 'type' => "Broad", 'maxCpc' => 0.10, 'isNegative' =>	false);
	$keyword2 = array('text' => "Test".rand(0, 32768), 'type' => "Broad", 'maxCpc' => 0.10, 'isNegative' =>	false);
	if (is_array(getNewKeywordListEstimate(array($keyword1, $keyword2)))) echo "getNewKeywordListEstimate <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getNewKeywordListEstimate<br />\n";
?>