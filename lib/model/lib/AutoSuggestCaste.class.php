<?php
class AutoSuggestCaste {
  private $limit = 10;
	private $minChar = 1;
  public function Process($request) {
    header("Expires: " . gmdate('D,d M Y H:i:s', time()+(3600)) . " GMT");
    $suggestion = $request->getParameter('q');
		$designation = $request->getParameter('type');
		$suggestion = $this->check_for_valid_chars($suggestion);
		if ($designation && strlen($suggestion) > $this->minChar) {
			$fetchAutoSugData = new FetchAutoSugData($designation);
			if (isset($fetchAutoSugData)) {
				$resultset = $fetchAutoSugData->getAutoSugRecords($suggestion, $this->limit);
				if ($resultset){
					if($request->getParameter("rtype")=="json")
						echo json_encode($resultset);
					else
					 echo implode("\n", $resultset);
					
				}
			}
		}
   }
   private function check_for_valid_chars($value) {
		$pattern = "/^[a-zA-Z]/";
		if (preg_match($pattern, $value)) return addslashes(stripslashes($value));
		else die;
	}
}
?>
