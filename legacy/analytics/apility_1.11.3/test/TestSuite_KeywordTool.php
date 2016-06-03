<?php
	echo "<h2 style='color:blue;'>KeywordTool</h2>";
	require_once('apility.php');

// getKeywordVariations
	$seedKeyword1 = array('text' => "some text", 'type' => "Broad", 'isNegative' => false);
	$seedKeyword2 = array('text' => "other text", 'type' => "Phrase", 'isNegative' => true);
	$seedKeywords = array($seedKeyword1, $seedKeyword2);
	$keywordVariations = getKeywordVariations($seedKeywords, true, array("all"), array("all"));
	if ((is_array($keywordVariations)) && (!empty($keywordVariations))) echo "getKeywordVarations <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getKeywordVarations<br />\n";;

// getKeywordsFromSite
	$keywordsFromSite = getKeywordsFromSite("spiegel.de", false, array("all"), array("all"));
	if ((is_array($keywordsFromSite)) && (!empty($keywordsFromSite))) echo "getKeywordsFromSite <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getKeywordsFromSite<br />\n";;
?>